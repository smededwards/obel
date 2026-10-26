<?php
/**
 * Shared document and site header.
 *
 * @package Obel
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'obel' ); ?></a>
<header class="site-header site-shell">
	<a class="site-header__brand" href="<?php printf( '%s', esc_url( home_url( '/' ) ) ); ?>" rel="home">
		<img class="site-header__logo" src="<?php printf( '%s', esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ) ); ?>" width="38" height="46" alt="">
		<span class="site-header__name"><?php bloginfo( 'name' ); ?></span>
	</a>
	<div class="site-header__controls">
		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'obel' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'menu site-nav__list',
					'fallback_cb'    => array( '\Obel\Theme', 'navigation' ),
					'depth'          => 1,
				)
			);
			?>
		</nav>
		<button class="colour-toggle" type="button" data-colour-toggle aria-pressed="false" aria-label="<?php esc_attr_e( 'Dark mode', 'obel' ); ?>" title="<?php esc_attr_e( 'Toggle dark mode', 'obel' ); ?>" hidden>
			<svg class="colour-toggle__icon" viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true" focusable="false">
				<path d="M12,22 C17.5228475,22 22,17.5228475 22,12 C22,6.4771525 17.5228475,2 12,2 C6.4771525,2 2,6.4771525 2,12 C2,17.5228475 6.4771525,22 12,22 Z M12,20.5 L12,3.5 C16.6944204,3.5 20.5,7.30557963 20.5,12 C20.5,16.6944204 16.6944204,20.5 12,20.5 Z" />
			</svg>
		</button>
	</div>
</header>
