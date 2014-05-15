<?php

require_once(get_template_directory() . '/src/Facebook/FacebookSession.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookRedirectLoginHelper.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookRequest.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookResponse.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookSDKException.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookRequestException.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookAuthorizationException.php' );
require_once(get_template_directory() . '/src/Facebook/GraphObject.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookPermissionException.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookClientException.php' );
require_once(get_template_directory() . '/src/Facebook/FacebookOtherException.php' );


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

//app ip, app secret, page token, page id
define('page_id', '276813939159864');
define('app_id', '235958703262480');
define('app_secret', 'f608ec2687f60c051396c4d0fabaae06');


function fb_login(){

	FacebookSession::setDefaultApplication( app_id, app_secret );

	$media = $_GET['media'];

	$helper = new FacebookRedirectLoginHelper( 'http://localhost/yfennimap/upload-form/?media='.$media );

	try {
		$session = $helper->getSessionFromRedirect();
	} catch( FacebookRequestException $ex ) {
		// When Facebook returns an error
	} catch( Exception $ex ) {
		// When validation fails or other local issues
	}
 
// see if we have a session
if ( isset( $session ) ) {

	// graph api request for user data
	$request = new FacebookRequest( $session, 'GET', '/me' );
	$response = $request->execute();
	// get response
	$graphObject = $response->getGraphObject();

	// print data
	echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';


	echo '<pre>'.print_r($session). '</pre>';

  try {

    $user_id =  $graphObject->getProperty('id');

    $permissions = (new FacebookRequest( $session, 'GET' , page_id .'/permissions' ))->execute();
    //$permissionObject = $permissions->getGraphObject

    echo '<pre>'. print_r($permissions->getGraphObject(), 1). '</pre>';

  } catch(FacebookRequestException $e) {

    echo "Exception occured, code: " . $e->getCode();
    echo " with message: " . $e->getMessage();

  }   


} else {

	//permissions required.. Will need to be as few as possible

	$params = array(
		'scope' => 'read_stream, user_friends, friends_likes, publish_actions, manage_pages',
	);

	// show login url
	echo '<a href="' . $helper->getLoginUrl($params) . '">Login</a>';
}

}

function fb_post_on_page($token, $edge, $content){
	//Would like this to be able to take either a FacebookSession object or a strong
	//and act appropriately
	$session = new FacebookSession($token);
	//photo, video, feed
	//content is associative array containing source (optional), message, location
	//get page token from constant into FacebookSessions
	

	$url = '/' . page_id . '/' . $edge;

	$params = array(
		'message' => $content
		);

	$request = new FacebookRequest( $session, 'POST', $url, $params);

	try{
		$response = $request->execute()->getGraphObject();
	
		return $response->getProperty('id'); //string with object id
	}
	catch( FacebookRequestException $ex ) {
	  // When Facebook returns an error
		$error = "Exception occured, code: " . $ex->getCode()
    		. " with message: " . $ex->getMessage();

    	return $error;
	} 
	catch( Exception $ex ) {
	  // When validation fails or other local issues
		return 'YFenni error';
	}
}

function fb_get_node($token, $node, $edge=null){
	//Would like this to be able to take either a FacebookSession object or a strong
	//and act appropriately
	$session = new FacebookSession($token);
	//photo, video, feed
	//content is associative array containing source (optional), message, location
	//get page token from constant into FacebookSessions

	$url = '/' . $node . '/' .$edge;

	$request = new FacebookRequest( $session, 'GET', $url);

	try{
		$response = $request->execute()->getGraphObject();
	
		// return $response->getProperty('id'); //string with object i
		echo '<pre>';
			print_r($response);
		echo '</pre>';
	}
	catch( FacebookRequestException $ex ) {
	  // When Facebook returns an error
		$error = "Exception occured, code: " . $ex->getCode()
    		. " with message: " . $ex->getMessage();

    	return $error;
	} 
	catch( Exception $ex ) {
	  // When validation fails or other local issues
		return 'YFenni error';
	}
}

function fb_get_token(){

	// init app with app id and secret
	FacebookSession::setDefaultApplication( app_id, app_secret );
	 
	// login helper with redirect_uri

	$helper = new FacebookRedirectLoginHelper('http://localhost/yfennimap');

	try {
	  $session = $helper->getSessionFromRedirect();
	} catch( FacebookRequestException $ex ) {
	  // When Facebook returns an error
	} catch( Exception $ex ) {
	  // When validation fails or other local issues
	}
	 
	// see if we have a session
	if ( isset( $session ) ) {
	  // graph api request for user data
	  $request = new FacebookRequest( $session, 'GET', '/me' );
	  $response = $request->execute();
	  // get response
	  $graphObject = $response->getGraphObject();
	  
	  // print data
	  // echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';

	  echo '<pre>';
	  	print_r($session);
	  echo '</pre>';

	} else {

	  $params = array(
	    'scope' => 'publish_actions',
	  );

	  // show login url
	  echo '<a href="' . $helper->getLoginUrl($params) . '">Login</a>';
	}
}
