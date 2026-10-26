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
	public function register() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
	}

	/** Register the primary menu location. */
	public function setup() {
		register_nav_menus( array( 'primary' => __( 'Primary navigation', 'obel' ) ) );
	}

	/** Load the early colour preference before the first paint. */
	public function assets() {
		wp_enqueue_script( 'obel-colour-mode', get_template_directory_uri() . '/assets/colour-mode.js', array(), (string) filemtime( get_template_directory() . '/assets/colour-mode.js' ), false );
	}

	/** Render links until a menu has been assigned. */
	public static function navigation() {
		echo '<ul class="menu">';
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
			echo '<li><a href="' . esc_url( get_permalink( $page ) ) . '"' . ( $current ? ' aria-current="page"' : '' ) . '>' . esc_html( $label ) . '</a></li>';
		}
		echo '</ul>';
	}
}
