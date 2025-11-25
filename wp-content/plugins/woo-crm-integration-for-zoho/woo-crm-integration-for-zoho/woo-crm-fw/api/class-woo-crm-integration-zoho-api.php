<?php
/**
 * Base Api Class
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */

/**
 * Base Api Class.
 *
 * This class defines all code necessary api communication.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */
class Woo_Crm_Integration_Zoho_Api extends Woo_Crm_Integration_Api_Base {

	/**
	 * Zoho access token.
	 *
	 * @since    1.0.0
	 * @var      string    $access_token    Zoho access token.
	 */
	private static $access_token;

	/**
	 * Zoho refresh token.
	 *
	 * @since    1.0.0
	 * @var      string    $refresh_token    Zoho refresh token.
	 */
	private static $refresh_token;

	/**
	 * Zoho account url.
	 *
	 * @since    1.0.0
	 * @var      string    $acc_url    Zoho account url.
	 */
	private static $acc_url;

	/**
	 * Zoho account domain.
	 *
	 * @since    1.0.0
	 * @var      string    $api_domain   Zoho account domain.
	 */
	private static $api_domain;

	/**
	 * Zoho access token expiry.
	 *
	 * @since    1.0.0
	 * @var      string    $expiry   access token expiry.
	 */
	private static $expiry;

	/**
	 * Current class instance.
	 *
	 * @since    1.0.0
	 * @var      string    $_instance   Current class instance.
	 */
	protected static $_instance = null; //phpcs:ignore

	/**
	 * Main Woo_Crm_Integration_Zoho_Api Instance.
	 *
	 * Ensures only one instance of Woo_Crm_Integration_Zoho_Api is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Woo_Crm_Integration_Zoho_Api - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self();
			self::initialize();
		}

		return self::$_instance;
	}

	/**
	 * Initialize properties.
	 *
	 * @param  array $token_data Saved token data.
	 */
	private static function initialize( $token_data = array() ) {

		if ( empty( $token_data ) ) {
			$token_data = get_option( 'wciz_woo_zoho_token_data', array() );
		}

		self::$access_token  = isset( $token_data['access_token'] ) ? $token_data['access_token'] : '';
		self::$refresh_token = isset( $token_data['refresh_token'] ) ? $token_data['refresh_token'] : '';
		self::$expiry        = isset( $token_data['expiry'] ) ? $token_data['expiry'] : '';
		self::$api_domain    = isset( $token_data['api_domain'] ) ? $token_data['api_domain'] : '';
	}

	/**
	 * Get api domain.
	 *
	 * @return string Api domain.
	 */
	public function get_api_domain() {
		return self::$api_domain;
	}

	/**
	 * Get access token.
	 *
	 * @return string Access token.
	 */
	public function get_access_token() {
		return self::$access_token;
	}

	/**
	 * Get refresh token.
	 *
	 * @return string Refresh token.
	 */
	public function get_refresh_token() {
		return self::$refresh_token;
	}

	/**
	 * Get access token expiry.
	 *
	 * @return string expiry.
	 */
	public function get_access_token_expiry() {
		return self::$expiry;
	}

	/**
	 * Check if access token is valid.
	 *
	 * @return boolean
	 */
	public function is_access_token_valid() {
		return ( self::$expiry > time() );
	}

	/**
	 * Renew access token.
	 *
	 * @return boolean
	 */
	public function renew_access_token() {

		$endpoint       = '/oauth/v2/token';
		$client_id      = get_option( 'wciz_zoho_client_id', false );
		$client_secret  = get_option( 'wciz_zoho_secret_id', false );
		$domain         = get_option( 'wciz_zoho_domain', 'in' );
		if ( 'ca' == $domain ) {
			$acc_url        = 'https://accounts.zohocloud.' . $domain;
		} else {
			$acc_url        = 'https://accounts.zoho.' . $domain;
		}
		$refresh_token  = $this->get_refresh_token();
		$params         = array(
			'grant_type'    => 'refresh_token',
			'client_id'     => $client_id,
			'client_secret' => $client_secret,
			'refresh_token' => $refresh_token,
		);
		$this->base_url = $acc_url;
		$response       = $this->post( $endpoint, $params );
		$this->log_request( __FUNCTION__, $endpoint, $params, $response );
		if ( ! empty( $response ) && ( 200 === $response['code'] || '200' === $response['code'] ) && $this->check_response_error( $response ) ) {
			$this->save_token_data( $response['data'] );
			return true;
		}
		return false;
	}

	/**
	 * Save token data into db.
	 *
	 * @param  array $token_data token data.
	 */
	public function save_token_data( $token_data ) {
		$old_token_data = get_option( 'wciz_woo_zoho_token_data', array() );
		foreach ( $token_data as $key => $value ) {
			if ( is_array( $old_token_data ) ) {
				$old_token_data[ $key ] = $value;
				if ( 'expires_in' === $key ) {
					$old_token_data['expiry'] = time() + $value;
				}
			}
		}
		$this->initialize( $old_token_data );
		update_option( 'wciz_woo_zoho_token_data', $old_token_data );
	}

