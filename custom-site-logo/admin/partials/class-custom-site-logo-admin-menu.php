<?php
/**
 * The admin-settings page functionality of the plugin.
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
class Custom_Site_Logo_Admin_Menu {

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
		$this->csl_register_setting_section();
	}

	/**
	 * Register the setting section for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function csl_register_setting_section() {
		/* Adding Admin Page */
		add_action( 'admin_menu', array( $this, 'csl_add_menu_page' ) );

		/* Scoped to this plugin only, so it fires once, for this plugin's own row. */
		$plugin_basename = plugin_basename( dirname( __DIR__, 2 ) . '/custom-site-logo.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'csl_add_action_plugin' ) );
	}

	/**
	 * Register the settings page under the Appearance menu.
	 *
	 * @since    1.0.0
	 */
	public function csl_add_menu_page() {
		add_submenu_page(
			'themes.php', /* Adding this submenu to Settings Main Menu */
			__( 'Custom Site Logo', 'custom-site-logo' ),
			__( 'Custom Site Logo', 'custom-site-logo' ),
			'manage_options',
			'custom-site-logo',
			array( $this, 'csl_submenu_callback_function' )
		);
	}

	/**
	 * Register the setting section for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function csl_submenu_callback_function() {
		/* Check user capability */
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		/* WordPress will add the "settings-updated" $_GET parameter to the url. */
		if ( isset( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only check of a WordPress core generated flag, no state is changed here.
			// Add settings saved message with the class of "updated".
			add_settings_error( 'csl_success_messages', 'csl_success_messages', __( 'Logo settings saved successfully.', 'custom-site-logo' ), 'updated' );
			?>
		<div id="message" class="updated">
		<p><strong><?php esc_html_e( 'Settings saved successfully.', 'custom-site-logo' ); ?></strong></p>
		</div>
			<?php
		}

		// "Logo Maker" and "Import/Export" aren't Settings API sections; they're spliced in around the real ones.
		$tabs = array_merge(
			array( 'csl_logo_maker' => __( 'Logo Maker', 'custom-site-logo' ) ),
			Custom_Site_Logo_Admin_Settings::get_tabs(),
			array( 'csl_import_export' => __( 'Import / Export', 'custom-site-logo' ) )
		);

		$tab_icons = array(
			'csl_logo_maker'        => 'dashicons-edit',
			'csl_section_general'   => 'dashicons-format-image',
			'csl_section_size'      => 'dashicons-editor-expand',
			'csl_section_effects'   => 'dashicons-art',
			'csl_section_advanced'  => 'dashicons-images-alt2',
			'csl_section_sticky'    => 'dashicons-admin-page',
			'csl_section_locations' => 'dashicons-location-alt',
			'csl_section_rules'     => 'dashicons-calendar-alt',
			'csl_section_seo'       => 'dashicons-performance',
			'csl_import_export'     => 'dashicons-migrate',
		);
		?>
		<div class="wrap csl-settings-wrap">

		<div class="csl-page-header">
			<span class="dashicons dashicons-format-image"></span>
			<h1><?php esc_html_e( 'Custom Site Logo', 'custom-site-logo' ); ?></h1>
			<?php if ( defined( 'CUSTOM_SITE_LOGO_VERSION' ) ) : ?>
				<span class="csl-version-badge">
					<?php
					printf(
						/* translators: %s: The plugin's current version number. */
						esc_html__( 'v%s', 'custom-site-logo' ),
						esc_html( CUSTOM_SITE_LOGO_VERSION )
					);
					?>
				</span>
			<?php endif; ?>
		</div>

		<details class="csl-CustomSiteLogo-notice-block">
			<summary><?php esc_html_e( 'How do I display my logo?', 'custom-site-logo' ); ?></summary>
			<p>
				<?php esc_html_e( 'The easiest way is to turn on "Replace Theme Logo" under Display Locations, which fills your theme\'s own logo slot with no code at all.', 'custom-site-logo' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'To place it yourself, add the "Custom Site Logo" block in the block editor, the matching widget in a sidebar, or this shortcode in any post or page:', 'custom-site-logo' ); ?>
				<code>[csl_display_logo]</code>
			</p>
			<p>
				<?php esc_html_e( 'In a theme template, call it directly:', 'custom-site-logo' ); ?>
				<code>&lt;?php csl_CustomSiteLogo_show_logo(); ?&gt;</code>
			</p>
		</details>

		<div class="csl-layout">

			<nav class="csl-tab-nav" role="tablist" aria-label="<?php esc_attr_e( 'Custom Site Logo settings sections', 'custom-site-logo' ); ?>">
				<?php foreach ( $tabs as $tab_id => $tab_label ) : ?>
					<a href="#<?php echo esc_attr( $tab_id ); ?>-panel" id="<?php echo esc_attr( $tab_id ); ?>"
						class="csl-tab-nav-item" data-tab="<?php echo esc_attr( $tab_id ); ?>"
						role="tab" aria-selected="false" aria-controls="<?php echo esc_attr( $tab_id ); ?>-panel">
						<span class="dashicons <?php echo esc_attr( isset( $tab_icons[ $tab_id ] ) ? $tab_icons[ $tab_id ] : 'dashicons-admin-generic' ); ?>"></span>
						<span class="csl-tab-nav-label"><?php echo esc_html( $tab_label ); ?></span>
					</a>
				<?php endforeach; ?>
			</nav>

			<div class="csl-tab-panels">

			<form action="options.php" method="post" class="csl_CustomSiteLogo_form" >
			<!-- Display Settings Here -->
			<?php

				// Output security fields for the registered setting "csl_custom_site_logo".
				settings_fields( 'custom-site-logo' );

				$settings_sections = Custom_Site_Logo_Admin_Settings::get_tabs();

			foreach ( $tabs as $tab_id => $tab_label ) {
				if ( 'csl_import_export' === $tab_id ) {
					continue; // Rendered separately, in its own form, after this one.
				}

				printf(
					'<div class="csl-tab-content" id="%1$s-panel" data-tab="%1$s" role="tabpanel" aria-labelledby="%1$s">',
					esc_attr( $tab_id )
				);

				printf( '<h2 class="csl-panel-title">%s</h2>', esc_html( $tab_label ) );

				if ( isset( $settings_sections[ $tab_id ] ) ) {
					echo '<table class="form-table" role="presentation"><tbody>';
					do_settings_fields( 'custom-site-logo', $tab_id );
					echo '</tbody></table>';
				} else {
					/**
					 * Fires inside a non-Settings-API tab panel (e.g. "Logo Maker"), inside the main settings `<form>`.
					 *
					 * @since 1.2.0
					 * @param string $tab_id The id of the tab panel currently being rendered.
					 */
					do_action( 'custom_site_logo_render_custom_tab', $tab_id );
				}

				echo '</div>';
			}

				// Output save settings button. Hidden (via JS) while the Import/Export tab is active, since that tab has its own buttons.
				echo '<div class="csl-save-button-wrap">';
				submit_button( __( 'Save Settings', 'custom-site-logo' ) );
				echo '</div>';

			?>
			</form>

			<div class="csl-tab-content" id="csl_import_export-panel" data-tab="csl_import_export" role="tabpanel" aria-labelledby="csl_import_export">
			<h2 class="csl-panel-title"><?php esc_html_e( 'Import / Export', 'custom-site-logo' ); ?></h2>
			<?php
			/**
			 * Fires inside the "Import / Export" tab panel, inside the `.wrap` container.
			 *
			 * Used by Custom_Site_Logo_Export_Import to add its export/import UI
			 * without cluttering the main Settings API form.
			 *
			 * @since 1.2.0
			 */
			do_action( 'custom_site_logo_after_settings_form' );
			?>
			</div><!-- csl-tab-content (import/export) -->

			</div><!-- csl-tab-panels -->
		</div><!-- csl-layout -->

		</div><!-- wrap -->
		<?php
	}

	/**
	 * Add a "Settings" link to this plugin's row on the Plugins page.
	 *
	 * Hooked via the plugin-specific `plugin_action_links_{$plugin_basename}`
	 * filter (see csl_register_setting_section()), so this only ever runs
	 * for this plugin's own row and must always return the (possibly
	 * modified) $actions array.
	 *
	 * @since    1.0.0
	 * @param    array $actions    The existing list of action links for this plugin.
	 * @return   array             The list of action links, with the settings link prepended.
	 */
	public function csl_add_action_plugin( $actions ) {
		$settings = array(
			'settings' => '<a href="admin.php?page=custom-site-logo">' . esc_html__( 'Settings', 'custom-site-logo' ) . '</a>',
		);

		return array_merge( $settings, $actions );
	}
}

// Initialize class.
new Custom_Site_Logo_Admin_Menu();
