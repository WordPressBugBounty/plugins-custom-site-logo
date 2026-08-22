<?php
/**
 * REST API surface for the plugin.
 *
 * Exposes the resolved logo so headless front ends, mobile apps and other
 * plugins can read exactly what the site would render, and provides the
 * lightweight endpoint used to record logo clicks.
 *
 * @link       https://no-site.com
 * @since      2.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */

/**
 * The REST API endpoints class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */
class Custom_Site_Logo_Rest {

	/**
	 * The REST namespace used by the plugin.
	 *
	 * @since 2.0.0
	 * @var   string
	 */
	const NAMESPACE_NAME = 'custom-site-logo/v1';

	/**
	 * The option holding per-day logo click counts.
	 *
	 * @since 2.0.0
	 * @var   string
	 */
	const CLICKS_OPTION = 'csl_logo_click_stats';

	/**
	 * How many days of click history to keep.
	 *
	 * @since 2.0.0
	 * @var   int
	 */
	const CLICKS_RETENTION_DAYS = 90;

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the plugin's REST routes.
	 *
	 * @since 2.0.0
	 */
	public function register_routes() {
		register_rest_route(
			self::NAMESPACE_NAME,
			'/logo',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_logo' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'post_id' => array(
						'description'       => __( 'Optional post ID, used to apply a per-page logo override.', 'custom-site-logo' ),
						'type'              => 'integer',
						'default'           => 0,
						'sanitize_callback' => 'absint',
					),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE_NAME,
			'/click',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'record_click' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Return the resolved logo for the current site.
	 *
	 * @since 2.0.0
	 * @param  WP_REST_Request $request The REST request.
	 * @return WP_REST_Response
	 */
	public function get_logo( $request ) {
		$post_id  = absint( $request->get_param( 'post_id' ) );
		$settings = Custom_Site_Logo_Renderer::get_settings( $post_id );

		list( $width, $height ) = Custom_Site_Logo_Renderer::get_image_dimensions( $settings['image'] );

		return rest_ensure_response(
			array(
				'url'        => $settings['image'],
				'retina_url' => $settings['retina_image'],
				'dark_url'   => $settings['dark_image'],
				'mobile_url' => $settings['mobile_image'],
				'width'      => $width,
				'height'     => $height,
				'alt'        => '' !== $settings['alt'] ? $settings['alt'] : get_bloginfo( 'name' ),
				'link'       => '' !== $settings['url'] ? $settings['url'] : home_url( '/' ),
				'html'       => Custom_Site_Logo_Renderer::render(
					array(
						'post_id'       => $post_id,
						'error_message' => false,
					)
				),
			)
		);
	}

	/**
	 * Record a logo click.
	 *
	 * Counts are aggregated per day rather than stored per event, so the option
	 * stays a fixed, tiny size no matter how much traffic the site gets.
	 *
	 * @since 2.0.0
	 * @return WP_REST_Response
	 */
	public function record_click() {
		if ( ! Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_click_tracking_field' ) ) {
			return rest_ensure_response( array( 'recorded' => false ) );
		}

		/*
		 * The endpoint is deliberately open so anonymous visitors can be counted,
		 * so it is throttled per visitor to keep it from being used to hammer the
		 * options table.
		 */
		$throttle_key = 'csl_click_throttle_' . md5( self::get_client_fingerprint() );

		if ( get_transient( $throttle_key ) ) {
			return rest_ensure_response( array( 'recorded' => false ) );
		}

		set_transient( $throttle_key, 1, MINUTE_IN_SECONDS );

		$stats = get_option( self::CLICKS_OPTION, array() );

		if ( ! is_array( $stats ) ) {
			$stats = array();
		}

		$today = current_time( 'Y-m-d' );

		$stats[ $today ] = isset( $stats[ $today ] ) ? (int) $stats[ $today ] + 1 : 1;

		$cutoff = gmdate( 'Y-m-d', strtotime( '-' . self::CLICKS_RETENTION_DAYS . ' days', (int) current_time( 'timestamp' ) ) ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested -- Compared against dates recorded in the same site-local timezone.

		foreach ( array_keys( $stats ) as $date ) {
			if ( $date < $cutoff ) {
				unset( $stats[ $date ] );
			}
		}

		update_option( self::CLICKS_OPTION, $stats, false );

		return rest_ensure_response(
			array(
				'recorded' => true,
				'total'    => self::get_total_clicks(),
			)
		);
	}

	/**
	 * A coarse identifier for the current visitor, used only for throttling.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	private static function get_client_fingerprint() {
		$address = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$agent   = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';

		return $address . '|' . $agent;
	}

	/**
	 * Total recorded clicks across the retained history.
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public static function get_total_clicks() {
		$stats = get_option( self::CLICKS_OPTION, array() );

		return is_array( $stats ) ? (int) array_sum( $stats ) : 0;
	}

	/**
	 * Clicks recorded within the last given number of days.
	 *
	 * @since 2.0.0
	 * @param  int $days How many days back to count.
	 * @return int
	 */
	public static function get_recent_clicks( $days = 30 ) {
		$stats = get_option( self::CLICKS_OPTION, array() );

		if ( ! is_array( $stats ) || empty( $stats ) ) {
			return 0;
		}

		$cutoff = gmdate( 'Y-m-d', strtotime( '-' . absint( $days ) . ' days', (int) current_time( 'timestamp' ) ) ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested -- Compared against dates recorded in the same site-local timezone.
		$total  = 0;

		foreach ( $stats as $date => $count ) {
			if ( $date >= $cutoff ) {
				$total += (int) $count;
			}
		}

		return $total;
	}
}
