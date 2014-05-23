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
    <a href="#" class="tool add video">Add Video</a>
    <a href="#" class="tool add pictures">Add Pictures</a>
    <a href="#" class="tool add words">Add Words</a>
    <a href="#" class="tool add link">Add a Link</a>
  </div>
</div>
<div id="map-canvas"></div>
<div id="media-modal"></div>
<?php get_footer(); ?>
	