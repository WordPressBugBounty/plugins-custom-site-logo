<?php
/**
 * Puts the configured logo everywhere WordPress shows a site identity.
 *
 * Out of the box the plugin only renders where it is explicitly placed (a
 * shortcode, block, widget, or template tag). The options handled here let the
 * same logo take over the places a site owner would otherwise have to edit
 * theme files or write CSS for: the theme's own logo slot, the login screen,
 * the admin bar and footer, and WooCommerce's transactional emails.
 *
 * @link       https://no-site.com
 * @since      2.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */

/**
 * The site-wide logo locations class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */
class Custom_Site_Logo_Locations {

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_hooks' ) );
	}

	/**
	 * Register each location's hooks, honouring the individual toggles.
	 *
	 * @since 2.0.0
	 */
	public function register_hooks() {
		if ( Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_replace_theme_logo_field' ) ) {
			add_filter( 'get_custom_logo', array( $this, 'replace_theme_logo' ) );
			add_filter( 'theme_mod_custom_logo', array( $this, 'filter_theme_mod_custom_logo' ) );
		}

		if ( Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_login_enabled_field' ) ) {
			add_action( 'login_head', array( $this, 'render_login_styles' ) );
			add_filter( 'login_headerurl', array( $this, 'filter_login_header_url' ) );
			add_filter( 'login_headertext', array( $this, 'filter_login_header_text' ) );
		}

		if ( Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_admin_bar_field' ) ) {
			add_action( 'admin_head', array( $this, 'render_admin_bar_styles' ) );
			add_action( 'wp_head', array( $this, 'render_admin_bar_styles' ) );
		}

		if ( Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_admin_footer_field' ) ) {
			add_filter( 'admin_footer_text', array( $this, 'filter_admin_footer_text' ) );
		}

		if ( Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_woo_email_field' ) ) {
			add_filter( 'woocommerce_email_header_image', array( $this, 'filter_woocommerce_email_image' ) );
		}
	}

	/**
	 * Swap the theme's own logo markup for this plugin's.
	 *
	 * Core runs this filter even when the theme has no logo of its own, which
	 * is what lets the plugin populate a theme's logo slot without the site
	 * owner touching the Customizer.
	 *
	 * @since 2.0.0
	 * @param  string $html The theme's logo markup.
	 * @return string
	 */
	public function replace_theme_logo( $html ) {
		if ( is_customize_preview() ) {
			return $html;
		}

		$markup = Custom_Site_Logo_Renderer::render(
			array(
				'post_id'       => get_the_ID(),
				'error_message' => false,
				'class'         => 'csl-logo-block--theme',
			)
		);

		return '' !== $markup ? $markup : $html;
	}

	/**
	 * Report this plugin's logo as the theme's `custom_logo` attachment.
	 *
	 * Many themes only call `the_custom_logo()` after checking
	 * `has_custom_logo()`, which reads the theme mod directly. Filtering the
	 * theme mod makes those themes render a logo slot at all, which the
	 * `get_custom_logo` filter above then fills with this plugin's markup.
	 *
	 * @since 2.0.0
	 * @param  mixed $value The stored theme mod value.
	 * @return mixed
	 */
	public function filter_theme_mod_custom_logo( $value ) {
		if ( is_admin() || is_customize_preview() || $value ) {
			return $value;
		}

		$attachment_id = self::get_logo_attachment_id();

		return $attachment_id ? $attachment_id : $value;
	}

	/**
	 * Resolve the logo URL back to a Media Library attachment ID.
	 *
	 * Cached because resolving a URL to an attachment costs a database query
	 * and this runs on every page load.
	 *
	 * @since 2.0.0
	 * @return int The attachment ID, or 0 when the logo is not in the library.
	 */
	public static function get_logo_attachment_id() {
		$image = Custom_Site_Logo_Renderer::get_logo_url();

		if ( empty( $image ) ) {
			return 0;
		}

		$cache_key = 'csl_attachment_id_' . md5( $image );
		$cached    = get_transient( $cache_key );

		if ( false !== $cached ) {
			return (int) $cached;
		}

		$attachment_id = attachment_url_to_postid( $image );

		set_transient( $cache_key, $attachment_id, DAY_IN_SECONDS );

		return (int) $attachment_id;
	}

	/**
	 * Pick the image to use on the login screen.
	 *
	 * @since 2.0.0
	 * @return string The login logo URL, or an empty string.
	 */
	private function get_login_image() {
		$image = Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_login_image_field' );

		if ( ! empty( $image ) ) {
			return $image;
		}

		return Custom_Site_Logo_Renderer::get_logo_url();
	}

	/**
	 * Replace the WordPress logo above the login form.
	 *
	 * @since 2.0.0
	 */
	public function render_login_styles() {
		$image = $this->get_login_image();

		if ( empty( $image ) ) {
			return;
		}

		$width  = absint( Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_login_width_field' ) );
		$width  = $width ? $width : 84;
		$height = 84;

		list( $natural_width, $natural_height ) = Custom_Site_Logo_Renderer::get_image_dimensions( $image );

		if ( $natural_width && $natural_height ) {
			$height = (int) round( $width * ( $natural_height / $natural_width ) );
		}

		$css = sprintf(
			'#login h1 a,.login h1 a{background-image:url("%1$s");background-size:contain;background-position:center center;background-repeat:no-repeat;width:%2$dpx;height:%3$dpx;}',
			esc_url( $image ),
			$width,
			max( 1, $height )
		);

		printf( '<style id="csl-login-styles">%s</style>%s', $css, "\n" ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS built from esc_url() and integers above.
	}

	/**
	 * Point the login logo's link at the site rather than wordpress.org.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function filter_login_header_url() {
		$custom_url = Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_custom_url_field' );

		return ! empty( $custom_url ) ? $custom_url : home_url( '/' );
	}

	/**
	 * Use the site name as the login logo's accessible text.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function filter_login_header_text() {
		$alt = Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_alt_text_field' );

		return ! empty( $alt ) ? $alt : get_bloginfo( 'name' );
	}

	/**
	 * Replace the WordPress icon in the admin bar with the site logo.
	 *
	 * @since 2.0.0
	 */
	public function render_admin_bar_styles() {
		if ( ! is_admin_bar_showing() ) {
			return;
		}

		$image = Custom_Site_Logo_Renderer::get_logo_url();

		if ( empty( $image ) ) {
			return;
		}

		$css = sprintf(
			'#wpadminbar #wp-admin-bar-wp-logo>.ab-item .ab-icon:before{background-image:url("%1$s");background-size:contain;background-position:center center;background-repeat:no-repeat;content:"";width:20px;height:20px;display:inline-block;top:2px;}',
			esc_url( $image )
		);

		printf( '<style id="csl-admin-bar-styles">%s</style>%s', $css, "\n" ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS built from esc_url() above.
	}

	/**
	 * Add the logo to the dashboard footer.
	 *
	 * @since 2.0.0
	 * @param  string $text The existing footer text.
	 * @return string
	 */
	public function filter_admin_footer_text( $text ) {
		$image = Custom_Site_Logo_Renderer::get_logo_url();

		if ( empty( $image ) ) {
			return $text;
		}

		$logo = sprintf(
			'<img src="%1$s" alt="%2$s" style="height:20px;width:auto;vertical-align:middle;margin-right:8px;" />',
			esc_url( $image ),
			esc_attr( get_bloginfo( 'name' ) )
		);

		return $logo . $text;
	}

	/**
	 * Use the logo as the header image in WooCommerce emails.
	 *
	 * @since 2.0.0
	 * @param  string $image_url The image URL WooCommerce would otherwise use.
	 * @return string
	 */
	public function filter_woocommerce_email_image( $image_url ) {
		$image = Custom_Site_Logo_Renderer::get_logo_url();

		return ! empty( $image ) ? $image : $image_url;
	}
}
