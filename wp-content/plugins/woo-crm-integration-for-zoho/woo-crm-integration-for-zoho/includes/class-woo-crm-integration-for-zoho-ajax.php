<?php
/**
 * The ajax-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */

 use Automattic\WooCommerce\Utilities\OrderUtil;
if ( ! class_exists( 'Woo_Crm_Integration_Connect_Framework' ) ) {
	wp_die( 'Woo_Crm_Integration_Connect_Framework does not exists.' );
}

/**
 * The ajax-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the ajax-specific stylesheet and JavaScript.
 *
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */
class Woo_Crm_Integration_For_Zoho_Ajax {

	/**
	 * Ajax Call back
	 */
	public function wciz_woo_zoho_ajax_cb() {
		check_ajax_referer( 'ajax_nonce', 'nonce' );
		$event = ! empty( $_POST['event'] ) ? sanitize_text_field( wp_unslash( $_POST['event'] ) ) : '';
		$data  = $this->$event( $_POST );
		echo wp_json_encode( $data );
		wp_die();
	}

	/**
	 * Get module fields for mapping.
	 *
	 * @param array $posted_data Posted data array.
	 * @return array             Module data.
	 */
	public function get_module_fields_for_mapping( $posted_data = array() ) {

		// Get zoho crm module fields.
		$module_data               = array();
		$module                    = ! empty( $posted_data['module'] ) ? sanitize_text_field( wp_unslash( $posted_data['module'] ) ) : '';
		$force                     = ! empty( $posted_data['force'] ) ? sanitize_text_field( wp_unslash( $posted_data['force'] ) ) : false;
		$crm_integration_zoho_api  = Woo_Crm_Integration_Zoho_Api::get_instance();
		$module_data['crm_fields'] = $crm_integration_zoho_api->get_module_fields( $module, $force );

		// Get default woo mapping.
		$module_data['default_mapping_data'] = Woo_Crm_Integration_For_Zoho_Cpt::get_default_mapping_data( $module );
		$module_data['default_event']        = Woo_Crm_Integration_For_Zoho_Cpt::get_default_event( $module );
		$module_data['default_primary']      = Woo_Crm_Integration_For_Zoho_Cpt::get_default_primary_field( $module );
		return $module_data;
	}

	/**
	 * Get default mapping data.
	 *
	 * @return array Status mapping array.
	 */
	public function get_default_status_mapping_data() {

		check_ajax_referer( 'ajax_nonce', 'nonce' );
		$module         = isset( $_POST['crm_selected_object'] ) ? sanitize_text_field( wp_unslash( $_POST['crm_selected_object'] ) ) : '';
		$field          = isset( $_POST['field'] ) ? sanitize_text_field( wp_unslash( $_POST['field'] ) ) : '';
		$status_mapping = array();
		if ( ! empty( $module ) && ! empty( $field ) ) {
			$status_mapping = $this->get_default_status_mapping( $module, $field );
		}
		return $status_mapping;
	}


	/**
	 * Get default status mapping.
	 *
	 * @param  string $module Module name.
	 * @param  string $field  Field name.
	 * @return array          Status mapping array.
	 */
	public function get_default_status_mapping( $module, $field ) {

		$status_mapping = array();
		if ( ! empty( $module ) && ! empty( $field ) ) {
			if ( 'Sales_Orders' === $module && 'Status' === $field ) {
				$order_statuses = wc_get_order_statuses();
				foreach ( $order_statuses as $key => $value ) {
					switch ( $key ) {
						case 'wc-pending':
							$status_mapping[ $key ] = 'Created';
							break;
						case 'wc-processing':
							$status_mapping[ $key ] = 'Approved';
							break;
						case 'wc-on-hold':
							$status_mapping[ $key ] = 'Created';
							break;
						case 'wc-completed':
							$status_mapping[ $key ] = 'Delivered';
							break;
						case 'wc-cancelled':
							$status_mapping[ $key ] = 'Cancelled';
							break;
						case 'wc-refunded':
							$status_mapping[ $key ] = 'Cancelled';
							break;
						case 'wc-failed':
							$status_mapping[ $key ] = 'Cancelled';
							break;
						default:
							$status_mapping[ $key ] = 'Created';
							break;
					}
				}
			}
		}
		return $status_mapping;
	}


	/**
	 * Get available fields in a module.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 * @return array $module_data data of specific module.
	 */
	public function get_module_fields( $posted_data = array() ) {

		$module_data              = array();
		$module                   = ! empty( $posted_data['module'] ) ? sanitize_text_field( wp_unslash( $posted_data['module'] ) ) : '';
		$force                    = ! empty( $posted_data['force'] ) ? sanitize_text_field( wp_unslash( $posted_data['force'] ) ) : false;
		$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
		$module_data              = $crm_integration_zoho_api->get_module_fields( $module, $force );
		return $module_data;
	}

	/**
	 * Get available modules in zoho.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 * @return array $module_data data of specific module.
	 */
	public function get_modules( $posted_data = array() ) {
		$module_data              = array();
		$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
		$force                    = isset( $posted_data['force'] ) ? $posted_data['force'] : false;
		return $crm_integration_zoho_api->get_modules_data( $force );
	}

	/**
	 * Get feed post.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 * @return array Response array.
	 */
	public function delete_feed_post( $posted_data = array() ) {

		$feed_id = isset( $posted_data['feed_id'] ) ? sanitize_text_field( wp_unslash( $posted_data['feed_id'] ) ) : false;
		if ( $feed_id ) {
			wp_trash_post( $feed_id );
		}
		return array( 'success' => true );
	}


	/**
	 * Change feed status.
	 *
	 * @param  array $posted_data Array of ajax posted data.
	 * @return array Response array.
	 */
	public function change_feed_status( $posted_data = array() ) {

		$feed_id = isset( $posted_data['feed_id'] ) ? sanitize_text_field( wp_unslash( $posted_data['feed_id'] ) ) : false;
		$status  = isset( $posted_data['status'] ) ? sanitize_text_field( wp_unslash( $posted_data['status'] ) ) : false;
		if ( $feed_id && $status ) {
			$args = array(
				'ID'          => $feed_id,
				'post_status' => $status,
			);
			wp_update_post( $args );
		}
		return array( 'success' => true );
	}

	/**
	 * Delete data on disconnecting data.
	 *
	 * @return array Response array.
	 */
	public function disconnect_account() {

		$wipe_allowed = Woo_Crm_Integration_For_Zoho_Admin::is_wipe_allowed();

		if ( true === $wipe_allowed ) {

			$saved_post_meta = wciz_woo_crm_saved_feed_meta();
			if ( ! empty( $saved_post_meta ) && is_array( $saved_post_meta ) ) {
				foreach ( $saved_post_meta as $key => $meta ) {
					delete_post_meta( $meta['post_id'], $meta['meta_key'] );
				}
			}

			$saved_user_meta = wciz_woo_crm_saved_feed_usermeta();
			if ( ! empty( $saved_user_meta ) && is_array( $saved_user_meta ) ) {
				foreach ( $saved_user_meta as $key => $meta ) {
					delete_user_meta( $meta['user_id'], $meta['meta_key'] );
				}
			}

			$crm_module_name = 'Woo_Crm_Integration_For_Zoho_Fw';
			$crm_module      = $crm_module_name::get_instance();
			$all_feeds       = $crm_module->get_all_feeds();

			if ( ! empty( $all_feeds ) && is_array( $all_feeds ) ) {
				foreach ( $all_feeds as $key => $feed_id ) {
					wp_delete_post( $feed_id );
				}
			}

			$saved_options = wciz_woo_crm_saved_options();

			if ( ! empty( $saved_options ) && is_array( $saved_options ) ) {

				foreach ( $saved_options as $key => $option ) {
					if ( ! empty( $option['option_name'] ) ) {
						delete_option( $option['option_name'] );
					}
				}
			}
		} else {

			$saved_options = array(
				'wciz_woo_zoho_onboarding_completed',
				'wciz_woo_zoho_authorised',
			);

			if ( ! empty( $saved_options ) && is_array( $saved_options ) ) {

				foreach ( $saved_options as $key => $option ) {
					if ( ! empty( $option ) ) {
						delete_option( $option );
					}
				}
			}
		}

		return array( 'success' => true );
	}

