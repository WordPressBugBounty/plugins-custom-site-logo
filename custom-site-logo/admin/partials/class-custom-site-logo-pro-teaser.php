<?php
/**
 * Locked preview of the settings that belong to Custom Site Logo Pro.
 *
 * The controls are visible so the free plugin can show what Pro adds, and
 * they cannot be focused, clicked, or saved. Pro replaces the logo, campaign,
 * store, and agency tabs with a working form when it is active. Conditional
 * Rules and SEO & Performance stay in this plugin's form, but they are locked
 * until Pro is active.
 *
 * @link       https://no-site.com
 * @since      2.1.0
 *
 * @package    Custom_Site_Logo
 * @subpackage Custom_Site_Logo/admin/partials
 */

/**
 * Renders the non-interactive Pro settings preview.
 *
 * @since 2.1.0
 */
class Custom_Site_Logo_Pro_Teaser {

	/**
	 * Whether Custom Site Logo Pro is providing these settings itself.
	 *
	 * @since 2.1.0
	 * @return bool
	 */
	public static function is_unlocked() {
		/**
		 * Filters whether Custom Site Logo Pro is active and should replace this preview.
		 *
		 * @since 2.1.0
		 * @param bool $active False in the free plugin. Pro should return true.
		 */
		return (bool) apply_filters( 'custom_site_logo_pro_active', false );
	}

	/**
	 * Render one locked tab for each Pro group.
	 *
	 * Called only while Pro is inactive. The panels use the same ids as the
	 * working Pro form, so the sidebar tabs stay in place.
	 *
	 * @since 2.1.0
	 */
	public static function render_locked_tabs() {
		$teaser = new self();
		$teaser->render_tabs();
	}

	/**
	 * Locked network controls for the network settings screen.
	 *
	 * These rows sit inside the network dashboard form. They have no names,
	 * so saving that screen cannot store them until Pro is active.
	 *
	 * @since 2.1.0
	 */
	public static function render_locked_network_rows() {
		$teaser = new self();
		$teaser->render_locked_toggle_row( __( 'Lock for every site', 'custom-site-logo' ), __( 'Every site shows the main site’s logo and cannot override it. Part of Custom Site Logo Pro.', 'custom-site-logo' ) );
		$teaser->render_locked_text_row( __( 'Shared library', 'custom-site-logo' ), __( 'One logo URL per line, shared with every site. Part of Custom Site Logo Pro.', 'custom-site-logo' ) );
	}

