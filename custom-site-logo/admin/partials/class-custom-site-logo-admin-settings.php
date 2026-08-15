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
		register_setting( 'custom-site-logo', 'csl_CustomSiteLogo_option_name' );

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
			'csl_section_general'  => __( 'General', 'custom-site-logo' ),
			'csl_section_size'     => __( 'Size & Layout', 'custom-site-logo' ),
			'csl_section_effects'  => __( 'Hover Effect', 'custom-site-logo' ),
			'csl_section_advanced' => __( 'Advanced Logos', 'custom-site-logo' ),
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
	 * @param    array $field {
	 *     Field arguments.
	 *
	 *     @type string $id          The base id used for the input, button, and thumbnail elements.
	 *     @type string $name        The option key (used to build the field's `name` attribute).
	 *     @type string $value       The field's current value.
	 *     @type string $placeholder Optional. Placeholder text for the input.
	 *     @type string $description Optional. Help text shown below the field.
	 * }.
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
					<button type="button" id="<?php echo esc_attr( $field['id'] ); ?>_button" class="button button-secondary">
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
