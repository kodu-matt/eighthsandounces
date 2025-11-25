<?php

/**
 * The core plugin class.
 *
 * This is used for the class that handles various checkout-related tasks.
 *
 * @since      1.0.0
 * @package    Woounits
 * @subpackage Woounits/includes
 */
class Woounits_Checkout {

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
	public function __construct() {

		add_action( 'woocommerce_checkout_update_order_meta', array( &$this, 'wut_order_item_meta' ), 10, 2 );
		add_action( 'woocommerce_checkout_create_order_line_item', array( &$this, 'wut_add_unit_item_meta_data' ), 10, 3 );

		// Hide hardcoded item meta records on the admin orders page.
		add_filter( 'woocommerce_hidden_order_itemmeta', array( &$this, 'wut_hidden_order_itemmeta' ), 10, 1 );

		add_action( 'woocommerce_resume_order', array( &$this, 'wut_woocommerce_resume_order' ), 10, 1 );
		add_action( 'woocommerce_checkout_order_processed', array( &$this, 'wut_woocommerce_new_order' ), 10, 3 );

		// Generate a unit from WooCommerce Cart & Checkout blocks.
		add_action( 'woocommerce_store_api_checkout_order_processed', array( &$this, 'wut_wc_store_api_checkout_order_processed' ), 10, 1 );
	}

	/**
	 * This function updates unit details in the database and adds unit fields on the Order Received page for WooCommerce orders (version > 2.0).
	 *
	 * @param int   $item_meta Order ID.
	 * @param mixed $cart_item Cart Item data.
	 *
     * @since 1.0.0
	 *
	 * @hook woocommerce_checkout_update_order_meta
	 */
	public static function wut_order_item_meta( $order_id, $cart_item ) {
		global $wpdb;

		$unit_is_present    = WooUnits_Common::wut_unit_is_present_check( $order_id ); // true is present
		$order_item_ids     = array();
		$sub_query          = '';
		$ticket_content     = array();
		$wc_version_compare = ( version_compare( WOOCOMMERCE_VERSION, '3.0.0' ) < 0 );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {

			$_product     = $values['data'];
			$parent_id    = $wc_version_compare ? $_product->get_parent() : WooUnits_Common::wut_get_parent_id( $values['product_id'] );
			$variation_id = ( isset( $values['variation_id'] ) ) ? $values['variation_id'] : 0;

			if ( isset( $values['wut_sunit'] ) && isset( $values['wut_sunit'][0]['wut_select_unit'] ) ) {
				$sunit = $values['wut_sunit'][0];
			} else {
				do_action( 'wut_update_order_non_unit', $values );
				continue;
			}

			$su_count = count( $values['wut_sunit'] );

			$post_id    = WooUnits_Common::wut_get_product_id( $values['product_id'] );
			$quantity   = $values['quantity'];
			$post_title = $wc_version_compare ? $_product->get_title() : $_product->get_name();

			// Fetch line item.
			if ( count( $order_item_ids ) > 0 ) {
				$order_item_ids_to_exclude = implode( ',', $order_item_ids );
				$sub_query                 = ' AND order_item_id NOT IN (' . $order_item_ids_to_exclude . ')';
			}

			$query   = 'SELECT order_item_id,order_id FROM `' . $wpdb->prefix . 'woocommerce_order_items`
								WHERE order_id = %s AND order_item_name LIKE %s' . $sub_query;
			$results = $wpdb->get_results( $wpdb->prepare( $query, $order_id, trim( $post_title, ' ' ) . '%' ) );

			$order_item_ids[] = $results[0]->order_item_id;
			$order_id         = $results[0]->order_id;
			$order_obj        = wc_get_order( $order_id );

			for ( $i = 0; $i < $su_count; $i++ ) {

				if ( isset( $values['wut_sunit'][ $i ]['wut_select_unit'] ) ) {
					$sunit = $values['wut_sunit'][ $i ];
				} else {
					break;
				}

				do_action( 'wut_update_order', $values, $results[0] );

				if ( $unit_is_present ) {
					continue; // Do not run the code if it's already been executed.
				}

				// Update the availability for that product.
				// $details = self::wut_update_lockout( $order_id, $post_id, $parent_id, $quantity, $sunit );

				$ticket         = array( apply_filters( 'wut_send_ticket', $values, $order_obj ) );
				$ticket_content = array_merge( $ticket_content, $ticket );

				if ( ! empty( $results ) ) {
					if ( isset( $sunit['wut_select_unit'] ) && '' !== $sunit['wut_select_unit'] ) {
						$name        = __( 'Selected Unit', 'woounits' );
						$name        = apply_filters( 'wut_change_checkout_selected_unit_label', $name );
						$unit_select = $sunit['wut_lbl'];

						WooUnits_Common::save_unit_information_to_order_note( $results[0]->order_item_id, $order_id, sprintf( __( $name . ': %s', 'woounits' ), sanitize_text_field( $unit_select, true ) ) );
					}
				}
			}
		}

		do_action( 'wut_send_email', $ticket_content );
	}

