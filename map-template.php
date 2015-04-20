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

<div class="avatar-wrapper facebook" tabindex="2">
	<div class="avatar facebook"></div>
</div>
<div class="login-wrapper">
	<?php _e('Login', 'yfenni') ?>
</div>

<?php get_template_part('map-pins' ); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="info-window" style="display:none;">
		<?php the_title( '<h1>', '</h1>'); ?>
		<?php the_content(); ?>
	</div>

	<!-- Message to be displayed if a pin was sucessfully submitted -->
	<div class="success-message" style="display:none;">
		<?php
		$success_message = get_post_meta( get_the_id(), 'success_message', true );
		echo $success_message;
		?>
	</div>

	<!-- Message to be displayed if a pin was NOT sucessfully submitted -->
	<div class="failure-message" style="display:none;">
		<?php
		$success_message = get_post_meta( get_the_id(), 'failure_message', true );
		echo $success_message;
		?>
	</div>

<?php endwhile; endif; ?>

<div class="filters">
	<span class="tool categories" title="<?php _e('Tags','yfenni')?>"></span>
	<span class="tool clear-categories"></span>
</div>

<div class="date">
	<input type="text" id="date-label">
	<div id="date-range"></div>
</div>

<div class="toolbox">
	<div class="actions" style="display:none;"></div>
	<div class="handle add" tabindex="1" title="<?php _e('Add a pin','yfenni')?>"></div>
	<div class="box">
		<a href="#" data-media="link" class="tool add link">Web Link</a>
		<a href="#" data-media="video" class="tool add video">Video</a>
		<a href="#" data-media="image" class="tool add pictures">Pictures</a>
		<a href="#" data-media="text" class="tool add words">Words</a>
  	</div>
</div>

<div id="map-canvas"></div>

<div id="modal-window"></div>


<?php get_footer(); ?>
	