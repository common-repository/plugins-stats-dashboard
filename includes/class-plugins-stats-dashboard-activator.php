<?php

/**
 * Fired during plugin activation
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Plugins_Stats_Dashboard
 * @subpackage Plugins_Stats_Dashboard/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugins_Stats_Dashboard
 * @subpackage Plugins_Stats_Dashboard/includes
 * @author     David Baumwald <david.baumwald@gmail.com>
 */
class Plugins_Stats_Dashboard_Activator {
	/**
	 * Run activation to set some defaults.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option( 'de_plugins_stats_dashboard_author', 'matt' );
		add_option( 'de_plugins_stats_dashboard_update_frequency', '1h' );
	}
}