	/**
	 * Render the locked Conditional Rules or SEO & Performance fields.
	 *
	 * The surrounding tab panel and heading are printed by the settings page.
	 * None of these controls have a name, so saving another tab cannot change them.
	 *
	 * @since 2.1.0
	 * @param string $tab_id csl_section_rules or csl_section_seo.
	 */
	public static function render_locked_section( $tab_id ) {
		$teaser = new self();
		$teaser->notice();

		echo '<div class="csl-pro-locked" inert><table class="form-table" role="presentation"><tbody>';

		if ( 'csl_section_seo' === $tab_id ) {
			$teaser->render_locked_toggle_row( __( 'Disable Link', 'custom-site-logo' ), __( 'Render the logo without wrapping it in a link.', 'custom-site-logo' ) );
			$teaser->render_locked_toggle_row( __( 'Open In New Tab', 'custom-site-logo' ), __( 'Open the logo link in a new tab.', 'custom-site-logo' ) );
			$teaser->render_locked_toggle_row( __( 'Nofollow Link', 'custom-site-logo' ), __( 'Add rel="nofollow" to the logo link.', 'custom-site-logo' ) );
			$teaser->render_locked_text_row( __( 'Title Attribute', 'custom-site-logo' ), __( 'Shown as a tooltip when hovering the logo.', 'custom-site-logo' ) );
			$teaser->render_locked_text_row( __( 'Link ARIA Label', 'custom-site-logo' ), __( 'Describes where the logo link goes, for screen reader users.', 'custom-site-logo' ) );
			$teaser->render_locked_toggle_row( __( 'Size Attributes', 'custom-site-logo' ), __( 'Add width and height attributes to the logo.', 'custom-site-logo' ) );
			$teaser->render_locked_toggle_row( __( 'Preload Logo', 'custom-site-logo' ), __( 'Tell the browser to start downloading the logo immediately.', 'custom-site-logo' ) );
			$teaser->render_locked_toggle_row( __( 'Lazy Load', 'custom-site-logo' ), __( 'Only load the logo when it scrolls into view.', 'custom-site-logo' ) );
			$teaser->render_locked_toggle_row( __( 'Structured Data', 'custom-site-logo' ), __( 'Publish the logo as Organization structured data.', 'custom-site-logo' ) );
		} elseif ( 'csl_section_analytics' === $tab_id ) {
			$teaser->render_locked_toggle_row( __( 'Track Logo Clicks', 'custom-site-logo' ), __( 'Count clicks on the logo.', 'custom-site-logo' ) );
			$teaser->render_locked_text_row( __( 'Reports', 'custom-site-logo' ), __( 'Today, the last 7 and 30 days, daily history, mobile and desktop, top pages, and where the logo linked.', 'custom-site-logo' ) );
		} else {
			$teaser->render_locked_text_row( __( 'Scheduled Logos', 'custom-site-logo' ), __( 'Swap the logo automatically between two dates.', 'custom-site-logo' ) );
			$teaser->render_locked_text_row( __( 'Conditional Logos', 'custom-site-logo' ), __( 'Show a different logo when a condition is met.', 'custom-site-logo' ) );
			$teaser->render_locked_text_row( __( 'Logo Per Language', 'custom-site-logo' ), __( 'Assign a logo to a locale, such as fr_FR.', 'custom-site-logo' ) );
			$teaser->render_locked_toggle_row( __( 'Rotate Logos', 'custom-site-logo' ), __( 'Pick a random logo from a list on each page load.', 'custom-site-logo' ) );
			$teaser->render_locked_text_row( __( 'Rotation Images', 'custom-site-logo' ), __( 'The logos rotation can choose from.', 'custom-site-logo' ) );
			if ( is_multisite() ) {
				$teaser->render_locked_toggle_row( __( 'Network Default', 'custom-site-logo' ), __( 'Fall back to the network-wide logo when this site has none.', 'custom-site-logo' ) );
			}
		}

		echo '</tbody></table></div>';
	}

