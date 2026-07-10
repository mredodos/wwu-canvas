<?php
/**
 * Archive template (categories, tags, authors, dates, CPT archives).
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
		<?php if ( have_posts() ) : ?>
			<header class="wwu-canvas-archive__header">
				<?php
				the_archive_title( '<h1 class="wwu-canvas-archive__title">', '</h1>' );
				the_archive_description( '<div class="wwu-canvas-archive__description">', '</div>' );
				?>
			</header>
			<?php get_template_part( 'template-parts/loop', 'listing' ); ?>
		<?php else : ?>
			<p class="wwu-canvas-empty"><?php esc_html_e( 'Nessun contenuto trovato.', 'wwu-canvas' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
