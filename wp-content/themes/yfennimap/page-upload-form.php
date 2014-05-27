<?php 
 
$media = $_GET['media'];

// change description field title dependant on media type
switch ($media) {
	case 'link': $description_field = 'Message'; break;
	default: $description_field = 'Description'; break;
}

//Check if the post has a description - NOT WORKING
$post_description_error = '';

if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

	if(trim($_POST['postContent']) === '') {
		$post_description_error = 'Please enter some information about your upload.';
		$hasError = true;
	} else {
		$postContent = trim($_POST['postContent']);
	}
	
	//Insert Post
	//standard WP info
	$post_information = array(
		'post_title' => esc_attr(strip_tags($_POST['postTitle'])),
		'post_type' => 'pin',
		'post_status' => 'pending'
	);

	//Create Post
	$post_id = wp_insert_post($post_information);

	//Custom Fields
	update_post_meta( $post_id, "description", esc_attr(strip_tags($_POST['postContent'])) );
	update_post_meta( $post_id, "media_type", $media );
	update_post_meta( $post_id, "fb_user_id", '235958703262480' );

	if ( $media == 'Image'):

		// files for frontend media upload
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		
		// Let WordPress handle the upload.
		// Remember, 'my_image_upload' is the name of our file input in our form above.
		$attachment_id = media_handle_upload( 'my_image_upload', $_POST['post_id'] );
		$attachment_object = get_attached_file( $attachment_id );	
		
		//insert media in to post 
		update_post_meta( $post_id, "media", $attachment_id );

	endif;

//if ( $media == 'Video'|| $media == 'link'):


	//Message if succesful
	if($post_id) _e('<p> Your Post has been submitted for moderation </p>' ); 


	exit;

}

get_header(); ?>

	<form action="" id="pinForm" method="POST" enctype="multipart/form-data">

		<?php //if ( $media == 'test' || $media == 'link'): ?>
			<fieldset>
				<label for="postTitle"><?php _e('Post\'s Title:') ?></label>
				<input type="text" name="postTitle" id="postTitle" value="<?php if(isset($_POST['postTitle'])) echo $_POST['postTitle'];?>" />
			</fieldset>
		<?php //endif ?>

		<?php if($post_description_error != '') { ?>
			<span class="error"><?php echo $post_description_error; ?></span>
		<?php } ?>

		<fieldset>					
			<label for="postContent"><?php _e($description_field) ?></label>
			<textarea name="postContent" id="postContent" rows="8" cols="30"><?php if(isset($_POST['postContent'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['postContent']); } else { echo $_POST['postContent']; } } ?></textarea>
		</fieldset>

		<?php if ( $media == 'Video' || $media == 'Image' || $media == 'Audio'): ?>
			<input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" />
			<input type="hidden" name="post_id" id="post_id" value="55" />
		<?php endif ?>

		<?php if ( $media == 'Video' || $media == 'Link' ): ?>
			<input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" />
		<?php endif ?>

		<fieldset>			
			<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
			<input type="hidden" name="submitted" id="submitted" value="true" />
			<button type="submit"><?php _e('Add Post') ?></button>
		</fieldset>
	</form>

<? get_footer() ?>