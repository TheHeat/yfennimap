<?get_header();?>

<h1>What would you like to upload?</h1>

<form action="<?php echo get_bloginfo('url');?>/facebook-login">
	<input type="radio" name="media" value="link">Link<br>
	<input type="radio" name="media" value="Message">Message<br>
	<input type="radio" name="media" value="Image">Image<br>
	<input type="radio" name="media" value="Video">Video<br>
	<input type="radio" name="media" value="Sound">Sound<br>
	<input type="submit" value="upload">
</form>

<?php get_footer()?>