<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://no-site.com
 * @since      1.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/public
 * @author     Awais Altaf <m.awaisaltaf@gmail.com>
 */
class Custom_Site_Logo_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->csl_add_partials_files();
	}

	/**
	 * Include the partial files.
	 *
	 * @since    1.0.0
	 */
	public function csl_add_partials_files() {
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/partials/class-custom-site-logo-public-shortcodes.php';

		/**
		 * The function responsible for rendering the logo markup when called
		 * directly from a theme template.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/partials/custom-site-logo-public-functions.php';

		/**
		 * The widget responsible for displaying the logo in a sidebar/widget area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/partials/class-custom-site-logo-widget.php';
		add_action(
			'widgets_init',
			function () {
				register_widget( 'Custom_Site_Logo_Widget' );
			}
		);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/custom-site-logo-public.css',
			array(),
			$this->version,
			'all'
		);

		/*
		 * The Hover.css library is over 100KB, so it is only worth sending when
		 * a hover effect has actually been chosen.
		 */
		$hover_effect = Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_hover_effect_field' );

		if ( ! empty( $hover_effect ) && 'none' !== $hover_effect ) {
			wp_enqueue_style(
				'csl_front_hover_css',
				plugins_url( 'css/hover-css/hover-min.css', __FILE__ ),
				array(),
				'1.0',
				'all'
			);
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * The script only powers the sticky logo and click tracking, so it is left
	 * out entirely when neither feature is switched on.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$sticky_enabled = (int) Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_sticky_enabled_field' );
		$track_clicks   = (int) Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_click_tracking_field' );

		if ( ! $sticky_enabled && ! $track_clicks ) {
			return;
		}

		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/custom-site-logo-public.js',
			array(),
			$this->version,
			true
		);

		wp_localize_script(
			$this->plugin_name,
			'cslPublic',
			array(
				'trackClicks'   => (bool) $track_clicks,
				'clickEndpoint' => $track_clicks ? esc_url_raw( rest_url( Custom_Site_Logo_Rest::NAMESPACE_NAME . '/click' ) ) : '',
			)
		);
	}
}
