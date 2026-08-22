<?php
/**
 * Per-page/post logo override meta box.
 *
 * Lets an editor choose a different logo to display on a specific
 * post/page (e.g. a campaign landing page) than the site-wide default
 * configured in Appearance » Custom Site Logo.
 *
 * @link       https://no-site.com
 * @since      1.2.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */

/**
 * The per-page/post logo override meta box class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */
class Custom_Site_Logo_Meta_Box {

	/**
	 * Nonce action/name used to verify the meta box save request.
	 *
	 * @since 1.2.0
	 * @var   string
	 */
	const NONCE_ACTION = 'csl_logo_override_save';

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since    1.2.0
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register the meta box on every public post type.
	 *
	 * @since    1.2.0
	 */
	public function register_meta_box() {
		$post_types = get_post_types( array( 'public' => true ) );

		add_meta_box(
			'csl_logo_override_meta_box',
			__( 'Custom Site Logo Override', 'custom-site-logo' ),
			array( $this, 'render_meta_box' ),
			$post_types,
			'side',
			'default'
		);
	}

	/**
	 * Load the media uploader on post edit screens only.
	 *
	 * @since    1.2.0
	 * @param    string $hook_suffix The current admin page hook suffix.
	 */
	public function enqueue_assets( $hook_suffix = '' ) {
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script(
			'csl-meta-box',
			plugins_url( '../js/custom-site-logo-meta-box.js', __FILE__ ),
			array( 'jquery' ),
			defined( 'CUSTOM_SITE_LOGO_VERSION' ) ? CUSTOM_SITE_LOGO_VERSION : '1.2.0',
			true
		);
	}

	/**
	 * Render the meta box fields.
	 *
	 * @since    1.2.0
	 * @param    WP_Post $post The post being edited.
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( self::NONCE_ACTION, 'csl_logo_override_nonce' );

		$enabled = get_post_meta( $post->ID, '_csl_logo_override_enabled', true );
		$image   = get_post_meta( $post->ID, '_csl_logo_override_image', true );
		?>
		<p>
			<label>
				<input type="checkbox" id="csl_logo_override_enabled" name="csl_logo_override_enabled" value="1" <?php checked( $enabled, 1 ); ?> />
				<?php esc_html_e( 'Use a different logo on this page', 'custom-site-logo' ); ?>
			</label>
		</p>
		<p>
			<input type="button" class="button" id="csl_logo_override_button" value="<?php esc_attr_e( 'Media Library', 'custom-site-logo' ); ?>" style="margin-bottom:6px;" /><br />
			<input type="text" class="widefat" id="csl_logo_override_image" name="csl_logo_override_image" value="<?php echo esc_attr( $image ); ?>" placeholder="<?php esc_attr_e( 'No image selected', 'custom-site-logo' ); ?>" />
		</p>
		<?php if ( ! empty( $image ) ) : ?>
			<p><img id="csl_logo_override_preview" src="<?php echo esc_url( $image ); ?>" style="max-width:100%;height:auto;" /></p>
		<?php else : ?>
			<p><img id="csl_logo_override_preview" src="" style="max-width:100%;height:auto;display:none;" /></p>
		<?php endif; ?>
		<p class="description"><?php esc_html_e( 'Falls back to the site-wide logo when disabled or empty.', 'custom-site-logo' ); ?></p>

		<?php
		/*
		 * The site-wide retina/dark/mobile variants belong to a different logo,
		 * so they are not reused for an override. These fields let an override
		 * keep high-DPI and dark-mode support with its own artwork.
		 */
		?>
		<details class="csl-override-variants">
			<summary><?php esc_html_e( 'Alternate versions of this logo', 'custom-site-logo' ); ?></summary>
			<?php
			foreach ( self::get_variant_fields() as $meta_key => $label ) :
				$variant_value = get_post_meta( $post->ID, $meta_key, true );
				?>
				<p>
					<label for="<?php echo esc_attr( $meta_key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label>
					<input type="text" class="widefat csl-override-variant" id="<?php echo esc_attr( $meta_key ); ?>"
						name="<?php echo esc_attr( $meta_key ); ?>" value="<?php echo esc_attr( $variant_value ); ?>"
						placeholder="<?php esc_attr_e( 'No image selected', 'custom-site-logo' ); ?>" />
					<button type="button" class="button csl-override-variant-button"><?php esc_html_e( 'Media Library', 'custom-site-logo' ); ?></button>
				</p>
			<?php endforeach; ?>
			<p class="description"><?php esc_html_e( 'Optional. Leave empty to serve the override image to every device.', 'custom-site-logo' ); ?></p>
		</details>
		<?php
	}

	/**
	 * The per-page variant fields, keyed by post meta key.
	 *
	 * @since 2.0.0
	 * @return array Map of meta key => label.
	 */
	private static function get_variant_fields() {
		return array(
			'_csl_logo_override_retina' => __( 'Retina (@2x)', 'custom-site-logo' ),
			'_csl_logo_override_dark'   => __( 'Dark mode', 'custom-site-logo' ),
			'_csl_logo_override_mobile' => __( 'Mobile', 'custom-site-logo' ),
		);
	}

	/**
	 * Persist the meta box fields.
	 *
	 * @since    1.2.0
	 * @param    int $post_id The ID of the post being saved.
	 */
	public function save_meta_box( $post_id ) {
		if ( ! isset( $_POST['csl_logo_override_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['csl_logo_override_nonce'] ) ), self::NONCE_ACTION ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$enabled = isset( $_POST['csl_logo_override_enabled'] ) ? 1 : 0;
		update_post_meta( $post_id, '_csl_logo_override_enabled', $enabled );

		if ( isset( $_POST['csl_logo_override_image'] ) ) {
			update_post_meta( $post_id, '_csl_logo_override_image', esc_url_raw( wp_unslash( $_POST['csl_logo_override_image'] ) ) );
		}

		foreach ( array_keys( self::get_variant_fields() ) as $meta_key ) {
			if ( isset( $_POST[ $meta_key ] ) ) {
				update_post_meta( $post_id, $meta_key, esc_url_raw( wp_unslash( $_POST[ $meta_key ] ) ) );
			}
		}
	}
}
