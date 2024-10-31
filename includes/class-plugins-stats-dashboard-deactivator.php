<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Plugins_Stats_Dashboard
 * @subpackage Plugins_Stats_Dashboard/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Plugins_Stats_Dashboard
 * @subpackage Plugins_Stats_Dashboard/includes
 * @author     David Baumwald <david.baumwald@gmail.com>
 */
class Plugins_Stats_Dashboard_Deactivator {
	/**
	 * Deactivation hook.
	 *
	 * Remove options set by the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( 'de_plugins_stats_dashboard_author' );
		delete_option( 'de_plugins_stats_dashboard_update_frequency' );

		delete_transient( 'de_plugins_stats_dashboard-results' );
		delete_transient( 'de_plugins_stats_dashboard-current-stat' );
	}
}
