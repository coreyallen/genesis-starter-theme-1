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

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\load_styles' );
/**
 * Enqueue web fonts or additional stylesheets
 *
 * @since 1.0
 */
function load_styles() {
	wp_enqueue_style( 'fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700' );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\load_scripts' );
/**
 * Enqueue scripts in the footer
 *
 * @since 1.0
 */
function load_scripts() {

	wp_dequeue_script( 'comment-reply' );
	wp_enqueue_script( 'gst-theme', CHILD_THEME_DIRECTORY . '/assets/js/theme.js', [ 'jquery' ], CHILD_THEME_VERSION, true );

}

add_filter( 'genesis_pre_load_favicon', __NAMESPACE__ . '\pre_load_favicon' );
/**
 * Simple favicon override to specify your favicon's location.
 *
 * @since 1.0
 */
function pre_load_favicon() {

	return CHILD_THEME_DIRECTORY . '/assets/images/favicon.ico';

}
