<div class="left-sidebar">

	<nav id="left-nav" class="left-navigation">

		<?php 
			if ( has_nav_menu( 'left' ) ) {
				wp_nav_menu( array( 'theme_location' => 'left', 'menu_id' => 'left-menu', 'menu_class' => 'sf-menu' ) );
			} else {
		?>

			<ul id="left-menu" class="sf-menu">
				<li><a href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e('Home', 'justvideo'); ?></a></li>
				<?php wp_list_categories('title_li='); ?>
			</ul><!-- .sf-menu -->

		<?php } ?>

	</nav><!-- #left-nav -->	

</div><!-- .left-nav -->