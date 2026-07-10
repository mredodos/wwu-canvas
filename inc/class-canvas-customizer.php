<?php
/**
 * Customizer section + the 2 theme toggles (theme_mods).
 *
 * Toggle matrix (SPEC §1):
 *   A ON  + B ON  = full bridge (default — "it just works")
 *   A OFF + B OFF = absolute blank canvas
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer registration + typed accessors for the two toggles.
 *
 * @since 0.1.0
 */
final class WWU_Canvas_Customizer {

	/**
	 * Theme mod: toggle A — render CVB chrome (with fallback) on non-CVB pages.
	 *
	 * @var string
	 */
	const MOD_CHROME = 'wwu_canvas_cvb_chrome';

	/**
	 * Theme mod: toggle B — neutral typography for non-CVB content.
	 *
	 * @var string
	 */
	const MOD_TYPOGRAPHY = 'wwu_canvas_neutral_typography';

	/**
	 * Wire hooks.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function init() {
		add_action( 'customize_register', array( __CLASS__, 'register' ) );
	}

	/**
	 * Whether the CVB chrome bridge (toggle A) is enabled.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public static function chrome_bridge_enabled() {
		/**
		 * Filter toggle A programmatically (e.g. force-off from a snippet).
		 *
		 * @since 0.1.0
		 * @param bool $enabled Current resolved value (theme_mod, default true).
		 */
		return (bool) apply_filters(
			'wwu_canvas_enable_chrome_bridge',
			(bool) get_theme_mod( self::MOD_CHROME, true )
		);
	}

	/**
	 * Whether the neutral typography stylesheet (toggle B) is enabled.
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public static function typography_enabled() {
		/**
		 * Filter toggle B programmatically.
		 *
		 * @since 0.1.0
		 * @param bool $enabled Current resolved value (theme_mod, default true).
		 */
		return (bool) apply_filters(
			'wwu_canvas_enable_typography',
			(bool) get_theme_mod( self::MOD_TYPOGRAPHY, true )
		);
	}

	/**
	 * Strict boolean sanitizer for checkbox settings.
	 *
	 * @since 0.1.0
	 * @param mixed $checked Raw Customizer value.
	 * @return bool
	 */
	public static function sanitize_checkbox( $checked ) {
		return ( true === $checked || 1 === $checked || '1' === $checked );
	}

	/**
	 * Register the "WWU Canvas" section + 2 controls.
	 *
	 * @since 0.1.0
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 * @return void
	 */
	public static function register( $wp_customize ) {
		$wp_customize->add_section(
			'wwu_canvas',
			array(
				'title'       => __( 'WWU Canvas', 'wwu-canvas' ),
				'priority'    => 30,
				'description' => __( 'Comportamento del tema sui contenuti NON costruiti con il Code & Visual Builder (blog, archivi, WooCommerce). Le pagine costruite col builder non sono mai toccate dal tema.', 'wwu-canvas' ),
			)
		);

		// Toggle A — CVB chrome bridge.
		$wp_customize->add_setting(
			self::MOD_CHROME,
			array(
				'type'              => 'theme_mod',
				'default'           => true,
				'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			self::MOD_CHROME,
			array(
				'section'     => 'wwu_canvas',
				'type'        => 'checkbox',
				'label'       => __( 'Header/footer CVB su tutto il sito', 'wwu-canvas' ),
				'description' => __( 'Renderizza i fragment header/footer di default del Code & Visual Builder anche su blog, archivi e pagine WooCommerce. Quando il chrome CVB non è disponibile (plugin disattivo o fragment non configurati) appare un header minimo con logo e menu. I fragment di default si configurano in CVB → Settings. Disattivato: il tema non emette nessun header/footer.', 'wwu-canvas' ),
			)
		);

		// Toggle B — neutral typography.
		$wp_customize->add_setting(
			self::MOD_TYPOGRAPHY,
			array(
				'type'              => 'theme_mod',
				'default'           => true,
				'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			self::MOD_TYPOGRAPHY,
			array(
				'section'     => 'wwu_canvas',
				'type'        => 'checkbox',
				'label'       => __( 'Tipografia neutra per contenuti non-CVB', 'wwu-canvas' ),
				'description' => __( 'Carica un piccolo foglio di stile (~8 KB) che rende leggibili articoli, archivi, ricerca e 404: titoli, paragrafi, liste, immagini, form. Nessuna opinione di design. Disattivato: i contenuti non-CVB usano i default del browser (canvas completamente blank).', 'wwu-canvas' ),
			)
		);
	}
}
