<?php
/**
 * Article layout with a focused reading column.
 *
 * @package Obel
 */

get_header();
?>
<main id="main" class="site-shell page-content">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article <?php post_class( 'entry' ); ?>>
			<?php get_template_part( 'template-parts/entry-header', null, array( 'back_id' => (int) get_option( 'page_for_posts' ) ) ); ?>
			<div class="entry__body"><?php the_content(); ?></div>
			<div class="entry__navigation"><?php the_post_navigation(); ?></div>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
