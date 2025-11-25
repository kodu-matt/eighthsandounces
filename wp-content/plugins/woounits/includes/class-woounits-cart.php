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
class Woounits_Cart {

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
	public function __construct() {
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'wut_add_cart_item_data' ), 25, 2 );
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'wut_get_cart_item_from_session' ), 25, 2 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'wut_get_item_data_selected_units' ), 25, 2 );
		add_action( 'woocommerce_update_cart_action_cart_updated', array( $this, 'wut_update_cart_item_meta' ) );
		add_filter( 'woocommerce_add_cart_item', array( $this, 'wut_add_cart_item_callback' ), 25, 1 );
	}

	/**
	 * This function adds product unit details upon clicking "Add to Cart."
	 *
	 * @param mixed      $cart_item_meta Cart Item Meta Object
	 * @param string|int $product_id Product ID of the product added to cart
	 * @return mixed Cart Item Meta Object with Unit data Added
	 *
	 * @since 1.0.0
	 *
	 * @hook woocommerce_add_cart_item_data
	 */
	public static function wut_add_cart_item_data( $cart_item_meta, $product_id ) {
		global $wpdb;

		$new_pro_id  = WooUnits_Common::wut_get_product_id( $product_id );
		$is_unit     = WooUnits_Common::wut_get_is_unit( $new_pro_id );
		$allow_units = apply_filters( 'wut_cart_allow_add_units', true, $cart_item_meta );

		$product      = wc_get_product( $new_pro_id );
		$product_type = $product->get_type();

		$variation_id = 0;
		if ( $product_type == 'variable' && isset( $_POST['variation_id'] ) ) {
			$variation_id = $_POST['variation_id'];
		}

		if ( $is_unit && $allow_units ) {
			$cart_arr      = array();
			$purchase_unit = (int) $_POST['quantity'];

			if ( isset( $_POST['wut_radio_unit'] ) ) {
				$_POST['wut_select_unit'] = $_POST['wut_radio_unit'];
			}
			if ( isset( $_POST['wut_select_unit'] ) ) {
				$cart_arr['wut_select_unit']   = $_POST['wut_select_unit'];
				$cart_arr['wut_uh']            = $_POST['wut_uh'];
				$cart_arr['wut_lbl']           = $_POST['wut_lbl'];
				$cart_arr['wut_selected_rate'] = $_POST['wut_selected_rate'];
				$cart_arr['wut_selected_from'] = $_POST['wut_selected_from'];
				$cart_arr['wut_selected_to']   = $_POST['wut_selected_to'];
				$cart_arr['purchase_unit']     = $purchase_unit;
			}

			if ( ( isset( $_POST['wut_price_by'] ) && ! empty( $_POST['wut_price_by'] ) ) && 
			( isset( $_POST['wut_price_per_unit'] ) && is_numeric( $_POST['wut_price_per_unit'] ) ) ) {
				$cart_arr['wut_conv_rate']      = $_POST['wut_conv_rate'];
				$cart_arr['wut_is_global']      = $_POST['wut_is_global'];
				$cart_arr['wut_price_by']       = $_POST['wut_price_by'];
				$cart_arr['wut_price_per_unit'] = $_POST['wut_price_per_unit'];
				$cart_arr['wut_unt_price']      = $_POST['wut_unt_price'];
				$cart_arr['wut_product_price']  = $_POST['wut_product_price'];
			}

			$cart_arr = (array) apply_filters( 'wut_unit_add_cart_item_data', $cart_arr, $product_id, $variation_id, $cart_item_meta );

			$cart_item_meta['wut_sunit'][] = $cart_arr;
		} else {
			$cart_item_meta = apply_filters( 'wut_cart_modify_meta', $cart_item_meta );
		}

		return $cart_item_meta;
	}

	/**
	 * This function adjusts cart session prices from the plugin.
	 *
	 * @param mixed $cart_item Cart Item Object
	 * @param mixed $values Cart Session Object
	 * @return mixed Cart Item Object
	 *
	 * @since 1.0.0
	 *
	 * @hook woocommerce_get_cart_item_from_session
	 */
	public static function wut_get_cart_item_from_session( $cart_item, $values ) {

		if ( isset( $values['wut_sunit'] ) ) {

			// Calculates product prices in the cart based on the selected WPML Multi-Currency option.
			if ( function_exists( 'icl_object_id' ) ) {

				global $woocommerce_wpml, $woocommerce;

				if ( isset( $woocommerce_wpml->settings['enable_multi_currency'] ) && $woocommerce_wpml->settings['enable_multi_currency'] == '2' ) {

					$client_currency = $woocommerce->session->get( 'client_currency' );

					foreach ( $values['wut_sunit'] as $wut_key => $wut_value ) {

						if ( $wut_value['wcml_currency'] != $client_currency ) {

							if ( $wut_value['wcml_currency'] == get_option( 'woocommerce_currency' ) ) {
								$final_price = $wut_value['price'];
							} else {
								if ( WCML_VERSION >= '3.8' ) {
									$currencies = $woocommerce_wpml->multi_currency->get_client_currency();
								} else {
									$currencies = $woocommerce_wpml->multi_currency_support->get_client_currency();
								}

								$rate        = $currencies[ $wut_value['wcml_currency'] ]['rate'];
								$final_price = $wut_value['price'] / $rate;
							}

							$raw_price                           = apply_filters( 'wcml_raw_price_amount', $final_price );
							$wut_value['price']                 = $raw_price;
							$wut_value['wcml_currency']         = $client_currency;
							$values['wut_sunit'][ $wut_key ] = $wut_value;
						}
					}
				}
			}

			$cart_item['wut_sunit'] = $values['wut_sunit'];

			$cart_item = self::wut_add_cart_item_callback( $cart_item );

			$cart_item = (array) apply_filters( 'wut_get_cart_item_from_session', $cart_item, $values );
		}
		return $cart_item;
	}

	/**
	 * This function shows unit details in the cart and checkout pages.
	 *
	 * @param mixed $other_data Cart Meta Data Object
	 * @param mixed $cart_item Session Cart Item Object
	 * @return mixed Cart Meta Data Object
	 *
	 * @since 1.0.0
	 *
	 * @hook woocommerce_get_item_data
	 */
	public static function wut_get_item_data_selected_units( $other_data, $cart_item ) {

		if ( isset( $cart_item['wut_sunit'] ) ) {

			$hide_other_data = apply_filters( 'before_wut_get_item_data', false, $other_data, $cart_item );

			if ( $hide_other_data ) {
				return $other_data;
			}

			foreach ( $cart_item['wut_sunit'] as $key => $u_value ) {

				// Selected unit Label.
				if ( isset( $u_value['wut_lbl'] ) && '' !== $u_value['wut_lbl'] ) {
					$selected_unit_lbl = __( 'Selected Unit', 'woounits' );
					$selected_unit_lbl = apply_filters( 'wut_change_cart_selected_unit_label', $selected_unit_lbl );

					$other_data[] = array(
						'name'    => $selected_unit_lbl,
						'display' => $u_value['wut_lbl'],
					);
				}

				$other_data = apply_filters( 'wut_get_item_data', $other_data, $cart_item );
			}
		}

		return $other_data;
	}

	/**
	 * This function updates unit details when the cart quantity changes.
	 *
	 * @param bool $cart_updated Cart Updated
	 *
	 * @since 1.0.0
	 *
	 * @hook woocommerce_update_cart_action_cart_updated
	 */
	public static function wut_update_cart_item_meta( $cart_updated ) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if ( isset( $cart_item['wut_sunit'] ) ) {
				foreach ( $cart_item['wut_sunit'] as $key => $value ) {
					$value['purchase_unit'] = $cart_item['quantity'];
					WC()->cart->cart_contents[$cart_item_key]['wut_sunit'][$key] = $value;
				}
			}
		}
	}

	/**
	 * This function adjusts extra product prices based on the plugin's calculated price.
	 *
	 * @param mixed $cart_item Cart Item Array
	 * @return mixed Cart Item Array with modified data
	 *
	 * @since 1.0.0
	 *
	 * @hook woocommerce_add_cart_item
	 */
	public static function wut_add_cart_item_callback( $cart_item ) {
		global $wpdb;

		if ( isset( $cart_item['wut_sunit'] ) ) {
			$unt_cost = 0;
			foreach ( $cart_item['wut_sunit'] as $unt ) {
				if ( ( isset( $unt['wut_price_by'] ) && ! empty( $unt['wut_price_by'] ) ) && 
				( isset( $unt['wut_unt_price'] ) && is_numeric( $unt['wut_unt_price'] ) ) &&
				( isset( $unt['wut_price_per_unit'] ) && is_numeric( $unt['wut_price_per_unit'] ) ) ) {
					// $unt_price   = ( '_wut_from_unit' === $unt['wut_price_by'] ) ? $unt['wut_unt_price'] : $unt['wut_price_per_unit'];
					$unt_price   = ( ! empty( $unt['wut_unt_price'] ) ) ? $unt['wut_unt_price'] : $unt['wut_price_per_unit'];
					$unt_cost   += $unt_price;
				}
			}

			$new_pro_id   = WooUnits_Common::wut_get_product_id( $cart_item['product_id'] );
			$product      = wc_get_product( $cart_item['product_id'] );
			$product_type = $product->get_type();
			$variation_id = 0;

			if ( 'variable' === $product_type ) {
				$variation_id = $cart_item['variation_id'];
			}

			if ( ( version_compare( WOOCOMMERCE_VERSION, '3.0.0' ) < 0 ) ) {
				$price      = WooUnits_Common::wut_get_price( $cart_item['product_id'], $variation_id, $product_type );
				$unt_cost = $unt_cost - $price;
				$cart_item['data']->adjust_price( $unt_cost );

				// Advanced Dynamic Pricing for WooCommerce Pro plugin not supported for lower WC versions.
			} else {
				$cart_item['data']->set_price( $unt_cost );
			}

			$cart_item = apply_filters( 'wut_update_product_price', $cart_item );
		}

		return $cart_item;
	}

}
new Woounits_Cart();