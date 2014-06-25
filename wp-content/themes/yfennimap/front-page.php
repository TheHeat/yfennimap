<?php
/**
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
<?php endwhile; endif; ?>

<div class="filters">
</div>

<div class="date">
	<input type="text" id="date-label">
	<div id="date-range"></div>
</div>

<?php if(fb_get_token()): ?>
	<div class="toolbox">
		<div class="actions" style="display:none;"></div>
		<div class="handle add" tabindex="1"></div>
		<div class="box">
			<a href="#" data-media="link" class="tool add link">Web Link</a>
			<a href="#" data-media="video" class="tool add video">Video</a>
			<a href="#" data-media="pictures" class="tool add pictures">Pictures</a>
			<a href="#" data-media="text" class="tool add words">Words</a>
	  	</div>
	</div>
<?php endif; ?>
<div id="map-canvas">
</div>
<div id="modal-window">

</div>

<!-- Upload new content form -->
<div class="upload-form" style="display:none;">
	<form action="" id="pinForm" method="POST" enctype="multipart/form-data">

		<fieldset class="title">
			<label for="postTitle"><?php _e('Pin title:') ?></label>
			<input type="text" name="postTitle" id="postTitle" value="<?php if(isset($_POST['postTitle'])) echo $_POST['postTitle'];?>" />
		</fieldset>

		<fieldset class="content">					
			<label for="postContent">Description</label>
			<textarea name="postContent" id="postContent" rows="8" cols="30"><?php if(isset($_POST['postContent'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['postContent']); } else { echo $_POST['postContent']; } } ?></textarea>
		</fieldset>

		<fieldset class="link">
			<label for="link"><?php _e('Link:') ?></label>
			<input type="text" name="link" id="link"  multiple="false"/>
		</fieldset>

		<fieldset class="file">
			<input type="file" name="media_upload[]" id="media_upload"  multiple/>
			<input type="hidden" name="post_id" id="post_id" value="55" />
		</fieldset>
		
		<fieldset>
			<label for="year-created"><?php _e('Year Created:') ?></label>
			<input type="number" name="year-created" id="year-created" multiple="false" max='2020' value="<?php echo date('Y');  ?>"/>
		</fieldset>

		<fieldset>
			<label for="pin_category"><?php _e('Categories:') ?></label><br/>
				<?php
					$terms = get_terms('pin_category');
					
					foreach ($terms as $term) { ?>
						<input type="checkbox" name="pin_category" value="<?php echo $term->cat_ID;?>">
						
						<?php
						echo $term->cat_name;
						echo '<br/>';
					}
				?>
		</fieldset>

		<fieldset>			
			<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
			<input type="hidden" name="submitted" id="submitted" value="true" />
			<button type="submit"><?php _e('Add Post') ?></button>
		</fieldset>

	</form>
</div>
<?php get_footer(); ?>
	