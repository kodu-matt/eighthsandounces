<?php

/**
 * The core plugin class.
 *
 * This is used for the class that handles various cart-related tasks.
 *
 * @since      1.0.0
 * @package    Woounits
 * @subpackage Woounits/includes
 */
class Woounits_Single_Product {

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
	public function __construct() {
		// add_filter( 'woocommerce_add_cart_item_data', array( $this, 'wut_add_cart_item_data' ), 25, 2 );
		
		// add_action('woocommerce_before_add_to_cart_quantity', array( $this, 'add_custom_field_before_quantity' ));

		// add_filter('woocommerce_quantity_input_args', array( $this, 'custom_quantity_float_step'), 9999, 2);
		// add_filter('woocommerce_add_to_cart_validation', array( $this, 'allow_decimal_quantity_in_cart'), 9999, 2);

		add_filter('woocommerce_add_to_cart_validation', array( $this, 'allow_decimal_quantity_validation' ), 9999, 3);
		add_filter('woocommerce_cart_rounding_precision', array( $this, 'wc_cart_rounding_precision' ) );

		// Fires when an order is placed (even before payment is complete)
		add_action('woocommerce_checkout_order_processed', array( $this, 'custom_reduce_stock_on_new_order' ), 10, 1);
		add_action('woocommerce_order_status_processing', array( $this, 'custom_reduce_stock_on_new_order' ), 10, 1);
		add_action('woocommerce_order_status_changed', array( $this, 'custom_reduce_stock_on_status_change' ), 10, 3);

        add_action( 'woocommerce_get_availability_text', array( $this, 'wut_get_stock_availability' ), 10, 2 );
		add_filter( 'woocommerce_get_price_html', array( $this, 'custom_woocommerce_price_display' ), 10, 2 );

		add_filter('woocommerce_product_get_stock_quantity', array( $this, 'custom_get_stock_quantity' ), 10, 2);
		add_filter('woocommerce_order_item_quantity', array( $this, 'custom_convert_order_item_quantity_to_kg' ), 10, 3);
		add_filter('woocommerce_update_product_stock_query', array( $this, 'custom_update_stock_query' ), 10, 4);
	}

	public static function custom_update_stock_query($query, $product_id, $amount, $operation) {

		if ( 'decrease' !== $operation ) {
			return $query;
		}

        $product_id = WooUnits_Common::wut_get_product_id( $product_id );
        $is_unit    = WooUnits_Common::wut_get_is_unit( $product_id );
        if ( $is_unit ) {
			$from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
			$to_unit = get_post_meta( $product_id, '_wut_to_unit', true );
			$conversion_rate = WooUnits_Common::wut_calc_from_to_to_units( $from_unit, $to_unit );
	
			// Convert KG to grams before updating stock
			$converted_amount = $amount * $conversion_rate;
			 $math_operator = '-';

			// Modify the stock update query
			$query = "UPDATE wp_postmeta 
					  SET meta_value = meta_value {$math_operator} {$converted_amount} 
					  WHERE post_id = {$product_id} AND meta_key = '_stock'";
		}

		return $query;
	}

	public static function custom_convert_order_item_quantity_to_kg($quantity, $order, $item) {
		$selected_rate = $item->get_meta('_wut_selected_rate', true);
		$selected_from = $item->get_meta('_wut_selected_from', true);
		$selected_to   = $item->get_meta('_wut_selected_to', true);
		$product_id    = $item->get_product_id();
        $product_id    = WooUnits_Common::wut_get_product_id( $product_id );
		// error_log( print_r( '$quantity', true ) );
		// error_log( print_r( $quantity, true ) );
		// error_log( print_r( '$selected_rate', true ) );
		// error_log( print_r( $selected_rate, true ) );

		$p_from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
		$p_to_unit   = get_post_meta( $product_id, '_wut_to_unit', true );

		if ( $selected_to !== $p_to_unit ) {
			$selected_rate = WooUnits_Common::wut_calc_from_to_to_units( $p_from_unit, $p_to_unit, 'value', $selected_rate );
		} else {
			$selected_rate = WooUnits_Common::wut_calc_from_to_to_units( $selected_from, $selected_to, 'value', $selected_rate );
		}

		$new_qty = $selected_rate ? ( $quantity * $selected_rate ) : $quantity;
		// error_log( print_r( '$quantity', true ) );
		// error_log( print_r( $quantity, true ) );
		// error_log( print_r( '$selected_rate', true ) );
		// error_log( print_r( $selected_rate, true ) );
		// error_log( print_r( '$new_qty', true ) );
		// error_log( print_r( $new_qty, true ) );
		// die;

		return $new_qty;
	}

