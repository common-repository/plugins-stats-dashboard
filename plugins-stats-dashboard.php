<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dream-encode.com
 * @since             1.0.0
 * @package           Plugins_Stats_Dashboard
 *
 * @wordpress-plugin
 * Plugin Name:       Plugins Stats Dashboard
 * Plugin URI:        https://dream-encode.com
 * Description:       Dashboard widget to display plugin stats from WordPress.org for a specific author.
 * Version:           2.1.0
 * Author:            David Baumwald
 * Author URI:        https://dream-encode.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugins-stats-dashboard
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DE_PLUGIN_STATS_DASHBOARD_VERSION', '2.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugins-stats-dashboard-activator.php
 */
function activate_plugins_stats_dashboard() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugins-stats-dashboard-activator.php';

	Plugins_Stats_Dashboard_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugins-stats-dashboard-deactivator.php
 */
function deactivate_plugins_stats_dashboard() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugins-stats-dashboard-deactivator.php';

	Plugins_Stats_Dashboard_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugins_stats_dashboard' );
register_deactivation_hook( __FILE__, 'deactivate_plugins_stats_dashboard' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugins-stats-dashboard.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugins_stats_dashboard() {
	$plugin = new Plugins_Stats_Dashboard();
	$plugin->run();
}

run_plugins_stats_dashboard();
