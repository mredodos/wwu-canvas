<?php
/**
 * Chrome dispatch — footer region.
 *
 * Mirror of chrome-header.php: CVB fragment chrome → fallback → nothing.
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! WWU_Canvas_Customizer::chrome_bridge_enabled() ) {
	return;
}

$wwu_canvas_chrome_footer = WWU_Canvas_Cvb_Bridge::render_chrome( 'footer' );

if ( '' !== $wwu_canvas_chrome_footer ) {
	/*
	 * Pre-compiled CVB fragment output — trusted, do NOT re-escape.
	 * Same rationale as chrome-header.php (theme SPEC §9).
	 */
	echo $wwu_canvas_chrome_footer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

WWU_Canvas_Fallback_Chrome::render( 'footer' );
