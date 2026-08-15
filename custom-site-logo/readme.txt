=== Custom Site Logo ===
Contributors: iticiti
Tags: logo, center logo, logo management, custom logo, widget
Requires at least: 3.0
Tested up to: 7.0
Requires PHP: 5.6
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Upload a custom logo and display it anywhere via a shortcode, template tag, block, or widget — with retina, dark mode, mobile, and SVG support.

== Description ==

**Custom Site Logo** helps you manage your WordPress website's logo without editing a single line of code. Upload a new logo, or reuse one from your Media Library, then display it anywhere: in your theme, in a post or page, in the block editor, or in a widget area — no template editing required.

= Key Features =

* **Multiple ways to display your logo** — a `[csl_display_logo]` shortcode, a `csl_CustomSiteLogo_show_logo()` template tag, a "Custom Site Logo" block for the block editor, and a classic widget.
* **Live preview in the Customizer** — configure and preview your logo directly from Appearance » Customize, with instant feedback in the preview pane.
* **Retina/@2x support** — upload a high-resolution logo for crisp display on high-DPI screens.
* **Dark mode logo** — upload an alternate logo that's shown automatically based on the visitor's OS/browser color scheme preference.
* **Mobile logo** — upload an alternate logo for small screens, with a configurable breakpoint.
* **SVG logo support** — upload SVG logos in addition to raster images, with basic upload sanitization.
* **Per-page/post override** — show a different logo on individual pages or posts, great for campaign landing pages.
* **Built-in Logo Maker** — no logo yet? Type your site name, pick a font and colors, and generate a simple text logo right on the settings page. It's uploaded to your Media Library and set as your logo automatically.
* **Export/Import** — back up your settings, or move them to another site, as a JSON file.
* **Full styling controls** — set a custom width and height, apply a hover effect, make the logo fully responsive, center it, and optionally link it to a custom URL.
* **Organized, tabbed settings page** — General, Size & Layout, Hover Effect, Advanced Logos, and Import/Export tabs, with the active tab remembered between visits.

= How to Use It =

1. Open the `header.php` file of your active theme.
2. Paste `<?php echo csl_CustomSiteLogo_show_logo(); ?>` where you want the logo to appear.

Prefer not to edit theme files? Use the `[csl_display_logo]` shortcode in any post or page, add the "Custom Site Logo" block in the block editor, or drag the "Custom Site Logo" widget into a widget area instead.

If you run into any issues setting up the plugin, please reach out via the [support forum](https://wordpress.org/support/plugin/custom-site-logo/).

== Installation ==

= From your WordPress dashboard =

1. Go to Plugins » Add New.
2. Search for "Custom Site Logo".
3. Click **Install Now**, then **Activate**.
4. Go to Appearance » Custom Site Logo to upload your logo and configure it, or Appearance » Customize for a live preview.

= Manual installation =

1. Upload the `custom-site-logo` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Go to Appearance » Custom Site Logo to upload your logo and configure it.

== Frequently Asked Questions ==

= How do I insert the logo? =

Open the `header.php` file of your current theme and paste `<?php echo csl_CustomSiteLogo_show_logo(); ?>` where you want the logo to appear. Alternatively, use the `[csl_display_logo]` shortcode, the "Custom Site Logo" block, or the "Custom Site Logo" widget — no template editing required.

= Can I insert the logo from the post/page editor? =

Yes, just use the `[csl_display_logo]` shortcode, or the "Custom Site Logo" block, in any post, page, or custom post type editor to display the logo.

= Can I use a different logo on a specific page? =

Yes. Edit the page or post and use the "Custom Site Logo" meta box in the sidebar to choose an override logo just for that page.

= Does it support retina screens and dark mode? =

Yes. On the "Advanced Logos" tab of the settings page you can upload a retina/@2x logo, a dark-mode logo, and a mobile logo, each shown automatically under the right conditions.

= Why does my logo look too big? =

Uncheck "Make Logo Responsive" on the settings page, or set a custom width and/or height for your logo.

= Where is the settings page? =

Go to Dashboard » Appearance » Custom Site Logo. You can also configure and preview your logo live from Appearance » Customize.

= Can I back up or transfer my settings? =

Yes. Use the Import/Export tab on the settings page to download your configuration as a JSON file, and import it again on this site or another one.

== Screenshots ==

1. The plugin's settings page. Customize your logo and its behaviour from here.
2. The built-in Logo Maker tab — type your site name, pick a font and colors, and generate a text logo without ever leaving the settings page.

== Changelog ==

= 1.2.0 =
Release Date: August 15th, 2026

New features:

* Added a "Custom Site Logo" block for the block editor, with a live preview.
* Added a "Custom Site Logo" classic widget.
* Added retina/@2x logo support.
* Added an alternate dark-mode logo, shown automatically based on the visitor's OS/browser preference.
* Added an alternate mobile logo with a configurable breakpoint.
* Added SVG logo upload support with basic sanitization.
* Added a per-page/post logo override meta box.
* Added a live-preview Customizer section (Appearance » Customize).
* Added settings export/import (as a JSON file).
* Added a "Logo Maker" tab: no logo yet? Type your site name, pick a font and colors, and generate a text logo right on the settings page. It's uploaded to your Media Library and set as your logo automatically.

Updates:

* Reorganized the settings page into tabs (General, Size & Layout, Hover Effect, Advanced Logos, Import/Export) for easier navigation. The active tab is remembered between visits.
* Redesigned the settings page with a proper page header, thumbnail previews next to every logo field (with a one-click "Remove"), modern on/off toggle switches for checkbox options, and a two-card layout for Import/Export.
* Shrunk the oversized hover-effect preview image on the settings page.

Bugfixes:

* Fixed the "no logo uploaded" notice incorrectly appearing on every page/request; it is now only shown where the logo is actually rendered.
* Fixed a fatal error (`TypeError` in `WP_List_Table::row_actions()`) on the Plugins page caused by the "Settings" action link filter.
* Fixed the Media Library upload button being broken on other admin screens due to an unnecessary, globally-loaded Thickbox stylesheet.
* Fixed the logo image field silently storing the literal placeholder text "Select Logo" as its value when no logo was chosen.
* Fixed the "Save Settings" button showing on the Import/Export tab, where it didn't apply.

= 1.0.3 =
Release Date: August 14th, 2026

Updates:

* Resolved all PHPCS (WordPress Coding Standards) reported issues across the codebase.
* Escaped all admin and front-end output, and added a nonce-safe check around the settings-saved notice.
* Fixed the `[csl_display_logo]` shortcode to correctly return its markup instead of echoing it directly.
* Added an `alt` attribute to the rendered logo image for better accessibility.
* Clarified and improved the wording of settings page labels, descriptions, and messages.
* Corrected a translation domain mismatch on the plugin's "Settings" action link.
* Updated "Tested up to" to the latest WordPress version.

= 1.0.2 =
Release Date: May 09th, 2023

Bugfixes:

* Fixes PHP Function.

= 1.0.1 =
Release Date: May 25th, 2023

Bugfixes:

* Fixes PHP warnings.

Updates:

Update the code standards.

== Upgrade Notice ==

= 1.2.0 =
Major feature release: block, widget, retina/dark-mode/mobile logos, SVG uploads, per-page overrides, Customizer live preview, export/import, a tabbed and redesigned settings page, and a built-in Logo Maker.
