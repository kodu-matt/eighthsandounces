<?php
/**
 * Global functions of plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/woo-includes
 */

/**
 * Execute wpdb query.
 *
 * @param  string $query Query to be executed.
 */
function wciz_woo_zoho_execute_db_query( $query ) {
	global $wpdb;
	$wpdb->query( $query ); // @codingStandardsIgnoreLine.
}

/**
 * Get zoho log data from database.
 *
 * @param  string|boolean $search_value Search value.
 * @param  integer        $limit        Max limit of data.
 * @param  integer        $offset       Offest to start.
 * @param  boolean        $all          Return all results.
 * @return array                        Array of data.
 */
function wciz_woo_zoho_get_log_data( $search_value = false, $limit = 25, $offset = 0, $all = false ) {

	global $wpdb;
	$table_name = $wpdb->prefix . 'wciz_woo_zoho_log';

	$log_data = array();

	if ( $all ) {

		$log_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} ORDER BY `id` DESC" ), ARRAY_A ); // @codingStandardsIgnoreLine.
		return $log_data;

	}

	if ( ! $search_value ) {

		$log_data    = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} ORDER BY `id` DESC LIMIT %d OFFSET %d ", $limit, $offset ), ARRAY_A ); // @codingStandardsIgnoreLine.
		return $log_data;

	}

	$log_data    = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE `woo_id` = %d LIMIT %d OFFSET %d ", $search_value, $limit, $offset ), ARRAY_A ); // @codingStandardsIgnoreLine.

	return $log_data;
}

/**
 * Get zoho column log data from database.
 *
 * @param  string|boolean $search_value Search value.
 * @param  integer        $limit        Max limit of data.
 * @param  integer        $offset       Offest to start.
 * @param  boolean        $all          Return all results.
 * @return array                        Array of data.
 */
function wciz_woo_zoho_get_column_data( $query ) {

	global $wpdb;

	$log_data = $wpdb->get_col( $query ); // @codingStandardsIgnoreLine.
	return $log_data;

}

/**
 * Get zoho error data from database.
 *
 * @param  string|boolean $filter_value filer value.
 * @param  integer        $limit        Max limit of data.
 * @param  integer        $offset       Offest to start.
 * @param  boolean        $all          Return all results.
 * @return array                        Array of data.
 */
function wciz_woo_zoho_get_error_data( $filter_value = false, $limit = 25, $offset = 0, $all = false ) {

	global $wpdb;
	$table_name = $wpdb->prefix . 'wciz_woo_zoho_log';

	$log_data = array();

	if ( $all ) {

		$log_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} ORDER BY `id` DESC" ), ARRAY_A ); // @codingStandardsIgnoreLine.
		return $log_data;

	}

	if ( 'error' === $filter_value ) {
		$log_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE `zoho_id` = '-' OR  `zoho_id` = '' LIMIT %d OFFSET %d ", $limit, $offset ), ARRAY_A ); // @codingStandardsIgnoreLine.
	} else {
		$log_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} ORDER BY `id` DESC LIMIT %d OFFSET %d ", $limit, $offset ), ARRAY_A ); // @codingStandardsIgnoreLine.
	}
	return $log_data;
}

/**
 * Get total count from log table.
 *
 * @param  string $search_query Search query.
 * @return integer Total count.
 */
function wciz_woo_zoho_get_total_log_count( $search_query ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'wciz_woo_zoho_log';
	$count      = $wpdb->get_col( "SELECT COUNT(*) as `total_count` FROM {$table_name} WHERE 1 {$search_query} " ); // @codingStandardsIgnoreLine.
	return $count;
}

/**
 * Get query results from database
 *
 * @param  string $query Query to be executed.
 * @return array         Result data.
 */
function wciz_woo_zoho_get_query_results( $query ) {
	global $wpdb;
	$result = $wpdb->get_results( $query, ARRAY_A ); // @codingStandardsIgnoreLine.
	return $result;
}

/**
 * Get total count from log table.
 *
 * @return integer Total count.
 */
