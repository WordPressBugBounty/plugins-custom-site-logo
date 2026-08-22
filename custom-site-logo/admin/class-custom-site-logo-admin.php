<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://no-site.com
 * @since      1.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin
 * @author     Awais Altaf <m.awaisaltaf@gmail.com>
 */
class Custom_Site_Logo_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->include_partial_files();
	}

	/**
	 * Register the partial files for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function include_partial_files() {
		/**
		 * The class responsible for defining all settings for admin end.
		 */
		require_once plugin_dir_path( __DIR__ ) .
			'admin/partials/class-custom-site-logo-admin-settings.php';

		/**
		 * The class responsible for defining all menu for admin end.
		 */
		require_once plugin_dir_path( __DIR__ ) .
			'admin/partials/class-custom-site-logo-admin-menu.php';

		/**
		 * The class responsible for enabling safe SVG logo uploads.
		 */
		require_once plugin_dir_path( __DIR__ ) .
			'admin/partials/class-custom-site-logo-svg-support.php';
		new Custom_Site_Logo_Svg_Support();

		/**
		 * The class responsible for the per-page/post logo override meta box.
		 */
		require_once plugin_dir_path( __DIR__ ) .
			'admin/partials/class-custom-site-logo-meta-box.php';
		new Custom_Site_Logo_Meta_Box();

		/**
		 * The class responsible for the Customizer integration.
		 */
		require_once plugin_dir_path( __DIR__ ) .
			'admin/partials/class-custom-site-logo-customizer.php';
		new Custom_Site_Logo_Customizer();

		/**
		 * The class responsible for exporting/importing the plugin settings.
		 */
		require_once plugin_dir_path( __DIR__ ) .
			'admin/partials/class-custom-site-logo-export-import.php';
		new Custom_Site_Logo_Export_Import();

		/**
		 * The class responsible for the built-in text logo maker.
		 */
		require_once plugin_dir_path( __DIR__ ) .
			'admin/partials/class-custom-site-logo-logo-maker.php';
		new Custom_Site_Logo_Logo_Maker();

		/**
		 * The class responsible for the first-run welcome notice.
		 */
		require_once plugin_dir_path( __DIR__ ) .
			'admin/partials/class-custom-site-logo-onboarding.php';
		new Custom_Site_Logo_Onboarding();

		/**
		 * The class responsible for the network-wide default logo on multisite.
		 */
		require_once plugin_dir_path( __DIR__ ) .
			'admin/partials/class-custom-site-logo-network-settings.php';
		new Custom_Site_Logo_Network_Settings();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * Only loaded on this plugin's own settings screen so that they don't
	 * clash with other admin pages (in particular, no unrelated styles are
	 * loaded on top of the native Media Library modal).
	 *
	 * @since    1.0.0
	 * @param    string $hook_suffix    The current admin page hook suffix.
	 */
	public function enqueue_styles( $hook_suffix = '' ) {

		if ( 'appearance_page_custom-site-logo' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'csl_admin_css', plugins_url( 'css/custom-site-logo-admin.css', __FILE__ ), array(), '1.0', 'all' );

		wp_enqueue_style( 'csl_admin_hover_css', plugins_url( 'css/hover-css/hover-min.css', __FILE__ ), array(), '1.0', 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * Only loaded on this plugin's own settings screen. Previously this ran
	 * on every admin page and additionally enqueued the legacy `thickbox`
	 * stylesheet, which is not used anywhere by this plugin (it relies
	 * entirely on `wp.media()`). Loading `thickbox` globally overrides
	 * styles used by the native Media Library modal (overlay/z-index/button
	 * rules), which broke uploading via "Select Files" on other admin
	 * screens such as the post editor and Media Library page.
	 *
	 * @since    1.0.0
	 * @param    string $hook_suffix    The current admin page hook suffix.
	 */
	public function enqueue_scripts( $hook_suffix = '' ) {

		if ( 'appearance_page_custom-site-logo' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_media(); // Enables the media library button.

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-site-logo-admin.js', array( 'jquery' ), $this->version, false );
	}
}
