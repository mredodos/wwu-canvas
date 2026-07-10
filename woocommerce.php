<?php
/**
 * WooCommerce wrapper template.
 *
 * Declared WC support routes all WooCommerce views (without a CVB template
 * override) through this single wrapper; WooCommerce's own frontend CSS
 * handles product grids/buttons — the theme only provides the readable
 * container. The --woocommerce modifier widens the content column (shop
 * grids need more than the 720px prose width).
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
	<div class="wwu-canvas-content wwu-canvas-content--woocommerce">
		<?php woocommerce_content(); ?>
	</div>
</main>
<?php
get_footer();