	/**
	 * Get reauthorization url.
	 *
	 * @return array Response array.
	 */
	public function get_reauthorize_url() {
		$response = array( 'success' => false );
		$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
		$auth_url = $zoho_api->get_auth_code_url();
		if ( ! $auth_url ) {
			$response['msg'] = __( 'Something went wrong! Check your credentials and authorize again', 'woo-crm-integration-for-zoho' );
			delete_option( 'wciz_woo_zoho_authorised', false );
			return $response;
		}
		$response = array(
			'success' => true,
			'url'     => $auth_url,
		);
		return $response;
	}

	/**
	 * Get datatable data.
	 */
	public function get_datatable_data_cb() {

		check_ajax_referer( 'ajax_nonce', 'nonce' );
		$request = $_GET;
		$offset  = $request['start'];
		$limit   = $request['length'];

		$search_data  = $request['search'];
		$search_value = $search_data['value'];

		// Custom error filter.
		$search_by_error = $request['searchByError'];

		$total_recordwith_filter = 0;

		$search_query = ' ';
		$count_data   = wciz_woo_zoho_get_total_log_count( $search_query );
		$total_count  = $count_data[0];
		$data         = array();

		if ( 'error' === $search_by_error ) {
			$search_query .= " and (zoho_id = '-' or zoho_id = '') ";
			$log_data      = wciz_woo_zoho_get_error_data( 'error', $limit, $offset );
		} elseif ( '' !== $search_value ) {
			$search_query .= " and (woo_id = $search_value) ";
			$log_data      = wciz_woo_zoho_get_log_data( $search_value, $limit, $offset );

		} else {

			$log_data = wciz_woo_zoho_get_log_data( false, $limit, $offset );
		}

		// Total number of records with filtering.
		$total_recordwith_filter = wciz_woo_zoho_get_total_log_count( $search_query );
		$zoho_api                = Woo_Crm_Integration_Zoho_Api::get_instance();
		foreach ( $log_data as $key => $value ) {
			if ( ! empty( $value['zoho_id'] ) ) {
				$data_href = $zoho_api->get_crm_link( $value['zoho_id'], $value['zoho_object'] );
				if ( ! empty( $data_href ) ) { // phpcs:ignore
					$link = '<a href="' . $data_href . '" target="_blank">' . $value['zoho_id'] . '</a>';
				} else {
					$link = $value['zoho_id'];
				}
			} else {
				$link = '-';
			}

			$temp   = array(
				'',
				$value['woo_id'],
				$value['event'],
				$value['woo_object'],
				$link,
				$value['zoho_object'],
				gmdate( 'd-m-Y h:i A', esc_html( $value['time'] ) ),
				wp_json_encode( json_decode( $value['request'], true ) ), //phpcs:ignore
				wp_json_encode( json_decode( $value['response'], true ) ), //phpcs:ignore
			);
			$data[] = $temp;
		}

		$json_data = array(
			'draw'            => intval( $request['draw'] ),
			'recordsTotal'    => $total_count,
			'recordsFiltered' => $total_recordwith_filter,
			'data'            => $data,
		);

		echo wp_json_encode( $json_data );
		wp_die();
	}

	/**
	 * Renew current access token.
	 *
	 * @return array Response array.
	 */
	public function renew_access_token() {
		$response        = array( 'success' => false );
		$zoho_api        = Woo_Crm_Integration_Zoho_Api::get_instance();
		$response['msg'] = __( 'Something went wrong! Check your credentials and authorize again', 'woo-crm-integration-for-zoho' );
		if ( $zoho_api->renew_access_token() ) {
			$token_expiry = Woo_Crm_Integration_For_Zoho_Admin::get_access_token_expiry();
			// translators: Token expiry time.
			$token_message = sprintf( __( 'The access token expires in %s minutes.', 'woo-crm-integration-for-zoho' ), $token_expiry );
			$response      = array(
				'success'       => true,
				'msg'           => __( 'Success', 'woo-crm-integration-for-zoho' ),
				'token_message' => $token_message,
			);
		}
		return $response;
	}

	/**
	 * Create default feeds.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 * @return array Feed response.
	 */
	public function create_default_feeds( $posted_data = array() ) {

		$success      = false;
		$message      = __( 'Something went wrong !', 'woo-crm-integration-for-zoho' );
		$cpt_instance = new Woo_Crm_Integration_For_Zoho_Cpt();
		$feeds        = $cpt_instance->get_default_feeds();
		$step         = isset( $posted_data['step'] ) ? sanitize_text_field( wp_unslash( $posted_data['step'] ) ) : '';
		$feed         = ! empty( $feeds[ $step ] ) ? $feeds[ $step ] : false;

		if ( is_array( $feed ) ) {

			$existing_feed = get_option( 'wciz_woo_zoho_default_' . $feed['crm_object'] . '_feed_id', false );

			$feed_id = $existing_feed;

			if ( false !== $existing_feed ) {

				$feed_id = $cpt_instance->is_valid_feed_post( $existing_feed );
			}

			if ( false === $feed_id ) {

				$feed_id = $cpt_instance->check_for_feed_post( $feed['crm_object'] );
			}

			if ( false === $feed_id ) {

				$feed_id = $cpt_instance->create_feed_post( $feed['title'], $feed['crm_object'] );

				if ( ! is_wp_error( $feed_id ) && false !== $feed_id ) {

					update_option( 'wciz_woo_zoho_default_' . $feed['crm_object'] . '_feed_id', $feed_id );

					$message = sprintf(
						/* translators: 1: Name of a feed.*/
						__( '%s feed created.', 'woo-crm-integration-for-zoho' ),
						$feed['crm_object']
					);
					$success = true;
				}
			} else {

				$message = sprintf(
						/* translators: 1: Name of a city 2: ZIP code */
					__( '%s feed already exists.', 'woo-crm-integration-for-zoho' ),
					$feed['crm_object']
				);
				$success = true;
			}
		}

		$response['total_count'] = count( $feeds );
		$response['next_step']   = $step + 1;
		$response['feed']        = $feed;
		$response['feed_type']   = $feed['crm_object'];
		$response['feed_id']     = $feed_id;
		$response['message']     = $message;
		$response['success']     = $success;

		if ( $response['total_count'] == $response['next_step'] ) {  //phpcs:ignore
			update_option( 'wciz_woo_zoho_default_feeds_created', true );
		}
		return $response;
	}

