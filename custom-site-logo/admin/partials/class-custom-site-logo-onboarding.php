<?php
/**
 * First-run guidance for the plugin.
 *
 * A freshly activated logo plugin does nothing visible until a logo is chosen
 * and placed, which is the point most people get stuck. This shows a short
 * welcome notice with the two things worth doing first, and offers to switch
 * on theme-logo replacement so the logo appears without editing a template.
 *
 * @link       https://no-site.com
 * @since      2.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */

/**
 * The first-run onboarding notice class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */
class Custom_Site_Logo_Onboarding {

	/**
	 * The option that records whether the welcome notice has been dismissed.
	 *
	 * @since 2.0.0
	 * @var   string
	 */
	const DISMISSED_OPTION = 'csl_onboarding_dismissed';

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'maybe_render_notice' ) );
		add_action( 'admin_post_csl_dismiss_onboarding', array( $this, 'handle_dismiss' ) );
		add_action( 'admin_post_csl_enable_theme_logo', array( $this, 'handle_enable_theme_logo' ) );
	}

	/**
	 * Show the welcome notice while there is still something to set up.
	 *
	 * @since 2.0.0
	 */
	public function maybe_render_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( get_option( self::DISMISSED_OPTION ) ) {
			return;
		}

		$has_logo     = ! empty( Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_image_field' ) );
		$is_displayed = (bool) Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_replace_theme_logo_field' );

		if ( $has_logo && $is_displayed ) {
			return;
		}

		$settings_url = admin_url( 'themes.php?page=custom-site-logo' );
		?>
		<div class="notice notice-info csl-onboarding-notice">
			<h3><?php esc_html_e( 'Custom Site Logo is ready to set up', 'custom-site-logo' ); ?></h3>

			<?php if ( ! $has_logo ) : ?>
				<p>
					<?php esc_html_e( 'Start by choosing a logo, or design a text logo right in the dashboard if you do not have one yet.', 'custom-site-logo' ); ?>
				</p>
				<p>
					<a class="button button-primary" href="<?php echo esc_url( $settings_url ); ?>">
						<?php esc_html_e( 'Choose a logo', 'custom-site-logo' ); ?>
					</a>
				</p>
			<?php else : ?>
				<p>
					<?php esc_html_e( 'Your logo is set. To show it without editing your theme, let the plugin fill your theme\'s own logo slot.', 'custom-site-logo' ); ?>
				</p>
				<p>
					<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=csl_enable_theme_logo' ), 'csl_enable_theme_logo' ) ); ?>">
						<?php esc_html_e( 'Show my logo in the theme', 'custom-site-logo' ); ?>
					</a>
					<a class="button" href="<?php echo esc_url( $settings_url ); ?>">
						<?php esc_html_e( 'All settings', 'custom-site-logo' ); ?>
					</a>
				</p>
			<?php endif; ?>

			<p>
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=csl_dismiss_onboarding' ), 'csl_dismiss_onboarding' ) ); ?>">
					<?php esc_html_e( 'Dismiss this notice', 'custom-site-logo' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Record that the notice has been dismissed.
	 *
	 * @since 2.0.0
	 */
	public function handle_dismiss() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to do this.', 'custom-site-logo' ) );
		}

		check_admin_referer( 'csl_dismiss_onboarding' );

		update_option( self::DISMISSED_OPTION, 1, false );

		wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url() );
		exit;
	}

	/**
	 * Switch on theme-logo replacement from the notice.
	 *
	 * @since 2.0.0
	 */
	public function handle_enable_theme_logo() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to do this.', 'custom-site-logo' ) );
		}

		check_admin_referer( 'csl_enable_theme_logo' );

		$options = get_option( Custom_Site_Logo_Options::OPTION_NAME, array() );

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$options['csl_CustomSiteLogo_replace_theme_logo_field'] = 1;

		update_option( Custom_Site_Logo_Options::OPTION_NAME, $options );
		Custom_Site_Logo_Options::flush_cache();
		update_option( self::DISMISSED_OPTION, 1, false );

		wp_safe_redirect( admin_url( 'themes.php?page=custom-site-logo' ) );
		exit;
	}
}
