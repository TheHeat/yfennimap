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

	// init app with app id and secret
	FacebookSession::setDefaultApplication( app_id, app_secret );

	// code for using current url... can make the login process tempramental
	//global $wp;
	// $page_url = home_url( $wp->request ) ;

	// echo 'pageurl ' .$page_url;
	//Compose the page URL
	$page_url = home_url() . strtok($_SERVER['REQUEST_URI'], '?');
	// echo 'pageurl ' .$page_url;
	// $page_url = 'http://localhost/yfennimap/map/';
	$helper = new FacebookRedirectLoginHelper( $page_url );

	// echo '<pre>';
	// 	print_r($helper);
	// echo '</pre>';

	//check if the user is trying to logout
	//If logout = true, then destroy the FB session
	if(isset($_GET['logout'])){
		unset($_SESSION['fb_session']);
	}
	
	//get the fb session out of the PHP session, otherwise get a new FB session
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
	  
	} else {
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
	if ( isset($session) ) {
		// echo '<pre>';
		// 	print_r($session);
		// echo '</pre>';

		//Save the FB session to a $_SESSION
		// save the session
		$_SESSION['fb_token'] = $session->getToken();
		// create a session using saved token or the new one we generated at login
		$session = new FacebookSession( $session->getToken() );
		
	  	// graph api request for user data
	  	$request = new FacebookRequest( $session, 'GET', '/me' );
	  	$response = $request->execute();
	  	// get response
	  	$graphObject = $response->getGraphObject();

	  	//print_r($graphObject);

	  	//display the FB Avatar/logout
	  	?>
		<div class="avatar-wrapper facebook" tabindex="2">
			<div class="avatar facebook">
				
				<?php $avatar_url = 'http://graph.facebook.com/' . $graphObject->getProperty('id') . '/picture'; ?>

				<img src="<?php echo $avatar_url; ?>"/>
			</div>
			<div class="avatar-menu facebook">
				<?php //add a parameter
				$logout_uri = add_query_arg('logout', 'true', $page_url);

				//display logout
				echo '<a href="' . $logout_uri . '">Log out</a>';
				?>
			</div>
		</div>
	<?php
	} 

	else {	//if not logged in show login link

		// get permissions required
	  	$params = array(
	    	'scope' => 'publish_actions',
	  		); ?>

	  	<div class="avatar-wrapper facebook">	
			<?php // show login url
	   			echo '<a href="' . $helper->getLoginUrl($params) . '">Login</a>';
			?>
		</div>
    <?php
	}
}
