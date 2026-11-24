<?php
/**
 * Title: Footer logo
 * Slug: obel/footer-logo
 * Inserter: no
 *
 * @package Obel
 */

?>
<!-- wp:image {"sizeSlug":"full","linkDestination":"custom","href":"/","className":"site-footer__logo"} -->
<figure class="wp-block-image size-full site-footer__logo">
	<a href="<?php printf( '%s', esc_url( home_url( '/' ) ) ); ?>" aria-label="<?php esc_attr_e( 'Home', 'obel' ); ?>">
		<img
			src="<?php printf( '%s', esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ) ); ?>"
			alt=""
		>
	</a>
</figure>
<!-- /wp:image -->
