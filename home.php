<?php
/**
 * Posts index.
 *
 * @package Obel
 */

get_header();
$posts_page = (int) get_option( 'page_for_posts' );
?>
<main id="main" class="site-shell page-content">
	<h1><?php printf( '%s', esc_html( $posts_page ? get_the_title( $posts_page ) : __( 'Posts', 'obel' ) ) ); ?></h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; ?>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Articles and development notes are on the way.', 'obel' ); ?></p>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
