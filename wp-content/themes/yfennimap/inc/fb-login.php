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


function fb_login(){

	// init app with app id and secret
	FacebookSession::setDefaultApplication( app_id, app_secret );

	// code for using current url... can make the login process tempramental
	//global $wp;
	//$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

	//Compose the page URL
	$page_url = home_url() . $_SERVER['REQUEST_URI'] . '/';
	$helper = new FacebookRedirectLoginHelper( $page_url );

	// echo '<pre>';
	// 	print_r($helper);
	// echo '</pre>';

	//check if the user is trying to logout
	fb_logout();
	
	//get the fb session out of the PHP session, otherwise get a new FB session
	if( fb_get_session( $helper )){
		$session = fb_get_session();
		// echo '<pre>';
		// 	print_r($session);
		// echo '</pre>';
	}
	 
	// see if we have a session
	if ( isset($session) ) {

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
				$logout_uri = add_query_arg('logout', 'true', home_url());

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
