<?php
/**
 * Theme presentation setup.
 *
 * @package Obel
 */

namespace Obel;

/**
 * Registers shared presentation behaviour.
 */
final class Theme {
	/**
	 * Register theme hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) );
		add_filter( 'wp_preload_resources', array( $this, 'preload_fonts' ) );
		add_filter( 'aeon_seo_person_schema', array( $this, 'person_schema' ) );
		add_filter( 'wp_robots', array( $this, 'noindex_taxonomy_archives' ), 100 );
		add_filter( 'wp_sitemaps_taxonomies', array( $this, 'exclude_taxonomies_from_sitemap' ) );
		add_filter( 'render_block_core/button', array( $this, 'colour_toggle' ), 10, 2 );
		add_filter( 'render_block_core/post-excerpt', array( $this, 'work_card_read_more' ), 10, 3 );
		add_filter( 'render_block_core/post-navigation-link', array( $this, 'post_navigation_link' ), 10, 2 );
	}

	/**
	 * Register editorial support.
	 *
	 * @return void
	 */
	public function setup(): void {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_editor_style( $this->asset_url( 'editor.css' ) );
		add_post_type_support( 'page', 'excerpt' );
	}

	/**
	 * Load the early colour preference before the first paint.
	 *
	 * @return void
	 */
	public function assets(): void {
		$this->enqueue_entry( 'colour-mode-preference', false );
		$this->enqueue_entry( 'scripts' );
	}

	/**
	 * Load editor assets.
	 *
	 * @return void
	 */
	public function editor_assets(): void {
		$this->enqueue_entry( 'editor' );
	}

	/**
	 * Render post navigation links with the project navigation markup.
	 *
	 * @param string               $content Rendered block content.
	 * @param array<string,mixed> $block   Block data.
	 * @return string Navigation link markup.
	 */
	public function post_navigation_link( string $content, array $block ): string {
		$type = $block['attrs']['type'] ?? 'next';
		$post = 'previous' === $type ? get_previous_post() : get_next_post();

		if ( ! $post instanceof \WP_Post ) {
			return '';
		}

		$label = 'previous' === $type ? __( 'Previous Post', 'obel' ) : __( 'Next Post', 'obel' );
		$title = get_the_title( $post );
		$aria  = sprintf( '%1$s: %2$s', $label, $title );

		return sprintf(
			'<p class="entry__navigation-item entry__navigation-item--%1$s wp-block-paragraph"><span class="entry__navigation-label">%2$s</span><a aria-label="%3$s" title="%3$s" href="%4$s" class="entry__navigation-link">%5$s</a></p>',
			esc_attr( $type ),
			esc_html( $label ),
			esc_attr( $aria ),
			esc_url( get_permalink( $post ) ),
			esc_html( $title )
		);
	}

	/**
	 * Preload the fonts used above the fold.
	 *
	 * @param array<int,array<string,string>> $resources Existing preload resources.
	 * @return array<int,array<string,string>> Updated preload resources.
	 */
	public function preload_fonts( array $resources ): array {
		$font_directory = get_template_directory() . '/build/fonts/';
		$font_uri       = get_template_directory_uri() . '/build/fonts/';

		foreach ( array( 'noto-sans', 'noto-serif' ) as $font_name ) {
			$font_files = glob( $font_directory . $font_name . '.*.woff2' );

			if ( empty( $font_files ) ) {
				continue;
			}

			$resources[] = array(
				'href'        => $font_uri . basename( $font_files[0] ),
				'as'          => 'font',
				'type'        => 'font/woff2',
				'crossorigin' => 'anonymous',
			);
		}

		return $resources;
	}

	/**
	 * Load a compiled entry with WordPress dependency metadata.
	 *
	 * @param string $entry    Entry name.
	 * @param bool   $in_footer Whether to enqueue the script in the footer.
	 * @return void
	 */
	private function enqueue_entry( string $entry, bool $in_footer = true ): void {
		$base = get_template_directory() . '/build/' . $entry;
		if ( ! file_exists( $base . '.asset.php' ) ) {
			return;
		}
		$asset = require $base . '.asset.php';
		$script_path = $base . '.js';
		$script_ver  = file_exists( $script_path ) ? (string) filemtime( $script_path ) : $asset['version'];

		wp_enqueue_script( 'obel-' . $entry, $this->asset_url( $entry . '.js' ), $asset['dependencies'], $script_ver, $in_footer );

		if ( $entry === 'scripts' && file_exists( get_template_directory() . '/build/styles.css' ) ) {
			wp_enqueue_style( 'obel-styles', $this->asset_url( 'styles.css' ), array(), null );
		}
	}

