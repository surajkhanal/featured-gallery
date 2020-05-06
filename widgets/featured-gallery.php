<?php
/**
 * Elementor featured_gallery Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_featured_gallery_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve featured_gallery widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'featured_gallery';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve featured_gallery widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Featured Gallery', 'cdx' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve featured_gallery widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-gallery';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the featured_gallery widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Register featured_gallery widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'cdx' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->end_controls_section();

	}

	/**
	 * Render featured_gallery widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$featured_gallery = new \WP_Query(['post_type' => 'featured_gallery']);

        ?>
        <div class="swiper-container swiper-container-h">
            <div class="swiper-wrapper">
				<?php while($featured_gallery->have_posts()): $featured_gallery->the_post(); ?>
				<div class="swiper-slide">
					<?php $images = cdx_featured_gallery(get_the_ID()); 
					
					?>
					<?php if(is_array($images) && count($images) > 0): ?>
					<div class="swiper-container swiper-container-v">
						<div class="swiper-wrapper">
							<?php foreach($images as $image): 
								$image = json_decode($image);
								?>
							<div class="swiper-slide">
								<figure>
									<img src="<?php echo esc_url($image->url); ?>"></div>
									<?php if(!empty($image->description)): ?>
									<figcaption class="slider-caption">
										<?php //echo esc_html($image->description); ?>
									</figcaption>
									<?php endif; ?>
								</figure>
							<?php endforeach; ?>
						</div>
						<!-- Add Arrows -->
						<div class="swiper-button-next swiper-button-white"></div>
						<div class="swiper-button-prev swiper-button-white"></div>
						<div class="swiper-pagination swiper-pagination-v"></div>
					</div>
					
					<div class="swiper-container gallery-thumbs">
						<div class="swiper-wrapper">
							<?php foreach($images as $image): 
								$image = json_decode($image);?>
							<div class="swiper-slide" style="background-image:url('<?php echo esc_url($image->url); ?>')"></div>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<?php endwhile; ?>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-h"></div>
        </div>
        
        <?php

	}

}