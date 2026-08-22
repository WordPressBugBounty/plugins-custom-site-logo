<?php
/**
 * Network-wide default logo for multisite installs.
 *
 * Lets a network administrator set one logo that every site falls back to, so
 * a new site in the network is never left with no branding at all. Individual
 * sites opt in from their own settings page and can still override it.
 *
 * @link       https://no-site.com
 * @since      2.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */

/**
 * The multisite network default logo class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */
class Custom_Site_Logo_Network_Settings {

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		if ( ! is_multisite() ) {
			return;
		}

		add_action( 'wpmu_options', array( $this, 'render_field' ) );
		add_action( 'update_wpmu_options', array( $this, 'save_field' ) );
	}

	/**
	 * Render the network default logo field on Network Settings.
	 *
	 * @since 2.0.0
	 */
	public function render_field() {
		$value = get_site_option( Custom_Site_Logo_Options::NETWORK_OPTION_NAME, '' );
		?>
		<h2><?php esc_html_e( 'Custom Site Logo', 'custom-site-logo' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row">
					<label for="csl_network_default_logo"><?php esc_html_e( 'Network Default Logo', 'custom-site-logo' ); ?></label>
				</th>
				<td>
					<input type="text" class="regular-text code" id="csl_network_default_logo"
						name="csl_network_default_logo" value="<?php echo esc_attr( $value ); ?>"
						placeholder="https://example.com/logo.png" />
					<p class="description">
						<?php esc_html_e( 'The image URL sites in this network fall back to when they have no logo of their own. Each site must enable "Network Default" in its own logo settings.', 'custom-site-logo' ); ?>
					</p>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Persist the network default logo.
	 *
	 * @since 2.0.0
	 */
	public function save_field() {
		if ( ! current_user_can( 'manage_network_options' ) ) {
			return;
		}

		check_admin_referer( 'siteoptions' );

		if ( ! isset( $_POST['csl_network_default_logo'] ) ) {
			return;
		}

		update_site_option(
			Custom_Site_Logo_Options::NETWORK_OPTION_NAME,
			esc_url_raw( wp_unslash( $_POST['csl_network_default_logo'] ) )
		);
	}
}
