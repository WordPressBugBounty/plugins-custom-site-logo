<?php
/**
 * Enables safe SVG uploads for use as a logo.
 *
 * SVG upload is restricted to users who can `manage_options` (the same
 * capability required to change the logo settings), and uploaded SVG files
 * are passed through a basic sanitizer that strips <script> tags and
 * inline event-handler attributes before they're saved to disk.
 *
 * @link       https://no-site.com
 * @since      1.2.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */

/**
 * The SVG upload support class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */
class Custom_Site_Logo_Svg_Support {

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since    1.2.0
	 */
	public function __construct() {
		add_filter( 'upload_mimes', array( $this, 'allow_svg_upload' ) );
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'fix_svg_filetype' ), 10, 4 );
		add_filter( 'wp_handle_upload_prefilter', array( $this, 'sanitize_svg_upload' ) );
		add_action( 'admin_head', array( $this, 'fix_svg_thumbnail_display' ) );
	}

	/**
	 * Allow the SVG mime type to be uploaded, for admins only.
	 *
	 * @since    1.2.0
	 * @param    array $mimes Allowed mime types, keyed by file extension.
	 * @return   array
	 */
	public function allow_svg_upload( $mimes ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return $mimes;
		}

		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';

		return $mimes;
	}

	/**
	 * Ensure WordPress correctly recognizes uploaded SVG files.
	 *
	 * @since    1.2.0
	 * @param    array  $data     File data, including 'ext', 'type', and 'proper_filename' keys.
	 * @param    string $file     Full path to the uploaded file.
	 * @param    string $filename The name of the file.
	 * @param    array  $mimes    Key is the file extension with value as the mime type.
	 * @return   array
	 */
	public function fix_svg_filetype( $data, $file, $filename, $mimes ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- Required by the wp_check_filetype_and_ext filter callback signature.
		if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
			return $data;
		}

		$filetype = wp_check_filetype( $filename, $mimes );

		if ( 'svg' === $filetype['ext'] ) {
			$data['ext']             = 'svg';
			$data['type']            = 'image/svg+xml';
			$data['proper_filename'] = $filename;
		}

		return $data;
	}

	/**
	 * Strip potentially dangerous markup from an uploaded SVG file before it's saved.
	 *
	 * This is a basic sanitizer, not an exhaustive security guarantee. It is
	 * only applied to files uploaded by users capable of `manage_options`.
	 *
	 * @since    1.2.0
	 * @param    array $file Uploaded file data (from the $_FILES superglobal).
	 * @return   array
	 */
	public function sanitize_svg_upload( $file ) {
		if ( empty( $file['type'] ) || 'image/svg+xml' !== $file['type'] ) {
			return $file;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			$file['error'] = __( 'You do not have permission to upload SVG files.', 'custom-site-logo' );
			return $file;
		}

		$contents = file_get_contents( $file['tmp_name'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading a just-uploaded temp file, not a remote URL.

		if ( false === $contents || false === stripos( $contents, '<svg' ) ) {
			$file['error'] = __( 'This does not appear to be a valid SVG file.', 'custom-site-logo' );
			return $file;
		}

		// Strip script tags and inline event handler attributes (onload, onclick, etc.).
		$contents = preg_replace( '#<script\b[^>]*>.*?</script>#is', '', $contents );
		$contents = preg_replace( '/\s+on\w+\s*=\s*"[^"]*"/i', '', $contents );
		$contents = preg_replace( "/\s+on\w+\s*=\s*'[^']*'/i", '', $contents );
		$contents = preg_replace( '/\s+on\w+\s*=\s*[^\s>]+/i', '', $contents );

		file_put_contents( $file['tmp_name'], $contents ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- Writing back to the same just-uploaded temp file.

		return $file;
	}

	/**
	 * Give SVG attachments a sensible size in the Media Library grid, since
	 * they have no raster dimensions for WordPress to compute a thumbnail from.
	 *
	 * @since    1.2.0
	 */
	public function fix_svg_thumbnail_display() {
		echo '<style>.media-icon img[src$=".svg"], td.media-icon img[src$=".svg"] { width: 100%; height: auto; }</style>';
	}
}
