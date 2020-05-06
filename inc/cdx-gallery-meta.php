<?php 

/**
 * Gallery Meta Field For post types
 * 
 * @since 1.0.0
 * @author codersuraz
 */
class CDXGalleryField
{
    private $post_types = array();

    public function __construct($post_types = array('post', 'page')) {
        $this->post_types = $post_types;

        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Enqueue assets
     * 
     * @param $hook - current navigated page
     */
    public function enqueue_scripts($hook) {
        if ('post.php' != $hook && 'post-edit.php' != $hook && 'post-new.php' != $hook)
            return;
        wp_enqueue_style('cdx_styles', plugin_dir_url(__DIR__) . '/assets/css/cdx-gallery.css', array(), '1.0.0', 'all');
        wp_enqueue_script('cdx_script', plugin_dir_url(__DIR__) . '/assets/js/cdx.js', array('jquery'));
    }

    /**
     * Adds the gallery meta box.
     * 
     * @param string $post_type
     */
    public function add_meta_box($post_type) {

        if (in_array($post_type, $this->post_types))
        {
            add_meta_box(
                    'cdx_multi_img_meta_box'
                    , __('Add Gallery Images', 'cdx')
                    , array($this, 'render_meta_box_content')
                    , $post_type
                    , 'advanced'
                    , 'high'
            );
        }
    }

    /**
     * Save the images when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_meta($post_id) {
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
        // check_admin_referer('cdx_gallery_nonce', 'cdx_gallery_nonce_field');

        // Check if our nonce is set.
        if (!isset($_POST['cdx_gallery_nonce_field']))
            return $post_id;

        $nonce = $_POST['cdx_gallery_nonce_field'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'cdx_gallery_nonce'))
            return $post_id;

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        
        // Check the user's permissions.
        if ('page' == $_POST['post_type'])
        {

            if (!current_user_can('edit_page', $post_id))
                return $post_id;
        } else {

            if (!current_user_can('edit_post', $post_id))
                return $post_id;
        }

        $added_images = $_POST['cdx_img'];
        $new_images = array();

        if( !empty($added_images) ) {
            foreach($added_images as $image) {
                $new_images[] = $image;
            }
        }

        update_post_meta($post_id, 'cdx_gallery', wp_json_encode($new_images, JSON_UNESCAPED_UNICODE));

    }

    /**
     * Render Gallery Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content($post) {
        wp_nonce_field('cdx_gallery_nonce', 'cdx_gallery_nonce_field');

        $imagesRaw = get_post_meta($post->ID, 'cdx_gallery', true);
        $images = json_decode($imagesRaw);
        ?>
            <div class="cdx-gallery-block">
                <div class="actions">
                    <button type="button" class="btn btn-add-images" >Add Images</button>
                </div>

                <?php if(empty($images) || count($images) == 0): ?>
                    <div class="no__img">Please add new images.</div>   
                <?php endif; ?>
                
                <div class="img__grid">
                    <?php if(!empty($images)): ?>
                    <?php foreach($images as $image):
                        $image = json_decode($image); ?>
                        <div class="img-block" id="img-<?php echo absint($image->id); ?>">
                            <button type="button" class="btn-remove" onclick="removeImg(<?php echo absint($image->id); ?>)"><i class="dashicons dashicons-trash"></i></button>
                            <input type="hidden" name="cdx_img[]" value='<?php echo wp_json_encode($image); ?>'>
                            <div class="soft-wrap">
                                <img src="<?php echo esc_url($image->url); ?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php
    }
}



function cdx_featured_gallery($post_id) {
    $images = get_post_meta($post_id, 'cdx_gallery', true);
    if( !empty($images) ) {
        return json_decode($images);
    } 
    return array();
}

?>