	public static function custom_get_stock_quantity($stock, $product) {
		if (!$product || !$product->managing_stock()) {
			return $stock; // Skip if stock management is disabled
		}

		$product_id = $product->get_id();
        $product_id = WooUnits_Common::wut_get_product_id( $product_id );
        $is_unit    = WooUnits_Common::wut_get_is_unit( $product_id );
        if ( $is_unit ) {
			$from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
			$to_unit = get_post_meta( $product_id, '_wut_to_unit', true );
			$conv_rate = WooUnits_Common::wut_calc_from_to_to_units( $from_unit, $to_unit );
			$stock_in_kg = $stock * $conv_rate;

			return $stock_in_kg; // Return stock in KG
		} else {
			return $stock;
		}
	}

	public static function custom_woocommerce_price_display($price, $product) {
		$product_id = $product->get_id();
		$product_id = WooUnits_Common::wut_get_product_id($product_id);
		$is_unit    = WooUnits_Common::wut_get_is_unit($product_id);
		$pro_units  = WooUnits_Common::wut_get_product_all_units($product_id);
		$unit_pr    = [];
	
		if ( ! empty( $pro_units ) ) {
			foreach ( $pro_units as $key => $value ) {
				if ( ! empty( $value->unit_level_price ) && is_numeric( $value->unit_level_price ) ) {
					$unit_pr[] = floatval($value->unit_level_price);
				}
			}
		}
	
		if ( ! empty( $unit_pr ) ) {
			$min_price = min($unit_pr);
			$max_price = max($unit_pr);
			
			if ($min_price === $max_price) {
				$price = wc_price($min_price); // Single price
			} else {
				$price = wc_price($min_price) . ' - ' . wc_price($max_price); // Price range
			}
		}
	
		if ($is_unit) {
			$from_unit = get_post_meta($product_id, '_wut_from_unit', true);
			if ($from_unit) {
				// $custom_text = " " . $from_unit;
				$custom_text = '';
				return $price . '<span class="custom-price-text">' . esc_html__($custom_text, 'woounits') . '</span>';
			}
		}
	
		return $price;
	}
	
	public static function custom_woocommerce_price_display1($price, $product) {
        $product_id = $product->get_id();
        $product_id = WooUnits_Common::wut_get_product_id( $product_id );
        $is_unit    = WooUnits_Common::wut_get_is_unit( $product_id );
		$pro_units  = WooUnits_Common::wut_get_product_all_units( $product_id );
		$unit_pr    = array();
		if ( ! empty( $pro_units ) ) {
			foreach ( $pro_units as $key => $value ) {
				if ( ! empty( $value->unit_level_price ) ) {
					$unit_pr[] = $value->unit_level_price;
				}
			}
		}

		$min_price = '';
		$max_price = '';
		if ( ! empty( $unit_pr ) ) {
            $min_price = min($unit_pr);
            $max_price = max($unit_pr);
		}
		if ( ! empty( $min_price ) && ! empty( $max_price ) ) {
            $price = wc_price( $min_price ) . ' - ' . wc_price( $max_price );
        }

        if ( $is_unit ) {
            $from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
            if ($from_unit) {
				$custom_text = " 1/8 " . $from_unit;
				return $price . '<span class="custom-price-text">' . esc_html__( $custom_text, 'woounits' ) . '</span>';
            }
        }
		return $price;
	}

    /**
     * Update stock availability message.
     *
     * @since 1.0.0
     */
    public function wut_get_stock_availability($availability, $product) {
        $product_id = $product->get_id();
        $product_id = WooUnits_Common::wut_get_product_id( $product_id );
        $is_unit    = WooUnits_Common::wut_get_is_unit( $product_id );

        if ( $is_unit ) {
			$from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
			$to_unit = get_post_meta( $product_id, '_wut_to_unit', true );
			$conv_rate = WooUnits_Common::wut_calc_from_to_to_units( $from_unit, $to_unit );
            // $from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
            if ($product->is_in_stock()) {
                $stock_quantity = $product->get_stock_quantity();
				$new_stock_qty = floatval( $stock_quantity / $conv_rate );
                $availability = $new_stock_qty ? __( "$new_stock_qty $from_unit in stock!", 'woounits' ) : __( 'In stock', 'woounits' ); 
            }
        }
        return $availability;
    }

	function custom_reduce_stock_on_status_change($order_id, $old_status, $new_status) {
		if (in_array($new_status, array('processing', 'completed'))) {
			self::custom_reduce_stock_on_new_order($order_id);
		}
	}

