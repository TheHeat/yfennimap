<?php
	
	global $wpdb;
	$post_id = $post->ID;

	// WP Variables
	$media = get_post_meta($post_id, 'media_type', true);
	$content = array();

	$content['title'] = get_the_title();;
	$content['description'] = get_post_meta($post_id, 'description', true);

	//FB variables
	$token = get_post_meta($post_id, 'user_fb_token', true);
	
	switch ($media){
		case ('Image'): 
			$edge = 'photos';
			$attachment_url = wp_get_attachment_url( get_post_meta($post_id, 'media', true) );
			$content['url'] = $attachment_url; 
		break;

		case ('gallery'): 
			$edge = 'albums';
			$content['privacy'] = 'public';
		break;

		default: 
			$edge = 'feed'; 
			$content['url'] = get_post_meta($post_id, 'link', true);
		break;
	}

	$fb_object = fb_post_on_page($token, $edge, $content);

	add_post_meta( $post_id, 'new_fb_object', $fb_object);

?>