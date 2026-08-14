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
	 * Register the setting section for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function csl_register_setting_section() {
		// Register a new section on the settings page.
		add_settings_section(
			'csl_section_developers',
			__( 'Custom Site Logo Settings', 'custom-site-logo' ),
			array( $this, 'csl_section_developers_function' ),
			'custom-site-logo'
		);
	}

	/**
	 * Render the introductory help text for the settings section.
	 *
	 * @since    1.0.0
	 * @param    array $args Arguments passed by add_settings_section(). Unused, kept for compatibility with the WordPress Settings API callback signature.
	 */
	public function csl_section_developers_function( $args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- Required by the add_settings_section() callback signature.
		?>
		<!-- Setting Section -->
		<div class="csl-CustomSiteLogo-notice-block">
			<p>
				<?php esc_html_e( 'To display the logo directly in your theme, add the following code where you want it to appear:', 'custom-site-logo' ); ?>
				<code>&lt;?php echo csl_CustomSiteLogo_show_logo(); ?&gt;</code>
			</p>
			<p>
				<?php esc_html_e( 'Alternatively, use the following shortcode in any post, page, or widget:', 'custom-site-logo' ); ?>
				<code>[csl_display_logo]</code>
			</p>
		</div>
		<?php
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
			'csl_section_developers',
			array(
				'label_for' => 'csl_CustomSiteLogo_image_field',
				'class'     => 'csl_CustomSiteLogo_row_image',
			)
		);

		/* Logo Hover Effect Field */
		add_settings_field(
			'csl_CustomSiteLogo_hover_effect_field',
			__( 'Hover Effect', 'custom-site-logo' ),
			array( $this, 'csl_hover_effecr_field_callback_function' ),
			'custom-site-logo',
			'csl_section_developers',
			array(
				'label_for' => 'csl_CustomSiteLogo_hover_effect_field',
				'class'     => 'csl_CustomSiteLogo_row_custom_url',
			)
		);

		/* Logo Width Field */
		add_settings_field(
			'csl_CustomSiteLogo_width_field',
			__( 'Logo Width', 'custom-site-logo' ),
			array( $this, 'csl_width_field_callback_function' ),
			'custom-site-logo',
			'csl_section_developers',
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
			'csl_section_developers',
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
			'csl_section_developers',
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
			'csl_section_developers',
			array(
				'label_for' => 'csl_CustomSiteLogo_image_responsive_field',
				'class'     => 'csl_CustomSiteLogo_row_image_responsive',
			)
		);

		/* Logo Custom URL Link Responsive Field */
		add_settings_field(
			'csl_CustomSiteLogo_custom_url_field',
			__( 'Custom Logo Link', 'custom-site-logo' ),
			array( $this, 'csl_custom_url_field_callback_function' ),
			'custom-site-logo',
			'csl_section_developers',
			array(
				'label_for' => 'csl_CustomSiteLogo_custom_url_field',
				'class'     => 'csl_CustomSiteLogo_row_custom_url',
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
		$field_value = ! empty( $csl_options[ $args['label_for'] ] ) ? $csl_options[ $args['label_for'] ] : '';
		?>
		<p>
			<input id="csl_CustomSiteLogo_image_button" type="button" value="<?php esc_attr_e( 'Media Library', 'custom-site-logo' ); ?>" class="button-secondary" />
			<input id="csl_CustomSiteLogo_logo_image" class="regular-text code" type="text"
			name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $args['label_for'] ); ?>]"
			value="<?php echo ! empty( $field_value ) ? esc_attr( $field_value ) : esc_attr__( 'Select Logo', 'custom-site-logo' ); ?>">
		</p>
		<p class="description"><?php esc_html_e( 'Enter an image URL, or select one from the media library.', 'custom-site-logo' ); ?></p>
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
		?>
		<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="1" <?php checked( $center_logo_option, 1 ); ?> />
		<label for="<?php echo esc_attr( $args['label_for'] ); ?>">
			<?php esc_html_e( 'Center the logo horizontally.', 'custom-site-logo' ); ?>
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
		?>
		<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="csl_CustomSiteLogo_option_name[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="1" <?php checked( $responsive_logo_option, 1 ); ?> />
		<label for="<?php echo esc_attr( $args['label_for'] ); ?>">
			<?php esc_html_e( 'Automatically scale the logo to fit its container on smaller screens.', 'custom-site-logo' ); ?>
		</label>
		<?php
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