	/**
	 * Get Bulk feeds from database and send a request.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 * @since  1.0.0
	 * @return array
	 */
	public function form_bulk_data_sync( $posted_data = array() ) {
		// Get current Offset.
		$offset = ! empty( $posted_data['offset'] ) ?
		sanitize_text_field( wp_unslash( $posted_data['offset'] ) ) :
		'1';

		$is_onboarding = ! empty( $posted_data['isOnboarding'] ) ?
		sanitize_text_field( wp_unslash( $posted_data['isOnboarding'] ) ) :
		false;

		// Get current form submission.
		$formdata = array();
		! empty( $posted_data['form'] ) ? parse_str( sanitize_text_field( wp_unslash( $posted_data['form'] ) ), $formdata ) : '';
		$formdata = ! empty( $formdata ) ? map_deep( wp_unslash( $formdata ), 'sanitize_text_field' ) : false;

		// Feed Id.
		$feed_id = ! empty( $formdata['object-feed'] ) ?
		sanitize_text_field( wp_unslash( $formdata['object-feed'] ) ) :
		'';

		$zoho        = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$record_type = $zoho->get_feed( $feed_id, 'crm_object' );
		if ( true == $is_onboarding ) { //phpcs:ignore
			$is_onboarding_done = get_option( 'wciz_woo_zoho_' . $record_type . '_feed_onboarded', false );
			if ( ! empty( $is_onboarding_done ) && 'true' == $is_onboarding_done ) { //phpcs:ignore

				return array(
					'result'      => true,
					'record_type' => $record_type,
					'message'     => get_option( 'wciz_woo_zoho_' . $record_type . '_feed_final_reponse', array() ),
				);
			}
		}

		$zoho       = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$feed_title = $zoho->get_feed_title( $feed_id );

		switch ( $record_type ) {
			case 'Sales_Orders':
			case 'Deals':
			case 'Contacts':
			case 'Accounts':
			case 'Invoices':
				$woo_object = 'shop_order';
				break;

			case 'Products':
				$woo_object = 'product';
				break;
		}

		$request_module = new Woo_Crm_Integration_Request_Module();
		$bulk_data      = $request_module->get_bulk_data( $woo_object, $offset, $feed_id );

		if ( ! empty( $bulk_data['request'] ) && is_array( $bulk_data['request'] ) ) {

			$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
			$bulk_ids = ! empty( $bulk_data['bulk_ids'] ) ? $bulk_data['bulk_ids'] : array();

			$log_data = compact( 'feed_id', 'feed_title', 'bulk_ids', 'woo_object' );

			$result = $zoho_api->create_single_record(
				$record_type,
				$bulk_data['request'],
				true,
				$log_data
			);
		}

		return array(
			'result'  => true,
			'message' => $bulk_data,
		);
	}

	/**
	 * Get Bulk feeds which are not synced yet from database and send a request.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function form_one_click_sync( $posted_data = array() ) {
		// Get current Offset.
		$offset = ! empty( $posted_data['offset'] ) ?
		sanitize_text_field( wp_unslash( $posted_data['offset'] ) ) :
		'1';

		// Get current form submission.
		$formdata = array();
		! empty( $posted_data['form'] ) ? parse_str( sanitize_text_field( wp_unslash( $posted_data['form'] ) ), $formdata ) : '';
		$formdata = ! empty( $formdata ) ? map_deep( wp_unslash( $formdata ), 'sanitize_text_field' ) : false;

		// Feed Id.
		$feed_id = ! empty( $formdata['object-feed'] ) ?
		sanitize_text_field( wp_unslash( $formdata['object-feed'] ) ) :
		'';

		$zoho        = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$record_type = $zoho->get_feed( $feed_id, 'crm_object' );

		$zoho       = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$feed_title = $zoho->get_feed_title( $feed_id );

		switch ( $record_type ) {
			case 'Sales_Orders':
			case 'Deals':
			case 'Contacts':
			case 'Accounts':
			case 'Invoices':
				$woo_object = 'shop_order';
				break;

			case 'Products':
				$woo_object = 'product';
				break;
		}

		$request_module = new Woo_Crm_Integration_Request_Module();
		$bulk_data      = $request_module->get_bulk_data( $woo_object, $offset, $feed_id, true );

		if ( ! empty( $bulk_data['request'] ) && is_array( $bulk_data['request'] ) ) {

			$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
			$bulk_ids = ! empty( $bulk_data['bulk_ids'] ) ? $bulk_data['bulk_ids'] : array();
			$result   = $zoho_api->create_single_record(
				$record_type,
				$bulk_data['request'],
				true,
				compact( 'feed_id', 'feed_title', 'bulk_ids', 'woo_object' )
			);
		}

		return array(
			'result'  => true,
			'message' => $bulk_data,
		);
	}

	/**
	 * Returns the mapping step we require.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 *
	 * @since 1.0.0
	 *
	 */
	public function get_object_feed_options( $posted_data = array() ) {
		$feed_type = ! empty( $posted_data['syncModule'] ) ?
					sanitize_text_field( wp_unslash( $posted_data['syncModule'] ) ) :
					false;
		if ( empty( $feed_type ) ) {
			wp_send_json(
				array(
					'result'  => 404,
					'options' => '',
				)
			);
		}

		$args = array(
			'post_type'   => 'wciz_crm_feed',
			'post_status' => 'publish',
			'fields'      => 'ids',
			'meta_query'  => array( //phpcs:ignore
				'relation' => 'AND',
				array(
					'key'     => 'crm_object',
					'compare' => '=',
					'value'   => $feed_type,
				),
			),
		);

		$feeds = get_posts( $args );

		if ( ! empty( $feeds ) && is_array( $feeds ) ) {
			$result = array();
			foreach ( $feeds as $key => $feed_id ) {
				$result[ $feed_id ] = get_the_title( $feed_id );
			}
			wp_send_json(
				array(
					'result'  => 200,
					'options' => $result,
				)
			);
		} else {
			wp_send_json(
				array(
					'result'  => 403,
					'options' => '',
				)
			);
		}
	}

	/**
	 * Mark the onboarding step completed.
	 *
	 * @return array Response array.
	 */
	public function mark_onboarding_complete() {
		update_option( 'wciz_woo_zoho_onboarding_completed', true );
		return array( 'success' => true );
	}

	/**
	 * Save plugin general settings.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 * @return array Response array.
	 */
	public function save_general_setting( $posted_data = array() ) {

		$settings = $posted_data['settings'];
		foreach ( $settings as $key => $value ) {

			$value = is_array( $value ) ?
			map_deep( wp_unslash( $value ), 'sanitize_text_field' ) :
			sanitize_text_field( wp_unslash( $value ) );

			update_option( 'wciz_zoho_' . $key, $value );
		}

		return array( 'success' => true );
	}

	/**
	 * Save plugin general settings.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 * @return array Response array.
	 */
	public function save_abandoned_cart_setting( $posted_data = array() ) {

		$settings = $posted_data['settings'];
		foreach ( $settings as $key => $value ) {

			$value = is_array( $value ) ?
			map_deep( wp_unslash( $value ), 'sanitize_text_field' ) :
			sanitize_text_field( wp_unslash( $value ) );

			update_option( 'wciz_zoho_' . $key, $value );
		}

		return array( 'success' => true );
	}

	/**
	 * Clear sync log.
	 *
	 * @return array Response array.
	 */
	public function clear_sync_log() {
		Woo_Crm_Integration_For_Zoho_Admin::delete_sync_log();
		return array( 'success' => true );
	}

