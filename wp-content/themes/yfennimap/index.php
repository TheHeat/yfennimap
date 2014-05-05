<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package yfennimap
 */

get_header();

$token = 'CAADWmmqwixABADlOjnJnuQlvWDwdFfFvwRBgGKR0lHN6OWAThIRtzZBD8WONQsf1SVxfHiNZAP0ai9FK2iD3LMqVXzSLytfDmxXcVf1SVrXrZAFhzJTDf15pZCted6pDJtIk0mo6xF0PpBM6NRkFfET4eXV4KA6ZCPlw27Y1dizHXFTDEQDyBocRhlUAhAHsZD';
$edge = 'feed';
$content = 'wowee';

$fb = fb_post_on_page($token, $edge, $content);

echo $fb;

get_footer(); ?>
