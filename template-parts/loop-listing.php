<?php
/**
 * Archive-style listing loop (title link + date + excerpt) + pagination.
 *
 * Used by index.php (non-singular branch), archive.php and search.php.
 * Caller guarantees have_posts() is true.
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'wwu-canvas-entry wwu-canvas-entry--listed' ); ?>>
		<h2 class="wwu-canvas-entry__title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h2>
		<?php if ( 'post' === get_post_type() ) : ?>
			<p class="wwu-canvas-entry__meta"><?php echo esc_html( get_the_date() ); ?></p>
		<?php endif; ?>
		<div class="wwu-canvas-entry__excerpt"><?php the_excerpt(); ?></div>
	</article>
	<?php
endwhile;

the_posts_pagination( array( 'mid_size' => 1 ) );
