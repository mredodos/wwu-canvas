<?php
/**
 * Theme header: document head + body open + chrome dispatch.
 *
 * Used by the theme's own templates AND by CVB's theme-embedded render mode
 * (frontend-page-embedded.php calls get_header()). Keep wp_head() +
 * wp_body_open() in place — CVB hooks both for fragment emission and chrome.
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#wwu-canvas-main"><?php esc_html_e( 'Salta al contenuto', 'wwu-canvas' ); ?></a>
<?php
get_template_part( 'template-parts/chrome', 'header' );

/*
 * Skip-link landing point for CVB theme-embedded pages.
 *
 * Those pages call get_header() but then render CVB's own <main> (see
 * frontend-page-embedded.php), which carries no id — the skip link above
 * would target an anchor that does not exist in the document. The theme's
 * own templates already open <main id="wwu-canvas-main">, hence the guard:
 * emitting the anchor there as well would duplicate the id.
 *
 * tabindex="-1" makes the anchor programmatically focusable, so the jump
 * moves keyboard focus and not merely the scroll position.
 */
if ( WWU_Canvas_Cvb_Bridge::is_embedded_cvb_page() ) {
	echo '<span id="wwu-canvas-main" tabindex="-1" class="screen-reader-text"></span>';
}
?>
