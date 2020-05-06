<?php 

function cdx_custom_meta() {
    add_meta_box( 'cdx_meta', __( 'Featured Posts', 'cdx' ), 'cdx_meta_callback', 'post' );
}

function cdx_meta_callback( $post ) {
    $featured = get_post_meta( $post->ID, 'featured_meta', true);
    $featured_hide_thumb = get_post_meta( $post->ID, 'featured_hide_thumb', true);
    $featured_hide_excerpt = get_post_meta( $post->ID, 'featured_hide_excerpt', true);
?>
	<p>
    <div class="cdx-row-content">
        <?php wp_nonce_field('cdx_nonce', 'cdx_nonce'); ?>
        <label for="featured_meta">
            <input type="checkbox" name="featured_meta" id="featured_meta" value="yes" <?php if ( isset ( $featured ) ) checked( $featured, 'yes' ); ?> />
            <?php _e( 'Featured this post', 'cdx' ); ?>
        </label>
        <p></p>
        <label for="featured_hide_thumb">
            <input type="checkbox" name="featured_hide_thumb" id="featured_hide_thumb" value="yes" <?php if ( isset ( $featured_hide_thumb ) ) checked( $featured_hide_thumb, 'yes' ); ?> />
                <?php _e( 'Hide Thumb', 'cdx' ); ?>
        </label>
        <p></p>
        <label for="featured_hide_excerpt">
            <input type="checkbox" name="featured_hide_excerpt" id="featured_hide_excerpt" value="yes" <?php if ( isset ( $featured_hide_excerpt ) ) checked( $featured_hide_excerpt, 'yes' ); ?> />
                <?php _e( 'Hide Summery', 'cdx' ); ?>
        </label>
    </div>
</p>
 
    <?php
}
add_action( 'add_meta_boxes', 'cdx_custom_meta' );

/* Saves the custom meta input
 */
function cdx_meta_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'cdx_nonce' ] ) && wp_verify_nonce( $_POST[ 'cdx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and saves
    if( isset( $_POST[ 'featured_meta' ] ) ) {
        update_post_meta( $post_id, 'featured_meta', 'yes' );
    } else {
        update_post_meta( $post_id, 'featured_meta', '' );
    }

    if( isset( $_POST[ 'featured_hide_thumb' ] ) ) {
        update_post_meta( $post_id, 'featured_hide_thumb', 'yes' );
    } else {
        update_post_meta( $post_id, 'featured_hide_thumb', '' );
    }

    if( isset( $_POST[ 'featured_hide_excerpt' ] ) ) {
        update_post_meta( $post_id, 'featured_hide_excerpt', 'yes' );
    } else {
        update_post_meta( $post_id, 'featured_hide_excerpt', '' );
    }
 
}
add_action( 'save_post', 'cdx_meta_save' );