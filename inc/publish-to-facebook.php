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
use Facebook\GraphSessionInfo;

function publish_to_facebook($post_id, $token){

	// WP Variables
	$media = get_post_meta($post_id, 'media_type', true);
	$content = array();

	$content['title'] = get_the_title();;
	$content['description'] = get_field('field_5362ad1d9bf84', $post_id, true);
	
	//If the user selected video but added a link rather than a file, change the media type to link
	$link_content = get_post_meta($post_id, 'link', true);
	if ( $media == 'video' && $link_content != '') $media = 'link';
	
	switch ($media){

		case ('gallery'): 
			$edge = 'albums';
			$content['privacy'] = 'public';
		break;

		case ('video'):
			$edge = 'videos';

		break;

		case ('image'): 
			if ($media == 'image') $edge = 'photos';

			// $attachment = get_field('field_5362addb9bf86', $post_id, true)[0];

			// $attachment_filepath = get_attached_file( $attachment['file']['id'], true );
			$attachment_filepath = get_attached_file( get_post_thumbnail_id( $post_id ), true );

			$content['source'] = new CURLFile( $attachment_filepath);
		break;

		default: 
			$edge = 'feed'; 
			$content['url'] = get_post_meta($post_id, 'link', true);
		break;
	}


	$response = fb_post_on_page($token, $edge, $content);

	if(is_array($response) && is_sset($response['error'])){
		return array('error' => 'Error occurred when posting to Facebook. Please try logging out and logging in again, giving the app permission to post to the public');	
	}
	$fb_object['fb_object_id'] = $response->getProperty('id');

	if($fb_object['fb_object_id']){
		// The post was successful. Make a record of its object ID
		add_post_meta( $post_id, 'new_fb_object', $fb_object['fb_object_id']);

		// Get the url of facebook post
		switch ($media) {
			
			case 'text':
			case 'link':
				$id_array = explode ( '_' , $fb_object['fb_object_id'] );
				$fbid = $id_array[1];
				$userid = $id_array[0];

				$fb_url = 'https://www.facebook.com/permalink.php?story_fbid='.$fbid.'&id='.$userid;
				break;
			
			case 'video':
				$fb_url = $response->getProperty('link');
				break;
			
			case 'image':
				
				$id = $response->getProperty('post_id');
				$id_array = explode ( '_' , $id );
				$fbid = $id_array[1];
				$userid = $id_array[0];

				$fb_url = 'https://www.facebook.com/permalink.php?story_fbid='.$fbid.'&id='.$userid;
				break;
			
			case 'album':
				$fb_url = $response->getProperty('link');
				break;
			
			default:
				# nothing here...
		}
		add_post_meta( $post_id, 'fb_post_url', $fb_url );
	}
	
	// Return either the object id, if successful, or the error or not
	return $fb_object;
}
?>