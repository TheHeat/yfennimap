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

fb_get_token();
?>

<form>
  <label for"message">Message to Post</label>
  <input name="message" class="message" id="message" type="text">
  <input type="submit" value="submit">
</form>


<?php

if(null!=($_GET["message"])):

  $token = 'CAADWmmqwixABAMCTlPK1FrjU1u4ZBZAZB96QVy11bZBCYx7JDU91RwEVPZAQZCwJx9VBIEj9sm1mGePcZAWeilZBNUzRk5bDjZBkbd9EHAUDrd2VHKpwcc3nq1fgFjzykEKnMbzjFjfpUPAHSSKaVf39RMJHGibEvsXnEtnzR6BzDa78Io2MutZA7VaMBmpgPE3F4ZD';
  $edge = 'feed';
  $content = $_GET["message"];

  $fb = fb_post_on_page($token, $edge, $content);

  if($fb) echo 'Message Posted!';

endif;


fb_get_edge('CAADWmmqwixABAMCTlPK1FrjU1u4ZBZAZB96QVy11bZBCYx7JDU91RwEVPZAQZCwJx9VBIEj9sm1mGePcZAWeilZBNUzRk5bDjZBkbd9EHAUDrd2VHKpwcc3nq1fgFjzykEKnMbzjFjfpUPAHSSKaVf39RMJHGibEvsXnEtnzR6BzDa78Io2MutZA7VaMBmpgPE3F4ZD', '276813939159864', 'feed');
get_footer(); ?>
