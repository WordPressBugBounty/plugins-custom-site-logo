/**
 * "Custom Site Logo" block.
 *
 * A dynamic block: the editor shows a live server-rendered preview and the
 * front end is rendered entirely by PHP (see Custom_Site_Logo_Block::render_block()),
 * so this file intentionally contains no markup-building logic of its own.
 *
 * @package Custom_Site_Logo
 */

( function ( blocks, element, blockEditor, components, serverSideRender, i18n ) {
	var el = element.createElement;
	var __ = i18n.__;
	var ServerSideRender = serverSideRender.default || serverSideRender;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;

	blocks.registerBlockType(
		'custom-site-logo/logo-block',
		{
			title: __( 'Custom Site Logo', 'custom-site-logo' ),
			description: __( 'Displays the logo configured in Appearance » Custom Site Logo.', 'custom-site-logo' ),
			icon: 'format-image',
			category: 'widgets',
			attributes: {
				variant: {
					type: 'string',
					default: ''
				}
			},
			supports: {
				html: false,
				align: [ 'left', 'center', 'right', 'wide', 'full' ],
				anchor: true,
				spacing: {
					margin: true,
					padding: true
				}
			},
			edit: function ( props ) {
				return el(
					element.Fragment,
					{},
					el(
						InspectorControls,
						{},
						el(
							PanelBody,
							{ title: __( 'Logo Version', 'custom-site-logo' ) },
							el(
								SelectControl,
								{
									label: __( 'Version to show', 'custom-site-logo' ),
									value: props.attributes.variant,
									help: __( 'By default the browser picks between your logos based on the device and colour scheme. Choose a specific version to always show that one.', 'custom-site-logo' ),
									options: [
										{ label: __( 'Automatic (recommended)', 'custom-site-logo' ), value: '' },
										{ label: __( 'Dark mode logo', 'custom-site-logo' ), value: 'dark' },
										{ label: __( 'Mobile logo', 'custom-site-logo' ), value: 'mobile' },
										{ label: __( 'Retina (@2x) logo', 'custom-site-logo' ), value: 'retina' }
									],
									onChange: function ( value ) {
										props.setAttributes( { variant: value } );
									}
								}
							)
						)
					),
					el(
						'div',
						{ className: 'csl-block-editor-preview' },
						el(
							ServerSideRender,
							{
								block: 'custom-site-logo/logo-block',
								attributes: props.attributes
							}
						)
					)
				);
			},
			save: function () {
				// Server-side rendered block; nothing to save on the client.
				return null;
			},
			variations: [
				{
					name: 'csl-logo-default',
					title: __( 'Site Logo', 'custom-site-logo' ),
					description: __( 'Adapts to the visitor\'s device and colour scheme.', 'custom-site-logo' ),
					icon: 'format-image',
					isDefault: true,
					attributes: { variant: '' },
					scope: [ 'block', 'inserter' ]
				},
				{
					name: 'csl-logo-dark',
					title: __( 'Site Logo (Dark)', 'custom-site-logo' ),
					description: __( 'Always shows your dark mode logo, for use on dark sections.', 'custom-site-logo' ),
					icon: 'admin-appearance',
					attributes: { variant: 'dark' },
					scope: [ 'block', 'inserter' ]
				},
				{
					name: 'csl-logo-mobile',
					title: __( 'Site Logo (Compact)', 'custom-site-logo' ),
					description: __( 'Always shows your mobile logo, for tight spaces.', 'custom-site-logo' ),
					icon: 'smartphone',
					attributes: { variant: 'mobile' },
					scope: [ 'block', 'inserter' ]
				}
			]
		}
	);
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.serverSideRender, window.wp.i18n );
