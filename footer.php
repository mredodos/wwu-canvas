<?php
/**
 * Theme footer: chrome dispatch + wp_footer.
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'template-parts/chrome', 'footer' );

wp_footer();
?>
</body>
</html>
