<?php
/**
 * Theme foundation.
 *
 * @package Obel
 */

/**
 * Register editor support.
 *
 * @return void
 */
function obel_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'build/editor.css' );
}
add_action( 'after_setup_theme', 'obel_setup' );

/**
 * Load a compiled entry with WordPress dependency metadata.
 *
 * @param string $entry    Entry name.
 * @param bool   $in_footer Whether to enqueue the script in the footer.
 * @return void
 */
function obel_enqueue_entry( $entry, $in_footer = true ) {
	$base = get_template_directory() . '/build/' . $entry;
	if ( ! file_exists( $base . '.asset.php' ) ) {
		return;
	}
	$asset = require $base . '.asset.php';
	wp_enqueue_script( 'obel-' . $entry, get_template_directory_uri() . '/build/' . $entry . '.js', $asset['dependencies'], $asset['version'], $in_footer );
	if ( 'scripts' === $entry && file_exists( get_template_directory() . '/build/styles.css' ) ) {
		wp_enqueue_style( 'obel-styles', get_template_directory_uri() . '/build/styles.css', array(), (string) filemtime( get_template_directory() . '/build/styles.css' ) );
	}
}

/**
 * Enqueue frontend assets.
 *
 * @return void
 */
function obel_frontend_assets() {
	obel_enqueue_entry( 'scripts' );
}
add_action( 'wp_enqueue_scripts', 'obel_frontend_assets' );

/**
 * Enqueue editor scripts.
 *
 * @return void
 */
function obel_editor_assets() {
	obel_enqueue_entry( 'editor' );
}
add_action( 'enqueue_block_editor_assets', 'obel_editor_assets' );

require_once __DIR__ . '/class-theme.php';
( new \Obel\Theme() )->register();

require_once __DIR__ . '/class-images.php';
( new \Obel\Images() )->register();