function wciz_woo_zoho_get_total_shop_order_count() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'posts';

	$sql      = "SELECT COUNT(`ID`) FROM {$table_name} WHERE ( `post_type` = 'shop_order' )";
	$log_data = $wpdb->get_results($sql, ARRAY_A ); // @codingStandardsIgnoreLine.

	foreach ( $log_data as $key => $value ) {
		$count = $value['COUNT(`ID`)'];
		break;
	}

	return is_numeric( $count ) ? $count : 0;
}

/**
 * Get total count from log table.
 *
 * @return integer Total count.
 */
function wciz_woo_zoho_get_total_product_count() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'posts';

	$sql      = "SELECT COUNT(`ID`) FROM {$table_name} WHERE ( `post_type` = 'product' )";
	$log_data = $wpdb->get_results($sql, ARRAY_A ); // @codingStandardsIgnoreLine.
	foreach ( $log_data as $key => $value ) {
		$count = $value['COUNT(`ID`)'];
		break;
	}

	return is_numeric( $count ) ? $count : 0;
}

/**
 * Check for all the options saved via current crm plugin.
 *
 * @return array
 */
function wciz_woo_crm_saved_feed_meta() {

	global $wpdb;
	$crm_module_name = 'Woo_Crm_Integration_For_Zoho_Fw';
	$crm_module      = $crm_module_name::get_instance();
	$all_feeds       = $crm_module->get_all_feeds();
	$sql_query = '';
	foreach ( $all_feeds as $key => $feed_id ) {
		$feed_key = '%wciz_zoho_feed_' . $feed_id . '_association%';
		if ( $key === ( count( $all_feeds ) - 1 ) ) {
			$sql_query .= $wpdb->prepare("`meta_key` LIKE %s ", $feed_key);
		} else {
			$sql_query .= $wpdb->prepare("`meta_key` LIKE %s or ", $feed_key);
		}
	}

	$metaa_key = 'wciz_zoho_allow_background_syncing';
	return $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}postmeta WHERE `meta_key` LIKE %s or %s ", $metaa_key, $sql_query
		), ARRAY_A );
}

/**
 * Check for all the options saved via current crm plugin.
 *
 * @return array
 */
function wciz_woo_crm_saved_feed_usermeta() {

	global $wpdb;
	$crm_module_name = 'Woo_Crm_Integration_For_Zoho_Fw';
	$crm_module      = $crm_module_name::get_instance();
	$all_feeds       = $crm_module->get_all_feeds();
	$sql_query = '';
	foreach ( $all_feeds as $key => $feed_id ) {
		$feed_key = '%wciz_zoho_feed_' . $feed_id . '_association%';
		if ( $key === ( count( $all_feeds ) - 1 ) ) {
			$sql_query .= $wpdb->prepare("`meta_key` LIKE %s ", $feed_key);
		} else {
			$sql_query .= $wpdb->prepare("`meta_key` LIKE %s or ", $feed_key);
		}
	}
	$metaa_key = 'wciz_zoho_allow_background_syncing';
	return $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}usermeta WHERE `meta_key` LIKE %s or %s ", $metaa_key, $sql_query
		), ARRAY_A );
}

/**
 * Check for all the options saved via current crm plugin.
 *
 * @return array
 */
function wciz_woo_crm_saved_options() {

	global $wpdb;
	$crm = '%zoho%';
	$wciz = '%wciz%';
	return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}options WHERE `option_name` LIKE %s AND `option_name` LIKE %s LIMIT 50 ", $crm, $wciz ), ARRAY_A );
}

/**
 * Create unique nonce for authorization.
 *
 * @return string unique nonce.
 */
function wciz_woo_zoho_create_nonce() {
	$nonce = wp_create_nonce( 'wciz_zoho_auth_nonce' );
	return $nonce;
}

/**
 * Verify unique nonce.
 *
 * @param  string $nonce Nonce string to be verified.
 * @return bool .
 */
