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

<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

<form method="post" action="options.php">

	<?php settings_fields( $this->plugin_name ); ?>

	<?php do_settings_sections( $this->plugin_name ); ?>

	<?php submit_button( __( 'Save Settings', $this->plugin_name ) ); ?>

</form>