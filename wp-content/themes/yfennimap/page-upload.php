<?get_header();?>

<div class="page-wrapper">
	<h1>What would you like to upload?</h1>

	<!-- <form action="<?php echo get_bloginfo('url');?>/facebook-login"> -->
	<!-- Using redirect to upload form temporarily -->
	<form action="<?php echo get_bloginfo('url');?>/upload-form">
		<input type="radio" name="media" value="link">Link<br>
		<input type="radio" name="media" value="Message">Message<br>
		<input type="radio" name="media" value="Image">Image<br>
		<input type="radio" name="media" value="Video">Video<br>
		<input type="radio" name="media" value="Sound">Sound<br>
		<input type="submit" value="upload">
	</form>
</div>

<?php get_footer()?>