<?php

/**
 * Provide a public-facing view for the plugin common functions
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Woounits
 * @subpackage Woounits/includes/common
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class for adding the Common.
 */
class WooUnits_Common {

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
    }

    /**
     * Get product ids by category ids.
	 *
     * @param array  $category_ids Catgory IDs array
     * @param string $limit set product limit
	 * @since 1.0.0
     */
    public static function wut_get_product_ids_by_category_ids( $category_ids, $limit = -1 ) {
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $limit,
            'fields'         => 'ids',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $category_ids,
                    'operator' => 'IN',
                ),
            ),
        );

        $query = new WP_Query( $args );
        return ( $query->have_posts() ) ? $query->posts : array();
    }

    /**
     * Calculate from to to unit Rate|Value|Formila.
     * 
     * @param  string  $from from unit
     * @param  string  $to to unit
     * @param  string  $return Default rate
     * @param  string  $amount Default 1
     * @return mixed   $return (rate|value|formula)
	 * @since 1.0.0
     */
	public static function wut_calc_from_to_to_units($from, $to, $return = 'rate', $amount = 1) {
		$conversion_table = self::wut_get_all_conv_units();
		$conversion_key = strtolower($from . '_to_' . $to);
		$rev_conv_key = strtolower($to . '_to_' . $from);

		// Check for direct or reverse conversion
		if (isset($conversion_table[$conversion_key])) {
			$rate = apply_filters('woounits_rate_' . $conversion_key, $conversion_table[$conversion_key][1] ?? 1);
			$value = apply_filters('woounits_calc_formula', $amount / $rate, $amount, $rate);
		} elseif (isset($conversion_table[$rev_conv_key])) {
			$rate = apply_filters('woounits_rate_' . $rev_conv_key, $conversion_table[$rev_conv_key][1] ?? 1);
			$value = apply_filters('woounits_calc_formula', $amount * $rate, $amount, $rate);
		} else {
			$rate = 1;
			$value = apply_filters('woounits_calc_formula', $amount * $rate, $amount, $rate);
		}

		// Format conversion formula
		$formula = sprintf('%s %s = %s %s', $amount, ucfirst($from), $value, ucfirst($to));
		$formula = apply_filters('woounits_calc_display_formula', $formula, $amount, $from, $value, $to);

		// Return based on requested format
		switch ($return) {
			case 'formula':
				return $formula;
			case 'value':
				return $value;
			default:
				return $rate;
		}		
	}

	public static function wut_calc_from_to_to_units1111($from, $to, $return = 'rate', $amount = 1) {
		$conversion_table = self::wut_get_all_conv_units();

		$conversion_key = strtolower($from . '_to_' . $to);
		$rev_conv_key = strtolower($to . '_to_' . $from);
		$rate = 1;
		if ( array_key_exists($conversion_key, $conversion_table) ) {
			$rate = ( isset( $conversion_table[$conversion_key][1] ) ) ? apply_filters( 'woounits_rate_' . $conversion_key, $conversion_table[$conversion_key][1] ) : $rate;

			if ( 0 === $rate ) {
				$rate = 1;
			}

			$value = apply_filters( 'woounits_calc_formula', ( $amount / $rate ), $amount, $rate );

			$formula = $amount . ' ' . ucfirst($from) . ' = ' . $value . ' ' . ucfirst($to);
			$formula = apply_filters( 'woounits_calc_display_formula', $formula, $amount, $from, $value, $to );

			switch ( $return ) {
				case 'formula':
					return $formula;
					break;
				case 'value':
					return $value;
					break;
				default:
					return $rate;
			}
		} elseif ( array_key_exists($rev_conv_key, $conversion_table) ) {
			$rate = ( isset( $conversion_table[$rev_conv_key][1] ) ) ? apply_filters( 'woounits_rate_' . $rev_conv_key, $conversion_table[$rev_conv_key][1] ) : $rate;

			if ( 0 === $rate ) {
				$rate = 1;
			}

			$value = apply_filters( 'woounits_calc_formula', ( $amount * $rate ), $amount, $rate );

			$formula = $amount . ' ' . ucfirst($from) . ' = ' . $value . ' ' . ucfirst($to);
			$formula = apply_filters( 'woounits_calc_display_formula', $formula, $amount, $from, $value, $to );
	
			switch ( $return ) {
				case 'formula':
					return $formula;
					break;
				case 'value':
					return $value;
					break;
				default:
					return $rate;
			}
		} else {
			if ( 0 === $rate ) {
				$rate = 1;
			}

			$value = apply_filters( 'woounits_calc_formula', ( $amount * $rate ), $amount, $rate );

			$formula = $amount . ' ' . ucfirst($from) . ' = ' . $value . ' ' . ucfirst($to);
			$formula = apply_filters( 'woounits_calc_display_formula', $formula, $amount, $from, $value, $to );

			switch ( $return ) {
				case 'formula':
					return $formula;
					break;
				case 'value':
					return $value;
					break;
				default:
					return $rate;
			}
		}
	}

	public static function wut_get_all_conv_units() {
		$conversion_units = array(
			// kg & g, lbs & oz done.
			'ft_to_in' => array( __( 'Foot to Inch', WOOUNITS_DOMAIN ), 12 ),
			'ft_to_ft' => array( __( 'Foot to Foot', WOOUNITS_DOMAIN ), 1 ),
			'ft_to_yd' => array( __( 'Foot to Yard', WOOUNITS_DOMAIN ), 3 ),
			'ft_to_mi' => array( __( 'Foot to Mile', WOOUNITS_DOMAIN ), 5280 ),
			'ft_to_mm' => array( __( 'Foot to Millimeter', WOOUNITS_DOMAIN ), 304.8 ),
			'ft_to_cm' => array( __( 'Foot to Centimeter', WOOUNITS_DOMAIN ), 30.48 ),
			// 'ft_to_m' => array( __( 'Foot to Meter', WOOUNITS_DOMAIN ), 0.3048 ),
			'ft_to_km' => array( __( 'Foot to Kilometer', WOOUNITS_DOMAIN ), 0.0003048 ),
			'l_to_ml' => array( __( 'Liter to Milliliter', WOOUNITS_DOMAIN ), 1000 ),
			'l_to_l' => array( __( 'Liter to Liter', WOOUNITS_DOMAIN ), 1 ),

			'm_to_mm' => array( __( 'Meter to Millimeter', WOOUNITS_DOMAIN ), 1000 ),
			'mm_to_m' => array( __( 'Millimeter to Meter', WOOUNITS_DOMAIN ), 0.001 ),
			'mm_to_mm' => array( __( 'Millimeter to Millimeter', WOOUNITS_DOMAIN ), 1 ),
			'm_to_cm' => array( __( 'Meter to Centimeter', WOOUNITS_DOMAIN ), 100 ),
			'cm_to_m' => array( __( 'Centimeter to Meter', WOOUNITS_DOMAIN ), 0.01 ),
			'cm_to_cm' => array( __( 'Centimeter to Centimeter', WOOUNITS_DOMAIN ), 1 ),
			'm_to_m' => array( __( 'Meter to Meter', WOOUNITS_DOMAIN ), 1 ),
			'm_to_km' => array( __( 'Meter to Kilometer', WOOUNITS_DOMAIN ), 0.001 ),
			'km_to_m' => array( __( 'Kilometer to Meter', WOOUNITS_DOMAIN ), 1000 ),
			'm_to_in' => array( __( 'Meter to Inch', WOOUNITS_DOMAIN ), 39.3701 ),
			'in_to_m' => array( __( 'Inch to Meter', WOOUNITS_DOMAIN ), 0.0254 ),
			'in_to_in' => array( __( 'Inch to Inch', WOOUNITS_DOMAIN ), 1 ),
			'm_to_ft' => array( __( 'Meter to Foot', WOOUNITS_DOMAIN ), 3.28084 ),
			'ft_to_m' => array( __( 'Foot to Meter', WOOUNITS_DOMAIN ), 0.3048 ),
			'ft_to_ft' => array( __( 'Foot to Foot', WOOUNITS_DOMAIN ), 1 ),
			'm_to_yd' => array( __( 'Meter to Yard', WOOUNITS_DOMAIN ), 1.09361 ),
			'yd_to_m' => array( __( 'Yard to Meter', WOOUNITS_DOMAIN ), 0.9144 ),
			'yd_to_yd' => array( __( 'Yard to Yard', WOOUNITS_DOMAIN ), 1 ),
			'm_to_mi' => array( __( 'Meter to Mile', WOOUNITS_DOMAIN ), 0.000621371 ),
			'mi_to_m' => array( __( 'Mile to Meter', WOOUNITS_DOMAIN ), 1609.34 ),
			'mi_to_mi' => array( __( 'Mile to Mile', WOOUNITS_DOMAIN ), 1 ),

			'oz_to_lbs' => array( __( 'Ounce to Pound', WOOUNITS_DOMAIN ), 0.0625 ),
			'lbs_to_oz' => array( __( 'Pound to Ounce', WOOUNITS_DOMAIN ), 16 ),
			'lbs_to_lbs' => array( __( 'Pound to Pound', WOOUNITS_DOMAIN ), 1 ),
			'lbs_to_tn' => array( __( 'Pound to Ton', WOOUNITS_DOMAIN ), 0.0005 ),
			'tn_to_lbs' => array( __( 'Ton to Pound', WOOUNITS_DOMAIN ), 2000 ),
			'lbs_to_g' => array( __( 'Pound to Gram', WOOUNITS_DOMAIN ), 453.592 ),
			'g_to_lbs' => array( __( 'Gram to Pound', WOOUNITS_DOMAIN ), 0.00220462 ),
			'lbs_to_kg' => array( __( 'Pound to Kilogram', WOOUNITS_DOMAIN ), 0.453592 ),
			'kg_to_lbs' => array( __( 'Kilogram to Pound', WOOUNITS_DOMAIN ), 2.20462 ),
			'lbs_to_t' => array( __( 'Pound to Metric Ton', WOOUNITS_DOMAIN ), 0.000453592 ),
			't_to_lbs' => array( __( 'Metric Ton to Pound', WOOUNITS_DOMAIN ), 2204.62 ),

			'g_to_g' => array( __( 'Gram to Gram', WOOUNITS_DOMAIN ), 1 ),
			'g_to_kg' => array( __( 'Gram to Kilogram', WOOUNITS_DOMAIN ), 0.001 ),
			'kg_to_g' => array( __( 'Kilogram to Gram', WOOUNITS_DOMAIN ), 1000 ),
			'kg_to_kg' => array( __( 'Kilogram to Kilogram', WOOUNITS_DOMAIN ), 1 ),
			'kg_to_t' => array( __( 'Kilogram to Metric Ton', WOOUNITS_DOMAIN ), 0.001 ),
			't_to_kg' => array( __( 'Metric Ton to Kilogram', WOOUNITS_DOMAIN ), 1000 ),
			't_to_t' => array( __( 'Metric Ton to Metric Ton', WOOUNITS_DOMAIN ), 1 ),
			'kg_to_oz' => array( __( 'Kilogram to Ounce', WOOUNITS_DOMAIN ), 35.274 ),
			'oz_to_kg' => array( __( 'Ounce to Kilogram', WOOUNITS_DOMAIN ), 0.0283495 ),
			'oz_to_oz' => array( __( 'Ounce to Ounce', WOOUNITS_DOMAIN ), 1 ),
			'kg_to_tn' => array( __( 'Kilogram to Ton', WOOUNITS_DOMAIN ), 0.00110231 ),
			'tn_to_kg' => array( __( 'Ton to Kilogram', WOOUNITS_DOMAIN ), 907.185 ),
			'tn_to_tn' => array( __( 'Ton to Ton', WOOUNITS_DOMAIN ), 1 ),
		);
		return apply_filters( 'woounits_conversion_units', $conversion_units );
	}

	public static function wut_get_all_units() {

		$conversion_units1 = array(
			'ft' => array( __( 'Foot (ft)', WOOUNITS_DOMAIN ), 1 ),
			'in' => array( __( 'Inch (in)', WOOUNITS_DOMAIN ), 12 ),
			'yd' => array( __( 'Yard (yd)', WOOUNITS_DOMAIN ), 3 ),
			'mi' => array( __( 'Mile (mi)', WOOUNITS_DOMAIN ), 5280 ),
			'mm' => array( __( 'Millimeter (mm)', WOOUNITS_DOMAIN ), 304.8 ),
			'cm' => array( __( 'Centimeter (cm)', WOOUNITS_DOMAIN ), 30.48 ),
			'm' => array( __( 'Meter (m)', WOOUNITS_DOMAIN ), 0.3048 ),
			'km' => array( __( 'Kilometer (km)', WOOUNITS_DOMAIN ), 0.0003048 ),
			'ml' => array( __( 'Milliliter (ml)', WOOUNITS_DOMAIN ), 1000 ),
			'l' => array( __( 'Liter (l)', WOOUNITS_DOMAIN ), 1 ),
			'lbs' => array( __( 'Pound (lbs)', WOOUNITS_DOMAIN ), 1 ),
			'oz' => array( __( 'Ounce (oz)', WOOUNITS_DOMAIN ), 16 ),
			'tn' => array( __( 'Ton (tn)', WOOUNITS_DOMAIN ), 2000 ),
			'g' => array( __( 'Gram (g)', WOOUNITS_DOMAIN ), 453.592 ),
			'kg' => array( __( 'Kilogram (kg)', WOOUNITS_DOMAIN ), 0.453592 ),
			't' => array( __( 'Metric Ton (t)', WOOUNITS_DOMAIN ), 0.000453592 ),
		);

		$conversion_units = array(
			// 'other' => array(
			// 	'label'   => __( 'Other', WOOUNITS_DOMAIN ),
			// 	'options' => array(
			// 		'item' => array( __( 'Item', WOOUNITS_DOMAIN ), 1 )
			// 	)
			// ),
			'weight' => array( 
				'label'   => __( 'Weight', WOOUNITS_DOMAIN ),
				'options' => array( 
					'g'   => array( __( 'Gram (g)', WOOUNITS_DOMAIN ), 453.592 ),
					'kg'  => array( __( 'Kilogram (kg)', WOOUNITS_DOMAIN ), 0.453592 ),
					't'   => array( __( 'Metric Ton (t)', WOOUNITS_DOMAIN ), 0.000453592 ),
					'oz'  => array( __( 'Ounce (oz)', WOOUNITS_DOMAIN ), 16 ),
					'lbs' => array( __( 'Pound (lbs)', WOOUNITS_DOMAIN ), 1 ),
					'tn'  => array( __( 'Ton (tn)', WOOUNITS_DOMAIN ), 2000 )
				)
			),
			'dimension' => array(
				'label'   => __( 'Dimension', WOOUNITS_DOMAIN ),
				'options' => array(
					'mm' => array( __( 'Millimeter (mm)', WOOUNITS_DOMAIN ), 304.8 ),
					'cm' => array( __( 'Centimeter (cm)', WOOUNITS_DOMAIN ), 30.48 ),
					'm'  => array( __( 'Meter (m)', WOOUNITS_DOMAIN ), 0.3048 ),
					'km' => array( __( 'Kilometer (km)', WOOUNITS_DOMAIN ), 0.0003048 ),
					'in' => array( __( 'Inch (in)', WOOUNITS_DOMAIN ), 12 ),
					'ft' => array( __( 'Foot (ft)', WOOUNITS_DOMAIN ), 1 ),
					'yd' => array( __( 'Yard (yd)', WOOUNITS_DOMAIN ), 3 ),
					'mi' => array( __( 'Mile (mi)', WOOUNITS_DOMAIN ), 5280 )
				)
			),
			// 'area' => array(
			// 	'label'   => __( 'Area', WOOUNITS_DOMAIN ),
			// 	'options' => array(
			// 		'sq mm'   => array( __( 'Square Millimeter', WOOUNITS_DOMAIN ), 1 ),
			// 		'sq cm'   => array( __( 'Square Centimeter', WOOUNITS_DOMAIN ), 1 ),
			// 		'sq m'    => array( __( 'Square Meter', WOOUNITS_DOMAIN ), 1 ),
			// 		'ha'      => array( __( 'Hectare', WOOUNITS_DOMAIN ), 1 ),
			// 		'sq km'   => array( __( 'Square Kilometer', WOOUNITS_DOMAIN ), 1 ),
			// 		'sq. in.' => array( __( 'Square Inch', WOOUNITS_DOMAIN ), 1 ),
			// 		'sq. ft.' => array( __( 'Square Foot', WOOUNITS_DOMAIN ), 1 ),
			// 		'sq. yd.' => array( __( 'Square Yard', WOOUNITS_DOMAIN ), 1 ),
			// 		'acs'     => array( __( 'Acre', WOOUNITS_DOMAIN ), 1 ),
			// 		'sq. mi.' => array( __( 'Square Mile', WOOUNITS_DOMAIN ), 1 )
			// 	)
			// ),
			'volume' => array(
				'label'   => __( 'Volume', WOOUNITS_DOMAIN ),
				'options' => array(
					'ml'      => array( __( 'Milliliter (ml)', WOOUNITS_DOMAIN ), 1000 ),
					'l'       => array( __( 'Liter (l)', WOOUNITS_DOMAIN ), 1 ),
					// 'cup'     => array( __( 'Cup', WOOUNITS_DOMAIN ), 1 ),
					// 'pt'      => array( __( 'Pint', WOOUNITS_DOMAIN ), 1 ),
					// 'qt'      => array( __( 'Quart', WOOUNITS_DOMAIN ), 1 ),
					// 'gal'     => array( __( 'Gallon', WOOUNITS_DOMAIN ), 1 ),
					// 'fl. oz.' => array( __( 'Fluid Ounce', WOOUNITS_DOMAIN ), 1 )
				)
			),
			// 'volume-dimension' => array(
			// 	'label'   => __( 'Volume (LxWxH)', WOOUNITS_DOMAIN ),
			// 	'options' => array(
			// 		'cu cm'   => array( __( 'Cubic Centimeter', WOOUNITS_DOMAIN ), 1 ),
			// 		'cu m'    => array( __( 'Cubic Meter', WOOUNITS_DOMAIN ), 1 ),
			// 		'cu. in.' => array( __( 'Cubic Inch', WOOUNITS_DOMAIN ), 1 ),
			// 		'cu. ft.' => array( __( 'Cubic Foot', WOOUNITS_DOMAIN ), 1 ),
			// 		'cu. yd.' => array( __( 'Cubic Yard', WOOUNITS_DOMAIN ), 1 )
			// 	)
			// ),
			// 'custom' => array(
			// 	'label'   => __( 'Custom', WOOUNITS_DOMAIN ),
			// 	'options' => array()
			// )
		);
		

		return apply_filters( 'woounits_all_conversion_units', $conversion_units );
	}

	/**
	 * Send the Base language product ID
	 *
	 * @param integer $product_id
	 * @return integer $base_product_id
	 * @since 1.0.0
	 */
	public static function wut_get_product_id( $product_id ) {
		$base_product_id = $product_id;
		// If WPML is enabled.
		if ( function_exists( 'icl_object_id' ) ) {
			global $sitepress;
			global $polylang;

			if ( isset( $polylang ) ) {
				$default_lang = pll_current_language();
			} else {
				$default_lang = $sitepress->get_default_language();
			}

			$base_product_id = icl_object_id( $product_id, 'product', false, $default_lang );
			// The base product ID is blanks when the product is being created.
			if ( ! isset( $base_product_id ) || ( isset( $base_product_id ) && $base_product_id == '' ) ) {
				$base_product_id = $product_id;
			}
		}
		return $base_product_id;
	}

	/**
	 * Checks whether a product is units.
	 *
	 * @param  int $product_id
	 * @return bool $is_unit
	 * @since 1.0.0
	 */
    public static function wut_get_is_unit( $product_id ) {
		$is_unit = false;

		if ( ! empty( $product_id ) ) {
			$product       = wc_get_product( $product_id );
			$product_type  = $product->get_type();
			$product_types = array( 'simple', 'variable' );
			$product_types = apply_filters( 'wut_show_select_unit_on_single_product_page_by_product_type', $product_types, $product_id );
			$is_enable     = get_post_meta( $product_id, '_wut_enable_unit', true );

			if ( $is_enable && in_array( $product_type, $product_types, true ) ) {
				$is_unit = true;
			}
		}

		return $is_unit;
	}

    /**
     * This function get all units list.
     *
	 * @param integer $post_id Product ID
     *
     * @since 1.0.0
     */
    public static function wut_get_product_all_units( $post_id = 0 ) {
        global $wpdb;
        $units_list = array();
        $table_name = $wpdb->prefix . 'unit_history';
        $from = get_post_meta( $post_id, '_wut_from_unit', true );
        $to = get_post_meta( $post_id, '_wut_to_unit', true );

        $base_units = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE post_id = %d AND from_unit = %s AND to_unit = %s",
                $post_id,
                $from,
                $from
            )
        );

        $all_units = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE post_id = %d AND from_unit = %s AND to_unit = %s",
                $post_id,
                $from,
                $to
            )
        );

        if ( isset( $all_units ) && count( $all_units ) > 0 ) {
            if ( isset( $base_units ) && count( $base_units ) > 0 ) {
                $units_list = array_merge($base_units, $all_units);
            } else {
                $units_list = $all_units;
            }
        }

        $unique_units = array_values(array_reduce($units_list, function ($carry, $item) {
            if (!isset($carry[$item->id])) {
                $carry[$item->id] = $item;
            }
            return $carry;
        }, []));

        return apply_filters( 'woounits_get_unit_levels', $unique_units, $from, $to );
    }

	/**
	 * Adds item meta for unit products when an order is placed
	 *
	 * @param integer $item_id
	 * @param integer $product_id
	 * @param array   $unit_data
	 * @since 1.0.0
	 */
    public static function wut_update_order_item_meta( $item_id, $product_id, $unit_data, $add_item_meta = false, $item = array() ) {
		global $wpdb;

		// Get Order ID from $item_id.
		$order_id = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_id = %d",
				$item_id
			)
		);
		
		$status = 'confirmed'; // Get order status.

		$status = apply_filters( 'wut_unit_status_on_create_order', $status );
		if ( $add_item_meta ) {
			$item->add_meta_data( '_wut_unit_status', $status );
		} else {
			wc_add_order_item_meta( $item_id, '_wut_unit_status', $status );
		}

		/**
		 * Storing Selected Unit Information.
		 */
		if ( isset( $unit_data['wut_select_unit'] ) && '' !== $unit_data['wut_select_unit'] ) {

			$name        = __( 'Selected Unit', WOOUNITS_DOMAIN );
			$name        = apply_filters( 'wut_change_checkout_selected_unit_label', $name );
			$unit_select = $unit_data['wut_lbl'];

			if ( $add_item_meta ) {
				$item->add_meta_data( $name, sanitize_text_field( $unit_select, true ) );
			} else {
				wc_add_order_item_meta( $item_id, $name, sanitize_text_field( $unit_select, true ) );
			}

			if ( ! $add_item_meta ) {
				// Save Selected Unit Information to Order Note.
				self::save_unit_information_to_order_note( $item_id, $order_id, sprintf( __( $name . ': %s', WOOUNITS_DOMAIN ), sanitize_text_field( $unit_select, true ) ) );
			}

			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_select_unit', sanitize_text_field( $unit_data['wut_select_unit'], true ) );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_select_unit', sanitize_text_field( $unit_data['wut_select_unit'], true ) );
			}

		}

		if ( isset( $unit_data['wut_uh'] ) && '' !== $unit_data['wut_uh'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_uh', $unit_data['wut_uh'] );
				// $item->add_meta_data( 'wut_uh', $unit_data['wut_uh'] );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_uh', $unit_data['wut_uh'] );
				// wc_add_order_item_meta( $item_id, 'wut_uh', $unit_data['wut_uh'] );
			}
		}

		if ( isset( $unit_data['old_qty'] ) && '' !== $unit_data['old_qty'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_old_qty', $unit_data['old_qty'] );
				$item->add_meta_data( 'old_qty', $unit_data['old_qty'] );
			} else {
				wc_add_order_item_meta( $item_id, '_old_qty', $unit_data['old_qty'] );
				wc_add_order_item_meta( $item_id, 'old_qty', $unit_data['old_qty'] );
			}
		}

		if ( isset( $unit_data['wut_lbl'] ) && '' !== $unit_data['wut_lbl'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_lbl', $unit_data['wut_lbl'] );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_lbl', $unit_data['wut_lbl'] );
			}
		}

		if ( isset( $unit_data['purchase_unit'] ) && '' !== $unit_data['purchase_unit'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_purchase_unit', $unit_data['purchase_unit'] );
				// $item->add_meta_data( 'purchase_unit', $unit_data['purchase_unit'] );
			} else {
				wc_add_order_item_meta( $item_id, '_purchase_unit', $unit_data['purchase_unit'] );
				// wc_add_order_item_meta( $item_id, 'purchase_unit', $unit_data['purchase_unit'] );
			}
		}

		if ( isset( $unit_data['wut_selected_rate'] ) && '' !== $unit_data['wut_selected_rate'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_selected_rate', $unit_data['wut_selected_rate'] );
				// $item->add_meta_data( 'wut_selected_rate', $unit_data['wut_selected_rate'] );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_selected_rate', $unit_data['wut_selected_rate'] );
				// wc_add_order_item_meta( $item_id, 'wut_selected_rate', $unit_data['wut_selected_rate'] );
			}
		}

		if ( isset( $unit_data['wut_selected_from'] ) && '' !== $unit_data['wut_selected_from'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_selected_from', $unit_data['wut_selected_from'] );
				// $item->add_meta_data( 'wut_selected_from', $unit_data['wut_selected_from'] );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_selected_from', $unit_data['wut_selected_from'] );
				// wc_add_order_item_meta( $item_id, 'wut_selected_from', $unit_data['wut_selected_from'] );
			}
		}

		if ( isset( $unit_data['wut_selected_to'] ) && '' !== $unit_data['wut_selected_to'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_selected_to', $unit_data['wut_selected_to'] );
				// $item->add_meta_data( 'wut_selected_to', $unit_data['wut_selected_to'] );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_selected_to', $unit_data['wut_selected_to'] );
				// wc_add_order_item_meta( $item_id, 'wut_selected_to', $unit_data['wut_selected_to'] );
			}
		}

		if ( isset( $unit_data['wut_conv_rate'] ) && '' !== $unit_data['wut_conv_rate'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_conv_rate', $unit_data['wut_conv_rate'] );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_conv_rate', $unit_data['wut_conv_rate'] );
			}
		}
		if ( isset( $unit_data['wut_is_global'] ) && '' !== $unit_data['wut_is_global'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_is_global', $unit_data['wut_is_global'] );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_is_global', $unit_data['wut_is_global'] );
			}
		}
		if ( isset( $unit_data['wut_price_by'] ) && '' !== $unit_data['wut_price_by'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_price_by', $unit_data['wut_price_by'] );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_price_by', $unit_data['wut_price_by'] );
			}
		}
		if ( isset( $unit_data['wut_price_per_unit'] ) && '' !== $unit_data['wut_price_per_unit'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_price_per_unit', $unit_data['wut_price_per_unit'] );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_price_per_unit', $unit_data['wut_price_per_unit'] );
			}
		}
		if ( isset( $unit_data['wut_unt_price'] ) && '' !== $unit_data['wut_unt_price'] ) {
			if ( $add_item_meta ) {
				$item->add_meta_data( '_wut_unt_price', $unit_data['wut_unt_price'] );
			} else {
				wc_add_order_item_meta( $item_id, '_wut_unt_price', $unit_data['wut_unt_price'] );
			}
		}

		do_action( 'wut_update_item_meta', $item_id, $product_id, $unit_data, $add_item_meta, $item );
	}

	/**
	 * Save unit information to Order Note.
	 *
	 * @param integer $item_id Order Item ID
	 * @param integer $order_id Order ID.
	 * @param string  $note Note to be saved containing unit Information.
	 * @since 1.0.0
	 */
	public static function save_unit_information_to_order_note( $item_id, $order_id, $note ) {
		global $wpdb;

		$order = wc_get_order( $order_id );
		if ( ! empty( $order ) ) {

			// Ensure the function is executed only when $item_id is defined. If $item_id is undefined, retrieve its value.
			if ( empty( $item_id ) ) {
				$order_items = $order->get_items();
				$order_item  = reset( $order_items );
				$item_id     = $order_item->get_id();
			}

			// Verify if the Order Note ID is saved to ensure a single note is used for storing unit information.
			$order_note_id = (int) wc_get_order_item_meta( $item_id, '_wut_order_note_id', true );

			if ( $order_note_id > 0 ) {
				// The Order Note already exists, so we'll update it, but we first need to retrieve the existing data.
				$previous_note = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT comment_content FROM {$wpdb->prefix}comments WHERE comment_ID = %d",
						$order_note_id
					)
				);

				$note = $previous_note . "\n" . $note;

				$wpdb->query(
					$wpdb->prepare(
						"UPDATE {$wpdb->prefix}comments SET comment_content = %s WHERE comment_ID = %d",
						$note,
						$order_note_id
					)
				);
			} else {
				$order_note_id = $order->add_order_note( $note );
				wc_add_order_item_meta( $item_id, '_wut_order_note_id', $order_note_id );
			}
		}
	}

	/**
	 * This function checks if the order ID exists in the order_history table.
	 *
	 * @param string $order_id Order ID
	 *
	 * @return bool $unit_is_present Return true of order info is present else false
	 * @since 1.0.0
	 */
	public static function wut_unit_is_present_check( $order_id ) {
		global $wpdb;

		$check_data    = 'SELECT * FROM `' . $wpdb->prefix . 'unit_order_history` WHERE order_id = %s';
		$results_check = $wpdb->get_results( $wpdb->prepare( $check_data, $order_id ) );

		$unit_is_present = ( count( $results_check ) > 0 ) ? true : false;

		return $unit_is_present;
	}

	/**
	 * The function verifies if the given product ID exists as a child in the _children postmeta.
	 *
	 * @param  int $child_id
	 * @return int $parent_id
	 *
	 * @since 1.0.0
	 */
	public static function wut_get_parent_id( $child_id ) {
		global $wpdb;

		$parent_id = '';
		$query_children = 'SELECT post_id, meta_value FROM `' . $wpdb->prefix . 'postmeta`
                           WHERE meta_key = %s';

		$results_children = $wpdb->get_results( $wpdb->prepare( $query_children, '_children' ) );

		if ( is_array( $results_children ) && count( $results_children ) > 0 ) {
			foreach ( $results_children as $r_value ) {
				if ( $r_value->meta_value != '' ) {
					$child_array = maybe_unserialize( $r_value->meta_value );

					if ( is_array( $child_array ) && ( in_array( $child_id, $child_array ) ) ) {
						$parent_id = $r_value->post_id;
						break;
					}
				}
			}
		}
		return $parent_id;
	}

	/**
	 * This function return the unit field labels.
	 *
	 * @since 1.0.0
	 */
	public static function wut_unit_fields_label() {
		$selected_unit_lbl = __( 'Selected Unit', WOOUNITS_DOMAIN );
		$unit_labels          = array(
			'selected_unit' => $selected_unit_lbl,
		);

		return $unit_labels;
	}

	/**
	 * Update each level in unit history table
	 *
	 * @param int    $order_id Order ID.
	 * @param int    $unit_id Unit ID.
	 * @param string $query type of query to perform.
	 *
	 * @globals mixed $wpdb
	 *
     * @since 1.0.0
	 */
	public static function wut_update_each_level_unit_history( $post_id, $unit_id, $quantity, $action = 'add', $call = 'front' ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'unit_history';

		// $from_unit = ! empty( get_option( 'wut_from_unit' ) ) ? get_option( 'wut_from_unit' ) : 'pound';
        // $to_unit   = ! empty( get_option( 'wut_to_unit' ) ) ? get_option( 'wut_to_unit' ) : 'ounce';
        $from_unit = get_post_meta( $post_id, '_wut_from_unit', true );
        $to_unit = get_post_meta( $post_id, '_wut_to_unit', true );
        $conv_rate = WooUnits_Common::wut_calc_from_to_to_units( $from_unit, $to_unit );

		$current_unit = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $table_name WHERE id = %d AND post_id = %d",
				$unit_id,
				$post_id
			)
		);

		if ( ! empty( $current_unit ) ) {
			$cunit_level_value = $current_unit->unit_level_value;
			$cnew_qty          = $cunit_level_value * $quantity;
			$e_ounce           = 1 / $cunit_level_value;
			$removed_ounces    = round( $cnew_qty * $e_ounce, 3 );
			$removed_ounces    = ( 'admin' !== $call ) ? ( $removed_ounces + $current_unit->to_total ) : ( $current_unit->to_total - $removed_ounces);
			$single_ounce      = round( $conv_rate - $removed_ounces, 3 );
			$pounds_decimal    = round( $single_ounce / $conv_rate, 4 );

			$all_units = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM $table_name WHERE post_id = %d AND from_unit = %s AND to_unit = %s",
					$post_id,
					$from_unit,
					$to_unit
				)
			);

			foreach ( $all_units as $specfic_unit ) {
				$level_ounce = $single_ounce * $specfic_unit->unit_level_value;

				if ( 'remove' === $action ) {
					// $level_ounce = $specfic_unit->available_unit - $level_ounce;
					// $removed_ounces = $specfic_unit->to_total - $removed_ounces;
					// $single_ounce = round( $conv_rate - $removed_ounces, 3 );
					// $pounds_decimal = round( $single_ounce / $conv_rate, 4 );

					$query = 'UPDATE `' . $wpdb->prefix . 'unit_history`
					SET available_unit = ' . $level_ounce . ",
					from_total = '" . $pounds_decimal . "',
					to_total = '" . $removed_ounces . "'
					WHERE
					id = '" . $specfic_unit->id . "' AND
					post_id = '" . $post_id . "' AND
					status != 'inactive' AND
					total_unit > 0";

					$removed = $wpdb->query( $query );

				} else {
					$query = $wpdb->prepare(
						"UPDATE {$wpdb->prefix}unit_history 
						SET available_unit = %f, 
							from_total = %f, 
							to_total = %f 
						WHERE id = %d 
							AND post_id = %d 
							AND status != %s 
							AND total_unit > 0",
						$level_ounce, 
						$pounds_decimal, 
						$removed_ounces, 
						$specfic_unit->id, 
						$post_id, 
						'inactive'
					);

					$updated = $wpdb->query( $query );
				}
			}
		}
	}

	/**
	 * This function add/remove units as per action.
	 *
	 * @param int    $order_id Order ID.
	 * @param string $action Item Object.
	 *
	 * @since 1.0.0
	 */
	public static function wut_add_remove_units( $order_id, $action = 'add' ) {
		global $wpdb;

		$order        = wc_get_order( $order_id );
		$order_items  = $order->get_items();
		$select_query = 'SELECT unit_id FROM `' . $wpdb->prefix . 'unit_order_history` WHERE order= %d';
		$results      = $wpdb->get_results( $wpdb->prepare( $select_query, $order_id ) );
		$item_unit_id = 0;
		$unit_details = array();

		foreach ( $results as $key => $value ) {
			$select_query_post = 'SELECT post_id, total_unit, available_unit, from_total, to_total FROM `' . $wpdb->prefix . 'unit_history`
								WHERE id= %d';
			$results_post      = $wpdb->get_results( $wpdb->prepare( $select_query_post, $value->unit_id ) );

			if ( count( $results_post ) > 0 ) {
				$unit_info = array(
					'post_id'        => $results_post[0]->post_id,
					'total_unit'     => $results_post[0]->total_unit,
					'available_unit' => $results_post[0]->available_unit,
					'from_total'     => $results_post[0]->from_total,
					'to_total'       => $results_post[0]->to_total,
				);
				$unit_details[ $value->unit_id ] = $unit_info;
				$item_unit_id                    = $value->unit_id;
			}
		}

		$unit_labels = WooUnits_Common::wut_unit_fields_label();
		if ( ! empty( $unit_details ) ) {
			foreach ( $order_items as $item_key => $item_value ) {
				$all_unit_details = self::wut_get_unit_item_meta( $item_key, $item_value, $unit_labels );

				$quantity = $item_value['quantity'];
				$qty      = $all_unit_details['_purchase_unit'];
				$u_id     = $all_unit_details['_wut_uh'];
				$post_id  = $unit_details[$u_id]['post_id'];

				$product_id = WooUnits_Common::wut_get_product_id( $item_value['product_id'] );
				$_product   = wc_get_product( $product_id );
				$parent_id  = 0;

				// It verifies the product object; if it's boolean, the parent ID won't be fetched.
				if ( is_bool( $_product ) === false ) {
					$parent_id = ( version_compare( WOOCOMMERCE_VERSION, '3.0.0' ) < 0 ) ? $_product->get_parent() : WooUnits_Common::wut_get_parent_id( $product_id );
				}
				if ( isset( $parent_id ) && $parent_id != '' ) {
					self::wut_update_each_level_unit_history( $parent_id, $u_id, $qty, $action, 'admin' );
				} else {
					self::wut_update_each_level_unit_history( $product_id, $u_id, $qty, $action, 'admin' );
				}
			}
		}
	}

	/**
	 * This function will prepare the array for the unit details in the order item.
	 *
	 * @param int   $item_id Item ID.
	 * @param array $item Item Object.
	 * @param array $unit_labels Array of Unit Label.
	 *
	 * @since 1.0.0
	 */
	public static function wut_get_unit_item_meta( $item_id, $item, $unit_labels ) {
		$sel_unit_item_meta = $unit_labels['selected_unit'];
		$all_unit_details   = array();

		foreach ( $item->get_meta_data() as $meta_index => $meta ) {
			switch ( $meta->key ) {
				case $sel_unit_item_meta:
					$all_unit_details['selected_unit'] = $meta->value;
					break;
				case 'wut_select_unit':
					$all_unit_details['_wut_select_unit'] = $meta->value;
					break;
				case 'wut_uh':
					$all_unit_details['_wut_uh'] = $meta->value;
					break;
				case 'wut_lbl':
					$all_unit_details['_wut_lbl'] = $meta->value;
					break;
				case 'purchase_unit':
					$all_unit_details['_purchase_unit'] = $meta->value;
					break;
				case 'wut_unit_status':
					$all_unit_details['wut_unit_status'] = $meta->value;
					break;
				case 'wut_selected_rate':
					$all_unit_details['wut_selected_rate'] = $meta->value;
					break;
				case 'wut_selected_from':
					$all_unit_details['wut_selected_from'] = $meta->value;
					break;
				case 'wut_selected_to':
					$all_unit_details['wut_selected_to'] = $meta->value;
					break;
			}
		}

		return $all_unit_details;
	}

	/**
	 * This function returns the Woocommerce price
	 *
	 * @param integer $product_id
	 * @param integer $variation_id
	 * @param string  $product_type - simple, variable, bundled, composite, etc.
	 * @return integer $price
	 *
	 * @since 1.0.0
	 */
	public static function wut_get_price( $product_id, $variation_id = 0, $product_type = 'simple' ) {
		global $wpdb;

		$adp_product_id            = $product_id;
		$price                     = 0;
		$wpml_multicurreny_enabled = 'no';
		if ( function_exists( 'icl_object_id' ) ) {
			global $woocommerce_wpml, $woocommerce;
			if ( isset( $woocommerce_wpml->settings['enable_multi_currency'] ) && $woocommerce_wpml->settings['enable_multi_currency'] == '2' ) {
				if ( $product_type == 'variable' ) {
					$custom_post = self::wut_get_custom_post( $product_id, $variation_id, $product_type );
					if ( $custom_post == 1 ) {
						$client_currency = $woocommerce->session->get( 'client_currency' );
						if ( $client_currency != '' && $client_currency != get_option( 'woocommerce_currency' ) ) {
							$price                     = get_post_meta( $variation_id, '_price_' . $client_currency, true );
							$wpml_multicurreny_enabled = 'yes';
						}
					}
				} elseif ( $product_type == 'simple' || 'bundle' == $product_type ) {
					$custom_post = self::wut_get_custom_post( $product_id, $variation_id, $product_type );
					if ( $custom_post == 1 ) {
						$client_currency = $woocommerce->session->get( 'client_currency' );
						if ( $client_currency != '' && $client_currency != get_option( 'woocommerce_currency' ) ) {
							$price                     = get_post_meta( $product_id, '_price_' . $client_currency, true );
							$wpml_multicurreny_enabled = 'yes';
						}
					}
				}
			}
		}

		if ( $wpml_multicurreny_enabled == 'no' ) {

			if ( $product_type == 'variable' ) {

				$adp_product_id = $variation_id;
				$sale_price     = get_post_meta( $variation_id, '_sale_price', true );

				if ( ! isset( $sale_price ) || $sale_price == '' || $sale_price == 0 ) {
					$regular_price = get_post_meta( $variation_id, '_regular_price', true );
					$price         = $regular_price;
				} else {
					$price = $sale_price;
				}

				if ( isset( $_POST['wc_measurement'] ) ) {
					$price = $price * $_POST['wc_measurement'];
				}
			} elseif ( $product_type == 'simple' || 'bundle' == $product_type || 'composite' == $product_type ) {
				$product_obj = self::wut_get_product( $product_id );
				if ( 'bundle' === $product_type ) {
					$sale_price = get_post_meta( $product_id, '_wc_pb_base_sale_price', true );
				} else {
					$sale_price = get_post_meta( $product_id, '_sale_price', true );
				}

				if ( $sale_price != '' ) {

					$price = $product_obj->get_sale_price();

					// $regular_price = get_post_meta( $product_id, '_sale_price', true );
					// $price         = $product_obj->get_sale_price();
				} else {
					$price = $product_obj->get_price();
					if ( isset( $_POST['alg_lang'] ) || isset( $_POST['wc_membership'] ) ) {
						$price = get_post_meta( $product_id, '_regular_price', true );
					}
				}

				if ( $price == '' ) {
					$price = 0;
				}

				// $unit_price = self::wut_get_per_unit_price( $product_id );
				// if ( ! empty( $unit_price ) ) {
				// 	$price = $unit_price;
				// }

				if ( isset( $_POST['wc_measurement'] ) ) {
					$price = $price * $_POST['wc_measurement'];
				}
			} else {
				if ( isset( $variation_id ) && $variation_id != '0' && $variation_id != '' ) {
					$product_obj = self::wut_get_product( $variation_id );
				} else {
					$product_obj = self::wut_get_product( $product_id );
				}

				$price = $product_obj->get_price();
			}

			// Compatibility with Advanced Dynamic Pricing for WooCommerce Pro plugin.
			$price = self::wut_get_price_adp( $price, $adp_product_id );

			// check if any of the products are individually priced
			// if yes then we need to add those to the bundle price
			/*
			if ( $price > 0 && 'bundle' == $product_type ) {
			$bundle_price = self::get_bundle_price( $price, $product_id, $variation_id );

			$price = $bundle_price;
			}*/
		}

		// Passes the final price through a filter for external adjustments.
		$price = apply_filters( 'wut_get_price', $price, $product_id );

		return $price;
	}

	
	/**
	 * Retrieves the Multicurrency setting at the product level from the WPML plugin when active.
	 *
	 * @param integer $product_id - Product ID
	 * @param integer $variation_id - Variation ID
	 * @param string  $product_type - Product Type
	 * @return integer $custom_post
	 * @since 1.0.0
	 */
	public static function wut_get_custom_post( $product_id, $variation_id, $product_type ) {
		if ( $product_type == 'variable' ) {
			$custom_post = get_post_meta( $variation_id, '_wcml_custom_prices_status', true );
		} elseif ( $product_type == 'simple' || $product_type == 'grouped' ) {
			$custom_post = get_post_meta( $product_id, '_wcml_custom_prices_status', true );
		}
		if ( $custom_post == '' ) {
			$custom_post = 0;
		}
		return $custom_post;
	}

	/**
	 * Get WooCommerce Product object
	 *
	 * @param  int|string $product_id Product ID
	 * @return WC_Product Product Object
	 * @since  1.0.0
	 */
	public static function wut_get_product( $product_id ) {
		return wc_get_product( $product_id );
	}

	/**
	 * Returns Woocommerce price from the Advanced Dynamic Pricing for WooCommerce Pro plugin.
	 *
	 * @param float   $price Product Price.
	 * @param integer $product_id Product ID.
	 *
	 * @since 1.0.0
	 */
	public static function wut_get_price_adp( $price, $product_id ) {

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Ensure the plugin is activated.
		if ( ! is_plugin_active( 'advanced-dynamic-pricing-pro/advanced-dynamic-pricing-for-woocommerce-pro.php' ) ) {
			return $price;
		}

		$qty = isset( $_POST['quantity'] ) ? (int) $_POST['quantity'] : 0; // phpcs:ignore

		// ADP offers special functions for discounted prices that we should consider.
		$context    = new \ADP\BaseVersion\Includes\Context();
		$customizer = \ADP\Factory::get( 'External_Customizer_Customizer', $context );

		$product = \ADP\BaseVersion\Includes\External\CacheHelper::getWcProduct( $product_id );

		// We're focusing on simple and variable products for now, but need to explore ADP's handling of other product types.
		if ( ! $product->is_type( 'simple' ) && 'product_variation' !== $product->post_type ) {
			return $price;
		}

		$range_record = \ADP\Factory::get( 'External_RangeDiscountTable_RangeDiscountTable', $context, $customizer );

		// Verifying ADP discount policies.
		$rule = $range_record->findRuleForProductTable( $product );
		if ( ! $rule ) {
			return $price;
		}

		// Verifying ADP Product Handlers.
		$handler = $rule->getProductRangeAdjustmentHandler();
		if ( ! $handler ) {
			return $price;
		}

		// Generate the discounted price using available rules.
		$price_processor = $range_record->makePriceProcessor( $rule );
		if ( ! $price_processor ) {
			return $price;
		}

		// Review the product quantities in your cart.
		foreach ( WC()->cart->cart_contents as $cart_content ) {
			$facade = new \ADP\BaseVersion\Includes\External\WC\WcCartItemFacade( $context, $cart_content );

			if ( $facade->getProduct()->get_id() === $product->get_id() ) {
				$qty += $facade->getQty();
			}
		}

		// Explore discount options.
		$ranges = array();
		foreach ( $handler->getRanges() as $range ) {

			$adp_product = $price_processor->calculateProduct( $product, $range->getFrom() );

			if ( ! $adp_product ) {
				return $price;
			}

			$range_from = $range->getFrom();
			$range_to   = $range->getTo();

			if ( ( $range_from <= $qty && $qty <= $range_to )
			|| ( $range_from <= $qty && '' === $range_to )
			|| ( '' === $range_from && $qty <= $range_to )
			) {
				$price = $adp_product->getPrice();
			}
		}

		return $price;
	}

	/**
	 * Returns Unit price from the global settings or product level unit price.
	 *
	 * @param integer $product_id Product ID.
	 *
	 * @since 1.0.0
	 */
	public static function wut_get_per_unit_price( $product_id ) {
		if ( $product_id > 0 ) {
			// $from_unt  = ! empty( get_option( 'wut_from_unit' ) ) ? get_option( 'wut_from_unit' ) : 'pound';
			// $to_unt    = ! empty( get_option( 'wut_to_unit' ) ) ? get_option( 'wut_to_unit' ) : 'ounce';
			$from_unt = get_post_meta( $product_id, '_wut_from_unit', true );
			$to_unt = get_post_meta( $product_id, '_wut_to_unit', true );
			$conv_rate = WooUnits_Common::wut_calc_from_to_to_units( $from_unt, $to_unt );

			$wut_price_unt     = get_post_meta( $product_id, '_wut_price_unit', true );
			$wut_price_per_unt = (float) get_post_meta( $product_id, '_wut_price_per_unit', true );

			if ( ! empty( $wut_price_unt ) && ! empty( $wut_price_per_unt ) ) {
				$unt_price = ( 'wut_to_unit' === $wut_price_unt ) ? ( $wut_price_per_unt * $conv_rate ) : $wut_price_per_unt;
				return $unt_price;
			} else {
				$price_unt     = ! empty( get_option( 'wut_price_unit' ) ) ? get_option( 'wut_price_unit' ) : '';
				$price_per_unt = ! empty( get_option( 'wut_price_per_unit' ) ) ? (float) get_option( 'wut_price_per_unit' ) : '';

				if ( ! empty( $price_unt ) && ! empty( $price_per_unt ) ) {
					$unt_price = ( 'wut_to_unit' === $price_unt ) ? ( $price_per_unt * $conv_rate ) : $price_per_unt;
					return $unt_price;
				}
			}
		}
		return;
	}

}