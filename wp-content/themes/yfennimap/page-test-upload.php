<?php get_header( );?>





<form id="featured_upload" method="post" action="#" enctype="multipart/form-data">
	<input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" />
	<input type="hidden" name="post_id" id="post_id" value="55" />
	<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
	<input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" />
</form>

<?php

// Check that the nonce is valid, and the user can edit this post.
if ( 
	isset( $_POST['my_image_upload_nonce'], $_POST['post_id'] ) 
	&& wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' )
	&& current_user_can( 'edit_post', $_POST['post_id'] )
) {
	// The nonce was valid and the user has the capabilities, it is safe to continue.

	// These files need to be included as dependencies when on the front end.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
	// Let WordPress handle the upload.
	// Remember, 'my_image_upload' is the name of our file input in our form above.
	$attachment_id = media_handle_upload( 'my_image_upload', $_POST['post_id'] );
	
				if ( is_wp_error( $attachment_id ) ) {
					_e( 'File Upload Error' );
				} else {
					_e( 'File Upload Succesful' );
				}

} else {

	// The security check failed, maybe show the user an error.
};

// $location = get_field('field_5362ae02910ff', '277');

// echo'bum';

// print_r($location);

// echo '<br>'.$location['lat'];
// echo '<br>'.$location['lng'];

// $location['lat'] = '1234567890';
// $location['lng'] = '1234567890';


// update_field( 'field_5362ae02910ff',  $location, '277');

// $location = get_field('field_5362ae02910ff', '277');

// print_r($location);
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=1403610066585894&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php
echo '<div class="page-wrapper">';

$fb_media = new FB_Media('398');
//echo '<pre>'; print_r($fb_media); echo '</pre>'; 

echo $fb_media-> get_text();
echo $fb_media-> get_long_text();
echo $fb_media-> get_images();
echo $fb_media-> get_video();
echo $fb_media-> get_comments();
echo $fb_media-> get_link();


echo '</div>';

get_footer( );

