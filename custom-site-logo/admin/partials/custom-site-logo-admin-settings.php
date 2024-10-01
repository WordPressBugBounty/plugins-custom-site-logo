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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
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
     * Register the JavaScript for the admin area.
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
        // register a new section in the "wporg" page
         add_settings_section(
             'csl_section_developers',
             __( 'Custom Site Logo Settings', 'custom-site-logo' ),  /* WordPress Function To Translate String  */
             array( $this, 'csl_section_developers_function' ),
             'custom-site-logo'
         );
    }

    /**
     * Register the setting section for this plugin.
     *
     * @since    1.0.0
     */
    function csl_section_developers_function( $args ){
    ?>  <!-- Setting Section -->
        <!-- Use Details -->
        <div class="csl-CustomSiteLogo-notice-block">
            <?php 
                _e('Dear user, kindly paste csl_CustomSiteLogo_show_logo(); function in your template where you want to display the logo.<br><br>','custom-site-logo');
             ?>

            <?php 
                _e('Dear user, kindly paste shortcode [csl_display_logo] in your post, page or any editor where you want to display the logo.','custom-site-logo');
             ?>
        </div>
    <?php
    }

    /**
     * Register the setting fields for this plugin.
     *
     * @since    1.0.0
     */
    public function csl_section_register_settings(){
        /* Logo Image Upload Field */
     add_settings_field(
         'csl_CustomSiteLogo_image_field', 
         __( 'Upload Image', 'custom-site-logo' ),  
         array( $this, 'csl_image_field_callback_function' ),
         'custom-site-logo',
         'csl_section_developers',
         [
            'label_for' => 'csl_CustomSiteLogo_image_field',
            'class' => 'csl_CustomSiteLogo_row_image',
         ]
     );

     /* Logo Hover Effect Field */
     add_settings_field(
         'csl_CustomSiteLogo_hover_effect_field', 
         __( 'Choose Hover Effect', 'custom-site-logo' ),  
         array( $this, 'csl_hover_effecr_field_callback_function' ),
         'custom-site-logo',
         'csl_section_developers',
         [
            'label_for' => 'csl_CustomSiteLogo_hover_effect_field',
            'class' => 'csl_CustomSiteLogo_row_custom_url',
         ]
     );

     /* Logo Width Field */
     add_settings_field(
         'csl_CustomSiteLogo_width_field', 
         __( 'Logo Width', 'custom-site-logo' ),  
         array( $this, 'csl_width_field_callback_function' ),
         'custom-site-logo',
         'csl_section_developers',
         [
            'label_for' => 'csl_CustomSiteLogo_width_field',
            'class' => 'csl_CustomSiteLogo_row_width_field',
         ]
     );

     /* Logo Height Field */
     add_settings_field(
         'csl_CustomSiteLogo_height_field', 
         __( 'Logo Height', 'custom-site-logo' ),  
         array( $this, 'csl_height_field_callback_function' ),
         'custom-site-logo',
         'csl_section_developers',
         [
            'label_for' => 'csl_CustomSiteLogo_height_field',
            'class' => 'csl_CustomSiteLogo_row_height_field',
         ]
     );

     /* Logo Image Center Field */
     add_settings_field(
         'csl_CustomSiteLogo_image_center_field', 
         __( 'Center Logo', 'custom-site-logo' ),  
         array( $this, 'csl_image_center_field_callback_function' ),
         'custom-site-logo',
         'csl_section_developers',
         [
            'label_for' => 'csl_CustomSiteLogo_image_center_field',
            'class' => 'csl_CustomSiteLogo_row_image_center',
         ]
     );

     /* Logo Image Responsive Field */
     add_settings_field(
         'csl_CustomSiteLogo_image_responsive_field', 
         __( 'Make Logo Responsive', 'custom-site-logo' ),  
         array( $this, 'csl_image_responsive_field_callback_function' ),
         'custom-site-logo',
         'csl_section_developers',
         [
            'label_for' => 'csl_CustomSiteLogo_image_responsive_field',
            'class' => 'csl_CustomSiteLogo_row_image_responsive',
         ]
     );

     /* Logo Custom URL Link Responsive Field */
     add_settings_field(
         'csl_CustomSiteLogo_custom_url_field', 
         __( 'Link logo to custom URL', 'custom-site-logo' ),  
         array( $this, 'csl_custom_url_field_callback_function' ),
         'custom-site-logo',
         'csl_section_developers',
         [
            'label_for' => 'csl_CustomSiteLogo_custom_url_field',
            'class' => 'csl_CustomSiteLogo_row_custom_url',
         ]
     );
    }

    /**
     * Register the setting fields for this plugin.
     *
     * @since    1.0.0
     */
    public function csl_section_developers( $args ){
    ?>  <!-- Setting Section -->
        <!-- Use Details -->
        <div class="csl-CustomSiteLogo-notice-block">
            <?php 
                _e('Dear user, kindly paste csl_CustomSiteLogo_show_logo(); function in your template where you want to display the logo.<br><br>','custom-site-logo');
             ?>

            <?php 
                _e('Dear user, kindly paste shortcode [csl_display_logo] in your post, page or any editor where you want to display the logo.','custom-site-logo');
             ?>
        </div>
    <?php
    }

    /**
     * Register the image setting field.
     *
     * @since    1.0.0
     * @var      string    $args    Contains arguments.
     */
    public function csl_image_field_callback_function( $args ) {
        $csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
    ?><p>
            <input id="csl_CustomSiteLogo_image_button" type="button" value="Media Library" class="button-secondary" />
            <input id="csl_CustomSiteLogo_logo_image" class="regular-text code" type="text" 
            name="csl_CustomSiteLogo_option_name[<?php echo esc_attr($args['label_for']); ?>]" 
            value="<?php echo !empty($csl_options[esc_attr($args['label_for'])]) ?( esc_attr($csl_options[$args['label_for']]) ):(_e('Select Logo','custom-site-logo')) ;?>">
        </p>
        <p class="description"><?php _e('Enter an image URL or use an image from media library.','custom-site-logo'); ?></p>

    <?php       
    }

    /**
     * Register the width setting field.
     *
     * @since    1.0.0
     * @var      string    $args    Contains arguments.
     */
    public function csl_width_field_callback_function( $args ){
        $csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
    ?>
        <input id="csl_CustomSiteLogo_logo_width" class="regular-text code" type="number" 
        name="csl_CustomSiteLogo_option_name[<?php echo esc_attr($args['label_for']); ?>]" 
        value="<?php echo !empty($csl_options[esc_attr($args['label_for'])]) ?( esc_attr($csl_options[$args['label_for']]) ):('') ;?>">
        <p class="description">
            <?php _e('Put width in px. If you want default logo width with just leave it empty.','custom-site-logo'); ?>
        </p>
    <?php
    }

    /**
     * Register the height setting field.
     *
     * @since    1.0.0
     * @var      string    $args    Contains arguments.
     */
    public function csl_height_field_callback_function( $args ){
        $csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
    ?>
        <input id="csl_CustomSiteLogo_logo_width" class="regular-text code" type="number" 
        name="csl_CustomSiteLogo_option_name[<?php echo esc_attr($args['label_for']); ?>]" 
        value="<?php echo !empty($csl_options[esc_attr($args['label_for'])]) ?( esc_attr($csl_options[$args['label_for']]) ):('') ;?>">
        <p class="description">
            <?php _e('Put height in px. If you want default logo height with just leave it empty.','custom-site-logo'); ?>
        </p>
    <?php
    }

    /**
     * Register the center-image setting field.
     *
     * @since    1.0.0
     * @var      string    $args    Contains arguments.
     */
    public function csl_image_center_field_callback_function( $args ){
        $csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
        $center_logo_option = isset( $csl_options[esc_attr($args['label_for'])] ) ? $csl_options[esc_attr($args['label_for'])] : 0; 
    ?>
        <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" 
        name="csl_CustomSiteLogo_option_name[<?php echo esc_attr($args['label_for']); ?>]" 
        value="1" <?php checked($center_logo_option, 1); ?>  />

        <label for="csl_CustomSiteLogo_option_name[<?php echo esc_attr($args['label_for']); ?>]">
            <?php _e('Please check if you want to center the logo','custom-site-logo'); ?>
        </label>
        <p class="description">
            <?php _e('Check this option to center the logo.','custom-site-logo'); ?>
        </p>
    <?php
    }

   /**
     * Register the responsive-image setting field.
     *
     * @since    1.0.0
     * @var      string    $args    Contains arguments.
     */
    public function csl_image_responsive_field_callback_function( $args ){
        $csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
        $responsive_logo_option = isset( $csl_options[esc_attr($args['label_for'])] ) ? $csl_options[esc_attr($args['label_for'])] : 0; 
    ?>
        
        <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>"  name="csl_CustomSiteLogo_option_name[<?php echo esc_attr($args['label_for']); ?>]" 
        value="1" <?php checked($responsive_logo_option, 1); ?> />
        <label for="csl_CustomSiteLogo_option_name[<?php echo esc_attr($args['label_for']); ?>]">
            <?php _e('Please check if you want to make logo responsive','custom-site-logo'); ?>
        </label>
        <p class="description">
            <?php _e('Check this option for responsive logo.', 'custom-site-logo'); ?>
        </p>
    <?php
    }


    /**
     * Register the custom url setting field.
     *
     * @since    1.0.0
     * @var      string    $args    Contains arguments.
     */
    public function csl_custom_url_field_callback_function( $args ){
        $csl_options = get_option( 'csl_CustomSiteLogo_option_name' );
    ?>
        <input id="csl_CustomSiteLogo_custom_url_responsive_field" class="regular-text code" type="text" 
            name="csl_CustomSiteLogo_option_name[<?php echo esc_attr($args['label_for']); ?>]" 
             placeholder="<?php _e('Put custom link here.','custom-site-logo'); ?>"  value="<?php echo !empty($csl_options[esc_attr($args['label_for'])]) ?( esc_attr($csl_options[$args['label_for']]) ): '' ;?>">
        
        <p class="description">
            <?php _e('Put any custom URL.','custom-site-logo'); ?>
        </p>
        <?php
    }

    /**
     * Register the hover effects setting field.
     *
     * @since    1.0.0
     * @var      string    $args    Contains arguments.
     */
    public function csl_hover_effecr_field_callback_function( $args ){
        $csl_options = get_option( 'csl_CustomSiteLogo_option_name' );

        if ( empty( $csl_options[$args['label_for'] ] ) ) {
            $csl_options[$args['label_for'] ] = 'none';
        }
    ?>
        <p>
            <select id="csl_CustomSiteLogo_hover_effect" name="csl_CustomSiteLogo_option_name[<?php echo esc_attr($args['label_for']); ?>]">
                <option value="none" <?php selected($csl_options[$args['label_for']], 'none'); ?>>None</option>
                <option value="hvr-grow" <?php selected($csl_options[$args['label_for']], 'hvr-grow'); ?>>Grow</option>
                <option value="hvr-shrink" <?php selected($csl_options[$args['label_for']], 'hvr-shrink'); ?>>Shrink</option>
                <option value="hvr-push" <?php selected($csl_options[$args['label_for']], 'hvr-push'); ?>>Push</option>
                <option value="hvr-pop" <?php selected($csl_options[$args['label_for']], 'hvr-pop'); ?>>Pop</option>
                <option value="hvr-rotate" <?php selected($csl_options[$args['label_for']], 'hvr-rotate'); ?>>Rotate</option>
                <option value="hvr-grow-rotate" <?php selected($csl_options[$args['label_for']], 'hvr-grow-rotate'); ?>>Grow Rotate</option>
                <option value="hvr-float" <?php selected($csl_options[$args['label_for']], 'hvr-float'); ?>>Float</option>
                <option value="hvr-sink" <?php selected($csl_options[$args['label_for']], 'hvr-sink'); ?>>Sink</option>
                <option value="hvr-skew" <?php selected($csl_options[$args['label_for']], 'hvr-skew'); ?>>Skew</option>
                <option value="hvr-skew-forward" <?php  selected($csl_options[$args['label_for']], 'hvr-skew-forward'); ?>>Skew Forward</option>
                <option value="hvr-skew-backward" <?php  selected($csl_options[$args['label_for']], 'hvr-skew-backward'); ?>>Skew Backward</option>
                <option value="hvr-wobble-horizontal" <?php  selected($csl_options[$args['label_for']], 'hvr-wobble-horizontal'); ?>>Wobble Horizontal</option>
                <option value="hvr-wobble-vertical" <?php  selected($csl_options[$args['label_for']], 'hvr-wobble-vertical'); ?>>Wobble Vertical</option>
                <option value="hvr-wobble-to-bottom-right" <?php  selected($csl_options[$args['label_for']], 'hvr-wobble-to-bottom-right'); ?>>Wobble to bottom right</option>
                <option value="hvr-wobble-to-top-right" <?php  selected($csl_options[$args['label_for']], 'hvr-wobble-to-top-right'); ?>>Wobble to top right</option>
                <option value="hvr-wobble-top" <?php  selected($csl_options[$args['label_for']], 'hvr-wobble-top'); ?>>Wobble Top</option>
                <option value="hvr-wobble-bottom" <?php  selected($csl_options[$args['label_for']], 'hvr-wobble-bottom'); ?>>Wobble Bottom</option>
                <option value="hvr-wobble-skew" <?php  selected($csl_options[$args['label_for']], 'hvr-wobble-skew'); ?>>Wobble Skew</option>
                <option value="hvr-buzz" <?php  selected($csl_options[$args['label_for']], 'hvr-buzz'); ?>>Buzz</option>
                <option value="hvr-buzz-out" <?php  selected($csl_options[$args['label_for']], 'hvr-buzz-out'); ?>>Buzz Out</option>
                            <option value="rotate-csl" <?php  selected($csl_options[$args['label_for']], 'rotate-csl'); ?>>Rotate</option>
            </select>
        </p>


    <div class="csl-preview-blocks" <?php  if(isset($csl_options['csl_CustomSiteLogo_image_field']) && ($csl_options['csl_CustomSiteLogo_image_field'] != 'Select Logo')){  }else{ echo 'style=display:none;'; } ?>>
        <p id="csl-margi-btm"><strong><?php _e('Check logo preview with hover effect if selected','custom-site-logo'); ?></strong></p>
        <?php if ( ! empty( $csl_options['csl_CustomSiteLogo_image_field'] ) ) { ?>
            <img id="csl_CustomSiteLogo_admin_hover_preview" class="<?php echo esc_attr($csl_options['csl_CustomSiteLogo_hover_effect_field']); ?>" src="<?php echo esc_attr($csl_options['csl_CustomSiteLogo_image_field']); ?>" alt="Logo" />
        <?php } ?>
        
        <p class="description"><?php _e('Select best hover effects','custom-site-logo'); ?></p>
    </div>

    <div class="csl-error-logo-url">
        <p><?php _e('No Preview Available','custom-site-logo'); ?></p>
    </div>
    <?php

    }


}


// Initialize class.
new Custom_Site_Logo_Admin_Settings();