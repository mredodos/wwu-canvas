<?php
/**
 * CVB bridge adapter — the ONLY file in the theme allowed to reference
 * WWU Code and Visual Builder symbols or contract keys.
 *
 * Everything is guarded: with CVB absent (or older than the ThemeBridge
 * public API) every method degrades to a safe default and the theme works
 * as a plain blank theme with its own fallback chrome.
 *
 * Consumed CVB contract (see wwu-code-and-visual-builder/docs/specs/
 * wwu-cvb-theme-bridge-SPEC.md — handoff 2026-06-10):
 *   - class \WWU\CodeVisualBuilder\Frontend\ThemeBridge
 *       ::render_chrome( 'header'|'footer' ): string
 *       ::emit_global_fragments( 'head'|'footer' ): void
 *       ::is_cvb_page(): bool
 *   - CPT name 'wwu_cvb_page' (stable since CVB F0)
 *   - post meta '_wwu_cvb_render_mode' + option wwu_cvb_settings
 *     ['default_render_mode'] (RenderModeResolver contract, read-only)
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Guarded adapter toward the CVB ThemeBridge public API.
 *
 * @since 0.1.0
 */
final class WWU_Canvas_Cvb_Bridge {

	/**
	 * Fully-qualified name of the CVB public bridge class.
	 *
	 * @var string
	 */
	const THEME_BRIDGE_CLASS = 'WWU\\CodeVisualBuilder\\Frontend\\ThemeBridge';

	/**
	 * CVB page CPT name (stable CVB contract).
	 *
	 * @var string
	 */
	const CVB_PAGE_TYPE = 'wwu_cvb_page';

	/**
	 * Per-page render mode meta key (CVB RenderModeResolver contract).
	 *
	 * @var string
	 */
	const CVB_RENDER_MODE_META = '_wwu_cvb_render_mode';

	/**
	 * CVB site settings option name.
	 *
	 * @var string
	 */
	const CVB_SETTINGS_OPTION = 'wwu_cvb_settings';

	/**
	 * Per-request cache for is_embedded_cvb_page().
	 *
	 * @var bool|null
	 */
	private static $embedded_cache = null;

	/**
	 * Wire hooks.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function init() {
		// Late priority so auto_global fragments land after SEO/analytics tags.
		add_action( 'wp_head', array( __CLASS__, 'emit_global_fragments_head' ), 99 );
		add_action( 'wp_footer', array( __CLASS__, 'emit_global_fragments_footer' ), 99 );
		add_action( 'admin_notices', array( __CLASS__, 'maybe_bridge_update_notice' ) );
	}

	/**
	 * Whether the CVB plugin is active at all.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public static function cvb_active() {
		return class_exists( 'WWU\\CodeVisualBuilder\\Plugin' );
	}

	/**
	 * Whether the CVB ThemeBridge public API is available.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public static function bridge_available() {
		return class_exists( self::THEME_BRIDGE_CLASS );
	}

	/**
	 * Whether the current request renders a CVB page (either mode).
	 *
	 * Local is_singular() check on purpose: it works at wp_enqueue_scripts
	 * time even when ThemeBridge is absent.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public static function is_cvb_page() {
		return is_singular( self::CVB_PAGE_TYPE );
	}

	/**
	 * Whether the current request is a CVB page in THEME-EMBEDDED render
	 * mode (per-page meta beats the site default, which defaults to
	 * standalone — mirrors CVB RenderModeResolver, read-only).
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public static function is_embedded_cvb_page() {
		if ( null !== self::$embedded_cache ) {
			return self::$embedded_cache;
		}

		if ( ! self::is_cvb_page() ) {
			self::$embedded_cache = false;
			return false;
		}

		$mode = (string) get_post_meta( get_queried_object_id(), self::CVB_RENDER_MODE_META, true );

		if ( '' === $mode ) {
			$settings = get_option( self::CVB_SETTINGS_OPTION, array() );
			if ( is_array( $settings ) && isset( $settings['default_render_mode'] ) ) {
				$mode = (string) $settings['default_render_mode'];
			}
		}

		self::$embedded_cache = ( 'theme-embedded' === $mode );

		return self::$embedded_cache;
	}

	/**
	 * Render the CVB chrome for a region via the ThemeBridge.
	 *
	 * Returns '' whenever the bridge is unavailable or unconfigured — the
	 * chrome dispatch templates fall back to the minimal theme chrome.
	 *
	 * The returned HTML is pre-compiled, admin-authored fragment output
	 * escaped by CVB's own pipeline (TemplatePartEmitter). Callers echo it
	 * verbatim — re-escaping would destroy the markup (SPEC §9 trust
	 * boundary).
	 *
	 * @since 0.1.0
	 * @param string $kind 'header' or 'footer'.
	 * @return string Chrome HTML or ''.
	 */
	public static function render_chrome( $kind ) {
		if ( ! self::bridge_available() ) {
			return '';
		}

		$html = call_user_func( array( self::THEME_BRIDGE_CLASS, 'render_chrome' ), (string) $kind );

		return is_string( $html ) ? $html : '';
	}

	/**
	 * Emit auto_global fragments in the head of non-CVB pages.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function emit_global_fragments_head() {
		self::emit_global_fragments( 'head' );
	}

	/**
	 * Emit auto_global fragments in the footer of non-CVB pages.
	 *
	 * ThemeBridge exposes 'footer' as a Day-1 position (JS / HTML / PHP —
	 * CSS is head-only by FragmentResolver design) and the theme is its
	 * only consumer: without this hook every `auto_global` footer fragment
	 * an admin authors in CVB would silently never render.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public static function emit_global_fragments_footer() {
		self::emit_global_fragments( 'footer' );
	}

	/**
	 * Shared emitter for one auto_global fragment position.
	 *
	 * CVB pages emit their own fragments from their templates — the guard
	 * prevents double emission (ThemeBridge guards too; belt and braces).
	 * Deliberately NOT gated by toggle A: global fragments are a CVB
	 * authoring decision (enqueue_mode=auto_global), not theme chrome.
	 *
	 * @since 0.1.1
	 * @param string $position 'head' or 'footer'.
	 * @return void
	 */
	private static function emit_global_fragments( $position ) {
		if ( ! self::bridge_available() || self::is_cvb_page() ) {
			return;
		}

		call_user_func( array( self::THEME_BRIDGE_CLASS, 'emit_global_fragments' ), (string) $position );
	}

	/**
	 * Admin notice: CVB is active but predates the ThemeBridge API while
	 * the chrome bridge toggle is ON — the fallback chrome is serving.
	 *
	 * No notice when CVB is absent entirely: the theme is a valid blank
	 * theme on its own by design.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function maybe_bridge_update_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! WWU_Canvas_Customizer::chrome_bridge_enabled() ) {
			return;
		}
		if ( ! self::cvb_active() || self::bridge_available() ) {
			return;
		}

		echo '<div class="notice notice-info is-dismissible"><p>'
			. esc_html__( 'WWU Canvas: aggiorna WWU Code and Visual Builder per abilitare il rendering di header/footer CVB su tutto il sito. Nel frattempo è attivo il chrome di fallback del tema (logo + menu).', 'wwu-canvas' )
			. '</p></div>';
	}
}
