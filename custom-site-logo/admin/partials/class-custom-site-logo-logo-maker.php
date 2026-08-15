<?php
/**
 * A simple built-in text logo maker.
 *
 * Lets a site owner type some text, pick a font/colors, and generate a
 * logo image entirely in the browser (via <canvas>), which is then
 * uploaded straight to the Media Library and set as the site logo -
 * no external image editor required.
 *
 * @link       https://no-site.com
 * @since      1.2.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */

/**
 * The Logo Maker class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */
class Custom_Site_Logo_Logo_Maker {

	/**
	 * The fonts offered in the logo maker, mapping a CSS font-family value
	 * to its human-readable label. A mix of web-safe fonts and a handful of
	 * Google Fonts (loaded only on this admin screen) for more variety.
	 *
	 * @since 1.2.0
	 * @return array
	 */
	private function get_fonts() {
		return array(
			'Arial, Helvetica, sans-serif' => __( 'Arial', 'custom-site-logo' ),
			'Georgia, serif'               => __( 'Georgia', 'custom-site-logo' ),
			'"Times New Roman", serif'     => __( 'Times New Roman', 'custom-site-logo' ),
			'"Courier New", monospace'     => __( 'Courier New', 'custom-site-logo' ),
			'Impact, sans-serif'           => __( 'Impact', 'custom-site-logo' ),
			'Verdana, sans-serif'          => __( 'Verdana', 'custom-site-logo' ),
			'"Poppins", sans-serif'        => __( 'Poppins (Google Font)', 'custom-site-logo' ),
			'"Montserrat", sans-serif'     => __( 'Montserrat (Google Font)', 'custom-site-logo' ),
			'"Playfair Display", serif'    => __( 'Playfair Display (Google Font)', 'custom-site-logo' ),
			'"Oswald", sans-serif'         => __( 'Oswald (Google Font)', 'custom-site-logo' ),
			'"Pacifico", cursive'          => __( 'Pacifico (Google Font, script)', 'custom-site-logo' ),
			'"Lobster", cursive'           => __( 'Lobster (Google Font, script)', 'custom-site-logo' ),
		);
	}

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since    1.2.0
	 */
	public function __construct() {
		add_action( 'custom_site_logo_render_custom_tab', array( $this, 'maybe_render_ui' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Only render on the "Logo Maker" tab.
	 *
	 * @since    1.2.0
	 * @param    string $tab_id The id of the tab panel currently being rendered.
	 */
	public function maybe_render_ui( $tab_id ) {
		if ( 'csl_logo_maker' === $tab_id ) {
			$this->render_ui();
		}
	}

	/**
	 * Load the Google Fonts stylesheet and the logo maker script, only on
	 * this plugin's own settings screen.
	 *
	 * @since    1.2.0
	 * @param    string $hook_suffix The current admin page hook suffix.
	 */
	public function enqueue_assets( $hook_suffix = '' ) {
		if ( 'appearance_page_custom-site-logo' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'csl-logo-maker-fonts',
			'https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Montserrat:wght@700&family=Playfair+Display:wght@700&family=Oswald:wght@600&family=Pacifico&family=Lobster&display=swap',
			array(),
			null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Third-party (Google Fonts) URL; a version query arg is meaningless here and would defeat their own CDN caching.
		);

		wp_enqueue_script(
			'csl-logo-maker',
			plugins_url( '../js/custom-site-logo-logo-maker.js', __FILE__ ),
			array( 'jquery' ),
			defined( 'CUSTOM_SITE_LOGO_VERSION' ) ? CUSTOM_SITE_LOGO_VERSION : '1.2.0',
			true
		);

		wp_localize_script(
			'csl-logo-maker',
			'cslLogoMaker',
			array(
				'restUrl'     => esc_url_raw( rest_url( 'wp/v2/media' ) ),
				'nonce'       => wp_create_nonce( 'wp_rest' ),
				'defaultText' => get_bloginfo( 'name' ),
				'i18n'        => array(
					'uploading' => __( 'Generating and uploading your logoâ€¦', 'custom-site-logo' ),
					'success'   => __( 'Logo created! It has been set below. Click "Save Settings" on the General tab to apply it.', 'custom-site-logo' ),
					'error'     => __( 'Something went wrong while uploading the generated logo. Please try again.', 'custom-site-logo' ),
					'noText'    => __( 'Please enter some text for your logo first.', 'custom-site-logo' ),
				),
			)
		);
	}

	/**
	 * Render the logo maker tool.
	 *
	 * @since    1.2.0
	 */
	private function render_ui() {
		$fonts = $this->get_fonts();
		?>
		<div class="csl-logo-maker">
			<p class="description"><?php esc_html_e( 'Don\'t have a logo yet? Create a simple text logo right here â€” it will be uploaded to your Media Library and set as your site logo automatically.', 'custom-site-logo' ); ?></p>

			<div class="csl-logo-maker-layout">
				<div class="csl-logo-maker-controls">
					<p>
						<label for="csl_logo_maker_text"><strong><?php esc_html_e( 'Logo Text', 'custom-site-logo' ); ?></strong></label><br />
						<input type="text" id="csl_logo_maker_text" class="regular-text" value="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" placeholder="<?php esc_attr_e( 'Your Site Name', 'custom-site-logo' ); ?>" />
					</p>

					<p>
						<label for="csl_logo_maker_font"><strong><?php esc_html_e( 'Font', 'custom-site-logo' ); ?></strong></label><br />
						<select id="csl_logo_maker_font">
							<?php foreach ( $fonts as $font_value => $font_label ) : ?>
								<option value="<?php echo esc_attr( $font_value ); ?>"><?php echo esc_html( $font_label ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>

					<p>
						<label for="csl_logo_maker_size"><strong><?php esc_html_e( 'Font Size', 'custom-site-logo' ); ?></strong></label><br />
						<input type="range" id="csl_logo_maker_size" min="24" max="110" value="64" />
					</p>

					<p class="csl-logo-maker-colors">
						<span>
							<label for="csl_logo_maker_text_color"><strong><?php esc_html_e( 'Text Color', 'custom-site-logo' ); ?></strong></label><br />
							<input type="color" id="csl_logo_maker_text_color" value="#1d2327" />
						</span>
						<span>
							<label for="csl_logo_maker_bg_color"><strong><?php esc_html_e( 'Background Color', 'custom-site-logo' ); ?></strong></label><br />
							<input type="color" id="csl_logo_maker_bg_color" value="#ffffff" />
						</span>
						<span>
							<label for="csl_logo_maker_transparent">
								<input type="checkbox" id="csl_logo_maker_transparent" checked="checked" />
								<?php esc_html_e( 'Transparent background', 'custom-site-logo' ); ?>
							</label>
						</span>
					</p>

					<p>
						<button type="button" id="csl_logo_maker_generate" class="button button-primary">
							<span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Use This Logo', 'custom-site-logo' ); ?>
						</button>
						<span id="csl_logo_maker_status" class="csl-logo-maker-status"></span>
					</p>
				</div>

				<div class="csl-logo-maker-preview">
					<canvas id="csl_logo_maker_canvas" width="800" height="240"></canvas>
				</div>
			</div>
		</div>
		<?php
	}
}
