<?php 

// get the media type for use in the rest of the form
$media = esc_attr(strip_tags($_GET['media']));

// change description field title dependant on media type
switch ($media) {
	case 'Link': $description_field = 'Message'; break;
	default: $description_field = 'Description'; break;
}

//check if the post has been submitted
if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

	require get_template_directory() . '/inc/upload-submit.php';
}

get_header(); ?>

<div class="page-wrapper">

	<form action="" id="pinForm" method="POST" enctype="multipart/form-data">

		<?php if ( $media == 'text'): ?>
			<fieldset>
				<label for="postTitle"><?php _e('Pin\'s Title:') ?></label>
				<input type="text" name="postTitle" id="postTitle" value="<?php if(isset($_POST['postTitle'])) echo $_POST['postTitle'];?>" />
			</fieldset>
		<?php endif ?>



		<?php
		// validation
		// if($post_description_error != '') { 
		// 	echo '<span class="error">. $post_description_error; </span>';
		// } ?>

		<fieldset>					
			<label for="postContent"><?php _e($description_field) ?></label>
			<textarea name="postContent" id="postContent" rows="8" cols="30"><?php if(isset($_POST['postContent'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['postContent']); } else { echo $_POST['postContent']; } } ?></textarea>
		</fieldset>

		<?php if ( $media == 'video' || $media == 'pictures' || $media == 'Audio'): ?>
		<fieldset>
			<input type="file" name="media_upload[]" id="media_upload"  multiple="multiple" <?php if($media == 'video') echo'accept="3g2, 3gp, 3gpp, asf, avi, dat, divx, dv, f4v, flv, m2ts, m4v, mkv, mod, mov, mp4, mpe, mpeg, mpeg4, mpg, mts, nsv, ogm, ogv, qt, tod, ts, vob, wmv"'?>/>
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