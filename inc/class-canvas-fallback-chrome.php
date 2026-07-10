<?php
/**
 * Minimal fallback chrome: site logo/title + primary menu header and a
 * copyright footer. Rendered ONLY when the chrome bridge toggle is ON but
 * the CVB chrome is unavailable (CVB inactive, ThemeBridge missing, or no
 * default fragment configured) — the site is never left without navigation.
 *
 * @package WWU_Canvas
 * @since   0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fallback header/footer renderer (no hooks of its own — invoked by the
 * chrome dispatch template parts).
 *
 * @since 0.1.0
 */
final class WWU_Canvas_Fallback_Chrome {

	/**
	 * Render the fallback chrome for a region.
	 *
	 * @since 0.1.0
	 * @param string $kind 'header' or 'footer'.
	 * @return void
	 */
	public static function render( $kind ) {
		if ( 'header' === $kind ) {
			self::render_header();
		} elseif ( 'footer' === $kind ) {
			self::render_footer();
		}
	}

	/**
	 * Minimal header: brand (custom logo or site title) + primary menu.
	 *
	 * Menu depth 1 by design: no dropdown CSS shipped — sub-items belong in
	 * a real CVB header fragment. wp_page_menu fallback keeps navigation on
	 * fresh installs with no menu assigned.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private static function render_header() {
		/**
		 * Fires immediately before the fallback header.
		 *
		 * @since 0.1.0
		 */
		do_action( 'wwu_canvas_before_fallback_header' );
		?>
		<header class="wwu-canvas-fallback-header">
			<div class="wwu-canvas-fallback-header__inner">
				<div class="wwu-canvas-fallback-header__brand">
					<?php
					if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
						the_custom_logo();
					} else {
						?>
						<a class="wwu-canvas-fallback-header__title" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
						<?php
					}
					?>
				</div>
				<?php
				wp_nav_menu(
					array(
						'theme_location'       => 'primary',
						'container'            => 'nav',
						'container_class'      => 'wwu-canvas-fallback-nav',
						'container_aria_label' => __( 'Menu principale', 'wwu-canvas' ),
						'menu_class'           => 'wwu-canvas-fallback-menu',
						'depth'                => 1,
						'fallback_cb'          => 'wp_page_menu',
					)
				);
				?>
			</div>
		</header>
		<?php
		/**
		 * Fires immediately after the fallback header.
		 *
		 * @since 0.1.0
		 */
		do_action( 'wwu_canvas_after_fallback_header' );
	}

	/**
	 * Minimal footer: optional footer menu + filterable copyright line.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private static function render_footer() {
		$text = sprintf(
			/* translators: 1: current year, 2: site name. */
			__( '© %1$s %2$s', 'wwu-canvas' ),
			esc_html( wp_date( 'Y' ) ),
			esc_html( get_bloginfo( 'name' ) )
		);

		/**
		 * Filter the fallback footer copyright line (safe HTML allowed,
		 * passed through wp_kses_post at output).
		 *
		 * @since 0.1.0
		 * @param string $text Default "© {year} {site name}".
		 */
		$text = apply_filters( 'wwu_canvas_fallback_footer_text', $text );
		?>
		<footer class="wwu-canvas-fallback-footer">
			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu(
					array(
						'theme_location'       => 'footer',
						'container'            => 'nav',
						'container_class'      => 'wwu-canvas-fallback-footer__nav',
						'container_aria_label' => __( 'Menu footer', 'wwu-canvas' ),
						'menu_class'           => 'wwu-canvas-fallback-menu',
						'depth'                => 1,
						'fallback_cb'          => false,
					)
				);
			}
			?>
			<p class="wwu-canvas-fallback-footer__text"><?php echo wp_kses_post( $text ); ?></p>
		</footer>
		<?php
	}
}
