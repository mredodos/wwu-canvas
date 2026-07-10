<?php
/**
 * Theme setup: supports, menus, content width, WooCommerce.
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers core theme supports and navigation menus.
 *
 * Deliberately minimal: no editor-styles in MVP (typography.css is scoped to
 * .wwu-canvas-content, which the block editor wrapper does not carry — a
 * dedicated editor stylesheet is a future polish item, noted in CHANGELOG).
 *
 * @since 0.1.0
 */
final class WWU_Canvas_Setup {

	/**
	 * Wire hooks.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function init() {
		add_action( 'after_setup_theme', array( __CLASS__, 'content_width' ), 0 );
		add_action( 'after_setup_theme', array( __CLASS__, 'setup' ) );
		add_action( 'after_setup_theme', array( __CLASS__, 'woocommerce_support' ) );
	}

	/**
	 * Set the global content width (oEmbed max width, media defaults).
	 *
	 * Matches theme.json layout.contentSize.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function content_width() {
		if ( ! isset( $GLOBALS['content_width'] ) ) {
			$GLOBALS['content_width'] = 800;
		}
	}

	/**
	 * Core theme supports + nav menus + textdomain.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function setup() {
		load_theme_textdomain( 'wwu-canvas', WWU_CANVAS_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
		);
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 100,
				'width'       => 350,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		register_nav_menus(
			array(
				'primary' => __( 'Menu principale', 'wwu-canvas' ),
				'footer'  => __( 'Menu footer', 'wwu-canvas' ),
			)
		);
	}

	/**
	 * Declare WooCommerce support (removable via filter — Hello Elementor
	 * pattern, lets non-ecommerce installs strip it with one line).
	 *
	 * WooCommerce's own frontend CSS handles product grids/buttons; the theme
	 * only provides the readable wrapper (woocommerce.php template).
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function woocommerce_support() {
		/**
		 * Filter whether the theme declares WooCommerce support.
		 *
		 * @since 0.1.0
		 * @param bool $add Default true.
		 */
		if ( ! apply_filters( 'wwu_canvas_add_woocommerce_support', true ) ) {
			return;
		}

		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