	/**
	 * Get refresh token data from api.
	 *
	 * @param  string $code Unique code.
	 * @return boolean.
	 */
	public function get_refresh_token_data( $code ) {

		$endpoint      = '/oauth/v2/token';
		$client_id     = get_option( 'wciz_zoho_client_id', false );
		$client_secret = get_option( 'wciz_zoho_secret_id', false );
		$domain        = get_option( 'wciz_zoho_domain', 'in' );
		$use_gapp      = get_option( 'wciz_zoho_use_gapp', false );
		if ( 'ca' == $domain ) {
			$acc_url        = 'https://accounts.zohocloud.' . $domain;
		} else {
			$acc_url        = 'https://accounts.zoho.' . $domain;
		}

		if ( true === $use_gapp || 'true' === $use_gapp ) {
			$redirect_uri = 'https://auth.makewebbetter.com/integration/zoho-auth/';
		} else {
			$redirect_uri = rtrim( admin_url(), '/' );
		}

		$params = array(
			'grant_type'    => 'authorization_code',
			'client_id'     => $client_id,
			'client_secret' => $client_secret,
			'redirect_uri'  => $redirect_uri,
			'code'          => $code,
		);

		$this->base_url = $acc_url;
		$response       = $this->post( $endpoint, $params );
		$this->log_request( __FUNCTION__, $endpoint, $params, $response );
		if ( ( 200 === $response['code'] || '200' === $response['code'] ) && $this->check_response_error( $response ) ) {
			$this->save_token_data( $response['data'] );
			return true;
		}
		return false;
	}

	/**
	 * Get autherization url.
	 *
	 * @return  string autherization url.
	 */
	public function get_auth_code_url() {

		$client_id     = get_option( 'wciz_zoho_client_id', false );
		$client_secret = get_option( 'wciz_zoho_secret_id', false );
		$domain        = get_option( 'wciz_zoho_domain', 'in' );
		$use_gapp      = get_option( 'wciz_zoho_use_gapp', false );

		if ( ! $client_id || ! $client_secret || ! $domain ) {
			return false;
		}

		if ( true === $use_gapp || 'true' === $use_gapp ) {
			$redirect_uri = 'https://auth.makewebbetter.com/integration/zoho-auth/';
		} else {
			$redirect_uri = rtrim( admin_url(), '/' );
		}

		if ( 'ca' == $domain ) {
			$acc_url        = 'https://accounts.zohocloud.' . $domain . '/oauth/v2/auth';
		} else {
			$acc_url        = 'https://accounts.zoho.' . $domain . '/oauth/v2/auth';
		}
		$auth_params = array(
			'scope'         => 'ZohoCRM.modules.READ,ZohoCRM.modules.CREATE,ZohoCRM.modules.UPDATE,ZohoCRM.modules.DELETE,ZohoCRM.modules.ALL,ZohoCRM.settings.ALL,ZohoCRM.users.Read,ZohoCRM.coql.READ,ZohoCRM.settings.profiles.ALL,ZohoCRM.org.ALL,ZohoCRM.org.READ,aaaserver.profile.READ',
			'client_id'     => $client_id,
			'response_type' => 'code',
			'access_type'   => 'offline',
			'redirect_uri'  => $redirect_uri,
			'state'         => $this->get_oauth_state(),
		);

		$auth_url = add_query_arg( $auth_params, $acc_url );
		return $auth_url;
	}

	/**
	 * Get oauth state with current instance redirect url.
	 *
	 * @return string State.
	 */
	public function get_oauth_state() {

		$admin_redirect_url = rtrim( admin_url(), '/' );
		$args               = array(
			'mwb_nonce'  => wciz_woo_zoho_create_nonce(),
			'mwb_source' => 'zoho',
			'mwb_plugin' => 'zoho-crm',
		);
		$admin_redirect_url = add_query_arg( $args, $admin_redirect_url );
		return urlencode( $admin_redirect_url );
	}

	/**
	 * Get authorization headers.
	 *
	 * @return array headers.
	 */
	public function get_auth_header() {
		$headers = array(
			'Authorization' => sprintf( 'Zoho-oauthtoken %s', $this->get_access_token() ),
		);
		return $headers;
	}


	/**
	 * Get fields for specific module.
	 *
	 * @param  string  $module Module name.
	 * @param  boolean $force  Fetch from api.
	 * @return array           Fields data.
	 */
	public function get_module_fields( $module, $force = false ) {
		// get new access token if current token is expired.
		$data = array();
		if ( ! $this->is_access_token_valid() ) {
			$this->renew_access_token();
		}

		$data = get_option( 'wciz_woo_zoho_' . $module . '_fields', array() );
		if ( ! $force && ! empty( $data ) ) {
			return $data;
		}
		$response = $this->get_fields( $module );
		if ( $this->is_success( $response ) ) {
			$data = $response['data'];
			$this->save_multiselectpicklist_fields( $module, $data );
			update_option( 'wciz_woo_zoho_' . $module . '_fields', $data );
		}
		return $data;
	}

	/**
	 * Get all module data.
	 *
	 * @param  boolean $force Fetch from api.
	 * @return array          Module data.
	 */
	public function get_modules_data( $force = false ) {

		$data = array();
		if ( ! $this->is_access_token_valid() ) {
			$this->renew_access_token();
		}
		$data = get_option( 'wciz_woo_zoho_modules_data', array() );
		if ( ! $force && ! empty( $data ) ) {
			return $data;
		}
		$response = $this->get_modules();
		if ( $this->is_success( $response ) ) {
			$data = $response['data'];
			update_option( 'wciz_woo_zoho_modules_data', $data );
		}
		return $data;
	}