	/**
	 * Download sync log.
	 *
	 * @return array Response array.
	 */
	public function download_sync_log() {

		$log_data = wciz_woo_zoho_get_log_data( false, 25, 0, true );
		$data     = array();
		$log_dir  = WC_LOG_DIR . 'wciz-woo-zoho-sync-log.log';

		if ( ! is_dir( $log_dir ) ) {
			$log_dir = WC_LOG_DIR . 'wciz-woo-zoho-sync-log.log';
		}

		global $wp_filesystem;  // Define global object of WordPress filesystem.
		WP_Filesystem();        // Intialise new file system object.

		$file_data = '';

		if ( ! empty( $log_data ) && is_array( $log_data ) ) {
			foreach ( $log_data as $key => $value ) {

				$value['zoho_id'] = ! empty( $value['zoho_id'] ) ? $value['zoho_id'] : '-';
				$log              = 'FEED : ' . $value['event'] . PHP_EOL;
				$log             .= 'WOO ID : ' . $value['woo_id'] . PHP_EOL;
				$log             .= 'WOO OBJECT : ' . $value['woo_object'] . PHP_EOL;
				$log             .= 'ZOHO ID : ' . $value['zoho_id'] . PHP_EOL;
				$log             .= 'ZOHO OBJECT : ' . $value['zoho_object'] . PHP_EOL;
				$log             .= 'TIME : ' . gmdate( 'd-m-Y h:i A', esc_html( $value['time'] ) ) . PHP_EOL;
				$log             .= 'REQUEST : ' . wp_json_encode( maybe_unserialize( $value['request'] ) ) . PHP_EOL; //phpcs:ignore
				$log             .= 'RESPONSE : ' . wp_json_encode( maybe_unserialize( $value['response'] ) ) . PHP_EOL; //phpcs:ignore
				$log             .= '------------------------------------' . PHP_EOL;

				$file_data .= $log;
			}

			$wp_filesystem->put_contents( $log_dir, $file_data );

			return array(
				'success'  => true,
				'redirect' => admin_url( '?wciz_download=1' ),
			);
		} else {
			return array(
				'success' => false,
				'msg'     => esc_html__( 'No log data available', 'woo-crm-integration-for-zoho' ),
			);
		}
	}

	/**
	 * Download data log for product, order update.
	 *
	 * @return array Response array.
	 */
	public function download_data_log() {

		$log_dir = WC_LOG_DIR . 'wciz-woo-zoho-fetch-log.log';

		if ( ! is_dir( $log_dir ) ) {
			@fopen( WC_LOG_DIR . 'wciz-woo-zoho-fetch-log.log', 'a' ); //phpcs:ignore
		}

		global $wp_filesystem;  // Define global object of WordPress filesystem.
		WP_Filesystem();        // Intialise new file system object.

		$log_file = WC_LOG_DIR . 'wciz-woo-zoho-fetch-' . gmdate( 'Y-m-d' ) . '.log';

		if ( file_exists( $log_file ) ) {
			$file_data = $wp_filesystem->get_contents( $log_file );
		} else {
			$file_data = '';
		}

		if ( ! empty( $file_data ) ) {
			$wp_filesystem->put_contents( $log_dir, $file_data );

			return array(
				'success'  => true,
				'redirect' => admin_url( '?wciz_log_download=1' ),
			);
		} else {
			return array(
				'success' => false,
				'msg'     => esc_html__( 'No log data available', 'woo-crm-integration-for-zoho' ),
			);
		}
	}

	/**
	 * Clear data log for prodct, order.
	 *
	 * @return array Response array.
	 */
	public function clear_data_log() {
		$log_file = WC_LOG_DIR . 'wciz-woo-zoho-fetch-' . gmdate( 'Y-m-d' ) . '.log';
		if ( ! is_dir( $log_file ) ) {
			@fopen( WC_LOG_DIR . 'wciz-woo-zoho-fetch-' . gmdate( 'Y-m-d' ) . '.log', 'a' ); //phpcs:ignore
		}
		global $wp_filesystem;  // Define global object of WordPress filesystem.
		WP_Filesystem();        // Intialise new file system object.

		if ( file_exists( $log_file ) ) {
			$wp_filesystem->put_contents( $log_file, '' );
		}
		return array( 'success' => true );
	}

