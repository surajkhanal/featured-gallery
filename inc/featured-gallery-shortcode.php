<?php

add_shortcode('featured_gallery', 'featured_gallery_shortcode');

function featured_gallery_shortcode($atts = []) {
    $post_id = $atts['id'];
    $images = cdx_featured_gallery($post_id);
    
    $output = '<div class="cdx_gallery">';
    $output .= '</div>';

    return $output;
}
?>