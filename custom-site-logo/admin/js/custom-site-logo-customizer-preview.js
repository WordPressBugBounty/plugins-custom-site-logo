/**
 * Live-updates the logo preview in the Customizer preview pane as settings change,
 * without requiring a full page reload.
 *
 * @package Custom_Site_Logo
 */

( function ( $, wp ) {
	if ( ! wp || ! wp.customize ) {
		return;
	}

	var OPTION = 'csl_CustomSiteLogo_option_name';

	wp.customize( OPTION + '[csl_CustomSiteLogo_image_field]', function ( value ) {
		value.bind(
			function ( newValue ) {
				if ( ! newValue ) {
					return;
				}
				$( '#csl-customsite-logo' ).attr( 'src', newValue );
				$( '.csl-error' ).hide();
				$( '.csl-logo-block' ).show();
			}
		);
	} );

	wp.customize( OPTION + '[csl_CustomSiteLogo_width_field]', function ( value ) {
		value.bind(
			function ( newValue ) {
				$( '#csl-customsite-logo' ).css( 'width', newValue ? newValue + 'px' : '' );
			}
		);
	} );

	wp.customize( OPTION + '[csl_CustomSiteLogo_height_field]', function ( value ) {
		value.bind(
			function ( newValue ) {
				$( '#csl-customsite-logo' ).css( 'height', newValue ? newValue + 'px' : '' );
			}
		);
	} );

	wp.customize( OPTION + '[csl_CustomSiteLogo_image_center_field]', function ( value ) {
		value.bind(
			function ( newValue ) {
				$( '.csl-logo-block' ).css( 'text-align', parseInt( newValue, 10 ) === 1 ? 'center' : '' );
			}
		);
	} );

	wp.customize( OPTION + '[csl_CustomSiteLogo_custom_url_field]', function ( value ) {
		value.bind(
			function ( newValue ) {
				$( '#csl-logo-block-link' ).attr( 'href', newValue || '#' );
			}
		);
	} );

} )( jQuery, window.wp );
