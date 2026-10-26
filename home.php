<?php
/**
 * Posts archive, with its native Query Loop saved in the Posts page editor.
 *
 * @package Obel
 */

get_header();
$posts_page = (int) get_option( 'page_for_posts' );
?>
<main id="main" class="site-shell page-content archive-page">
	<header class="archive-page__header"><h1><?php printf( '%s', esc_html( $posts_page ? get_the_title( $posts_page ) : __( 'Posts', 'obel' ) ) ); ?></h1></header>
	<?php
	if ( $posts_page ) {
		// The same WordPress content pipeline used by the_content(); blocks own their output escaping.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( '%s', apply_filters( 'the_content', get_post_field( 'post_content', $posts_page ) ) );
	}
	?>
</main>
<?php get_footer(); ?>
