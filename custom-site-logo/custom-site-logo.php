<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://no-site.com
 * @since             1.0.0
 * @package           Custom_Site_Logo
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Site Logo
 * Plugin URI:        https://no-site.com
 * Description:       This is a plugin to show the custom logo in the site header or anywhere.
 * Version:           1.0.1
 * Author:            Awais Altaf
 * Author URI:        https://no-site.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       custom-site-logo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CUSTOM_SITE_LOGO_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-custom-site-logo-activator.php
 */
function activate_custom_site_logo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-site-logo-activator.php';
	Custom_Site_Logo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-custom-site-logo-deactivator.php
 */
function deactivate_custom_site_logo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-site-logo-deactivator.php';
	Custom_Site_Logo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_site_logo' );
register_deactivation_hook( __FILE__, 'deactivate_custom_site_logo' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-custom-site-logo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_custom_site_logo() {

	$plugin = new Custom_Site_Logo();
	$plugin->run();

}
run_custom_site_logo();