	/**
	 * Get records data.
	 *
	 * @param  string  $module  Module name.
	 * @param  boolean $force  Fetch from api.
	 * @return array           Record data.
	 */
	public function get_records_data( $module, $force = false ) {
		$data = array();
		if ( ! $this->is_access_token_valid() ) {
			$this->renew_access_token();
		}
		$data = get_option( 'wciz_woo_zoho_' . $module . '_data', array() );
		if ( ! $force && ! empty( $data ) ) {
			return $data;
		}

		$response = $this->get_records( $module );
		if ( $this->is_success( $response ) ) {
			$data = $response['data'];
			update_option( 'wciz_woo_zoho_' . $module . '_data', $data );
		}

		return $data;
	}


	/**
	 * Get single record data.
	 *
	 * @param  string $module   Module name.
	 * @param  string $record_id Record id.
	 * @return array            Single record data.
	 */
	public function get_single_record_data( $module, $record_id ) {
		$data = array();
		if ( ! $this->is_access_token_valid() ) {
			$this->renew_access_token();
		}
		$response = $this->get_single_record( $module, $record_id );
		if ( $this->is_success( $response ) ) {
			$data = $response['data'];
		}
		return $data;
	}

	/**
	 * Create single record on Zoho
	 *
	 * @param  string  $module     Module name.
	 * @param  array   $record_data Module data.
	 * @param  boolean $is_bulk    Is a bulk request.
	 * @param  array   $log_data   Data to create log.
	 * @return array               Response data.
	 */
	public function create_single_record( $module, $record_data, $is_bulk = false, $log_data = array() ) {

		$data         = array();
		$filter_exist = false;
		if ( ! $this->is_access_token_valid() ) {
			$this->renew_access_token();
		}

		$request_module = Woo_Crm_Integration_Request_Module::get_instance();
		if ( ! empty( $log_data ) ) {
			$feed_id      = $log_data['feed_id'];
			$filter_exist = $request_module->maybe_check_filter( $feed_id );
		}

		if ( ! empty( $filter_exist ) ) {
			if ( $is_bulk ) {
				$filtered_ids = array();
				$syncing_data = array();
				foreach ( $record_data as $data ) {
					$filter_result = $request_module->wciz_zoho_validate_filter( $filter_exist, $data );
					if ( true === $filter_result ) { // If filter results true, then send data to CRM.
						array_push( $syncing_data, $data );
						array_push( $filtered_ids, $data['post_id'] );
					} elseif ( is_array( $filter_result ) && false == $filter_result['result'] ) { // phpcs:ignore
						array_push( $syncing_data, $data );
						array_push( $filtered_ids, $data['post_id'] );
					}
				}

				if ( ! empty( $syncing_data ) ) {
					$log_data['bulk_ids'] = $filtered_ids;
					$response             = $this->create_or_update_record( $module, $syncing_data, $is_bulk, $log_data );
				}
			} else {
				$filter_result = $request_module->wciz_zoho_validate_filter( $filter_exist, $record_data );
				if ( true === $filter_result ) { // If filter results true, then send data to CRM.
					$response = $this->create_or_update_record( $module, $record_data, $is_bulk, $log_data );
				} elseif ( is_array( $filter_result ) && false == $filter_result['result'] ) { // phpcs:ignore
					$response = $this->create_or_update_record( $module, $record_data, $is_bulk, $log_data );
				}
			}
		} else {
			$response = $this->create_or_update_record( $module, $record_data, $is_bulk, $log_data );
		}

		if ( $this->is_success( $response ) ) {
			$data = $response['data'];
		}
		return $data;
	}

	/**
	 * Create of update record data.
	 *
	 * @param  string  $module     Module name.
	 * @param  array   $record_data Module data.
	 * @param  boolean $is_bulk    Is a bulk request.
	 * @param  array   $log_data   Data to create log.
	 * @return array               Response data.
	 */
	private function create_or_update_record( $module, $record_data, $is_bulk, $log_data ) {

		$feed_id = ! empty( $log_data['feed_id'] ) ? $log_data['feed_id'] : false;

		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v2/' . $module . '/upsert';

		if ( true == $is_bulk ) { //phpcs:ignore
			foreach ( $record_data as $keys => $data ) {
				unset( $record_data[ $keys ]['post_id'] );
			}
			$request['data'] = $record_data;
		} else {
			unset( $record_data['post_id'] );
			$request['data'] = array( $record_data );
		}

		if ( $feed_id ) {
			$duplicate_check_fields = get_post_meta( $feed_id, 'primary_field', false );
			if ( ! empty( $duplicate_check_fields ) ) {
				$request['duplicate_check_fields'] = $duplicate_check_fields;
			}
		}

		$request_data = wp_json_encode( $request );

		$headers  = $this->get_auth_header();
		$response = $this->post( $endpoint, $request_data, $headers );
		$this->log_request(
			__FUNCTION__,
			$endpoint,
			$request,
			$response
		);

		if ( ! empty( $log_data ) ) {
			$this->log_request_in_db(
				__FUNCTION__,
				$module,
				$request,
				$response,
				$log_data
			);
		}
		return $response;
	}

	/**
	 * Fetch object id of created record.
	 *
	 * @param  array $response Api response.
	 * @return string           Id of object.
	 */
	private function get_object_id_from_response( $response ) {
		$id = '';
		if ( isset( $response['data'] ) && isset( $response['data']['data'] ) ) {
			$data = $response['data']['data'];
			if ( isset( $data[0] ) && isset( $data[0]['details'] ) ) {
				return ! empty( $data[0]['details']['id'] ) ? $data[0]['details']['id'] : $id;
			}
		}
		return $id;
	}


