<?php
/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */
class Woo_Crm_Integration_For_Zoho_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Create log table in database.
		self::may_be_create_log_table();

		// Schedule clear log event.
		self::add_scheduled_event();
	}


	/**
	 * Schedule clear log event.
	 */
	public static function add_scheduled_event() {
		if ( ! wp_next_scheduled( 'wciz_woo_zoho_clear_log' ) ) {
			wp_schedule_event( time(), 'daily', 'wciz_woo_zoho_clear_log' );
		}
	}

	/**
	 * Create log table in database.
	 *
	 * @return bool
	 */
	public static function may_be_create_log_table() {

		if ( get_option( 'wciz_woo_zoho_log_table_created', false ) ) {
			return false;
		}
		require_once plugin_dir_path( __DIR__ ) . 'woo-includes/woo-functions.php';
		global $wpdb;
		$table = $wpdb->prefix . 'wciz_woo_zoho_log';
		$query = "CREATE TABLE IF NOT EXISTS $table (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `woo_object` varchar(255) NOT NULL,
            `woo_id` int(11) NOT NULL,
            `zoho_object` varchar(255) NOT NULL,
            `zoho_id` varchar(255) NOT NULL,
            `event` varchar(255) NOT NULL,
            `request` text NOT NULL,
            `response` text NOT NULL,
            `time` int(11) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		  
		wciz_woo_zoho_execute_db_query( $query );
		update_option( 'wciz_woo_zoho_log_table_created', true );
		return true;
	}
}
