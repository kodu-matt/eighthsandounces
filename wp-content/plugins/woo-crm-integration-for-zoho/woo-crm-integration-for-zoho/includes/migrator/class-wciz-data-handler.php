<?php
/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes/migrator
 */

/**
 * Fired during plugin migration.
 *
 * This class defines all code necessary to run during the plugin's migration.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes/migrator
 */
class WCIZ_Data_Handler {

	/**
	 * Define the core functionality of the migrator.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Replacement for WordPress native get option.
	 * Serves for new meta keys only.
	 *
	 * @param string $option_name   The option name.
	 * @param string $def_value     The default option value.
	 *
	 * @since     1.0.0
	 */
	public static function get_option( $option_name = false, $def_value = false ) {

		if ( empty( $option_name ) ) {
			return false;
		}

		// Key doesn't contains wciz as new key.
		if ( false !== strpos( 'wciz', $option_name ) ) {
			return get_option( $option_name, $def_value );
		}

		// Just in case WCIZ key exists.
		$option_value = get_option( $option_name );
		if ( ! empty( $option_value ) ) {
			return $option_value;
		}

		// WCIZ not found! Fetch from OLD saved key.
		if ( empty( $option_value ) ) {

			// prepare same key as old one.
			$mwb_option_name  = str_replace( 'wciz', 'mwb', $option_name );
			$mwb_option_value = get_option( $mwb_option_name );

			// Update the same value to wciz key.
			if ( ! empty( $mwb_option_value ) ) {
				update_option( $option_name, $mwb_option_value );
				delete_option( $mwb_option_name );
			}

			// return saved value.
			return get_option( $option_name, $def_value );
		}
	}

	/**
	 * Replacement for WordPress native delete option.
	 *
	 * @param string $option_name   The option name.
	 *
	 * @since     1.0.0
	 */
	public static function delete_option( $option_name = false ) {

		if ( empty( $option_name ) ) {
			return false;
		}

		$mwb_option_name = str_replace( 'wciz', 'mwb', $option_name );

		delete_option( $option_name );
		delete_option( $mwb_option_name );
	}

	/**
	 * Replacement for WordPress native get post meta.
	 * Serves for new meta keys only.
	 *
	 * @param string  $post_id   The post id.
	 * @param string  $meta_key  The post meta key.
	 * @param boolean $single  return a single value.
	 *
	 * @since     1.0.0
	 */
	public static function get_post_meta( $post_id = false, $meta_key = false, $single = false ) {

		if ( empty( $meta_key ) ) {
			return false;
		}

		// Key doesn't contains wciz as new key.
		if ( false !== strpos( 'wciz', $meta_key ) ) {
			return get_post_meta( $post_id, $meta_key, $single );
		}

		// Just in case WCIZ key exists.
		$meta_value = get_post_meta( $post_id, $meta_key, $single );
		if ( ! empty( $meta_value ) ) {
			return $meta_value;
		}

		// WCIZ not found! Fetch from OLD saved key.
		if ( empty( $meta_value ) ) {

			// prepare same key as old.
			$mwb_meta_key   = str_replace( 'wciz', 'mwb', $meta_key );
			$mwb_meta_value = get_post_meta( $post_id, $mwb_meta_key, $single );

			// Update the same value to wciz key.
			if ( ! empty( $mwb_meta_value ) ) {
				update_post_meta( $post_id, $meta_key, $mwb_meta_value );
				delete_post_meta( $post_id, $mwb_meta_key );
			}

			// return saved value.
			return get_post_meta( $post_id, $meta_key, $single );
		}
	}

	/**
	 * Replacement for WordPress native get post meta.
	 * Serves for new meta keys only.
	 *
	 * @param string  $user_id   The user id.
	 * @param string  $meta_key  The user meta key.
	 * @param boolean $single  return a single value.
	 *
	 * @since     1.0.0
	 */
	public static function get_user_meta( $user_id = false, $meta_key = false, $single = false ) {

		if ( empty( $meta_key ) ) {
			return false;
		}

		// Key doesn't contains wciz as new key.
		if ( false !== strpos( 'wciz', $meta_key ) ) {
			return get_user_meta( $user_id, $meta_key, $single );
		}

		// Just in case WCIZ key exists.
		$meta_value = get_user_meta( $user_id, $meta_key, $single );
		if ( ! empty( $meta_value ) ) {
			return $meta_value;
		}

		// WCIZ not found! Fetch from OLD saved key.
		if ( empty( $meta_value ) ) {

			// prepare same key as old.
			$mwb_meta_key   = str_replace( 'wciz', 'mwb', $meta_key );
			$mwb_meta_value = get_user_meta( $user_id, $mwb_meta_key, $single );

			// Update the same value to wciz key.
			if ( ! empty( $mwb_meta_value ) ) {
				update_user_meta( $user_id, $meta_key, $mwb_meta_value );
				delete_user_meta( $user_id, $mwb_meta_key );
			}

			// return saved value.
			return get_user_meta( $user_id, $meta_key, $single );
		}
	}

	/**
	 * Replacement for tables to WCIZ.
	 *
	 * @since     1.0.0
	 */
	public function migrate_tables() {

		global $wpdb;
		$crm_log_table = $wpdb->prefix . 'mwb_woo_zoho_log';

		// If exists true.
		if ( 'exists' === wciz_woo_crm_log_table_exists( $crm_log_table ) ) {
			$sql = 'ALTER TABLE ' . $wpdb->prefix . 'mwb_woo_zoho_log RENAME TO ' . $wpdb->prefix . 'wciz_woo_zoho_log';
			wciz_woo_zoho_execute_db_query( $sql );
		}
	}

	/**
	 * Replacement for Feeds to WCIZ.
	 *
	 * @since     1.0.0
	 */
	public function migrate_feeds() {
		$all_feeds = get_posts(
			array(
				'post_type'      => 'mwb_crm_feed',
				'post_status'    => array( 'publish', 'draft' ),
				'fields'         => 'ids',
				'posts_per_page' => -1,
			)
		);

		if ( ! empty( $all_feeds ) && is_array( $all_feeds ) ) {
			foreach ( $all_feeds as $key => $feed_id ) {
				$args = array(
					'ID'        => $feed_id,
					'post_type' => 'wciz_crm_feed',
				);
				wp_update_post( $args );
			}
		}
	}

	// End of class.
}