	/**
	 * Sync particular object manually.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|array feed ids with label.
	 */
	public function sync_object_manually( $posted_data = array() ) {
		$feed_id = ! empty( $posted_data['feed_id'] ) ?
		$posted_data['feed_id'] :
		false;

		$post_id = ! empty( $posted_data['post_id'] ) ?
		$posted_data['post_id'] :
		false;

		if ( empty( $feed_id ) || empty( $post_id ) ) {
			return array(
				'status'  => 404,
				'message' => esc_html__( 'Required data not found.', 'woo-crm-integration-for-zoho' ),
			);
		} else {

			$zoho           = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
			$request_module = Woo_Crm_Integration_Request_Module::get_instance();

			$record_type = $zoho->get_feed( $feed_id, 'crm_object' );
			$feed_title  = $zoho->get_feed_title( $feed_id );
			switch ( $record_type ) {
				case 'Sales_Orders':
				case 'Deals':
				case 'Invoices':
				case 'Quotes':
					$request_module->perform_shop_order_sync( $feed_id, $post_id );
					break;
				case 'Contacts':
				case 'Leads':
				case 'Accounts':
					$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
					if ( 'users' == $woo_object ) {
						if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
							$order = wc_get_order($post_id);
							$user_id = $order->get_customer_id();
						} else {

							$user_id = get_post_meta( $post_id, '_customer_user', true );
						}
						if ( ! empty( $user_id ) ) {
							$request_module->perform_wp_user_sync( $feed_id, $user_id );
						}
					} else {
						$request_module->perform_shop_order_sync( $feed_id, $post_id );
					}
					break;
				case 'Products':
					$request_module->trigger_product_related_feed( $post_id, $feed_id );
					break;
			}

			return array(
				'status'  => 200,
				'message' => __( 'Sync Completed. Check Logs for verification.', 'woo-crm-integration-for-zoho' ),
			);
		}
	}

		/**
		 * Sync particular object manually.
		 *
		 * @param array $posted_data Array of ajax posted data.
		 *
		 * @since 1.0.0
		 *
		 * @return bool|array feed ids with label.
		 */
	public function delete_object_keys_from_woo( $posted_data = array() ) {
		$feed_id  = ! empty( $posted_data['feed_id'] ) ?
		$posted_data['feed_id'] :
		false;
		$bulk_ids = array();
		$post_id  = ! empty( $posted_data['post_id'] ) ?
		$posted_data['post_id'] :
		false;

		if ( empty( $feed_id ) || empty( $post_id ) ) {
			return array(
				'status'  => 404,
				'message' => esc_html__( 'Required data not found.', 'woo-crm-integration-for-zoho' ),
			);
		} else {
			$zoho           = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
			$request_module = Woo_Crm_Integration_Request_Module::get_instance();
			$meta_key       = 'wciz_zoho_feed_' . $feed_id . '_association';
			$record_type    = $zoho->get_feed( $feed_id, 'crm_object' );
			if ( 'Products' == $record_type ) {
				$product = wc_get_product( $post_id );
				if ( 'variable' === $product->get_type() || 'variable-subscription' === $product->get_type() ) {

					$cpt_instance    = new Woo_Crm_Integration_For_Zoho_Cpt();
					$product_feed_id = get_option( 'wciz_woo_zoho_default_Products_feed_id', '' );
					if ( ! empty( $product_feed_id ) ) {
						$is_default_feed = $cpt_instance->get_feed_data( $product_feed_id, 'default_feed', '' );
						if ( $is_default_feed ) {
							$sync_parent_product = $cpt_instance->get_feed_data( $product_feed_id, 'sync_parent_product', 'no' );
							if ( 'yes' === $sync_parent_product ) {
									 delete_post_meta( $post_id, $meta_key );
							}
						}
					}

					$available_variations = $product->get_children();
					if ( ! empty( $available_variations ) && is_array( $available_variations ) ) {
						foreach ( $available_variations as $variation_id ) {
							$variation = wc_get_product( $variation_id );
							if ( ! $variation || ! $variation->exists() ) {
								continue;
							}
							 delete_post_meta( $variation_id, $meta_key );
						}
					}
						$status  = 200;
						$message = __( 'Data deleted successfully..!', 'woo-crm-integration-for-zoho' );

				} elseif ( 'variation' === $product->get_type() || 'subscription_variation' === $product->get_type() ) {

					if ( delete_post_meta( $post_id, $meta_key ) ) {
						$status  = 200;
						$message = __( 'Data deleted successfully..!', 'woo-crm-integration-for-zoho' );
					} else {
						$status  = 404;
						$message = __( 'Something went wrong..!', 'woo-crm-integration-for-zoho' );
					}
				} elseif ( delete_post_meta( $post_id, $meta_key ) ) {

						$status  = 200;
						$message = __( 'Data deleted successfully..!', 'woo-crm-integration-for-zoho' );
				} else {
					$status  = 404;
					$message = __( 'Something went wrong..!', 'woo-crm-integration-for-zoho' );
				}
			} elseif ( empty( wciz_zoho_get_meta_data( $post_id, $meta_key, true ) ) ) {
					$status  = 404;
					$message = __( 'Data related to this feed does not exists.', 'woo-crm-integration-for-zoho' );
			} elseif ( delete_post_meta( $post_id, $meta_key ) ) {

					$status  = 200;
					$message = __( 'Data deleted successfully..!', 'woo-crm-integration-for-zoho' );
			} else {
				$status  = 404;
				$message = __( 'Something went wrong..!', 'woo-crm-integration-for-zoho' );
			}
			return array(
				'status'  => $status,
				'message' => $message,
			);
		}
	}



	/**
	 * Get all object id's required for sync.
	 *
	 * @param array $posted_data Array of post data.
	 * @return array             Response data.
	 */
	public function fetch_required_sync_count( $posted_data = array() ) {

		$response = array(
			'success' => false,
			'message' => __( 'Something went wrong !!', 'woo-crm-integration-for-zoho' ),
		);

		$feed_id   = isset( $posted_data['feed_id'] ) ? $posted_data['feed_id'] : false;
		$sync_type = isset( $posted_data['sync_type'] ) ? $posted_data['sync_type'] : 'bulk_data_sync';
		if ( ! $feed_id ) {
			return $response;
		}

		$request_module = new Woo_Crm_Integration_Request_Module();

		$from_time = isset( $posted_data['from_time'] ) ? $posted_data['from_time'] : '';
		$to_time   = isset( $posted_data['to_time'] ) ? $posted_data['to_time'] : '';
		$woo_object_ids = $request_module->get_feed_required_object_ids( $feed_id, $sync_type, $from_time, $to_time );


		if ( ! empty( $woo_object_ids ) ) {
			$response['data']    = $woo_object_ids;
			$response['success'] = true;
			return $response;
		}
		$response['message'] = __( 'No data found. Seems everything is up to date', 'woo-crm-integration-for-zoho' );
		return $response;
	}

	public function fetch_prod_feed_id() {
		$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
		$all_feeds        = $framework->get_all_feed();
		$default_feeds    = array();
		$additional_feeds = array();
		$product_feeds    = array();
		$request_module   = Woo_Crm_Integration_Request_Module::get_instance();
		if ( ! empty( $all_feeds ) && is_array( $all_feeds ) ) {
			foreach ( $all_feeds as $key => $feed_id ) {
				$is_default_feed = $framework->get_feed( $feed_id, 'default_feed' );
				if ( $is_default_feed ) {
					array_push( $default_feeds, $feed_id );
				} else {
					array_push( $additional_feeds, $feed_id );
				}
			}
		}
		if ( ! empty( $default_feeds ) ) {
			foreach ( $default_feeds as $key => $feed_id ) {
				$record_type = $framework->get_feed( $feed_id, 'crm_object' );
				if ( 'Products' === $record_type ) {
					$product_feeds['0'] = $feed_id;
				}
			}
		}
		if ( ! empty( $additional_feeds ) ) {
			foreach ( $additional_feeds as $key => $feed_id ) {
				$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
				if ( 'product' === $woo_object ) {
					array_push( $product_feeds, $feed_id );
				}
			}
		}

		if ( !empty( $product_feeds ) ) {
			ksort( $product_feeds );

			$feed_id = $product_feeds[0];
		} else {
			$feed_id = 0;
		}
		return $feed_id;
	}

	/**
	 * Get all object id's required for sync.
	 *
	 * @param array $posted_data Array of post data.
	 * @return array             Response data.
	 */
	public function sync_bulk_data_request( $posted_data = array() ) {

		$response  = array(
			'success'       => false,
			'data'          => array(),
			'has_next_step' => false,
		);
		$feed_id   = isset( $posted_data['feed_id'] ) ? $posted_data['feed_id'] : false;
		$step      = isset( $posted_data['step'] ) ? $posted_data['step'] : false;
		$sync_type = isset( $posted_data['sync_type'] ) ? $posted_data['sync_type'] : 'bulk_sync';
		$from_time = isset( $posted_data['from_time'] ) ? $posted_data['from_time'] : '';
		$to_time   = isset( $posted_data['to_time'] ) ? $posted_data['to_time'] : '';
		if ( false === $feed_id || false === $step ) {
			return $response;
		}
		$length           = 10;
		$start            = $step * $length;
		$crm_object       = get_post_meta( $feed_id, 'crm_object', true );
		if ( 'Products' == $crm_object ) {
		$option_key       = 'wciz_woo_zoho_' . $feed_id . '_' . $sync_type . '_sync_ids';
		} elseif (( '' != $to_time ) && ( '' != $from_time )) {
				$option_key = 'wciz_woo_zoho_' . $feed_id . '_' . $sync_type . '_sync_ids_from_' . $from_time . '_to_' . $to_time;
		} elseif ( $to_time ) {
			$option_key = 'wciz_woo_zoho_' . $feed_id . '_' . $sync_type . '_sync_ids_to_' . $to_time;
		} elseif ( $from_time ) {
			$option_key = 'wciz_woo_zoho_' . $feed_id . '_' . $sync_type . '_sync_ids_from_' . $from_time;
		} else {
			$option_key = 'wciz_woo_zoho_' . $feed_id . '_' . $sync_type . '_sync_ids';
		}
		$saved_object_ids = get_option( $option_key, array() );
		$current_dataset  = array_slice( $saved_object_ids, $start, $length );
		if ( ! empty( $current_dataset ) ) {
			$sync_data = $this->bulk_data_sync( $feed_id, $current_dataset, $sync_type);
			if ( 'Products' == $crm_object ) {
				$add_product_image = get_post_meta( $feed_id, 'add_product_image', true );
				if ( 'yes' === $add_product_image ) {
					// May be sync product images.
					$this->may_be_sync_product_images( $feed_id, $sync_data['bulk_ids'] );
				}
			}
			$percent  = ceil( ( $start + count( $current_dataset ) ) / count( $saved_object_ids ) * 100 );
			$response = array(
				'success'       => true,
				'data'          => $current_dataset,
				'synced_data'   => $sync_data['bulk_ids'],
				'next_step'     => ++$step,
				'has_next_step' => true,
				'percent'       => $percent,
			);
		}
		return $response;
	}

	/**
	 * Perform sync operation for selected data set.
	 *
	 * @param int   $feed_id Feed id.
	 * @param array $current_dataset Array of selected object ids.
	 * @return array Bulk data.
	 */
	public function bulk_data_sync( $feed_id, $current_dataset, $sync_type ) {
		$zoho           = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$record_type    = $zoho->get_feed( $feed_id, 'crm_object' );
		$feed_title     = $zoho->get_feed_title( $feed_id );
		$request_module = new Woo_Crm_Integration_Request_Module();
		$one_click_sync = false;
		$woo_object = 'woo_object';
		if ( 'one_click_sync' == $sync_type ) {
			$one_click_sync = true;
		}
		switch ( $record_type ) {
			case 'Sales_Orders':
			case 'Deals':
			case 'Invoices':
			case 'Quotes':
				$woo_object = 'shop_order';
				break;

			case 'Contacts':
			case 'Leads':
			case 'Accounts':
				$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
				break;

			case 'Products':
				$woo_object = 'product';
				break;
		}

		$offset    = 1;
		$bulk_data = $request_module->get_bulk_data( $woo_object, $offset, $feed_id, $one_click_sync, $current_dataset );
		if ( ! empty( $bulk_data['request'] ) && is_array( $bulk_data['request'] ) ) {

			$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
			$bulk_ids = ! empty( $bulk_data['bulk_ids'] ) ? $bulk_data['bulk_ids'] : array();

			$log_data = compact( 'feed_id', 'feed_title', 'bulk_ids', 'woo_object' );
			$result = $zoho_api->create_single_record(
				$record_type,
				$bulk_data['request'],
				true,
				$log_data
			);
		}

		return $bulk_data;
	}

	/**
	 * Get all feed id's required for sync.
	 *
	 * @param array $posted_data Array of post data.
	 * @return array             Response data.
	 */
	public function get_available_object_feed_ids( $posted_data = array() ) {
		$response = array(
			'success' => false,
			'message' => __( 'Something went wrong!!', 'woo-crm-integration-for-zoho' ),
		);

		$feed_data    = array();
		$zoho_objects = array( 'Products', 'Contacts', 'Deals', 'Sales_Orders' );
		foreach ( $zoho_objects as $key => $object ) {
			$args  = array(
				'post_type'      => 'wciz_crm_feed',
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'posts_per_page' => 1,
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => 'crm_object',
						'compare' => '=',
						'value'   => $object,
					),
				),
			);
			$feeds = get_posts( $args );
			if ( ! empty( $feeds ) && isset( $feeds[0] ) ) {
				$feed_data[] = array(
					'object'  => $object,
					'feed_id' => $feeds[0],
				);
			}
		}
		$response['feed_data'] = $feed_data;
		if ( ! empty( $feed_data ) ) {
			$response['success'] = true;
		}

		return $response;
	}

	/**
	 * Create shipping line item product.
	 *
	 * @return array             Response data.
	 */
	public function create_shipping_product() {

		$response = array(
			'success' => false,
			'message' => __( 'Something went wrong!!', 'woo-crm-integration-for-zoho' ),
		);

		$product_data = array(
			array(
				'Product_Name' => __( 'Shipping', 'woo-crm-integration-for-zoho' ),
				'Product_Code' => 'woo-shipping-item',
				'Unit_Price'   => 0,
				'Description'  => __( 'This product is created to capture shipping cost of an order.', 'woo-crm-integration-for-zoho' ),
			),
		);
		$zoho_api     = Woo_Crm_Integration_Zoho_Api::get_instance();
		$result       = $zoho_api->create_single_record(
			'Products',
			$product_data,
			true,
			array()
		);

		$shipping_product_id = false;

		if ( ! empty( $result ) ) {
			if ( isset( $result['data'] ) && isset( $result['data'][0] ) ) {
				if ( 'SUCCESS' == $result['data'][0]['code'] ) {
					$details             = $result['data'][0]['details'];
					$shipping_product_id = $details['id'];
					update_option( 'wciz_woo_shipping_product_id', $shipping_product_id );
				}
			}
		}

		if ( false != $shipping_product_id ) {
			$response = array(
				'success'             => true,
				'message'             => __( 'Product created', 'woo-crm-integration-for-zoho' ),
				'shipping_product_id' => $shipping_product_id,
			);
		}

		return $response;
	}

	/**
	 * Create filter field in feed form.
	 *
	 * @param    array $posted_data   Posted data.
	 * @since    1.0.0
	 * @return   array                Response data.
	 */
	public function create_feed_filters( $posted_data ) {

		$response        = array(
			'success' => false,
			'msg'     => esc_html__( 'Somthing went wrong, Refresh and try again.', 'woo-crm-integration-for-zoho' ),
		);
		$selected_object = ! empty( $posted_data['crm_selected_object'] ) ? sanitize_text_field( wp_unslash( $posted_data['crm_selected_object'] ) ) : '';

		$object_fields = $this->get_field_mapping_options( $selected_object );
		$filter_fields = $this->get_field_filter_options();
		return array(
			'object_fields' => $object_fields,
			'filter'        => $filter_fields,
			'success'       => true,
		);
	}

	/**
	 * Get all mapping options for a filter field.
	 *
	 * @return   array           Array for field option.
	 * @since    1.0.0
	 */
	public function get_field_filter_options() {
		$framework_class    = 'Woo_Crm_Integration_Connect_Framework';
		$framework_instance = $framework_class::get_instance();
		$options            = $framework_instance->get_filter_mapping_dataset();
		return $options;
	}

	/**
	 * Get all mapping options for a zoho field.
	 *
	 * @param    string $selected_object   selected object.
	 * @return   array           Array for field option.
	 * @since    1.0.0
	 */
	public function get_field_mapping_options( $selected_object ) {
		$framework_class    = 'Woo_Crm_Integration_For_Zoho_Fw';
		$framework_instance = $framework_class::get_instance();
		$options            = $framework_instance->getObjectFilteringField( $selected_object );
		return $options;
	}

	/**
	 * Sync product images in bulk action.
	 *
	 * @param int   $feed_id feed id.
	 * @param array $product_ids product ids.
	 * @return void
	 */
	public function may_be_sync_product_images( $feed_id, $product_ids ) {

		$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
		foreach ( $product_ids as $key => $product_id ) {

			$zoho_id   = get_post_meta( $product_id, 'wciz_zoho_feed_' . $feed_id . '_association', true );
			$image_url = wp_get_attachment_url( get_post_thumbnail_id( $product_id ) );

			if ( empty( $image_url ) ) {
				$product = wc_get_product( $product_id );
				if ( $product ) {
					if ( $product->is_type( 'variation' ) || $product->is_type( 'subscription_variation' ) ) {
						$parent_product_id = $product->get_parent_id();
						$image_url         = wp_get_attachment_url( get_post_thumbnail_id( $parent_product_id ) );
					}
				}
			}

			if ( empty( $image_url ) || empty( $zoho_id ) ) {
				continue;
			}
			$uploads   = wp_upload_dir();
			$zoho_api->upload_record_image( 'Products', $zoho_id, $image_url );
		}
	}

	/**
	 * Get zoho salesorder status.
	 *
	 * @param array $posted_data Array of ajax posted data.
	 * @return array $module_data data of specific module.
	 */
	public function get_zoho_salesorder_status( $posted_data = array() ) {

		$force       = ! empty( $posted_data['force'] ) ? sanitize_text_field( wp_unslash( $posted_data['force'] ) ) : false;
		$zoho_status = Woo_Crm_Integration_For_Zoho_Admin::get_zoho_order_statuses( $force );
		return $zoho_status;
	}
	/**
	 * Fetch the data synced from zoho to woocommerce.
	 *
	 * @return void
	 */
	public function get_zoho_to_woocommerce_sync_log_data() {
		check_ajax_referer( 'ajax_nonce', 'nonce' );
		$log_file = WC_LOG_DIR . 'wciz-woo-zoho-fetch-' . gmdate( 'Y-m-d' ) . '.log';
		global $wp_filesystem;  // Define global object of WordPress filesystem.
		WP_Filesystem();

		if ( file_exists( $log_file ) ) {
			$file_data = $wp_filesystem->get_contents( $log_file );

			$response = esc_html( $file_data );
		} else {
			$response = array(
				'result' => 'failure',
				'data'   => esc_html__( 'No data synced...', 'woo-crm-integration-for-zoho' ),
			);
		}

		echo wp_json_encode( $response );
		wp_die();
	}

	public function wciz_cart_table_view_cart() {
		check_ajax_referer( 'ajax_nonce', 'nonce' );
		$postcartemail = isset( $_POST['cart_email'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_email'] ) ) : '';
		if ( isset( $_POST['user_type'] ) && 'Guest' == $_POST['user_type'] ) {

			$data = get_option( 'zoho_cart_data_' . esc_html( $postcartemail ) );

			$cart_details = $data['cart_items'];
		}

		if ( isset( $_POST['user_type'] ) && 'Guest' != $_POST['user_type'] ) {

			$user = get_user_by( 'email', esc_html( $postcartemail ) );

			$data = get_user_meta( $user->ID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true );

			$cart_details = $data['cart'];
		}

		$cart_total = 0;
		echo '<div class="wciz_cart_table_cart_details_wrap" ><div><span class="dashicons dashicons-no-alt cart_details_close"></span><table class="wciz_guest_user_abd_cart_table  wp-list-table widefat striped">';
		$cart_data = array();
		echo '<tr><th></th><th>' . esc_html__( 'Name', 'woo-crm-integration-for-zoho' ) . '</th><th>' . esc_html__( 'Qty', 'woo-crm-integration-for-zoho' ) . '</th><th>' . esc_html__( 'Price', 'woo-crm-integration-for-zoho' ) . '</th></tr>';
		foreach ( $cart_details as $key => $value ) {

			$variation_id = $value['variation_id'];
			$product_id   = $value['product_id'];

			if ( 0 != $variation_id ) {
				$product = wc_get_product( $value['variation_id'] );

				echo '<tr>';
				echo '<td><div class="wciz_guest_user_abd_image"><a href = "' . esc_html( get_permalink( $value['variation_id'] ) ) . '">' . wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ) . '</a></div></td>';
				echo '<td><a href = "' . esc_html( get_permalink( $value['product_id'] ) ) . '">' . esc_html( $product->get_name() ) . '</a></td>';
				echo '<td>' . esc_html( $value['quantity'] ) . '</td>';

				echo '<td>' . esc_html( wc_price( $product->get_price() ) ) . '</td>';
				echo '</tr>';
				$cart_total += $value['quantity'] * $product->get_price();

			}

			if ( 0 == $variation_id ) {

				$product = wc_get_product( $value['product_id'] );

				echo '<tr>';
				echo '<td><div class="wciz_guest_user_abd_image"><a href = "' . esc_html( get_permalink( $value['product_id'] ) ) . '">' . wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ) . '</a></div></td>';
				echo '<td><a href = "' . esc_html( get_permalink( $value['product_id'] ) ) . '">' . esc_html( $product->get_name() ) . '</a></td>';
				echo '<td>' . esc_html( $value['quantity'] ) . '</td>';

				echo '<td>' . esc_html( $product->get_price() ) . '</td>';
				echo '</tr>';
				$cart_total += $value['quantity'] * $product->get_price();

			}
		}

		echo '<tr class="wciz_cart_table_cart_details_total"><td></td><td></td><td>' . esc_html__( 'Cart Total', 'woo-crm-integration-for-zoho' ) . '</td><td>' . wp_kses_post( wc_price( $cart_total ) ) . '</td></tr>';
		echo '</table></div></div>';

		wp_die();
	}

	public function sync_abndn_carts() {
		if ( 'yes' == get_option( 'wciz_zoho_enable_abandoned_cart_sync', 'no' ) ) {
			$feed_id = get_option( 'wciz_zoho_abandoned_cart_feed_id' );
			$cart_status = 'Active';
			$connect_framework = new Woo_Crm_Integration_Connect_Framework();
			$zoho_abundant_guests = wciz_zoho_abundant_guests();
			if ( count( $zoho_abundant_guests ) > 0 ) {
	
				foreach ( $zoho_abundant_guests as $key => $single_cart ) {
					$user_type = 'guest';
					$user_id = 0;
					if ( isset( $single_cart['option_value'] ) ) {
						$data = unserialize( $single_cart['option_value'] );
						
						if ( ! ( isset( $data['sync_status'] ) && !empty( $data['sync_status'] ) ) ) {
							$zoho_abundant_timer = (int) get_option( 'wciz_zoho_cart_timer', 5 );
							$cart_expiry_time = $zoho_abundant_timer * 60 + $data['last_updated'];
							if ( $cart_expiry_time < time() ) {
	
								$cart_status = 'Abandoned';
							} else {
								$cart_status = 'Active';
							}
							if ( 'Abandoned' == $cart_status ) {
								$cart_email = $data['cart_email'];
								$crm_connect_manager = new Woo_Crm_Integration_For_Zoho_Fw();
								$record_type = $crm_connect_manager->get_feed(
									$feed_id,
									'crm_object'
								);
								$request = $connect_framework->get_request(
									'abandoned_cart',
									$feed_id,
									0,
									$cart_email
								);
			
								$log_data = array(
									'woo_id'     => 0,
									'feed_id'    => $feed_id,
									'woo_object' => 'abandoned_cart',
									'feed_title' => $crm_connect_manager->get_feed_title( $feed_id ),
								);
					
								$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
								
								$result                   = $crm_integration_zoho_api->create_single_record(
									$record_type,
									$request,
									false,
									$log_data
								);
								if ( 'SUCCESS' == $result['data'][0]['code'] ) {
									wciz_set_abandoned_cart_sync_status( $cart_email, $user_type, $user_id );
								}
							}
						}
					}
				}
			}

			$abandoned_customers = zoho_abundant_users();
			if ( isset( $abandoned_customers ) && null != $abandoned_customers && count( $abandoned_customers ) ) {

				$current_time = time();
				$user_type = 'user';
				foreach ( $abandoned_customers as $key => $single_user ) {
					if ( in_array('administrator', $single_user->roles ) ) {
						continue;
					}
					$customer_cart = get_user_meta( $single_user->ID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true );
					$last_time = get_user_meta( $single_user->ID, 'zoho_last_addtocart', true );

					if ( empty( $last_time ) && ! $last_time ) {

						continue;
					}

					$sync_status = get_user_meta( $single_user->ID, 'zoho_abdn_sync_status', true );

					$zoho_abundant_delete = (int) get_option( 'wciz_zoho_delete_data', 1 );

					if ( ! empty( $sync_status ) && 'not_synced' !== $sync_status ) {

						$sync_time = $sync_status;

						$abundant_delete_time = $zoho_abundant_delete * 24 * 60 * 60;

						if ( $current_time > ( $sync_time + $abundant_delete_time ) ) {

							update_user_meta( $single_user->ID, 'zoho_user_left_cart', 'no' );

						}
					}

					$user_email = $single_user->data->user_email;
					if ( ! empty( $sync_status ) && 'not_synced' == $sync_status ) {
						$zoho_abundant_timer = (int) get_option( 'wciz_zoho_cart_timer', 5 );
						$cart_expiry_time = $zoho_abundant_timer * 60 + $data['last_updated'];
						if ( $cart_expiry_time < time() ) {

							$cart_status = 'Abandoned';
						} else {
							$cart_status = 'Active';
						}
						if ( 'Abandoned' == $cart_status ) {
							$cart_email = $user_email;
							$crm_connect_manager = new Woo_Crm_Integration_For_Zoho_Fw();
							$record_type = $crm_connect_manager->get_feed(
								$feed_id,
								'crm_object'
							);
							$request = $connect_framework->get_request(
								'abandoned_cart',
								$feed_id,
								$single_user->ID,
								$cart_email
							);

							$log_data = array(
								'woo_id'     => $single_user->ID,
								'feed_id'    => $feed_id,
								'woo_object' => 'abandoned_cart',
								'feed_title' => $crm_connect_manager->get_feed_title( $feed_id ),
							);
				
							$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
							
							$result                   = $crm_integration_zoho_api->create_single_record(
								$record_type,
								$request,
								false,
								$log_data
							);
							if ( 'SUCCESS' == $result['data'][0]['code'] ) {
								wciz_set_abandoned_cart_sync_status( $cart_email, $user_type, $single_user->ID );
							}
						}
					}
				}
			}

		}
		wp_die();
	}

	public function create_abndn_cart_object() {
		$api_class = new Woo_Crm_Integration_Zoho_Api();
		$response = $api_class->create_abandoned_cart_module();
		if ( '201' == $response['code'] && '400' != $response['code'] ) {
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	public function create_feed_for_abndn_cart_object() {

		$connect_framework = new Woo_Crm_Integration_Connect_Framework();
		$feed_id = get_option( 'wciz_zoho_abandoned_cart_feed_id', '' );
		if ( !empty( $feed_id ) ) {
			$feed = $connect_framework->get_feed( $feed_id );
			if ( empty( $feed ) ) {
				$cpt_instance = new Woo_Crm_Integration_For_Zoho_Cpt();
				$feed_id = $cpt_instance->create_feed_post( 'Abandoned Cart', 'abandoned_cart' );
				if ( !empty( $feed_id ) ) {
					update_option( 'wciz_zoho_abandoned_cart_feed_id', $feed_id );
					$response = array(
						'result' => 'success',
						'data'   => esc_html__( 'Abandoned cart object and fields created; abandoned cart feed generated.', 'woo-crm-integration-for-zoho' ),
					);
				}
			} else {
				$response = array(
					'result' => 'already_exists',
					'data'   => esc_html__( 'Abandoned cart object & fields created and Abandoned cart feed already exists', 'woo-crm-integration-for-zoho' ),
				);
			}
		} else {
			$cpt_instance = new Woo_Crm_Integration_For_Zoho_Cpt();

			$feed_id = $cpt_instance->create_feed_post( 'Abandoned Cart', 'abandoned_cart' );
			if ( !empty( $feed_id ) ) {
				update_option( 'wciz_zoho_abandoned_cart_feed_id', $feed_id );
				$response = array(
					'result' => 'success',
					'data'   => esc_html__( 'Abandoned cart object and fields created; abandoned cart feed generated.', 'woo-crm-integration-for-zoho' ),
				);
			}
		}

		echo wp_json_encode( $response );
		wp_die();
	}

	public function changed_field_values_according_to_feed_event( $posted_data = array() ) {
		$feed_event = isset( $posted_data['feed_event'] ) ? $posted_data['feed_event'] : 'send_manually';
		if ( is_numeric( $feed_event ) ) {
			if ( get_post_type( $feed_event) === 'wciz_crm_feed' ) {
				$feed_event = get_post_meta( $feed_event, 'feed_event', true );
			}
		}

		$admin_instance = new Woo_Crm_Integration_For_Zoho_Admin( 'CRM Integration For Zoho', WOO_CRM_INTEGRATION_ZOHO_VERSION );
		if ( strpos($feed_event, 'order') !== false || in_array( $feed_event, array_keys(wc_get_order_statuses()) ) || 'status_change' == $feed_event ) {
			$obj_type = array(
				'shop_order',
				'abandoned_cart',
			);
		} else if ( strpos($feed_event, 'product') !== false ) {
			$obj_type = array(
				'product',
				'abandoned_cart',
			);
		} else if ( strpos($feed_event, 'user') !== false ) {
			$obj_type = array(
				'users',
				'abandoned_cart',
			);
		} else if ( strpos($feed_event, 'membership') !== false ) {
			$obj_type = array(
				'abandoned_cart',
				( $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) ? 'wc_user_membership' : '',
			);
		} else if ( 'send_manually' == $feed_event ) {
			$obj_type = array(
				'shop_order',
				'product',
				'users',
				'abandoned_cart',
				( $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) ? 'wc_user_membership' : '',
			);
		} else if ( 'create_subscription' == $feed_event ) {
			$obj_type = array(
				'shop_order',
			);
		} else if ( 'subscription_status_changed' == $feed_event ) {
			$obj_type = array(
				'shop_order',
			);
		} else if ( 'subscription_updated' == $feed_event ) {
			$obj_type = array(
				'shop_order',
			);
		}

		
		$class           = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$options         = $class->getMappingDataset( $obj_type );
		$cpt_instance    = new Woo_Crm_Integration_For_Zoho_Cpt();
		$available_feeds = $cpt_instance->get_available_feeds_for_mapping();
		if ( count( $available_feeds ) ) {
			$options['feeds'] = $available_feeds;
		}
		$settings['field_type']  = array(
			'label'   => __( 'Field Type', 'woo-crm-integration-for-zoho' ),
			'options' => array(
				'standard_field' => __( 'Standard Fields', 'woo-crm-integration-for-zoho' ),
				'custom_value'   => __( 'Custom Value', 'woo-crm-integration-for-zoho' ),
			),
		);
		$settings['field_value'] = array(
			'label'   => __( 'Field Value', 'woo-crm-integration-for-zoho' ),
			'options' => $options,
		);

		$settings['custom_value'] = array(
			'label' => __( 'Custom Value', 'woo-crm-integration-for-zoho' ),
		);

		echo wp_json_encode( $settings );
		wp_die();
	}

	// End of class.
}
