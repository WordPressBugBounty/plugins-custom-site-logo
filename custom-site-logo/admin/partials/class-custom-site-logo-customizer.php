<?php
/**
 * Customizer integration for the plugin.
 *
 * Adds an "Custom Site Logo" section to Appearance » Customize, wired to
 * the very same `csl_CustomSiteLogo_option_name` option used by the classic
 * settings page (using WordPress's native `option_name[key]` setting id
 * syntax), so both stay perfectly in sync. Where possible, changes are
 * reflected live in the preview pane without a full page reload.
 *
 * @link       https://no-site.com
 * @since      1.2.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */

/**
 * The Customizer integration class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */
class Custom_Site_Logo_Customizer {

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since    1.2.0
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'customize_preview_init', array( $this, 'enqueue_preview_script' ) );
	}

	/**
	 * Register the Customizer section, settings, and controls.
	 *
	 * @since    1.2.0
	 * @param    WP_Customize_Manager $wp_customize The Customizer manager instance.
	 */
	public function register( $wp_customize ) {
		$option = 'csl_CustomSiteLogo_option_name';

		$wp_customize->add_section(
			'csl_customizer_section',
			array(
				'title'       => __( 'Custom Site Logo', 'custom-site-logo' ),
				'description' => __( 'Configure the logo displayed via the [csl_display_logo] shortcode, the Custom Site Logo block, or the Custom Site Logo widget.', 'custom-site-logo' ),
				'priority'    => 120,
			)
		);

		$wp_customize->add_setting(
			"{$option}[csl_CustomSiteLogo_image_field]",
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'esc_url_raw',
				'default'           => '',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'csl_customizer_image_control',
				array(
					'label'    => __( 'Logo Image', 'custom-site-logo' ),
					'section'  => 'csl_customizer_section',
					'settings' => "{$option}[csl_CustomSiteLogo_image_field]",
				)
			)
		);

		$wp_customize->add_setting(
			"{$option}[csl_CustomSiteLogo_width_field]",
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'           => '',
			)
		);
		$wp_customize->add_control(
			"{$option}[csl_CustomSiteLogo_width_field]",
			array(
				'type'     => 'number',
				'label'    => __( 'Logo Width (px)', 'custom-site-logo' ),
				'section'  => 'csl_customizer_section',
				'settings' => "{$option}[csl_CustomSiteLogo_width_field]",
			)
		);

		$wp_customize->add_setting(
			"{$option}[csl_CustomSiteLogo_height_field]",
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'           => '',
			)
		);
		$wp_customize->add_control(
			"{$option}[csl_CustomSiteLogo_height_field]",
			array(
				'type'     => 'number',
				'label'    => __( 'Logo Height (px)', 'custom-site-logo' ),
				'section'  => 'csl_customizer_section',
				'settings' => "{$option}[csl_CustomSiteLogo_height_field]",
			)
		);

		$wp_customize->add_setting(
			"{$option}[csl_CustomSiteLogo_image_center_field]",
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'           => 0,
			)
		);
		$wp_customize->add_control(
			"{$option}[csl_CustomSiteLogo_image_center_field]",
			array(
				'type'     => 'checkbox',
				'label'    => __( 'Center Logo', 'custom-site-logo' ),
				'section'  => 'csl_customizer_section',
				'settings' => "{$option}[csl_CustomSiteLogo_image_center_field]",
			)
		);

		$wp_customize->add_setting(
			"{$option}[csl_CustomSiteLogo_custom_url_field]",
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'esc_url_raw',
				'default'           => '',
			)
		);
		$wp_customize->add_control(
			"{$option}[csl_CustomSiteLogo_custom_url_field]",
			array(
				'type'     => 'url',
				'label'    => __( 'Custom Logo Link', 'custom-site-logo' ),
				'section'  => 'csl_customizer_section',
				'settings' => "{$option}[csl_CustomSiteLogo_custom_url_field]",
			)
		);

		$wp_customize->add_setting(
			"{$option}[csl_CustomSiteLogo_dark_image_field]",
			array(
				'type'              => 'option',
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url_raw',
				'default'           => '',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'csl_customizer_dark_image_control',
				array(
					'label'    => __( 'Dark Mode Logo', 'custom-site-logo' ),
					'section'  => 'csl_customizer_section',
					'settings' => "{$option}[csl_CustomSiteLogo_dark_image_field]",
				)
			)
		);

		$wp_customize->add_setting(
			"{$option}[csl_CustomSiteLogo_mobile_image_field]",
			array(
				'type'              => 'option',
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url_raw',
				'default'           => '',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'csl_customizer_mobile_image_control',
				array(
					'label'    => __( 'Mobile Logo', 'custom-site-logo' ),
					'section'  => 'csl_customizer_section',
					'settings' => "{$option}[csl_CustomSiteLogo_mobile_image_field]",
				)
			)
		);
	}

	/**
	 * Enqueue the script that patches live changes into the preview pane.
	 *
	 * @since    1.2.0
	 */
	public function enqueue_preview_script() {
		wp_enqueue_script(
			'csl-customizer-preview',
			plugins_url( '../js/custom-site-logo-customizer-preview.js', __FILE__ ),
			array( 'jquery', 'customize-preview' ),
			defined( 'CUSTOM_SITE_LOGO_VERSION' ) ? CUSTOM_SITE_LOGO_VERSION : '1.2.0',
			true
		);
	}
}
