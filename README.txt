=== Plugins Stats Dashboard ===
Contributors: davidbaumwald
Tags: plugin, plugins, stats, dashboard
Requires at least: 3.5
Tested up to: 6.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a dashboard widget that displays plugin statistics from the WordPress.org Plugin Repository for a given author.

== Description ==

This plugin adds a new widget to the admin dashboard that displays simple plugin stats from WordPress.org for a given author.  Stats like downloads, active installs, and current version are displayed for each plugin for the designated author.  For developers using WordPress who want to keep tabs on their own plugins frequently without visiting the Plugins Directory.

Features:

*   Select any WordPress.org developer username.
*   View total downloads, active installs, and current version.
*	Results are cached for a variable amount of time, set by the configuration.

== Installation ==

1. Upload the `plugins-stats-dashboard` folder to the plugins directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Update the settings by going to "Plugins Stats Dashboard" in the sidebar.

== Screenshots ==

1. The settings screen for updating default configuration settings.
2. Dashboard widget.

== Changelog ==

= 2.1.0 =
* ENH - Moving last viewed stat to browser storage, removing extra database transient.
* ENH - Move to functional React components under the hood.

= 2.0.2 =
* BUG - Cache JS and CSS.

= 2.0.1 =
* BUG - Use `number.toLocaleString` instead of regex for number formatting of downloads.  Regex caused error on iOS devices.
* ENH - Use wp.htmlEntities in place of third-party dep for decoding HTML entities.

= 2.0.0 =
* Major rewrite, using @wordpress components for dashboard widget and settings.
* Fix plugin action links,
* Updated I18N.

= 1.1.3 =
* Adding "+" to active installs count since the repository only counts them in batches of 10.
* Changing "0" to "Fewer than 10" for active installs count, mimicking the plugin repository.

= 1.1.2 =
* Updated NProgress
* Fixed bug where stats not shown
* Tested for 4.8

= 1.1.1 =
* Updating JS files in dist.

= 1.1 =
* Adding author setting to the dashboard widget title.
* Moving settings page under general settings
* Adding "Settings" link to plugins page
* Updated translation files
* Adding L10n for JS
* More strict sprintf text

= 1.0.2 =
* Resolving tagged version

= 1.0.1 =
* Resolving tagged version

= 1.0.0 =
* Initial release