function wciz_woo_zoho_verify_nonce( $nonce ) {

	// Get the nonce from url .
	if ( false !== filter_var( $nonce, FILTER_VALIDATE_URL ) ) {

		$queries = array();
		$nonce   = str_replace( rtrim( admin_url(), '/' ) . '?', '', $nonce );
		parse_str( $nonce, $queries );
		$nonce = ! empty( $queries['mwb_nonce'] ) ? $queries['mwb_nonce'] : '';
	}

	if ( ! wp_verify_nonce( $nonce, 'wciz_zoho_auth_nonce' ) ) {
		return false;
	}

	return true;
}

/**
 * Execute wpdb query.
 *
 * @param  int $timestamp delete timestamp.
 */
function wciz_woo_zoho_clear_log_data( $timestamp ) {
	global $wpdb;
	$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wciz_woo_zoho_log WHERE `time` < %d", $timestamp ) );
}

/**
 * Check if table exists.
 *
 * @param  string $table_name Table name to be checked.
 */
function wciz_woo_crm_log_table_exists( $table_name ) {
	global $wpdb;
	if ( $wpdb->get_var( $wpdb->prepare( "show tables like %s", $table_name ) ) === $table_name ) {
		return 'exists';
	} else {
		return false;
	}
}

/**
 * Clear guest abandoned carts
 *
 * @since 1.0.0
 */
function wciz_zoho_clear_cart() {

	global $wpdb;
	$table_name = $wpdb->prefix . 'options';
	$query = "DELETE FROM `$table_name` WHERE `option_name` LIKE '%zoho_cart_data_%'";
	$guest_abdn_carts = wciz_woo_zoho_get_query_results( $query );

	esc_url( wp_safe_redirect( admin_url( 'admin.php?page=woo-crm-integration-for-zoho#wciz-abandoned-carts' ) ) );

	exit();
}

function wciz_zoho_abundant_guests() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'options';

	$query = "SELECT * FROM `$table_name` WHERE `option_name` LIKE '%zoho_cart_data_%'";

	$guest_abdn_carts = wciz_woo_zoho_get_query_results( $query );

	if ( !empty( $guest_abdn_carts ) ) {
		foreach ( $guest_abdn_carts as $abdn_key => $abdn_value ) {
			if ( !empty( $abdn_value['option_value'] ) ) {
				$data = unserialize( $abdn_value['option_value'] );
				$sync_status = isset( $data['sync_status'] ) ? $data['sync_status'] : '';
				$current_time = time();

				$zoho_abundant_delete = (int) get_option( 'wciz_zoho_delete_data', 1 );

				if ( ! empty( $sync_status ) && 'not_synced' !== $sync_status ) {

					$sync_time = $sync_status;
		
					$abundant_delete_time = $sync_time + $zoho_abundant_delete * 24 * 60 * 60;
		
					if ( $current_time > $abundant_delete_time ) {
		
						delete_option( 'zoho_cart_data_' . $data['cart_email'] );
		
					}
		
				}
			}
		}
	}

	$guest_abdn_carts = wciz_woo_zoho_get_query_results( $query );

	return $guest_abdn_carts;
}

/**
 * Get all users with abandoned cart
 */
function zoho_abundant_users() {

	$args['meta_query'] = array(

		array(
			'key'       => 'zoho_user_left_cart',
			'value'     => 'yes',
			'compare'   => '==',
		),
	);

	$zoho_abundant_users = get_users( $args );
	return $zoho_abundant_users;
}

