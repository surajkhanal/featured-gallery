<?php 
// Register Custom Post Type
function cdx_cpt_featured_gallery() {

	$labels = array(
		'name'                  => _x( 'Featured Gallery', 'Post Type General Name', 'cdx' ),
		'singular_name'         => _x( 'Featured Gallery', 'Post Type Singular Name', 'cdx' ),
		'menu_name'             => __( 'Featured Gallery', 'cdx' ),
		'name_admin_bar'        => __( 'Featured Gallery', 'cdx' ),
		'archives'              => __( 'Featured Gallery Archives', 'cdx' ),
		'attributes'            => __( 'Featured Gallery Attributes', 'cdx' ),
		'parent_item_colon'     => __( 'Parent Gallery:', 'cdx' ),
		'all_items'             => __( 'All Featured Gallery', 'cdx' ),
		'add_new_item'          => __( 'Add New Gallery', 'cdx' ),
		'add_new'               => __( 'Add New', 'cdx' ),
		'new_item'              => __( 'New Gallery Item', 'cdx' ),
		'edit_item'             => __( 'Edit Gallery Item', 'cdx' ),
		'update_item'           => __( 'Update Gallery', 'cdx' ),
		'view_item'             => __( 'View Gallery', 'cdx' ),
		'view_items'            => __( 'View Gallery', 'cdx' ),
		'search_items'          => __( 'Search Gallery', 'cdx' ),
		'not_found'             => __( 'Not found', 'cdx' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'cdx' ),
		'featured_image'        => __( 'Featured Image', 'cdx' ),
		'set_featured_image'    => __( 'Set featured image', 'cdx' ),
		'remove_featured_image' => __( 'Remove featured image', 'cdx' ),
		'use_featured_image'    => __( 'Use as featured image', 'cdx' ),
		'insert_into_item'      => __( 'Insert into Gallery', 'cdx' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'cdx' ),
		'items_list'            => __( 'Media list', 'cdx' ),
		'items_list_navigation' => __( 'Items list navigation', 'cdx' ),
		'filter_items_list'     => __( 'Filter gallery list', 'cdx' ),
	);
	$args = array(
		'label'                 => __( 'Featured Gallery', 'cdx' ),
		'description'           => __( '', 'cdx' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ),
		// 'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-welcome-write-blog',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'rewrite'               => array('slug' => 'multimedia/visual-story'),
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'featured_gallery', $args );

}
add_action( 'init', 'cdx_cpt_featured_gallery', 0 );

// add custom column
add_filter( 'manage_featured_gallery_posts_columns', 'featured_gallery_columns' );
function featured_gallery_columns($columns) {
    $columns['shortcode'] = __( 'Shortcode', 'cdx' );
    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_featured_gallery_posts_custom_column' , 'custom_featured_gallery_column', 10, 2 );
function custom_featured_gallery_column( $column, $post_id ) {
	switch ( $column ) {
		case 'shortcode' :
			echo '[featured_gallery id="'. $post_id . '"]'; 
			break;
	}
}
?>