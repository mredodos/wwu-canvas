<?php
/**
 * 404 template.
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="wwu-canvas-main" class="wwu-canvas-main">
	<div class="wwu-canvas-content">
		<header class="wwu-canvas-archive__header">
			<h1 class="wwu-canvas-archive__title"><?php esc_html_e( 'Pagina non trovata', 'wwu-canvas' ); ?></h1>
		</header>
		<p><?php esc_html_e( 'La pagina che cerchi non esiste o è stata spostata. Prova una ricerca, oppure torna alla home.', 'wwu-canvas' ); ?></p>
		<?php get_search_form(); ?>
		<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">&larr; <?php esc_html_e( 'Torna alla home', 'wwu-canvas' ); ?></a></p>
	</div>
</main>
<?php
get_footer();