	/**
	 * Log request and response in database.
	 *
	 * @param  string $event       Event of which data is synced.
	 * @param  string $zoho_object Update or create zoho object.
	 * @param  array  $request     Request data.
	 * @param  array  $response    Api response.
	 * @param  array  $log_data    Extra data to be logged.
	 */
	private function log_request_in_db( $event, $zoho_object, $request, $response, $log_data ) {

		$zoho = Woo_Crm_Integration_For_Zoho_Fw::get_instance();

		$feed_id = ! empty( $log_data['feed_id'] ) ? $log_data['feed_id'] : false;

		$event = ! empty( $log_data['feed_title'] ) ?
		$log_data['feed_title'] :
		$zoho->get_feed_title( $feed_id );

		$woo_object = ! empty( $log_data['woo_object'] ) ? $log_data['woo_object'] : false;

		if ( ! empty( $log_data['bulk_ids'] ) && is_array( $log_data['bulk_ids'] ) ) {

			$code     = $response['code'];
			$message  = $response['message'];
			$bulk_ids = $log_data['bulk_ids'];

			if ( ! empty( $response['data'] ) && is_array( $response['data'] ) ) {
				if ( ! empty( $response['data']['data'] ) && is_array( $response['data']['data'] ) ) {

					$response_data = $response['data']['data'];

					foreach ( $response_data as $key => $data ) {
						$temp_response = array(
							'code'    => $code,
							'message' => $message,
							'data'    => array(
								'data' => array( $data ),
							),
						);

						$is_success = $this->is_success_response( $temp_response );
						$zoho_id    = $this->get_object_id_from_response( $temp_response );

						$temp_request = $request['data'][ $key ];
						$woo_id       = $bulk_ids[ $key ];
						$log_data     = array(
							'event'       => $event,
							'zoho_object' => $zoho_object,
							'woo_object'  => $woo_object,
							'request'     => json_encode( $temp_request ), //phpcs:ignore
							'response'    => json_encode( $temp_response ), //phpcs:ignore
							'zoho_id'     => $zoho_id,
							'woo_id'      => $woo_id,
							'time'        => time(),
						);

						$this->set_woo_response_meta( $feed_id, $woo_id, $zoho_id, $is_success, $woo_object );
						$this->insert_log_data( $log_data );
					}
				}
			}
		} else {

			$zoho_id    = $this->get_object_id_from_response( $response );
			$is_success = $this->is_success_response( $response );
			$request    = json_encode( $request ); //phpcs:ignore
			$response   = json_encode( $response ); //phpcs:ignore
			$woo_id     = ! empty( $log_data['woo_id'] ) ? $log_data['woo_id'] : false;
			$time       = time();
			$log_data   = compact( 'event', 'zoho_object', 'request', 'response', 'zoho_id', 'woo_id', 'woo_object', 'time' );
			$this->set_woo_response_meta( $feed_id, $woo_id, $zoho_id, $is_success, $woo_object );
			$this->insert_log_data( $log_data );
		}
	}

	/**
	 * Insert data into database.
	 *
	 * @param  array $log_data Log data.
	 */
	private function insert_log_data( $log_data ) {

		if ( ! Woo_Crm_Integration_For_Zoho_Admin::is_log_enable() ) {
			return;
		}

		global $wpdb;
		$table    = $wpdb->prefix . 'wciz_woo_zoho_log';
		$response = $wpdb->insert( $table, $log_data ); //phpcs:ignore
	}

	/**
	 * Log request in sync log.
	 *
	 * @param  string $event    Event.
	 * @param  string $endpoint Endpoint.
	 * @param  array  $request  Request data.
	 * @param  array  $response Response data.
	 */
	private function log_request1( $event, $endpoint, $request, $response ) {

		$url = $this->base_url . $endpoint;

		$log_dir = WC_LOG_DIR . 'wciz-woo-zoho-' . gmdate( 'Y-m-d' ) . '.log';

		if ( ! is_dir( $log_dir ) ) {
			@fopen( WC_LOG_DIR . 'wciz-woo-zoho-' . gmdate( 'Y-m-d' ) . '.log', 'a' ); //phpcs:ignore
		}

		if ( ! is_admin() ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		global $wp_filesystem;  // Define global object of WordPress filesystem.
		WP_Filesystem();        // Intialise new file system object.

		if ( file_exists( $log_dir ) ) {
			$file_data = $wp_filesystem->get_contents( $log_dir );
		} else {
			$file_data = '';
		}

		$log  = 'Url : ' . $url . PHP_EOL;
		$log .= 'Method : ' . $event . PHP_EOL;
		$log .= 'Time: ' . current_time( 'F j, Y  g:i a' ) . PHP_EOL;
		$log .= 'Request : ' . wp_json_encode( $request ) . PHP_EOL;
		$log .= 'Response : ' . wp_json_encode( $response ) . PHP_EOL;
		$log .= '------------------------------------' . PHP_EOL;

		$file_data .= $log;
		$wp_filesystem->put_contents( $log_dir, $file_data );
	}
	private function log_request( $event, $endpoint, $request, $response ) {

		$url = $this->base_url . $endpoint;
	
		$log_file = WC_LOG_DIR . 'wciz-woo-zoho-' . gmdate( 'Y-m-d' ) . '.log'; // Use correct log file path
	
		$log  = "Url : $url" . PHP_EOL;
		$log .= "Method : $event" . PHP_EOL;
		$log .= "Time: " . current_time( 'F j, Y  g:i a' ) . PHP_EOL;
		$log .= "Request : " . wp_json_encode( $request ) . PHP_EOL;
		$log .= "Response : " . wp_json_encode( $response ) . PHP_EOL;
		$log .= "------------------------------------" . PHP_EOL;
	
		// Write log without loading the entire file
		file_put_contents($log_file, $log, FILE_APPEND | LOCK_EX);
	}
	

	/**
	 * Create a new record.
	 *
	 * @param  string $module      Module to be created.
	 * @param  array  $record_data Record data.
	 * @return array               Response data.
	 */
	private function create_record( $module, $record_data ) {
		$this->base_url  = $this->get_api_domain();
		$endpoint        = '/crm/v2/' . $module;
		$request['data'] = array( $record_data );
		$request_data    = wp_json_encode( $request );
		$headers         = $this->get_auth_header();
		return $this->post( $endpoint, $request_data, $headers );
	}

	/**
	 * Deleted a new record.
	 *
	 * @param  string $module      Module to be deleted.
	 * @param  array  $record_data Record data.
	 * @return array               Response data.
	 */
	public function delete_record( $record_id, $module, $record_data ) {
		if ( ! $this->is_access_token_valid() ) {
			$this->renew_access_token();
		}
		$this->base_url  = $this->get_api_domain();
		$endpoint        = '/crm/v2/' . $module . '?ids=' . $record_id;
		$request_data    = array();
		$headers         = $this->get_auth_header();
		return $this->delete( $endpoint, $request_data, $headers );
	}

	/**
	 * Get all records for a specific module.
	 *
	 * @param  string $module Module name.
	 * @return array          Response.
	 */
	private function get_records( $module ) {
		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v2/' . $module;
		$data           = array();
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, $data, $headers );
		$this->log_request( __FUNCTION__, $endpoint, $data, $response );
		return $response;
	}

