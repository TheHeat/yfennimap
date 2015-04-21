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

/* AJAX Facebook Request Handling */

add_action('wp_ajax_ajax_post_to_facebook', 'ajax_post_to_facebook');
add_action('wp_ajax_nopriv_ajax_post_to_facebook', 'ajax_post_to_facebook');

function ajax_post_to_facebook(){

	// Validate nonce. Return an error if it failed
	if( check_ajax_referer( 'media-form', 'nonce', true ) == -1 ){
		wp_send_json(array( 'error' => 'Invalid nonce. No funny business please.'));
	}
	
	// Check that we've been sent a token. If we have, convert it into a session, otherwise die.
	if($_REQUEST['token']){
		$session = new FacebookSession( $_POST['token'] );	
	}
	else{
		wp_send_json(array( 'error' => 'No Facebook token sent'));
	}

	// Post a media file if there is one
	if( $_REQUEST['mediaType'] == 'image'){
		$image_id = media_handle_upload( 'async-upload', 0 );
		
		//Die if the we get a WP error
		if ( is_wp_error($image_id) ){
			wp_send_json(array( 'error' => 'Error adding media to WordPress'));
		}	
	}
	

	// An expired token. We can delete this when we're done testing
	// $session = new FacebookSession( 'CAAWdu4OUTpgBAE6b9rD0QwoB9ivcpEt8x951uRjoUZC5D6TidRZAMv5eUgMwpISG2lm9VvJbalkpvrs5XRxNIa0n91YcaxoFJP98OqQMT5dIQNZBy9qgoxXc0XeqIw41dx8QG7O6bdt11EGZASN6P7PwtCZAESN65ej2qI8AVWSXpp0dZBDRj02Y7yXAXQqYMZBKXEPX8vdTzZADDktECxvCpo7o1FsEyNcZD' );
	
	// Check that token is valid
	// Create a request object
	$request = new FacebookRequest( $session, 'GET', '/me' );

	// Execute the request
	try {
	    $response = $request->execute();

	    // We don't need to do anything with this response, this is simply to check that the user is logged in
	    // Technically we could do this in one request with the POST to Facebook; however, we do it here
	    // So that the user (or spammer) can't create a pin unless they're logged in to Facebook

	    // Get response
	    // $graphObject = $response->getGraphObject();

	} catch (Exception $e) {
	    wp_send_json(array( 'error' => $e->getMessage() ));
	}

	// echo 'wow';
	// die();

	// Create an array with the data that we need to make a pin
	$pin_data = array(
		'content' => $_REQUEST['content'],
		'link' => $_REQUEST['link'],
		'content' => $_REQUEST['content'],
		'year' => $_REQUEST['year'],
		'mediaType' => $_REQUEST['mediaType'],
		'lat' => $_REQUEST['lat'],
		'lng' => $_REQUEST['lng'],
	);

	// We didn't get kicked out, so the user's session is valid. Nice one, create a pin. The function returns the id of the pin that was created
	$post_id = create_pin($pin_data, $image_id);

	// Execute post to Facebook
	$output = publish_to_facebook($post_id, $_REQUEST['token']);
	
	// For testing only - deliberate breakage
	// $output = publish_to_facebook($post_id, 'CAAWdu4OUTpgBAE6b9rD0QwoB9ivcpEt8x951uRjoUZC5D6TidRZAMv5eUgMwpISG2lm9VvJbalkpvrs5XRxNIa0n91YcaxoFJP98OqQMT5dIQNZBy9qgoxXc0XeqIw41dx8QG7O6bdt11EGZASN6P7PwtCZAESN65ej2qI8AVWSXpp0dZBDRj02Y7yXAXQqYMZBKXEPX8vdTzZADDktECxvCpo7o1FsEyNcZD');

	// $output = array(
	// 	'result' => $output
	// );
	// wp_send_json( $_POST['body'] );
	wp_send_json( $output );
}

/* AJAX Facebook Request Handling */

add_action('wp_ajax_yfenni_post_media', 'yfenni_post_media');
add_action('wp_ajax_nopriv_yfenni_post_media', 'yfenni_post_media');

function yfenni_post_media(){

	// Validate nonce
	check_ajax_referer( 'media-form', 'nonce', true );

	// Post media
	$id = media_handle_upload( 'async-upload', 0 );

	echo $id;
	// Return post ID
	// wp_send_json($id);

	die();
}