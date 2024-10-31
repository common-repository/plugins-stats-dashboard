<?php
/**
 * Dashboard view for the plugin
 *
 * Mostly HTML for the display fo the dashboard widget.
 *
 * @link       https://dream-encode.com
 * @since      1.0.0
 *
 * @package    Plugins_Stats_Dashboard
 * @subpackage Plugins_Stats_Dashboard/admin/partials
 */
?>

<div id="plugins-stats-dashboard-progress-bar"></div>

<?php echo do_action( 'de/'.$this->plugin_name.'/dashboard_widget_before_plugins_table' ); ?>

<table class="plugin-stats-dashboard-list-table">
	<thead>
		<tr>
			<th align="left"><?php _e( 'Plugin', $this->plugin_name ); ?></th>
			<th align="right"><select name="<?php echo esc_attr( $this->plugin_name ); ?>-stat-option" id="<?php echo esc_attr( $this->plugin_name ); ?>-stat-option"><?php echo $this->select_choices( $this->current_stat_select_options(), get_transient( $this->plugin_name.'-current-stat' ) ); ?></select></th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<?php echo do_action( 'de/'.$this->plugin_name.'/dashboard_widget_after_plugins_table' ); ?>

<?php echo do_action( 'de/'.$this->plugin_name.'/dashboard_widget_before_update_frequency_text' ); ?>

<p class="plugin-stats-dashboard-updated-text"><?php echo $this->update_frequency_text(); ?></p>

<?php echo do_action( 'de/'.$this->plugin_name.'/dashboard_widget_after_update_frequency_text' ); ?>