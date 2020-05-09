<?php 

/**
 * Plugin Name: Featured Gallery
 * Description: Featured Gallery support
 * Plugin URI: https://github.com/featured-gallery
 * Version: 1.0.3
 * Author: codersuraz
 * Author URI: http://facebook.com/suraj.khanal.311
 * Text Domain: cdx
 * 
 * Copyright 2020  Codersuraz  (email : codersuraz@gmail.com)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class FeaturedGalleryElement {
    /**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.2';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
    const MINIMUM_PHP_VERSION = '7.0';
    
    /**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var FeaturedGalleryElement The single instance of the class.
	 */
    private static $_instance = null;

    /**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return FeaturedGalleryElement An instance of the class.
	 */
    public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}
 

    /**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}
    
    /**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'featured-gallery-extension' );

    }
    
    /**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
    public function init() {
        require_once( 'inc/cpt-featured-gallery.php' );
        require_once( 'inc/featured-meta.php' );
        require_once( 'inc/th-udate.php' );
        require_once( 'inc/cdx-gallery-meta.php' );
        require_once( 'inc/featured-gallery-shortcode.php' );

        new CDXGalleryField(array('featured_gallery'));
        
        if ( is_admin() ) {
            new THUpdater( __FILE__, 'surajkhanal', "featured-gallery" );
        }

        // Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

        add_action('wp_enqueue_scripts', array($this, 'register_assets'));
        add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );
        add_filter( "the_content", array($this, "get_custom_post_type_content") );
        
    }

    public function get_custom_post_type_content($content) {
        global $post;
   
        if ($post->post_type == 'featured_gallery') {
            $images = cdx_featured_gallery($post->ID);
            foreach ($images as $value) {
                $image = json_decode($value);
                $imgTag = '<figure class="featured_gallery_img"><img src="'.$image->url.'"><figcaption>'.($image->description).'</figcaption></figure>';
                $content .= $imgTag;
            }
        }

        return $content;
   }
  

    /**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-test-extension' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }
    
    /**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		// Include Widget files
        require_once( __DIR__ . '/widgets/featured-gallery.php' );
        
		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_featured_gallery_Widget() );

	}

    public function register_assets() {
      wp_enqueue_style('cdx-slider-style', plugin_dir_url(__FILE__) . '/assets/css/swiper.min.css', array(), null, 'all');
      wp_enqueue_style('cdx-featured-style', plugin_dir_url(__FILE__) . '/assets/css/cdx-featured-gallery.css', array(), null, 'all');
      wp_enqueue_script('cdx-slider-script', plugin_dir_url(__FILE__) . '/assets/js/swiper.min.js', array('jquery'), '1.0.0', true);
      wp_enqueue_script('cdx-main', plugin_dir_url(__FILE__) . '/assets/js/cdx-main.js', array('jquery'), '1.0.0', true);
    }
 
 }
  
 FeaturedGalleryElement::instance();
?>