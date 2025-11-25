<?php

/**
 * The core plugin class.
 *
 * This is used for the class that handles unit on order status tasks.
 *
 * @since      1.0.0
 * @package    Woounits
 * @subpackage Woounits/includes
 */

class wut_cancel_order {

	/**
	 * Default constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'wc_order_is_editable', array( &$this, 'wut_wc_order_is_editable' ), 10, 2 );
		add_action( 'woocommerce_order_status_changed', array( $this, 'wut_update_product_stock_based_on_status' ), 10, 3 );
		add_action( 'woocommerce_new_order', array( $this, 'update_stock_and_sales_on_new_order' ), 10, 1 );
	}

	public static function update_stock_and_sales_on_new_order($order_id) {
		$order = wc_get_order($order_id);
		if (!$order) return;

		$unit_labels = WooUnits_Common::wut_unit_fields_label();
		if ( ! empty( $unit_details ) ) {

			// Loop through each product in the order
			foreach ($order->get_items() as $item_key => $item) {
				$all_unit_details = self::wut_get_unit_item_meta( $item_key, $item, $unit_labels );

				$product_id = $item->get_product_id();
				$qty = $item->get_quantity();

				if ($product_id) {
					$product = wc_get_product($product_id);
					$stock = $product->get_stock_quantity();
					$total_sales = get_post_meta($product_id, 'total_sales', true);
					$total_sales = (int) $total_sales;
		
					if ($product->managing_stock()) {
						$new_stock = ($stock !== null) ? max(0, $stock - $qty) : null; // Prevent negative stock
						update_post_meta($product_id, '_stock', $new_stock);
						update_post_meta($product_id, 'total_sales', $total_sales + $qty);
		
						if ($new_stock === 0) {
							$product->set_stock_status('outofstock');
						}
						$product->save();
					}
				}
			}
		}
	}

	public static function wut_update_product_stock_based_on_status($order_id, $old_status, $new_status) {
		if (!$order_id) return;
	
		$order = wc_get_order($order_id);
		if (!$order) return;
	
		// Define statuses for stock reduction and restocking
		$reduce_stock_statuses = ['processing', 'completed']; // Stock is reduced when order reaches these statuses
		$restock_statuses = ['cancelled', 'refunded', 'failed']; // Stock is restored when order is cancelled/refunded

		$unit_labels = WooUnits_Common::wut_unit_fields_label();
		if ( ! empty( $unit_details ) ) {

			// Loop through each product in the order
			foreach ($order->get_items() as $item_key => $item) {
				$all_unit_details = self::wut_get_unit_item_meta( $item_key, $item, $unit_labels );
				$product_id = $item->get_product_id();
				$qty = $item->get_quantity();

				if ($product_id) {
					$product = wc_get_product($product_id);
					$stock = $product->get_stock_quantity();
		
					if ($product->managing_stock()) {
						if (in_array($new_status, $reduce_stock_statuses) && !in_array($old_status, $reduce_stock_statuses)) {
							// Reduce stock only if transitioning to 'processing' or 'completed' from another status
							$new_stock = ($stock !== null) ? max(0, $stock - $qty) : null; // Prevent negative stock
							update_post_meta($product_id, '_stock', $new_stock);
		
							if ($new_stock === 0) {
								$product->set_stock_status('outofstock');
							}
							$product->save();
		
						} elseif (in_array($new_status, $restock_statuses) && in_array($old_status, $reduce_stock_statuses)) {
							// Restock only if transitioning from 'processing' or 'completed' to 'cancelled' or 'refunded'
							$new_stock = ($stock !== null) ? $stock + $qty : null;
							update_post_meta($product_id, '_stock', $new_stock);
		
							if ($new_stock > 0) {
								$product->set_stock_status('instock');
							}
							$product->save();
						}
					}
				}
			}
		}
	}

	/**
	 * This function runs when deleting line items via the Refund Button in the Order table.
	 *
	 * @hook woocommerce_before_delete_order_item
	 *
	 * @param  bool     $is_editable Default true.
	 * @param  WC_Order $order Order Object.
	 * @return $is_editable.
	 *
	 * @since 1.0.0
	 */
	public static function wut_wc_order_is_editable( $is_editable, $order ) {
		if ( ! $order ) {
			return $is_editable;
		}

		foreach ( $order->get_items() as $item_id => $item ) {
			$product_id = $item->get_product_id();
			$post_id    = WooUnits_Common::wut_get_product_id( $product_id );
			$is_unit    = WooUnits_Common::wut_get_is_unit( $post_id );

			if ( $is_unit ) {
				return false;
			}
		}

		return $is_editable;
	}

}
