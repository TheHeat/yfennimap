<?php

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\FacebookPermissionException;
use Facebook\FacebookClientException;
use Facebook\FacebookOtherException;

	global $wpdb;

	$post_id = $post->ID;

//check if this is existing content
if(get_field('field_537ccb499a819', $post_id, true)):

	// if it is save the url against the fb_post_url wp custom field for display 
	$existing_content_url = (get_field('field_537ccb9c9a81a', $post_id, true));

	$fb_url = $existing_content_url;

else:

	// WP Variables
	$media = get_post_meta($post_id, 'media_type', true);
	$content = array();

	$content['title'] = get_the_title();;
	$content['description'] = get_field('field_5362ad1d9bf84', $post_id, true);

	//FB variables 
	$token = get_post_meta($post_id, 'user_fb_token', true);

	if ( $token == null) $token = page_token;
	
	
	switch ($media){

		case ('gallery'): 
			$edge = 'albums';
			$content['privacy'] = 'public';
		break;

		case ('video'):
			$edge = 'videos';

		case ('image'): 
			if ($media == 'image') $edge = 'photos';

			$attachment = get_field('field_5362addb9bf86', $post_id, true)[0];
			$attachment_filepath = get_attached_file( $attachment['file']['id'], true );

			$content['source'] = new CURLFile( $attachment_filepath);
		break;

		default: 
			$edge = 'feed'; 
			$content['url'] = get_post_meta($post_id, 'link', true);
		break;
	}

	$fb_object = fb_post_on_page($token, $edge, $content);

	// echo '<pre>';
	// 	print_r($fb_object);
	// echo '</pre>';
	//save facebook bject against post
	add_post_meta( $post_id, 'new_fb_object', $fb_object);

	//save url of facebook post
	$fb_media = new FB_Media($post->ID);

	$fb_url = $fb_media->get_url();

endif;

	add_post_meta( $post_id, 'fb_post_url', $fb_url );
?>