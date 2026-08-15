/**
 * "Custom Site Logo" block.
 *
 * A dynamic block: the editor shows a live server-rendered preview and the
 * front end is rendered entirely by PHP (see Custom_Site_Logo_Block::render_block()),
 * so this file intentionally contains no markup-building logic of its own.
 *
 * @package Custom_Site_Logo
 */

( function ( blocks, element, serverSideRender, i18n ) {
	var el = element.createElement;
	var __ = i18n.__;
	var ServerSideRender = serverSideRender.default || serverSideRender;

	blocks.registerBlockType(
		'custom-site-logo/logo-block',
		{
			title: __( 'Custom Site Logo', 'custom-site-logo' ),
			description: __( 'Displays the logo configured in Appearance » Custom Site Logo.', 'custom-site-logo' ),
			icon: 'format-image',
			category: 'widgets',
			supports: {
				html: false
			},
			edit: function () {
				return el(
					'div',
					{ className: 'csl-block-editor-preview' },
					el( ServerSideRender, { block: 'custom-site-logo/logo-block' } )
				);
			},
			save: function () {
				// Server-side rendered block; nothing to save on the client.
				return null;
			}
		}
	);
} )( window.wp.blocks, window.wp.element, window.wp.serverSideRender, window.wp.i18n );
