<?php
/**
 * This class is for validating the unit on Product, Cart and Checkout page.
 *
 * @package    Woounits
 * @since      1.0.0
 * @subpackage Woounits/includes
 */

if ( ! class_exists( 'Woounits_Validation' ) ) {

	/**
	 * Class for Handling Validation on Product, Cart and Checkout page
	 *
	 * @class Woounits_Validation
	 */
	class Woounits_Validation {

		/**
		 * Default constructor
		 *
		 * @since 2.0
		 */

		public function __construct() {

			add_filter( 'woocommerce_add_to_cart_validation', array( &$this, 'wut_get_validate_add_cart_item' ), 8, 3 );
			add_action( 'woocommerce_before_checkout_process', array( &$this, 'wut_cart_checkout_quantity_check' ) );
			add_action( 'woocommerce_check_cart_items', array( &$this, 'wut_cart_checkout_quantity_check' ) );

			// To validate the product in cart and checkout as per the unit Period set.
			add_action( 'woocommerce_check_cart_items', array( &$this, 'wut_remove_product_from_cart' ) );
			add_action( 'woocommerce_before_checkout_process', array( &$this, 'wut_remove_product_from_cart' ) );

			add_filter( 'wut_get_validate_add_cart_item', array( &$this, 'wut_same_unit_in_cart_validation' ), 10, 3 );
		}

		public static function wut_get_validate_add_cart_item( $passed, $product_id, $quantity ) {
			$product = wc_get_product($product_id);

			if (!$product || !$product->managing_stock()) {
				return $passed; // Skip if stock management is disabled
			}

			// Get current stock in base unit
			$current_stock = (float) $product->get_stock_quantity();

			// Get selected unit details from the request
			$selected_unit = isset($_POST['wut_lbl']) ? sanitize_text_field($_POST['wut_lbl']) : ''; 
			$selected_rate = isset($_POST['wut_selected_rate']) ? (float) $_POST['wut_selected_rate'] : 1; 
			$selected_from = isset($_POST['wut_selected_from']) ? $_POST['wut_selected_from'] : '';
			$selected_to   = isset($_POST['wut_selected_to']) ? $_POST['wut_selected_to'] : '';

			if (!$selected_unit || !$selected_rate) {
				return $passed; // If no unit is selected, allow default behavior
			}

    		// Convert order quantity to base unit
			$order_qty_in_base = ($selected_rate * $quantity);

			// Check if order exceeds available stock
			if ($order_qty_in_base > $current_stock) {
				wc_add_notice(sprintf(__('You cannot add that amount of "%s" to the cart because there is not enough stock (%.2f kg remaining).', 'woocommerce'), $product->get_name(), $current_stock), 'error');
				return false;
			}
		
			return $passed;
		}
		

		/**
		 * This functions validates the Availability for the selected date and timeslots.
		 *
		 * @global $wp Global $wp Object
		 * @param string $passed Validation Status.
		 * @param int    $product_id Product Id.
		 * @param int    $qty Quantity selected when adding product to cart.
		 *
		 * @since 2.0
		 * @hook woocommerce_add_to_cart_validation
		 * @return string $passed Returns true if allowed to add to cart else false if not.
		 */
		public static function wut_get_validate_add_cart_item1( $passed, $product_id, $qty ) {

			global $wp;

			$perform_validation = apply_filters( 'wut_skip_add_to_cart_validation', false, $product_id );

			if ( $perform_validation ) {
				return $passed;
			}

			$product_id       = WooUnits_Common::wut_get_product_id( $product_id );
			// $booking_settings = bkap_setting( $product_id );
			$product          = wc_get_product( $product_id );
			// $bkap_sale        = apply_filters( 'wut_get_validate_add_cart_item_sale_rent', 'sale' );

			do_action( 'wut_get_validate_add_cart_item_before', $product_id, $product );




			if (!$product || !$product->managing_stock()) {
				return $passed; // Skip if stock management is disabled
			}

			// Get total stock (base unit in KG)
			$current_stock = (float) $product->get_stock_quantity(); 

			// Get selected unit details
			$selected_unit = isset($_POST['wut_lbl']) ? sanitize_text_field($_POST['wut_lbl']) : ''; // e.g., "gm"
			$selected_rate = isset($_POST['wut_selected_rate']) ? (float) $_POST['wut_selected_rate'] : 1; // Convert rate
			$selected_from = isset($_POST['wut_selected_from']) ? $_POST['wut_selected_from'] : '';
			$selected_to   = isset($_POST['wut_selected_to']) ? $_POST['wut_selected_to'] : '';

			if (!$selected_unit || !$selected_rate) {
				return $passed; // If no unit is selected, allow default behavior
			}

			// Convert order quantity to KG
			$order_weight_in_kg = ($selected_rate * $qty); // Convert grams to kg

			// Check if the order exceeds available stock
			if ($order_weight_in_kg > $current_stock) {
				wc_add_notice(sprintf(__('You 1111111111 cannot add that amount of "%s" to the cart because there is not enough stock (%s kg remaining).', 'woocommerce'), $product->get_name(), $current_stock), 'error');
				return false;
			}

			// return $passed;





			// if ( '' != $booking_settings && ( isset( $booking_settings['booking_enable_date'] ) && $booking_settings['booking_enable_date'] == 'on' ) ) {

			// 	$date_check = self::bkap_post_date_validation( $product_id, $booking_settings );
			// 	if ( $date_check ) {

			// 		$quantity     = self::wut_get_quantity( $product_id );
			// 		$passed       = 'yes' === $quantity ? true : false;
			// 		$product_type = $product->get_type();

			// 		if ( 'composite' === $product_type ) {
			// 			$passed = self::bkap_get_composite_item_validations( $product_id, $product );
			// 		}

			// 		if ( 'bundle' === $product_type ) {
			// 			$passed = self::bkap_get_bundle_item_validations( $product_id, $product );
			// 		}
			// 	} elseif ( isset( $_GET['pay_for_order'] ) && isset( $_GET['key'] ) && isset( $wp->query_vars['order-pay'] ) && isset( $_GET['subscription_renewal'] ) && $_GET['subscription_renewal'] === 'true' ) {
			// 		$passed = true;
			// 	} else {

			// 		if ( isset( $booking_settings['booking_purchase_without_date'] ) && $booking_settings['booking_purchase_without_date'] == 'on' && $bkap_sale == 'sale' ) {
			// 			$passed = true;
			// 		} else {
			// 			$passed  = false;
			// 			$message = apply_filters( 'bkap_cant_be_added_to_cart_without_booking', __( 'Product can\'t be added to cart. Please select booking details to continue.', 'woocommerce-booking' ) );
			// 			if ( ! wc_has_notice( $message, 'error' ) ) {
			// 				wc_add_notice( $message, 'error' );
			// 			}
			// 			wp_safe_redirect( get_permalink( $product_id ) );
			// 			exit();
			// 		}
			// 	}
			// } else {
			// 	$passed = true;
			// }
			// $passed = true;

			do_action( 'wut_get_validate_add_cart_item_after', $product_id, $product );

			return apply_filters( 'wut_get_validate_add_cart_item', $passed, $product_id, $product );
		}

		/**
		 * This functions check if date is selected when adding to cart or not.
		 *
		 * @param string|int $product_id Product ID.
		 *
		 * @return bool true if booking date is selected else false.
		 * @since 5.3.0
		 */
		public static function bkap_post_date_validation( $product_id ) {

			$status = false;
			if ( isset( $_POST['wapbk_hidden_date'] ) && '' !== $_POST['wapbk_hidden_date'] ) {
				$status = true;
			}

			if ( isset( $_POST['bkap_multidate_data'] ) && '' !== $_POST['bkap_multidate_data'] ) {
				$status = true;
			}

			return $status;
		}

		/**
		 * This functions Validate Composite Products
		 *
		 * @param string|int $product_id Product ID.
		 * @param WC_Product $product Product Object.
		 *
		 * @return bool true for available and false for locked out
		 * @since 5.13.0
		 */
		public static function bkap_get_composite_item_validations( $product_id, $product ) {

			$wc_cp_cart    = new WC_CP_Cart();
			$configuration = $wc_cp_cart->get_posted_composite_configuration( $product_id );
			$status        = array();
			foreach ( $configuration as $cart_key => $cart_value ) {
				if ( isset( $cart_value['product_id'] ) && '' !== $cart_value['product_id'] ) {
					if ( isset( $cart_value['quantity'] ) && $cart_value['quantity'] > 0 ) {

						if ( isset( $cart_value['variation_id'] ) && $cart_value['variation_id'] !== '' ) {
							$_POST['variation_id'] = $cart_value['variation_id'];
						}

						$quantity = self::wut_get_quantity( $cart_value['product_id'], $cart_value['quantity'] );
						$status[] = $quantity;
					}
				}
			}

			if ( in_array( 'no', $status, true ) ) {
				return false;
			}

			return true;
		}

		/**
		 * This functions Validate Bundle Products
		 *
		 * @param string|int $product_id Product ID.
		 * @param WC_Product $product Product Object.
		 *
		 * @return bool true for available and false for locked out
		 * @since 4.2
		 */

		public static function bkap_get_bundle_item_validations( $product_id, $product ) {

			// $cart_configs = bkap_common::bkap_bundle_add_to_cart_config( $product );

			foreach ( $cart_configs as $cart_key => $cart_value ) {

				if ( isset( $cart_value['quantity'] ) && $cart_value['quantity'] > 0 ) {

					if ( isset( $cart_value['variation_id'] ) && $cart_value['variation_id'] !== '' ) {
						$_POST['variation_id'] = $cart_value['variation_id'];
					}

					$quantity = self::wut_get_quantity( $cart_value['product_id'], $cart_value['quantity'] );

					if ( 'yes' !== $quantity ) {
						return false;
					}
				}
			}

			return true;
		}

		/**
		 * This function checks the overlapping timeslot for the selected date
		 * and timeslots when the product is added to cart.
		 *
		 * If the overlapped timeslot is already in cart and availability is less then selected
		 * bookinng date and timeslot then it will retun and array which contains the overlapped date
		 * and timeslot present in the cart
		 *
		 * @param int    $product_id Product ID
		 * @param array  $post $_POST
		 * @param string $check_in_date Date
		 * @param string $from_time_slot From Time
		 * @param string $to_time_slot To Time
		 * @since 4.4.0
		 * @return $pass_fail_result_array Array contains overlapped start date and timeslot present in cart.
		 */
		public static function bkap_validate_overlap_time_cart( $product_id, $post, $check_in_date, $from_time_slot, $to_time_slot ) {
			global $wpdb;

			$qty                    = isset( $post['quantity'] ) ? $post['quantity'] : 1;
			$pass_fail_result_array = array();
			$pass_fail_result       = true;

			$query              = 'SELECT *  FROM `' . $wpdb->prefix . "booking_history`
                                        WHERE post_id = '" . $product_id . "' AND
                                        start_date = '" . $check_in_date . "' AND
                                        available_booking > 0  AND
                                        status !=  'inactive' ";
			$get_all_time_slots = $wpdb->get_results( $query );

			if ( count( $get_all_time_slots ) == 0 ) {
				return $pass_fail_result_array;
			}

			foreach ( $get_all_time_slots as $time_slot_key => $time_slot_value ) {

				$timeslot = $from_time_slot . ' - ' . $to_time_slot;

				$query_from_time_time_stamp = strtotime( $from_time_slot );
				$query_to_time_time_stamp   = strtotime( $to_time_slot );

				$time_slot_value_from_time_stamp = strtotime( $time_slot_value->from_time );
				$time_slot_value_to_time_stamp   = strtotime( $time_slot_value->to_time );

				$db_timeslot = $time_slot_value->from_time . ' - ' . $time_slot_value->to_time;

				if ( $query_to_time_time_stamp > $time_slot_value_from_time_stamp && $query_from_time_time_stamp < $time_slot_value_to_time_stamp ) {

					if ( $time_slot_value_from_time_stamp != $query_from_time_time_stamp || $time_slot_value_to_time_stamp != $query_to_time_time_stamp ) {

						foreach ( WC()->cart->cart_contents as $prod_in_cart_key => $prod_in_cart_value ) {

							if ( isset( $prod_in_cart_value['wut_sunit'] ) && ! empty( $prod_in_cart_value['wut_sunit'] ) ) {

								$booking_data = $prod_in_cart_value['wut_sunit'];
								$product_qty  = $prod_in_cart_value['quantity'];

								foreach ( $booking_data as $value ) {

									if ( isset( $value['time_slot'] ) && $value['time_slot'] != '' ) {

										if ( $value['time_slot'] == $db_timeslot && $time_slot_value->available_booking > 0 ) {

											$compare_qty = $time_slot_value->available_booking - $product_qty;

											if ( $compare_qty < $qty ) {
												$pass_fail_result = false;

												$pass_fail_result_array['pass_fail_result']   = $pass_fail_result;
												$pass_fail_result_array['pass_fail_date']     = $check_in_date;
												$pass_fail_result_array['pass_fail_timeslot'] = $db_timeslot;
											}
										}
									}
								}
							}
						}
					}
				}
			}

			return $pass_fail_result_array;
		}

		/**
		 * Based on the global timeslot option, this function adds up to the quantity to the selected quantity.
		 *
		 * @param int   $post_id Product ID.
		 * @param array $post $_POST.
		 * @param int   $item_quantity Item Quantity.
		 * @since 5.6.1
		 */
		public static function bkap_validate_global_time_cart( $post_id, $post, $item_quantity ) {

			foreach ( WC()->cart->cart_contents as $cart_key => $cart_value ) {
				if ( isset( $cart_value['wut_sunit'] ) && ! empty( $cart_value['wut_sunit'] ) ) {
					$product_id = $cart_value['product_id'];
					if ( $product_id != $post_id ) {

						$booking_data = $cart_value['wut_sunit'];
						foreach ( $booking_data as $value ) {

							if ( isset( $value['time_slot'] ) && '' !== $value['time_slot'] ) {

								if ( $post['wapbk_hidden_date'] === $value['hidden_date'] && $value['time_slot'] === $post['time_slot'] ) {
									$item_quantity = $item_quantity + $cart_value['quantity'];
								}
							}
						}
					}
				}
			}

			return $item_quantity;
		}

		/**
		 * This function checks the availabilty for the selected date and timeslots when the product is added to cart.
		 * If availability is less then selected it prevents product from being added to the cart and displays an error message.
		 *
		 * @param int $post_id Product ID.
		 * @param int $bundle_child_qty Bundle Child Quantity.
		 *
		 * @since   4.4.0
		 * @global  object $wpdb Global wpdb Object.
		 * @global  object $woocommerce Global WooCommerce Object.
		 *
		 * @return  Array $pass_fail_result_array Array contains overlapped start date and timeslot present in cart.
		 */
		public static function wut_get_quantity( $post_id, $bundle_child_qty = '' ) {

			global $wpdb;
			global $woocommerce;

			$post_id      = WooUnits_Common::wut_get_product_id( $post_id );
			$product      = wc_get_product( $post_id );
			$product_name = $product->get_name();
			$wc_version   = ( version_compare( WOOCOMMERCE_VERSION, '3.0.0' ) < 0 );
			// $parent_id    = ( $wc_version ) ? $product->get_parent() : bkap_common::bkap_get_parent_id( $post_id );

			$booking_settings       = bkap_setting( $post_id );
			$booking_type           = bkap_type( $post_id );
			// $global_settings        = bkap_global_setting();
			// $time_format            = $global_settings->booking_time_format;
			// $date_format_to_display = $global_settings->booking_date_format;

			// $timezone_check = bkap_timezone_check( $global_settings ); // Check if the timezone setting is enabled.

			// new chanegs..
			$date_checks   = array();
			$booking_dates = array();
			$time_slots    = array();
			if ( isset( $_POST['wapbk_hidden_date'] ) && '' !== $_POST['wapbk_hidden_date'] ) {
				$date_checks[]   = $_POST['wapbk_hidden_date']; // Selected date in Y-m-d format.
				$booking_dates[] = $_POST['booking_calender']; // Date will be in selected language.
				if ( isset( $_POST['time_slot'] ) ) {
					$time_slots[] = $_POST['time_slot'];
				}
				$date_to_display = date( $bkap_date_formats[ $date_format_to_display ], strtotime( $_POST['wapbk_hidden_date'] ) ); // Date as per global setting.
			}

			$quantity_check_pass = 'yes';
			$variation_id        = isset( $_POST['variation_id'] ) ? $_POST['variation_id'] : '';
			$_POST['product_id'] = $post_id;

			/* Before checking lockout validations, confirm that the cart does not contain any conflicting products */
			$quantity_check_pass = apply_filters( 'bkap_validate_cart_products', $_POST, $post_id ); // yes if all good.

					do_action( 'bkap_single_days_product_validation' );

					if ( ! isset( $_POST['validated'] ) || ( isset( $_POST['validated'] ) && $_POST['validated'] == 'NO' ) ) {

						foreach ( $date_checks as $date_check ) { // loop through each date.

							$date_check_ymd = date( 'Y-m-d', strtotime( $date_check ) );

							$query   = 'SELECT total_booking, available_booking, start_date FROM `' . $wpdb->prefix . "booking_history`
										WHERE post_id = %d
										AND start_date = %s
										AND status != 'inactive' ";
							$results = $wpdb->get_results( $wpdb->prepare( $query, $post_id, $date_check_ymd ) );

							$item_quantity = isset( $_POST['quantity'] ) ? $_POST['quantity'] : 1;

							if ( isset( $bundle_child_qty ) && $bundle_child_qty > 0 ) {
								$item_quantity = $item_quantity * $bundle_child_qty;
							}

							if ( isset( $results ) && count( $results ) > 0 ) {

								$date_to_display = date( $bkap_date_formats[ $date_format_to_display ], strtotime( $results[0]->start_date ) );

								// Validation for parent products page - Grouped Products  , Here $item_array come Array when order place from the Parent page
								if ( isset( $parent_id ) && $parent_id != '' && is_array( $item_quantity ) ) {

									$item_quantity[ $post_id ] = $item_quantity[ $post_id ] * $total_person;
									if ( $results[0]->available_booking > 0 && $results[0]->available_booking < $item_quantity[ $post_id ] ) {

										$msg_text = __( get_option( 'book_limited-booking-msg-date' ), 'woocommerce-booking' );
										$message  = str_replace( array( 'PRODUCT_NAME', 'AVAILABLE_SPOTS', 'DATE' ), array( $product_name, $results[0]->available_booking, $date_to_display ), $msg_text );
										if ( ! wc_has_notice( $message, 'error' ) ) {
											wc_add_notice( $message, $notice_type = 'error' );
										}
										$quantity_check_pass = 'no';

									} elseif ( $results[0]->total_booking > 0 && $results[0]->available_booking == 0 ) {

										$msg_text = __( get_option( 'book_no-booking-msg-date' ), 'woocommerce-booking' );
										$message  = str_replace( array( 'PRODUCT_NAME', 'DATE' ), array( $product_name, $date_to_display ), $msg_text );
										if ( ! wc_has_notice( $message, 'error' ) ) {
											wc_add_notice( $message, $notice_type = 'error' );
										}
										$quantity_check_pass = 'no';
									}
								} else {
									$item_quantity = $item_quantity * $total_person;
									if ( $results[0]->available_booking > 0 && $results[0]->available_booking < $item_quantity ) {

										$msg_text = __( get_option( 'book_limited-booking-msg-date' ), 'woocommerce-booking' );
										$message  = str_replace( array( 'PRODUCT_NAME', 'AVAILABLE_SPOTS', 'DATE' ), array( $product_name, $results[0]->available_booking, $date_to_display ), $msg_text );
										if ( ! wc_has_notice( $message, 'error' ) ) {
											wc_add_notice( $message, $notice_type = 'error' );
										}
										$quantity_check_pass = 'no';

									} elseif ( $results[0]->total_booking > 0 && $results[0]->available_booking == 0 ) {

										$msg_text = __( get_option( 'book_no-booking-msg-date' ), 'woocommerce-booking' );
										$message  = str_replace( array( 'PRODUCT_NAME', 'DATE' ), array( $product_name, $date_to_display ), $msg_text );
										if ( ! wc_has_notice( $message, 'error' ) ) {
											wc_add_notice( $message, $notice_type = 'error' );
										}
										$quantity_check_pass = 'no';
									}
								}
							}

							if ( 'yes' === $quantity_check_pass ) {

								$total_quantity = 0;
								foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {

									if ( array_key_exists( 'wut_sunit', $values ) ) {
										$booking = $values['wut_sunit'];
									} else {
										$booking = array();
									}

									$cart_total_person = 1;
									if ( isset( $booking[0]['persons'] ) ) {
										if ( 'on' === $booking_settings['bkap_each_person_booking'] ) {
											$cart_total_person = array_sum( $booking[0]['persons'] );
										}
									}

									$quantity   = $values['quantity'] * $cart_total_person;
									$product_id = $values['product_id'];

									if ( $product_id == $post_id && isset( $booking[0]['hidden_date'] ) && $date_check && $booking[0]['hidden_date'] == $date_check ) {

										// if ( isset( $parent_id ) && $parent_id != '' && is_array( $item_quantity ) ) {
										// $item_quantity[ $post_id ] = $item_quantity[ $post_id ] * $total_person;
										$total_quantity += /* $item_quantity[ $post_id ] + */ $quantity;
										// } else {
										/*
										 $item_quantity  = ( $added ) ? 0 : $item_quantity * $total_person; */
										// $total_quantity += /* $item_quantity + */ $quantity;
										// }
									}
								}

								if ( isset( $results ) && count( $results ) > 0 ) {

									$date_to_display = date( $bkap_date_formats[ $date_format_to_display ], strtotime( $results[0]->start_date ) );

									if ( isset( $parent_id ) && $parent_id != '' && is_array( $item_quantity ) ) {
										$total_quantity = $total_quantity + $item_quantity[ $post_id ];
										if ( $results[0]->available_booking > 0 && $results[0]->available_booking < $total_quantity ) {
											$msg_text = __( get_option( 'book_limited-booking-msg-date' ), 'woocommerce-booking' );
											$message  = str_replace( array( 'PRODUCT_NAME', 'AVAILABLE_SPOTS', 'DATE' ), array( $product_name, $results[0]->available_booking, $date_to_display ), $msg_text );
											if ( ! wc_has_notice( $message, 'error' ) ) {
												wc_add_notice( $message, $notice_type = 'error' );
											}
											$quantity_check_pass = 'no';
										}
									} else {
										$total_quantity = $total_quantity + $item_quantity;
										if ( $results[0]->available_booking > 0 && $results[0]->available_booking < $total_quantity ) {
											$msg_text = __( get_option( 'book_limited-booking-msg-date' ), 'woocommerce-booking' );
											$message  = str_replace( array( 'PRODUCT_NAME', 'AVAILABLE_SPOTS', 'DATE' ), array( $product_name, $results[0]->available_booking, $date_to_display ), $msg_text );
											if ( ! wc_has_notice( $message, 'error' ) ) {
												wc_add_notice( $message, $notice_type = 'error' );
											}
											$quantity_check_pass = 'no';
										}
									}
								}
							}
						}
					} else {
						$quantity_check_pass = $_POST['quantity_check_pass'];
					}
			return $quantity_check_pass;
		}

		/**
		 * This function checks an order was already created, this means the validation has been run once already.
		 *
		 * @since 4.10.0
		 *
		 * @global object $wpdb Global wpdb Object
		 * @return bool $status true if order is created else false.
		 */

		public static function bkap_order_created_check() {
			global $wpdb;

			$status   = false;
			$order_id = absint( WC()->session->order_awaiting_payment ); // Get the order ID if an order is already pending

			if ( $order_id > 0 && ( $order = wc_get_order( $order_id ) ) && $order->has_status( array( 'pending', 'failed' ) ) ) {
				// Confirm if data is found in the order history table for the given order, then we need to skip the validation
				$check_data    = 'SELECT * FROM `' . $wpdb->prefix . 'booking_order_history`
                                WHERE order_id = %s';
				$results_check = $wpdb->get_results( $wpdb->prepare( $check_data, $order_id ) );

				if ( count( $results_check ) > 0 ) {
					$status = true;
				}
			}
			return $status;
		}

		/**
		 * This function checks availability for date and time slot on the cart page when quantity on cart page is changed.
		 *
		 * @since 2.0
		 * @hook woocommerce_before_checkout_process
		 * @global object $wpdb Global wpdb Object
		 * @global object $woocommerce Global WooCommerce Object
		 */
		public static function wut_cart_checkout_quantity_check() {
			global $wpdb;

			// check if the order is already created.
			if ( self::bkap_order_created_check() ) {
				return;
			}

			$wc_cart_object    = WC();
			if ( count( $wc_cart_object->cart->cart_contents ) > 0 ) {

				$availability_display = array();
				foreach ( $wc_cart_object->cart->cart_contents as $key => $value ) {

					if ( isset( $value['wut_sunit'][0]['purchase_unit'] ) ) {
						$ws_units = $value['wut_sunit'];
					} else {
						continue;
					}

					foreach ( $ws_units as $b => $bkap_booking ) {

						$date_availablity = array();
						$duplicate_of     = WooUnits_Common::wut_get_product_id( $value['product_id'] );
						$post_title       = get_post( $value['product_id'] );

						$variation_id = isset( $value['variation_id'] ) ? $value['variation_id'] : '';

								do_action( 'bkap_single_days_cart_validation' );
								$validation_completed = isset( $_POST['validation_status'] ) ? $_POST['validation_status'] : '';

								if ( empty( $validation_completed ) || $validation_completed == 'NO' ) {

									// $lockout = 10;
									// if ( 'unlimited' !== $lockout ) {
										// if ( ! $results ) {
										// 	break;
										// } else {
											$qty_check = 4;
											$available_tickets = 10;
											if ( $available_tickets > 0 && $available_tickets < $qty_check ) {
												$values_tobe_replaced = array( $post_title->post_title, $available_tickets, $date_to_display );
												$message              = bkap_str_replace( 'book_limited-booking-msg-date', $values_tobe_replaced );
												if ( ! wc_has_notice( $message, 'error' ) ) {
													wc_add_notice( $message, $notice_type = 'error' );
												}
												bkap_remove_proceed_to_checkout();
											} 
											// elseif ( $results[0]->total_booking > 0 && $available_tickets == 0 ) {
											// 	$values_tobe_replaced = array( $post_title->post_title, $date_to_display );
											// 	$message              = bkap_str_replace( 'book_no-booking-msg-date', $values_tobe_replaced );
											// 	if ( ! wc_has_notice( $message, 'error' ) ) {
											// 		wc_add_notice( $message, $notice_type = 'error' );
											// 	}
											// 	bkap_remove_proceed_to_checkout();
											// } elseif ( $results[0]->total_booking == 0 && $available_tickets == 0 ) {
											// 	$values_tobe_replaced = array( $post_title->post_title, $available_tickets, $date_to_display );
											// 	$message              = bkap_str_replace( 'book_limited-booking-msg-date', $values_tobe_replaced );
											// 	if ( ! wc_has_notice( $message, 'error' ) ) {
											// 		wc_add_notice( $message, $notice_type = 'error' );
											// 	}
											// 	bkap_remove_proceed_to_checkout();
											// }
										// }
									// }
								}
					}
				} // cart each.
			}
		}

		/**
		 * This function will remove the product from cart when date and/or time is passed.
		 *
		 * @since 2.5.3
		 * @hook woocommerce_check_cart_items
		 * @hook woocommerce_before_checkout_process
		 * @global object $wpdb Global wpdb Object
		 */

		public static function wut_remove_product_from_cart() {
			global $wpdb;

			// Run only in the Cart or Checkout Page
			if ( is_cart() || is_checkout() ) {

				// $global_settings = bkap_global_setting();
				// $current_time    = current_time( 'timestamp' );
				// $date_today      = date( 'Y-m-d H:i', $current_time );
				// $today           = new DateTime( $date_today );
				// $phpversion      = version_compare( phpversion(), '5.3', '>' );
				// $global_holidays = array();
				// if ( isset( $global_settings->booking_global_holidays ) ) {
				// 	$global_holidays = explode( ',', $global_settings->booking_global_holidays );
				// }

				foreach ( WC()->cart->cart_contents as $prod_in_cart_key => $prod_in_cart_value ) {

					if ( isset( $prod_in_cart_value['wut_sunit'] ) && ! empty( $prod_in_cart_value['wut_sunit'] ) ) {

						$date_strtotime = '';

						// Get the Variation or Product ID
						if ( isset( $prod_in_cart_value['product_id'] ) && $prod_in_cart_value['product_id'] != 0 ) {
							$prod_id = $prod_in_cart_value['product_id'];
						}

						$duplicate_of     = WooUnits_Common::wut_get_product_id( $prod_id );
						// $booking_settings = array();
						// $holiday_array    = isset( $booking_settings['booking_product_holiday'] ) ? $booking_settings['booking_product_holiday'] : array();

						// $holiday_array_keys = array();
						// if ( is_array( $holiday_array ) && count( $holiday_array ) > 0 ) {
						// 	$holiday_array_keys = array_keys( $holiday_array );
						// }

						$unit_data = $prod_in_cart_value['wut_sunit'];

						foreach ( $unit_data as $key => $value ) {

							if ( isset( $value['hidden_date'] ) && $value['hidden_date'] != '' ) { // can be blanks if the product has been purchased without a date
								$date           = $value['hidden_date'];
								$date_strtotime = strtotime( $date );

								// Product is in cart and later store admin set the date as global holiday then remove from cart.
								if ( is_array( $global_holidays ) && count( $global_holidays ) > 0 ) {
									if ( in_array( $date, $global_holidays ) ) {
										unset( WC()->cart->cart_contents[ $prod_in_cart_key ] );
										continue;
									}
								}

								if ( isset( $value['time_slot'] ) && $value['time_slot'] != '' ) {
									$advance_booking_hrs  = bkap_advance_booking_hrs( $booking_settings, $duplicate_of );
									$time_slot_to_display = $value['time_slot'];

									if ( strpos( $time_slot_to_display, '<br>' ) !== false ) {
										$timeslots = explode( '<br>', $time_slot_to_display );
									} else {
										$timeslots = array( $time_slot_to_display );
									}

									foreach ( $timeslots as $k => $v ) {
										if ( '' !== $v ) {
											$time_exploded = explode( ' - ', $v );
											$from_time     = $time_exploded[0];
											$to_time       = isset( $time_exploded[1] ) ? $time_exploded[1] : '';
											$dateymd       = date( 'Y-m-d', $date_strtotime );
											$booking_time  = $dateymd . $from_time;
											$booking_time  = apply_filters( 'bkap_change_date_comparison_for_abp', $booking_time, $dateymd, $from_time, $to_time, $duplicate_of, $booking_settings );
											$date2         = new DateTime( $booking_time );
											$include       = bkap_dates_compare( $today, $date2, $advance_booking_hrs, $phpversion );
											if ( ! $include ) {
												break;
											}
										}
									}

									if ( ! $include ) {
										unset( WC()->cart->cart_contents[ $prod_in_cart_key ] );
									}
								}

								$custom_ranges = isset( $booking_settings['booking_date_range'] ) ? $booking_settings['booking_date_range'] : array();
								if ( count( $custom_ranges ) > 0 ) {
									$dates = array();
									foreach ( $custom_ranges as $r_key => $range_value ) {
										$range_start_date = $range_value['start'];
										$range_end_date   = $range_value['end'];
										// $dates            = array_merge( $dates, bkap_common::bkap_get_betweendays( $range_start_date, $range_end_date ) );
									}

									$date = date( 'd-n-Y', strtotime( $date ) );
									if ( ! in_array( $date, $dates ) ) {
										unset( WC()->cart->cart_contents[ $prod_in_cart_key ] );
									}
								}

								if ( isset( WC()->cart->cart_contents[ $prod_in_cart_key ] ) ) {
									do_action( 'bkap_remove_bookable_product_from_cart', $value, $prod_in_cart_key, WC()->cart->cart_contents[ $prod_in_cart_key ] );
								}
							}
						}
					}
				}
			}
		}

		/**
		 * This function force the same booking details in the cart.
		 *
		 * @param bool   $passed true.
		 * @param int    $product_id Product ID.
		 * @param array  $booking_settings Booking Settings.
		 * @param object $product Product Object.
		 *
		 * @since 5.10.0
		 */
		public static function wut_same_unit_in_cart_validation( $passed, $product_id, $product ) {

			if ( $passed && isset( $_POST['wapbk_hidden_date'] ) ) {

				$bkap_booking = bkap_get_first_booking_data_from_cart();

				if ( ! empty( $bkap_booking ) ) {

					$date         = $_POST['wapbk_hidden_date'];
					$end_date     = '';
					$booking_type = bkap_type( $product_id );

					if ( isset( $_POST['variation_id'] ) && '' !== $_POST['variation_id'] ) {
						$variation     = wc_get_product( $_POST['variation_id'] );
						$product_title = $variation->get_formatted_name();
					} else {
						$variation     = wc_get_product( $product_id );
						$product_title = $product->get_name();
					}

					if ( $date !== $bkap_booking['hidden_date'] ) {
						if ( isset( $bkap_booking['date_checkout'] ) ) {
							$message = sprintf( __( 'Please select %1$s to %2$s to book %3$s.', 'woocommerce-booking' ), $bkap_booking['date'], $bkap_booking['date_checkout'], $product_title );
						} else {
							$message = sprintf( __( 'Please select %1$s to book %2$s.', 'woocommerce-booking' ), $bkap_booking['date'], $product_title );
						}
						wc_add_notice( $message, $notice_type = 'error' );
						return false;
					}

					if ( isset( $_POST['wapbk_hidden_date_checkout'] ) && '' !== $_POST['wapbk_hidden_date_checkout'] ) {
						$end_date = $_POST['wapbk_hidden_date_checkout'];
					}

					switch ( $booking_type ) {

						case 'multiple_days':
							if ( '' != $end_date ) { // Check if the product being added to cart is havin end date or not.

								if ( isset( $bkap_booking['hidden_date_checkout'] ) ) { // first product in cart having end date or not.

									if ( $date !== $bkap_booking['hidden_date'] || $end_date !== $bkap_booking['hidden_date_checkout'] ) {
										$message = sprintf( __( 'Please select %1$s to %2$s to book %3$s.', 'woocommerce-booking' ), $bkap_booking['date'], $bkap_booking['date_checkout'], $product_title );
										wc_add_notice( $message, $notice_type = 'error' );
										$passed = false;
									}
								} else {

									// checks in cart for the multiple days booking.
									if ( isset( WC()->cart ) ) {

										$mbkap_booking = bkap_get_first_booking_data_from_cart( 'multiple_days' );

										if ( ! empty( $mbkap_booking ) ) {

											if ( $date !== $mbkap_booking['hidden_date'] || $end_date !== $mbkap_booking['hidden_date_checkout'] ) {
												$message = sprintf( __( 'Please select %1$s to %2$s to book %3$s.', 'woocommerce-booking' ), $mbkap_booking['date'], $mbkap_booking['date_checkout'], $product_title );
												wc_add_notice( $message, $notice_type = 'error' );
												$passed = false;
											}
										} else {

											if ( $date !== $bkap_booking['hidden_date'] ) {
												$message = sprintf( __( 'Please select %1$s to %2$s to book %3$s.', 'woocommerce-booking' ), $bkap_booking['date'], $bkap_booking['date_checkout'], $product_title );
												wc_add_notice( $message, $notice_type = 'error' );
												$passed = false;
											}
										}
									}
								}
							}
							break;
						case 'date_time':
						case 'duration_time':
							$key = ( 'duration_time' == $booking_type ) ? 'duration_time_slot' : 'time_slot';
							if ( isset( $_POST[ $key ] ) && '' !== $_POST[ $key ] ) {

								$time_slot = $_POST[ $key ];

								if ( isset( $bkap_booking[ $key ] ) ) { // first product in cart having end date or not.

									if ( $time_slot !== $bkap_booking[ $key ] ) {
										$message = sprintf( __( 'Please select %1$s and %2$s to book %3$s.', 'woocommerce-booking' ), $bkap_booking['date'], $bkap_booking[ $key ], $product_title );
										wc_add_notice( $message, $notice_type = 'error' );
										$passed = false;
									}
								} else {
									// checks in cart for the date and time booking.
									if ( isset( WC()->cart ) ) {

										$mbkap_booking = bkap_get_first_booking_data_from_cart( $booking_type );

										if ( ! empty( $mbkap_booking ) ) {

											if ( $time_slot !== $mbkap_booking[ $key ] ) {
												$message = sprintf( __( 'Please select %1$s to %2$s to book %3$s.', 'woocommerce-booking' ), $mbkap_booking['date'], $mbkap_booking[ $key ], $product_title );
												wc_add_notice( $message, $notice_type = 'error' );
												$passed = false;
											}
										}
									}
								}
							}
							break;
					}
				}
			}

			return $passed;
		}
	}
	$woounits_validation = new Woounits_Validation();
}
