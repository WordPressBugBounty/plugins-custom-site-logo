<?php
/**
 * A classic widget that displays the configured Custom Site Logo.
 *
 * @link       https://no-site.com
 * @since      1.2.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/public/partials
 */

/**
 * The classic widget class.
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/public/partials
 */
class Custom_Site_Logo_Widget extends WP_Widget {

	/**
	 * Register the widget with WordPress.
	 *
	 * @since    1.2.0
	 */
	public function __construct() {
		parent::__construct(
			'csl_logo_widget',
			__( 'Custom Site Logo', 'custom-site-logo' ),
			array(
				'description' => __( 'Displays the logo configured in Appearance Â» Custom Site Logo.', 'custom-site-logo' ),
			)
		);
	}

	/**
	 * Output the widget content on the front end.
	 *
	 * @since    1.2.0
	 * @param    array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param    array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Provided by the current theme's registered sidebar.

		if ( ! empty( $instance['title'] ) ) {
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- before_title/after_title are provided by the current theme's registered sidebar.
		}

		echo Custom_Site_Logo_Renderer::render( array( 'post_id' => get_the_ID() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup is already escaped inside Custom_Site_Logo_Renderer::render().

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Provided by the current theme's registered sidebar.
	}

	/**
	 * Output the widget settings form in the admin.
	 *
	 * @since    1.2.0
	 * @param    array $instance The current settings for the widget.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'custom-site-logo' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p class="description"><?php esc_html_e( 'Leave blank to hide the widget title.', 'custom-site-logo' ); ?></p>
		<?php
	}

	/**
	 * Sanitize the widget settings before saving.
	 *
	 * @since    1.2.0
	 * @param    array $new_instance New settings for this instance as input by the user.
	 * @param    array $old_instance Old settings for this instance.
	 * @return   array The sanitized settings to save.
	 */
	public function update( $new_instance, $old_instance ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- Required by the WP_Widget::update() method signature.
		$instance          = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}
}
