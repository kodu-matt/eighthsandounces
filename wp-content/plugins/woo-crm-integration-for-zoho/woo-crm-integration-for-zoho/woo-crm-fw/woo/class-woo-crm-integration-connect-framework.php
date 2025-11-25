<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */

 use Automattic\WooCommerce\Internal\DataStores\Orders\OrdersTableDataStore;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */
class Woo_Crm_Integration_Connect_Framework {

	/**
	 *  The instance of this class.
	 *
	 * @since    1.0.0
	 * @var      string    $instance    The instance of this class.
	 */
	private static $instance;

	/**
	 * Main Woo_Crm_Integration_Connect_Framework Instance.
	 *
	 * Ensures only one instance of Woo_Crm_Integration_Connect_Framework is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Woo_Crm_Integration_Connect_Framework - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Main Woo_Crm_Integration_Connect_Framework Instance.
	 *
	 * Ensures only one instance of Woo_Crm_Integration_Connect_Framework is loaded or can be loaded.
	 *
	 * @since 1.0.0.
	 */
	public function __construct() {
	}

	/**
	 * Main Woo_Crm_Integration_Connect_Framework Instance.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @return array - Main instance.
	 */
	public static function supports() {
		return array(
			'product',
			'users',
			'shop_order',
			'wc_user_membership',
			'abandon_carts',
			'abandoned_cart',
		);
	}

	/**
	 * Shop Order Fields Mapping which are not in meta keys.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @return array
	 */
	public static function shop_order_meta_extras() {
		$order_callbacks = array(
			'order_id'              => 'ID',
			'order_status'          => 'post_status',
			'order_items'           => 'get_items',
			'order_create_date'     => 'get_date_created',
			'order_paid_date'       => 'get_date_paid',
			'coupon_code_used'      => 'get_coupon_code_used',
			'order_link'            => 'get_edit_order_url',
			'order_subtotal'        => 'get_order_subtotal',
			'order_total'           => 'get_order_total',
			'billing_country_name'  => 'get_billing_country_name',
			'shipping_country_name' => 'get_shipping_country_name',
			'order_items_name'      => 'get_order_items_name',
			'order_number'          => 'get_order_number',
			'order_notes'           => 'get_order_customer_notes',
			'average_spent_by_user' => 'get_average_spent_by_user',
			'total_spent_by_user'   => 'get_total_spent_by_user',
			'user_role'             => 'get_user_role',
			'coupon_amount'         => 'get_coupon_amount',
			'coupon_code'           => 'get_coupon_code',
			'coupon_type'           => 'get_coupon_type',
			'order_tax'             => 'get_order_tax',
			'customer_ip_address'   => 'get_customer_ip_address',
			'customer_user_agent'   => 'get_customer_user_agent',
		);

		/**
		 * Add option to sync in order.
		 *
		 * @since 1.0.0
		 *
		 * @param Array $order_callbacks Order option data.
		 */
		return apply_filters( 'woo_zoho_order_field_values_filter', $order_callbacks );
	}

	/**
	 * User Fields Mapping which are not in meta keys.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @return array
	 */
	public static function user_meta_extras() {
		return array(
			'user_email' => 'user_email',
			'user_role'  => 'get_user_role',
		);
	}

	/**
	 * User Fields Mapping which are not in meta keys.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @return array
	 */
	public static function membership_meta_extras() {
		return array(
			'membership_plan' => 'membership_plan',
			'membership_plan_id' => 'membership_plan_id',
			'user_membership_id' => 'user_membership_id',
			'membership_user_id' => 'membership_user_id',
			'membership_status' => 'membership_status',
			'membership_since' => 'membership_since',
			'membership_expiry' => 'membership_expiry',
			'membership_purchased_in' => 'membership_purchased_in',
			'membership_order_date' => 'membership_order_date',
			'membership_order_total' => 'membership_order_total',
			'membership_subscription' => 'membership_subscription',
			'membership_next_bill_on' => 'membership_next_bill_on',
		);
	}

	/**
	 * Products Fields Mapping which are not in meta keys.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @return array
	 */
	public static function product_meta_extras() {
		$product_callbacks = array(
			'product_id'                => 'ID',
			'product_name'              => 'get_name',
			'product_short_description' => 'get_short_description',
			'product_description'       => 'get_description',
			'product_create_date'       => 'get_date_created',
			'product_type'              => 'get_type',
			'product_display_url'       => 'get_permalink',
			'product_status'            => 'get_product_status',
			'product_publish_status'    => 'get_publish_status',
			'price_without_tax'         => 'get_price_without_tax',
			'product_attributes'        => 'get_product_attributes',
			'product_categories'        => 'get_product_categories',
			'product_tags'              => 'get_product_tags',
		);

		return apply_filters( 'woo_zoho_product_callback', $product_callbacks );
	}

	public static function abandoned_cart_meta_extras() {
		$abnd_cart_callbacks = array(
			'abandoned_cart_email'                => 'abandoned_cart_email',
			'abandoned_cart_url'                  => 'abandoned_cart_url',
			'abandoned_cart_products'             => 'abandoned_cart_products',
			'abandoned_cart_products_html'        => 'abandoned_cart_products_html',
			'abandoned_cart_total'                => 'abandoned_cart_total',
			'abandoned_cart_user_type'            => 'abandoned_cart_user_type',
			'abandoned_cart_user_id'              => 'abandoned_cart_user_id',
		);
		return $abnd_cart_callbacks;
	}

	/**
	 * Returns the mapping index from Woo we require.
	 *
	 * @param string|bool $post_type        The object we need index for.
	 * @param string|bool $custom_post_type If required a custom post type.
	 *
	 * @since  1.0.0
	 * @return array|bool          The current mapping step required.
	 */
	public function get_wp_meta( $post_type = false, $custom_post_type = false ) {

		$status = 'publish';
		global $wpdb;

		switch ( $post_type ) {
			case 'product':
				$result = wciz_woo_zoho_get_column_data(
						"SELECT DISTINCT( $wpdb->postmeta.meta_key )
					FROM $wpdb->posts
					LEFT JOIN $wpdb->postmeta
					ON $wpdb->posts.ID = $wpdb->postmeta.post_id
					WHERE $wpdb->posts.post_type = 'product' AND $wpdb->postmeta.meta_key != ''"
				);
				if ( ! empty( self::product_meta_extras() ) && 'product' === $post_type ) {
					$result = array_merge( $result, array_keys( self::product_meta_extras() ) );
				} elseif ( ! empty( self::shop_order_meta_extras() ) && 'shop_order' === $post_type ) {
					$result = array_merge(
						$result,
						array_keys( self::shop_order_meta_extras() )
					);
				}

				break;

			case 'abandoned_cart':
				$result = self::abandoned_cart_meta_extras();
				break;
			case 'shop_order':
				if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() && 'product' != $post_type ) {
					$ord_meta = $wpdb->prefix . 'wc_orders_meta';
					$data_store = wc_get_container()->get( OrdersTableDataStore::class );
					$columns_mapping   = $data_store->get_internal_meta_keys();
					$result_order_data = array_unique($columns_mapping);
					$result_order_meta = wciz_woo_zoho_get_column_data( "SELECT Distinct $ord_meta.meta_key
					FROM $ord_meta" );
					$result = array_merge( $result_order_data, $result_order_meta );
				} else {
					$result = wciz_woo_zoho_get_column_data(
						"SELECT DISTINCT( $wpdb->postmeta.meta_key )
						FROM $wpdb->posts
						LEFT JOIN $wpdb->postmeta
						ON $wpdb->posts.ID = $wpdb->postmeta.post_id
						WHERE $wpdb->posts.post_type = 'shop_order'
						AND $wpdb->postmeta.meta_key != ''"
					);
				}
				if ( ! empty( self::product_meta_extras() ) && 'product' === $post_type ) {
					$result = array_merge( $result, array_keys( self::product_meta_extras() ) );
				} elseif ( ! empty( self::shop_order_meta_extras() ) && 'shop_order' === $post_type ) {
					$result = array_merge(
						$result,
						array_keys( self::shop_order_meta_extras() )
					);
				}

				break;

			case 'wc_user_membership':
				if ( ! empty( self::membership_meta_extras() ) && 'wc_user_membership' === $post_type ) {
					$result = self::membership_meta_extras();
				}
				break;

			case 'users':
				$query = "SELECT distinct $wpdb->usermeta.meta_key FROM 
                $wpdb->usermeta";

				$result = $this->format_query_object(
					wciz_woo_zoho_get_query_results( $query )
				);

				$query_user_table = "SELECT COLUMN_NAME
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE TABLE_NAME = '" . $wpdb->users . "'";

				$result_user_table = $this->format_query_object(
					wciz_woo_zoho_get_query_results( $query_user_table )
				);

				$result = array_merge( $result, $result_user_table );

				if ( ! empty( self::user_meta_extras() ) && 'users' === $post_type ) {
					$result = array_merge( $result, array_keys( self::user_meta_extras() ) );
				}
				break;

			case 'abandon_carts':
				$query  = 'SELECT `session_value`, `session_id` FROM `wp_woocommerce_sessions`';
				$result = $this->format_query_object(
					wciz_woo_zoho_get_query_results( $query )
				);
				break;

			case 'custom':
				$result = wciz_woo_zoho_get_column_data(
						"SELECT DISTINCT( $wpdb->postmeta.meta_key )
					FROM $wpdb->posts
					LEFT JOIN $wpdb->postmeta
					ON $wpdb->posts.ID = $wpdb->postmeta.post_id
					WHERE $wpdb->posts.post_type = $custom_post_type
					AND $wpdb->postmeta.meta_key != ''"
				);
				break;
		}

		return ! empty( $result ) ? $result : false;
	}

	/**
	 * Returns the mapping index from Woo we require.
	 *
	 * @param array $array The query object result.
	 *
	 * @since 1.0.0
	 *
	 * @return array The formatted data as raw array
	 */
	public function format_query_object( $array = false ) {
		if ( empty( $array ) ) {
			return;
		}

		$formatted_array = array();

		foreach ( $array as $key => $value ) {
			$dataset = array_values( $value );
			if ( ! empty( $dataset[0] ) ) {
				array_push( $formatted_array, $dataset[0] );
			}
		}

		return $formatted_array;
	}


