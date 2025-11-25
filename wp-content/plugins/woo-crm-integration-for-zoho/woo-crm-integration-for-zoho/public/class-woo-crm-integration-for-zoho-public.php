<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/public
 */
class Woo_Crm_Integration_For_Zoho_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Instance of Api class.
	 *
	 * @since    1.0.0
	 * @var      object    $zoho_api  Instance of the Api class.
	 */
	private $zoho_api;

	/**
	 * Instance of Request module class.
	 *
	 * @since    1.0.0
	 * @var      object    $request_module  Instance of the Request module class.
	 */
	private $request_module;

	/**
	 * Instance of Cpt module class.
	 *
	 * @since    1.0.0
	 * @var      object    $request_module  Instance of the Cpt module class.
	 */
	private $cpt_module;

	/**
	 * Insatance of the current fom fields
	 *
	 * @since    1.0.0
	 * @var      array    An array of form fields data.
	 */
	public $form_fields;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->zoho_api       = Woo_Crm_Integration_Zoho_Api::get_instance();
		$this->request_module = Woo_Crm_Integration_Request_Module::get_instance();
		$this->cpt_module     = new Woo_Crm_Integration_For_Zoho_Cpt();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Crm_Integration_For_Zoho_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Crm_Integration_For_Zoho_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-crm-integration-for-zoho-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Crm_Integration_For_Zoho_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Crm_Integration_For_Zoho_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'js/woo-crm-integration-for-zoho-public.js', array( 'jquery' ), $this->version, false );
		
		$ajax_data = array(
			'crm'                => 'zoho',
			'ajax_url'           => admin_url( 'admin-ajax.php' ),
			'ajax_action'        => 'wciz_woo_zoho_ajax_public',
			'ajax_nonce'         => wp_create_nonce( 'ajax_nonce' ),
		);

		wp_localize_script( $this->plugin_name . '-public', 'ajax_data_public', $ajax_data );
	}

	/**
	 * Add dummy edit user profile hook to manage param count.
	 *
	 * @param int $user_id Id of updated user.
	 *
	 * @since 1.0.0
	 */
	public function add_wciz_edit_user_profile_update_hook( $user_id ) {
		if ( ! is_admin() ) {
			/**
			 * Trigger associated Feeds.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed  $user_id  Woo user id.
			 * @param mixed array.
			 */
			do_action( 'wciz_edit_user_profile_update', $user_id, array() );
		}
	}

		/**
		 * Get contact form data.
		 *
		 * @param    object $cf    Submitted form object.
		 * @since    1.0.0
		 */
	public function wciz_cf7_integration_fetch_input_data( $cf ) {

		$form_data = $this->request_module->retrieve_form_data( $cf );
		$zoho              = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$this->form_fields = $zoho->getMappingOptionsCF7( $form_data['id'] );

		$this->wciz_cf7_integration_create_form_submission( $form_data, $_SERVER );
	}

	/**
	 * Create form data enrty.
	 * For org instantly save data for pro create action hook to save form entries.
	 *
	 * @param    array $form_data         An array of form data.
	 * @param    mixed $additional_info   An array of additional information related to form entry.
	 * @since    1.0.0
	 * @return   mixed
	 */
	public function wciz_cf7_integration_create_form_submission( $form_data, $additional_info ) {

		if ( empty( $form_data ) || ! is_array( $form_data ) ) {
			return;
		}
		/**
		 * Trigger associated Feeds.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  $form_data  Cf7 form data.
		 * @param mixed $additional_info Additional info.
		 */
		do_action( 'wciz_cf7_save_submission', $form_data, $additional_info );

		$this->wciz_cf7_integration_send_to_crm( $form_data );
	}

		/**
		 * Send form data over crm.
		 *
		 * @param     array $data   An array of form data and entries.
		 * @since     1.0.0
		 * @return    mixed
		 */
	public function wciz_cf7_integration_send_to_crm( $data = array() ) {

		if ( empty( $data ) || ! is_array( $data ) ) {
			return;
		}

		if ( ! $this->zoho_api->is_access_token_valid() ) {
			$this->zoho_api->renew_access_token();
		}

		$all_feeds = $this->request_module->get_feeds_by_form_id( $data['id'] );

		/**
		 * Filters the value of feeds.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $all_feeds  feeds array.
		 */
		$active_feeds = apply_filters( 'wciz_zcf7_filtered_feeds', $all_feeds );
		$filter_exist = false;

		if ( ! empty( $active_feeds ) && is_array( $active_feeds ) ) {
			foreach ( $active_feeds as $key => $feed_id ) {

				$filter_exist = $this->request_module->maybe_check_filter_cf7( $feed_id );

				$crm_object = $this->cpt_module->get_feed_data( $feed_id, 'wciz_zcf7_object', '' );

				$log_data = array(
					'feed_id'     => $feed_id,
					'feed_name'   => get_the_title( $feed_id ),
					'zoho_object' => $crm_object,
				);

				$request = $this->request_module->get_crm_request_cf7( $crm_object, $feed_id, $data['values'] );

				if ( ! empty( $filter_exist ) ) {
					$filter_result = $this->wciz_cf7_integration_validate_filter( $filter_exist, $data['values'] );

					if ( true === $filter_result ) { // If filter results true, then send data to CRM.
						$result = $this->zoho_api->create_cf7_single_record( $crm_object, $request, false, $log_data );
					} elseif ( is_array( $filter_result ) && false == $filter_result['result'] ) { // phpcs:ignore
						$result = $this->zoho_api->create_cf7_single_record( $crm_object, $request, false, $log_data );
					}
				} else {

					$result = $this->zoho_api->create_cf7_single_record( $crm_object, $request, false, $log_data );

				}
			}
		}
	}

	/**
	 * Validate form entries with feeds filter conditions.
	 *
	 * @param     array $filters    An array of filter data.
	 * @param     array $data       Form data.
	 * @since     1.0.0
	 * @return    bool
	 */
	public function wciz_cf7_integration_validate_filter( $filters = array(), $data = array() ) {

		if ( ! empty( $filters ) && is_array( $filters ) ) {

			foreach ( $filters as $or_key => $or_filters ) {
				$result = true;

				if ( is_array( $or_filters ) ) {

					foreach ( $or_filters as $and_key => $and_filter ) {
						if ( '-1' == $and_filter['field'] || '-1' == $and_filter['option'] ) { // phpcs:ignore
							return array( 'result' => false );
						}

						$form_field = $and_filter['field'];
						$feed_value = ! empty( $and_filter['option'] ) ? $and_filter['option'] : '';
						$entry_val  = $this->wciz_cf7_integration_get_entry_values( $form_field, $data );
						$result     = $this->request_module->is_value_allowed_cf7( $and_filter['option'], $feed_value, $entry_val );

						if ( false == $result ) { // phpcs:ignore
							break;
						}
					}
				}

				if ( true === $result ) {
					break;
				}
			}
		}

		return $result;
	}

	/**
	 * Verify and get entered field values.
	 *
	 * @param     string $field      Form field whose value to verify.
	 * @param     array  $entries    An array of form entries.
	 * @since     1.0.0
	 * @return    mixed              value of the field
	 */
	public function wciz_cf7_integration_get_entry_values( $field, $entries ) {

		$value = false;

		$form_fields = $this->form_fields;
		$field_type  = isset( $form_fields[ $field ]['type'] ) ? $form_fields[ $field ]['type'] : '';

		if ( ! empty( $field ) || ! empty( $entries ) || is_array( $entries ) ) {

			if ( isset( $entries[ $field ] ) ) {
				$value = $entries[ $field ];

				if ( is_array( $value ) && ! empty( $value['value'] ) ) {
					$value = $value['value'];
				} elseif ( ! is_array( $value ) ) {
					$value = maybe_unserialize( $value );
				}
			}
		}

		if ( ! empty( $value ) && 'file' == $field_type ) { // phpcs:ignore
			$value = false;
		} elseif ( is_array( $value ) && 1 == count( $value ) ) { // phpcs:ignore
			$value = implode( ' ', $value );
		}

		return $value;
	}

	public function zoho_capture_billing_email() {
		if ( ! is_user_logged_in() ) {

			check_ajax_referer( 'ajax_nonce', 'nonce' );
			$zoho_cart_data = WC()->session->get( 'zoho_cart_data' );
			if ( ! empty( $zoho_cart_data ) ) {
				if ( isset( $_POST['billing_email'] ) ) {
					$zoho_cart_data['cart_email'] = ! empty( $_POST['billing_email'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_email'] ) ) : '';
				}

				if ( isset( $_POST['user_data'] ) ) {
					$zoho_cart_data['user_data'] = ! empty( $_POST['user_data'] ) ? sanitize_text_field( wp_unslash( $_POST['user_data'] ) ) : '';
				}
			}

			if ( isset( $_POST['billing_email'] ) ) {
				$email = ! empty( $_POST['billing_email'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_email'] ) ) : '';
			}

			update_option( 'zoho_cart_data_' . $email, $zoho_cart_data );
		}
	}

	/**
	 * Capture data for abandoned cart tracking
	 */

	public function zoho_crm_capture_cart_data() {

		if ( ! is_user_logged_in() ) {

			$zoho_cart_data['cart_items'] = WC()->cart->get_cart();
			foreach ( $zoho_cart_data['cart_items'] as $it_key => $it_value ) {
				unset( $zoho_cart_data['cart_items'][$it_key]['data'] );
			}
			$zoho_cart_data['last_updated'] = time();

			if ( function_exists( 'WC' ) && ! empty( WC()->session ) && ! current_user_can('manage_options') ) {

				WC()->session->set( 'zoho_cart_data', $zoho_cart_data );
			}
			if ( isset( $zoho_cart_data['cart_email'] ) ) {

				update_option( 'zoho_cart_data_' . $zoho_cart_data['cart_email'], $zoho_cart_data );
			}
		} else {
 
			$user_id = get_current_user_id();

			if ( ! empty( $user_id ) && $user_id ) {

				update_user_meta( $user_id, 'zoho_user_left_cart', 'yes' );
				update_user_meta( $user_id, 'zoho_last_addtocart', time() );
				update_user_meta( $user_id, 'zoho_abdn_sync_status', 'not_synced' );
			}
		}
	}

	/**
	 * Load cart contents from cart url
	 */

	public function zoho_load_cart_contents() {
		global $woocommerce;
		$zoho_utype = isset( $_GET['zoho_utype'] ) ? sanitize_text_field( wp_unslash( $_GET['zoho_utype'] ) ) : false;
		$zoho_uid = isset( $_GET['zoho_uid'] ) ? sanitize_text_field( wp_unslash( $_GET['zoho_uid'] ) ) : false;
		if ( $zoho_utype && $zoho_uid ) {

			if ( 'guest' == $zoho_utype ) {
				$abdn_cart_data = get_option( 'zoho_cart_data_' . $zoho_uid, array() );

				if ( ! empty( $abdn_cart_data ) ) {
					WC()->cart->empty_cart();
					if ( isset( $abdn_cart_data['cart_items'] ) && count( $abdn_cart_data['cart_items'] ) ) {
						foreach ( $abdn_cart_data['cart_items'] as $key => $item ) {
							$product_id = $item['product_id'];
							$quantity = $item['quantity'];
							$variation_id = $item['variation_id'];
							$variation = $item['variation'];
							WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
						}
						$woocommerce->session->set( 'c_email', $zoho_uid );

						wp_safe_redirect( wc_get_checkout_url() );
					}
				}
			} else if ( 'customer' == $zoho_utype ) {
				esc_url( wp_safe_redirect( home_url( '/wp-login.php' ) ) );
			}
		}
	}
}