	/**
	 * Incorporating unit metadata into the item object.
	 *
	 * @param object $item Item Object.
	 * @param string $cart_item_key Cart Item Key.
	 * @param array  $values Cart Item Array.
     * @since 1.0.0
	 *
	 * @hook woocommerce_checkout_create_order_line_item
	 */
	function wut_add_unit_item_meta_data( $item, $cart_item_key, $values ) {

		if ( isset( $values['wut_sunit'] ) && isset( $values['wut_sunit'][0]['wut_select_unit'] ) ) {
			$su_count   = count( $values['wut_sunit'] );
			$product_id = WooUnits_Common::wut_get_product_id( $values['product_id'] );
			$item_id    = $item->get_id();

			for ( $i = 0; $i < $su_count; $i++ ) {

				if ( isset( $values['wut_sunit'][ $i ]['wut_select_unit'] ) ) {
					$unit_data = $values['wut_sunit'][ $i ];
				} else {
					break;
				}
				WooUnits_Common::wut_update_order_item_meta( $item_id, $product_id, $unit_data, true, $item );
			}
		}
	}

	/**
	 * Conceal hardcoded item meta records from appearing on the admin orders page.
	 *
	 * @param array $arr array containing meta fields that are hidden on Admin Order Page.
	 * @return array Hidden Fields Array modified
	 *
     * @since 1.0.0
	 *
	 * @hook woocommerce_hidden_order_itemmeta
	 */
	public static function wut_hidden_order_itemmeta( $arr ) {
		$wut_order_items = array(
			'_wut_select_unit',
			'_wut_uh',
			'_wut_lbl',
			'_purchase_unit',
			'_wut_selected_rate',
			'_wut_selected_from',
			'_wut_selected_to',
			'_wut_unit_status'
		);

		foreach ( $wut_order_items as $wut_order_item ) {
			$arr[] = $wut_order_item;
		}

		return apply_filters( 'wut_hidden_order_itemmeta', $arr );
	}

	/**
	 * Removing failed order from history and trashing the unit, then resuming purchase from the checkout page.
	 *
	 * @param int $order_id Order ID.
     * @since 1.0.0
	 *
	 * @hook woocommerce_resume_order
	 */
	public static function wut_woocommerce_resume_order( $order_id ) {
		global $wpdb;

		$order = wc_get_order( $order_id );

		if ( in_array( $order->get_status(), array( 'failed', 'pending' ), true ) ) {
			$order       = wc_get_order( $order_id );
			$item_values = $order->get_items();

			foreach ( $item_values as $item_id => $item_value ) {
				// $unt_id = WooUnits_Common::get_unit_id( $item_id ); // get the unit ID for each item.
				// if ( $unt_id ) {
				// 	wp_trash_post( $unt_id );
				// 	wp_delete_post( $unt_id, true );
				// }
			}
		}
	}