	/**
	 * Locked panels that replace the old single Pro tab.
	 *
	 * @since 2.1.0
	 */
	private function render_tabs() {
		$this->open_panel( 'csl_pro_logos', __( 'Logo Sets', 'custom-site-logo' ) );
		$this->render_locked_image_row( __( 'Footer logo', 'custom-site-logo' ), __( 'Place it with the shortcode [csl_pro_logo location="footer"].', 'custom-site-logo' ) );
		$this->render_locked_image_row( __( 'Email logo', 'custom-site-logo' ), __( 'Used as the header image on WooCommerce emails.', 'custom-site-logo' ) );
		$this->render_locked_image_row( __( 'Invoice logo', 'custom-site-logo' ), __( 'Used on invoices and other printed store documents.', 'custom-site-logo' ) );
		$this->render_locked_image_row( __( 'Checkout logo', 'custom-site-logo' ), __( 'Shown above the checkout form.', 'custom-site-logo' ) );
		$this->render_locked_image_row( __( 'My Account logo', 'custom-site-logo' ), __( 'Shown above the customer account menu.', 'custom-site-logo' ) );
		$this->render_locked_image_row( __( 'Refund email logo', 'custom-site-logo' ), __( 'Used on refund emails instead of the normal email logo.', 'custom-site-logo' ) );
		$this->render_locked_toggle_row( __( 'Site icons', 'custom-site-logo' ), __( 'Generate the site icon from the main logo, and publish that logo as the social image.', 'custom-site-logo' ) );
		$this->close_panel();

		$this->open_panel( 'csl_pro_campaigns', __( 'Campaigns', 'custom-site-logo' ) );
		$this->render_locked_toggle_row( __( 'Repeat campaigns', 'custom-site-logo' ), __( 'Show each scheduled logo again every year between the same month and day.', 'custom-site-logo' ) );
		$this->render_locked_text_row( __( 'Campaign link', 'custom-site-logo' ), __( 'While a scheduled logo is showing, the logo opens this page.', 'custom-site-logo' ) );
		?>
		<tr>
			<th scope="row"><?php esc_html_e( 'Campaign pack', 'custom-site-logo' ); ?></th>
			<td>
				<button type="button" class="button" disabled><?php esc_html_e( 'Add Black Friday', 'custom-site-logo' ); ?></button>
				<button type="button" class="button" disabled><?php esc_html_e( 'Add Christmas', 'custom-site-logo' ); ?></button>
				<p class="description"><?php esc_html_e( 'Adds a dated schedule that uses the current main logo.', 'custom-site-logo' ); ?></p>
			</td>
		</tr>
		<?php
		$this->render_locked_toggle_row( __( 'Ending reminder', 'custom-site-logo' ), __( 'Email the site admin three days before a campaign ends.', 'custom-site-logo' ) );
		$this->close_panel();

		$this->open_panel( 'csl_pro_access', __( 'Access', 'custom-site-logo' ) );
		?>
		<tr>
			<th scope="row"><?php esc_html_e( 'Who can edit', 'custom-site-logo' ); ?></th>
			<td>
				<select disabled>
					<option><?php esc_html_e( 'Administrators only', 'custom-site-logo' ); ?></option>
					<option><?php esc_html_e( 'Administrators and shop managers', 'custom-site-logo' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Change history', 'custom-site-logo' ); ?></th>
			<td>
				<button type="button" class="button" disabled><?php esc_html_e( 'Restore previous logo', 'custom-site-logo' ); ?></button>
				<p class="description"><?php esc_html_e( 'Pro records who changed the logo and lets you restore the previous image.', 'custom-site-logo' ); ?></p>
			</td>
		</tr>
		<?php
		$this->close_panel();

		$this->open_panel( 'csl_pro_woocommerce', __( 'WooCommerce', 'custom-site-logo' ) );
		$this->render_locked_image_row( __( 'Brand logo', 'custom-site-logo' ), __( 'Shown on products in the category slug below.', 'custom-site-logo' ) );
		$this->render_locked_text_row( __( 'Category slug', 'custom-site-logo' ), __( 'Product category slug, for example hoodies.', 'custom-site-logo' ) );
		?>
		<tr>
			<th scope="row"><?php esc_html_e( 'Country logo', 'custom-site-logo' ); ?></th>
			<td>
				<select class="csl-country-select" disabled>
					<option><?php esc_html_e( 'United States', 'custom-site-logo' ); ?></option>
					<option><?php esc_html_e( 'United Kingdom', 'custom-site-logo' ); ?></option>
				</select>
				<?php $this->render_locked_image( __( 'Shown to visitors identified as being in that country.', 'custom-site-logo' ) ); ?>
			</td>
		</tr>
		<?php
		$this->render_locked_image_row( __( 'Business hours logo', 'custom-site-logo' ), __( 'Shown during open hours. The main logo is used outside them.', 'custom-site-logo' ) );
		$this->close_panel();

		$this->open_panel( 'csl_pro_agency', __( 'Agency', 'custom-site-logo' ) );
		$this->render_locked_toggle_row( __( 'Client mode', 'custom-site-logo' ), __( 'Shop managers only see the General tab, and can only change the main logo.', 'custom-site-logo' ) );
		$this->render_locked_toggle_row( __( 'Approve changes', 'custom-site-logo' ), __( 'A shop manager’s new logo stays pending until an administrator accepts it.', 'custom-site-logo' ) );
		$this->render_locked_text_row( __( 'Menu name', 'custom-site-logo' ), __( 'Replaces the plugin name under Appearance.', 'custom-site-logo' ) );
		$this->render_locked_toggle_row( __( 'Logo Maker SVG', 'custom-site-logo' ), __( 'Adds a button on the Logo Maker tab that saves the text logo as an SVG.', 'custom-site-logo' ) );
		?>
		<tr>
			<th scope="row"><?php esc_html_e( 'Find the logo', 'custom-site-logo' ); ?></th>
			<td>
				<button type="button" class="button" disabled><?php esc_html_e( 'Scan the theme', 'custom-site-logo' ); ?></button>
				<p class="description"><?php esc_html_e( 'Lists every place the theme outputs a logo.', 'custom-site-logo' ); ?></p>
			</td>
		</tr>
		<?php
		$this->close_panel();

		$this->open_panel( 'csl_pro_files', __( 'Logo Files', 'custom-site-logo' ) );
		$this->render_locked_toggle_row( __( 'Size check', 'custom-site-logo' ), __( 'Warn when a new main logo is very small or extremely wide.', 'custom-site-logo' ) );
		?>
		<tr>
			<th scope="row"><?php esc_html_e( 'Download set', 'custom-site-logo' ); ?></th>
			<td>
				<button type="button" class="button" disabled><?php esc_html_e( 'Download ZIP', 'custom-site-logo' ); ?></button>
				<p class="description"><?php esc_html_e( 'Zips the main, retina, dark, mobile, and Pro logos that live in the Media Library.', 'custom-site-logo' ); ?></p>
			</td>
		</tr>
		<?php
		$this->render_locked_text_row( __( 'Translated alt text', 'custom-site-logo' ), __( 'One language per line, written as locale|alt text.', 'custom-site-logo' ) );
		$this->close_panel();
	}

	/**
	 * Open a locked tab panel and its table.
	 *
	 * @since 2.1.0
	 * @param string $id    Tab id.
	 * @param string $label Panel heading.
	 */
	private function open_panel( $id, $label ) {
		printf(
			'<div class="csl-tab-content" id="%1$s-panel" data-tab="%1$s" role="tabpanel" aria-labelledby="%1$s">',
			esc_attr( $id )
		);
		printf( '<h2 class="csl-panel-title">%s</h2>', esc_html( $label ) );
		$this->notice();
		echo '<div class="csl-pro-locked" inert><table class="form-table" role="presentation"><tbody>';
	}

	/**
	 * Close the table and panel opened by open_panel().
	 *
	 * @since 2.1.0
	 */
	private function close_panel() {
		echo '</tbody></table></div></div>';
	}

	/**
	 * Short note that the controls need Pro.
	 *
	 * @since 2.1.0
	 */
	private function notice() {
		?>
		<div class="csl-pro-notice">
			<span class="csl-pro-badge"><?php esc_html_e( 'Pro', 'custom-site-logo' ); ?></span>
			<p><?php esc_html_e( 'These settings are part of Custom Site Logo Pro. They are shown here as a preview and cannot be changed until Pro is active.', 'custom-site-logo' ); ?></p>
		</div>
		<?php
	}

	/**
	 * A disabled image row.
	 *
	 * @since 2.1.0
	 * @param string $label       Row label.
	 * @param string $description Help text.
	 */
	private function render_locked_image_row( $label, $description ) {
		?>
		<tr>
			<th scope="row"><?php echo esc_html( $label ); ?></th>
			<td><?php $this->render_locked_image( $description ); ?></td>
		</tr>
		<?php
	}

	/**
	 * A disabled toggle row.
	 *
	 * @since 2.1.0
	 * @param string $label       Row label.
	 * @param string $description Toggle text.
	 */
	private function render_locked_toggle_row( $label, $description ) {
		?>
		<tr>
			<th scope="row"><?php echo esc_html( $label ); ?></th>
			<td><?php $this->render_locked_toggle( $description ); ?></td>
		</tr>
		<?php
	}

	/**
	 * A disabled text row.
	 *
	 * @since 2.1.0
	 * @param string $label       Row label.
	 * @param string $description Help text.
	 */
	private function render_locked_text_row( $label, $description ) {
		?>
		<tr>
			<th scope="row"><?php echo esc_html( $label ); ?></th>
			<td>
				<input type="text" class="regular-text" value="" disabled />
				<p class="description"><?php echo esc_html( $description ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * A disabled image field with no submitted name.
	 *
	 * @since 2.1.0
	 * @param string $description Help text under the field.
	 */
	private function render_locked_image( $description ) {
		?>
		<div class="csl-image-field">
			<span class="csl-image-thumb" aria-hidden="true"><span class="dashicons dashicons-format-image"></span></span>
			<span class="csl-image-controls">
				<input type="text" class="regular-text" value="" placeholder="<?php esc_attr_e( 'Image URL', 'custom-site-logo' ); ?>" disabled />
				<span class="csl-image-buttons">
					<button type="button" class="button" disabled>
						<span class="dashicons dashicons-admin-media"></span>
						<?php esc_html_e( 'Media Library', 'custom-site-logo' ); ?>
					</button>
				</span>
				<p class="description"><?php echo esc_html( $description ); ?></p>
			</span>
		</div>
		<?php
	}

	/**
	 * A disabled toggle with no submitted name.
	 *
	 * @since 2.1.0
	 * @param string $label Text beside the switch.
	 */
	private function render_locked_toggle( $label ) {
		?>
		<label class="csl-toggle-field">
			<input type="checkbox" disabled />
			<span class="csl-toggle-switch" aria-hidden="true"></span>
			<span><?php echo esc_html( $label ); ?></span>
		</label>
		<?php
	}
}
