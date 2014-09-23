<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package yfennimap
 */

get_header(); ?>

	<div class="page-wrapper">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>

				<?php if(have_rows('map_steps')): ?>

				<div class="map-steps">
				<?php
					// loop through the rows of data
					while ( have_rows('map_steps') ) : the_row();

						$step_image = get_sub_field('image');

					?>

						<div class="step">

							<img src="<?php echo $step_image['url']; ?>" alt="point">

					        <?php echo '<h2>' . get_sub_field('title') . '</h2>';
					        the_sub_field('text'); ?>

					    </div>

			       	<?php endwhile; ?>

				</div>

				<?php endif; ?>

			<?php $map_post = get_field('map_link'); ?>
			<a class="btn call-to-action" href="<?php echo get_permalink( $map_post->ID ); ?>"><?php the_field('map_cta'); ?></a>

    		<?php the_field('project_details'); ?>
			<?php endwhile; // end of the loop. ?>

		</main>
	</div>

<?php get_footer(); ?>
