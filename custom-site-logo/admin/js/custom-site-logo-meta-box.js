/**
 * Admin-facing JavaScript for the per-page/post logo override meta box.
 *
 * @package Custom_Site_Logo
 */

jQuery( document ).ready(
	function ($) {

		$( '#csl_logo_override_button' ).click(
			function (e) {
				e.preventDefault();
				var uploader = wp.media(
					{
						title: 'Select or upload a logo',
						button: { text: 'Select Logo' },
						multiple: false
					}
				).on(
					'select',
					function () {
						var attachment = uploader.state().get( 'selection' ).first().toJSON();
						$( '#csl_logo_override_image' ).val( attachment.url );
						$( '#csl_logo_override_preview' ).attr( 'src', attachment.url ).show();
						$( '#csl_logo_override_enabled' ).prop( 'checked', true );
					}
				).open();
			}
		);

	}
);
