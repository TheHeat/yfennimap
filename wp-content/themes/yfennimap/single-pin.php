<?php
/**
 *
 * Template to display a single pin
 *
 *
 * @package yfennimap
 */

get_header(); 
echo '<div class="page-wrapper">';

$fb_media = new FB_Media($post->ID);

//echo '<pre>'; print_r($fb_media); echo '</pre>'; 

echo $fb_media-> get_text();
echo $fb_media-> get_long_text();
echo $fb_media-> get_images();
echo $fb_media-> get_video();
echo $fb_media-> get_comments();
echo $fb_media-> get_link();

echo '</div>';
get_footer(); ?>