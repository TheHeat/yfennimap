<?php
/**
 * Template Name: Map
 *
 * Map interface for "pins" custom post type
 * All we have is a canvas and an empty media container
 *
 *
 * @package yfennimap
 */

get_header();
?>
<div id="fb-root"></div>
<?php //fb_login(); ?>

<?
/**
 * Load Facebook PHP SDK
 */
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
require_once(get_template_directory() . '/src/Facebook/GraphSessionInfo.php' );

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;

//check if the user is trying to logout
//If logout = true, then destroy the FB session
if(isset($_GET['logout'])){
	unset($_SESSION['fb_token']);
}
 
// start session
// session_start();
 
// init app with app id and secret
FacebookSession::setDefaultApplication( app_id,app_secret );

//Get page URL
$page_url = home_url() . strtok($_SERVER['REQUEST_URI'], '?');
 
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper($page_url);
 
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
  $graphObject = $response->getGraphObject();
  
  // echo '<pre>';
  // 	print_r($_SESSION);
  // echo '</pre>';
  // print profile data
  // echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';
  
  // print logout url using session and redirect_uri (logout.php page should destroy the session)
  // echo '<a href="' . $helper->getLogoutUrl( $session, 'http://localhost/yfennimap/test?logout=true' ) . '">Logout</a>';
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

} else {
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

?>


<?php get_template_part('map-pins' ); ?>

<?php

	//check if the post has been submitted
	if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

		require get_template_directory() . '/inc/upload-submit.php';
	}

?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="info-window" style="display:none;">
		<?php the_title( '<h1>', '</h1>'); ?>
		<?php the_content(); ?>
	</div>

	<!-- Message if a pin was sucessfully submitted -->

<?php endwhile; endif; ?>

<div class="filters">
	<span class="tool categories"></span>
	<span class="tool clear-categories"></span>
</div>

<div class="date">
	<input type="text" id="date-label">
	<div id="date-range"></div>
</div>

<?php if($_SESSION['fb_token']): ?>
	<div class="toolbox">
		<div class="actions" style="display:none;"></div>
		<div class="handle add" tabindex="1"></div>
		<div class="box">
			<a href="#" data-media="link" class="tool add link">Web Link</a>
			<a href="#" data-media="video" class="tool add video">Video</a>
			<a href="#" data-media="image" class="tool add pictures">Pictures</a>
			<a href="#" data-media="text" class="tool add words">Words</a>
	  	</div>
	</div>
<?php endif; ?>
<div id="map-canvas"></div>

<div id="modal-window"></div>

<!-- Upload new content form -->
<div class="upload-form" style="display:none;">
	<form action="<?php echo site_url() . '/';?>" id="pinForm" method="POST" enctype="multipart/form-data" name="pinForm">

		<fieldset class="title">
			<label for="postTitle"><?php _e('Pin title:') ?></label>
			<input type="text" name="postTitle" id="postTitle" value="<?php if(isset($_POST['postTitle'])) echo $_POST['postTitle'];?>" required aria-required="true" />
		</fieldset>

		<fieldset class="content">					
			<label for="postContent">Description</label>
			<textarea name="postContent" id="postContent" rows="8" cols="30" required aria-required="true"><?php if(isset($_POST['postContent'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['postContent']); } else { echo $_POST['postContent']; } } ?></textarea>
		</fieldset>

		<fieldset class="link">
			<label for="link"><?php _e('Link:') ?></label>
			<input type="text" name="link" id="link"  multiple="false" required aria-required="true"/>
		</fieldset>

		<fieldset class="file">
			<input type="file" name="media_upload[]" id="media_upload"  multiple required aria-required="true"/>
			<input type="hidden" name="post_id" id="post_id" value="55" />
		</fieldset>
		
		<fieldset>
			<label for="year-created"><?php _e('Year Created:') ?></label>
			<input type="number" name="year-created" id="year-created" multiple="false" max='2020' value="<?php echo date('Y');  ?>" required aria-required="true"/>
		</fieldset>

		<fieldset>
			<label for="pin_category"><?php _e('Categories:') ?></label><br/>
				<?php
					$terms = get_terms('pin_category');
					
					foreach ($terms as $term) { 
						//Get the term ID
						$term_id = $term->cat_ID;

						//It returns a string. Cast it as an integer
						$term_id = (integer)$term_id;
						?>
						<input type="checkbox" name="pin_category[]" value=<?php echo $term_id;?> />
						<?php
						echo $term->cat_name;
						echo '<br/>';
					}
				?>
		</fieldset>

		<!-- Hidden fields for JQuery use -->
		<input type="hidden" class="media-hidden" name="media"/>
		<input type="hidden" class="lat-hidden" name="lat"/>
		<input type="hidden" class="lng-hidden" name="lng"/>

		<fieldset>			
			<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
			<input type="hidden" name="submitted" id="submitted" value="true" />
			<button type="submit"><?php _e('Add Pin') ?></button>
		</fieldset>

	</form>
</div>


<?php get_footer(); ?>
	