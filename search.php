<?php
/**
 * Search results template.
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
			<h1 class="wwu-canvas-archive__title">
				<?php
				printf(
					/* translators: %s: search query. */
					esc_html__( 'Risultati per: %s', 'wwu-canvas' ),
					'<span>' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<?php get_template_part( 'template-parts/loop', 'listing' ); ?>
		<?php else : ?>
			<p class="wwu-canvas-empty"><?php esc_html_e( 'Nessun risultato. Prova con altre parole chiave.', 'wwu-canvas' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
