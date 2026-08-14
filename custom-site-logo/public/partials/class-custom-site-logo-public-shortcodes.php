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
		$get_csl_options = get_option( 'csl_CustomSiteLogo_option_name' );

		$csl_image_field       = isset( $get_csl_options['csl_CustomSiteLogo_image_field'] ) ? $get_csl_options['csl_CustomSiteLogo_image_field'] : '';
		$csl_image_url         = isset( $get_csl_options['csl_CustomSiteLogo_custom_url_field'] ) ? $get_csl_options['csl_CustomSiteLogo_custom_url_field'] : '';
		$csl_option_width      = isset( $get_csl_options['csl_CustomSiteLogo_width_field'] ) ? $get_csl_options['csl_CustomSiteLogo_width_field'] : '';
		$csl_option_height     = isset( $get_csl_options['csl_CustomSiteLogo_height_field'] ) ? $get_csl_options['csl_CustomSiteLogo_height_field'] : '';
		$csl_option_responsive = isset( $get_csl_options['csl_CustomSiteLogo_image_responsive_field'] ) ? $get_csl_options['csl_CustomSiteLogo_image_responsive_field'] : 0;
		$csl_option_hover      = isset( $get_csl_options['csl_CustomSiteLogo_hover_effect_field'] ) ? $get_csl_options['csl_CustomSiteLogo_hover_effect_field'] : 'none';
		$csl_option_center     = isset( $get_csl_options['csl_CustomSiteLogo_image_center_field'] ) ? $get_csl_options['csl_CustomSiteLogo_image_center_field'] : 0;

		ob_start();

		if ( empty( $csl_image_field ) ) {
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
			return ob_get_clean();
		}

		$logo_style = '';
		if ( ! empty( $csl_option_width ) ) {
			$logo_style .= 'width:' . absint( $csl_option_width ) . 'px;';
		}
		if ( ! empty( $csl_option_height ) ) {
			$logo_style .= 'height:' . absint( $csl_option_height ) . 'px;';
		}
		if ( 1 === (int) $csl_option_responsive ) {
			$logo_style .= 'width:100%;max-width:100%;height:auto;';
		}

		$wrapper_style = ( 1 === (int) $csl_option_center ) ? 'text-align:center;' : '';
		$logo_link     = ! empty( $csl_image_url ) ? $csl_image_url : '#';
		?>
		<div class="csl-logo-block" style="<?php echo esc_attr( $wrapper_style ); ?>">
			<a id="csl-logo-block-link" href="<?php echo esc_url( $logo_link ); ?>">
				<img id="csl-customsite-logo" class="csl-customsite-logo <?php echo esc_attr( $csl_option_hover ); ?>" src="<?php echo esc_url( $csl_image_field ); ?>" style="<?php echo esc_attr( $logo_style ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
			</a>
		</div><!-- csl-logo-block Ends -->
		<?php
		return ob_get_clean();
	}
}

// Initialize class.
new Custom_Site_Logo_Public_Shortcodes();
