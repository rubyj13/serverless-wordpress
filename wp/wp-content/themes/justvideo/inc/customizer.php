<?php
/**
 * JustVideo Theme Customizer.
 *
 * @package justvideo
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function justvideo_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'justvideo_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function justvideo_customize_preview_js() {
	wp_enqueue_script( 'justvideo_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'justvideo_customize_preview_js' );

function justvideo_reset_mytheme_options() { 
    remove_theme_mods();
}
add_action( 'after_switch_theme', 'justvideo_reset_mytheme_options' );