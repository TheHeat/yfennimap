<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package yfennimap
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

		<?php wp_head(); ?>
	</head>

<body <?php body_class(); ?> onload="initialize();">
	<div class="site-wrapper">
		<header id="masthead" class="site-header" role="banner" style="background-image:url(<?php header_image(); ?>);">
			<div id="site-branding">
				<h1 class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				</h1>
			</div>
			<div id="site-navigation"><?php wp_nav_menu('primary' ); ?></div>

			<?php if(is_page_template('map-template.php' )): ?>
				<span class="tool info"></span>
			<?php endif; ?>
		</header>