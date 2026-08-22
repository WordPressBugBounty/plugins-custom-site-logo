<?php
/**
 * Resolves which logo image should be used for the current request.
 *
 * The plugin supports several ways to swap the logo out for something other
 * than the site-wide default. They are applied in a fixed order of
 * precedence, and the first one that produces an image wins:
 *
 * 1. A per-page override set on the post being viewed (handled by the renderer).
 * 2. An active schedule, e.g. a seasonal or campaign logo for a date range.
 * 3. A conditional rule, e.g. a different logo for one post type or user role.
 * 4. A logo assigned to the visitor's language/locale.
 * 5. A randomly chosen logo, when logo rotation is enabled.
 * 6. The site-wide default, falling back to the network default on multisite.
 *
 * @link       https://no-site.com
 * @since      2.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */

/**
 * The logo-resolution rules class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */
class Custom_Site_Logo_Conditions {

	/**
	 * Per-request cache of resolved logos, keyed by input.
	 *
	 * @since 2.0.0
	 * @var   array
	 */
	private static $resolved = array();

	/**
	 * Resolve the logo URL to use for the current request.
	 *
	 * The result is memoized for the rest of the request. Besides saving the
	 * repeated work, this is what keeps a rotating logo consistent: the preload
	 * hint, the structured data and the rendered `<img>` all have to agree on
	 * which logo was picked.
	 *
	 * @since 2.0.0
	 * @param  string $default_image The site-wide logo URL.
	 * @param  int    $post_id       Optional. The post being rendered. Default 0.
	 * @return string The resolved logo URL, or an empty string when nothing is configured.
	 */
	public static function resolve_image( $default_image, $post_id = 0 ) {
		$cache_key = $default_image . '|' . (int) $post_id;

		if ( isset( self::$resolved[ $cache_key ] ) ) {
			return self::$resolved[ $cache_key ];
		}

		self::$resolved[ $cache_key ] = self::resolve_image_uncached( $default_image, $post_id );

		return self::$resolved[ $cache_key ];
	}

	/**
	 * Run the resolution rules in order of precedence.
	 *
	 * @since 2.0.0
	 * @param  string $default_image The site-wide logo URL.
	 * @param  int    $post_id       The post being rendered, or 0.
	 * @return string
	 */
	private static function resolve_image_uncached( $default_image, $post_id ) {
		$image = self::get_scheduled_image();

		if ( '' === $image ) {
			$image = self::get_conditional_image( $post_id );
		}

		if ( '' === $image ) {
			$image = self::get_language_image();
		}

		if ( '' === $image ) {
			$image = self::get_rotation_image();
		}

		if ( '' === $image ) {
			$image = $default_image;
		}

		if ( '' === $image ) {
			$image = self::get_network_default_image();
		}

		/**
		 * Filters the logo URL resolved for the current request.
		 *
		 * Runs after every built-in rule (schedules, conditions, language,
		 * rotation, network default) has been applied.
		 *
		 * @since 2.0.0
		 * @param string $image         The resolved logo URL.
		 * @param string $default_image The site-wide logo URL.
		 * @param int    $post_id       The post being rendered, or 0.
		 */
		return (string) apply_filters( 'custom_site_logo_resolved_image', $image, $default_image, $post_id );
	}

	/**
	 * Find the logo belonging to the currently active schedule, if any.
	 *
	 * Both bounds are inclusive and evaluated against the site's local date,
	 * so a schedule ending "2026-12-26" still shows on Boxing Day. An empty
	 * bound means "open ended" in that direction.
	 *
	 * @since 2.0.0
	 * @return string The scheduled logo URL, or an empty string.
	 */
	private static function get_scheduled_image() {
		$schedules = Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_schedules_field', array() );

		if ( empty( $schedules ) ) {
			return '';
		}

		$today = current_time( 'Y-m-d' );

		foreach ( $schedules as $schedule ) {
			$start = isset( $schedule['start'] ) ? $schedule['start'] : '';
			$end   = isset( $schedule['end'] ) ? $schedule['end'] : '';

			if ( '' !== $start && $today < $start ) {
				continue;
			}

			if ( '' !== $end && $today > $end ) {
				continue;
			}

			if ( '' === $start && '' === $end ) {
				continue; // A schedule with no bounds at all would never expire; ignore it.
			}

			return $schedule['image'];
		}

		return '';
	}