	public static function custom_reduce_stock_on_new_order($order_id) {
		if (!$order_id) return;
	
		$order = wc_get_order($order_id);
		if (!$order) return;
	
		$unit_labels = WooUnits_Common::wut_unit_fields_label();
		
		// Make sure you have your unit details loaded (e.g., $unit_details should be defined)
		// For example, you might be doing something like:
		// $unit_details = WooUnits_Common::wut_get_some_unit_details();
		// if you haven't defined it yet, the below loop won't run.
		if ( ! empty( $unit_labels ) ) { 
			foreach ($order->get_items() as $item_key => $item) {
				$all_unit_details = WooUnits_Common::wut_get_unit_item_meta( $item_key, $item, $unit_labels );
	
				$product_id = $item->get_product_id();
				$product_id = WooUnits_Common::wut_get_product_id( $product_id );
				$qty = (float) $item->get_quantity(); // Get ordered quantity
				$quantity = $qty;

				$from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
				$to_unit = get_post_meta( $product_id, '_wut_to_unit', true );
	
				// Use your selected rate from unit details for the calculation
				$weight_per_unit = (float) $item->get_meta('_wut_selected_rate', true);
				$selected_from = $item->get_meta('_wut_selected_from', true);
				$selected_to   = $item->get_meta('_wut_selected_to', true);

				$p_from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
				$p_to_unit   = get_post_meta( $product_id, '_wut_to_unit', true );

				if ( $selected_to !== $p_to_unit ) {
					$qty = WooUnits_Common::wut_calc_from_to_to_units( $p_from_unit, $p_to_unit, 'value', $qty );
				} else {
					$qty = WooUnits_Common::wut_calc_from_to_to_units( $selected_from, $selected_to, 'value', $qty );
				}

				if ($product_id && $weight_per_unit > 0) {
					$product = wc_get_product($product_id);
					$current_stock = (float) $product->get_stock_quantity();
					if ( $selected_to !== $p_to_unit ) {
						$current_stock = WooUnits_Common::wut_calc_from_to_to_units( $p_from_unit, $p_to_unit, 'value', $current_stock );
					} else {
						$current_stock = WooUnits_Common::wut_calc_from_to_to_units( $selected_from, $selected_to, 'value', $current_stock );
					}
	
					// Convert purchased quantity to weight (or value) based on the conversion rate.
					$deduct_weight = ( $selected_to !== $p_to_unit ) ? ($weight_per_unit * $quantity) : ($weight_per_unit * $qty);

					// error_log( print_r( '$quantity', true ) );
					// error_log( print_r( $quantity, true ) );
					// error_log( print_r( '$deduct_weight', true ) );
					// error_log( print_r( $deduct_weight, true ) );
					// error_log( print_r( '$qty', true ) );
					// error_log( print_r( $qty, true ) );
					// Reduce stock and update total sales
					$new_stock = max(0, $current_stock - $deduct_weight);

					$current_total_sales = (float) get_post_meta($product_id, 'total_sales', true);
					$new_total_sales = ( $current_total_sales - $quantity ) + $deduct_weight;
					// error_log( print_r( '$new_stock', true ) );
					// error_log( print_r( $new_stock, true ) );
					// error_log( print_r( '$current_total_sales', true ) );
					// error_log( print_r( $current_total_sales, true ) );
					// error_log( print_r( '$new_total_sales', true ) );
					// error_log( print_r( $new_total_sales, true ) );
					// die;

					update_post_meta($product_id, '_stock', $new_stock);
					update_post_meta($product_id, 'total_sales', $new_total_sales);
	
					// Set product as out of stock if necessary
					if ($new_stock == 0) {
						$product->set_stock_status('outofstock');
					}
					$product->save();
				}
			}
		}
	}

	// Keeps 2 decimal places
	public static function wc_cart_rounding_precision($passed, $product_id, $quantity) {
		return 2;
	}

	// Ensure decimal quantity is properly validated
	public static function allow_decimal_quantity_validation($passed, $product_id, $quantity) {
		if ($quantity <= 0) {
			wc_add_notice(__('Please enter a valid quantity.', 'woounits'), 'error');
			return false;
		}
		return $passed;
	}

	// Allow float values in the WooCommerce quantity input field
	public static function custom_quantity_float_step($args, $product) {
		$args['inputmode'] = 'decimal';  // Allow decimal input
		$args['step'] = '0.1';  // Set step value (adjust as needed)
		$args['min'] = '0.1';   // Set minimum quantity
		$args['min_value'] = '0.1';   // Set minimum quantity
		// $args['readonly'] = true;   // Set minimum quantity
		return $args;
	}

	// Ensure decimal quantities are allowed in the cart
	// public static function allow_decimal_quantity_in_cart($quantity, $cart_item_key) {
	//     return floatval($quantity); // Convert quantity to float
	// }

}
new Woounits_Single_Product();