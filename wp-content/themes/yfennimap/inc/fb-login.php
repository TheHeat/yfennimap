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
define('page_token', 'CAADWmmqwixABAMCTlPK1FrjU1u4ZBZAZB96QVy11bZBCYx7JDU91RwEVPZAQZCwJx9VBIEj9sm1mGePcZAWeilZBNUzRk5bDjZBkbd9EHAUDrd2VHKpwcc3nq1fgFjzykEKnMbzjFjfpUPAHSSKaVf39RMJHGibEvsXnEtnzR6BzDa78Io2MutZA7VaMBmpgPE3F4ZD');


function fb_login($redirect_uri){

	// init app with app id and secret
	FacebookSession::setDefaultApplication( app_id, app_secret );

	// login helper with redirect_uri

	$helper = new FacebookRedirectLoginHelper($redirect_uri);

	//If logout = true, then destroy the FB session
	if($_GET['logout'] == true){
		unset($_SESSION['fb_session']);
	}

	//get the fb session out of the PHP session, otherwise get a new FB session
	if($_SESSION['fb_session']){
		$session = $_SESSION['fb_session'];
	}

	else{
		//create a new FB session

		try {
		  $session = $helper->getSessionFromRedirect();
		} catch( FacebookRequestException $ex ) {
		  // When Facebook returns an error
		} catch( Exception $ex ) {
		  // When validation fails or other local issues
		}
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

	  	//set the FB session into a PHP session so that the user can stay logged in
	  	$_SESSION['fb_session'] = $session;

	  	//display the FB login/logout
	  	?>
		<div class="avatar-wrapper facebook">
			<div class="avatar facebook">
				
				<?php $avatar_url = 'http://graph.facebook.com/' . $graphObject->getProperty('id') . '/picture'; ?>

				<img src="<?php echo $avatar_url; ?>"/>
			</div>
			<div class="avatar-menu facebook">
				<?php
				//add a parameter
				$logout_uri = add_query_arg('logout', 'true', $redirect_uri);

				//display logout

				echo '<a href="' . $logout_uri . '">Log out</a>';
				?>
			</div>
		</div>
		
	  	<?php

	} 

	else {
	//show login link

  	$params = array(
    'scope' => 'publish_actions',
  	);

  	?>

  	<div class="avatar-wrapper facebook">
		<div class="avatar facebook">
				
				<?php 

				// show login url
    			echo '<a href="' . $helper->getLoginUrl($params) . '">Login</a>';

    			?>

		</div>
	</div>
    <?php
	}
}