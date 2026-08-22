/**
 * Public-facing JavaScript for the Custom Site Logo plugin.
 *
 * Powers two optional features: the sticky logo, which shrinks (and can swap
 * artwork) once the visitor scrolls past a configurable offset, and logo click
 * tracking.
 *
 * @package Custom_Site_Logo
 */

( function () {
	'use strict';

	var settings = window.cslPublic || {};

	/**
	 * Shrink and optionally swap each sticky logo as the page scrolls.
	 */
	function initStickyLogos() {
		var blocks = document.querySelectorAll( '.csl-logo-block--sticky' );

		if ( ! blocks.length ) {
			return;
		}

		var logos = [];

		Array.prototype.forEach.call(
			blocks,
			function ( block ) {
				var image = block.querySelector( '.csl-customsite-logo' );

				if ( ! image ) {
					return;
				}

				logos.push(
					{
						block: block,
						image: image,
						offset: parseInt( block.getAttribute( 'data-csl-sticky-offset' ), 10 ) || 0,
						scale: ( parseInt( block.getAttribute( 'data-csl-sticky-scale' ), 10 ) || 70 ) / 100,
						stickyImage: block.getAttribute( 'data-csl-sticky-image' ) || '',
						defaultImage: image.getAttribute( 'data-csl-default-image' ) || image.getAttribute( 'src' ),
						isStuck: false
					}
				);
			}
		);

		if ( ! logos.length ) {
			return;
		}

		var ticking = false;

		function update() {
			var scrolled = window.pageYOffset || document.documentElement.scrollTop;

			logos.forEach(
				function ( logo ) {
					var shouldStick = scrolled > logo.offset;

					if ( shouldStick === logo.isStuck ) {
						return;
					}

					logo.isStuck = shouldStick;
					logo.block.classList.toggle( 'is-csl-stuck', shouldStick );

					/*
					 * Scaling with a transform keeps the header from reflowing on
					 * every scroll, which is what makes this cheap enough to run
					 * against the scroll event.
					 */
					logo.image.style.transform = shouldStick ? 'scale(' + logo.scale + ')' : '';

					if ( logo.stickyImage ) {
						logo.image.setAttribute( 'src', shouldStick ? logo.stickyImage : logo.defaultImage );
					}
				}
			);

			ticking = false;
		}

		function onScroll() {
			if ( ticking ) {
				return;
			}

			ticking = true;
			window.requestAnimationFrame( update );
		}

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		update();
	}

	/**
	 * Report a logo click to Google Analytics (when present) and to the
	 * plugin's own counter.
	 */
	function initClickTracking() {
		if ( ! settings.trackClicks || ! settings.clickEndpoint ) {
			return;
		}

		document.addEventListener(
			'click',
			function ( event ) {
				var logo = event.target.closest ? event.target.closest( '[data-csl-track]' ) : null;

				if ( ! logo ) {
					return;
				}

				if ( 'function' === typeof window.gtag ) {
					window.gtag(
						'event',
						'site_logo_click',
						{
							event_category: 'Custom Site Logo',
							event_label: logo.getAttribute( 'alt' ) || ''
						}
					);
				}

				/*
				 * keepalive lets the request survive the navigation that a logo
				 * click almost always triggers.
				 */
				if ( window.fetch ) {
					window.fetch(
						settings.clickEndpoint,
						{
							method: 'POST',
							keepalive: true,
							headers: { 'Content-Type': 'application/json' },
							body: '{}'
						}
					).catch(
						function () {
							/* A dropped analytics ping must never break the click itself. */
						}
					);
				}
			},
			true
		);
	}

	function init() {
		initStickyLogos();
		initClickTracking();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
