<?php
/**
 * Shared title, metadata and featured image for editorial detail pages.
 *
 * @package Obel
 */

$back_id = isset( $args['back_id'] ) ? absint( $args['back_id'] ) : 0;
?>
<header class="entry__header">
	<?php if ( $back_id ) : ?>
		<a class="entry__back" href="<?php printf( '%s', esc_url( get_permalink( $back_id ) ) ); ?>"><span aria-hidden="true">←</span> <?php printf( '%s', esc_html( get_the_title( $back_id ) ) ); ?></a>
	<?php endif; ?>
	<h1 class="entry__title"><?php the_title(); ?></h1>
	<?php if ( is_singular( 'post' ) ) : ?>
		<p class="entry__meta"><time datetime="<?php printf( '%s', esc_attr( get_the_date( DATE_W3C ) ) ); ?>"><?php printf( '%s', esc_html( get_the_date( 'j F Y' ) ) ); ?></time></p>
	<?php elseif ( has_excerpt() ) : ?>
		<p class="entry__summary"><?php printf( '%s', esc_html( get_the_excerpt() ) ); ?></p>
	<?php endif; ?>
</header>
<?php if ( has_post_thumbnail() ) : ?>
	<figure class="entry__figure"><?php the_post_thumbnail( 'large', array( 'class' => 'entry__image' ) ); ?></figure>
<?php endif; ?>
