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

/* AJAX Request Handling */

add_action('wp_ajax_ajax_post_to_facebook', 'ajax_post_to_facebook');
add_action('wp_ajax_nopriv_ajax_post_to_facebook', 'ajax_post_to_facebook');

function ajax_post_to_facebook(){

	// Check that we've been sent a token. If we have, convert it into a session, otherwise die.
	if($_POST['token']){
		$session = new FacebookSession( $_POST['token'] );	
	}
	else{
		die();
	}

	// An expired token
	// $session = new FacebookSession( 'CAAWdu4OUTpgBAE6b9rD0QwoB9ivcpEt8x951uRjoUZC5D6TidRZAMv5eUgMwpISG2lm9VvJbalkpvrs5XRxNIa0n91YcaxoFJP98OqQMT5dIQNZBy9qgoxXc0XeqIw41dx8QG7O6bdt11EGZASN6P7PwtCZAESN65ej2qI8AVWSXpp0dZBDRj02Y7yXAXQqYMZBKXEPX8vdTzZADDktECxvCpo7o1FsEyNcZD' );
	
	// Check that token is valid
	// Create a request object
	$request = new FacebookRequest( $session, 'GET', '/me' );

	// Execute the request
	try {
	    $response = $request->execute();
	} catch (Exception $e) {
	    $output = array(
	    	'error' => $e->getMessage()
	    );
	    die();
	}
	
	// Get response
	$graphObject = $response->getGraphObject();

	// Test media type

	// Execute post

	// echo '<pre>';
	// 	print_r($graphObject);
	// echo '</pre>';
	$output = $graphObject->asArray();
	wp_send_json( $output );
	
	die();
}