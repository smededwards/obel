<?php
/**
 * Minimal fallback template.
 *
 * @package Obel
 */

get_header();
?>
<main id="main" class="site-shell page-content">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class(); ?>>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
