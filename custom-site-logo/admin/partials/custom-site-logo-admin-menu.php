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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
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
        add_filter( 'plugin_action_links', array( $this, 'csl_add_action_plugin' ), 10, 5 );
    }

    public function csl_add_menu_page()
    {
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
    public function csl_submenu_callback_function(){
        /* Check user capability */
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        /* wordpress will add the "settings-updated" $_GET parameter to the url */
        if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
            add_settings_error( 'csl_success_messages', 'csl_success_messages', __( 'Logo Settings Saved Successfully', 'custom-site-logo' ), 'updated' );
    ?>
        <div id="message" class="updated">
        <p><strong><?php _e( 'Settings Saved Successfully.', 'custom-site-logo' ) ?></strong></p>
        </div>
    <?php   
        }
         
        ?>
      <div class="wrap">
        <form action="options.php" method="post" class="csl_CustomSiteLogo_form" >
        <!-- Display Settings Here -->
        <?php

             // output security fields for the registered setting "csl_custom_site_logo"
             settings_fields( 'custom-site-logo' );
             
             // output setting sections and their fields
             do_settings_sections( 'custom-site-logo' );
             
             // output save settings button
             submit_button( 'Save Settings' );

        ?>
       </form>
      </div><!-- wrap -->
      <?php
    }

    /**
     * Register the settings link in plugin page.
     *
     * @since    1.0.0
     * @param      string    $actions       The name of this action.
     * @param      string    $plugin_file   The name of this plugin file.
     */
    public function csl_add_action_plugin( $actions, $plugin_file ) 
    {
        static $plugin;
        
        if ( ! isset( $plugin ) ) {
                $plugin = 'custom-site-logo/custom-site-logo.php';

                if ($plugin == $plugin_file) {
                    $settings   = array( 
                        'settings'  => 
                            '<a href="admin.php?page=custom-site-logo">' . __( 'Settings', 'General' ) . '</a>' 
                    );
                    $actions = array_merge( $settings, $actions );
                }

            return $actions;
        }   
    }
}

// Initialize class.
new Custom_Site_Logo_Admin_Menu();