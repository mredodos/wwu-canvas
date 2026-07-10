<?php
/**
 * Comments template — minimal list + form.
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="wwu-canvas-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="wwu-canvas-comments__title">
			<?php
			printf(
				/* translators: %s: number of comments. */
				esc_html( _n( '%s commento', '%s commenti', get_comments_number(), 'wwu-canvas' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>

		<ol class="wwu-canvas-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol>

		<?php the_comments_pagination(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="wwu-canvas-comments__closed"><?php esc_html_e( 'I commenti sono chiusi.', 'wwu-canvas' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</section>
