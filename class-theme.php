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
	}

	/** Register the primary menu location. */
	public function setup(): void {
		register_nav_menus( array( 'primary' => __( 'Primary navigation', 'obel' ) ) );
	}

	/** Load the early colour preference before the first paint. */
	public function assets(): void {
		wp_enqueue_script( 'obel-colour-mode', get_template_directory_uri() . '/assets/colour-mode.js', array(), (string) filemtime( get_template_directory() . '/assets/colour-mode.js' ), false );
	}

	/**
	 * Provide fallback links with the same output contract as wp_nav_menu().
	 *
	 * @param array $args Menu arguments, including the output preference.
	 * @return string|null Menu markup when output is disabled, otherwise null.
	 */
	public static function navigation( array $args = array() ): ?string {
		$markup = self::navigation_markup();
		if ( isset( $args['echo'] ) && ! $args['echo'] ) {
			return $markup;
		}
		printf( '%s', wp_kses_post( $markup ) );
		return null;
	}

	/**
	 * Build fallback navigation independently of the output boundary.
	 *
	 * @return string Menu markup containing escaped page links.
	 */
	private static function navigation_markup(): string {
		$items = '';
		foreach ( array(
			'about'    => __( 'About', 'obel' ),
			'work'     => __( 'Work', 'obel' ),
			'projects' => __( 'Projects', 'obel' ),
			'posts'    => __( 'Posts', 'obel' ),
			'contact'  => __( 'Contact', 'obel' ),
		) as $slug => $label ) {
			$page = get_page_by_path( $slug );
			if ( ! $page || 'publish' !== $page->post_status ) {
				continue;
			}
			$current = (int) get_queried_object_id() === (int) $page->ID;
			$items  .= sprintf(
				'<li><a href="%s"%s>%s</a></li>',
				esc_url( get_permalink( $page ) ),
				$current ? ' aria-current="page"' : '',
				esc_html( $label )
			);
		}
		return sprintf( '<ul class="menu site-nav__list">%s</ul>', $items );
	}
}
