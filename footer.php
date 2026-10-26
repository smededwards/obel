<?php
/**
 * Shared footer.
 *
 * @package Obel
 */

?>
<footer class="site-footer site-shell">
	<p class="site-footer__identity"><?php bloginfo( 'name' ); ?> <span class="site-footer__separator" aria-hidden="true">/</span> <?php bloginfo( 'description' ); ?></p>
	<a class="site-footer__contact" href="<?php printf( '%s', esc_url( home_url( '/contact/' ) ) ); ?>"><?php esc_html_e( 'Get in touch', 'obel' ); ?> <span class="site-footer__separator" aria-hidden="true">↗</span></a>
</footer>
<?php wp_footer(); ?>
</body>
</html>
