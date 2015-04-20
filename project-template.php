<?php
/**
 * Template Name: Project
 *
 * Template for the project section of the website
 *
 *
 * @package yfennimap
 */

get_header();
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<div class="page-wrapper">
		<div id="project-navigation">
			<?php 
			$language_menu_item = '<li class="menu-item language-menu-item">' . get_yfenni_language_link() . '</li>';
			$args = array(
					'theme_location' => 'project',
					'container' => 'nav',
					'container_class' => 'project-nav',
					'container_id' => 'project-nav',
					'menu_class' => 'menu',
					'menu_id' => '',
					'echo' => true,
					'fallback_cb' => 'wp_page_menu',
					'before' => '',
					'after' => '',
					'link_before' => '',
					'link_after' => '',
					'depth' => 1,
					'walker' => ''
				);
				wp_nav_menu( $args ); ?>
		</div>
		<?php the_title( '<h1>', '</h1>'); ?>
		<?php the_content(); ?>
	</div>

<?php endwhile; endif; ?>


<?php get_footer(); ?>
	