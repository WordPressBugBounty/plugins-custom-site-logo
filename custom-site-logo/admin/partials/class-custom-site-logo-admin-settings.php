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
class Custom_Site_Logo_Admin_Settings {

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
		$this->initialize_admin_hooks();
	}

	/**
	 * Initialize admin hooks.
	 *
	 * @since    1.0.0
	 */
	public function initialize_admin_hooks() {
		add_action( 'admin_init', array( $this, 'csl_init_settings' ) );
	}

	/**
	 * Register the plugin settings, section and fields.
	 *
	 * @since    1.0.0
	 */
	public function csl_init_settings() {
		/* Register General Settings */
		register_setting(
			'custom-site-logo',
			'csl_CustomSiteLogo_option_name',
			array(
				'sanitize_callback' => array( 'Custom_Site_Logo_Options', 'sanitize' ),
			)
		);

		$this->csl_register_setting_section();
		$this->csl_section_register_settings();
	}

	/**
	 * The settings page's tabs, in display order.
	 *
	 * Each tab maps to one Settings API section id. Shared here (rather than
	 * only in csl_register_setting_section()) so that Custom_Site_Logo_Admin_Menu
	 * can render the tab navigation and panels without duplicating this list.
	 *
	 * @since 1.2.0
	 * @return array Associative array of section id => tab label.
	 */
	public static function get_tabs() {
		return array(
			'csl_section_general'   => __( 'General', 'custom-site-logo' ),
			'csl_section_size'      => __( 'Size & Layout', 'custom-site-logo' ),
			'csl_section_effects'   => __( 'Hover Effect', 'custom-site-logo' ),
			'csl_section_advanced'  => __( 'Advanced Logos', 'custom-site-logo' ),
			'csl_section_sticky'    => __( 'Sticky Logo', 'custom-site-logo' ),
			'csl_section_locations' => __( 'Display Locations', 'custom-site-logo' ),
			'csl_section_rules'     => __( 'Conditional Rules', 'custom-site-logo' ),
			'csl_section_seo'       => __( 'SEO & Performance', 'custom-site-logo' ),
		);
	}

	/**
	 * Register the setting sections for this plugin, one per tab.
	 *
	 * @since    1.0.0
	 */
	public function csl_register_setting_section() {
		foreach ( self::get_tabs() as $section_id => $section_label ) {
			add_settings_section( $section_id, $section_label, '__return_false', 'custom-site-logo' );
		}
	}

	/**
	 * Register the setting fields for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function csl_section_register_settings() {
		/* Logo Image Upload Field */
		add_settings_field(
			'csl_CustomSiteLogo_image_field',
			__( 'Logo Image', 'custom-site-logo' ),
			array( $this, 'csl_image_field_callback_function' ),
			'custom-site-logo',
			'csl_section_general',
			array(
				'label_for' => 'csl_CustomSiteLogo_image_field',
				'class'     => 'csl_CustomSiteLogo_row_image',
			)
		);

		/* Logo Custom URL Link Field */
		add_settings_field(
			'csl_CustomSiteLogo_custom_url_field',
			__( 'Custom Logo Link', 'custom-site-logo' ),
			array( $this, 'csl_custom_url_field_callback_function' ),
			'custom-site-logo',
			'csl_section_general',
			array(
				'label_for' => 'csl_CustomSiteLogo_custom_url_field',
				'class'     => 'csl_CustomSiteLogo_row_custom_url',
			)
		);

		/* Logo Width Field */
		add_settings_field(
			'csl_CustomSiteLogo_width_field',
			__( 'Logo Width', 'custom-site-logo' ),
			array( $this, 'csl_width_field_callback_function' ),
			'custom-site-logo',
			'csl_section_size',
			array(
				'label_for' => 'csl_CustomSiteLogo_width_field',
				'class'     => 'csl_CustomSiteLogo_row_width_field',
			)
		);

		/* Logo Height Field */
		add_settings_field(
			'csl_CustomSiteLogo_height_field',
			__( 'Logo Height', 'custom-site-logo' ),
			array( $this, 'csl_height_field_callback_function' ),
			'custom-site-logo',
			'csl_section_size',
			array(
				'label_for' => 'csl_CustomSiteLogo_height_field',
				'class'     => 'csl_CustomSiteLogo_row_height_field',
			)
		);

		/* Logo Image Center Field */
		add_settings_field(
			'csl_CustomSiteLogo_image_center_field',
			__( 'Center Logo', 'custom-site-logo' ),
			array( $this, 'csl_image_center_field_callback_function' ),
			'custom-site-logo',
			'csl_section_size',
			array(
				'label_for' => 'csl_CustomSiteLogo_image_center_field',
				'class'     => 'csl_CustomSiteLogo_row_image_center',
			)
		);

		/* Logo Image Responsive Field */
		add_settings_field(
			'csl_CustomSiteLogo_image_responsive_field',
			__( 'Make Logo Responsive', 'custom-site-logo' ),
			array( $this, 'csl_image_responsive_field_callback_function' ),
			'custom-site-logo',
			'csl_section_size',
			array(
				'label_for' => 'csl_CustomSiteLogo_image_responsive_field',
				'class'     => 'csl_CustomSiteLogo_row_image_responsive',
			)
		);

		/* Logo Hover Effect Field */
		add_settings_field(
			'csl_CustomSiteLogo_hover_effect_field',
			__( 'Hover Effect', 'custom-site-logo' ),
			array( $this, 'csl_hover_effecr_field_callback_function' ),
			'custom-site-logo',
			'csl_section_effects',
			array(
				'label_for' => 'csl_CustomSiteLogo_hover_effect_field',
				'class'     => 'csl_CustomSiteLogo_row_custom_url',
			)
		);

		/* Retina (@2x) Logo Image Field */
		add_settings_field(
			'csl_CustomSiteLogo_retina_image_field',
			__( 'Retina (@2x) Logo', 'custom-site-logo' ),
			array( $this, 'csl_retina_image_field_callback_function' ),
			'custom-site-logo',
			'csl_section_advanced',
			array(
				'label_for' => 'csl_CustomSiteLogo_retina_image_field',
				'class'     => 'csl_CustomSiteLogo_row_retina_image',
			)
		);

		/* Dark Mode Logo Image Field */
		add_settings_field(
			'csl_CustomSiteLogo_dark_image_field',
			__( 'Dark Mode Logo', 'custom-site-logo' ),
			array( $this, 'csl_dark_image_field_callback_function' ),
			'custom-site-logo',
			'csl_section_advanced',
			array(
				'label_for' => 'csl_CustomSiteLogo_dark_image_field',
				'class'     => 'csl_CustomSiteLogo_row_dark_image',
			)
		);

		/* Mobile Logo Image Field */
		add_settings_field(
			'csl_CustomSiteLogo_mobile_image_field',
			__( 'Mobile Logo', 'custom-site-logo' ),
			array( $this, 'csl_mobile_image_field_callback_function' ),
			'custom-site-logo',
			'csl_section_advanced',
			array(
				'label_for' => 'csl_CustomSiteLogo_mobile_image_field',
				'class'     => 'csl_CustomSiteLogo_row_mobile_image',
			)
		);

		/* Mobile Breakpoint Field */
		add_settings_field(
			'csl_CustomSiteLogo_mobile_breakpoint_field',
			__( 'Mobile Breakpoint', 'custom-site-logo' ),
			array( $this, 'csl_mobile_breakpoint_field_callback_function' ),
			'custom-site-logo',
			'csl_section_advanced',
			array(
				'label_for' => 'csl_CustomSiteLogo_mobile_breakpoint_field',
				'class'     => 'csl_CustomSiteLogo_row_mobile_breakpoint',
			)
		);

		$this->register_declared_fields();
	}

	/**
	 * The settings added in 2.0.0, declared rather than hand-registered.
	 *
	 * Each entry only needs a key, a label and a field type; the shared
	 * render_field() dispatcher below turns that into markup, which keeps
	 * adding a setting down to a single line.
	 *
	 * @since 2.0.0
	 * @return array Map of section id => list of field definitions.
	 */
	private function get_declared_fields() {
		return array(
			'csl_section_general'   => array(
				array(
					'key'         => 'csl_CustomSiteLogo_alt_text_field',
					'label'       => __( 'Alt Text', 'custom-site-logo' ),
					'type'        => 'text',
					'description' => __( 'Describes the logo to screen readers and search engines. Defaults to your site title.', 'custom-site-logo' ),
				),
			),
			'csl_section_sticky'    => array(
				array(
					'key'         => 'csl_CustomSiteLogo_sticky_enabled_field',
					'label'       => __( 'Enable Sticky Logo', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Shrink the logo once the visitor scrolls down.', 'custom-site-logo' ),
					'description' => __( 'Works with any theme whose header stays fixed while scrolling.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_sticky_image_field',
					'label'       => __( 'Sticky Logo Image', 'custom-site-logo' ),
					'type'        => 'image',
					'description' => __( 'Optional. A compact logo swapped in while scrolled, e.g. an icon-only mark. Leave empty to reuse the main logo.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_sticky_scale_field',
					'label'       => __( 'Shrink To', 'custom-site-logo' ),
					'type'        => 'number',
					'min'         => 10,
					'max'         => 100,
					'suffix'      => '%',
					'description' => __( 'The scrolled size, as a percentage of the normal logo size.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_sticky_offset_field',
					'label'       => __( 'Scroll Offset', 'custom-site-logo' ),
					'type'        => 'number',
					'min'         => 0,
					'suffix'      => 'px',
					'description' => __( 'How far down the page the visitor must scroll before the logo shrinks.', 'custom-site-logo' ),
				),
			),
			'csl_section_locations' => array(
				array(
					'key'         => 'csl_CustomSiteLogo_replace_theme_logo_field',
					'label'       => __( 'Replace Theme Logo', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Use this logo wherever the theme shows its own logo.', 'custom-site-logo' ),
					'description' => __( 'Fills your theme\'s built-in logo slot, so you do not need the shortcode, block, widget, or template tag at all.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_login_enabled_field',
					'label'       => __( 'Login Screen Logo', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Replace the WordPress logo above the login form.', 'custom-site-logo' ),
					'description' => __( 'The login logo also links to your site instead of wordpress.org.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_login_image_field',
					'label'       => __( 'Login Logo Image', 'custom-site-logo' ),
					'type'        => 'image',
					'description' => __( 'Optional. Leave empty to reuse the main logo.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_login_width_field',
					'label'       => __( 'Login Logo Width', 'custom-site-logo' ),
					'type'        => 'number',
					'min'         => 20,
					'max'         => 320,
					'suffix'      => 'px',
					'description' => __( 'The height is worked out from your image so it never looks stretched.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_admin_bar_field',
					'label'       => __( 'Admin Bar Logo', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Replace the WordPress icon in the admin bar.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_admin_footer_field',
					'label'       => __( 'Dashboard Footer Logo', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Show the logo in the dashboard footer.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_woo_email_field',
					'label'       => __( 'WooCommerce Emails', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Use the logo as the header image in WooCommerce emails.', 'custom-site-logo' ),
					'description' => __( 'Only applies when WooCommerce is active.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_print_field',
					'label'       => __( 'Print Styles', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Make sure the logo appears on printed pages.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_print_image_field',
					'label'       => __( 'Print Logo Image', 'custom-site-logo' ),
					'type'        => 'image',
					'description' => __( 'Optional. A version suited to paper, e.g. solid black. Leave empty to print the main logo.', 'custom-site-logo' ),
				),
			),
			'csl_section_rules'     => array(
				array(
					'key'         => 'csl_CustomSiteLogo_schedules_field',
					'label'       => __( 'Scheduled Logos', 'custom-site-logo' ),
					'type'        => 'repeater',
					'description' => __( 'Swap the logo automatically between two dates, for a holiday or a campaign. Both dates are inclusive, and an empty date means open-ended. Schedules take priority over the conditional rules below.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_conditions_field',
					'label'       => __( 'Conditional Logos', 'custom-site-logo' ),
					'type'        => 'repeater',
					'description' => __( 'Show a different logo when a condition is met. Rules are checked top to bottom and the first match wins.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_language_logos_field',
					'label'       => __( 'Logo Per Language', 'custom-site-logo' ),
					'type'        => 'repeater',
					'description' => __( 'Assign a logo to a locale, e.g. fr_FR. Use just the language code (fr) to cover every regional variant.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_rotation_enabled_field',
					'label'       => __( 'Rotate Logos', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Pick a random logo from the list below on each page load.', 'custom-site-logo' ),
				),
				array(
					'key'   => 'csl_CustomSiteLogo_rotation_images_field',
					'label' => __( 'Rotation Images', 'custom-site-logo' ),
					'type'  => 'repeater',
				),
				array(
					'key'         => 'csl_CustomSiteLogo_network_default_field',
					'label'       => __( 'Network Default', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Fall back to the network-wide logo when this site has none.', 'custom-site-logo' ),
					'description' => __( 'Multisite only. Set the network logo under Network Admin » Settings.', 'custom-site-logo' ),
				),
			),
			'csl_section_seo'       => array(
				array(
					'key'         => 'csl_CustomSiteLogo_link_disable_field',
					'label'       => __( 'Disable Link', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Render the logo without wrapping it in a link.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_link_new_tab_field',
					'label'       => __( 'Open In New Tab', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Open the logo link in a new tab.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_link_nofollow_field',
					'label'       => __( 'Nofollow Link', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Add rel="nofollow" to the logo link.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_title_text_field',
					'label'       => __( 'Title Attribute', 'custom-site-logo' ),
					'type'        => 'text',
					'description' => __( 'Optional. Shown as a tooltip when hovering the logo.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_aria_label_field',
					'label'       => __( 'Link ARIA Label', 'custom-site-logo' ),
					'type'        => 'text',
					'description' => __( 'Optional. Describes where the logo link goes, e.g. "Home". Helps screen reader users.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_dimensions_field',
					'label'       => __( 'Size Attributes', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Add width and height attributes to the logo.', 'custom-site-logo' ),
					'description' => __( 'Lets the browser reserve space for the logo, which prevents the page from jumping as it loads. Recommended.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_preload_field',
					'label'       => __( 'Preload Logo', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Tell the browser to start downloading the logo immediately.', 'custom-site-logo' ),
					'description' => __( 'Useful when the logo is the largest thing visible on first paint. Leave off if the logo sits below the fold.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_lazy_load_field',
					'label'       => __( 'Lazy Load', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Only load the logo when it scrolls into view.', 'custom-site-logo' ),
					'description' => __( 'Only worth enabling for a logo in the footer. Lazy loading a header logo makes it appear later, not sooner.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_schema_field',
					'label'       => __( 'Structured Data', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Publish the logo as Organization structured data.', 'custom-site-logo' ),
					'description' => __( 'This is what search engines read to associate a logo with your site. Turn off if your SEO plugin already outputs it.', 'custom-site-logo' ),
				),
				array(
					'key'         => 'csl_CustomSiteLogo_click_tracking_field',
					'label'       => __( 'Track Logo Clicks', 'custom-site-logo' ),
					'type'        => 'toggle',
					'toggle_text' => __( 'Count clicks on the logo.', 'custom-site-logo' ),
					'description' => __( 'Also sends a site_logo_click event to Google Analytics when gtag.js is present.', 'custom-site-logo' ),
				),
				array(
					'key'   => 'csl_click_stats',
					'label' => __( 'Click Stats', 'custom-site-logo' ),
					'type'  => 'stats',
				),
			),
		);
	}

	/**
	 * Register every declared field with the Settings API.
	 *
	 * @since 2.0.0
	 */
	private function register_declared_fields() {
		foreach ( $this->get_declared_fields() as $section => $fields ) {
			foreach ( $fields as $field ) {
				add_settings_field(
					$field['key'],
					$field['label'],
					array( $this, 'render_field' ),
					'custom-site-logo',
					$section,
					array_merge(
						$field,
						array(
							'label_for' => $field['key'],
							'class'     => 'csl_CustomSiteLogo_row_' . $field['key'],
						)
					)
				);
			}
		}
	}

	/**
	 * Render a declared field, dispatching on its type.
	 *
	 * @since 2.0.0
	 * @param array $args The field definition, as passed through add_settings_field().
	 */
	public function render_field( $args ) {
		$key   = $args['key'];
		$value = 'stats' === $args['type'] ? '' : Custom_Site_Logo_Options::get( $key );

		switch ( $args['type'] ) {
			case 'toggle':
				$this->render_toggle_field( $key, $value, $args['toggle_text'] );
				break;

			case 'text':
				printf(
					'<input type="text" id="%1$s" class="regular-text" name="%2$s[%1$s]" value="%3$s" />',
					esc_attr( $key ),
					esc_attr( Custom_Site_Logo_Options::OPTION_NAME ),
					esc_attr( $value )
				);
				break;

			case 'number':
				printf(
					'<input type="number" id="%1$s" class="small-text" name="%2$s[%1$s]" value="%3$s"%4$s%5$s /> %6$s',
					esc_attr( $key ),
					esc_attr( Custom_Site_Logo_Options::OPTION_NAME ),
					esc_attr( $value ),
					isset( $args['min'] ) ? ' min="' . esc_attr( $args['min'] ) . '"' : '',
					isset( $args['max'] ) ? ' max="' . esc_attr( $args['max'] ) . '"' : '',
					isset( $args['suffix'] ) ? esc_html( $args['suffix'] ) : ''
				);
				break;

			case 'image':
				$this->render_image_field(
					array(
						'id'    => $key,
						'name'  => $key,
						'value' => $value,
					)
				);
				break;

			case 'repeater':
				$this->render_repeater_field( $key, is_array( $value ) ? $value : array() );
				break;

			case 'stats':
				$this->render_click_stats();
				break;
		}

		if ( ! empty( $args['description'] ) ) {
			printf( '<p class="description">%s</p>', esc_html( $args['description'] ) );
		}
	}

	/**
	 * Render a repeatable set of rows.
	 *
	 * The row markup is driven by the repeater schema, and a hidden template
	 * row is emitted alongside the saved rows for the "Add" button to clone.
	 *
	 * @since 2.0.0
	 * @param string $key  The option key.
	 * @param array  $rows The saved rows.
	 */
	private function render_repeater_field( $key, $rows ) {
		$subfields = Custom_Site_Logo_Options::get_repeater_schema();

		if ( ! isset( $subfields[ $key ] ) ) {
			return;
		}

		$subfields = array_keys( $subfields[ $key ] );
		?>
		<div class="csl-repeater" data-csl-repeater="<?php echo esc_attr( $key ); ?>">
			<div class="csl-repeater-rows">
				<?php foreach ( $rows as $index => $row ) : ?>
					<?php $this->render_repeater_row( $key, $subfields, $row, (int) $index ); ?>
				<?php endforeach; ?>
			</div>

			<script type="text/html" class="csl-repeater-template">
				<?php $this->render_repeater_row( $key, $subfields, array(), '__INDEX__' ); ?>
			</script>

			<button type="button" class="button button-secondary csl-repeater-add">
				<span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e( 'Add Row', 'custom-site-logo' ); ?>
			</button>
		</div>
		<?php
	}

	/**
	 * Render one repeater row.
	 *
	 * @since 2.0.0
	 * @param string     $key       The option key.
	 * @param array      $subfields The sub-field names for this repeater.
	 * @param array      $row       The row's saved values.
	 * @param int|string $index     The row index, or the template placeholder.
	 */
	private function render_repeater_row( $key, $subfields, $row, $index ) {
		$name_base = Custom_Site_Logo_Options::OPTION_NAME . '[' . $key . '][' . $index . ']';
		?>
		<div class="csl-repeater-row">
			<?php foreach ( $subfields as $subfield ) : ?>
				<?php
				$value = isset( $row[ $subfield ] ) ? $row[ $subfield ] : '';
				$name  = $name_base . '[' . $subfield . ']';
				?>
				<label class="csl-repeater-cell csl-repeater-cell--<?php echo esc_attr( $subfield ); ?>">
					<span class="csl-repeater-label"><?php echo esc_html( $this->get_subfield_label( $subfield ) ); ?></span>
					<?php if ( 'start' === $subfield || 'end' === $subfield ) : ?>
						<input type="date" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
					<?php elseif ( 'type' === $subfield ) : ?>
						<select name="<?php echo esc_attr( $name ); ?>">
							<?php foreach ( Custom_Site_Logo_Options::get_condition_types() as $type => $label ) : ?>
								<option value="<?php echo esc_attr( $type ); ?>" <?php selected( $value, $type ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php elseif ( 'image' === $subfield ) : ?>
						<span class="csl-repeater-image">
							<input type="text" class="csl-repeater-image-input" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'Image URL', 'custom-site-logo' ); ?>" />
							<button type="button" class="button button-secondary csl-repeater-media"><span class="dashicons dashicons-admin-media"></span></button>
						</span>
					<?php else : ?>
						<input type="text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $this->get_subfield_placeholder( $subfield ) ); ?>" />
					<?php endif; ?>
				</label>
			<?php endforeach; ?>

			<button type="button" class="button-link csl-repeater-remove" aria-label="<?php esc_attr_e( 'Remove this row', 'custom-site-logo' ); ?>">
				<span class="dashicons dashicons-trash"></span>
			</button>
		</div>
		<?php
	}

	/**
	 * Human-readable label for a repeater sub-field.
	 *
	 * @since 2.0.0
	 * @param  string $subfield The sub-field name.
	 * @return string
	 */
	private function get_subfield_label( $subfield ) {
		$labels = array(
			'label'  => __( 'Name', 'custom-site-logo' ),
			'start'  => __( 'From', 'custom-site-logo' ),
			'end'    => __( 'Until', 'custom-site-logo' ),
			'image'  => __( 'Logo', 'custom-site-logo' ),
			'type'   => __( 'When', 'custom-site-logo' ),
			'value'  => __( 'Matches', 'custom-site-logo' ),
			'locale' => __( 'Locale', 'custom-site-logo' ),
		);

		return isset( $labels[ $subfield ] ) ? $labels[ $subfield ] : $subfield;
	}

	/**
	 * Placeholder text for a repeater sub-field.
	 *
	 * @since 2.0.0
	 * @param  string $subfield The sub-field name.
	 * @return string
	 */
	private function get_subfield_placeholder( $subfield ) {
		$placeholders = array(
			'label'  => __( 'Christmas', 'custom-site-logo' ),
			'value'  => __( 'e.g. page, 42, news', 'custom-site-logo' ),
			'locale' => __( 'fr_FR', 'custom-site-logo' ),
		);

		return isset( $placeholders[ $subfield ] ) ? $placeholders[ $subfield ] : '';
	}

	/**
	 * Show the recorded logo click totals.
	 *
	 * @since 2.0.0
	 */
	private function render_click_stats() {
		$total  = Custom_Site_Logo_Rest::get_total_clicks();
		$recent = Custom_Site_Logo_Rest::get_recent_clicks( 30 );
		?>
		<div class="csl-stats">
			<span class="csl-stat">
				<strong><?php echo esc_html( number_format_i18n( $recent ) ); ?></strong>
				<?php esc_html_e( 'clicks in the last 30 days', 'custom-site-logo' ); ?>
			</span>
			<span class="csl-stat">
				<strong><?php echo esc_html( number_format_i18n( $total ) ); ?></strong>
				<?php esc_html_e( 'clicks recorded in total', 'custom-site-logo' ); ?>
			</span>
		</div>
		<?php
	}

	/**
	 * Register the image setting field.
	 *
	 * @since    1.0.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_image_field_callback_function( $args ) {
		$csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
		$field_value = ! empty( $csl_options[ $args['label_for'] ] ) && 'Select Logo' !== $csl_options[ $args['label_for'] ] ? $csl_options[ $args['label_for'] ] : '';
		$this->render_image_field(
			array(
				'id'          => 'csl_CustomSiteLogo_logo_image',
				'name'        => $args['label_for'],
				'value'       => $field_value,
				'placeholder' => __( 'Select Logo', 'custom-site-logo' ),
				'description' => __( 'Enter an image URL, or select one from the media library.', 'custom-site-logo' ),
			)
		);
	}

	/**
	 * Render a media-library-backed image field: thumbnail preview, text
	 * input, "Media Library" button, and a "Remove" link (shared markup
	 * used by the logo/retina/dark/mobile image fields).
	 *
	 * @since    1.2.0
	 * @param    array $field The field definition: `id` is the base id for the input,
	 *                        button, and thumbnail elements, `name` is the option key
	 *                        the `name` attribute is built from, `value` is the current
	 *                        value, and `placeholder` and `description` are optional.
	 */
	private function render_image_field( $field ) {
		$field = wp_parse_args(
			$field,
			array(
				'id'          => '',
				'name'        => '',
				'value'       => '',
				'placeholder' => '',
				'description' => '',
			)
		);
		?>
		<div class="csl-image-field">
			<div class="csl-image-thumb" id="<?php echo esc_attr( $field['id'] ); ?>_thumb">
				<?php if ( $field['value'] ) : ?>
					<img src="<?php echo esc_url( $field['value'] ); ?>" alt="" />
				<?php else : ?>
					<span class="dashicons dashicons-format-image"></span>
				<?php endif; ?>
			</div>
			<div class="csl-image-controls">
				<input id="<?php echo esc_attr( $field['id'] ); ?>" class="regular-text code" type="text"
					name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $field['name'] ); ?>]"
					<?php
					if ( $field['placeholder'] ) :
						?>
						placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"<?php endif; ?>
					value="<?php echo esc_attr( $field['value'] ); ?>">
				<div class="csl-image-buttons">
					<button type="button" id="<?php echo esc_attr( $field['id'] ); ?>_button" class="button button-secondary csl-media-button">
						<span class="dashicons dashicons-admin-media"></span> <?php esc_html_e( 'Media Library', 'custom-site-logo' ); ?>
					</button>
					<button type="button" class="button-link csl-remove-image-button" style="<?php echo $field['value'] ? '' : 'display:none;'; ?>">
						<?php esc_html_e( 'Remove', 'custom-site-logo' ); ?>
					</button>
				</div>
				<?php if ( $field['description'] ) : ?>
					<p class="description"><?php echo esc_html( $field['description'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Register the retina (@2x) image setting field.
	 *
	 * @since    1.2.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_retina_image_field_callback_function( $args ) {
		$csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
		$field_value = ! empty( $csl_options[ $args['label_for'] ] ) ? $csl_options[ $args['label_for'] ] : '';
		$this->render_image_field(
			array(
				'id'          => 'csl_CustomSiteLogo_retina_image',
				'name'        => $args['label_for'],
				'value'       => $field_value,
				'description' => __( 'Optional. A double-resolution (@2x) version of the logo, served to high-DPI/retina screens.', 'custom-site-logo' ),
			)
		);
	}

	/**
	 * Register the dark-mode logo setting field.
	 *
	 * @since    1.2.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_dark_image_field_callback_function( $args ) {
		$csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
		$field_value = ! empty( $csl_options[ $args['label_for'] ] ) ? $csl_options[ $args['label_for'] ] : '';
		$this->render_image_field(
			array(
				'id'          => 'csl_CustomSiteLogo_dark_image',
				'name'        => $args['label_for'],
				'value'       => $field_value,
				'description' => __( 'Optional. An alternate logo automatically shown when the visitor\'s device/browser is set to dark mode.', 'custom-site-logo' ),
			)
		);
	}

	/**
	 * Register the mobile logo setting field.
	 *
	 * @since    1.2.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_mobile_image_field_callback_function( $args ) {
		$csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
		$field_value = ! empty( $csl_options[ $args['label_for'] ] ) ? $csl_options[ $args['label_for'] ] : '';
		$this->render_image_field(
			array(
				'id'          => 'csl_CustomSiteLogo_mobile_image',
				'name'        => $args['label_for'],
				'value'       => $field_value,
				'description' => __( 'Optional. An alternate logo shown on small screens (see the breakpoint below).', 'custom-site-logo' ),
			)
		);
	}

	/**
	 * Register the mobile breakpoint setting field.
	 *
	 * @since    1.2.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_mobile_breakpoint_field_callback_function( $args ) {
		$csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
		$field_value = ! empty( $csl_options[ $args['label_for'] ] ) ? $csl_options[ $args['label_for'] ] : Custom_Site_Logo_Renderer::DEFAULT_MOBILE_BREAKPOINT;
		?>
		<input id="csl_CustomSiteLogo_mobile_breakpoint" class="small-text" type="number" min="0"
		name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo esc_attr( $field_value ); ?>"> px
		<p class="description"><?php esc_html_e( 'The screen width at or below which the mobile logo is shown.', 'custom-site-logo' ); ?></p>
		<?php
	}

	/**
	 * Register the width setting field.
	 *
	 * @since    1.0.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_width_field_callback_function( $args ) {
		$csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
		?>
		<input id="csl_CustomSiteLogo_logo_width" class="regular-text code" type="number"
		name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo ! empty( $csl_options[ $args['label_for'] ] ) ? esc_attr( $csl_options[ $args['label_for'] ] ) : ''; ?>">
		<p class="description">
			<?php esc_html_e( 'Enter the logo width in pixels, or leave empty to use the original image width.', 'custom-site-logo' ); ?>
		</p>
		<?php
	}

	/**
	 * Register the height setting field.
	 *
	 * @since    1.0.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_height_field_callback_function( $args ) {
		$csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
		?>
		<input id="csl_CustomSiteLogo_logo_height" class="regular-text code" type="number"
		name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="<?php echo ! empty( $csl_options[ $args['label_for'] ] ) ? esc_attr( $csl_options[ $args['label_for'] ] ) : ''; ?>">
		<p class="description">
			<?php esc_html_e( 'Enter the logo height in pixels, or leave empty to use the original image height.', 'custom-site-logo' ); ?>
		</p>
		<?php
	}

	/**
	 * Register the center-image setting field.
	 *
	 * @since    1.0.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_image_center_field_callback_function( $args ) {
		$csl_options        = get_option( 'csl_CustomSiteLogo_option_name' );
		$center_logo_option = isset( $csl_options[ $args['label_for'] ] ) ? $csl_options[ $args['label_for'] ] : 0;
		$this->render_toggle_field( $args['label_for'], $center_logo_option, __( 'Center the logo horizontally.', 'custom-site-logo' ) );
	}

	/**
	 * Render a checkbox field styled as a modern on/off toggle switch.
	 *
	 * @since    1.2.0
	 * @param    string $field_id The option key/field id.
	 * @param    mixed  $value    The field's current value.
	 * @param    string $label    The label shown next to the switch.
	 */
	private function render_toggle_field( $field_id, $value, $label ) {
		?>
		<label class="csl-toggle-field" for="<?php echo esc_attr( $field_id ); ?>">
			<?php
			/*
			 * An unchecked checkbox submits nothing, which is indistinguishable
			 * from a payload that never carried the setting. This pairs each
			 * toggle with a hidden "0" so "off" is always sent explicitly.
			 */
			?>
			<input type="hidden" name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $field_id ); ?>]" value="0" />
			<input type="checkbox" id="<?php echo esc_attr( $field_id ); ?>"
				name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $field_id ); ?>]"
				value="1" <?php checked( $value, 1 ); ?> />
			<span class="csl-toggle-switch" aria-hidden="true"></span>
			<span><?php echo esc_html( $label ); ?></span>
		</label>
		<?php
	}

	/**
	 * Register the responsive-image setting field.
	 *
	 * @since    1.0.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_image_responsive_field_callback_function( $args ) {
		$csl_options            = get_option( 'csl_CustomSiteLogo_option_name' );
		$responsive_logo_option = isset( $csl_options[ $args['label_for'] ] ) ? $csl_options[ $args['label_for'] ] : 0;
		$this->render_toggle_field( $args['label_for'], $responsive_logo_option, __( 'Automatically scale the logo to fit its container on smaller screens.', 'custom-site-logo' ) );
	}

	/**
	 * Register the custom url setting field.
	 *
	 * @since    1.0.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_custom_url_field_callback_function( $args ) {
		$csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
		?>
		<input id="csl_CustomSiteLogo_custom_url_responsive_field" class="regular-text code" type="text"
			name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $args['label_for'] ); ?>]"
			placeholder="<?php esc_attr_e( 'https://example.com', 'custom-site-logo' ); ?>" value="<?php echo ! empty( $csl_options[ $args['label_for'] ] ) ? esc_attr( $csl_options[ $args['label_for'] ] ) : ''; ?>">
		<p class="description">
			<?php esc_html_e( 'If set, the logo will link to this URL instead of the site homepage.', 'custom-site-logo' ); ?>
		</p>
		<?php
	}

	/**
	 * Register the hover effects setting field.
	 *
	 * @since    1.0.0
	 * @param    array $args Field arguments, including the `label_for` option name.
	 */
	public function csl_hover_effecr_field_callback_function( $args ) {
		$csl_options = get_option( 'csl_CustomSiteLogo_option_name' );

		if ( empty( $csl_options[ $args['label_for'] ] ) ) {
			$csl_options[ $args['label_for'] ] = 'none';
		}

		$hover_effects = array(
			'none'                       => __( 'None', 'custom-site-logo' ),
			'hvr-grow'                   => __( 'Grow', 'custom-site-logo' ),
			'hvr-shrink'                 => __( 'Shrink', 'custom-site-logo' ),
			'hvr-push'                   => __( 'Push', 'custom-site-logo' ),
			'hvr-pop'                    => __( 'Pop', 'custom-site-logo' ),
			'hvr-rotate'                 => __( 'Rotate', 'custom-site-logo' ),
			'hvr-grow-rotate'            => __( 'Grow Rotate', 'custom-site-logo' ),
			'hvr-float'                  => __( 'Float', 'custom-site-logo' ),
			'hvr-sink'                   => __( 'Sink', 'custom-site-logo' ),
			'hvr-skew'                   => __( 'Skew', 'custom-site-logo' ),
			'hvr-skew-forward'           => __( 'Skew Forward', 'custom-site-logo' ),
			'hvr-skew-backward'          => __( 'Skew Backward', 'custom-site-logo' ),
			'hvr-wobble-horizontal'      => __( 'Wobble Horizontal', 'custom-site-logo' ),
			'hvr-wobble-vertical'        => __( 'Wobble Vertical', 'custom-site-logo' ),
			'hvr-wobble-to-bottom-right' => __( 'Wobble to Bottom Right', 'custom-site-logo' ),
			'hvr-wobble-to-top-right'    => __( 'Wobble to Top Right', 'custom-site-logo' ),
			'hvr-wobble-top'             => __( 'Wobble Top', 'custom-site-logo' ),
			'hvr-wobble-bottom'          => __( 'Wobble Bottom', 'custom-site-logo' ),
			'hvr-wobble-skew'            => __( 'Wobble Skew', 'custom-site-logo' ),
			'hvr-buzz'                   => __( 'Buzz', 'custom-site-logo' ),
			'hvr-buzz-out'               => __( 'Buzz Out', 'custom-site-logo' ),
			'rotate-csl'                 => __( 'Rotate (Continuous)', 'custom-site-logo' ),
		);
		?>
		<p>
			<select id="csl_CustomSiteLogo_hover_effect" name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $args['label_for'] ); ?>]">
				<?php foreach ( $hover_effects as $effect_value => $effect_label ) : ?>
					<option value="<?php echo esc_attr( $effect_value ); ?>" <?php selected( $csl_options[ $args['label_for'] ], $effect_value ); ?>><?php echo esc_html( $effect_label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<div class="csl-preview-blocks"
			<?php
			$has_logo = ! empty( $csl_options['csl_CustomSiteLogo_image_field'] ) && 'Select Logo' !== $csl_options['csl_CustomSiteLogo_image_field'];
			if ( ! $has_logo ) {
				echo 'style="display:none;"';
			}
			?>
		>
			<p id="csl-margi-btm"><strong><?php esc_html_e( 'Preview your logo with the selected hover effect below.', 'custom-site-logo' ); ?></strong></p>
			<?php if ( ! empty( $csl_options['csl_CustomSiteLogo_image_field'] ) ) : ?>
				<img id="csl_CustomSiteLogo_admin_hover_preview" class="<?php echo esc_attr( $csl_options['csl_CustomSiteLogo_hover_effect_field'] ); ?>" src="<?php echo esc_attr( $csl_options['csl_CustomSiteLogo_image_field'] ); ?>" alt="<?php esc_attr_e( 'Logo preview', 'custom-site-logo' ); ?>" />
			<?php endif; ?>

			<p class="description"><?php esc_html_e( 'Choose the hover effect that best complements your logo.', 'custom-site-logo' ); ?></p>
		</div>

		<div class="csl-error-logo-url">
			<p><?php esc_html_e( 'No preview available.', 'custom-site-logo' ); ?></p>
		</div>
		<?php
	}
}

// Initialize class.
new Custom_Site_Logo_Admin_Settings();
