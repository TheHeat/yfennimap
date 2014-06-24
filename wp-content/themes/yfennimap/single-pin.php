<?php
/**
 *
 * Template to display a single pin
 *
 *
 * @package yfennimap
 */

//get_header(); 
echo '<div class="page-wrapper">';

$post = $wp_query->post;

print_r($post->ID);
echo '<br>';

$fb_media = new FB_Media($post->ID);

echo '<pre>'; print_r($fb_media); echo '</pre>'; 

$mediaType = get_field('media_type');

echo $mediaType;

pin_display($mediaType, $fb_media);

// echo $fb_media-> get_url();
// echo $fb_media-> get_long_text();
// echo $fb_media-> get_images();
// echo $fb_media-> get_video();
// echo $fb_media-> get_comments();
// echo $fb_media-> get_link();

echo '</div>';
get_footer(); ?>