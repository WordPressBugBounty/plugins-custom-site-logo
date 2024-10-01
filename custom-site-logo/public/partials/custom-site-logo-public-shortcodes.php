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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'csl_shortcodes_init' ) );
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function csl_shortcodes_init() {
        add_shortcode( 'csl_display_logo', array( $this, 'csl_show_logo' ) );
    }

     /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function csl_show_logo() {
        /* Get Options */
        $get_csl_options  = get_option('csl_CustomSiteLogo_option_name');

        /* Assign Option Values */
        $csl_image_field = ( ! empty( $get_csl_options['csl_CustomSiteLogo_image_field'] ) && isset( $get_csl_options['csl_CustomSiteLogo_image_field'] ) ) ? $get_csl_options['csl_CustomSiteLogo_image_field'] : '';
        $csl_image_url = isset( $get_csl_options['csl_CustomSiteLogo_custom_url_field'] ) ? $get_csl_options['csl_CustomSiteLogo_custom_url_field'] : '';
        $csl_option_width = isset( $get_csl_options['csl_CustomSiteLogo_width_field'] ) ? $get_csl_options['csl_CustomSiteLogo_width_field'] : '';
        $csl_option_height = isset( $get_csl_options['csl_CustomSiteLogo_height_field'] ) ? $get_csl_options['csl_CustomSiteLogo_height_field'] : '';
        $csl_option_responsive = isset( $get_csl_options['csl_CustomSiteLogo_image_responsive_field'] ) ? $get_csl_options['csl_CustomSiteLogo_image_responsive_field'] : 0;
        $csl_option_hover = isset( $get_csl_options['csl_CustomSiteLogo_hover_effect_field'] ) ? $get_csl_options['csl_CustomSiteLogo_hover_effect_field'] : 'none';
        $csl_option_center = isset( $get_csl_options['csl_CustomSiteLogo_image_center_field'] ) ? $get_csl_options['csl_CustomSiteLogo_image_center_field'] : 0;

        if ( isset( $csl_image_field ) && !empty( $csl_image_field ) ) {
        ?>
            <div class="csl-logo-block" style="<?php if($csl_option_center == 1){ echo esc_attr(('text-align:center')); } ?>">
                <a id="csl-logo-block-link" href="<?php if(!empty($csl_image_url)){ echo esc_url($csl_image_url); }else{ echo esc_attr("#"); } ?>">
                    <img id="csl-customsite-logo" class="csl-customsite-logo <?php echo esc_attr($csl_option_hover); ?>" 
                    src="<?php echo esc_url($csl_image_field); ?>" style=" <?php if(!empty($csl_option_width)){ echo esc_attr('width:'.$csl_option_width.'px;'); } ?> 
                    <?php if(!empty($csl_option_height)){ echo esc_attr('height:'.$csl_option_height.'px;'); } ?>
                        <?php if($csl_option_responsive == 1){  echo esc_attr('width:100%;max-width:100%;height:auto;'); } ?>" />
                </a>
            </div><!-- csl-logo-block Ends -->
        <?php
            } else {
        ?>
            <div class="csl-error" style="text-align: center;">Error! No Logo Upload. Please upload logo : <strong>(Dashboard => Appearence => Custom Site Logo)</strong></div>
        <?php 
        }
    }

}

// Initialize class.
new Custom_Site_Logo_Public_Shortcodes();