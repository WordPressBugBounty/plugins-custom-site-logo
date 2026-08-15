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
class Custom_Site_Logo_Public_Shortcodes {

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
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'csl_shortcodes_init' ) );
	}

	/**
	 * Register the shortcode for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function csl_shortcodes_init() {
		add_shortcode( 'csl_display_logo', array( $this, 'csl_show_logo' ) );
	}

	/**
	 * Build the logo markup for the [csl_display_logo] shortcode.
	 *
	 * @since    1.0.0
	 * @return   string    The logo markup, ready to be output by WordPress.
	 */
	public function csl_show_logo() {
		return Custom_Site_Logo_Renderer::render( array( 'post_id' => get_the_ID() ) );
	}
}

// Initialize class.
new Custom_Site_Logo_Public_Shortcodes();
