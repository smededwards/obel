<?php
/**
 * Editor-owned pages and editorial layouts for Work/Projects child pages.
 *
 * @package Obel
 */

get_header();
?>
<main id="main" class="site-shell page-content">
	<?php while ( have_posts() ) : ?>
		<?php
		the_post();
		$parent_id  = wp_get_post_parent_id( get_the_ID() );
		$parent     = $parent_id ? get_post( $parent_id ) : null;
		$is_entry   = $parent && in_array( $parent->post_name, array( 'work', 'projects' ), true );
		$is_listing = ! $parent && in_array( get_post_field( 'post_name', get_the_ID() ), array( 'work', 'projects' ), true );
		?>
		<article <?php post_class( $is_entry ? 'entry' : ( $is_listing ? 'archive-page' : '' ) ); ?>>
			<?php if ( $is_entry ) : ?>
				<?php get_template_part( 'template-parts/entry-header', null, array( 'back_id' => $parent_id ) ); ?>
				<div class="entry__body"><?php the_content(); ?></div>
			<?php else : ?>
				<?php if ( ! is_front_page() ) : ?>
					<header class="<?php printf( '%s', esc_attr( $is_listing ? 'archive-page__header' : 'page-content__header' ) ); ?>"><h1><?php the_title(); ?></h1></header>
				<?php endif; ?>
				<?php the_content(); ?>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
