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
				<?php
				/*
				 * Listing branch: also the default blog homepage ("Your latest
				 * posts"), since the theme ships no home.php. Every listing must
				 * carry exactly one <h1> — loop-listing.php only emits <h2> per
				 * entry — or the page has no level-1 heading (a11y + SEO gap).
				 * A dedicated "Posts page" gets a visible title (parity with
				 * archive.php/search.php); the default latest-posts front gets a
				 * screen-reader-only site-name h1 so the DOM has an h1 without a
				 * giant heading duplicating the header logo/title.
				 */
				$wwu_canvas_posts_page = (int) get_option( 'page_for_posts' );
				if ( is_home() && ! is_front_page() && $wwu_canvas_posts_page > 0 ) :
					?>
					<header class="wwu-canvas-archive__header">
						<h1 class="wwu-canvas-archive__title"><?php echo esc_html( get_the_title( $wwu_canvas_posts_page ) ); ?></h1>
					</header>
				<?php else : ?>
					<h1 class="wwu-canvas-archive__title screen-reader-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/loop', 'listing' ); ?>
			<?php endif; ?>
		<?php else : ?>
			<p class="wwu-canvas-empty"><?php esc_html_e( 'Nessun contenuto trovato.', 'wwu-canvas' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
