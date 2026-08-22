<?php
/**
 * Front-end `<head>` output: preloading, structured data and print styles.
 *
 * These are all opt-in from the settings page, and each one bails out early
 * when there is no logo configured, so the plugin adds nothing to the page
 * unless it has something useful to contribute.
 *
 * @link       https://no-site.com
 * @since      2.0.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */

/**
 * The document head output class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/includes
 */
class Custom_Site_Logo_Head {

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		/* Early, so the browser starts fetching the logo before it parses the stylesheets. */
		add_action( 'wp_head', array( $this, 'render_preload_tag' ), 2 );
		add_action( 'wp_head', array( $this, 'render_structured_data' ), 20 );
		add_action( 'wp_head', array( $this, 'render_print_styles' ), 21 );
	}

	/**
	 * Output a preload hint for the logo.
	 *
	 * A header logo is very often the Largest Contentful Paint element, and
	 * preloading it lets the browser start the request without waiting to
	 * discover the `<img>` in the markup.
	 *
	 * @since 2.0.0
	 */
	public function render_preload_tag() {
		if ( ! Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_preload_field' ) ) {
			return;
		}

		/* Preloading something that is deliberately lazy-loaded would defeat the point of both. */
		if ( Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_lazy_load_field' ) ) {
			return;
		}

		$image = Custom_Site_Logo_Renderer::get_logo_url();

		if ( empty( $image ) ) {
			return;
		}

		$retina = Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_retina_image_field' );
		$srcset = '';

		if ( ! empty( $retina ) ) {
			$srcset = sprintf(
				' imagesrcset="%1$s 1x, %2$s 2x"',
				esc_url( $image ),
				esc_url( $retina )
			);
		}

		printf(
			'<link rel="preload" as="image" href="%1$s"%2$s fetchpriority="high" />%3$s',
			esc_url( $image ),
			$srcset, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from esc_url() above.
			"\n"
		);
	}

	/**
	 * Output Organization structured data pointing at the logo.
	 *
	 * This is the markup search engines read to associate a logo with a site,
	 * which a plain `<img>` in the header does not give them.
	 *
	 * @since 2.0.0
	 */
	public function render_structured_data() {
		if ( ! Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_schema_field' ) ) {
			return;
		}

		$image = Custom_Site_Logo_Renderer::get_logo_url();

		if ( empty( $image ) ) {
			return;
		}

		$data = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Organization',
			'name'     => get_bloginfo( 'name' ),
			'url'      => home_url( '/' ),
			'logo'     => array(
				'@type' => 'ImageObject',
				'url'   => $image,
			),
		);

		list( $width, $height ) = Custom_Site_Logo_Renderer::get_image_dimensions( $image );

		if ( $width && $height ) {
			$data['logo']['width']  = $width;
			$data['logo']['height'] = $height;
		}

		/**
		 * Filters the Organization structured data emitted for the logo.
		 *
		 * @since 2.0.0
		 * @param array  $data  The structured data, before encoding.
		 * @param string $image The logo URL.
		 */
		$data = apply_filters( 'custom_site_logo_structured_data', $data, $image );

		printf(
			'<script type="application/ld+json">%s</script>%s',
			wp_json_encode( $data ),
			"\n"
		);
	}

	/**
	 * Output the print stylesheet for the logo.
	 *
	 * Browsers drop background images when printing and often print light
	 * artwork onto white paper, so this both forces the logo to print and
	 * optionally swaps in a print-specific version.
	 *
	 * @since 2.0.0
	 */
	public function render_print_styles() {
		if ( ! Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_print_field' ) ) {
			return;
		}

		if ( empty( Custom_Site_Logo_Renderer::get_logo_url() ) ) {
			return;
		}

		$print_image = Custom_Site_Logo_Options::get( 'csl_CustomSiteLogo_print_image_field' );

		$css = '@media print{.csl-customsite-logo{display:block!important;max-width:100%!important;-webkit-print-color-adjust:exact;print-color-adjust:exact;}';

		if ( ! empty( $print_image ) ) {
			/* Replacing an <img>'s rendered content is the only way to swap artwork from CSS alone. */
			$css .= sprintf( '.csl-customsite-logo{content:url("%s");}', esc_url( $print_image ) );
		}

		$css .= '}';

		printf( '<style id="csl-print-styles">%s</style>%s', $css, "\n" ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS built from esc_url() and literals above.
	}
}
