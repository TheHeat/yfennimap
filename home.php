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

<div class="filters">
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
<?php get_footer(); ?>
	