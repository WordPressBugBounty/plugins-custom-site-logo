=== Custom Site Logo ===
Contributors: iticiti
Tags: logo, center logo, logo management, effects, custom logo
Requires at least: 3.0
Tested up to: 7.0
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Upload a custom logo, or reuse one from your media library, and display it anywhere on your site.

== Description ==

Custom Site Logo helps you manage your WordPress website's logo without editing any code.
Upload a new logo, or choose an existing image from your media library, then display it anywhere using a simple shortcode or template function.

### Benefits, Features and Options:

* Set a custom width and height for your logo.
* Apply a hover effect to your logo.
* Make your logo fully responsive.
* Center your logo, and optionally link it to a custom URL.
* Display the logo via a shortcode in any post or page, or via a function call in your theme template.

== How to use it ==

1. Open the `header.php` file of your current theme.
2. Paste `<?php echo csl_CustomSiteLogo_show_logo(); ?>` where you want the logo to appear.

== Shortcode ==

The shortcode `[csl_display_logo]` can be used anywhere posts, pages, or widgets support shortcodes.

If you run into any issues setting up the plugin, please reach out via the [support forum](https://wordpress.org/support/plugin/custom-site-logo/).

== Screenshots ==

1. The plugin's settings page. Customize your logo and its behaviour from here.

== Frequently Asked Questions ==

= How do I insert the logo? =

Open the `header.php` file of your current theme and paste `<?php echo csl_CustomSiteLogo_show_logo(); ?>` where you want the logo to appear.

= Can I insert the logo from the post/page editor? =

Yes, just use the shortcode `[csl_display_logo]` in any post, page, or custom post type editor to display the logo.

= Why does my logo look too big? =

Uncheck the "Make Logo Responsive" option on the settings page, or set a custom width and/or height for your logo.

= Where is the settings page? =

Go to Dashboard => Appearance => Custom Site Logo.

== Changelog ==

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
