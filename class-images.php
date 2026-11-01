<?php
/**
 * Image sizes matched to the portfolio layouts.
 *
 * @package Obel
 */

namespace Obel;

/**
 * Keeps useful responsive sizes without generating overlapping core variants.
 */
final class Images {

	/**
	 * Register a card image size, responsive filters, and activation defaults.
	 *
	 * @return void
	 */
	public function register(): void {
		add_image_size( 'obel-card-small', 480, 360, true );
		add_filter( 'intermediate_image_sizes', array( $this, 'generated_sizes' ) );
		add_filter( 'render_block_data', array( $this, 'featured_image_size' ) );
		add_filter( 'render_block_core/post-featured-image', array( $this, 'responsive_featured_image_sizes' ), 10, 2 );
		add_action( 'after_switch_theme', array( $this, 'defaults' ) );
	}

	/**
	 * Apply native, administrator-editable Media settings on theme activation.
	 *
	 * @return void
	 */
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

	/**
	 * Use the generated medium image for archive and homepage cards.
	 *
	 * @param array $block Parsed block data.
	 * @return array Block data with the card image size set to Medium.
	 */
	public function featured_image_size( array $block ): array {
		if ( 'core/post-featured-image' !== ( $block['blockName'] ?? '' ) ) {
			return $block;
		}

		$class_names = preg_split( '/\s+/', trim( $block['attrs']['className'] ?? '' ) );
		$class_names = false === $class_names ? array() : $class_names;

		if ( ! in_array( 'archive-card__image', $class_names, true ) && ! in_array( 'work-card__image', $class_names, true ) ) {
			return $block;
		}

		$block['attrs']['sizeSlug'] = 'medium';

		return $block;
	}

	/**
	 * Match responsive image source selection to the rendered card or entry width.
	 *
	 * @param string $content Rendered featured-image block HTML.
	 * @param array  $block   Parsed featured-image block data.
	 * @return string Featured-image HTML with a layout-specific sizes hint.
	 */
	public function responsive_featured_image_sizes( string $content, array $block ): string {
		$class_names = preg_split( '/\s+/', trim( $block['attrs']['className'] ?? '' ) );
		$class_names = false === $class_names ? array() : $class_names;

		if ( in_array( 'archive-card__image--row', $class_names, true ) ) {
			$sizes = '(max-width: 600px) calc(100vw - 3rem), 240px';
		} elseif ( in_array( 'work-card__image', $class_names, true ) ) {
			$sizes = is_front_page()
				? '(max-width: 600px) calc(100vw - 3rem), (max-width: 960px) 46vw, (max-width: 1200px) 30vw, 357px'
				: '(max-width: 782px) calc(100vw - 3rem), (max-width: 1200px) 30vw, 357px';
		} elseif ( in_array( 'archive-card__image', $class_names, true ) ) {
			$sizes = '(max-width: 600px) calc(100vw - 3rem), (max-width: 1200px) 46vw, 544px';
		} elseif ( in_array( 'entry__figure', $class_names, true ) ) {
			$sizes = '(max-width: 960px) calc(100vw - 3rem), 960px';
		} else {
			return $content;
		}

		$tags = new \WP_HTML_Tag_Processor( $content );
		if ( ! $tags->next_tag( 'IMG' ) ) {
			return $content;
		}

		$tags->set_attribute( 'sizes', $sizes );

		return $tags->get_updated_html();
	}
}