	/**
	 * Returns the requested data values for required index from Woo we require.
	 *
	 * @param string $obj_type The post type.
	 * @param string $key      The post meta key.
	 * @param string $id       The post object id.
	 * @param string $nonce    Nonce.
	 * @since 1.0.0
	 *
	 * @return string               The post meta values.
	 */
	public function get_prop_value( $obj_type = false, $key = false, $id = false, $email = '', $nonce = false ) {

		if ( false === $key || false === $id ) {
			return;
		}

		$response = '';
		if ( empty( $obj_type ) || ! in_array( $obj_type, self::supports() ) ) { //phpcs:ignore
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $nonce ) ), 'wciz-create-pro' ) ) {
			return;
		}
		$admin_instance = new Woo_Crm_Integration_For_Zoho_Admin( 'CRM Integration For Zoho', WOO_CRM_INTEGRATION_ZOHO_VERSION );

		switch ( $obj_type ) {

			case 'users':
				if ( array_key_exists( $key, self::user_meta_extras() ) ) {
					
					$callback = self::user_meta_extras()[ $key ];
					if ( 'ID' === $callback ) {
						
						$response = $id;
					} elseif ( 'user_email' === $callback ) {
						$user = get_user_by( 'ID', $id );
						if ( $user ) {
							$response = $user->user_email;
						} else {
							$response = '';
						}
					} elseif ( 'get_user_role' === $callback ) {
						$user = get_user_by( 'id', $id );
						$user_roles = $user->roles;
						$response  = implode( ',', $user_roles );
					} else {
						$user = get_user_by( 'id', $id );
					}
				} else {

					if ( ! empty( $_POST[ $key ] ) ) {

						if ( is_array( $_POST[ $key ] ) ) {
							$posted_value = array_map( 'sanitize_text_field', wp_unslash( $_POST[ $key ] ) );
						} else {
							$posted_value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
						}
					}

					$response = ! empty( $posted_value ) ? $posted_value : get_user_meta( $id, $key, true );
					if ( empty( $response ) ) {
						$userdata = get_userdata($id);
						$user_d = (array) $userdata->data;
						if ( !empty( $user_d[$key] ) ) {
							$response = $user_d[$key];
						}
					}
				}
				break;

			case 'shop_order':
				if ( array_key_exists( $key, self::shop_order_meta_extras() ) ) {
					$callback = self::shop_order_meta_extras()[ $key ];
					if ( 'ID' === $callback ) {
						$response = $id;
					} elseif ( 'post_status' === $callback ) {
						$response = ucwords(wc_get_order( $id )->get_status());
					} elseif ( 'get_date_created' == $callback ) {

						$created_date = wc_get_order( $id )->get_date_created();
						if ( $created_date ) {
							$response = $created_date->date_i18n( 'Y-m-d\TH:i:s' );
						}
					} elseif ( 'get_date_paid' == $callback ) {

						$paid_date = wc_get_order( $id )->get_date_paid();
						if ( $paid_date ) {
							$response = $paid_date->date_i18n( 'Y-m-d\TH:i:s' );
						}
					} elseif ( 'get_order_items_name' == $callback ) {
						$order        = wc_get_order( $id );
						$order_items  = $order->get_items();
						$product_list = '';
						if ( ! empty( $order_items ) ) {

							foreach ( $order_items as $product ) {
								$product_details[] = $product['name'];
							}
							$product_list = implode( ',', $product_details );
						}
						$response = $product_list;
					} elseif ( 'get_order_number' == $callback ) {
						$order    = new WC_Order( $id );
						$response = $order->get_order_number();
					} elseif ( 'get_order_customer_notes' === $callback ) {
						$order = wc_get_order( $id );
						$response = $order->get_customer_note();
					} elseif ( 'get_average_spent_by_user' == $callback ) {
						if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
							$order = wc_get_order($id);
							$user_id = $order->get_customer_id();
						} else {

							$user_id = get_post_meta( $id, '_customer_user', true );
						}
						if ( ! empty( $user_id ) ) {
							$total_spent = wc_get_customer_total_spent( $user_id );
							$order_count = wc_get_customer_order_count( $user_id );
							if ( empty( $total_spent ) ) {
								$aov = '0';
							} else {
								$aov = ( ( $total_spent ) / ( $order_count ) );
							}
						} else {
							$order       = wc_get_order( $id );
							$order_count = 0;

							$total = 0;
							if ( ! empty( $order ) ) {
								$email     = $order->get_billing_email();
								$order_arg = array(
									'customer'    => $email,
									'limit'       => -1,
									'post_status' => array( 'wc-completed' ),
								);
								$orders    = wc_get_orders( $order_arg );

								if ( ! empty( $orders ) ) {
									$order_count = count( $orders );
									foreach ( $orders as  $order_data ) {
										$total += $order_data->get_total();
									}
								}
							}
							if ( empty( $total ) ) {
								$aov = '0';
							} else {
								$aov = ( ( $total ) / ( $order_count ) );
							}
						}
						$response = $aov;
					} elseif ( 'get_total_spent_by_user' == $callback ) {
						if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
							$order = wc_get_order($id);
							$user_id = $order->get_customer_id();
						} else {

							$user_id = get_post_meta( $id, '_customer_user', true );
						}
						if ( ! empty( $user_id ) ) {
							$total_spent = wc_get_customer_total_spent( $user_id );
						} else {
							$order       = wc_get_order( $id );
							$order_count = 0;

							$total = 0;
							if ( ! empty( $order ) ) {
								$email     = $order->get_billing_email();
								$order_arg = array(
									'customer'    => $email,
									'limit'       => -1,
									'post_status' => array( 'wc-completed' ),
								);
								$orders    = wc_get_orders( $order_arg );

								if ( ! empty( $orders ) ) {
									$order_count = count( $orders );
									foreach ( $orders as  $order_data ) {
										$total += $order_data->get_total();
									}
								}
							}
							$total_spent = $total;
						}
						$response = $total_spent;
					} elseif ( 'get_coupon_code_used' === $callback ) {

						$order        = wc_get_order( $id );
						$coupons      = $order->get_items( 'coupon' );
						$coupons_used = array();

						if ( ! empty( $coupons ) ) {
							foreach ( $coupons as $key => $coupon_obj ) {
								$coupons_used[] = $coupon_obj->get_name();
							}
						}

						if ( ! empty( $coupons_used ) ) {

							$response = implode( ',', $coupons_used );
						} else {
							$response = '';
						}
					} elseif ( 'get_order_subtotal' == $callback ) {
						$order = wc_get_order( $id );
						if ( ! empty( $order ) ) {
							$response = floatval( $order->get_subtotal() );
						} else {
							$response = '';
						}
					} elseif ( 'get_order_total' == $callback ) {
						$order = wc_get_order( $id );
						if ( ! empty( $order ) ) {
							$response = floatval( $order->get_total() );
						} else {
							$response = '';
						}
					} elseif ( 'get_billing_country_name' == $callback ) {
						$order = wc_get_order( $id );
						if ( ! empty( $order->billing_country ) ) {
							$response = WC()->countries->countries[ $order->billing_country ];
						} else {
							$response = '';
						}
					} elseif ( 'get_shipping_country_name' == $callback ) {
						$order = wc_get_order( $id );
						if ( ! empty( $order->shipping_country ) ) {
							$response = WC()->countries->countries[ $order->shipping_country ];
						} else {
							$response = '';
						}
					} elseif ( 'get_if_subscription_or_one_shot' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							if ( ! empty( $subscriptions ) && is_array( $subscriptions ) ) {
								$subscription_obj = $subscriptions [ array_key_first( $subscriptions ) ];
								if ( $subscription_obj->is_one_payment() ) {
									$one_shot = esc_html__( 'One Time Payment', 'woo-crm-integration-for-zoho' );
								} else {
									$one_shot = esc_html__( 'Subscription', 'woo-crm-integration-for-zoho' );
								}
							} else {
								$one_shot = esc_html__( 'One Time Payment', 'woo-crm-integration-for-zoho' );
							}
							$response = $one_shot;
						}

					} elseif ( 'get_subscription_start_date' == $callback ) {
						$start_date = '';
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							if ( ! empty( $subscriptions ) && is_array( $subscriptions ) ) {
								$subscription_obj = $subscriptions [ array_key_first( $subscriptions ) ];
								if ( 0 != $subscription_obj->get_date( 'start' ) ) {
									$start_date   = date_i18n( wc_date_format(), strtotime( $subscription_obj->get_date( 'start' ) ) );
								} else {
									$start_date = '';
								}
							} else {
								$start_date = '';
							}
							$response = $start_date;
						}
					} elseif ( 'get_subscription_renewal_date' == $callback ) {
						$next_payment_date = '';
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							if ( ! empty( $subscriptions ) && is_array( $subscriptions ) ) {
								$subscription_obj  = $subscriptions [ array_key_first( $subscriptions ) ];
								if ( 0 != $subscription_obj->get_date( 'next_payment' ) ) {
									$next_payment_date = date_i18n( wc_date_format(), strtotime( $subscription_obj->get_date( 'next_payment' ) ) );
								} else {
									$next_payment_date = '';
								}
							} else {
								$next_payment_date = '';
							}
							$response = $next_payment_date;
						}
						
					} elseif ( 'get_subscription_next_payment_date' == $callback ) {
						$next_payment_date = '';
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							if ( ! empty( $subscriptions ) && is_array( $subscriptions ) ) {
								$subscription_obj  = $subscriptions [ array_key_first( $subscriptions ) ];
								if ( 0 != $subscription_obj->get_date( 'next_payment' ) ) {
									$next_payment_date = date_i18n( wc_date_format(), strtotime( $subscription_obj->get_date( 'next_payment' ) ) );
								} else {
									$next_payment_date = '';
								}
							} else {
								$next_payment_date = '';
							}

							$response = $next_payment_date;
						}
						
					} elseif ( 'get_subscription_status' == $callback ) {
						$status = '';
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							if ( ! empty( $subscriptions ) && is_array( $subscriptions ) ) {
								$subscription_obj = $subscriptions [ array_key_first( $subscriptions ) ];
								$status           = $subscription_obj->get_status();
							} else {
								$status = '';
							}

							$response = $status;
						}
						
					} elseif ( 'get_subscription_price' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							if ( ! empty( $subscriptions ) && is_array( $subscriptions ) ) {
								$subscription_obj = $subscriptions [ array_key_first( $subscriptions ) ];
									$price        = $subscription_obj->get_total();
							}

							$response = $price;
						}
						
					} elseif ( 'get_subscription_products_details' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							if ( ! empty( $subscriptions ) && is_array( $subscriptions ) ) {
								$subscription_obj = $subscriptions [ array_key_first( $subscriptions ) ];

									$subscrition_items = ( $subscription_obj->get_items() );
								if ( ! empty( $subscrition_items ) ) {

									foreach ( $subscrition_items as $product ) {
										$product_details[] = $product['name'];
									}
									$product_list = implode( ' | ', $product_details );
								}
							}

							$response = $product_list;
						}
						
					} elseif ( 'get_subscription_id' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions_ids = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
								$response = '' . $subscription_id;
							}
						}
						
					} elseif ( 'get_subscription_customer' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions_ids = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
								$user = get_user_by( 'id', $subscription_obj->get_customer_id() );
								$response = $user->user_login;
							}
						}
						
					} elseif ( 'get_subscription_payment_method' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions_ids = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
								$response = $subscription_obj->get_payment_method_title();
							}
						}
						
					} elseif ( 'get_subscription_payment_schedule' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions_ids = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
								$response = $subscription_obj->get_billing_interval() . ' ' . $subscription_obj->get_billing_period();
							}
						}
						
					} elseif ( 'get_subscription_start_date' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							if ( ! empty( $subscriptions ) && is_array( $subscriptions ) ) {
								$subscription_obj = $subscriptions [ array_key_first( $subscriptions ) ];
								if ( 0 != $subscription_obj->get_date( 'start' ) ) {
									$start_date   = date_i18n( wc_date_format(), strtotime( $subscription_obj->get_date( 'start' ) ) );
								} else {
									$start_date = '';
								}
							} else {
								$start_date = '';
							}
							$response = $start_date;
						}
						
					} elseif ( 'get_subscription_end_date' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							if ( ! empty( $subscriptions ) && is_array( $subscriptions ) ) {
								$subscription_obj = $subscriptions [ array_key_first( $subscriptions ) ];
								if ( 0 != $subscription_obj->get_date( 'schedule_end' ) ) {
									$end_date   = date_i18n( wc_date_format(), strtotime( $subscription_obj->get_date( 'schedule_end' ) ) );
								} else {
									$end_date = '';
								}
							} else {
								$end_date = '';
							}
							$response = $end_date;
						}
						
					} elseif ( 'get_subscription_next_payment' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions_ids = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
								if ( 0 != $subscription_obj->get_date( 'schedule_next_payment' ) ) {
									$next_payment_date = ! empty( $subscription_obj->get_date( 'schedule_next_payment' ) ) ? $subscription_obj->get_date( 'schedule_next_payment' ) : '';
								} else {
									$next_payment_date = '';
								}

								if ( ! empty( $next_payment_date ) ) {

									$response = gmdate( 'Y-m-d H:i:s', strtotime( $next_payment_date ) );
								}
							}
						}
						
					} elseif ( 'get_subscription_information' == $callback ) {
						$subs_info = 'Acepto las condiciones de uso del servicio : ' . get_post_meta($id, '_billing_acepto_las_condiciones_de_uso_del_servicio_', true);
						$subs_info .= ', Acepto suscribirme al boletín diario : ' . get_post_meta($id, '_billing_acepto_suscribirme_al_boletin_diario', true);
						$subs_info .= ', Acepto suscribirme al boletín premium (solo para miembros) : ' . get_post_meta($id, '_billing_acepto_suscribirme_al_boletin_premium_solo_para_miembros', true);
						if ( !empty( get_post_meta($id, '_billing_acepto_las_condiciones_de_uso_del_servicio_', true) ) || !empty( get_post_meta($id, '_billing_acepto_suscribirme_al_boletin_diario', true) ) || !empty( get_post_meta($id, '_billing_acepto_suscribirme_al_boletin_premium_solo_para_miembros', true) ) ) {
							$response = $subs_info;
						}
					} elseif ( 'get_subscription_variation_id' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions_ids = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
								$items = $subscription_obj->get_items();
								foreach ( $items as $i_key => $i_value ) {
									$i_data = $i_value->get_data();
									$var_id = $i_data['variation_id'];
									if ( 0 != $var_id && !empty( $var_id ) ) {
										$response = $var_id;
									}
								}
							}
						}
						
					} elseif ( 'get_subscription_user_id' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions_ids = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
								$subs_data = $subscription_obj->get_data();
								if ( !empty( $subs_data ) ) {
									$response = $subs_data['customer_id'];
								} else {
									$response = '';
								}
							}
						}
			
					} elseif ( 'get_subscription_email_id' == $callback ) {
						if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
							$subscriptions_ids = wcs_get_subscriptions_for_order( $id, array( 'order_type' => 'any' ) );
							foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
								$subs_data = $subscription_obj->get_data();
								if ( !empty( $subs_data ) ) {
									$response = $subs_data['billing']['email'];
								} else {
									$response = '';
								}
							}
						}
						
					} elseif ( 'get_membership_plan_id' === $callback ) {
						$prod_meta = ( get_post_meta( $id ) );
						if ( array_key_exists( '_wc_memberships_access_granted', $prod_meta ) ) {
							$membership_attached_array = ( get_post_meta( $id, '_wc_memberships_access_granted' ) );
							$user_membership_post_id   = ( array_key_first( $membership_attached_array[0] ) );
							if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
								$user_membership           = wc_memberships_get_user_membership( $user_membership_post_id );
								$membership_plan_id        = $user_membership->id;
								$response                  = 'woo_plan_' . $membership_plan_id;
							}
						} else {
							$response = '';
						}
					} elseif ( 'get_membership_plan' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						$ord = wc_get_order($id);
						$user_id = $ord->get_customer_id();
						if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
							$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
							foreach ($membership_attached_array as $membership) {
								if ( $membership->get_order_id() == $id ) {
									$response = $membership->plan->name . ' #' . $membership->plan->id;
								}
							}
						}
					} elseif ( 'get_membership_create_time' === $callback ) {
						$prod_meta = ( get_post_meta( $id ) );
						if ( array_key_exists( '_wc_memberships_access_granted', $prod_meta ) ) {
							$membership_attached_array = ( get_post_meta( $id, '_wc_memberships_access_granted' ) );
							$user_membership_post_id   = ( array_key_first( $membership_attached_array[0] ) );
							$membership_created_time   = date_i18n( wc_time_format(), strtotime( get_post_meta( $user_membership_post_id, '_start_date', true ) ) );
							$response                  = $membership_created_time;
						} else {
							$response = '';
						}
					} elseif ( 'get_membership_create_date' === $callback ) {
						$prod_meta = ( get_post_meta( $id ) );
						if ( array_key_exists( '_wc_memberships_access_granted', $prod_meta ) ) {
							$membership_attached_array = ( get_post_meta( $id, '_wc_memberships_access_granted' ) );
							$user_membership_post_id   = ( array_key_first( $membership_attached_array[0] ) );
							$membership_created_date   = date_i18n( wc_date_format(), strtotime( get_post_meta( $user_membership_post_id, '_start_date', true ) ) );
							$response                  = $membership_created_date;
						} else {
							$response = '';
						}
					} elseif ( 'get_membership_end_date' === $callback ) {
						$prod_meta = ( get_post_meta( $id ) );
						if ( array_key_exists( '_wc_memberships_access_granted', $prod_meta ) ) {
							$membership_attached_array = ( get_post_meta( $id, '_wc_memberships_access_granted' ) );
							$user_membership_post_id   = ( array_key_first( $membership_attached_array[0] ) );
							$membership_end_date       = date_i18n( wc_date_format(), strtotime( get_post_meta( $user_membership_post_id, '_end_date', true ) ) );
							$response                  = $membership_end_date;
						} else {
							$response = '';
						}
					} elseif ( 'get_membership_end_time' === $callback ) {
						$prod_meta = ( get_post_meta( $id ) );
						if ( array_key_exists( '_wc_memberships_access_granted', $prod_meta ) ) {
							$membership_attached_array = ( get_post_meta( $id, '_wc_memberships_access_granted' ) );
							$user_membership_post_id   = ( array_key_first( $membership_attached_array[0] ) );
							$membership_end_time       = date_i18n( wc_time_format(), strtotime( get_post_meta( $user_membership_post_id, '_end_date', true ) ) );
							$response                  = $membership_end_time;
						} else {
							$response = '';
						}
					} elseif ( 'get_user_membership_id' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						$ord = wc_get_order($id);
						$user_id = $ord->get_customer_id();
						if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
							$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
							foreach ($membership_attached_array as $membership) {
								if ( $membership->get_order_id() == $id ) {
									$response = $membership->id;
								}
							}
						}
					} elseif ( 'get_membership_user_id' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						$ord = wc_get_order($id);
						$user_id = $ord->get_customer_id();
						if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
							$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
							foreach ($membership_attached_array as $membership) {
								if ( $membership->get_order_id() == $id ) {
									$response = $membership->user_id;
								}
							}
						}
					} elseif ( 'get_membership_status' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						$ord = wc_get_order($id);
						$user_id = $ord->get_customer_id();
						if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
							$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
							foreach ($membership_attached_array as $membership) {
								if ( $membership->get_order_id() == $id ) {
									$response = $membership->status;
								}
							}
						}
					} elseif ( 'get_membership_product_id' === $callback ) {
						$prod_meta = ( get_post_meta( $id ) );
						if ( array_key_exists( '_wc_memberships_access_granted', $prod_meta ) ) {
							$membership_attached_array = ( get_post_meta( $id, '_wc_memberships_access_granted' ) );
							$user_membership_post_id   = ( array_key_first( $membership_attached_array[0] ) );
							$membership_product_id     = get_post_meta( $user_membership_post_id, '_product_id', true );
							$response                  = $membership_product_id;
						} else {
							$response = '';
						}
					} elseif ( 'get_membership_product_name' === $callback ) {
						$prod_meta = ( get_post_meta( $id ) );
						if ( array_key_exists( '_wc_memberships_access_granted', $prod_meta ) ) {
							$membership_attached_array = ( get_post_meta( $id, '_wc_memberships_access_granted' ) );
							$user_membership_post_id   = ( array_key_first( $membership_attached_array[0] ) );
							$membership_product_id     = get_post_meta( $user_membership_post_id, '_product_id', true );
							$product                   = wc_get_product( $membership_product_id );
							$response                  = $product->get_name();
						} else {
							$response = '';
						}
					} elseif ( 'get_membership_since' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						$ord = wc_get_order($id);
						$user_id = $ord->get_customer_id();
						if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
							$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
							foreach ($membership_attached_array as $membership) {
								if ( $membership->get_order_id() == $id ) {
									$response = $membership->get_start_date();
								}
							}
						}
					} elseif ( 'get_membership_expiry' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						$ord = wc_get_order($id);
						$user_id = $ord->get_customer_id();
						if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
							$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
							foreach ($membership_attached_array as $membership) {
								if ( $membership->get_order_id() == $id ) {
									$response = $membership->get_end_date();
									if ( empty( $response ) ) {
										$response = 'Never';
									}
								}
							}
						}
					} elseif ( 'get_membership_purchased_in' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						$ord = wc_get_order($id);
						$user_id = $ord->get_customer_id();
						if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
							$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
							foreach ($membership_attached_array as $membership) {
								if ( $membership->get_order_id() == $id ) {
									$response = 'Order ' . $membership->get_order_id();
								}
							}
						}
					} elseif ( 'get_membership_order_date' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						$ord = wc_get_order($id);
						$user_id = $ord->get_customer_id();
						if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
							$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
							foreach ($membership_attached_array as $membership) {
								if ( $membership->get_order_id() == $id ) {
									$response = gmdate( 'Y/m/d', get_post_time( 'U', true, $membership->get_order_id() ) );
								}
							}
						}
					} elseif ( 'get_membership_order_total' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						$ord = wc_get_order($id);
						$user_id = $ord->get_customer_id();
						if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
							$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
							foreach ($membership_attached_array as $membership) {
								if ( $membership->get_order_id() == $id ) {
									$mem_ord = wc_get_order( $membership->get_order_id() );
									$response = $mem_ord->get_total();
								}
							}
						}
					} elseif ( 'get_membership_subscription' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
							$ord = wc_get_order($id);
							$user_id = $ord->get_customer_id();
							if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
								$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
								foreach ($membership_attached_array as $membership) {
									if ( $membership->get_order_id() == $id ) {
										if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
											$subscriptions_ids = wcs_get_subscriptions_for_order( $membership->get_order_id(), array( 'order_type' => 'any' ) );
											if ( !empty( $subscriptions_ids ) ) {
												foreach ( $subscriptions_ids as $subs_id => $subs_v ) {
													$response = $subs_id;
												}
											}
										}
									}
								}
							}
						}
					} elseif ( 'get_membership_next_bill_on' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
							$ord = wc_get_order($id);
							$user_id = $ord->get_customer_id();
							if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
								$membership_attached_array = wc_memberships_get_user_active_memberships($user_id);
								foreach ($membership_attached_array as $membership) {
									if ( $membership->get_order_id() == $id ) {
										if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
											$subscriptions_ids = wcs_get_subscriptions_for_order( $membership->get_order_id(), array( 'order_type' => 'any' ) );
											if ( !empty( $subscriptions_ids ) ) {
												foreach ( $subscriptions_ids as $subs_id => $subs_v ) {
													$next_payment_date = ! empty( $subs_v->get_date( 'schedule_next_payment' ) ) ? $subs_v->get_date( 'schedule_next_payment' ) : '';
				
													if ( ! empty( $next_payment_date ) ) {
				
														$response = gmdate( 'Y-m-d', strtotime( $next_payment_date ) );
													}
												}
											}
										}
									}
								}
							}
						}
					} elseif ( 'get_user_role' == $callback ) {
						$order    = wc_get_order( $id );
						$user_id  = $order->get_user_id();
						$response = '';
						if ( $user_id ) {
							// Get the user object
							$user = get_user_by( 'id', $user_id );

							if ( $user ) {
								// Get the user roles
								$user_roles = $user->roles;

								if ( ! empty( $user_roles ) ) {
									// The user_roles array may contain multiple roles, but we'll assume the first one is the primary role.
									$user_role = $user_roles[0];
									$response  = $user_role;
								}
							}
						}
					} elseif ( 'get_coupon_amount' == $callback ) {
						$order    = wc_get_order( $id );
						$response = $order->discount_total;
					} elseif ( 'get_coupon_code' == $callback ) {
						$order    = wc_get_order( $id );
						$coupon_codes = $order->get_coupon_codes();
						$coupon_codes = implode( ',', $coupon_codes );
						$response = $coupon_codes;
					} elseif ( 'get_coupon_type' == $callback ) {
						$order    = wc_get_order( $id );
						$coupons_of_order = $order->get_coupons();
						$discount_type_array = array();
						if ( !empty( $coupons_of_order ) ) {
							foreach ( $coupons_of_order as $coup_id => $coupon_data ) {
								$coupon_meta_data = $coupon_data->get_meta_data();
								foreach ( $coupon_meta_data as $c_key => $c_data ) {
									$coupon_details = $c_data->get_data();
									$coup = explode(',', substr($coupon_details['value'], 1, -1));
									$c = new WC_Coupon($coup[1]);
									$c_data = $c->get_data();
									$discount_type_array[] = $c_data['discount_type'];
								}
								
							}
						}
						if ( !empty( $discount_type_array ) && is_array( $discount_type_array ) ) {
							$discount_type = implode( ',', $discount_type_array );
							$response = $discount_type;
						} else {
							$response = '';
						}
					} elseif ( 'get_order_tax' == $callback ) {
						$order    = wc_get_order( $id );
						$tax = 0;
						if ( !empty( $order->get_items('tax') ) ) {
							foreach ($order->get_items('tax') as $item_id => $item ) {
								if ( !empty( $item->get_data() ) ) {
									$tax += $item->get_data()['tax_total'];
								}
							}
						}
						if ( 0 != $tax ) {
							$response = round($tax, 2);
						} else {
							$response = 0;
						}
					} else {
						$is_add_on_active = false;

						/**
						 * Check that add on is active.
						 *
						 * @since 1.0.0
						 *
						 * @param mixed $is_add_on_active Get that add on is active.
						 */
						$is_add_on_active = apply_filters( 'is_zoho_plugin_add_on_active', $is_add_on_active );
						
						if ( $is_add_on_active ) {

							/**
							 * Callaback condtions for add_on fields.
							 *
							 * @since 1.0.0
							 *
							 * @param Array $order_callbacks Order option data.
							 */
							$response = apply_filters( 'wciz_zoho_fetch_order_mapping_values', $callback, $id );
						} elseif ( !empty( wc_get_order( $id )->$callback() ) ) {
							
								$response = wc_get_order( $id )->$callback();
						} else {
							$response = '';
							
						}
					}
				} else {
					if ( ! empty( $_POST[ $key ] ) ) {
						$posted_value = '';
						if ( is_array( $_POST[ $key ] ) ) {
							$posted_value = array_map( 'sanitize_text_field', wp_unslash( $_POST[ $key ] ) );
						} else {
							$posted_value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
						}
					}
					if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {
						$order = wc_get_order( $id );
						if ( !empty( $order->get_meta($key) ) ) {
							$m_data = $order->get_meta($key);
						} else {
							$m_data = '';
						}
						
						$response = ! empty( $posted_value ) ? $posted_value : $m_data;
					} else {
						if ( !empty( get_post_meta( $id, $key, true ) ) ) {
							$m_data = get_post_meta( $id, $key, true );
						} else {
							$m_data = '';
						}
						$response = ! empty( $posted_value ) ? $posted_value : $m_data;

					}
				}
				break;

			case 'wc_user_membership':
				if ( array_key_exists( $key, self::membership_meta_extras() ) ) {
					$callback = self::membership_meta_extras()[ $key ];
					if ( 'membership_plan' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								$response = $membership->plan->name . ' #' . $membership->plan->id;
							}
						}
					} elseif ( 'membership_plan_id' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								$response = 'woo_plan_' . $membership->plan->id;
							}
						}
					} elseif ( 'user_membership_id' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						$response = $id;
					} elseif ( 'membership_user_id' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								$response = $membership->user_id;
							}
						}
					} elseif ( 'membership_status' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								$response = $membership->status;
							}
						}
					} elseif ( 'membership_since' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								$response = $membership->get_start_date();
							}
						}
					} elseif ( 'membership_expiry' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								$response = $membership->get_end_date();
								if ( empty( $response ) ) {
									$response = 'Never';
								}
							}
						}
					} elseif ( 'membership_purchased_in' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								if ( !empty( $membership->get_order_id() ) ) {
									$response = 'Order ' . $membership->get_order_id();
								}
							}
						}
					} elseif ( 'membership_order_date' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								if ( !empty( $membership->get_order_id() ) ) {
									$response = gmdate( 'Y/m/d', get_post_time( 'U', true, $membership->get_order_id() ) );
								}
							}
						}
					} elseif ( 'membership_order_total' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								if ( !empty( $membership->get_order_id() ) ) {
									$mem_ord = wc_get_order( $membership->get_order_id() );
									if ( $mem_ord instanceof WC_Order ) {
										$response = $mem_ord->get_total();
									}
								}
							}
						}
					} elseif ( 'membership_subscription' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								if ( !empty( $membership->get_order_id() ) ) {
									if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
										$subscriptions_ids = wcs_get_subscriptions_for_order( $membership->get_order_id(), array( 'order_type' => 'any' ) );
										if ( !empty( $subscriptions_ids ) ) {
											foreach ( $subscriptions_ids as $subs_id => $subs_v ) {
												$response = $subs_id;
											}
										}
									}
								}
							}
						}
					} elseif ( 'membership_next_bill_on' === $callback && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) && $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
						if ( function_exists( 'wc_memberships_get_user_membership' ) ) {
							$membership = wc_memberships_get_user_membership( $id );
							if ( ! is_wp_error( $membership ) && $membership ) {
								if ( !empty( $membership->get_order_id() ) ) {
									if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
										$subscriptions_ids = wcs_get_subscriptions_for_order( $membership->get_order_id(), array( 'order_type' => 'any' ) );
										if ( !empty( $subscriptions_ids ) ) {
											foreach ( $subscriptions_ids as $subs_id => $subs_v ) {
												$next_payment_date = ! empty( $subs_v->get_date( 'schedule_next_payment' ) ) ? $subs_v->get_date( 'schedule_next_payment' ) : '';

												if ( ! empty( $next_payment_date ) ) {

													$response = gmdate( 'Y-m-d', strtotime( $next_payment_date ) );
												}
											}
										}
									}
								}
							}
						}
					}
				}
				break;

			case 'product':
				if ( array_key_exists( $key, self::product_meta_extras() ) ) {

					$callback = self::product_meta_extras()[ $key ];

					if ( 'ID' === $callback ) {
						$response = $id;

					} elseif ( 'get_product_status' == $callback ) {
						$product = get_post( $id );
						if ( ! empty( $product ) ) {
							$response = $this->wciz_zoho_get_product_status( $product, $id );
						} else {
							$response = '';
						}
					} elseif ( 'get_date_created' == $callback ) {
						if ( wc_get_product( $id ) ) {
							$product_created_date = wc_get_product( $id )->get_date_created();
							if ( $product_created_date ) {
								$response = $product_created_date->date_i18n( 'Y-m-d\TH:i:s' );
							}
						}
					} elseif ( 'get_product_categories' === $callback ) {

						$product = wc_get_product( $id );
						if ( $product ) {
							$cat_ids = array();
							if ( $product->is_type( 'variation' ) || $product->is_type( 'subscription_variation' ) ) {

								$parent_product_id = $product->get_parent_id();
								$cat_ids           = wc_get_product( $parent_product_id )->get_category_ids();

							} else {
								$cat_ids = $product->get_category_ids();
							}

							foreach ( $cat_ids as $term ) {
								$product_cat_name[] = get_term($term)->name;
							}
							$response = ( implode( ',', $product_cat_name ) );
						}
					} elseif ( 'get_publish_status' == $callback ) {
						$product = get_post( $id );
						if ( ! empty( $product ) ) {
							$product_status = $this->wciz_zoho_get_product_status( $product, $id );
							$response       = ( 'publish' == $product_status );
						}
					} elseif ( 'get_price_without_tax' == $callback ) {
						$product = wc_get_product( $id );
						if ( $product ) {
							$price_excl_tax = wc_get_price_excluding_tax( $product );
							$response       = $price_excl_tax;
						}
					} elseif ( 'get_product_attributes' == $callback ) {
						$prod_attributes = array();
						$product         = wc_get_product( $id );
						if ( $product->is_type( 'variation' ) ) {
							$product_attributes = $product->get_attributes();

							if ( ! empty( $product_attributes ) ) {
								foreach ( $product_attributes as $key => $value ) {
									if ( is_string( $key ) && is_string( $value ) ) {
										$key = str_replace( 'pa_', '', $key );
										array_push( $prod_attributes, ucwords( $key ) . '-' . $value );
									}
								}
							}
						} elseif ( $product->is_type( 'variable' ) ) {
							$product_attributes = $product->get_attributes();

							if ( ! empty( $product_attributes ) ) {
								foreach ( $product_attributes as $key => $value ) {
									if ( is_string( $key ) && is_string( $value ) ) {
										$key = str_replace( 'pa_', '', $key );
										array_push( $prod_attributes, ucwords( $key ) . '-' . $value );
									}
								}
							}
						} else {
							$key                = '_product_attributes';
							$product_attributes = get_post_meta( $id, $key, true );
							if ( ! empty( $product_attributes ) ) {
								foreach ( $product_attributes as $key => $value ) {
									if ( is_string( $key ) && is_string( $value ) ) {
										$attribute_value                     = $product->get_attribute( $key );
										$product_attributes[ $key ]['value'] = $attribute_value;
										$product_attributes[ $key ]['name']  = str_replace( 'pa_', '', $product_attributes[ $key ]['name'] );
										array_push( $prod_attributes, ucwords( $product_attributes[ $key ]['name'] ) . '-' . $product_attributes[ $key ]['value'] );
									}
								}
							}
						}
						$response = implode( ', ', $prod_attributes );

					} elseif ( 'get_product_category' == $callback ) {

						$terms            = get_the_terms( $id, 'product_cat' );
						$product_cat_name = array();
						foreach ( $terms as $term ) {
							$product_cat_name[] = $term->name;
						}
						$response = ( implode( ',', $product_cat_name ) );

					} elseif ( 'get_product_tags' == $callback ) {
						$parent_id = wp_get_post_parent_id( $id, 'product_type' );
						if ( ! empty( $parent_id ) ) {
							$terms = wp_get_object_terms( $parent_id, 'product_tag', array( 'fields' => 'names' ) );
						} else {
							$terms = wp_get_object_terms( $id, 'product_tag', array( 'fields' => 'names' ) );
						}
						$response = implode( ',', $terms );
					} else {
						$product = wc_get_product( $id );
						if ( ! empty( $product ) ) {
							$response = $product->$callback();
						}
					}
				} else {

					if ( ! empty( $_POST[ $key ] ) ) {

						if ( is_array( $_POST[ $key ] ) ) {
							$posted_value = array_map( 'sanitize_text_field', wp_unslash( $_POST[ $key ] ) );
						} else {
							$posted_value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
						}
					}

					$response = ! empty( $posted_value ) ? $posted_value : get_post_meta( $id, $key, true );
				}
				break;

			case 'abandoned_cart':
				if ( array_key_exists( $key, self::abandoned_cart_meta_extras() ) ) {
					$callback = self::abandoned_cart_meta_extras()[ $key ];

					if ( 'abandoned_cart_email' === $callback ) {
						$response = $email;
					} else if ( 'abandoned_cart_url' === $callback ) {
						$user = get_user_by( 'email', esc_html( $email ) );

						if ( !empty( $user ) ) {
							$data = get_user_meta( $user->ID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true );

							if ( !empty( $data['cart'] ) ) {
								$cart_details = $data['cart'];
								$cart_url = add_query_arg(
									array(
										'zoho_utype' => 'customer',
										'zoho_uid' => $email,
									),
									wc_get_cart_url()
								);
						
								$response = $cart_url;
							}
						} else {
							$abdn_cart_data = get_option( 'zoho_cart_data_' . $email, '' );
							if ( !empty( $abdn_cart_data ) ) {
								$cart_url = add_query_arg(
									array(
										'zoho_utype' => 'guest',
										'zoho_uid' => $email,
									),
									wc_get_cart_url()
								);
						
								$response = $cart_url;
							}
						}

					} else if ( 'abandoned_cart_products' === $callback ) {
						$user = get_user_by( 'email', esc_html( $email ) );

						if ( !empty( $user ) ) {
							$cart_total = 0;
							$data = get_user_meta( $user->ID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true );

							if ( !empty( $data['cart'] ) ) {
								$cart_details = $data['cart'];
								$cart_product_array = array();
								foreach ( $cart_details as $key => $value ) {
									if ( get_post_status( $value['product_id'] ) == 'trash' || get_post_status( $value['product_id'] ) == false ) {
										continue;
									}
									$variation_id = $value['variation_id'];
									$product_id   = $value['product_id'];
									if ( 0 !== $variation_id ) {
										$product_id = $variation_id;
									}
									$product = wc_get_product( $product_id );
									if ( ! $product ) {
										continue;
									}
									$product_data['name'] = $product->get_name();
									$cart_product_array[] = $product_data['name'];
								}
								$abandoned_cart_products = implode( '|', $cart_product_array );
	
								$response = $abandoned_cart_products;
							}
						} else {
							$abdn_cart_data = get_option( 'zoho_cart_data_' . $email, '' );
							if ( !empty( $abdn_cart_data ) ) {
								$cart_product_array = array();
								foreach ( $abdn_cart_data['cart_items'] as $key => $value ) {
									if ( get_post_status( $value['product_id'] ) == 'trash' || get_post_status( $value['product_id'] ) == false ) {
										continue;
									}
									$variation_id = $value['variation_id'];
									$product_id   = $value['product_id'];
									if ( 0 !== $variation_id ) {
										$product_id = $variation_id;
									}
									$product = wc_get_product( $product_id );
									if ( ! $product ) {
										continue;
									}
									$product_data['name'] = $product->get_name();
									$cart_product_array[] = $product_data['name'];
								}
								$abandoned_cart_products = implode( '|', $cart_product_array );
	
								$response = $abandoned_cart_products;
							}
						}
					} else if ( 'abandoned_cart_products_html' === $callback ) {
						$user = get_user_by( 'email', esc_html( $email ) );

						if ( !empty( $user ) ) {
							$cart_total = 0;
							$data = get_user_meta( $user->ID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true );

							if ( !empty( $data['cart'] ) ) {
								$cart_details = $data['cart'];
								foreach ( $cart_details as $key => $value ) {
									if ( get_post_status( $value['product_id'] ) == 'trash' || get_post_status( $value['product_id'] ) == false ) {
										continue;
									}
									$variation_id = $value['variation_id'];
									$product_id   = $value['product_id'];
									if ( 0 !== $variation_id ) {
										$product_id = $variation_id;
									}
									$product = wc_get_product( $product_id );
									if ( ! $product ) {
										continue;
									}
									$product_data['id'] = $product_id;
									$product_data['price'] = $product->get_price();
									$product_data['qty'] = $value['quantity'];
									$product_data['name'] = $product->get_name();
									$product_data['url'] = get_permalink( $product_id );
									$product_data['total'] = $value['quantity'] * $product->get_price();
									$product_data['image'] = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' )[0];
									$cart_items[] = $product_data;
									$cart_product_array[] = $product_data['name'] . '-' . $product_id;
								}

								if ( !empty( $cart_items ) ) {
									if ( count( $cart_items ) ) {
										$products_html = wciz_zoho_get_cart_table_html( $cart_items );
										$zoho_base64 = get_option( 'wciz_zoho_save_html_encoded', 'no' );
										if ( 'yes' === $zoho_base64 ) {
											$products_html = base64_encode( $products_html );
										}
									}
								}
								
								$response = $products_html;
							}
							
						} else {
							$abdn_cart_data = get_option( 'zoho_cart_data_' . $email, '' );
							if ( !empty( $abdn_cart_data ) ) {
								foreach ( $abdn_cart_data['cart_items'] as $key => $value ) {
									if ( get_post_status( $value['product_id'] ) == 'trash' || get_post_status( $value['product_id'] ) == false ) {
										continue;
									}
									$variation_id = $value['variation_id'];
									$product_id   = $value['product_id'];
									if ( 0 !== $variation_id ) {
										$product_id = $variation_id;
									}
									$product = wc_get_product( $product_id );
									if ( ! $product ) {
										continue;
									}
									$product_data['id'] = $product_id;
									$product_data['price'] = $product->get_price();
									$product_data['qty'] = $value['quantity'];
									$product_data['name'] = $product->get_name();
									$product_data['url'] = get_permalink( $product_id );
									$product_data['total'] = $value['quantity'] * $product->get_price();
									$product_data['image'] = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' )[0];
									$cart_items[] = $product_data;
									$cart_product_array[] = $product_data['name'] . '-' . $product_id;
								}
								if ( count( $cart_items ) ) {
									$products_html = wciz_zoho_get_cart_table_html( $cart_items );
									$zoho_base64 = get_option( 'wciz_zoho_save_html_encoded', 'no' );
									if ( 'yes' === $zoho_base64 ) {
										$products_html = base64_encode( $products_html );
									}
								}
								
								$response = $products_html;
							}
						}
					} else if ( 'abandoned_cart_total' === $callback ) {
						$user = get_user_by( 'email', esc_html( $email ) );

						if ( !empty( $user ) ) {
							$cart_total = 0;
							$data = get_user_meta( $user->ID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true );

							if ( !empty( $data['cart'] ) ) {
								$cart_details = $data['cart'];
								foreach ( $cart_details as $key => $value ) {
									if ( get_post_status( $value['product_id'] ) == 'trash' || get_post_status( $value['product_id'] ) == false ) {
										continue;
									}
									$variation_id = $value['variation_id'];
									$product_id   = $value['product_id'];
									if ( 0 !== $variation_id ) {
										$product_id = $variation_id;
									}
									$product = wc_get_product( $product_id );
									if ( ! $product ) {
										continue;
									}
	
									$product_data['total'] = $value['quantity'] * $product->get_price();
									$cart_total += $product_data['total'];
								}
								$response = $cart_total;
							}
						} else {
							$abdn_cart_data = get_option( 'zoho_cart_data_' . $email, '' );
							if ( !empty( $abdn_cart_data ) ) {
								$cart_total = 0;
								foreach ( $abdn_cart_data['cart_items'] as $key => $value ) {
									if ( get_post_status( $value['product_id'] ) == 'trash' || get_post_status( $value['product_id'] ) == false ) {
										continue;
									}
									$variation_id = $value['variation_id'];
									$product_id   = $value['product_id'];
									if ( 0 !== $variation_id ) {
										$product_id = $variation_id;
									}
									$product = wc_get_product( $product_id );
									if ( ! $product ) {
										continue;
									}
	
									$product_data['total'] = $value['quantity'] * $product->get_price();
									$cart_total += $product_data['total'];
								}
								$response = $cart_total;
							}
						}
					} else if ( 'abandoned_cart_user_type' === $callback ) {
						$user = get_user_by( 'email', esc_html( $email ) );

						if ( !empty( $user ) ) {
							$response = 'Customer';
						} else {
							$response = 'Guest';
						}
					} else if ( 'abandoned_cart_user_id' === $callback ) {
						$user = get_user_by( 'email', esc_html( $email ) );

						if ( !empty( $user ) ) {
							$response = $user->ID;
						} else {
							$response = '';
						}
					}
				}
				break;

			default:
				// code...
				break;
		}

		return $response;
	}


	/**
	 * Current Woo meta keys with Labels.
	 *
	 * @param array $dataset array for woo keys.
	 *
	 * @since 1.0.0
	 *
	 * @return array - Current Woo meta keys with Labels to Woo keys.
	 */
	public function parse_labels( $dataset ) {
		if ( ! empty( $dataset ) && is_array( $dataset ) ) {
			foreach ( $dataset as $key => $value ) {
				if ( ! empty( $value ) && is_array( $value ) ) {
					foreach ( $value as $k => $v ) {

						// Initialise the key via wp index.
						$dataset[ $key ][ $v ] = false;

						// Unset the numeric index.
						unset( $dataset[ $key ][ $k ] );

						// Create Wp index.
						$k = $v;
						if ( !empty( $v ) ) {
							// Create Wp Labels.
							$v                     = str_replace( '_', ' ', $v );
							$v                     = str_replace( '-', ' ', $v );
							$v                     = ucwords( $v );
							$dataset[ $key ][ $k ] = $v;
						} else {
							// Create Wp Labels.
							$dataset[ $key ][ $k ] = $v;
						}
					}
				}
			}
		}
		return $dataset;
	}

	/**
	 * Replace the occurence within the string only once.
	 *
	 * @param string $from    The sub-string before replace.
	 * @param string $to      The sub-string after replace.
	 * @param string $content The string from which we operate.
	 *
	 * @since  1.0.0
	 * @return array - Current Woo meta keys with Labels to Woo keys.
	 */
	public function str_replace_first( $from, $to, $content ) {
		$from = '/' . preg_quote( $from, '/' ) . '/';

		return preg_replace( $from, $to, $content, 1 );
	}

	/**
	 * Get other associated zoho object in required format.
	 *
	 * @param string $woo_obj_type   The WP post type.
	 * @param string $lookup_feed_id The Feed id to get lookup request.
	 * @param string $lookup_type    The CRM Object required id.
	 * @param string $woo_id         The WP post id.
	 *
	 * @since  1.0.0
	 * @return array - Current Woo meta keys with Labels to Woo keys.
	 */
	public function resolve_lookup(
		$woo_obj_type,
		$lookup_feed_id,
		$lookup_type,
		$woo_id
	) {

		if ( 'publish' === get_post_status( $lookup_feed_id ) ) {
			switch ( $woo_obj_type ) {
				default:
					$status_obj = 'shop_order';
					break;
			}
			if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {
				$order = wc_get_order( $woo_id );
				
				$zoho_association_id = $order->get_meta( 'wciz_zoho_feed_' . $lookup_feed_id . '_association' );
			} else {
				$zoho_association_id = get_post_meta(
					$woo_id,
					'wciz_zoho_feed_' . $lookup_feed_id . '_association',
					true
				);
			}
			
			if ( empty( $zoho_association_id ) ) {

				$request = $this->get_request(
					$status_obj,
					$lookup_feed_id,
					$woo_id
				);

				$record_type = $this->get_feed( $lookup_feed_id, 'crm_object' );

				if ( 'Contacts' == $record_type ) {

					$zoho_association_id = $this->may_be_get_association_id_from_user( $woo_id, $lookup_feed_id );

					if ( ! empty( $zoho_association_id ) ) {
						return $zoho_association_id;
					}
				}

				if ( 'Accounts' == $record_type ) {

					$zoho_association_id = $this->may_be_get_association_id_from_user( $woo_id, $lookup_feed_id );
					return $zoho_association_id;

				}

				$log_data = array(
					'woo_id'     => $woo_id,
					'feed_id'    => $lookup_feed_id,
					'woo_object' => $status_obj,
				);

				$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
				$result                   = $crm_integration_zoho_api->create_single_record(
					$record_type,
					$request,
					false,
					$log_data
				);

				if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {
					$order = wc_get_order( $woo_id );
					
					$zoho_association_id = $order->get_meta( 'wciz_zoho_feed_' . $lookup_feed_id . '_association' );
				} else {
					$zoho_association_id = get_post_meta(
						$woo_id,
						'wciz_zoho_feed_' . $lookup_feed_id . '_association',
						true
					);
				}
			}
		}
		return ! empty( $zoho_association_id ) ? $zoho_association_id : '';
	}

	/**
	 * Returns the feed data we require.
	 *
	 * @param int|bool $feed_id  The object id for post type feed.
	 * @param string   $meta_key The object meta key for post type feed.
	 *
	 * @return array|bool The current data for required object.
	 *
	 * @since 1.0.0
	 */
	public function get_feed( $feed_id = false, $meta_key = 'mapping_data' ) {
		if ( false === $feed_id ) {
			return;
		}

		$mapping = get_post_meta( $feed_id, $meta_key, true );

		if ( empty( $mapping ) ) {
			$mapping = false;
		}

		return $mapping;
	}

	/**
	 * Returns the mapping step we require.
	 *
	 * @param string $obj_type The object post type.
	 * @param string $feed_id  The mapped feed for associated objects.
	 * @param string $obj_id   The object post id.
	 *
	 * @since  1.0.0
	 * @return array The current mapping step required.
	 */
	public function get_request( $obj_type = false, $feed_id = false, $obj_id = false, $email = '' ) {
		if ( false === $obj_type || false === $feed_id || false === $obj_id ) {
			return;
		}

		$feed = $this->get_feed( $feed_id );

		if ( empty( $feed ) ) {
			return false;
		}

		// Process Feeds.
		$response = array();

		foreach ( $feed as $k => $mapping ) {

			$field_type = ! empty( $mapping['field_type'] ) ?
					$mapping['field_type'] : 'standard_field';

			switch ( $field_type ) {

				case 'standard_field':
					$is_status_mapping_required = ! empty( $mapping['use_status_mapping'] )
					&& 'yes' === $mapping['use_status_mapping']
					? true :
					false;

					$field_format = ! empty( $mapping['field_value'] ) ?
					$mapping['field_value'] : '';

					// If lookup field.
					if ( 0 === strpos( $field_format, 'feeds_' ) ) {

						$lookup_feed_id = str_replace( 'feeds_', '', $field_format );

						$field_value = $this->resolve_lookup(
							$obj_type,
							$lookup_feed_id,
							$k,
							$obj_id
						);

					} elseif ( true == $is_status_mapping_required ) { //phpcs:ignore

						$status_mapping = $this->get_feed( $feed_id, 'status_mapping' );
						$order_status   = $this->get_prop_value(
							$obj_type,
							'order_status',
							$obj_id,
							$email,
							wp_create_nonce( 'wciz-create-pro' )
						);

						$order_status = lcfirst( $order_status );
						$order_status = str_replace( ' ', '-', $order_status );
						$order_status = 'wc-' . $order_status;
						$key = $order_status;

						$field_value = ! empty( $status_mapping[ $key ] )
						? $status_mapping[ $key ]
						: '';

					} else { // Just a standard meta key.

						$obj_required = strtok( $field_format, '_' );

						$meta_key = $this->str_replace_first(
							$obj_type . '_',
							'',
							$field_format
						);

						$field_value = $this->get_prop_value(
							$obj_type,
							$meta_key,
							$obj_id,
							$email,
							wp_create_nonce( 'wciz-create-pro' )
						);
					}

					break;

				case 'custom_value':
					$initial_value = ! empty( $mapping['custom_value'] ) ?
					$mapping['custom_value'] : '';
					$field_key = ! empty( $mapping['custom_value'] ) ?
					strtolower( $mapping['custom_value'] ) : '';

					preg_match_all( '/{(.*?)}/', $field_key, $dynamic_strings );

					if ( ! empty( $dynamic_strings[1] ) ) {
						
						$dynamic_values = $dynamic_strings[1];

						foreach ( $dynamic_values as $key => $value ) {
							$prefix       = $obj_type . '_';
							$field_format = $this->str_replace_first(
								$prefix,
								'',
								$value
							);

							
							$field_value = $this->get_prop_value(
								$obj_type,
								$field_format,
								$obj_id,
								$email,
								wp_create_nonce( 'wciz-create-pro' )
							);

							$substr = '{' . $prefix . $field_format . '}';

							$field_key = str_replace(
								$substr,
								$field_value,
								$field_key
							);

							$field_value = $field_key;
							
						}
					} else {
						$field_value = $initial_value;
					}

					break;
			}

			$response[ $k ] = $field_value;
		}

		$add_line_item = $this->get_feed( $feed_id, 'add_line_item' );
		if ( 'yes' === $add_line_item ) {

			$order_items = $this->get_prop_value(
				$obj_type,
				'order_items',
				$obj_id,
				$email,
				wp_create_nonce( 'wciz-create-pro' )
			);

			$woo_order = wc_get_order( $obj_id );

			$line_items = array();

			if ( ! empty( $order_items ) && is_array( $order_items ) ) {

				foreach ( $order_items as $items_key => $items_value ) {

					$product_feed_ids = $this->get_object_feed( 'Products' );
					$product_feed_id  = is_array( $product_feed_ids ) ? reset( $product_feed_ids ) : $product_feed_ids;

					$items_id = ! empty( $items_value->get_variation_id() ) ?
					$items_value->get_variation_id() :
					$items_value->get_product_id();

					$crm_association_id = get_post_meta(
						$items_id,
						'wciz_zoho_feed_' . $product_feed_id . '_association',
						true
					);

					// If not present sync the product instantly.
					$record_type_of_feed = $this->get_feed(
						$feed_id,
						'crm_object'
					);

					if ( !empty( wc_get_product($items_id)->get_data() ) ) {
						if ( ( empty( $crm_association_id ) && ! empty( $items_id ) ) || ( 'yes' == get_option( 'wciz_zoho_enable_reduce_stock', 'no' ) && 'Sales_Orders' == $record_type_of_feed && true == wc_get_product($items_id)->get_data()['manage_stock'] ) ) {
							$request = $this->get_request(
								'product',
								$product_feed_id,
								$items_id
							);
	
							$record_type = $this->get_feed(
								$product_feed_id,
								'crm_object'
							);
							/**
							 * Filters the value of zoho tabs array.
							 *
							 * @since 1.0.0
							 *
							 * @param mixed $request request.
							 * @param mixed $items_id item_id.
							 * @param mixed $record_type record_type.
							 */
							$request = apply_filters(
								'woo_crm_woo_zoho_product_request',
								$request,
								$items_id,
								$record_type
							);
	
							$log_data = array(
								'woo_id'     => $items_id,
								'feed_id'    => $product_feed_id,
								'woo_object' => 'product',
							);
	
							$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
							$result                   = $crm_integration_zoho_api->create_single_record(
								$record_type,
								$request,
								false,
								$log_data
							);
	
							$ajax_module = new Woo_Crm_Integration_For_Zoho_Ajax();
	
							if ( 'Products' == $record_type ) {
								$add_product_image = $this->get_feed( $product_feed_id, 'add_product_image' );
								if ( 'yes' === $add_product_image ) {
									// May be sync product images.
									$ajax_module->may_be_sync_product_images( $product_feed_id, array( $items_id ) );
								}
							}
	
							$crm_association_id = get_post_meta(
								$items_id,
								'wciz_zoho_feed_' . $product_feed_id . '_association',
								true
							);
						}
					}

					$item = array(
						'product'    => array(
							'id' => $crm_association_id,
						),
						'quantity'   => $items_value->get_quantity(),
						'list_price' => floatval( $items_value->get_subtotal() ) / $items_value->get_quantity(),
					);

					if ( 'yes' == get_option( 'wciz_zoho_enable_discount_in_line_items', 'no' ) ) {
						$item['Discount'] = floatval( $items_value->get_subtotal() - $items_value->get_total() );
					} else {
						$item['Discount'] = 0;
					}

					if ( 'yes' == get_option( 'wciz_zoho_enable_discount_in_both', 'no' ) ) {
						$item['Discount'] = floatval( $items_value->get_subtotal() - $items_value->get_total() );
					}

					array_push( $line_items, $item );

					if ( empty( $items_id ) ) {

						$log_dir = WC_LOG_DIR . 'wciz_woo-zoho-missing-product.log';

						if ( ! is_dir( $log_dir ) ) {
							@fopen( WC_LOG_DIR . 'wciz_woo-zoho-missing-product.log', 'a' ); //phpcs:ignore
						}

						global $wp_filesystem;  // Define global object of WordPress filesystem.
						WP_Filesystem();

						$file_data = '';
						if ( file_exists( $log_dir ) ) {
							$file_data = $wp_filesystem->get_contents( $log_dir );
						} else {
							$file_data = '';
						}

						$log  = 'Order ID: ' . $obj_id . PHP_EOL;
						$log .= '------------------------------------' . PHP_EOL;

						$file_data .= $log;
						$wp_filesystem->put_contents( $log_dir, $file_data );

					}
				}
			}

			$response['Product_Details'] = $line_items;
		}

		// Add tax related data to product feed.
		if ( 'product' == $obj_type ) {
			$taxable_product = $this->get_feed( $feed_id, 'taxable_product' );
			if ( 'yes' == $taxable_product ) {
				$response['Taxable'] = true;
				$tax_rate_mapping    = $this->get_feed( $feed_id, 'tax_rate_mapping' );
				$woo_product         = wc_get_product( $obj_id );
				$zoho_tax_rate       = '';
				if ( $woo_product ) {
					$tax_class     = $woo_product->get_tax_class();
					$zoho_tax_rate = $tax_rate_mapping[ $tax_class ];
				}
				if ( ! empty( $zoho_tax_rate ) ) {
					$response['Tax'] = array( $zoho_tax_rate );
				}
			} else {
				$response['Taxable'] = false;
				$response['Tax']     = array();
			}
		}
		// Add tax related data to product feed.

		$add_shipping_line_item   = $this->get_feed( $feed_id, 'add_shipping_line_item' );
		$shipping_line_item_total = $this->get_feed( $feed_id, 'shipping_line_item_total' );
		$shipping_product_id      = get_option( 'wciz_woo_shipping_product_id', false );

		if ( 'yes' == $add_shipping_line_item && $shipping_product_id ) {
			if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {

				$order = wc_get_order($obj_id );
				$order_meta = $order->get_data();
				$shipping_total = $order_meta['shipping_total'];
			} else {
				$shipping_total = get_post_meta( $obj_id, '_order_shipping', true );
			}

			if ( 'order_shipping_plus_tax' == $shipping_line_item_total ) {
				if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {

					$order = wc_get_order($obj_id );
					$order_meta = $order->get_data();
					$shipping_tax = $order_meta['shipping_tax'];
				} else {
					$shipping_tax = get_post_meta( $obj_id, '_order_shipping_tax', true );
				}
				$shipping_total = $shipping_total + $shipping_tax;
			}

			if ( ! empty( $shipping_total ) ) {

				$shipping_line_item            = array(
					'product'    => array(
						'id' => $shipping_product_id,
					),
					'quantity'   => 1,
					'list_price' => floatval( $shipping_total ),
				);
				$response['Product_Details'][] = $shipping_line_item;
			}
		}

		$record_type = $this->get_feed(
			$feed_id,
			'crm_object'
		);

		$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
		$object_fields            = $crm_integration_zoho_api->get_module_fields( $record_type, false );
		if ( ! empty( $response ) && is_array( $response ) ) {
			$response = $this->get_structured_object_request( $response, $object_fields );
		}

		$response['post_id'] = $obj_id;
		return $response;
	}

	/**
	 * Get structured object request.
	 *
	 * @param array $response_data  Feed maaping data.
	 * @param array $obj_fields     Fields of an object.
	 * @since 1.0.0
	 * @return array
	 */
	public function get_structured_object_request( $response_data = array(), $obj_fields = array() ) {

		if ( empty( $obj_fields ) || ! is_array( $obj_fields ) ) {
			return $response_data;
		}

		if ( empty( $obj_fields['fields'] ) || ! is_array( $obj_fields['fields'] ) ) {
			return $response_data;
		}
		$object_fields = $obj_fields['fields'];
		$obj_request   = array();
		$api_name      = array_column( $object_fields, 'api_name' );
		// Now restructure data.
		foreach ( $response_data as $key => $value ) {
			$field_value = '';
			$res_key     = array_search( $key, $api_name, true );
			if ( $res_key ) {
				$type        = ! empty( $object_fields[ $res_key ]['data_type'] ) ? $object_fields[ $res_key ]['data_type'] : '';
				$field_value = $this->maybe_verify_values( $value, $type );
			}

			if ( ! empty( $field_value ) ) {
				$obj_request[ $key ] = $field_value;
			} elseif ( false === $field_value ) {
				$obj_request[ $key ] = $field_value;
			} else {
				$obj_request[ $key ] = $value;
			}
		}

		return $obj_request;
	}

	/**
	 * Verify value
	 *
	 * @param mixed  $value  Entry value.
	 * @param string $type   Type of value.
	 * @since 1.0.0
	 * @return mixed
	 */
	public function maybe_verify_values( $value, $type ) {
		switch ( $type ) {

			case 'datetime':
				$value = ! empty( $value ) ? date_i18n( 'Y-m-d\TH:i:s', strtotime( str_replace( '/', '-', $value ) ) ) : '';
				break;

			case 'date':
				$value = ! empty( $value ) ? date_i18n( 'Y-m-d', strtotime( str_replace( '/', '-', $value ) ) ) : '';
				break;

			case 'boolean':
				$value = ! empty( $value );
				break;

			case 'text':
				if ( is_numeric( $value ) ) {
					$value = "$value";
				} else {
					$value = $value;
				}
				break;

			default:
				$value = $value;
		}
		return $value;
	}

	/**
	 * Returns the mapping step we require.
	 *
	 * @param string $feed_type The CRM Object post type.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|array single or multiple feed ids.
	 */
	public function get_object_feed( $feed_type = false ) {
		if ( empty( $feed_type ) ) {
			return false;
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

		if ( ! empty( $feeds ) ) {
			if ( count( $feeds ) > 1 ) {
				$feed_id = $feeds;
			} else {
				$feed_id = reset( $feeds );
			}

			return $feed_id;
		}
		return false;
	}

	/**
	 * Get title of a particur feed.
	 *
	 * @param int $feed_id Id of feed.
	 *
	 * @return array.
	 */
	public function get_feed_title( $feed_id ) {
		$title = esc_html( 'Feed #' ) . $feed_id;
		$feed  = get_post( $feed_id );
		$title = ! empty( $feed->post_title ) ? $feed->post_title : $title;
		return $title;
	}

	/**
	 * Returns the hooks/callbacks/priority for each active feed.
	 *
	 * @since  1.0.0
	 * @return string The current object event Hook.
	 */
	public function get_hook_requests_for_feed() {
		$feed_ids = $this->get_all_feed();

		if ( empty( $feed_ids ) ) {
			return;
		}

		$requested_hooks = array();

		foreach ( $feed_ids as $key => $feed_id ) {
			if ( ! empty( $feed_id ) ) {
				$event                       = $this->get_feed( $feed_id, 'feed_event' );
				$record_type                 = $this->get_feed(
					$feed_id,
					'crm_object'
				);
				$requested_hooks[ $feed_id ] = array(
					'hook'     => $this->get_hook( $event, $record_type ),
					'callback' => $this->get_callback( $event, $record_type ),
				);
			}
		}

		return $requested_hooks;
	}

	/**
	 * Returns the callbacks for each active feed.
	 *
	 * @param string      $event  The event for feed.
	 * @param string|bool $object The CRM object for feed.
	 *
	 * @since  1.0.0
	 * @return string The current object event Hook.
	 */
	public function get_callback( $event = '', $object = false ) {
		if ( empty( $event ) || empty( $object ) ) {
			return;
		}
		$callback = '';
		switch ( $event ) {
			case 'new-order':
				$callback = 'shop_order_updated';
				break;

			case 'order-create-update':
				$callback = 'shop_order_updated';
				break;

			case 'wc-pending':
				$callback = 'shop_order_updated';
				break;

			case 'wc-processing':
				$callback = 'shop_order_updated';
				break;

			case 'wc-on-hold':
				$callback = 'shop_order_updated';
				break;

			case 'wc-completed':
				$callback = 'shop_order_updated';
				break;

			case 'wc-cancelled':
				$callback = 'shop_order_updated';
				break;

			case 'wc-refunded':
				$callback = 'shop_order_refunded';
				break;

			case 'wc-failed':
				$callback = 'shop_order_updated';
				break;

			case 'status_change':
				$callback = 'shop_order_status_changed';
				break;

			case 'product_update_create':
				$callback = 'create_and_update_product';
				break;

			case 'user_update_create':
				$callback = 'create_and_update_contact';
				break;

			case 'send_manually':
				switch ( $object ) {

					case 'Sales_Orders':
					case 'Deals':
					case 'Contacts':
					case 'Leads':
					case 'Accounts':
					case 'Quotes':
						$callback = 'create_and_update_shop_order';
						break;

					case 'Products':
						$callback = 'create_and_update_product';
						break;

				}
				break;

			case 'create_subscription':
				$callback = 'subscription_created';
				break;

			case 'subscription_status_changed':
				$callback = 'subscription_status_change';
				break;

			case 'subscription_updated':
				$callback = 'subscription_update';
				break;

			case 'membership_saved':
				$callback = 'sync_membership_data';
				break;
				
			default:
				break;
		}
		return $callback;
	}

	/**
	 * Returns the hooks for each active feed.
	 *
	 * @param string      $event  The event for feed.
	 * @param string|bool $object The CRM object for feed.
	 *
	 * @since  1.0.0
	 * @return string The current object event Hook.
	 */
	public function get_hook( $event = '', $object = false ) {

		$hook = '';
		switch ( $event ) {

			case 'new-order':
				$hook = 'woocommerce_new_order';
				break;

			case 'order-create-update':
				$hook =  array(
					'woocommerce_new_order',
					'woocommerce_process_shop_order_meta',
				);
				break;

			case 'wc-pending':
				$hook = 'woocommerce_order_status_pending';
				break;

			case 'wc-processing':
				$hook = 'woocommerce_order_status_processing';
				break;

			case 'wc-on-hold':
				$hook = 'woocommerce_order_status_on-hold';
				break;

			case 'wc-completed':
				$hook = 'woocommerce_order_status_completed';
				break;

			case 'wc-cancelled':
				$hook = 'woocommerce_order_status_cancelled';
				break;

			case 'wc-refunded':
				$hook = array(
					'woocommerce_order_fully_refunded',
					'woocommerce_order_partially_refunded',
				);
				break;

			case 'wc-failed':
				$hook = 'woocommerce_order_status_failed';
				break;

			case 'status_change':
				$hook = array(
					'woocommerce_order_status_pending',
					'woocommerce_order_status_failed',
					'woocommerce_order_fully_refunded',
					'woocommerce_order_partially_refunded',
					'woocommerce_order_status_cancelled',
					'woocommerce_order_status_completed',
					'woocommerce_order_status_on-hold',
					'woocommerce_order_status_processing',
				);
				break;

			case 'product_update_create':
				$hook = array(
					'woocommerce_new_product',
					'woocommerce_update_product',
				);
				break;

			case 'user_update_create':
				$hook = array(
					'user_register',
					'wciz_edit_user_profile_update',
					'woocommerce_checkout_update_user_meta',
				);
				break;

			case 'send_manually':
				switch ( $object ) {
					case 'Sales_Orders':
					case 'Deals':
					case 'Contacts':
					case 'Leads':
					case 'Accounts':
					case 'Quotes':
						$hook = 'save_post_shop_order';
						break;

					case 'Products':
						$hook = array(
							'woocommerce_new_product',
							'woocommerce_update_product',
						);
						break;

				}
				break;

			case 'create_subscription':
				$hook = 'woocommerce_subscription_payment_complete';
				break;

			case 'subscription_status_changed':
				$hook = 'woocommerce_subscription_status_changed';
				break;

			case 'subscription_updated':
				$hook = 'woocommerce_process_shop_subscription_meta';
				break;

			case 'membership_saved':
				$hook = 'wc_memberships_user_membership_saved';
				break;
				
			default:
				break;
		}

		return $hook;
	}

	/**
	 * Returns the hooks for each active feed.
	 *
	 * @param string $hook The hooks for feed.
	 *
	 * @since  1.0.0
	 * @return string The current hooks param count.
	 */
	public function get_hook_param_count( $hook = '' ) {
		if ( is_array( $hook ) ) {
			if ( in_array( 'woocommerce_order_fully_refunded', $hook ) ) { //phpcs:ignore
				$hook = 'woocommerce_order_fully_refunded';
			}
		}
		$count = '';
		switch ( $hook ) {

			case 'woocommerce_new_order':
				$count = '2';
				break;

			case 'woocommerce_process_shop_order_meta':
				$count = '2';
				break;

			case 'woocommerce_order_status_pending':
				$count = '1';
				break;

			case 'woocommerce_order_status_processing':
				$count = '1';
				break;

			case 'woocommerce_order_status_on-hold':
				$count = '1';
				break;

			case 'woocommerce_order_status_completed':
				$count = '1';
				break;

			case 'woocommerce_order_status_cancelled':
				$count = '1';
				break;

			case 'woocommerce_order_status_failed':
				$count = '1';
				break;

			case 'woocommerce_order_status_pre-ordered':
				$count = '1';
				break;

			case 'woocommerce_order_fully_refunded':
			case 'woocommerce_order_partially_refunded':
				$count = '2';
				break;

			case 'save_post_shop_order':
				$count = '3';
				break;

			case 'save_post_product':
				$count = '3';
				break;

			case 'woocommerce_new_product':
			case 'woocommerce_update_product':
				$count = '2';
				break;

			case 'user_register':
			case 'woocommerce_checkout_update_user_meta':
			case 'wciz_edit_user_profile_update':
				$count = '2';
				break;

			case 'woocommerce_subscription_payment_complete':
				$count = '1';
				break;

			case 'woocommerce_subscription_status_changed':
				$count = '3';
				break;

			case 'woocommerce_process_shop_subscription_meta':
				$count = '2';
				break;

			case 'wc_memberships_user_membership_saved':
				$count = '2';
				break;

			default:
				break;
		}

		return $count;
	}

	/**
	 * Returns the mapping step we require.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|array single or multiple feed ids.
	 */
	public function get_all_feed() {
		$args = array(
			'post_type'      => 'wciz_crm_feed',
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'posts_per_page' => -1,
		);

		$feeds = get_posts( $args );

		if ( ! empty( $feeds ) ) {
			return $feeds;
		}
		return false;
	}

	/**
	 * Returns all the feeds.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|array single or multiple feed ids.
	 */
	public function get_all_feeds() {
		$args = array(
			'post_type'      => 'wciz_crm_feed',
			'post_status'    => array( 'publish', 'draft' ),
			'fields'         => 'ids',
			'posts_per_page' => -1,
		);

		$feeds = get_posts( $args );

		if ( ! empty( $feeds ) ) {
			return $feeds;
		}
		return false;
	}

	/**
	 * Returns zoho association id for registerd user.
	 *
	 * @param int $order_id Order id.
	 * @param int $lookup_feed_id The Feed id to get lookup request.
	 * @since 1.0.0
	 *
	 * @return string.
	 */
	public function may_be_get_association_id_from_user( $order_id, $lookup_feed_id ) {
		$order = wc_get_order($order_id );
		if ( empty( $order->get_data()['refunded_by'] ) ) {
			return;
		}
		if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {

			$order = wc_get_order($order_id );
			if ( empty( $order->get_data()['refunded_by'] ) ) {
				$user_id = $order->get_customer_id();
			} else {
				return;
			}
		} else {
			$user_id = get_post_meta( $order_id, '_customer_user', true );
		}
		$zoho_id = '';
		if ( $user_id ) {

			global $wpdb;
			$usermeta   = $wpdb->prefix . 'usermeta';
			$user_query = "SELECT `meta_value` FROM {$usermeta} WHERE `meta_key` LIKE '%wciz_zoho_feed_{$lookup_feed_id}_association%' AND `user_id` = {$user_id }";
			$result     = wciz_woo_zoho_get_query_results( $user_query );
			$zoho_id    = ! empty( $result[0]['meta_value'] ) ? $result[0]['meta_value'] : '';
		}

		return $zoho_id;
	}

	/**
	 * Feeds conditional filter options.
	 *
	 * @since     1.0.0
	 * @return    array
	 */
	public function get_available_feed_filters() {

		$filter = array(
			'exact_match'       => esc_html__( 'Matches exactly', 'woo-crm-integration-for-zoho' ),
			'no_exact_match'    => esc_html__( 'Does not match exactly', 'woo-crm-integration-for-zoho' ),
			'contains'          => esc_html__( 'Contains (Text)', 'woo-crm-integration-for-zoho' ),
			'not_contains'      => esc_html__( 'Does not contain (Text)', 'woo-crm-integration-for-zoho' ),
			'exist'             => esc_html__( 'Exist in (Text)', 'woo-crm-integration-for-zoho' ),
			'not_exist'         => esc_html__( 'Does not Exists in (Text)', 'woo-crm-integration-for-zoho' ),
			'starts'            => esc_html__( 'Starts with (Text)', 'woo-crm-integration-for-zoho' ),
			'not_starts'        => esc_html__( 'Does not start with (Text)', 'woo-crm-integration-for-zoho' ),
			'ends'              => esc_html__( 'Ends with (Text)', 'woo-crm-integration-for-zoho' ),
			'not_ends'          => esc_html__( 'Does not end with (Text)', 'woo-crm-integration-for-zoho' ),
			'less_than'         => esc_html__( 'Less than (Text)', 'woo-crm-integration-for-zoho' ),
			'greater_than'      => esc_html__( 'Greater than (Text)', 'woo-crm-integration-for-zoho' ),
			'less_than_date'    => esc_html__( 'Less than (Date/Time)', 'woo-crm-integration-for-zoho' ),
			'greater_than_date' => esc_html__( 'Greater than (Date/Time)', 'woo-crm-integration-for-zoho' ),
			'equal_date'        => esc_html__( 'Equals (Date/Time)', 'woo-crm-integration-for-zoho' ),
			'empty'             => esc_html__( 'Is empty', 'woo-crm-integration-for-zoho' ),
			'not_empty'         => esc_html__( 'Is not empty', 'woo-crm-integration-for-zoho' ),
		);

		return $filter;
	}

	/**
	 * Feeds Condtional html.
	 *
	 * @param     string $and_condition  The and condition of current html.
	 * @param     string $and_index      The and offset of current html.
	 * @param     string $or_index       The or offset of current html.
	 * @since     1.0.0
	 * @return    mixed
	 */
	public function render_and_conditon( $and_condition = array(), $and_index = '1', $or_index = '' ) {

		if ( empty( $and_index ) || empty( $and_condition ) || empty( $or_index ) ) {
			return;
		}

		?>
		<div class="and-condition-filter" data-and-index=<?php echo esc_attr( $and_index ); ?> >
			<select name="condition[<?php echo esc_html( $or_index ); ?>][<?php echo esc_html( $and_index ); ?>][field]"  class="condition-form-field">
				<option value="-1" ><?php esc_html_e( 'Select Field', 'woo-crm-integration-for-zoho' ); ?></option>
				<?php foreach ( $and_condition['object_fields'] as $key => $value ) : ?>
					<optgroup label="<?php echo esc_html( $key ); ?>" >
						<?php foreach ( $value as $index => $field ) : ?>
							<option value="<?php echo esc_html( $index ); ?>" <?php selected( $and_condition['field'], $index ); ?> ><?php echo esc_html( $field ); ?></option>
						<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
			</select>
			<select name="condition[<?php echo esc_html( $or_index ); ?>][<?php echo esc_html( $and_index ); ?>][option]" class="condition-option-field">
				<option value="-1"><?php esc_html_e( 'Select Condition', 'woo-crm-integration-for-zoho' ); ?></option>
				<?php foreach ( $this->get_available_feed_filters() as $key => $value ) : ?>
					<option value="<?php echo esc_html( $key ); ?>" <?php selected( $and_condition['option'], $key ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
			<input type="text" name="condition[<?php echo esc_html( $or_index ); ?>][<?php echo esc_html( $and_index ); ?>][value]" class="condition-value-field" value="<?php echo esc_html( ! empty( $and_condition['value'] ) ? $and_condition['value'] : '' ); ?>" placeholder="<?php esc_html_e( 'Enter value', 'woo-crm-integration-for-zoho' ); ?>" >
			<?php if ( 1 != $and_index ) : // @codingStandardsIgnoreLine ?>
				<span class="dashicons dashicons-no"></span>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Feeds Condtional html.
	 *
	 * @param     string $and_condition  The and condition of current html.
	 * @param     string $and_index      The and offset of current html.
	 * @param     string $or_index       The or offset of current html.
	 * @since     1.0.0
	 * @return    mixed
	 */
	public function render_and_conditon_cf7( $and_condition = array(), $and_index = '1', $or_index = '' ) {

		if ( empty( $and_index ) || empty( $and_condition ) || empty( $or_index ) ) {
			return;
		}

		?>
		<div class="and-condition-filter" data-and-index=<?php echo esc_attr( $and_index ); ?> >
			<select name="condition[<?php echo esc_html( $or_index ); ?>][<?php echo esc_html( $and_index ); ?>][field]"  class="condition-form-field">
				<option value="-1" ><?php esc_html_e( 'Select Field', 'woo-crm-integration-for-zoho' ); ?></option>
				<?php foreach ( $and_condition['form'] as $key => $value ) : ?>
					<optgroup label="<?php echo esc_html( $key ); ?>">
						<?php foreach ( $value as $index => $field ) : ?>
							<option value="<?php echo esc_html( $index ); ?>" <?php selected( $and_condition['field'], $index ); ?> ><?php echo esc_html( $field ); ?></option>
						<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
			</select>
			<select name="condition[<?php echo esc_html( $or_index ); ?>][<?php echo esc_html( $and_index ); ?>][option]" class="condition-option-field">
				<option value="-1"><?php esc_html_e( 'Select Condition', 'woo-crm-integration-for-zoho' ); ?></option>
				<?php foreach ( $this->get_avialable_form_filters() as $key => $value ) : ?>
					<option value="<?php echo esc_html( $key ); ?>" <?php selected( $and_condition['option'], $key ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
			<input type="text" name="condition[<?php echo esc_html( $or_index ); ?>][<?php echo esc_html( $and_index ); ?>][value]" class="condition-value-field" value="<?php echo esc_html( ! empty( $and_condition['value'] ) ? $and_condition['value'] : '' ); ?>" placeholder="<?php esc_html_e( 'Enter value', 'woo-crm-integration-for-zoho' ); ?>">
			<?php if ( 1 != $and_index ) : // phpcs:ignore ?>
				<span class="dashicons dashicons-no"></span>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Feeds conditional filter options.
	 *
	 * @since     1.0.0
	 * @return    array
	 */
	public function get_avialable_form_filters() {

		$filter = array(
			'exact_match'       => esc_html__( 'Matches exactly', 'woo-crm-integration-for-zoho' ),
			'no_exact_match'    => esc_html__( 'Does not match exactly', 'woo-crm-integration-for-zoho' ),
			'contains'          => esc_html__( 'Contains (Text)', 'woo-crm-integration-for-zoho' ),
			'not_contains'      => esc_html__( 'Does not contain (Text)', 'woo-crm-integration-for-zoho' ),
			'exist'             => esc_html__( 'Exist in (Text)', 'woo-crm-integration-for-zoho' ),
			'not_exist'         => esc_html__( 'Does not Exists in (Text)', 'woo-crm-integration-for-zoho' ),
			'starts'            => esc_html__( 'Starts with (Text)', 'woo-crm-integration-for-zoho' ),
			'not_starts'        => esc_html__( 'Does not start with (Text)', 'woo-crm-integration-for-zoho' ),
			'ends'              => esc_html__( 'Ends with (Text)', 'woo-crm-integration-for-zoho' ),
			'not_ends'          => esc_html__( 'Does not end with (Text)', 'woo-crm-integration-for-zoho' ),
			'less_than'         => esc_html__( 'Less than (Text)', 'woo-crm-integration-for-zoho' ),
			'greater_than'      => esc_html__( 'Greater than (Text)', 'woo-crm-integration-for-zoho' ),
			'less_than_date'    => esc_html__( 'Less than (Date/Time)', 'woo-crm-integration-for-zoho' ),
			'greater_than_date' => esc_html__( 'Greater than (Date/Time)', 'woo-crm-integration-for-zoho' ),
			'equal_date'        => esc_html__( 'Equals (Date/Time)', 'woo-crm-integration-for-zoho' ),
			'empty'             => esc_html__( 'Is empty', 'woo-crm-integration-for-zoho' ),
			'not_empty'         => esc_html__( 'Is not empty', 'woo-crm-integration-for-zoho' ),
		);

		/**
		 * Filters the form filters.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $filter filters.
		 */
		return apply_filters( 'wciz_zoho_cf7_condition_filter', $filter );
	}

	/**
	 * Get available filter options.
	 *
	 * @since    1.0.0
	 * @return   array
	 */
	public function get_filter_mapping_dataset() {
		return $this->get_available_feed_filters();
	}

	/**
	 * Returns the product status.
	 *
	 * @param  object $product Product.
	 * @param  int    $id      Product id.
	 * @return string
	 */
	public function wciz_zoho_get_product_status( $product, $id ) {
		$product_status = '';
		if ( 'product_variation' == $product->post_type ) {
			$variation = wc_get_product( $id );
			if ( ! empty( $variation ) ) {
				$parent_id      = $variation->get_parent_id();
				$parent_product = get_post( $parent_id );
				if ( ! empty( $parent_product ) ) {
					$product_status = $parent_product->post_status;
				}
			}
		} else {
			$product_status = $product->post_status;
		}

		return $product_status;
	}

	/**
	 * Get all feeds.
	 *
	 * @since     1.0.0
	 * @return    array    An array of all feeds.
	 */
	public function get_available_crm_feeds() {

		$args = array(
			'post_type'   => 'wciz_cf7_zoho_feeds',
			'post_status' => array( 'publish', 'draft' ),
			'numberposts' => -1,
			'order'       => 'ASC',
			'meta_query'  => array( // phpcs:ignore
				array(
					'key'     => 'wciz-zoho-cf7-dependent-on',
					'compare' => 'NOT EXISTS',
				),
			),
		);

		return get_posts( $args );
	}

	/**
	 * Get feeds by form.
	 *
	 * @param     integer $form_id     Form ID.
	 * @since     1.0.0
	 * @return    array
	 */
	public function get_feeds_by_form( $form_id = false ) {
		if ( false == $form_id ) { // phpcs:ignore
			return;
		}

		$feeds = get_posts(
			array(
				'post_type'   => 'wciz_cf7_zoho_feeds',
				'post_status' => array( 'publish', 'draft' ),
				'numberposts' => -1,
				'meta_query'  => array( // phpcs:ignore
					array(
						'relation' => 'AND',
						array(
							'key'     => 'wciz_zcf7_form',
							'compare' => 'EXISTS',
						),
						array(
							'key'     => 'wciz_zcf7_form',
							'value'   => $form_id,
							'compare' => '==',
						),
						array(
							'key'     => 'wciz-zoho-cf7-dependent-on',
							'compare' => 'NOT EXISTS',
						),
					),
				),
			)
		);

		return $feeds;
	}

	/**
	 * Clear log table.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function delete_sync_log() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wciz_zcf7_log';
		$wpdb->query( $wpdb->prepare( 'TRUNCATE TABLE %s', $table_name ), ARRAY_A ); // @phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		// delete the existing log file.
		$log_file = WC_LOG_DIR . 'wciz-zcf7-sync-log.log';
		if ( file_exists( $log_file ) ) {
			wp_delete_file( $log_file );
		}
	}
	// End of class.
}
