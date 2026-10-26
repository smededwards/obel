<?php
/**
 * Shared footer.
 *
 * @package Obel
 */

?>
<footer class="site-footer site-shell">
	<p><?php bloginfo( 'name' ); ?> <span aria-hidden="true">/</span> <?php bloginfo( 'description' ); ?></p>
	<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Get in touch', 'obel' ); ?> <span aria-hidden="true">↗</span></a>
</footer>
<?php wp_footer(); ?>
</body>
</html>
