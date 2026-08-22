<?php
/**
 * The shared logo-resolution and markup-rendering logic for the plugin.
 *
 * Every front-end output path (the [csl_display_logo] shortcode, the
 * csl_CustomSiteLogo_show_logo() template tag, the Gutenberg block, the
 * widget, and the theme-logo replacement) renders through this single class
 * so that they always stay in sync and never drift apart with duplicated
 * markup.
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
		$options = Custom_Site_Logo_Options::all();

		$settings = array(
			'image'             => $options['csl_CustomSiteLogo_image_field'],
			'retina_image'      => $options['csl_CustomSiteLogo_retina_image_field'],
			'dark_image'        => $options['csl_CustomSiteLogo_dark_image_field'],
			'mobile_image'      => $options['csl_CustomSiteLogo_mobile_image_field'],
			'sticky_image'      => $options['csl_CustomSiteLogo_sticky_image_field'],
			'mobile_breakpoint' => ! empty( $options['csl_CustomSiteLogo_mobile_breakpoint_field'] ) ? absint( $options['csl_CustomSiteLogo_mobile_breakpoint_field'] ) : self::DEFAULT_MOBILE_BREAKPOINT,
			'url'               => $options['csl_CustomSiteLogo_custom_url_field'],
			'width'             => $options['csl_CustomSiteLogo_width_field'],
			'height'            => $options['csl_CustomSiteLogo_height_field'],
			'responsive'        => (int) $options['csl_CustomSiteLogo_image_responsive_field'],
			'hover'             => $options['csl_CustomSiteLogo_hover_effect_field'],
			'center'            => (int) $options['csl_CustomSiteLogo_image_center_field'],
			'alt'               => $options['csl_CustomSiteLogo_alt_text_field'],
			'title'             => $options['csl_CustomSiteLogo_title_text_field'],
			'aria_label'        => $options['csl_CustomSiteLogo_aria_label_field'],
			'link_disable'      => (int) $options['csl_CustomSiteLogo_link_disable_field'],
			'link_new_tab'      => (int) $options['csl_CustomSiteLogo_link_new_tab_field'],
			'link_nofollow'     => (int) $options['csl_CustomSiteLogo_link_nofollow_field'],
			'dimensions'        => (int) $options['csl_CustomSiteLogo_dimensions_field'],
			'lazy_load'         => (int) $options['csl_CustomSiteLogo_lazy_load_field'],
			'preload'           => (int) $options['csl_CustomSiteLogo_preload_field'],
			'sticky_enabled'    => (int) $options['csl_CustomSiteLogo_sticky_enabled_field'],
			'sticky_scale'      => absint( $options['csl_CustomSiteLogo_sticky_scale_field'] ),
			'sticky_offset'     => absint( $options['csl_CustomSiteLogo_sticky_offset_field'] ),
			'click_tracking'    => (int) $options['csl_CustomSiteLogo_click_tracking_field'],
		);

		/* Schedules, conditional rules, language logos, rotation and the network default. */
		$settings['image'] = Custom_Site_Logo_Conditions::resolve_image( $settings['image'], $post_id );

		if ( $post_id ) {
			$settings = self::apply_post_override( $settings, $post_id );
		}

		/**
		 * Filters the fully resolved logo settings before they are rendered.
		 *
		 * @since 2.0.0
		 * @param array $settings The resolved settings.
		 * @param int   $post_id  The post being rendered, or 0.
		 */
		return apply_filters( 'custom_site_logo_settings', $settings, $post_id );
	}

	/**
	 * Apply a per-page logo override on top of the resolved settings.
	 *
	 * The alternate images (retina, dark, mobile, sticky) are taken from the
	 * override too rather than inherited, because a site-wide dark or retina
	 * variant of a *different* logo would be shown alongside the override and
	 * flip to the wrong artwork. Each variant has its own per-page field so an
	 * override can still support high-DPI and dark mode.
	 *
	 * @since 2.0.0
	 * @param  array $settings The resolved settings.
	 * @param  int   $post_id  The post being rendered.
	 * @return array
	 */
	private static function apply_post_override( $settings, $post_id ) {
		if ( ! get_post_meta( $post_id, '_csl_logo_override_enabled', true ) ) {
			return $settings;
		}

		$override_image = get_post_meta( $post_id, '_csl_logo_override_image', true );

		if ( empty( $override_image ) ) {
			return $settings;
		}

		$settings['image']        = $override_image;
		$settings['retina_image'] = (string) get_post_meta( $post_id, '_csl_logo_override_retina', true );
		$settings['dark_image']   = (string) get_post_meta( $post_id, '_csl_logo_override_dark', true );
		$settings['mobile_image'] = (string) get_post_meta( $post_id, '_csl_logo_override_mobile', true );
		$settings['sticky_image'] = '';

		return $settings;
	}

	/**
	 * Resolve just the logo URL, for consumers that need the image rather
	 * than the full markup (the login screen, admin bar, structured data,
	 * WooCommerce emails, and the REST endpoint).
	 *
	 * @since 2.0.0
	 * @param  int $post_id Optional. The post ID used to resolve a per-page override. Default 0.
	 * @return string The logo URL, or an empty string when none is configured.
	 */
	public static function get_logo_url( $post_id = 0 ) {
		$settings = self::get_settings( $post_id );

		return $settings['image'];
	}

	/**
	 * Look up an image's intrinsic pixel dimensions.
	 *
	 * Used to emit `width`/`height` attributes, which let the browser reserve
	 * the right amount of space and avoid a layout shift while the logo loads.
	 * The result is cached for a day because resolving a URL back to an
	 * attachment costs a database query.
	 *
	 * @since 2.0.0
	 * @param  string $image_url The image URL.
	 * @return array A `array( $width, $height )` pair; both are 0 when unknown.
	 */
	public static function get_image_dimensions( $image_url ) {
		if ( empty( $image_url ) ) {
			return array( 0, 0 );
		}

		$cache_key = 'csl_dimensions_' . md5( $image_url );
		$cached    = get_transient( $cache_key );

		if ( is_array( $cached ) ) {
			return $cached;
		}

		$dimensions = array( 0, 0 );

		if ( preg_match( '/-(\d+)x(\d+)\.[A-Za-z0-9]+$/', $image_url, $matches ) ) {
			/* A resized WordPress image already carries its size in the filename. */
			$dimensions = array( (int) $matches[1], (int) $matches[2] );
		} else {
			$attachment_id = attachment_url_to_postid( $image_url );

			if ( $attachment_id ) {
				$meta = wp_get_attachment_metadata( $attachment_id );

				if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
					$dimensions = array( (int) $meta['width'], (int) $meta['height'] );
				}
			}
		}

		set_transient( $cache_key, $dimensions, DAY_IN_SECONDS );

		return $dimensions;
	}

	/**
	 * Work out the `width`/`height` attribute pair to render.
	 *
	 * When only one of the two is configured the other is derived from the
	 * image's own aspect ratio, so the logo never ends up stretched.
	 *
	 * @since 2.0.0
	 * @param  array $settings The resolved settings.
	 * @return array A `array( $width, $height )` pair; both are 0 when unknown.
	 */
	private static function get_render_dimensions( $settings ) {
		$width  = absint( $settings['width'] );
		$height = absint( $settings['height'] );

		if ( $width && $height ) {
			return array( $width, $height );
		}

		list( $natural_width, $natural_height ) = self::get_image_dimensions( $settings['image'] );

		if ( ! $natural_width || ! $natural_height ) {
			return array( $width, $height );
		}

		if ( $width && ! $height ) {
			return array( $width, (int) round( $width * ( $natural_height / $natural_width ) ) );
		}

		if ( $height && ! $width ) {
			return array( (int) round( $height * ( $natural_width / $natural_height ) ), $height );
		}

		return array( $natural_width, $natural_height );
	}

	/**
	 * Build the alt text for the logo.
	 *
	 * @since 2.0.0
	 * @param  array $settings The resolved settings.
	 * @return string
	 */
	private static function get_alt_text( $settings ) {
		if ( '' !== trim( (string) $settings['alt'] ) ) {
			return $settings['alt'];
		}

		return get_bloginfo( 'name' );
	}

	/**
	 * Build the logo markup, ready to be echoed or returned by callers.
	 *
	 * @since 1.2.0
	 * @param  array $args {
	 *     Optional. Rendering arguments.
	 *
	 *     @type int    $post_id       Post ID used to resolve a per-page override. Default 0.
	 *     @type bool   $error_message Whether to render the "no logo uploaded" notice when there is no logo. Default true.
	 *     @type string $class         Extra class names for the wrapper element. Default ''.
	 *     @type string $variant       Force one specific variant: 'dark', 'mobile' or 'retina'. Default '' (responsive).
	 * }
	 * @return string The logo markup (or the "no logo uploaded" notice), escaped and ready for output.
	 */
	public static function render( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'post_id'       => 0,
				'error_message' => true,
				'class'         => '',
				'variant'       => '',
			)
		);

		$settings = self::get_settings( $args['post_id'] );

		if ( '' !== $args['variant'] ) {
			$settings = self::apply_variant( $settings, $args['variant'] );
		}

		if ( empty( $settings['image'] ) ) {
			return $args['error_message'] ? self::get_error_notice() : '';
		}

		$markup = self::build_markup( $settings, $args['class'] );

		/**
		 * Filters the final logo markup.
		 *
		 * @since 2.0.0
		 * @param string $markup   The logo markup.
		 * @param array  $settings The resolved settings.
		 */
		return apply_filters( 'custom_site_logo_markup', $markup, $settings );
	}

	/**
	 * Pin the output to one specific variant.
	 *
	 * Used by the block variations, where the point is to place a known
	 * version of the logo (a dark mark on a dark section, say) rather than let
	 * the browser choose based on the device. The other variants are cleared so
	 * no `<picture>` sources are emitted to override the choice.
	 *
	 * @since 2.0.0
	 * @param  array  $settings The resolved settings.
	 * @param  string $variant  One of 'dark', 'mobile' or 'retina'.
	 * @return array
	 */
	private static function apply_variant( $settings, $variant ) {
		$variants = array(
			'dark'   => 'dark_image',
			'mobile' => 'mobile_image',
			'retina' => 'retina_image',
		);

		if ( ! isset( $variants[ $variant ] ) || empty( $settings[ $variants[ $variant ] ] ) ) {
			return $settings;
		}

		$settings['image']        = $settings[ $variants[ $variant ] ];
		$settings['retina_image'] = '';
		$settings['dark_image']   = '';
		$settings['mobile_image'] = '';

		return $settings;
	}

	/**
	 * Assemble the wrapper, link and image markup.
	 *
	 * @since 2.0.0
	 * @param  array  $settings    The resolved settings.
	 * @param  string $extra_class Extra class names for the wrapper element.
	 * @return string
	 */
	private static function build_markup( $settings, $extra_class = '' ) {
		$img_tag = self::build_image_tag( $settings );
		$sources = self::build_picture_sources( $settings );

		if ( ! empty( $sources ) ) {
			$img_tag = '<picture>' . implode( '', $sources ) . $img_tag . '</picture>';
		}

		$inner = self::maybe_wrap_in_link( $img_tag, $settings );

		$wrapper_classes = array( 'csl-logo-block' );
		if ( $settings['sticky_enabled'] ) {
			$wrapper_classes[] = 'csl-logo-block--sticky';
		}
		if ( '' !== $extra_class ) {
			$wrapper_classes[] = $extra_class;
		}

		$wrapper_attributes = sprintf(
			' class="%s"',
			esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) )
		);

		if ( 1 === (int) $settings['center'] ) {
			$wrapper_attributes .= ' style="text-align:center;"';
		}

		if ( $settings['sticky_enabled'] ) {
			$wrapper_attributes .= sprintf(
				' data-csl-sticky-scale="%1$d" data-csl-sticky-offset="%2$d"',
				max( 10, min( 100, $settings['sticky_scale'] ) ),
				$settings['sticky_offset']
			);

			if ( ! empty( $settings['sticky_image'] ) ) {
				$wrapper_attributes .= sprintf( ' data-csl-sticky-image="%s"', esc_url( $settings['sticky_image'] ) );
			}
		}

		return '<div' . $wrapper_attributes . '>' . $inner . '</div><!-- csl-logo-block Ends -->';
	}

	/**
	 * Build the `<img>` tag for the logo.
	 *
	 * @since 2.0.0
	 * @param  array $settings The resolved settings.
	 * @return string
	 */
	private static function build_image_tag( $settings ) {
		$style = '';
		if ( ! empty( $settings['width'] ) ) {
			$style .= 'width:' . absint( $settings['width'] ) . 'px;';
		}
		if ( ! empty( $settings['height'] ) ) {
			$style .= 'height:' . absint( $settings['height'] ) . 'px;';
		}
		if ( 1 === (int) $settings['responsive'] ) {
			$style .= 'width:100%;max-width:100%;height:auto;';
		}

		$attributes = array(
			'id'    => 'csl-customsite-logo',
			'class' => trim( 'csl-customsite-logo ' . $settings['hover'] ),
			'src'   => $settings['image'],
			'alt'   => self::get_alt_text( $settings ),
		);

		if ( ! empty( $settings['retina_image'] ) ) {
			/* Escaped per URL here, since a srcset is a comma-separated list and cannot go through esc_url() as a whole. */
			$attributes['srcset'] = esc_url( $settings['image'] ) . ' 1x, ' . esc_url( $settings['retina_image'] ) . ' 2x';
		}

		if ( 1 === (int) $settings['dimensions'] ) {
			list( $width, $height ) = self::get_render_dimensions( $settings );

			if ( $width && $height ) {
				$attributes['width']  = $width;
				$attributes['height'] = $height;
			}
		}

		/*
		 * A header logo is usually the largest element above the fold, so it is
		 * fetched eagerly and at high priority unless lazy loading is asked for.
		 */
		if ( 1 === (int) $settings['lazy_load'] ) {
			$attributes['loading'] = 'lazy';
		} else {
			$attributes['loading']       = 'eager';
			$attributes['fetchpriority'] = 'high';
		}

		$attributes['decoding'] = 'async';

		if ( '' !== trim( (string) $settings['title'] ) ) {
			$attributes['title'] = $settings['title'];
		}

		if ( $settings['click_tracking'] ) {
			$attributes['data-csl-track'] = '1';
		}

		if ( ! empty( $settings['sticky_image'] ) && $settings['sticky_enabled'] ) {
			$attributes['data-csl-default-image'] = $settings['image'];
		}

		return '<img' . self::build_attributes( $attributes ) . ' />';
	}

	/**
	 * Build the `<source>` elements used for the mobile and dark variants.
	 *
	 * @since 2.0.0
	 * @param  array $settings The resolved settings.
	 * @return array
	 */
	private static function build_picture_sources( $settings ) {
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

		return $sources;
	}

	/**
	 * Wrap the image in its link, unless linking has been switched off.
	 *
	 * @since 2.0.0
	 * @param  string $img_tag  The image markup.
	 * @param  array  $settings The resolved settings.
	 * @return string
	 */
	private static function maybe_wrap_in_link( $img_tag, $settings ) {
		if ( 1 === (int) $settings['link_disable'] ) {
			return $img_tag;
		}

		$attributes = array(
			'id'   => 'csl-logo-block-link',
			'href' => ! empty( $settings['url'] ) ? $settings['url'] : home_url( '/' ),
		);

		$rel = array();

		if ( 1 === (int) $settings['link_new_tab'] ) {
			$attributes['target'] = '_blank';
			$rel[]                = 'noopener';
			$rel[]                = 'noreferrer';
		}

		if ( 1 === (int) $settings['link_nofollow'] ) {
			$rel[] = 'nofollow';
		}

		if ( ! empty( $rel ) ) {
			$attributes['rel'] = implode( ' ', array_unique( $rel ) );
		}

		$aria_label = trim( (string) $settings['aria_label'] );
		if ( '' !== $aria_label ) {
			$attributes['aria-label'] = $aria_label;
		}

		return '<a' . self::build_attributes( $attributes ) . '>' . $img_tag . '</a>';
	}

	/**
	 * Turn an attribute map into an escaped attribute string.
	 *
	 * URL-bearing attributes go through esc_url() and everything else through
	 * esc_attr(), so callers never have to escape by hand.
	 *
	 * @since 2.0.0
	 * @param  array $attributes Map of attribute name => value.
	 * @return string The attribute string, with a leading space.
	 */
	private static function build_attributes( $attributes ) {
		$url_attributes = array( 'src', 'href' );
		$output         = '';

		foreach ( $attributes as $name => $value ) {
			if ( '' === $value || null === $value ) {
				continue;
			}

			$value = in_array( $name, $url_attributes, true ) ? esc_url( $value ) : esc_attr( $value );

			$output .= ' ' . esc_attr( $name ) . '="' . $value . '"';
		}

		return $output;
	}

	/**
	 * Build the "no logo uploaded" notice.
	 *
	 * Only shown to users who can actually fix it; visitors get nothing rather
	 * than dashboard instructions in the middle of the page.
	 *
	 * @since 1.2.0
	 * @return string
	 */
	private static function get_error_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return '';
		}

		return sprintf(
			'<div class="csl-error" style="text-align: center;">%s</div>',
			sprintf(
				/* translators: %s: Path to the plugin settings screen in the WordPress dashboard. */
				esc_html__( 'Error! No logo uploaded. Please upload a logo: %s', 'custom-site-logo' ),
				'<strong>' . esc_html__( 'Dashboard &raquo; Appearance &raquo; Custom Site Logo', 'custom-site-logo' ) . '</strong>'
			)
		);
	}
}
