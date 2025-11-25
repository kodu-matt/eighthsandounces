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
class Woo_Crm_Integration_Api_Base {

	/**
	 * Zoho base url.
	 *
	 * @since    1.0.0
	 * @var      string    $base_url    Zoho base url.
	 */
	public $base_url;

	/**
	 * Get Request.
	 *
	 * @param string $endpoint Api endpoint of zoho.
	 * @param array  $data Data to be used in request.
	 * @param array  $headers header to be used in request.
	 */
	public function get( $endpoint, $data = array(), $headers = array() ) {
		return $this->request( 'GET', $endpoint, $data, $headers );
	}

	/**
	 * Post Request.
	 *
	 * @param string $endpoint Api endpoint of zoho.
	 * @param array  $data Data to be used in request.
	 * @param array  $headers header to be used in request.
	 */
	public function post( $endpoint, $data = array(), $headers = array() ) {
		return $this->request( 'POST', $endpoint, $data, $headers );
	}

	/**
	 * Delete Request.
	 *
	 * @param string $endpoint Api endpoint of zoho.
	 * @param array  $data Data to be used in request.
	 * @param array  $headers header to be used in request.
	 */
	public function delete( $endpoint, $data = array(), $headers = array() ) {
		return $this->request( 'DELETE', $endpoint, $data, $headers );
	}

	/**
	 * Send api request
	 *
	 * @param string $method   HTTP method.
	 * @param string $endpoint Api endpoint.
	 * @param array  $data     Request data.
	 * @param array  $headers header to be used in request.
	 */
	private function request( $method, $endpoint, $data = array(), $headers = array() ) {

		$method  = strtoupper( trim( $method ) );
		$url     = $this->base_url . $endpoint;
		$headers = array_merge( $headers, $this->get_headers() );
		$args    = array(
			'method'    => $method,
			'headers'   => $headers,
			'timeout'   => 20, // @codingStandardsIgnoreLine
			/**
			 * Filters the value of zoho tabs array.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed  boolean.
			 */
			'sslverify' => apply_filters( 'wciz_woo_zoho_use_sslverify', true ),
		);
		
		if ( ! empty( $data ) ) {
			if ( in_array( $method, array( 'GET', 'DELETE' ), true ) ) {
				$url = add_query_arg( $data, $url );
			} else {
				$args['body'] = $data;
			}
		}
		/**
		 * Filters the value of zoho tabs array.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $args args.
		 * @param mixed $url url.
		 */
		$args     = apply_filters( 'wciz_woo_zoho_http_request_args', $args, $url );
		$response = wp_remote_request( $url, $args );

		if ( is_wp_error( $response ) ) {
			return array();
		}

		$response_data = array();

		// Add better exception handling.
		try {
			$response_data = $this->parse_response( $response );
		} catch ( Exception $e ) {

			// Got an error in api response may be due to unappropriate connection. Check logs for more information.
			return $response_data;
		}

		return $response_data;
	}

	/**
	 * Parse Api response.
	 *
	 * @param array $response Raw response.
	 * @throws Exception Exception.
	 * @return array filtered reponse.
	 */
	private function parse_response( $response ) {

		if ( $response instanceof WP_Error ) {
			throw new Exception( 'Error', 0 );
		}
		$code    = (int) wp_remote_retrieve_response_code( $response );
		$message = wp_remote_retrieve_response_message( $response );
		$body    = wp_remote_retrieve_body( $response );
		$data    = json_decode( $body, ARRAY_A );
		return compact( 'code', 'message', 'data' );
	}

	/**
	 * Get headers.
	 *
	 * @return array headers.
	 */
	public function get_headers() {
		return array();
	}
}
