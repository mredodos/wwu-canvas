<?php
/**
 * Chrome dispatch — header region.
 *
 * Order: CVB fragment chrome (bridge) → minimal fallback → nothing.
 *   - Toggle A OFF           → no output (blank contract).
 *   - Bridge returns HTML    → echo verbatim (pre-compiled trusted output).
 *   - Bridge returns ''      → minimal fallback header (logo + menu).
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

$wwu_canvas_chrome_header = WWU_Canvas_Cvb_Bridge::render_chrome( 'header' );

if ( '' !== $wwu_canvas_chrome_header ) {
	/*
	 * Pre-compiled CVB fragment output: already escaped by the CVB pipeline
	 * (TemplatePartEmitter). Admin-authored trust boundary (BUILDER_ACCESS)
	 * — do NOT re-escape, it would destroy the markup. See theme SPEC §9.
	 */
	echo $wwu_canvas_chrome_header; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

WWU_Canvas_Fallback_Chrome::render( 'header' );
