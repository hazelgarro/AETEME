<?php
/**
 * The template for displaying the footer in our theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package     Bloghash
 * @author      Peregrine Themes
 * @since       1.0.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


?>
		<?php do_action( 'bloghash_main_end' ); ?>
		
	</div><!-- #main .site-main -->
	<?php do_action( 'bloghash_after_main' ); ?>

	<?php do_action( 'bloghash_before_colophon', 'before_footer' ); ?>

	<?php if ( bloghash_is_colophon_displayed() ) { ?>
		<footer id="colophon" class="site-footer" role="contentinfo"<?php bloghash_schema_markup( 'footer' ); ?>>
			<!-- Mapa de Google a ancho completo -->
<div class="footer-map-container">
	<iframe 
		src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1964.5887815810831!2d-84.65246106134417!3d10.00218729752585!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8fa0370664aaf59f%3A0xc11e692b12c530f7!2sComplejo%20Deportivo%20Las%20Tres%20Mar%C3%ADas%2C%20Provincia%20de%20Puntarenas%2C%20Esparza!5e0!3m2!1ses!2scr!4v1750385017726!5m2!1ses!2scr" 
		class="footer-map-iframe"
		allowfullscreen="" 
		loading="lazy" 
		referrerpolicy="no-referrer-when-downgrade">
	</iframe>
</div>

			<div class="site-info" style="text-align: center; margin-top: 1rem;">
				<p>Copyright 2025 — <strong>Aeteme</strong>. Todos los derechos reservados.</p>
			</div>

		</footer>
	<?php } ?>

	<?php do_action( 'bloghash_after_colophon', 'after_footer' ); ?>

</div><!-- END #page -->
<?php do_action( 'bloghash_after_page_wrapper' ); ?>

<?php wp_footer(); ?>

</body>
</html>
