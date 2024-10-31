<?php

/**
 * The admin-specific functionality for Plugins Stats Dashboard.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Plugins_Stats_Dashboard
 * @subpackage Plugins_Stats_Dashboard/admin
 */

/**
 * Admin-specific functionality.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugins_Stats_Dashboard
 * @subpackage Plugins_Stats_Dashboard/admin
 * @author     David Baumwald <david.baumwald@gmail.com>
 */
class Plugins_Stats_Dashboard_Admin {
	/**
	 * Plugin options.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
	private $options;

	/**
	 * Settings sections.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$settings_sections
	 */
	private $settings_sections;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->load_options();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		$screen = get_current_screen();

		if ( 'dashboard' === $screen->id ) {
			wp_enqueue_style( 'de-plugins-stats-dashboard-dashboard', plugin_dir_url( __FILE__ ) . 'assets/dist/dashboard/de-plugins-stats-dashboard.min.css', array( 'wp-components' ), $this->version, 'all' );
		} elseif( 'settings_page_de_plugins_stats_dashboard' === $screen->id ) {
			wp_enqueue_style( 'de-plugins-stats-dashboard-settings', plugin_dir_url( __FILE__ ) . 'assets/dist/settings/de-plugins-stats-dashboard.min.css', array( 'wp-components' ), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();

		$localization = array(
			'AJAX_URL' => admin_url( 'admin-ajax.php' ),
			'L10N'     => array(
				'ERROR_LOADING_STATS'   => __( 'Error loading stats via AJAX!', $this->plugin_name ),
				'UPDATE_FREQUENCY_TEXT' => $this->update_frequency_text(),
			),
			'NONCES'   => array(
				'DASHBOARD_AJAX' => wp_create_nonce( $this->plugin_name.'-dashboard' ),
			),
			'DASHBOARD' => array(
				'STAT_SELECT_OPTIONS' => $this->current_stat_select_options(),
				'CURRENT_STAT'        => $this->get_selected_stat(),
				'AUTHOR'              => $this->options[ "{$this->plugin_name}_author" ],
			),
			'SETTINGS' => array(
				'UPDATE_FREQUENCY_OPTIONS' => $this->update_frequency_select_options(),
			),
		);

		if ( 'dashboard' === $screen->id ) {
			wp_register_script( 'de-plugins-stats-dashboard-dashboard', plugin_dir_url( __FILE__ ) . 'assets/dist/dashboard/de-plugins-stats-dashboard.min.js', array( 'wp-api', 'wp-i18n', 'wp-components', 'wp-element', 'wp-html-entities' ), $this->version, true );

			wp_localize_script( 'de-plugins-stats-dashboard-dashboard', 'DE_PLUGINS_STATS_DASHBOARD', $localization );

			wp_enqueue_script( 'de-plugins-stats-dashboard-dashboard' );
		} elseif( 'settings_page_de_plugins_stats_dashboard' === $screen->id ) {
			wp_register_script( 'de-plugins-stats-dashboard-settings', plugin_dir_url( __FILE__ ) . 'assets/dist/settings/de-plugins-stats-dashboard.min.js', array( 'wp-api', 'wp-i18n', 'wp-components', 'wp-element' ), $this->version, true );

			wp_localize_script( 'de-plugins-stats-dashboard-settings', 'DE_PLUGINS_STATS_DASHBOARD', $localization );

			wp_enqueue_script( 'de-plugins-stats-dashboard-settings' );
		}
	}

	/**
	 * Sets the class variable $options
	 *
	 * @since  1.0.0
	 * @return void
	 */
	private function load_options() {
		$keys = array(
			'author',
			'update_frequency',
		);

		foreach ( $keys as $key ) {
			$this->options[ "{$this->plugin_name}_{$key}" ] = get_option( "{$this->plugin_name}_{$key}" );
		}
	}

	/**
	 * Reset the cached results transient if we've updated the author
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function update_option_author() {
		delete_transient( 'de_plugins_stats_dashboard-results' );
	}

	/**
	 * Get the selected stat
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_selected_stat() {
		if ( false === ( $current_stat = get_transient( "{$this->plugin_name}-current-stat" ) ) ) {
			$current_stat = 'downloaded';

			set_transient( "{$this->plugin_name}-current-stat", $current_stat, YEAR_IN_SECONDS );
		}

		return $current_stat;
	}

	/**
	 * AJAX action for the getting plugin stats data
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function plugin_stats_dashboard_ajax() {
		if ( empty( $_POST ) || ! check_ajax_referer( "{$this->plugin_name}-dashboard", 'security' ) ) {
			wp_die( __( 'Missing data.', $this->plugin_name ) );
		}

		if ( false === ( $plugin_info = get_transient( "{$this->plugin_name}-results" ) ) ) {
			$args = (object) array(
				'author' => $this->options[ "{$this->plugin_name}_author" ],
				'fields' => array(
					'downloaded'      => true,
					'active_installs' => true,
				),
			);

		    $request = array(
				'action'  => 'query_plugins',
				'timeout' => 15,
				'request' => serialize( $args ),
			);

		    $url = 'http://api.wordpress.org/plugins/info/1.0/';

		    $response = wp_remote_post( $url, array( 'body' => $request ) );

		    $plugin_info = unserialize( $response['body'] );

		    set_transient( "{$this->plugin_name}-results", $plugin_info, $this->results_transient_length() );
		 }

		 wp_send_json( $plugin_info );
	}

	/**
	 * Add the dashboard widget.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function add_dashboard_widget() {
		wp_add_dashboard_widget( 'plugin-stats-dashboard', sprintf( __( 'Plugin Stats for %s', $this->plugin_name ), $this->options[ "{$this->plugin_name}_author" ] ), array( $this, 'display_dashboard_widget' ) );
	}

	/**
	 * Display the dashboard widget.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function display_dashboard_widget() {
		echo '<div id="de_plugins_stats_dashboard"></div>';
	}

	/**
	 * Adds a "Settings" link to the plugins page
	 *
	 * @since  1.0.3
	 * @param  array  $links  The current array of links
	 * @return array  The modified array of links
	 */
	function add_plugin_action_links( $links ) {
		$links[] = sprintf(
			__( '<a href="%s">Settings</a>', $this->plugin_name ),
			esc_url( menu_page_url( $this->plugin_name, false ) )
		);

		return $links;
	}

	/**
	 * Adds a settings page link to a menu
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function add_settings_page_to_menu() {
		add_options_page(
			__( 'Plugins Stats Dashboard', $this->plugin_name ),
			__( 'Plugins Stats Dashboard', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'option_menu_callback' )
		);
	}

	/**
	 * Admin menu item callback
	 *
	 * @since  2.0.0
	 * @return void
	 */
	function option_menu_callback() {
		echo '<div id="de_plugins_stats_dashboard"></div>';
	}

	/**
	 * Admin menu item callback
	 *
	 * @since  2.0.0
	 * @return void
	 */
	function register_settings() {
		register_setting(
			"{$this->plugin_name}_settings",
			"{$this->plugin_name}_author",
			array(
				'type'         => 'string',
				'show_in_rest' => true,
				'default'      => false,
			)
		);

		register_setting(
			"{$this->plugin_name}_settings",
			"{$this->plugin_name}_update_frequency",
			array(
				'type'         => 'string',
				'show_in_rest' => true,
				'default'      => false,
			)
		);
	}

	/**
	 * Stat selection options, filterable
	 *
	 * @since  1.0.0
	 * @return array
	 */
	protected function current_stat_select_options() {
		$options_array = array(
			array(
				'value' => 'downloaded',
				'label' => __( 'Downloads', $this->plugin_name ),
			),
			array(
				'value' => 'active_installs',
				'label' => __( 'Active Installs', $this->plugin_name ),
			),
			array(
				'value' => 'version',
				'label' => __( 'Version', $this->plugin_name ),
			),
		);

		return apply_filters( "de/{$this->plugin_name}/current_stat_select_options", $options_array );
	}

	/**
	 * Update frequency select options, filterable
	 *
	 * @since  1.0.0
	 * @return array
	 */
	protected function update_frequency_select_options() {
		$options_array = array(
			array(
				'value' => '5m',
				'label' => __( '5 Minutes', $this->plugin_name ),
			),
			array(
				'value' => '15m',
				'label' => __( '15 Minutes', $this->plugin_name ),
			),
			array(
				'value' => '30m',
				'label' => __( '30 Minutes', $this->plugin_name ),
			),
			array(
				'value' => '1h',
				'label' => __( '1 Hour', $this->plugin_name ),
			),
			array(
				'value' => '6h',
				'label' => __( '6 Hours', $this->plugin_name ),
			),
			array(
				'value' => '12h',
				'label' => __( '12 Hours', $this->plugin_name ),
			),
			array(
				'value' => '24h',
				'label' => __( '24 Hours', $this->plugin_name ),
			),
			array(
				'value' => '7d',
				'label' => __( '7 Days', $this->plugin_name ),
			),
		);

		return apply_filters( "de/{$this->plugin_name}/update_frequency_select_options", $options_array );
	}

	/**
	 * Update frequency display string, filterable
	 *
	 * @since  1.0.0
	 * @return string
	 */
	protected function update_frequency_text() {
		preg_match( "/^(\d+)(m|h|d)$/i", $this->options[ "{$this->plugin_name}_update_frequency" ], $matches );

		$update_frequency_text = __( '*Updated every ', $this->plugin_name );

		switch ( $matches[2] ) {
			case 'm':
				$update_frequency_text .= sprintf( _n( 'minute', '%d minutes', $matches[1], $this->plugin_name ), $matches[1] );
				break;
			case 'h':
				$update_frequency_text .= sprintf( _n( 'hour', '%d hours', $matches[1], $this->plugin_name ), $matches[1] );
				break;
			case 'd':
				$update_frequency_text .= sprintf( _n( 'day', '%d days', $matches[1], $this->plugin_name ), $matches[1] );
				break;
		}

		return apply_filters( "de/{$this->plugin_name}/update_frequency_text", $update_frequency_text );
	}

	/**
	 * Transient lifetime, filterable
	 *
	 * @since  1.0.0
	 * @return int  Length, in seconds
	 */
	protected function results_transient_length() {
		preg_match( "/^(\d+)(m|h|d)$/i", $this->options[ "{$this->plugin_name}_update_frequency" ], $matches );

		switch ( $matches[2] ) {
			case 'm':
				$results_transient_length = ( MINUTE_IN_SECONDS * absint( $matches[1] ) );
				break;
			case 'h':
				$results_transient_length = ( HOUR_IN_SECONDS * absint($matches[1] ) );
				break;
			case 'd':
				$results_transient_length = ( DAY_IN_SECONDS * absint($matches[1] ) );
				break;
		}

		return apply_filters( "de/{$this->plugin_name}/results_transient_length", $results_transient_length );
	}
}
