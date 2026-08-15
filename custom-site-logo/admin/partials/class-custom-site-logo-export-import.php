<?php
/**
 * Export/import UI for the plugin's settings.
 *
 * Lets site owners download their Custom Site Logo configuration as a JSON
 * file (handy for backups or moving the setup to another site) and re-import
 * it later. Imported data is whitelisted and re-sanitized field by field.
 *
 * @link       https://no-site.com
 * @since      1.2.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */

/**
 * The export/import UI class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */
class Custom_Site_Logo_Export_Import {

	/**
	 * Option name under which the plugin settings are stored.
	 *
	 * @since 1.2.0
	 * @var   string
	 */
	const OPTION_NAME = 'csl_CustomSiteLogo_option_name';

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since    1.2.0
	 */
	public function __construct() {
		add_action( 'custom_site_logo_after_settings_form', array( $this, 'render_ui' ) );
		add_action( 'admin_post_csl_export_settings', array( $this, 'handle_export' ) );
		add_action( 'admin_post_csl_import_settings', array( $this, 'handle_import' ) );
		add_action( 'admin_notices', array( $this, 'maybe_render_import_notice' ) );
	}

	/**
	 * The list of option keys that may be imported, and the sanitizer used for each.
	 *
	 * @since    1.2.0
	 * @return   array
	 */
	private function get_allowed_fields() {
		return array(
			'csl_CustomSiteLogo_image_field'             => 'esc_url_raw',
			'csl_CustomSiteLogo_retina_image_field'      => 'esc_url_raw',
			'csl_CustomSiteLogo_dark_image_field'        => 'esc_url_raw',
			'csl_CustomSiteLogo_mobile_image_field'      => 'esc_url_raw',
			'csl_CustomSiteLogo_mobile_breakpoint_field' => 'absint',
			'csl_CustomSiteLogo_custom_url_field'        => 'esc_url_raw',
			'csl_CustomSiteLogo_width_field'             => 'absint',
			'csl_CustomSiteLogo_height_field'            => 'absint',
			'csl_CustomSiteLogo_image_responsive_field'  => 'absint',
			'csl_CustomSiteLogo_hover_effect_field'      => 'sanitize_text_field',
			'csl_CustomSiteLogo_image_center_field'      => 'absint',
		);
	}

	/**
	 * Render the export/import UI beneath the main settings form.
	 *
	 * @since    1.2.0
	 */
	public function render_ui() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<p class="description"><?php esc_html_e( 'Download your Custom Site Logo configuration, or import a previously exported file.', 'custom-site-logo' ); ?></p>

		<div class="csl-import-export-grid">
			<div class="csl-io-card">
				<h3><span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Export', 'custom-site-logo' ); ?></h3>
				<p class="description"><?php esc_html_e( 'Save your current logo settings as a JSON file, handy for backups or moving your setup to another site.', 'custom-site-logo' ); ?></p>
				<a class="button button-secondary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=csl_export_settings' ), 'csl_export_settings' ) ); ?>">
					<?php esc_html_e( 'Export Settings', 'custom-site-logo' ); ?>
				</a>
			</div>

			<div class="csl-io-card">
				<h3><span class="dashicons dashicons-upload"></span> <?php esc_html_e( 'Import', 'custom-site-logo' ); ?></h3>
				<p class="description"><?php esc_html_e( 'Select a previously exported JSON file to restore its settings on this site.', 'custom-site-logo' ); ?></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
					<input type="hidden" name="action" value="csl_import_settings" />
					<?php wp_nonce_field( 'csl_import_settings' ); ?>
					<input type="file" name="csl_import_file" accept="application/json,.json" />
					<?php submit_button( __( 'Import Settings', 'custom-site-logo' ), 'secondary', 'submit', false ); ?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Stream the current settings as a downloadable JSON file.
	 *
	 * @since    1.2.0
	 */
	public function handle_export() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to do this.', 'custom-site-logo' ) );
		}

		check_admin_referer( 'csl_export_settings' );

		$options = get_option( self::OPTION_NAME, array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=custom-site-logo-settings-' . gmdate( 'Y-m-d' ) . '.json' );

		echo wp_json_encode( $options, JSON_PRETTY_PRINT );
		exit;
	}

	/**
	 * Validate and persist an uploaded settings JSON file.
	 *
	 * @since    1.2.0
	 */
	public function handle_import() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to do this.', 'custom-site-logo' ) );
		}

		check_admin_referer( 'csl_import_settings' );

		$redirect_args = array(
			'page' => 'custom-site-logo',
		);

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Tmp path is generated by PHP itself, not attacker-controlled; validated with is_uploaded_file() below.
		$tmp_name = isset( $_FILES['csl_import_file']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['csl_import_file']['tmp_name'] ) ) : '';

		if ( empty( $tmp_name ) || ! is_uploaded_file( $tmp_name ) ) {
			$redirect_args['csl_import'] = 'error';
			wp_safe_redirect( add_query_arg( $redirect_args, admin_url( 'themes.php' ) ) );
			exit;
		}

		$contents = file_get_contents( $tmp_name ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading a just-uploaded temp file, not a remote URL.
		$data     = json_decode( (string) $contents, true );

		if ( ! is_array( $data ) ) {
			$redirect_args['csl_import'] = 'error';
			wp_safe_redirect( add_query_arg( $redirect_args, admin_url( 'themes.php' ) ) );
			exit;
		}

		$sanitized = array();
		foreach ( $this->get_allowed_fields() as $key => $sanitizer ) {
			if ( isset( $data[ $key ] ) ) {
				$sanitized[ $key ] = call_user_func( $sanitizer, $data[ $key ] );
			}
		}

		update_option( self::OPTION_NAME, $sanitized );

		$redirect_args['csl_import'] = 'success';
		wp_safe_redirect( add_query_arg( $redirect_args, admin_url( 'themes.php' ) ) );
		exit;
	}

	/**
	 * Show an admin notice reporting the result of an import attempt.
	 *
	 * @since    1.2.0
	 */
	public function maybe_render_import_notice() {
		if ( ! isset( $_GET['page'], $_GET['csl_import'] ) || 'custom-site-logo' !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only check of a query flag used purely to display a notice.
			return;
		}

		if ( 'success' === $_GET['csl_import'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only check of a query flag used purely to display a notice.
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings imported successfully.', 'custom-site-logo' ) . '</p></div>';
		} else {
			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Could not import settings. Please check the file and try again.', 'custom-site-logo' ) . '</p></div>';
		}
	}
}
