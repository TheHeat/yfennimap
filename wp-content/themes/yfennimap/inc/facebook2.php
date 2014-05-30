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
define('page_token', 'CAACEdEose0cBANaK98nam9yUhhSARC6HtLYz9ilrmWNZAftyP2UAZBZC1YmXlgEYgVyUVte1BdUC7UdBljWoZAif6HHs1ZCVqwS8J95qA5CQGdzqELrPql2KNWnZCONsv9zOxXoI6gVvDdIZAmDpRxZBqs5lfpPLQSlB5Q3jQjarXuSOGW34VF3fZC83YbaejVg5iVphNqixL6gZDZD');



function fb_post_on_page($token, $edge, $content){
	//Would like this to be able to take either a FacebookSession object or a strong
	//and act appropriately
	$session = new FacebookSession($token);
	//photo, video, feed
	//content is associative array containing source (optional), message, location
	//get page token from constant into FacebookSessions
	
	print_r($session);

	$url = '/' . page_id . '/' . $edge;

	$params = array(
		'name' => $content['title'],
		'message' => $content['description'] ,
		'link' => $content['url']
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

function fb_get_edge($token, $node, $edge){
	//Would like this to be able to take either a FacebookSession object or a strong
	//and act appropriately
	$session = new FacebookSession($token);
	//photo, video, feed
	//content is associative array containing source (optional), message, location
	//get page token from constant into FacebookSessions
	

	$url = '/' . $node . '/' . $edge;

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
	//if the user is logged in, get their token. If not, return the page's token

	if($_SESSION['fb_session']){
		$token = $_SESSION['fb_session']->getToken();
		return $token;
	}
	elseif(is_admin()){
		$token = page_token;
		return $token;
	}
	else{
		return null;
	}

}
