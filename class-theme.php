<?php
/**
 * Theme presentation setup.
 *
 * @package Obel
 */

namespace Obel;

/** Registers shared presentation behaviour. */
final class Theme {
	/** Register hooks. */
	public function register(): void {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
		add_filter( 'render_block_core/button', array( $this, 'colour_toggle' ), 10, 2 );
		add_filter( 'render_block_core/post-excerpt', array( $this, 'work_card_read_more' ), 10, 3 );
	}

	/** Register editorial support. */
	public function setup(): void {
		add_post_type_support( 'page', 'excerpt' );
	}

	/** Load the early colour preference before the first paint. */
	public function assets(): void {
		wp_enqueue_script( 'obel-colour-mode', get_template_directory_uri() . '/assets/colour-mode.js', array(), (string) filemtime( get_template_directory() . '/assets/colour-mode.js' ), false );
	}

	/**
	 * Hide the enhancement until JavaScript is available.
	 *
	 * @param string $content Rendered button.
	 * @param array  $block Parsed block.
	 * @return string Accessible button markup.
	 */
	public function colour_toggle( string $content, array $block ): string {
		$classes = explode( ' ', $block['attrs']['className'] ?? '' );
		if ( ! in_array( 'colour-toggle', $classes, true ) ) {
			return $content;
		}
		$tags = new \WP_HTML_Tag_Processor( $content );
		if ( $tags->next_tag( 'button' ) ) {
			$tags->set_attribute( 'hidden', true );
			$tags->set_attribute( 'aria-pressed', 'false' );
		}
		return $tags->get_updated_html();
	}

	/** Add the project title to the Work card excerpt link. */
	public function work_card_read_more( string $content, array $block, \WP_Block $instance ): string {
		$classes = preg_split( '/\s+/', $block['attrs']['className'] ?? '', -1, PREG_SPLIT_NO_EMPTY );
		if ( ! in_array( 'work-card__summary', $classes, true ) ) {
			return $content;
		}

		$post_id = $instance->context['postId'] ?? 0;
		if ( ! $post_id ) {
			return $content;
		}

		$post_title = wp_strip_all_tags( get_the_title( $post_id ) );
		if ( '' === $post_title ) {
			return $content;
		}

		$tags = new \WP_HTML_Tag_Processor( $content );
		if ( ! $tags->next_tag( array( 'tag_name' => 'a', 'class_name' => 'wp-block-post-excerpt__more-link' ) ) ) {
			return $content;
		}

		$tags->set_attribute( 'title', sprintf( __( 'Read more about %s', 'obel' ), $post_title ) );

		return $tags->get_updated_html();
	}
}
