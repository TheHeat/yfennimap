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

function fb_post_on_page($token, $edge, $content){
	//Would like this to be able to take either a FacebookSession object or a strong
	//and act appropriately
	$session = new FacebookSession($token);

	//print_r($session);

	//photo, video, feed
	//content is associative array containing source (optional), message, location
	//get page token from constant into FacebookSessions
	

	$url = '/' . page_id . '/' . $edge;


	$params = array();

	$params['name'] = $content['title'];
	$params['message'] = $content['description'];

	if($edge == 'feed') 	$params['link'] = $content['url'];
	if($edge == 'videos' || $edge == 'photos')	$params['source'] = $content['source'];

	$request = new FacebookRequest( $session, 'POST', $url, $params);

	//echo '<pre>';print_r($request);

	try{
		$response = $request->execute()->getGraphObject();
	
		return $response->getProperty('id'); //string with object id
	}
	catch( FacebookRequestException $ex ) {
	  // When Facebook returns an error
		$error = "Facebook Exception occured, code: " . $ex->getCode()
    		. " with message: " . $ex->getMessage();

    	return $error;
	} 
	catch( Exception $ex ) {
	  // When validation fails or other local issues
		$error = "Exception occured, code: " . $ex->getCode()
    		. " with message: " . $ex->getMessage();

    	return $error;
	}
}

?>