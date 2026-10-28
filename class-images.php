<?php
/**
 * Image sizes matched to the portfolio layouts.
 *
 * @package Obel
 */

namespace Obel;

/** Keeps useful responsive sizes without generating overlapping core variants. */
final class Images {

	/** Register generation policy and activation defaults. */
	public function register(): void {
		add_filter( 'intermediate_image_sizes', array( $this, 'generated_sizes' ) );
		add_action( 'after_switch_theme', array( $this, 'defaults' ) );
	}

	/** Apply native, administrator-editable Media settings on theme activation. */
	public function defaults(): void {
		foreach ( array(
			'thumbnail_size_w'   => 150,
			'thumbnail_size_h'   => 150,
			'thumbnail_crop'     => 1,
			'medium_size_w'      => 640,
			'medium_size_h'      => 640,
			'large_size_w'       => 1120,
			'large_size_h'       => 1120,
			'image_default_size' => 'large',
		) as $option => $value ) {
			update_option( $option, $value );
		}
	}

	/**
	 * Remove redundant core sizes while retaining sizes supplied by other plugins.
	 *
	 * @param string[] $sizes Registered intermediate size names.
	 * @return string[] Sizes to generate.
	 */
	public function generated_sizes( array $sizes ): array {
		return array_values( array_diff( $sizes, array( 'medium_large', '1536x1536', '2048x2048' ) ) );
	}
}
