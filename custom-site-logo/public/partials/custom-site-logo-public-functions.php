<?php
/**
 * Provide the direct template function for displaying the logo.
 *
 * This file defines the public helper function that theme developers can
 * call directly (e.g. from header.php) to render the configured logo.
 *
 * @link       https://no-site.com
 * @since      1.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/public/partials
 */

/**
 * Render the custom site logo markup.
 *
 * @since 1.0.0
 * @param  bool $do_echo Optional. Whether to echo the markup (for backwards compatibility with
 *                        `<?php csl_CustomSiteLogo_show_logo(); ?>`) or return it. Default true.
 * @return string|void The logo markup when `$do_echo` is false, otherwise void.
 */
function csl_CustomSiteLogo_show_logo( $do_echo = true ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid, WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Documented public template tag called directly from theme templates; renaming it would break every site already using it.
	$markup = Custom_Site_Logo_Renderer::render( array( 'post_id' => get_the_ID() ) );

	if ( ! $do_echo ) {
		return $markup;
	}

	echo $markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup is already escaped inside Custom_Site_Logo_Renderer::render().
}
