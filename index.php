<?php
/**
 * Main template file — last-resort fallback for every content type.
 *
 * Handles both singular requests (full entry + comments) and listings
 * (archive-style loop) so the theme stays valid with just this template.
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
			<?php if ( is_singular() ) : ?>
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/entry', 'single' );
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
				?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/loop', 'listing' ); ?>
			<?php endif; ?>
		<?php else : ?>
			<p class="wwu-canvas-empty"><?php esc_html_e( 'Nessun contenuto trovato.', 'wwu-canvas' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
