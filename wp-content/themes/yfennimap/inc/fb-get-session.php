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

function fb_get_session($helper = null){

//check if there is currently a session created, if not return False 
	
	//get session from php session if one is available
	if ( isset($_SESSION['fb_session'])) {

		$session = $_SESSION['fb_session'];
		//echo 'php session '; print_r($session);
	
	} elseif(null !=$helper) {
		//create a new FB session
		try {
			//get the session from the query string
			// echo 'wow';
			$session = $helper->getSessionFromRedirect();
		} catch( FacebookRequestException $ex ) {
			echo "Exception occured, code: " . $ex->getCode();
	    	echo " with message: " . $ex->getMessage();
		  // When Facebook returns an error
		} catch( Exception $ex ) {
			echo "Exception occured, code: " . $ex->getCode();
	    	echo " with message: " . $ex->getMessage();
		  // When validation fails or other local issues
		}
		//echo 'php session '; print_r($session);
		// echo '<pre>';
		// 	print_r($session);
		// echo '</pre>';
	}
	  	//set the FB session into a PHP session so that the user can stay logged in
  	if(isset($session)):
	  	$_SESSION['fb_session'] = $session;
		return $session ;
	endif;

}