	/**
	 * Return a compiled asset URL with its file modification time as the version.
	 *
	 * @param string $file Compiled file name inside build/.
	 * @return string Versioned asset URL.
	 */
	private function asset_url( string $file ): string {
		$path = get_theme_file_path( 'build/' . $file );
		$url  = get_theme_file_uri( 'build/' . $file );

		if ( file_exists( $path ) ) {
			$url = add_query_arg( 'ver', (string) filemtime( $path ), $url );
		}

		return $url;
	}

	/**
	 * Add verified portfolio identity details to Aeon's Person schema node.
	 *
	 * @param array $person Person schema properties supplied by Aeon.
	 * @return array Person schema with the site's professional identity.
	 */
	public function person_schema( array $person ): array {
		$person['jobTitle'] = __( 'Senior WordPress Engineer', 'obel' );
		$person['sameAs']   = array(
			'https://www.linkedin.com/in/smededwards/',
			'https://github.com/smededwards',
		);
		$about_page         = get_page_by_path( 'about', OBJECT, 'page' );
		if ( $about_page instanceof \WP_Post && is_post_publicly_viewable( $about_page ) ) {
			$description = trim( wp_strip_all_tags( get_the_excerpt( $about_page ) ) );
			if ( $description !== '' ) {
				$person['description'] = $description;
			}
		}

		static $profile_image_url = null;
		if ( $profile_image_url === null ) {
			$profile_image     = get_page_by_path( 'profile', OBJECT, 'attachment' );
			$profile_image_url = $profile_image instanceof \WP_Post
				? wp_get_attachment_image_url( $profile_image->ID, 'full' )
				: false;
		}

		if ( $profile_image_url ) {
			$person['image'] = $profile_image_url;
		}

		return $person;
	}

	/**
	 * Keep category and tag archives available to visitors without indexing them.
	 *
	 * @param array $robots Existing robots directives.
	 * @return array Robots directives with taxonomy archives set to noindex,follow.
	 */
	public function noindex_taxonomy_archives( array $robots ): array {
		if ( ! is_category() && ! is_tag() ) {
			return $robots;
		}

		unset( $robots['index'], $robots['nofollow'] );
		$robots['noindex'] = true;
		$robots['follow']  = true;

		return $robots;
	}

	/**
	 * Keep taxonomy archive URLs out of the native WordPress sitemap.
	 *
	 * @param array $taxonomies Public taxonomy objects keyed by taxonomy name.
	 * @return array Taxonomies available to the sitemap provider.
	 */
	public function exclude_taxonomies_from_sitemap( array $taxonomies ): array {
		unset( $taxonomies['category'], $taxonomies['post_tag'] );

		return $taxonomies;
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
			$tags->set_attribute( 'aria-label', __( 'Toggle Colour Mode', 'obel' ) );
		}
		return $tags->get_updated_html();
	}

	/**
	 * Add the project title to the Work card excerpt link.
	 *
	 * @param string    $content  Rendered excerpt markup.
	 * @param array     $block    Parsed block data.
	 * @param \WP_Block $instance Current block instance.
	 * @return string Excerpt markup with a contextual link title.
	 */
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
		if ( $post_title === '' ) {
			return $content;
		}

		$tags = new \WP_HTML_Tag_Processor( $content );
		if (
			! $tags->next_tag(
				array(
					'tag_name'   => 'a',
					'class_name' => 'wp-block-post-excerpt__more-link',
				)
			)
		) {
			return $content;
		}

		/*
		 * translators: %s: Work item title.
		 */
		$read_more_label = sprintf( __( 'Read more about %s', 'obel' ), $post_title );
		$tags->set_attribute( 'aria-label', $read_more_label );
		$tags->set_attribute( 'title', $read_more_label );

		return $tags->get_updated_html();
	}
}
