<?php
global $post;
if ( is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent != 0) ) {
	wp_redirect(esc_url(get_permalink($post->post_parent), 301));
	exit;
} elseif ( is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent < 1) ) {
	wp_redirect(site_url(), 302);
	exit;       
}
?>