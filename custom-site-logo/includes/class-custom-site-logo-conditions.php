<?php
/**
 * Resolves which logo image should be used for the current request.
 *
 * The free plugin returns the site-wide logo. Custom Site Logo Pro replaces
 * that URL, through the custom_site_logo_resolved_image filter, when a
 * schedule, conditional rule, language logo, rotation, or network default
 * applies. A per-page override is applied afterwards by the renderer.
 *
 * @link       https://no-site.com
 * @since      2.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */

/**
 * The logo-resolution entry point.
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
	 * Offer the default logo to Pro.
	 *
	 * @since 2.0.0
	 * @param  string $default_image The site-wide logo URL.
	 * @param  int    $post_id       The post being rendered, or 0.
	 * @return string
	 */
	private static function resolve_image_uncached( $default_image, $post_id ) {
		/**
		 * Filters the logo URL resolved for the current request.
		 *
		 * The free plugin passes the site-wide logo. Custom Site Logo Pro
		 * uses this filter for schedules, conditional rules, language logos,
		 * rotation, and the network default.
		 *
		 * @since 2.0.0
		 * @param string $image         The resolved logo URL.
		 * @param string $default_image The site-wide logo URL.
		 * @param int    $post_id       The post being rendered, or 0.
		 */
		return (string) apply_filters( 'custom_site_logo_resolved_image', $default_image, $default_image, $post_id );
	}
}