	/**
	 * Get single records for a specific module.
	 *
	 * @param  string $module Module name.
	 * @return array          Response.
	 */
	public function get_record( $record_id, $module, $data = array() ) {
		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v2/' . $module . '/' . $record_id;
		$data           = array();
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, $data, $headers );
		$this->log_request( __FUNCTION__, $endpoint, $data, $response );
		return $response;
	}

	/**
	 * Get single record.
	 *
	 * @param  string $module    Module name.
	 * @param  string $record_id Id of the record.
	 * @return array            Response.
	 */
	private function get_single_record( $module, $record_id ) {
		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v2/' . $module . '/' . $record_id;
		$data           = array();
		$headers        = $this->get_auth_header();
		return $this->get( $endpoint, $data, $headers );
	}

	/**
	 * Get modules.
	 *
	 * @return array Response containing all modules.
	 */
	private function get_modules() {
		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v2/settings/modules';
		$data           = array();
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, $data, $headers );
		$this->log_request( __FUNCTION__, $endpoint, $data, $response );
		return $response;
	}

	/**
	 * Get modules.
	 *
	 * @return array Response containing all modules.
	 */
	public function create_abandoned_cart_module() {
		$profiles = $this->get_profiles();
		if ( !empty( $profiles['code'] ) && 200 == $profiles['code'] ) {
			if ( !empty( $profiles['data'] ) ) {
				if ( isset( $profiles['data']['profiles'] ) && !empty( $profiles['data']['profiles'] ) ) {
					foreach ( $profiles['data']['profiles'] as $prof_key => $prof_value ) {
						if ( 'Administrator' == $prof_value['api_name'] ) {
							$profile_id = $prof_value['id'];
						}
					}
				}
			}
		} else {
			$profile_id = '';
		}
		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v6/settings/modules';
		$data           = '{
			"modules": [
				{
					"plural_label": "Abandoned carts",
					"singular_label": "Abandoned cart",
					"profiles": [
						{
							"id": "' . $profile_id . '"
						}
					],
					"api_name": "abandoned_cart",
				}
			]
		}';
		$headers        = $this->get_auth_header();
		$response       = $this->post( $endpoint, $data, $headers );
		if ( '201' == $response['code'] && '400' != $response['code'] ) {
			update_option( 'wciz_zoho_abandoned_cart_object_created', 'yes' );
			$fields = $this->create_fields('Abandoned_cart');
		}
		$this->log_request( __FUNCTION__, $endpoint, $data, $response );
		return $response;
	}

	/**
	 * Get fields assosiated with a module.
	 *
	 * @param  string $module Module name.
	 * @return array          Response.
	 */
	public function get_profiles() {
		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v6/settings/profiles';
		$data           = array();
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, $data, $headers );
		$this->log_request( __FUNCTION__, $endpoint, $data, $response );
		return $response;
	}

	/**
	 * Get fields assosiated with a module.
	 *
	 * @param  string $module Module name.
	 * @return array          Response.
	 */
	private function get_fields( $module ) {
		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v2/settings/fields';
		$data           = array( 'module' => $module );
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, $data, $headers );
		$this->log_request( __FUNCTION__, $endpoint, $data, $response );
		return $response;
	}
	public function get_org( ) {
		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v4/org';
		$data           = array();
		$headers        = $this->get_auth_header();
		$header['Authorization']='Zoho-oauthtoken ' . $this->get_access_token();
		$args=array(
			'timeout' => '30',
			'headers' => $header,
		   );
		$response=$this->get( $endpoint, $data, $headers );
		return $response;
	}

	/**
	 * Get user details used for authetication.
	 */
	public function get_oauth_user_details( ) {
		$this->base_url = $this->get_api_domain();
		$header['Authorization']='Zoho-oauthtoken ' . $this->get_access_token();
		$args=array(
			'timeout' => '30',
			'headers' => $header,
		   );
		   $response = wp_remote_get(  'https://accounts.zoho.in/oauth/user/info'  , $args);
		return $response;
	}


	/**
	 * Get fields assosiated with a module.
	 *
	 * @param  string $module Module name.
	 * @return array          Response.
	 */
	private function create_fields( $module ) {
		$profiles = $this->get_profiles();
		if ( !empty( $profiles['code'] ) && 200 == $profiles['code'] ) {
			if ( !empty( $profiles['data'] ) ) {
				if ( isset( $profiles['data']['profiles'] ) && !empty( $profiles['data']['profiles'] ) ) {
					foreach ( $profiles['data']['profiles'] as $prof_key => $prof_value ) {
						if ( 'Administrator' == $prof_value['api_name'] ) {
							$profile_id = $prof_value['id'];
						}
					}
				}
			}
		} else {
			$profile_id = '';
		}
		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v6/settings/fields?module=' . $module;
		$data           = '{
			"fields": [
				{
					"field_label": "Abandoned cart email",
					"data_type": "text",
					"length": 150,
					"tooltip": {
						"name": "static_text",
						"value": "Enter your name"
					},
					"profiles": [
						{
							"id": "' . $profile_id . '",
							"permission_type": "read_write"
						},
					],
					"unique": {
                		"case_sensitive": false
            		},
				},
				{
					"field_label": "Abandoned cart url",
					"data_type": "text",
					"length": 150,
					"tooltip": {
						"name": "static_text",
						"value": "Enter your name"
					},
					"profiles": [
						{
							"id": "' . $profile_id . '",
							"permission_type": "read_write"
						},
					],
				},
				{
					"field_label": "Abandoned cart amount",
					"data_type": "text",
					"length": 150,
					"tooltip": {
						"name": "static_text",
						"value": "Enter your name"
					},
					"profiles": [
						{
							"id": "' . $profile_id . '",
							"permission_type": "read_write"
						},
					],
				},
				{
					"field_label": "Abandoned cart products",
					"data_type": "text",
					"length": 150,
					"tooltip": {
						"name": "static_text",
						"value": "Enter your name"
					},
					"profiles": [
						{
							"id": "' . $profile_id . '",
							"permission_type": "read_write"
						},
					],
				},
				{
					"field_label": "Abandoned cart products html",
					"data_type": "textarea",
					"length": 32000,
					"textarea": {
                		"type": "large"
            		},
					"tooltip": {
						"name": "static_text",
						"value": "Enter your name"
					},
					"profiles": [
						{
							"id": "' . $profile_id . '",
							"permission_type": "read_write"
						},
					],
				}
			]
		}';
		$headers        = $this->get_auth_header();
		$response       = $this->post( $endpoint, $data, $headers );
		$this->log_request( __FUNCTION__, $endpoint, $data, $response );
		return $response;
	}

	/**
	 * Check if resposne has success code.
	 *
	 * @param  array $response  Response data.
	 * @return boolean           Success.
	 */
	private function is_success( $response ) {
		if ( isset( $response['code'] ) ) {
			return in_array( $response['code'], array( 200, 201, 204, 202 ) ); //phpcs:ignore
		}
		return false;
	}

	/**
	 * Set object id in woocommerce meta.
	 *
	 * @param string $feed_id Feed id.
	 * @param string $woo_id  WooCommerce Order/Customer/Product id.
	 * @param string $zoho_id Zoho object id.
	 * @param boolen $success Is success.
	 * @param string $woo_object Type of woo object.
	 */
	private function set_woo_response_meta( $feed_id, $woo_id, $zoho_id, $success, $woo_object = 'shop_order' ) {

		$meta_function = ( 'users' == $woo_object ) ? 'update_user_meta' : 'update_post_meta';

		if ( $success ) {
			if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {

				if ( 'shop_order' == $woo_object ) {
					$order = wc_get_order($woo_id);
					$order->update_meta_data( 'wciz_zoho_feed_' . $feed_id . '_association', $zoho_id );
					$order->save();
				} else {
					$meta_function(
						$woo_id,
						'wciz_zoho_feed_' . $feed_id . '_association',
						$zoho_id
					);
				}
			} else {
				$meta_function(
					$woo_id,
					'wciz_zoho_feed_' . $feed_id . '_association',
					$zoho_id
				);
			}
		}
	}

	/**
	 * Check if resposne has success code.
	 *
	 * @param  array $response  Response data.
	 * @return boolean           Success.
	 */
	private function is_success_response( $response ) {

		if ( ! empty( $response['code'] ) && ( ( 200 === $response['code'] || '200' === $response['code'] ) || 'OK' === $response['message'] ) ) {
			return true;
		} elseif ( ! empty( $response['code'] ) && ( ! empty( $response['data']['data'][0] ) && 'SUCCESS' === $response['data']['data'][0]['code'] ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check if response has error.
	 *
	 * @param  array $response Response array.
	 * @return boolean          if has error.
	 */
	private function check_response_error( $response ) {

		if ( ! empty( $response['data'] ) ) {
			if ( ! empty( $response['data']['error'] ) ) {
				return false;
			} else {
				return true;
			}
		}
		return false;
	}

	/**
	 * Save api name of picklist fields.
	 *
	 * @param  string $module     Module name.
	 * @param  array  $field_data Field data.
	 */
	public function save_multiselectpicklist_fields( $module, $field_data ) {

		$all_picklist_fields = get_option( 'wciz_woo_zoho_multiselectpicklist_fields', array() );

		if ( ! empty( $field_data['fields'] ) ) {
			foreach ( $field_data['fields'] as $key => $field ) {
				if ( 'multiselectpicklist' == $field['data_type'] ) {
					$all_picklist_fields[ $module ][] = $field['api_name'];
				}
			}
		}

		update_option( 'wciz_woo_zoho_multiselectpicklist_fields', $all_picklist_fields );
	}

	/**
	 * Fetch zoho records.
	 *
	 * @param  string $module     Module name.
	 * @param  array  $args Data args.
	 */
	public function fetch_records( $module, $args ) {

		if ( ! $this->is_access_token_valid() ) {
			$this->renew_access_token();
		}

		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v2/' . $module;
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, $args, $headers );
		$this->log_request( __FUNCTION__, $endpoint, $args, $response );
		$data = array();
		if ( $this->is_success( $response ) ) {
			$data = $response['data'];
		}
		return $data;
	}

	/**
	 * Upload the image over crm.
	 *
	 * @param string $module crm object.
	 * @param int    $record_id  record id.
	 * @param string $file_path file path.
	 * @return void
	 */
	public function upload_record_image( $module, $record_id, $file_path ) {

		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v2/' . $module . '/' . $record_id . '/photo';
		$header['Authorization']='Zoho-oauthtoken ' . $this->get_access_token();
		$body=array( 'img'=>array( 'image.png'=>$file_path ) );

		if (!empty($body) && is_array($body) && isset($body['img'])) {
			$files = array();
			$file_name='attachments[]';
			if (!empty($body['img'])) {
				$files=$body['img'];
				unset($body['img']);
				$file_name='file';
			}
			$boundary = wp_generate_password( 24 );
			$delimiter = '-------------' . $boundary;
			$header['Content-Type']='multipart/form-data; boundary=' . $delimiter;
			$body = $this->get_request_body_for_attachment($boundary, $body, $files, $file_name);
		}
	   
		$args=array(
		 'method' => 'POST',
		 'timeout' => '30',
		 'headers' => $header,
		 'body' => $body,
		);

		$response = wp_remote_request( $this->base_url . $endpoint , $args);
		
		if ( ! is_wp_error( $response ) ) {
			$this->log_request(
				__FUNCTION__,
				$endpoint,
				array(),
				$response
			);
		}
	}

	public function get_request_body_for_attachment( $boundary, $fields, $files, $file_name = 'attachments[]' ) {
		$data = '';
		$eol = "\r\n";
	
		$delimiter = '-------------' . $boundary;
	
		foreach ($fields as $name => $content) {
			$data .= '--' . $delimiter . $eol
				. 'Content-Disposition: form-data; name="' . $name . '"' . $eol . $eol
				. $content . $eol;
		}

		foreach ($files as $name => $file) {
			$name=basename($file);
			$content = wp_remote_get( $file );
			$data .= '--' . $delimiter . $eol
				. 'Content-Disposition: form-data; name="' . $file_name . '"; filename="' . $name . '"' . $eol
				. 'Content-Transfer-Encoding: binary' . $eol;
	
			$data .= $eol;
			$data .= $content['body'] . $eol;
		}
		$data .= '--' . $delimiter . '--' . $eol;
	
	
		return $data;
	}

	/**
	 * Get link to data sent over Zoho.
	 *
	 * @param      string $crm_id   An array of data synced over Zoho.
	 * @param      string $object   Crm Object.
	 * @since      1.0.0
	 * @return     string
	 */
	public function get_crm_link( $crm_id = false, $object = '' ) {

		if ( false == $crm_id || empty( $object ) ) { // phpcs:ignore
			return;
		}
		$_domain = get_option( 'wciz_zoho_domain', 'in' );
		if ( 'Sales_Orders' == $object ) {
			$object = 'SalesOrders';
		}

		if ( strpos($object, 'ber') !== false || strpos($object, 'ship') !== false ) {
			if ( !empty( get_option('wciz_woo_zoho_modules_data') ) ) {
				if ( isset( get_option('wciz_woo_zoho_modules_data')['modules'] ) ) {
					$modules = get_option('wciz_woo_zoho_modules_data')['modules'];
					foreach ( $modules as $mod_key => $mod_value ) {
						if ( strpos($mod_value['api_name'], 'ber') !== false || strpos($mod_value['api_name'], 'ship') !== false ) {
							$object = $mod_value['module_name'];
						}
					}
				}
			}
		}
		if ( 'abandoned_cart' == $object ) {
			if ( !empty( get_option('wciz_woo_zoho_modules_data') ) ) {
				if ( isset( get_option('wciz_woo_zoho_modules_data')['modules'] ) ) {
					$modules = get_option('wciz_woo_zoho_modules_data')['modules'];
					foreach ( $modules as $mod_key => $mod_value ) {
						if ( 'abandoned_cart' == $mod_value['api_name'] ) {
							$object = $mod_value['module_name'];
						}
					}
				}
			}
		}
		
		if ('ca'== $_domain) {
			$link = 'https://crm.zohocloud.' . $_domain . '/crm/tab/' . $object . '/' . $crm_id;
		} else {
			$link = 'https://crm.zoho.' . $_domain . '/crm/tab/' . $object . '/' . $crm_id;
		}

		return $link;
	}

	/**
	 * Create single record.
	 *
	 * @param      string $module         Crm object.
	 * @param      array  $record_data    An array of data to be sent over crm.
	 * @param      bool   $is_bulk        Whether to send bulk data.
	 * @param      array  $log_data       An array of data to log.
	 * @param      bool   $manual_sync    If synced manually.
	 * @since      1.0.0
	 * @return     array
	 */
	public function create_cf7_single_record( $module, $record_data, $is_bulk = false, $log_data = array(), $manual_sync = false ) {

		$data = array();
		if ( ! $this->is_access_token_valid() ) {
			$this->renew_access_token();
		}
		$response = $this->create_or_update_cf7_record( $module, $record_data, $is_bulk, $log_data, $manual_sync );
		if ( $this->is_success( $response ) ) {
			$data = $response['data'];
		} else {
			$data = $response;
		}
		return $data;
	}

	/**
	 * Create or update a record.
	 *
	 * @param     string $module         Crm object.
	 * @param     array  $record_data    An array of data to be sent to zoho.
	 * @param     bool   $is_bulk        Whether to send bulk data.
	 * @param     array  $log_data       An array of data to log.
	 * @param     bool   $manual_sync    If synced manually.
	 * @since     1.0.0
	 * @return    array
	 */
	public function create_or_update_cf7_record( $module, $record_data, $is_bulk, $log_data, $manual_sync ) {

		$feed_id        = ! empty( $log_data['feed_id'] ) ? $log_data['feed_id'] : false;
		$this->base_url = $this->get_api_domain();
		$endpoint       = '/crm/v2/' . $module . '/upsert';

		if ( true == $is_bulk ) { // phpcs:ignore
			$request['data'] = $record_data;
		} else {
			$request['data'] = array( $record_data );
		}

		// To determine if manual sync or real time sync.
		if ( $manual_sync && ! empty( $log_data['method'] ) ) {
			$event = $log_data['method'];
		} else {
			$event = __FUNCTION__;
		}

		// If primary key is set.
		if ( $feed_id ) {
			$duplicate_check_fields = get_post_meta( $feed_id, 'wciz_zcf7_primary_field' );
			if ( ! empty( $duplicate_check_fields ) ) {
				$request['duplicate_check_fields'] = $duplicate_check_fields;
			}
		}
		$request_data = wp_json_encode( $request );
		$headers      = $this->get_auth_header();
		$response     = $this->post( $endpoint, $request_data, $headers );

		$this->log_request(
			__FUNCTION__,
			$endpoint,
			$request,
			$response
		);

		$this->log_cf7_request_in_db( $event, $module, $request, $response, $log_data );

		return $response;
	}

	/**
	 * Insert log data in db.
	 *
	 * @param     string $event          Trigger event/ Feed .
	 * @param     string $zoho_object    Name of zoho module.
	 * @param     array  $request        An array of request data.
	 * @param     array  $response       An array of response data.
	 * @param     array  $log_data       Data to log.
	 * @return    void
	 */
	public function log_cf7_request_in_db( $event, $zoho_object, $request, $response, $log_data ) {
		$zoho_id = $this->get_object_id_from_cf7_response( $response );
		if ( '-' == $zoho_id ) { // phpcs:ignore
			if ( ! empty( $log_data['id'] ) ) {
				$zoho_id = $log_data['id'];
			}
		}
		if ( $this->is_success( $response ) ) {

			if ( isset( $response['data']['data'][0]['code'] ) ) {

				$meta = $response['data']['data'][0];
				if ( 'SUCCESS' == $meta['code'] ) {
					if ( isset( $meta['action'] ) && 'insert' == $meta['action'] ) { // phpcs:ignore
						$count = get_option( 'wciz_zcf7_synced_forms_count', 0 );
						update_option( 'wciz_zcf7_synced_forms_count', $count + 1 );
					}
				}
			};
		}
		$request  = json_encode( $request ); // phpcs:ignore
		$response = json_encode( $response ); // phpcs:ignore

		$feed        = ! empty( $log_data['feed_name'] ) ? $log_data['feed_name'] : false;
		$feed_id     = ! empty( $log_data['feed_id'] ) ? $log_data['feed_id'] : false;
		$event       = ! empty( $event ) ? $event : false;
		$zoho_object = ! empty( $log_data['zoho_object'] ) ? $log_data['zoho_object'] : false;

		$time     = time();
		$log_data = compact( 'event', 'zoho_object', 'request', 'response', 'zoho_id', 'feed_id', 'feed', 'time' );
		$this->insert_log_data_cf7( $log_data );
	}

	/**
	 * Insert data to db.
	 *
	 * @param      array $data    Data to log.
	 * @since      1.0.0
	 * @return     void
	 */
	public function insert_log_data_cf7( $data ) {

		if ( ! Woo_Crm_Integration_For_Zoho_Admin::is_log_enable() ) {
			return;
		}

		global $wpdb;
		$table    = $wpdb->prefix . 'wciz_zcf7_log';
		$response = $wpdb->insert( $table, $data ); // phpcs:ignore
	}

	/**
	 * Fetch object id of created record.
	 *
	 * @param  array $response Api response.
	 * @return mixed           Id of object.
	 */
	private function get_object_id_from_cf7_response( $response ) {
		$id = '';
		if ( isset( $response['data'] ) && isset( $response['data']['data'] ) ) {
			$data = $response['data']['data'];
			if ( isset( $data[0] ) && isset( $data[0]['details'] ) ) {
				$id = ! empty( $data[0]['details']['id'] ) ? $data[0]['details']['id'] : $id;
			}
		}

		return $id;
	}
}
