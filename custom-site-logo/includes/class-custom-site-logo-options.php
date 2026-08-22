<?php
/**
 * Central definition, defaulting and sanitization of the plugin's settings.
 *
 * Every setting the plugin stores is declared once here, together with its
 * default value and the sanitizer used for it. The settings page, the
 * Settings API `sanitize_callback`, the import/export whitelist and the
 * renderer all read from these schemas, so a new setting only ever has to
 * be declared in one place.
 *
 * @link       https://no-site.com
 * @since      2.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */

/**
 * The settings schema and sanitization class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */
class Custom_Site_Logo_Options {

	/**
	 * The option name under which all settings are stored.
	 *
	 * @since 2.0.0
	 * @var   string
	 */
	const OPTION_NAME = 'csl_CustomSiteLogo_option_name';

	/**
	 * The network-wide (multisite) option holding the fallback logo URL.
	 *
	 * @since 2.0.0
	 * @var   string
	 */
	const NETWORK_OPTION_NAME = 'csl_network_default_logo';

	/**
	 * Runtime cache for the merged settings array.
	 *
	 * @since 2.0.0
	 * @var   array|null
	 */
	private static $cache = null;

	/**
	 * The plugin's scalar settings: default value and sanitizer for each.
	 *
	 * @since 2.0.0
	 * @return array Map of option key => array( default, sanitize ).
	 */
	public static function get_schema() {
		return array(
			/* General. */
			'csl_CustomSiteLogo_image_field'              => array(
				'default'  => '',
				'sanitize' => 'esc_url_raw',
			),
			'csl_CustomSiteLogo_custom_url_field'         => array(
				'default'  => '',
				'sanitize' => 'esc_url_raw',
			),

			/* Size & layout. */
			'csl_CustomSiteLogo_width_field'              => array(
				'default'  => '',
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_height_field'             => array(
				'default'  => '',
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_image_center_field'       => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_image_responsive_field'   => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),

			/* Hover effect. */
			'csl_CustomSiteLogo_hover_effect_field'       => array(
				'default'  => 'none',
				'sanitize' => 'sanitize_html_class',
			),

			/* Advanced logos. */
			'csl_CustomSiteLogo_retina_image_field'       => array(
				'default'  => '',
				'sanitize' => 'esc_url_raw',
			),
			'csl_CustomSiteLogo_dark_image_field'         => array(
				'default'  => '',
				'sanitize' => 'esc_url_raw',
			),
			'csl_CustomSiteLogo_mobile_image_field'       => array(
				'default'  => '',
				'sanitize' => 'esc_url_raw',
			),
			'csl_CustomSiteLogo_mobile_breakpoint_field'  => array(
				'default'  => 600,
				'sanitize' => 'absint',
			),

			/* Link, accessibility & SEO. */
			'csl_CustomSiteLogo_alt_text_field'           => array(
				'default'  => '',
				'sanitize' => 'sanitize_text_field',
			),
			'csl_CustomSiteLogo_title_text_field'         => array(
				'default'  => '',
				'sanitize' => 'sanitize_text_field',
			),
			'csl_CustomSiteLogo_aria_label_field'         => array(
				'default'  => '',
				'sanitize' => 'sanitize_text_field',
			),
			'csl_CustomSiteLogo_link_disable_field'       => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_link_new_tab_field'       => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_link_nofollow_field'      => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_schema_field'             => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),

			/* Performance. */
			'csl_CustomSiteLogo_dimensions_field'         => array(
				'default'  => 1,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_preload_field'            => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_lazy_load_field'          => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),

			/* Display locations. */
			'csl_CustomSiteLogo_replace_theme_logo_field' => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_login_enabled_field'      => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_login_image_field'        => array(
				'default'  => '',
				'sanitize' => 'esc_url_raw',
			),
			'csl_CustomSiteLogo_login_width_field'        => array(
				'default'  => 84,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_admin_bar_field'          => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_admin_footer_field'       => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_woo_email_field'          => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_print_field'              => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_print_image_field'        => array(
				'default'  => '',
				'sanitize' => 'esc_url_raw',
			),

			/* Sticky logo. */
			'csl_CustomSiteLogo_sticky_enabled_field'     => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_sticky_image_field'       => array(
				'default'  => '',
				'sanitize' => 'esc_url_raw',
			),
			'csl_CustomSiteLogo_sticky_scale_field'       => array(
				'default'  => 70,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_sticky_offset_field'      => array(
				'default'  => 100,
				'sanitize' => 'absint',
			),

			/* Conditional rules. */
			'csl_CustomSiteLogo_rotation_enabled_field'   => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'csl_CustomSiteLogo_network_default_field'    => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),

