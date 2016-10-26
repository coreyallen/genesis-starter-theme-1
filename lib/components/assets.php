<?php
/**
 * Enqueue Assets.
 *
 * @author  Craig Simpson
 * @package Project\Theme
 * @since   1.0.0
 */

namespace Project\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\load_scripts_styles' );
/**
 * Enqueue scripts and styles.
 *
 * @since 1.0
 */
function load_scripts_styles() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	/**
	 * Load Bower components, concatenated to vendor files.
	 */
	wp_enqueue_style( 'vendor', CHILD_THEME_URI . '/vendor/vendor' . $suffix . '.css' );
	wp_register_script( 'vendor', CHILD_THEME_URI . '/vendor/vendor' . $suffix . '.js', [ ], CHILD_THEME_VERSION, true );

	/**
	 * Load other custom scripts and styles.
	 */
	wp_enqueue_style( 'fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700' );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_script( 'gst-theme', CHILD_THEME_URI . '/js/theme' . $suffix . '.js', [ 'jquery', 'vendor' ], CHILD_THEME_VERSION, true );
}

add_filter( 'genesis_pre_load_favicon', __NAMESPACE__ . '\pre_load_favicon' );
/**
 * Simple favicon override to specify your favicon's location.
 *
 * @since 1.0
 */
function pre_load_favicon() {

	return CHILD_THEME_URI . '/assets/images/favicon.ico';

}