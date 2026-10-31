<?php
/**
 * Title: Site brand
 * Slug: obel/site-brand
 * Inserter: no
 *
 * @package Obel
 */

?>
<!-- wp:group {"className":"site-header__brand","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group site-header__brand">
<!-- wp:image {"width":"36px","height":"75px","sizeSlug":"full","linkDestination":"custom","className":"site-header__logo"} -->
<figure class="wp-block-image size-full is-resized site-header__logo"><a href="<?php printf( '%s', esc_url( home_url( '/' ) ) ); ?>"><img src="<?php printf( '%s', esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ) ); ?>" alt="<?php printf( '%s', esc_attr( get_bloginfo( 'name' ) ) ); ?>" style="width:36px;height:75px"/></a></figure>
<!-- /wp:image -->
<!-- wp:site-title {"level":0,"className":"site-header__name"} /-->
</div>
<!-- /wp:group -->
