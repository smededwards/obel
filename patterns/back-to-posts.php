<?php
/**
 * Title: Back to Posts
 * Slug: obel/back-to-posts
 * Inserter: no
 *
 * @package Obel
 */

?>
<!-- wp:paragraph {"className":"entry__back"} -->
<p class="entry__back"><a href="<?php printf( '%s', esc_url( home_url( '/posts/' ) ) ); ?>"><?php printf( '%s', esc_html__( '← Posts', 'obel' ) ); ?></a></p>
<!-- /wp:paragraph -->
