<?php
/**
 * Title: Footer logo
 * Slug: obel/footer-logo
 * Inserter: no
 *
 * @package Obel
 */

?>
<!-- wp:image {"width":"26px","height":"32px","sizeSlug":"full","linkDestination":"custom","className":"site-footer__logo"} -->
<figure class="wp-block-image size-full is-resized site-footer__logo"><a href="<?php printf( '%s', esc_url( home_url( '/' ) ) ); ?>"><img src="<?php printf( '%s', esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ) ); ?>" alt="<?php printf( '%s', esc_attr( get_bloginfo( 'name' ) ) ); ?>" style="width:26px;height:32px"/></a></figure>
<!-- /wp:image -->
