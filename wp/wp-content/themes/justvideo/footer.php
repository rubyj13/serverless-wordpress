<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package justvideo
 */

?>
		</div><!-- .clear -->

	</div><!-- #content .site-content -->

	<div id="site-bottom" class="clear">
		
		<div class="container">

			<div class="site-info">

				<?php
					$justvideo_theme = wp_get_theme();
				?>

				&copy; <?php echo esc_html( date("o") ); ?> <?php echo esc_html( get_bloginfo('name') ); ?> - <a href="<?php echo esc_url( $justvideo_theme->get( 'AuthorURI' ) ); ?>"><?php esc_html_e('WordPress Theme', 'justvideo'); ?></a> <?php esc_html_e('by', 'justvideo'); ?> <a href="<?php echo esc_url( $justvideo_theme->get( 'AuthorURI' ) ); ?>"><?php esc_html_e('WPEnjoy', 'justvideo'); ?></a>

			</div><!-- .site-info -->

			<?php 
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'footer-menu', 'menu_class' => 'footer-nav' ) );
				}
			?>	

		</div><!-- .container -->

	</div><!-- #site-bottom -->

</div><!-- #page -->

<div id="back-top">
	<a href="#top" title="<?php esc_attr_e('Back to top', 'justvideo'); ?>"><span class="genericon genericon-collapse"></span></a>
</div>

<?php wp_footer(); ?>

</body>
</html>
