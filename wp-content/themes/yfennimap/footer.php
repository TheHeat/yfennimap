<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package yfennimap
 */
?>


	</div>

<?php if(!is_page_template('map-template.php' )): ?>
	<?php $logo_root = get_stylesheet_directory_uri() . '/img/logos/'; ?>
	<footer class="site-footer">
		<div class="footer-logos">
			<div class="logo"><img src="<?php echo $logo_root; ?>cardiff.jpg" alt=""></div>
			<div class="logo"><img src="<?php echo $logo_root; ?>manchester.png" alt=""></div>
			<div class="logo"><img src="<?php echo $logo_root; ?>southampton.png" alt=""></div>
			<div class="logo"><img src="<?php echo $logo_root; ?>ahrc.png" alt=""></div>
		</div>

		<!-- Secondary menu -->
		<div class="secondary-navigation">
			<nav><?php wp_nav_menu(array('theme_location'=>'second',)); ?></nav>
		</div>
	</footer>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>