function wciz_set_abandoned_cart_sync_status( $cart_email, $user_type, $user_id ) {

	if ( 'user' === $user_type ) {

		update_user_meta( $user_id, 'zoho_abdn_sync_status', time() );

	} elseif ( 'guest' === $user_type ) {

		if ( !empty( $cart_email ) ) {

			$options_data = get_option( 'zoho_cart_data_' . $cart_email, array() );

			if ( ! empty( $options_data ) ) {
			
				$options_data['sync_status'] = time();

				update_option( 'zoho_cart_data_' . $cart_email, $options_data );
			}
		}
	}
}


	/**
	 * Get cart table data in html format
	 */
	function wciz_zoho_get_cart_table_html( $cart_products ) {

		$products_html = '<div class="" style="position: relative; left: 0px; top: 0px;" data-slot="separator"><hr></div><div data-slot="text"><table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody>
		<tr>
		<td class="content-spacing" style="border-bottom: 1px solid #f4f4f4;" width="20">
		<br>
		</td>
		<td style="border-bottom: 1px solid #f4f4f4;" width="225">
		<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
		<tbody>
		<tr>
		<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
		</tr>
		</tbody>
		</table>
		<div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left"><strong>Item</strong></div>
		<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
		<tbody>
		<tr>
		<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
		</tr>
		</tbody>
		</table>
		</td>
		<td class="content-spacing" style="border-bottom: 1px solid #f4f4f4;" width="20">
		<br>
		</td>
		<td style="border-bottom: 1px solid #f4f4f4;">
		<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
		<tbody>
		<tr>
		<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
		</tr>
		</tbody>
		</table>
		<div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left"><strong>Qty</strong></div>
		<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
		<tbody>
		<tr>
		<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
		</tr>
		</tbody>
		</table>
		</td>
		<td class="content-spacing" style="border-bottom: 1px solid #f4f4f4;" width="20">
		<br>
		</td>
		<td style="border-bottom: 1px solid #f4f4f4;" width="60">
		<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
		<tbody>
		<tr>
		<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
		</tr>
		</tbody>
		</table>
		<div class="text-center" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:center"><strong>Cost</strong></div>
		<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
		<tbody>
		<tr>
		<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
		</tr>
		</tbody>
		</table>
		</td>
		<td class="content-spacing" style="border-bottom: 1px solid #f4f4f4;" width="20">
		<br>
		</td>
		<td style="border-bottom: 1px solid #f4f4f4;" width="60">
		<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
		<tbody>
		<tr>
		<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
		</tr>
		</tbody>
		</table>
		<div class="text-center" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:center"><strong>Total</strong></div>
		<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
		<tbody>
		<tr>
		<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>';

		foreach ( $cart_products as $single_product ) {

			$products_html .= '<tr>
			<td>&nbsp;</td>
			<td>
			<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
			<tbody>
			<tr>
			<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
			</tr>
			</tbody>
			</table>
			<div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left"><a href="' . $single_product['url'] . '"><strong>' . $single_product['name'] . '</strong></a></div>
			<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
			<tbody>
			<tr>
			<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
			</tr>
			</tbody>
			</table>
			</td>



			<td>&nbsp;</td>
			<td>
			<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
			<tbody>
			<tr>
			<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
			</tr>
			</tbody>
			</table>
			<div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">' . $single_product['qty'] . '</div>
			<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
			<tbody>
			<tr>
			<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
			</tr>
			</tbody>
			</table>
			</td>
			<td>&nbsp;</td>

			<td>
			<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
			<tbody>
			<tr>
			<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
			</tr>
			</tbody>
			</table>
			<div class="text-center" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:center">' . wc_price( $single_product['price'], array( 'currency' => get_option( 'woocommerce_currency' ) ) ) . '</div>
			<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
			<tbody>
			<tr>
			<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
			</tr>
			</tbody>
			</table>
			</td>
			<td>&nbsp;</td>

			<td>
			<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
			<tbody>
			<tr>
			<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
			</tr>
			</tbody>
			</table>
			<div class="text-center" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:center">' . wc_price( $single_product['total'], array( 'currency' => get_option( 'woocommerce_currency' ) ) ) . '</div>
			<table border="0" cellpadding="0" cellspacing="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%" width="100%">
			<tbody>
			<tr>
			<td class="spacer" height="8" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td>
			</tr>
			</tbody>
			</table>
			</td>
			<td>&nbsp;</td>
			</tr>';
		}

		$products_html .= '</tbody></table></div><div class="" style="position: relative; left: 0px; top: 0px;" data-slot="separator"><hr></div>';

		$products_html = apply_filters( 'zoho_filter_abandoned_cart_product_html', $products_html, $cart_products );

		return $products_html;
	}