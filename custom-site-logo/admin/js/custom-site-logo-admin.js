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
		var $csl_tabs           = $( '.csl-tab-nav-item' );
		var $csl_tab_panels     = $( '.csl-tab-content' );
		var $csl_save_button    = $( '.csl-save-button-wrap' );
		var csl_tab_storage_key = 'csl_active_settings_tab';

		function csl_activate_tab( tabId ) {
			if ( ! tabId || ! $csl_tab_panels.filter( '[data-tab="' + tabId + '"]' ).length ) {
				tabId = $csl_tabs.first().data( 'tab' );
			}

			$csl_tabs.removeClass( 'is-active' ).attr( 'aria-selected', 'false' );
			$csl_tabs.filter( '[data-tab="' + tabId + '"]' ).addClass( 'is-active' ).attr( 'aria-selected', 'true' );

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
		 * Open the media library and hand the chosen attachment to a callback.
		 */
		function csl_open_media( title, onSelect ) {
			var uploader = wp.media(
				{
					title: title,
					button: { text: 'Select Image' },
					multiple: false
				}
			).on(
				'select',
				function () {
					onSelect( uploader.state().get( 'selection' ).first().toJSON() );
				}
			).open();
		}

		/*
		 * Delegated so that every image field works, including the ones added by
		 * the newer settings tabs and any repeater row cloned after page load.
		 */
		$( document ).on(
			'click',
			'.csl-image-field .csl-media-button',
			function (e) {
				e.preventDefault();
				var $wrap = $( this ).closest( '.csl-image-field' );

				csl_open_media(
					'Select or upload an image',
					function ( attachment ) {
						$wrap.find( 'input[type="text"]' ).val( attachment.url ).trigger( 'change' );
						csl_update_thumb( $wrap.find( '.csl-image-thumb' ), attachment.url );
						$wrap.find( '.csl-remove-image-button' ).show();
					}
				);
			}
		);

		$( document ).on(
			'click',
			'.csl-remove-image-button',
			function (e) {
				e.preventDefault();
				var $button = $( this );
				var $wrap   = $button.closest( '.csl-image-field' );

				$wrap.find( 'input[type="text"]' ).val( '' ).trigger( 'change' );
				csl_update_thumb( $wrap.find( '.csl-image-thumb' ), '' );
				$button.hide();
			}
		);

		/* ---------------------------------------------------------------
		 * Repeatable rows (scheduled, conditional, per-language and
		 * rotating logos).
		 * ------------------------------------------------------------- */

		$( document ).on(
			'click',
			'.csl-repeater-add',
			function (e) {
				e.preventDefault();

				var $repeater = $( this ).closest( '.csl-repeater' );
				var $rows     = $repeater.find( '.csl-repeater-rows' );
				var template  = $repeater.find( '.csl-repeater-template' ).html();

				if ( ! template ) {
					return;
				}

				/*
				 * Rows are indexed by position in the submitted array, so a fresh
				 * index only has to avoid colliding with the rows already present.
				 */
				var nextIndex = $rows.children( '.csl-repeater-row' ).length;
				while ( $rows.find( '[name*="[' + nextIndex + ']["]' ).length ) {
					nextIndex++;
				}

				$rows.append( template.replace( /__INDEX__/g, nextIndex ) );
			}
		);

		$( document ).on(
			'click',
			'.csl-repeater-remove',
			function (e) {
				e.preventDefault();
				$( this ).closest( '.csl-repeater-row' ).remove();
			}
		);

		$( document ).on(
			'click',
			'.csl-repeater-media',
			function (e) {
				e.preventDefault();
				var $input = $( this ).closest( '.csl-repeater-image' ).find( '.csl-repeater-image-input' );

				csl_open_media(
					'Select or upload an image',
					function ( attachment ) {
						$input.val( attachment.url ).trigger( 'change' );
					}
				);
			}
		);

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

	}
);
