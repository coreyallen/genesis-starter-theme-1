<?php
/**
 * Cleanup functions.
 *
 * @author  Craig Simpson
 * @package Project\Theme
 * @since   1.0.0
 */

namespace Project\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', __NAMESPACE__ . '\head_cleanup' );
/**
 * Clean up wp_head()
 *
 * Remove unnecessary <link>'s
 * Remove inline CSS and JS from WP emoji support
 * Remove inline CSS used by Recent Comments widget
 * Remove inline CSS used by posts with galleries
 * Remove self-closing tag.
 */
function head_cleanup() {

	add_action( 'wp_head', 'ob_start', 1, 0 );
	add_action( 'wp_head', function () {
		$pattern = '/.*' . preg_quote( esc_url( get_feed_link( 'comments_' . get_default_feed() ) ), '/' ) . '.*[\r\n]+/';
		echo preg_replace( $pattern, '', ob_get_clean() );
	}, 3, 0 );

	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'use_default_gallery_style', '__return_false' );

	global $wp_widget_factory;

	if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
		remove_action( 'wp_head', [
			$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
			'recent_comments_style'
		] );
	}
}

/**
 * Remove the WordPress version from RSS feeds
 */
add_filter( 'the_generator', '__return_false' );

add_filter( 'style_loader_tag', __NAMESPACE__ . '\clean_style_tag' );
/**
 * Clean up output of stylesheet <link> tags.
 *
 * @param $input
 *
 * @return string
 */
function clean_style_tag( $input ) {
	preg_match_all( "!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches );
	if ( empty( $matches[2] ) ) {
		return $input;
	}

	// Only display media if it is meaningful
	$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';

	return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}

add_filter( 'script_loader_tag', __NAMESPACE__ . '\clean_script_tag' );
/**
 * Clean up output of <script> tags.
 *
 * @param $input
 *
 * @return mixed
 */
function clean_script_tag( $input ) {
	$input = str_replace( "type='text/javascript' ", '', $input );

	return str_replace( "'", '"', $input );
}

add_filter( 'get_avatar', __NAMESPACE__ . '\remove_self_closing_tags' ); // <img />
add_filter( 'comment_id_fields', __NAMESPACE__ . '\remove_self_closing_tags' ); // <input />
add_filter( 'post_thumbnail_html', __NAMESPACE__ . '\remove_self_closing_tags' ); // <img />
/**
 * Remove unnecessary self-closing tags.
 *
 * @param $input
 *
 * @return mixed
 */
function remove_self_closing_tags( $input ) {
	return str_replace( ' />', '>', $input );
}
