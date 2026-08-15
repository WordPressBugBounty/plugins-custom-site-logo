<?php
/**
 * Registers the "Custom Site Logo" Gutenberg block.
 *
 * This is a dynamic block: the editor shows a live server-rendered preview
 * (via wp.serverSideRender) and the front end is rendered through the very
 * same Custom_Site_Logo_Renderer used by the shortcode, template tag, and
 * widget. No build step is required; the editor script is plain JavaScript
 * that relies on the WordPress-bundled `wp-*` script handles.
 *
 * @link       https://no-site.com
 * @since      1.2.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */

/**
 * The Gutenberg block registration class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */
class Custom_Site_Logo_Block {

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since    1.2.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	/**
	 * Register the block type and its editor script.
	 *
	 * @since    1.2.0
	 */
	public function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		wp_register_script(
			'csl-logo-block-editor',
			plugins_url( '../blocks/logo-block/index.js', __FILE__ ),
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render' ),
			defined( 'CUSTOM_SITE_LOGO_VERSION' ) ? CUSTOM_SITE_LOGO_VERSION : '1.2.0',
			true
		);

		register_block_type(
			'custom-site-logo/logo-block',
			array(
				'editor_script'   => 'csl-logo-block-editor',
				'render_callback' => array( $this, 'render_block' ),
				'attributes'      => array(),
			)
		);
	}

	/**
	 * Render the block on the front end.
	 *
	 * @since    1.2.0
	 * @return   string The logo markup.
	 */
	public function render_block() {
		return Custom_Site_Logo_Renderer::render( array( 'post_id' => get_the_ID() ) );
	}
}
