/**
 * Admin-facing JavaScript for the Custom Site Logo settings page.
 *
 * Handles the settings page tabs, the media library uploaders and their
 * thumbnail previews, and the hover effect preview.
 *
 * @package Custom_Site_Logo
 */

jQuery( document ).ready(
	function ($) {

		/* ---------------------------------------------------------------
		 * Settings page tabs.
		 * ------------------------------------------------------------- */
		var $csl_tabs           = $( '.csl-nav-tab-wrapper .nav-tab' );
		var $csl_tab_panels     = $( '.csl-tab-content' );
		var $csl_save_button    = $( '.csl-save-button-wrap' );
		var csl_tab_storage_key = 'csl_active_settings_tab';

		function csl_activate_tab( tabId ) {
			if ( ! tabId || ! $csl_tab_panels.filter( '[data-tab="' + tabId + '"]' ).length ) {
				tabId = $csl_tabs.first().data( 'tab' );
			}

			$csl_tabs.removeClass( 'nav-tab-active' );
			$csl_tabs.filter( '[data-tab="' + tabId + '"]' ).addClass( 'nav-tab-active' );

			$csl_tab_panels.removeClass( 'csl-tab-active' );
			$csl_tab_panels.filter( '[data-tab="' + tabId + '"]' ).addClass( 'csl-tab-active' );

			/* The main settings form's Save button doesn't apply to the Import/Export tab. */
			$csl_save_button.toggle( 'csl_import_export' !== tabId );

			try {
				window.localStorage.setItem( csl_tab_storage_key, tabId );
			} catch ( err ) {
				/* localStorage may be unavailable (e.g. private browsing); tabs still work without persistence. */
			}
		}

		if ( $csl_tabs.length ) {
			$csl_tabs.on(
				'click',
				function (e) {
					e.preventDefault();
					csl_activate_tab( $( this ).data( 'tab' ) );
				}
			);

			var csl_saved_tab = null;
			try {
				csl_saved_tab = window.localStorage.getItem( csl_tab_storage_key );
			} catch ( err ) {
				/* Ignore; falls back to the first tab. */
			}
			csl_activate_tab( csl_saved_tab );
		}

		/* ---------------------------------------------------------------
		 * Image fields: media library button + thumbnail preview + remove.
		 * ------------------------------------------------------------- */

		/**
		 * Swap a thumbnail box's contents between an <img> and a placeholder icon.
		 */
		function csl_update_thumb( $thumb, url ) {
			if ( ! $thumb || ! $thumb.length ) {
				return;
			}
			$thumb.empty();
			if ( url ) {
				$thumb.append( $( '<img>' ).attr( 'src', url ).attr( 'alt', '' ) );
			} else {
				$thumb.append( $( '<span>' ).addClass( 'dashicons dashicons-format-image' ) );
			}
		}

		/**
		 * Wire up one "Media Library" button + its text field + thumbnail + remove link.
		 */
		function csl_bind_image_field( buttonId, fieldId, thumbId, title ) {
			var $field = $( fieldId );
			var $thumb = $( thumbId );

			$( buttonId ).on(
				'click',
				function (e) {
					e.preventDefault();
					var uploader = wp.media(
						{
							title: title,
							button: { text: 'Select Image' },
							multiple: false
						}
					).on(
						'select',
						function () {
							var attachment = uploader.state().get( 'selection' ).first().toJSON();
							$field.val( attachment.url ).trigger( 'change' );
							csl_update_thumb( $thumb, attachment.url );
							$thumb.closest( '.csl-image-field' ).find( '.csl-remove-image-button' ).show();
						}
					).open();
				}
			);
		}

		$( '.csl-remove-image-button' ).on(
			'click',
			function (e) {
				e.preventDefault();
				var $button = $( this );
				var $wrap   = $button.closest( '.csl-image-field' );

				$wrap.find( 'input[type="text"]' ).val( '' ).trigger( 'change' );
				csl_update_thumb( $wrap.find( '.csl-image-thumb' ), '' );
				$button.hide();
			}
		);

		csl_bind_image_field( '#csl_CustomSiteLogo_logo_image_button', '#csl_CustomSiteLogo_logo_image', '#csl_CustomSiteLogo_logo_image_thumb', 'Select or upload a logo' );
		csl_bind_image_field( '#csl_CustomSiteLogo_retina_image_button', '#csl_CustomSiteLogo_retina_image', '#csl_CustomSiteLogo_retina_image_thumb', 'Select or upload a retina (@2x) logo' );
		csl_bind_image_field( '#csl_CustomSiteLogo_dark_image_button', '#csl_CustomSiteLogo_dark_image', '#csl_CustomSiteLogo_dark_image_thumb', 'Select or upload a dark mode logo' );
		csl_bind_image_field( '#csl_CustomSiteLogo_mobile_image_button', '#csl_CustomSiteLogo_mobile_image', '#csl_CustomSiteLogo_mobile_image_thumb', 'Select or upload a mobile logo' );

		/* Keep the main logo's hover-effect preview (on the "Hover Effect" tab) in sync too. */
		$( '#csl_CustomSiteLogo_logo_image' ).on(
			'change',
			function () {
				var url = $( this ).val();
				$( '#csl_CustomSiteLogo_admin_hover_preview' ).attr( 'src', url );
				if ( url ) {
					$( '.csl-preview-blocks' ).show();
					$( '.csl-error-logo-url' ).hide();
				}
			}
		);

		/* ---------------------------------------------------------------
		 * Hover effect preview + broken-image detection.
		 * ------------------------------------------------------------- */

		/* Testing the Logo Image onLoad */
		var csl_logo_url_val = $( '#csl_CustomSiteLogo_logo_image' ).val();
		csl_logo_testImage( csl_logo_url_val );

		function csl_logo_testImage(URL) {
			if (URL != "Select Logo" && URL) {
				var tester     = new Image();
				tester.onerror = csl_logo_imageNotFound;
				tester.src     = URL;
			}
		}

		function csl_logo_imageNotFound() {
			$( ".csl-preview-blocks" ).css( "display","none" );
			$( ".csl-error-logo-url" ).css( "display","block" );
			alert( "That image was not found." );
		}

		$( '#csl_CustomSiteLogo_hover_effect' ).on(
			'change',
			function () {
				var selectedHover = $( '#csl_CustomSiteLogo_hover_effect' ).val();
				$( '#csl_CustomSiteLogo_admin_hover_preview' ).removeClass();
				$( '#csl_CustomSiteLogo_admin_hover_preview' ).addClass( selectedHover );
			}
		);

		/* Check Image Field */
		$( '.csl_CustomSiteLogo_form' ).on(
			'submit',
			function () {
				var csl_CustomSiteLogo_logo_image = $( 'input#csl_CustomSiteLogo_logo_image' ).attr( 'value' ); // Getting the logo image value.

				if ((csl_CustomSiteLogo_logo_image === '' || csl_CustomSiteLogo_logo_image === null)) {
					alert( "Please select the img." );
					return false;
				}
			}
		);

	}
);
