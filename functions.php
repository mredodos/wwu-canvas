<?php
/**
 * WWU Canvas — theme bootstrap.
 *
 * Neutral companion theme for WWU Code and Visual Builder (CVB).
 * Behavior lives in inc/ classes; this file defines constants, loads them
 * and wires the conditional asset enqueue.
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WWU_CANVAS_VERSION', '0.1.1-alpha' );
define( 'WWU_CANVAS_DIR', get_template_directory() );
define( 'WWU_CANVAS_URI', get_template_directory_uri() );

require_once WWU_CANVAS_DIR . '/inc/class-canvas-setup.php';
require_once WWU_CANVAS_DIR . '/inc/class-canvas-customizer.php';
require_once WWU_CANVAS_DIR . '/inc/class-canvas-cvb-bridge.php';
require_once WWU_CANVAS_DIR . '/inc/class-canvas-fallback-chrome.php';

WWU_Canvas_Setup::init();
WWU_Canvas_Customizer::init();
WWU_Canvas_Cvb_Bridge::init();

/**
 * Enqueue theme assets (budget discipline — see SPEC §10).
 *
 * base.css (~3 KB: a11y helpers + fallback chrome) loads on every page the
 * theme renders; typography.css (~8 KB) only when the Customizer toggle is
 * ON and the request is NOT a CVB page.
 *
 * On CVB STANDALONE pages `wp_enqueue_scripts` still fires even though the
 * theme templates never render — enqueueing theme CSS there would reproduce
 * the exact third-party-theme bleed this theme exists to eliminate, so
 * everything is skipped. On CVB theme-embedded pages the theme chrome DOES
 * render, so base.css loads (typography stays off: builder content styles
 * itself, and typography.css is scoped to .wwu-canvas-content anyway).
 *
 * @since 0.1.0
 * @return void
 */
function wwu_canvas_enqueue_assets() {
	$is_cvb_page = WWU_Canvas_Cvb_Bridge::is_cvb_page();

	if ( $is_cvb_page && ! WWU_Canvas_Cvb_Bridge::is_embedded_cvb_page() ) {
		// Standalone CVB page: the document is 100% CVB-owned. Stay out.
		return;
	}

	wp_enqueue_style(
		'wwu-canvas-base',
		WWU_CANVAS_URI . '/assets/css/base.css',
		array(),
		WWU_CANVAS_VERSION
	);

	if ( ! $is_cvb_page && WWU_Canvas_Customizer::typography_enabled() ) {
		wp_enqueue_style(
			'wwu-canvas-typography',
			WWU_CANVAS_URI . '/assets/css/typography.css',
			array(),
			WWU_CANVAS_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'wwu_canvas_enqueue_assets' );
