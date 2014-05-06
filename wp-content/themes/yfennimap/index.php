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
?>

<form>
  <label for"message">Message to Post</label>
  <input name="message" class="message" id="message" type="text">
  <input type="submit" value="submit">
</form>


<?php

if(null!=($_GET["message"])):

  $token = 'CAADWmmqwixABAB9ZC1SQHdX8AkGknsY3OxB2zY2ljiZByslb064vVg9j2YRgZA3ME8MEauTUWxW49pYZAgoDDA3DZAPaOPDS31ZCZAV0bDlpAlkotKUBNkzKVXzZAZAy2DUdQw9O8WwFB8NhWSAZCp5pzu1o7KNG6XmXxQbGu2vh0vJtRJ4lT42mbxQJeZAhDuqnnUZD';
  $edge = 'feed';
  $content = $_GET["message"];

  $fb = fb_post_on_page($token, $edge, $content);

  if($fb) echo 'Message Posted!';

endif;

get_footer(); ?>
