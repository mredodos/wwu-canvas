<?php
/**
 * Single entry renderer (posts, pages, generic CPTs).
 *
 * Used inside the loop by single.php / page.php / index.php (singular branch).
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'wwu-canvas-entry' ); ?>>
	<header class="wwu-canvas-entry__header">
		<h1 class="wwu-canvas-entry__title"><?php the_title(); ?></h1>
		<?php if ( 'post' === get_post_type() ) : ?>
			<p class="wwu-canvas-entry__meta"><?php echo esc_html( get_the_date() ); ?></p>
		<?php endif; ?>
	</header>

	<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
		<figure class="wwu-canvas-entry__thumbnail"><?php the_post_thumbnail( 'large' ); ?></figure>
	<?php endif; ?>

	<div class="wwu-canvas-entry__content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<nav class="wwu-canvas-entry__pages">' . esc_html__( 'Pagine:', 'wwu-canvas' ) . ' ',
				'after'  => '</nav>',
			)
		);
		?>
	</div>
</article>
