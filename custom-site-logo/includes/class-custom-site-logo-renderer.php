<?php
/**
 * The shared logo-resolution and markup-rendering logic for the plugin.
 *
 * Every front-end output path (the [csl_display_logo] shortcode, the
 * csl_CustomSiteLogo_show_logo() template tag, the Gutenberg block, and the
 * widget) renders through this single class so that they always stay in
 * sync and never drift apart with duplicated markup.
 *
 * @link       https://no-site.com
 * @since      1.2.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */

/**
 * The shared logo renderer class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */
class Custom_Site_Logo_Renderer {

	/**
	 * The option name under which all logo settings are stored.
	 *
	 * @since 1.2.0
	 * @var   string
	 */
	const OPTION_NAME = 'csl_CustomSiteLogo_option_name';

	/**
	 * Default mobile breakpoint (in pixels) used when a mobile logo is set
	 * but no explicit breakpoint has been configured.
	 *
	 * @since 1.2.0
	 * @var   int
	 */
	const DEFAULT_MOBILE_BREAKPOINT = 600;

	/**
	 * Resolve the effective logo settings, applying any per-page override.
	 *
	 * @since 1.2.0
	 * @param  int $post_id Optional. The post ID to check for a per-page logo override. Default 0 (no override lookup).
	 * @return array
	 */
	public static function get_settings( $post_id = 0 ) {
		$options = get_option( self::OPTION_NAME );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$image = isset( $options['csl_CustomSiteLogo_image_field'] ) ? $options['csl_CustomSiteLogo_image_field'] : '';
		// Older versions of the settings field stored the untranslated placeholder text itself as the value.
		if ( 'Select Logo' === $image ) {
			$image = '';
		}

		$settings = array(
			'image'             => $image,
			'retina_image'      => isset( $options['csl_CustomSiteLogo_retina_image_field'] ) ? $options['csl_CustomSiteLogo_retina_image_field'] : '',
			'dark_image'        => isset( $options['csl_CustomSiteLogo_dark_image_field'] ) ? $options['csl_CustomSiteLogo_dark_image_field'] : '',
			'mobile_image'      => isset( $options['csl_CustomSiteLogo_mobile_image_field'] ) ? $options['csl_CustomSiteLogo_mobile_image_field'] : '',
			'mobile_breakpoint' => ! empty( $options['csl_CustomSiteLogo_mobile_breakpoint_field'] ) ? absint( $options['csl_CustomSiteLogo_mobile_breakpoint_field'] ) : self::DEFAULT_MOBILE_BREAKPOINT,
			'url'               => isset( $options['csl_CustomSiteLogo_custom_url_field'] ) ? $options['csl_CustomSiteLogo_custom_url_field'] : '',
			'width'             => isset( $options['csl_CustomSiteLogo_width_field'] ) ? $options['csl_CustomSiteLogo_width_field'] : '',
			'height'            => isset( $options['csl_CustomSiteLogo_height_field'] ) ? $options['csl_CustomSiteLogo_height_field'] : '',
			'responsive'        => isset( $options['csl_CustomSiteLogo_image_responsive_field'] ) ? (int) $options['csl_CustomSiteLogo_image_responsive_field'] : 0,
			'hover'             => isset( $options['csl_CustomSiteLogo_hover_effect_field'] ) ? $options['csl_CustomSiteLogo_hover_effect_field'] : 'none',
			'center'            => isset( $options['csl_CustomSiteLogo_image_center_field'] ) ? (int) $options['csl_CustomSiteLogo_image_center_field'] : 0,
		);

		if ( $post_id ) {
			$override_enabled = get_post_meta( $post_id, '_csl_logo_override_enabled', true );
			$override_image   = get_post_meta( $post_id, '_csl_logo_override_image', true );

			if ( $override_enabled && ! empty( $override_image ) ) {
				$settings['image']        = $override_image;
				$settings['retina_image'] = '';
				$settings['dark_image']   = '';
				$settings['mobile_image'] = '';
			}
		}

		return $settings;
	}

