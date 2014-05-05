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

$token = 'CAADWmmqwixABADZA2PPguDt75DAufmTHAA12fLFlBVlJhKYXb23AwHQkUQaiklUTdRHF2sxS5qQZAqApPhxOLr2nxbb0GHM0ICokbgPfzFRCaDNdMALMkClClFZCLGNvsMPvoEx2gU3q6ifZB34ZCQgRiqHPAMPLClkdpRie0rkr3tYCVFMUIhnoIKFLs049rHEYevhynZAgZDZD';
$edge = 'feed';
$content = 'some random shitasdfagainasdf4';

$fb = fb_post_on_page($token, $edge, $content);

echo $fb;

get_footer(); ?>
