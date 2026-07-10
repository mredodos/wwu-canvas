<?php
/**
 * Single post template.
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
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/entry', 'single' );
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
