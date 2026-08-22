<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://no-site.com
 * @since      1.0.0
 *
 * @package    Custom_Site_Logo
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/*
 * Option names are repeated as literals rather than read from the plugin's
 * classes, because uninstall.php runs standalone with none of them loaded.
 */
delete_option( 'csl_CustomSiteLogo_option_name' );
delete_option( 'csl_logo_click_stats' );
delete_option( 'csl_onboarding_dismissed' );

if ( is_multisite() ) {
	delete_site_option( 'csl_network_default_logo' );
}

/* Per-page logo overrides. */
$custom_site_logo_meta_keys = array(
	'_csl_logo_override_enabled',
	'_csl_logo_override_image',
	'_csl_logo_override_retina',
	'_csl_logo_override_dark',
	'_csl_logo_override_mobile',
);

foreach ( $custom_site_logo_meta_keys as $custom_site_logo_meta_key ) {
	delete_post_meta_by_key( $custom_site_logo_meta_key );
}
