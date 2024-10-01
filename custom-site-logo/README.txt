=== Custom Site Logo ===
Contributors: iticiti
Tags: logo, center logo, logo management, effects, custom logo 
Requires at least: 3.0
Tested up to: 6.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows the end user to upload a new logo or use an existing image from your WordPress media gallery as a logo.

== Description ==

This plugin helps in managing your WordPress website’s logo.
You can easily upload a new logo, or use an existing image in your WP media gallery.

### Benefits, Features and Options:

* Apply custom width and height to logo.

* Applying hover effects to your logos.

* Make your logo responsive

* Use in post/page editor by shortcode or in template by function. Check the details below.

== How to use it ==

1. Open header.php file of your current theme.
2. Paste `<?php echo csl_CustomSiteLogo_show_logo(); ?>` where you want to display your logo.

== Shortcode == 

 Shortcode is also available to use anywhere:  `[csl_display_logo]`

In case of any difficulties in setting up your plugin, write an email to m.awaisaltaf@gmail.com

== Screenshots ==

1. This is the screenshot of main settings page of this plugin. You can customise the settings as per your choice.


== Frequently Asked Questions ==

= How to insert logo? =
Open header.php file of your current theme.
Paste `<?php echo csl_CustomSiteLogo_show_logo(); ?>` where you want to display your logo.

= Can I also insert logo picture anywhere using backend editor? =

Yes you can just use the shortcode [csl_display_logo] in any post/page/custom post editor to show the logo image.

= Why my logo is so big? =

If your logo appears to be bigger than it needs to be, make sure to uncheck the "Make Logo Responsive" option in settings

= Why my logo is so big? =

Try to uncheck the "Make Logo Responsive" option from settings page (Dashboard => Appearence => Custom Site Logo).

= Where is settings page in admin? =

Please go to Dashboard => Appearence => Custom Site Logo

== Changelog ==

= 1.0.1 =
Release Date: May 25th, 2023

Bugfixes:

* Fixes PHP warnings.

Updates:

Update the code standards.