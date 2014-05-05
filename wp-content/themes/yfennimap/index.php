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

$token = 'CAADWmmqwixABALPfY9DQO9cQzk9eAiEUByrXZAuFvSZBM6Drj3iL7yYiFmjvunnQNNZAB6l9GrlicI9G1ZAFLYXUYTZCNFAcaJAy8dQfVdR83pLVsPQylrNSTG9CuYym5WEPCuth0J5kLpWfHxghRAWuKsRNtFHiKhujZB80Vlr9vFwRdBz1Ec';
$edge = 'feed';
$content = 'some random shitasdfagainasdfasdf4';

$fb = fb_post_on_page($token, $edge, $content);

echo $fb;

get_footer(); ?>