			/* Analytics. */
			'csl_CustomSiteLogo_click_tracking_field'     => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
		);
	}

	/**
	 * The plugin's repeatable (row-based) settings.
	 *
	 * Each entry describes one repeater: the sub-fields a row may contain,
	 * and the sanitizer for each sub-field. A row is discarded entirely when
	 * its `image` sub-field is empty, which keeps stray empty rows created by
	 * the "Add row" button out of the database.
	 *
	 * @since 2.0.0
	 * @return array Map of option key => array of sub-field => sanitizer.
	 */
	public static function get_repeater_schema() {
		return array(
			'csl_CustomSiteLogo_schedules_field'       => array(
				'label' => 'sanitize_text_field',
				'start' => array( __CLASS__, 'sanitize_date' ),
				'end'   => array( __CLASS__, 'sanitize_date' ),
				'image' => 'esc_url_raw',
			),
			'csl_CustomSiteLogo_conditions_field'      => array(
				'type'  => array( __CLASS__, 'sanitize_condition_type' ),
				'value' => 'sanitize_text_field',
				'image' => 'esc_url_raw',
			),
			'csl_CustomSiteLogo_language_logos_field'  => array(
				'locale' => array( __CLASS__, 'sanitize_locale' ),
				'image'  => 'esc_url_raw',
			),
			'csl_CustomSiteLogo_rotation_images_field' => array(
				'image' => 'esc_url_raw',
			),
		);
	}

	/**
	 * The condition types supported by the conditional-rules engine.
	 *
	 * @since 2.0.0
	 * @return array Map of condition type => human-readable label.
	 */
	public static function get_condition_types() {
		return array(
			'post_type'  => __( 'Post type is', 'custom-site-logo' ),
			'post_id'    => __( 'Post/Page ID is', 'custom-site-logo' ),
			'category'   => __( 'Post is in category (slug or ID)', 'custom-site-logo' ),
			'role'       => __( 'Visitor has role', 'custom-site-logo' ),
			'device'     => __( 'Device is (mobile/desktop)', 'custom-site-logo' ),
			'front_page' => __( 'Is the front page', 'custom-site-logo' ),
			'archive'    => __( 'Is an archive page', 'custom-site-logo' ),
			'search'     => __( 'Is a search results page', 'custom-site-logo' ),
			'logged_in'  => __( 'Visitor is logged in', 'custom-site-logo' ),
		);
	}

	/**
	 * Retrieve the full, defaulted settings array.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public static function all() {
		if ( null !== self::$cache ) {
			return self::$cache;
		}

		$stored = get_option( self::OPTION_NAME );
		if ( ! is_array( $stored ) ) {
			$stored = array();
		}

		$settings = array();

		foreach ( self::get_schema() as $key => $spec ) {
			$settings[ $key ] = array_key_exists( $key, $stored ) ? $stored[ $key ] : $spec['default'];
		}

		foreach ( self::get_repeater_schema() as $key => $subfields ) {
			$settings[ $key ] = ( isset( $stored[ $key ] ) && is_array( $stored[ $key ] ) ) ? $stored[ $key ] : array();
		}

		/*
		 * Very early versions stored the untranslated "Select Logo" placeholder
		 * as the value itself, which would otherwise be rendered as an image URL.
		 */
		if ( 'Select Logo' === $settings['csl_CustomSiteLogo_image_field'] ) {
			$settings['csl_CustomSiteLogo_image_field'] = '';
		}

		self::$cache = $settings;

		return $settings;
	}

	/**
	 * Retrieve a single setting.
	 *
	 * @since 2.0.0
	 * @param  string $key           The option key.
	 * @param  mixed  $fallback_value Optional. Value returned when the key is unknown. Default null.
	 * @return mixed
	 */
	public static function get( $key, $fallback_value = null ) {
		$settings = self::all();

		return array_key_exists( $key, $settings ) ? $settings[ $key ] : $fallback_value;
	}

	/**
	 * Clear the runtime cache.
	 *
	 * Needed after the settings are written within the same request (saving,
	 * importing, resetting), so subsequent reads don't serve stale values.
	 *
	 * @since 2.0.0
	 */
	public static function flush_cache() {
		self::$cache = null;
	}

	/**
	 * Sanitize a full settings payload.
	 *
	 * Registered as the Settings API `sanitize_callback`, which means it also
	 * runs for the Customizer and for programmatic `update_option()` calls
	 * (via `sanitize_option_{$option}`). Unknown keys are dropped.
	 *
	 * @since 2.0.0
	 * @param  mixed $input The raw submitted value.
	 * @return array The sanitized settings.
	 */
	public static function sanitize( $input ) {
		if ( ! is_array( $input ) ) {
			$input = array();
		}

		$output = array();

		foreach ( self::get_schema() as $key => $spec ) {
			if ( ! isset( $input[ $key ] ) ) {
				/*
				 * A missing key means this payload never carried the setting at
				 * all: the Customizer saves only what it manages, and an import
				 * file written by an older version has no key for a newer
				 * setting. Falling back to the default keeps those saves from
				 * silently switching features off. Unchecked checkboxes are not
				 * a problem here because every toggle submits a paired hidden
				 * "0" field.
				 */
				$output[ $key ] = $spec['default'];
				continue;
			}

			$output[ $key ] = call_user_func( $spec['sanitize'], $input[ $key ] );
		}

		foreach ( self::get_repeater_schema() as $key => $subfields ) {
			$output[ $key ] = self::sanitize_repeater( isset( $input[ $key ] ) ? $input[ $key ] : array(), $subfields );
		}

		self::flush_cache();

		return $output;
	}

	/**
	 * Sanitize one repeater's rows, dropping any row without an image.
	 *
	 * @since 2.0.0
	 * @param  mixed $rows      The raw rows.
	 * @param  array $subfields Map of sub-field => sanitizer.
	 * @return array
	 */
	private static function sanitize_repeater( $rows, $subfields ) {
		if ( ! is_array( $rows ) ) {
			return array();
		}

		$clean = array();

		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$clean_row = array();
			foreach ( $subfields as $subfield => $sanitizer ) {
				$clean_row[ $subfield ] = isset( $row[ $subfield ] ) ? call_user_func( $sanitizer, $row[ $subfield ] ) : '';
			}

			if ( empty( $clean_row['image'] ) ) {
				continue;
			}

			$clean[] = $clean_row;
		}

		return $clean;
	}

	/**
	 * Sanitize a `Y-m-d` date string, returning an empty string when invalid.
	 *
	 * @since 2.0.0
	 * @param  mixed $value The raw date.
	 * @return string
	 */
	public static function sanitize_date( $value ) {
		$value = sanitize_text_field( (string) $value );

		if ( '' === $value || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ) {
			return '';
		}

		return $value;
	}

	/**
	 * Sanitize a condition type against the supported list.
	 *
	 * @since 2.0.0
	 * @param  mixed $value The raw condition type.
	 * @return string
	 */
	public static function sanitize_condition_type( $value ) {
		$value = sanitize_key( (string) $value );

		return array_key_exists( $value, self::get_condition_types() ) ? $value : 'post_type';
	}

	/**
	 * Sanitize a WordPress locale such as `fr_FR`.
	 *
	 * @since 2.0.0
	 * @param  mixed $value The raw locale.
	 * @return string
	 */
	public static function sanitize_locale( $value ) {
		$value = sanitize_text_field( (string) $value );

		return preg_replace( '/[^A-Za-z0-9_\-]/', '', $value );
	}
}
