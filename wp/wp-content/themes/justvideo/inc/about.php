<?php
/**
 * JustVideo Theme page
 *
 * @package justvideo
 */

function justvideo_about_admin_style( $hook ) {
	if ( 'appearance_page_justvideo-about' === $hook ) {
		wp_enqueue_style( 'justvideo-about-admin', get_theme_file_uri( 'assets/css/about-admin.css' ), null, '1.0' );
	}
}
add_action( 'admin_enqueue_scripts', 'justvideo_about_admin_style' );

/**
 * Add theme page
 */
function justvideo_menu() {
	add_theme_page( esc_html__( 'About JustVideo', 'justvideo' ), esc_html__( 'About JustVideo', 'justvideo' ), 'edit_theme_options', 'justvideo-about', 'justvideo_about_display' );
}
add_action( 'admin_menu', 'justvideo_menu' );

/**
 * Display About page
 */
function justvideo_about_display() {
	$theme = wp_get_theme();
	?>
	<div class="wrap about-wrap full-width-layout">
		<h1><?php echo esc_html( $theme ); ?></h1>
		<div class="about-theme">
			<div class="theme-description">
				<p class="about-text">
					<?php
					// Remove last sentence of description.
					$description = explode( '. ', $theme->get( 'Description' ) );

					array_pop( $description );

					$description = implode( '. ', $description );

					echo esc_html( $description . '.' );
				?></p>
				<p class="actions">
					<a href="<?php echo esc_url( $theme->get( 'ThemeURI' ) ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Theme Demo', 'justvideo' ); ?></a>

					<a href="<?php echo esc_url( $theme->get( 'AuthorURI' ) . '/documentation/justvideo' ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Documentation', 'justvideo' ); ?></a>

					<a href="<?php echo esc_url( $theme->get( 'AuthorURI' ) . '/themes' ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'More Themes', 'justvideo' ); ?></a>
				</p>
			</div>

			<div class="theme-screenshot">
				<img src="<?php echo esc_url( $theme->get_screenshot() ); ?>" />
			</div>

		</div>

	</div>
	<?php
}