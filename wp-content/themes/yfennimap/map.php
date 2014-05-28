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
<div class="toolbox">
  <div class="handle" tabindex="1">Add</div>
  <div class="box">
  	<?php if(fb_get_token()): ?>
	    <a href="#" data-media="link" class="tool add link">Web Link</a>
	    <a href="#" data-media="video" class="tool add video">Video</a>
	    <a href="#" data-media="pictures" class="tool add pictures">Pictures</a>
	    <a href="#" data-media="text" class="tool add words">Words</a>
	<?php else: ?>
		<?php fb_login() ;?>
	<?php endif; ?>
  </div>
</div>
<div id="map-canvas"></div>
<div id="media-modal"></div>
<?php get_footer(); ?>
	