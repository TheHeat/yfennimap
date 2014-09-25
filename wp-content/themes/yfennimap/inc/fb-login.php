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


function fb_login(){

//If logout = true, then destroy the FB session
if(isset($_GET['logout'])){
  session_unset();
}

	global $wp;
	$current_url = home_url( $wp->request ).'/';

	echo $current_url;

// start session
//session_start();
 
// init app with app id and secret
FacebookSession::setDefaultApplication( '235958703262480','5a2916a451c2274eb70280221ff1fafb' );
 
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper( 'http://localhost/yfennimap/test' );
  // echo '<pre>';
  //   print_r($helper);
  // echo '</pre>'; 
// see if a existing session exists
if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
  // create new session from saved access_token

  $session = new FacebookSession( $_SESSION['fb_token'] );

  // validate the access_token to make sure it's still valid
  try {
    if ( !$session->validate() ) {
      
      $session = null;
    }
  } catch ( Exception $e ) {
    
    // catch any exceptions
    $session = null;
  }
   
} 

if ( !isset( $session ) || $session === null ) {
// no session exists
  
  try {
    $session = $helper->getSessionFromRedirect();
  } catch( FacebookRequestException $ex ) {
    // When Facebook returns an error
    // handle this better in production code
    print_r( $ex );
  } catch( Exception $ex ) {
    // When validation fails or other local issues
    // handle this better in production code
    print_r( $ex );
  }
  
}
 
// see if we have a session
if ( isset( $session ) ) {
  
  // save the session
  $_SESSION['fb_token'] = $session->getToken();
  // create a session using saved token or the new one we generated at login
  $session = new FacebookSession( $session->getToken() );
  
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject()->asArray();
  
  // print profile data
  echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';
  
  // print logout url using session and redirect_uri (logout.php page should destroy the session)
  echo '<a href="' . $helper->getLogoutUrl( $session, 'http://localhost/yfennimap/map?logout=true' ) . '">Logout</a>';
  
} else {
  // show login url
  echo '<a href="' . $helper->getLoginUrl( array( 'email', 'user_friends' ) ) . '">Login</a>';
}
}
