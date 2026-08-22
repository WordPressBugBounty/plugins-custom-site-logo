/**
 * Admin-facing JavaScript for the per-page/post logo override meta box.
 *
 * @package Custom_Site_Logo
 */

jQuery( document ).ready(
	function ($) {

		/**
		 * Open the media library and hand the chosen attachment to a callback.
		 */
		function csl_open_media( onSelect ) {
			var uploader = wp.media(
				{
					title: 'Select or upload a logo',
					button: { text: 'Select Logo' },
					multiple: false
				}
			).on(
				'select',
				function () {
					onSelect( uploader.state().get( 'selection' ).first().toJSON() );
				}
			).open();
		}

		$( '#csl_logo_override_button' ).on(
			'click',
			function (e) {
				e.preventDefault();

				csl_open_media(
					function ( attachment ) {
						$( '#csl_logo_override_image' ).val( attachment.url );
						$( '#csl_logo_override_preview' ).attr( 'src', attachment.url ).show();
						$( '#csl_logo_override_enabled' ).prop( 'checked', true );
					}
				);
			}
		);

		$( document ).on(
			'click',
			'.csl-override-variant-button',
			function (e) {
				e.preventDefault();
				var $input = $( this ).closest( 'p' ).find( '.csl-override-variant' );

				csl_open_media(
					function ( attachment ) {
						$input.val( attachment.url );
					}
				);
			}
		);

	}
);