	/**
	 * Canceling unit data and its lockout after multiple failed payments, with no order change hook triggered.
	 *
	 * @param int   $order_id Order ID.
	 * @param array $posted_data Posted Data.
	 * @param obj   $order Order Object.
     * @since 1.0.0
	 *
	 * @hook woocommerce_checkout_order_processed
	 */
	public static function wut_woocommerce_new_order( $order_id, $posted_data, $order ) {
		$order_status = $order->get_status();
		if ( in_array( $order_status, array( 'failed' ), true ) ) {
			wut_cancel_order::wut_woocommerce_cancel_order( $order_id );
		}
	}

	/**
	 * This function is Create unit when place order from WooCommerce Cart & Checkout blocks.
	 *
	 * @param array $order Order data.
     * @since 1.0.0
	 */
	public static function wut_wc_store_api_checkout_order_processed( $order ) {
		if ( ! empty( $order ) ) {
			$order_id = $order->get_id();
			self::wut_order_item_meta( $order_id, array() );
		}
	}

	/**
	 * Adjusted product availability and remaining units upon order placement.
	 *
	 * @param int    $order_id Order ID.
	 * @param int    $post_id Product ID.
	 * @param int    $parent_id Parent Product ID in case of Grouped Products.
	 * @param int    $quantity Quantity.
	 * @param array  $unit_data unit data array.
	 * @param string $called_from Called from front order admin.
	 * @return array An array containing the list of products IDs for whom availability was updated.
	 *
     * @since 1.0.0
	 */
	public static function wut_update_lockout( $order_id, $post_id, $parent_id, $quantity, $unit_data, $called_from = '' ) {
		global $wpdb;

		$details = array();

		if ( isset( $unit_data['wut_select_unit'] ) && '' !== $unit_data['wut_select_unit'] ) {

			$unit_level    = $unit_data['wut_select_unit'];
			$wut_lbl       = $unit_data['wut_lbl'];
			$wut_uh        = $unit_data['wut_uh'];
			$purchase_unit = $unit_data['purchase_unit'];

			if ( 'admin' !== $called_from ) {
				self::wut_update_unit_order_history( $order_id, $wut_uh );
			} else {
				self::wut_update_unit_order_history( $order_id, $wut_uh, 'update' );
			}

			WooUnits_Common::wut_update_each_level_unit_history( $post_id, $wut_uh, $quantity );

			do_action( 'wut_update_lockout', $order_id, $post_id, $parent_id, $quantity, $unit_data );
		}
		return $details;
	}

	/**
	 * Update unit order history table
	 *
	 * @param int    $order_id Order ID.
	 * @param int    $unit_id Unit ID.
	 * @param string $query type of query to perform.
	 *
	 * @globals mixed $wpdb
	 *
     * @since 1.0.0
	 */
	public static function wut_update_unit_order_history( $order_id, $unit_id, $query = 'insert' ) {
		global $wpdb;

		if ( empty( $order_id ) ) {
			return;
		}

		if ( 'update' == $query ) {
			$order_query = 'UPDATE `' . $wpdb->prefix . "unit_order_history`
			SET unit_id = '" . $unit_id . "'
			WHERE order_id = '" . $order_id . "'";
			$result      = $wpdb->query( $order_query );

			if ( $result === false || $wpdb->rows_affected === 0 ) {
				$order_query = 'INSERT INTO `' . $wpdb->prefix . "unit_order_history`
				(order_id,unit_id)
				VALUES (
				'" . $order_id . "',
				'" . $unit_id . "' )";
				$wpdb->query( $order_query );
			}
		} else {
			$order_query = 'INSERT INTO `' . $wpdb->prefix . "unit_order_history`
			(order_id,unit_id)
			VALUES (
			'" . $order_id . "',
			'" . $unit_id . "' )";
			$wpdb->query( $order_query );
		}

	}

}
new Woounits_Checkout();