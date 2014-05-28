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
	update_post_meta( $post_id, "link", esc_attr(strip_tags($_POST['link'])) );

	add_post_meta( $post_id, 'user_fb_token', fb_get_token() );

	if ( $media == 'Image'):

		if (count($_FILES[image_upload][name]) > 1) $media = 'gallery'; 
		update_post_meta( $post_id, "media_type", $media );

		$media_field = get_field('field_5362addb9bf86', $post_id);

		if ( $_FILES ) {
			$files = $_FILES['image_upload'];
			foreach ($files['name'] as $key => $value) {
				if ($files['name'][$key]) {
					$file = array(
						'name'     => $files['name'][$key],
						'type'     => $files['type'][$key],
						'tmp_name' => $files['tmp_name'][$key],
						'error'    => $files['error'][$key],
						'size'     => $files['size'][$key]
					);
		 
					$_FILES = array("upload_attachment" => $file);
		 
					foreach ($_FILES as $file => $array) {
						$new_upload[] = array('file' => insert_attachment($file,$post_id));						
					}
				}
			}
		}	
	endif;

	update_field( 'field_5362addb9bf86',  $new_upload, $post_id);
	
	//if ( $media == 'Video'|| $media == 'link'):

	//Message if succesful
	if($post_id) _e('<p> Your Post has been submitted for moderation </p>' ); 

	exit;
}

get_header(); ?>

<div class="page-wrapper">

	<form action="" id="pinForm" method="POST" enctype="multipart/form-data">

		<?php if ( $media == 'Message'): ?>
			<fieldset>
				<label for="postTitle"><?php _e('Pin\'s Title:') ?></label>
				<input type="text" name="postTitle" id="postTitle" value="<?php if(isset($_POST['postTitle'])) echo $_POST['postTitle'];?>" />
			</fieldset>
		<?php endif ?>

		<?php if ( $media == 'Video' || $media == 'Link' ): ?>
		<fieldset>
			<label for="link"><?php _e('Link:') ?></label>
			<input type="text" name="link" id="link"  multiple="false" />
		</fieldset>
		<?php endif ?>

		<?php if($post_description_error != '') { ?>
			<span class="error"><?php echo $post_description_error; ?></span>
		<?php } ?>

		<fieldset>					
			<label for="postContent"><?php _e($description_field) ?></label>
			<textarea name="postContent" id="postContent" rows="8" cols="30"><?php if(isset($_POST['postContent'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['postContent']); } else { echo $_POST['postContent']; } } ?></textarea>
		</fieldset>

		<?php if ( $media == 'Video' || $media == 'Image' || $media == 'Audio'): ?>
		<fieldset>
			<input type="file" name="image_upload[]" id="image_upload"  multiple="multiple" />
			<input type="hidden" name="post_id" id="post_id" value="55" />
		</fieldset>
		<?php endif ?>

		<fieldset>			
			<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
			<input type="hidden" name="submitted" id="submitted" value="true" />
			<button type="submit"><?php _e('Add Post') ?></button>
		</fieldset>
	</form>
</div>

<?php get_footer() ?>