	/**
	 * Build the logo markup, ready to be echoed or returned by callers.
	 *
	 * @since 1.2.0
	 * @param  array $args {
	 *     Optional. Rendering arguments.
	 *
	 *     @type int  $post_id       Post ID used to resolve a per-page override. Default 0.
	 *     @type bool $error_message Whether to render the "no logo uploaded" notice when there is no logo. Default true.
	 * }
	 * @return string The logo markup (or the "no logo uploaded" notice), escaped and ready for output.
	 */
	public static function render( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'post_id'       => 0,
				'error_message' => true,
			)
		);

		$settings = self::get_settings( $args['post_id'] );

		ob_start();

		if ( empty( $settings['image'] ) ) {
			if ( $args['error_message'] ) {
				self::render_error_notice();
			}
			return ob_get_clean();
		}

		$logo_style = '';
		if ( ! empty( $settings['width'] ) ) {
			$logo_style .= 'width:' . absint( $settings['width'] ) . 'px;';
		}
		if ( ! empty( $settings['height'] ) ) {
			$logo_style .= 'height:' . absint( $settings['height'] ) . 'px;';
		}
		if ( 1 === (int) $settings['responsive'] ) {
			$logo_style .= 'width:100%;max-width:100%;height:auto;';
		}

		$wrapper_style = ( 1 === (int) $settings['center'] ) ? 'text-align:center;' : '';
		$logo_link     = ! empty( $settings['url'] ) ? $settings['url'] : '#';

		$sources = array();
		if ( ! empty( $settings['mobile_image'] ) ) {
			$sources[] = sprintf(
				'<source media="(max-width: %1$dpx)" srcset="%2$s" />',
				absint( $settings['mobile_breakpoint'] ),
				esc_url( $settings['mobile_image'] )
			);
		}
		if ( ! empty( $settings['dark_image'] ) ) {
			$sources[] = sprintf(
				'<source media="(prefers-color-scheme: dark)" srcset="%s" />',
				esc_url( $settings['dark_image'] )
			);
		}

		$img_srcset = '';
		if ( ! empty( $settings['retina_image'] ) ) {
			$img_srcset = sprintf( ' srcset="%1$s 1x, %2$s 2x"', esc_url( $settings['image'] ), esc_url( $settings['retina_image'] ) );
		}

		$img_tag = sprintf(
			'<img id="csl-customsite-logo" class="csl-customsite-logo %1$s" src="%2$s"%3$s style="%4$s" alt="%5$s" />',
			esc_attr( $settings['hover'] ),
			esc_url( $settings['image'] ),
			$img_srcset, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from esc_url() above.
			esc_attr( $logo_style ),
			esc_attr( get_bloginfo( 'name' ) )
		);

		?>
		<div class="csl-logo-block" style="<?php echo esc_attr( $wrapper_style ); ?>">
			<a id="csl-logo-block-link" href="<?php echo esc_url( $logo_link ); ?>">
				<?php if ( ! empty( $sources ) ) : ?>
				<picture>
					<?php echo implode( "\n", $sources ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sources are built from esc_url()/absint() above. ?>
					<?php echo $img_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from escaped values above. ?>
				</picture>
				<?php else : ?>
					<?php echo $img_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from escaped values above. ?>
				<?php endif; ?>
			</a>
		</div><!-- csl-logo-block Ends -->
		<?php

		return ob_get_clean();
	}

	/**
	 * Render the "no logo uploaded" admin notice.
	 *
	 * @since 1.2.0
	 */
	private static function render_error_notice() {
		?>
		<div class="csl-error" style="text-align: center;">
			<?php
			printf(
				/* translators: %s: Path to the plugin settings screen in the WordPress dashboard. */
				esc_html__( 'Error! No logo uploaded. Please upload a logo: %s', 'custom-site-logo' ),
				'<strong>' . esc_html__( 'Dashboard &raquo; Appearance &raquo; Custom Site Logo', 'custom-site-logo' ) . '</strong>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is escaped above.
			);
			?>
		</div>
		<?php
	}
}
