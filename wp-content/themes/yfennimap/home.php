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

<?php get_template_part('map-pins' ); ?>

<?php if(fb_get_token()): ?>

<div class="toolbox">
	<div class="actions" style="display:none;"></div>
	<div class="handle" tabindex="1">Add a pin</div>
	<div class="box">
	  	
		    <a href="#" data-media="link" class="tool add link">Web Link</a>
		    <a href="#" data-media="video" class="tool add video">Video</a>
		    <a href="#" data-media="pictures" class="tool add pictures">Pictures</a>
		    <a href="#" data-media="text" class="tool add words">Words</a>
  	</div>
<?php endif; ?>
</div>
<div id="map-canvas">
</div>
<div id="media-modal">

</div>
<?php get_footer(); ?>
	