	/**
	 * Find the logo belonging to the first matching conditional rule.
	 *
	 * @since 2.0.0
	 * @param  int $post_id The post being rendered, or 0.
	 * @return string The rule's logo URL, or an empty string.
	 */
	private static function get_conditional_image( $post_id = 0 ) {
		$conditions = Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_conditions_field', array() );

		if ( empty( $conditions ) ) {
			return '';
		}

		foreach ( $conditions as $condition ) {
			if ( self::matches( $condition, $post_id ) ) {
				return $condition['image'];
			}
		}

		return '';
	}

	/**
	 * Evaluate a single conditional rule against the current request.
	 *
	 * @since 2.0.0
	 * @param  array $condition The rule (type, value, image).
	 * @param  int   $post_id   The post being rendered, or 0.
	 * @return bool Whether the rule matches.
	 */
	private static function matches( $condition, $post_id ) {
		$type  = isset( $condition['type'] ) ? $condition['type'] : '';
		$value = isset( $condition['value'] ) ? trim( (string) $condition['value'] ) : '';

		switch ( $type ) {
			case 'post_type':
				return '' !== $value && $post_id && get_post_type( $post_id ) === $value;

			case 'post_id':
				return '' !== $value && $post_id && absint( $value ) === absint( $post_id );

			case 'category':
				return '' !== $value && $post_id && has_category( $value, $post_id );

			case 'role':
				if ( '' === $value || ! is_user_logged_in() ) {
					return false;
				}
				$user = wp_get_current_user();

				return in_array( $value, (array) $user->roles, true );

			case 'device':
				$is_mobile = wp_is_mobile();
				$wanted    = strtolower( $value );

				return ( 'mobile' === $wanted && $is_mobile ) || ( 'desktop' === $wanted && ! $is_mobile );

			case 'front_page':
				return is_front_page();

			case 'archive':
				return is_archive();

			case 'search':
				return is_search();

			case 'logged_in':
				return is_user_logged_in();
		}

		return false;
	}

	/**
	 * Find the logo assigned to the current locale.
	 *
	 * Matches the full locale first (`pt_BR`) and then the language part
	 * alone (`pt`), so one row can cover every regional variant.
	 *
	 * @since 2.0.0
	 * @return string The locale's logo URL, or an empty string.
	 */
	private static function get_language_image() {
		$rows = Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_language_logos_field', array() );

		if ( empty( $rows ) ) {
			return '';
		}

		$locale   = determine_locale();
		$language = strtok( $locale, '_' );

		foreach ( $rows as $row ) {
			if ( isset( $row['locale'] ) && $row['locale'] === $locale ) {
				return $row['image'];
			}
		}

		foreach ( $rows as $row ) {
			if ( isset( $row['locale'] ) && $row['locale'] === $language ) {
				return $row['image'];
			}
		}

		return '';
	}

	/**
	 * Pick a random logo when rotation is enabled.
	 *
	 * @since 2.0.0
	 * @return string A randomly chosen logo URL, or an empty string.
	 */
	private static function get_rotation_image() {
		if ( ! Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_rotation_enabled_field' ) ) {
			return '';
		}

		$rows = Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_rotation_images_field', array() );

		if ( empty( $rows ) ) {
			return '';
		}

		$row = $rows[ array_rand( $rows ) ];

		return isset( $row['image'] ) ? $row['image'] : '';
	}

	/**
	 * Read the network-wide fallback logo on multisite.
	 *
	 * @since 2.0.0
	 * @return string The network default logo URL, or an empty string.
	 */
	private static function get_network_default_image() {
		if ( ! is_multisite() || ! Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_network_default_field' ) ) {
			return '';
		}

		return (string) get_site_option( Custom_Site_Logo_Options::NETWORK_OPTION_NAME, '' );
	}
}
