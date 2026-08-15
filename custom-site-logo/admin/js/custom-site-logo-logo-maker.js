/**
 * Logo Maker: draws a live text-logo preview on a <canvas> and, on request,
 * uploads the result straight to the Media Library via the REST API and
 * sets it as the site logo.
 *
 * @package Custom_Site_Logo
 */

jQuery( document ).ready(
	function ($) {

		var $canvas = $( '#csl_logo_maker_canvas' );
		if ( ! $canvas.length || ! $canvas[0].getContext ) {
			return;
		}

		var canvas  = $canvas[0];
		var ctx     = canvas.getContext( '2d' );
		var maxTextWidth = canvas.width - 80; /* Padding on each side. */

		var $text        = $( '#csl_logo_maker_text' );
		var $font        = $( '#csl_logo_maker_font' );
		var $size        = $( '#csl_logo_maker_size' );
		var $textColor   = $( '#csl_logo_maker_text_color' );
		var $bgColor     = $( '#csl_logo_maker_bg_color' );
		var $transparent = $( '#csl_logo_maker_transparent' );
		var $generate    = $( '#csl_logo_maker_generate' );
		var $status      = $( '#csl_logo_maker_status' );

		/**
		 * Draw the current settings onto the canvas, shrinking the font
		 * size as needed so the text always fits within the canvas.
		 */
		function drawLogo() {
			var text        = $.trim( $text.val() ) || ( window.cslLogoMaker ? window.cslLogoMaker.defaultText : '' ) || 'Your Logo';
			var fontFamily  = $font.val();
			var fontSize    = parseInt( $size.val(), 10 ) || 64;
			var textColor   = $textColor.val();
			var bgColor     = $bgColor.val();
			var transparent = $transparent.is( ':checked' );

			ctx.clearRect( 0, 0, canvas.width, canvas.height );

			if ( ! transparent ) {
				ctx.fillStyle = bgColor;
				ctx.fillRect( 0, 0, canvas.width, canvas.height );
			}

			ctx.textAlign    = 'center';
			ctx.textBaseline = 'middle';

			var size = fontSize;
			do {
				ctx.font = 'bold ' + size + 'px ' + fontFamily;
				size -= 2;
			} while ( ctx.measureText( text ).width > maxTextWidth && size > 12 );

			ctx.fillStyle = textColor;
			ctx.fillText( text, canvas.width / 2, canvas.height / 2 );
		}

		/**
		 * Make sure the chosen (possibly web) font is actually loaded before
		 * drawing, otherwise the canvas may silently fall back to a default font.
		 */
		function redraw() {
			var fontFamily = $font.val();
			var fontSize   = parseInt( $size.val(), 10 ) || 64;

			if ( document.fonts && document.fonts.load ) {
				document.fonts.load( 'bold ' + fontSize + 'px ' + fontFamily ).then( drawLogo, drawLogo );
			} else {
				drawLogo();
			}
		}

		$( '#csl_logo_maker_text, #csl_logo_maker_font, #csl_logo_maker_size, #csl_logo_maker_text_color, #csl_logo_maker_bg_color, #csl_logo_maker_transparent' ).on(
			'input change',
			redraw
		);

		/* Google Fonts load asynchronously; redraw once they're ready so the preview doesn't stay on the fallback font. */
		if ( document.fonts && document.fonts.ready ) {
			document.fonts.ready.then( redraw );
		}

		redraw();

		$generate.on(
			'click',
			function (e) {
				e.preventDefault();

				var strings = ( window.cslLogoMaker && window.cslLogoMaker.i18n ) || {};

				if ( ! $.trim( $text.val() ) ) {
					$status.text( strings.noText || 'Please enter some text for your logo first.' ).addClass( 'csl-logo-maker-error' );
					return;
				}

				if ( ! window.cslLogoMaker || ! window.cslLogoMaker.restUrl ) {
					return;
				}

				$generate.prop( 'disabled', true );
				$status.removeClass( 'csl-logo-maker-error' ).text( strings.uploading || 'Generating and uploading your logo…' );

				canvas.toBlob(
					function (blob) {
						if ( ! blob ) {
							$status.text( strings.error || 'Something went wrong.' ).addClass( 'csl-logo-maker-error' );
							$generate.prop( 'disabled', false );
							return;
						}

						var filename = ( $.trim( $text.val() ) || 'custom-site-logo' )
							.toLowerCase()
							.replace( /[^a-z0-9]+/g, '-' )
							.replace( /(^-|-$)/g, '' ) || 'custom-site-logo';

						$.ajax(
							{
								url: window.cslLogoMaker.restUrl,
								method: 'POST',
								contentType: 'image/png',
								processData: false,
								data: blob,
								headers: {
									'X-WP-Nonce': window.cslLogoMaker.nonce,
									'Content-Disposition': 'attachment; filename="' + filename + '.png"'
								}
							}
						).done(
							function (attachment) {
								var url = attachment && ( attachment.source_url || ( attachment.media_details && attachment.media_details.sizes && attachment.media_details.sizes.full && attachment.media_details.sizes.full.source_url ) );

								if ( ! url ) {
									$status.text( strings.error || 'Something went wrong.' ).addClass( 'csl-logo-maker-error' );
									return;
								}

								$( '#csl_CustomSiteLogo_logo_image' ).val( url ).trigger( 'change' );
								$( '#csl_CustomSiteLogo_logo_image_thumb' ).empty().append( $( '<img>' ).attr( 'src', url ).attr( 'alt', '' ) );
								$( '#csl_CustomSiteLogo_logo_image_thumb' ).closest( '.csl-image-field' ).find( '.csl-remove-image-button' ).show();

								$status.removeClass( 'csl-logo-maker-error' ).text( strings.success || 'Logo created and set!' );
							}
						).fail(
							function () {
								$status.text( strings.error || 'Something went wrong while uploading the generated logo. Please try again.' ).addClass( 'csl-logo-maker-error' );
							}
						).always(
							function () {
								$generate.prop( 'disabled', false );
							}
						);
					},
					'image/png'
				);
			}
		);

	}
);
