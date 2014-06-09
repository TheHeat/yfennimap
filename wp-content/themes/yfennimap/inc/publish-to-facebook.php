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

	// WP Variables
	$media = get_post_meta($post_id, 'media_type', true);
	$content = array();

	$content['title'] = get_the_title();;
	$content['description'] = get_post_meta($post_id, 'description', true);

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

		case ('pictures'): 
			if ($media == 'pictures') $edge = 'photos';

			echo $edge;

			$attachment = get_field('field_5362addb9bf86', $post_id, true)[0];

			$attachment_title = $attachment['file']['title'];
			$attachment_filepath = get_attached_file( $attachment['file']['id'], true );
			echo $attachment_filepath;
			$attachment_mimetype = $attachment['file']['mime_type'];

			$content['source'] = new CURLFile( $attachment_filepath, $attachment_mimetype, $attachment_title);
		break;

		default: 
			$edge = 'feed'; 
			$content['url'] = get_post_meta($post_id, 'link', true);
		break;
	}

	$fb_object = fb_post_on_page($token, $edge, $content);

	add_post_meta( $post_id, 'new_fb_object', $fb_object);

?>