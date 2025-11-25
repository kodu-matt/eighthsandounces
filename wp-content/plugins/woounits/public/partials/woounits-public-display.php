<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Woounits
 * @subpackage Woounits/public/partials
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class for adding the public display.
*/
class WooUnits_Public_Display {

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        // Bind the unit form.
        add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'wut_on_woocommerce_before_add_to_cart_form' ) );

        add_action( 'wp_ajax_wut_get_unit_price', array( $this, 'wut_get_unit_price' ) );
        add_action( 'wp_ajax_nopriv_wut_get_unit_price', array( $this, 'wut_get_unit_price') );
    }

    /**
     * This function uses the appropriate WooCommerce hook based on the product type.
     *
     * @hook woocommerce_before_add_to_cart_form
     *
     * @since 1.0.0
     */
    public function wut_on_woocommerce_before_add_to_cart_form() {
        if ( get_post_type() == 'product' ) {
            $product = wc_get_product( get_the_ID() );
            if ( $product->get_type() == 'variable' ) {
                add_action( 'woocommerce_single_variation', array( $this, 'wut_unit_after_add_to_cart' ), 8 );
            } else {
                add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'wut_unit_after_add_to_cart' ), 8 );
            }
        }
    }

    /**
     * This function adds unit fields to the frontend product page for "is_unit" products.
     *
     * @hook woocommerce_before_add_to_cart_button
     *
     * @since 1.0.0
     */
    public static function wut_unit_after_add_to_cart() {
        global $post;

        $product_id = WooUnits_Common::wut_get_product_id( $post->ID );
        $is_unit    = WooUnits_Common::wut_get_is_unit( $product_id );
        if ( ! $is_unit ) {
            return;
        }

        $product_price = '';
        $product       = wc_get_product( $post->ID );
        $product_type  = $product->get_type();
        $show_dropdown = get_option( 'wut_units_display_mode', 'radio' );
        $default_selected = array();
		$select_unit   = apply_filters( 'wut_choose_unit_dropdown_option', __( 'Choose a unit', 'woounits' ), $product_id );

        $product_price = WooUnits_Common::wut_get_price( $product_id );
        $product_units = WooUnits_Common::wut_get_product_all_units( $product_id );
        $def_selected_unit = get_option( 'wut_def_selected_unit', 'yes' );
        
        if ( $product_units && 'yes' === $def_selected_unit ) {
            $default_selected = $product_units[0];
        }

        $message = '';
        if ( ! empty( $default_selected ) && $product->is_in_stock() ) {
            $stock_quantity = $product->get_stock_quantity();

                $p_from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
                $p_to_unit = get_post_meta( $product_id, '_wut_to_unit', true );

                $from_unit = $default_selected->from_unit;
                $to_unit = $default_selected->to_unit;
                $unit_value = $default_selected->unit_level_value;

                $new_unit_val = WooUnits_Common::wut_calc_from_to_to_units( $from_unit, $to_unit, 'value', $unit_value );

                if ( $to_unit !== $p_to_unit ) {
                    $stock_quantity = WooUnits_Common::wut_calc_from_to_to_units( $p_from_unit, $p_to_unit, 'value', $stock_quantity );
                } else {
                    $stock_quantity = WooUnits_Common::wut_calc_from_to_to_units( $from_unit, $to_unit, 'value', $stock_quantity );
                }

                $availability = ( $stock_quantity > 0 ) ? intval( $stock_quantity / $new_unit_val ) : $stock_quantity; 
                $message = __( $availability . ' quantities are available per ' . $default_selected->unit_level_key . '.', 'woounits' );

        }
        $ss_style = ! empty( $message ) ? 'display: block;' : 'display: none;';
        // if ( isset( $product_type ) && 'simple' === $product_type ) {
            // if ( '' !== $unit_settings  ) {
                // $variation_id  = 0;
                // $product_price = get per unit price global/product level;
            // }
        // }
        ?>
        <div id="wut-unit-form" class="wut-unit-form">
            <?php
            do_action( 'wut_before_unit_form', $product_id );

            do_action( 'wut_before_availability_message', $product_id );
            ?>

            <div id="wut_show_stock_status" name="show_stock_status" class="wut_show_stock_status" style="<?php echo $ss_style; ?>">
                <?php echo  $message; ?>
            </div>

            <?php do_action( 'wut_after_availability_message', $product_id ); ?>

            <div class="wut_unit" id="wut_unit">
                <label class="wut_unit_label" style="margin-top:1em;">
                    <?php
                        $wut_unit_label = get_option( 'wut_unit-label', __( 'Units', 'woounits' ) );
                        $wut_unit_label = apply_filters( 'wut_change_unit_label', $wut_unit_label, $product_id );
                        echo __( $wut_unit_label, 'woounits' );
                    ?>
                </label>
                <?php
                /**
                 * Show units in dropdown.
                 */
                if ( $product_units ) {
                    if ( 'dropdown' === $show_dropdown ) {
                        // Show dropdown
                        ?>
                        <select name="wut_select_unit" id="wut_select_unit" required="">
                            <option value=""><?php echo esc_attr( $select_unit ); ?></option>
                            <?php
                            foreach ( $product_units as $key => $value ) {
                                ?>
                                <option value="<?php echo esc_attr( $value->unit_level ); ?>" 
                                data-uh="<?php echo esc_attr( $value->id ); ?>" 
                                data-level_value="<?php echo esc_attr( $value->unit_level_value ); ?>" 
                                data-level_price="<?php echo esc_attr( $value->unit_level_price ); ?>" 
                                data-from_unit="<?php echo esc_attr( $value->from_unit ); ?>" 
                                data-to_unit="<?php echo esc_attr( $value->to_unit ); ?>" 
                                data-total_unit="<?php echo esc_attr( $value->total_unit ); ?>" 
                                data-available_unit="<?php echo esc_attr( $value->available_unit ); ?>">
                                    <?php echo esc_html( $value->unit_level_key ); ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                        <?php
                    } else {
                        // Show radio buttons
                        ?>
                       <div class="wut-unit-selection" id="wut_select_unit">
                            <?php
                            foreach ( $product_units as $key => $value ) {
                                $unit_value = esc_attr( $value->unit_level );
                                $unit_label = esc_html( $value->unit_level_key );
                                ?>
                                <label class="wut-unit-radio">
                                    <input type="radio" name="wut_radio_unit" value="<?php echo $unit_value; ?>"
                                        data-uh="<?php echo esc_attr( $value->id ); ?>"
                                        data-level_value="<?php echo esc_attr( $value->unit_level_value ); ?>"
                                        data-level_price="<?php echo esc_attr( $value->unit_level_price ); ?>"
                                        data-from_unit="<?php echo esc_attr( $value->from_unit ); ?>"
                                        data-to_unit="<?php echo esc_attr( $value->to_unit ); ?>"
                                        data-total_unit="<?php echo esc_attr( $value->total_unit ); ?>"
                                        data-available_unit="<?php echo esc_attr( $value->available_unit ); ?>" 
                                        required />
                                    <span class="unit-label"><?php echo $unit_label; ?></span>
                                    <span class="checkmark"></span>
                                </label>
                                <?php
                            }
                            ?>
                        </div>

                        <?php
                    }
                }

                // Set default first option selected.
                if ( ! empty( $default_selected ) ) {
                    ?>
                    <input type="hidden" name="wut_uh" id="wut_uh" value="<?php echo $default_selected->id; ?>" />
                    <input type="hidden" name="wut_lbl" id="wut_lbl" value="<?php echo esc_attr( $default_selected->unit_level_key ); ?>" />
                    <input type="hidden" name="wut_selected_rate" id="wut_selected_rate" value="<?php echo $default_selected->unit_level_value; ?>" />
                    <input type="hidden" name="wut_selected_from" id="wut_selected_from" value="<?php echo $default_selected->from_unit; ?>" />
                    <input type="hidden" name="wut_selected_to" id="wut_selected_to" value="<?php echo $default_selected->to_unit; ?>" />
                    <?php
                } else {
                    ?>
                    <input type="hidden" name="wut_uh" id="wut_uh" value="" />
                    <input type="hidden" name="wut_lbl" id="wut_lbl" value="" />
                    <input type="hidden" name="wut_selected_rate" id="wut_selected_rate" value="" />
                    <input type="hidden" name="wut_selected_from" id="wut_selected_from" value="" />
                    <input type="hidden" name="wut_selected_to" id="wut_selected_to" value="" />
                    <?php
                }
            ?>
                <input type="hidden" name="wut_product_id" id="wut_product_id" value="<?php echo $product_id; ?>" />
                <input type="hidden" name="wut_product_price" id="wut_product_price" value="<?php echo $product_price; ?>" />
            </div>

            <?php do_action( 'wut_before_add_to_cart_button', $product_id ); ?>

            <div class="wut-form-error"></div>
        </div>
        <?php

        do_action( 'wut_after_unit_box_form', $product_id );

        $from_unt = get_post_meta( $product_id, '_wut_from_unit', true );
        $to_unt = get_post_meta( $product_id, '_wut_to_unit', true );
        // $from_unt  = ! empty( get_option( 'wut_from_unit' ) ) ? get_option( 'wut_from_unit' ) : 'pound';
        // $to_unt    = ! empty( get_option( 'wut_to_unit' ) ) ? get_option( 'wut_to_unit' ) : 'ounce';
        $conv_rate = WooUnits_Common::wut_calc_from_to_to_units( $from_unt, $to_unt );

        $is_global  = 0;
        $unt_price  = '';
        $price_by  = '_wut_from_unit';
        // $price_by   = ! empty( get_option( 'wut_price_unit' ) ) ? get_option( 'wut_price_unit' ) : '';
        // $unit_per_price = ! empty( get_option( 'wut_price_per_unit' ) ) ? get_option( 'wut_price_per_unit' ) : '';
        // if ( ! empty( $price_by ) && ! empty( $unit_per_price ) ) {
        //     $unt_price = ( 'wut_to_unit' === $price_by ) ? ( $unit_per_price * $conv_rate ) : $unit_per_price;
        // }

        // $price_unt     = get_post_meta( $post->ID, '_wut_price_unit', true );
        // $price_per_unt = get_post_meta( $post->ID, '_wut_price_per_unit', true);
		// if ( ! empty( $price_unt ) && ! empty( $price_per_unt ) ) {
        //     $is_global  = 0;
        //     $price_by   = $price_unt;
        //     $unit_per_price = $price_per_unt;
        //     $unt_price  = ( 'wut_to_unit' === $price_by ) ? ( $unit_per_price * $conv_rate ) : $unit_per_price;
		// }

        $unit_price = WooUnits_Common::wut_get_price( $product_id );
        // $unt_price = WooUnits_Common::wut_calc_from_to_to_units( $from_unt, $to_unt, 'value', $unit_price );

        do_action( 'wut_before_unit_price_html' );

        $lbl_total = __( 'Total :', 'woounits' );
        
        if ( ! empty( $default_selected ) ) {
            $style = 'display:block;';
            $unt_price = ( ! empty( $default_selected->unit_level_price ) ) ? $default_selected->unit_level_price : $unit_price;
            $total_price = wc_price( $unt_price );
        } else {
            $style = 'display:none;';
            $unt_price = $unit_price;
            $total_price = '';
        }

        $html = '<div id="wut-price-box" style="' . $style . '">
                    <span id="wut_no_of_units"></span>
                    <input type="hidden" name="wut_conv_rate" id="wut_conv_rate" value="' . $conv_rate . '">
                    <input type="hidden" name="wut_is_global" id="wut_is_global" value="' . $is_global . '">
                    <input type="hidden" name="wut_price_by" id="wut_price_by" value="' . $price_by . '">
                    <input type="hidden" name="wut_price_per_unit" id="wut_price_per_unit" value="' . $unit_price . '">
                    <input type="hidden" name="wut_unt_price" id="wut_unt_price" value="' . $unt_price . '">
                    <span id="wut_price" class="price">' . $lbl_total . $total_price . '</span>
                </div>';

        $price_html = apply_filters( 'wut_unit_price_html', $html, $lbl_total );

        echo $price_html;

        do_action( 'wut_after_unit_price_html' );

    }

    /**
     * This function get unit price.
     *
     * @since 1.0.0
     */
    public static function wut_get_unit_price() {
        $product_id = isset( $_REQUEST['product_id'] ) ? $_REQUEST['product_id'] : '';
        $unit_id    = isset( $_REQUEST['unit_id'] ) ? $_REQUEST['unit_id'] : '';
        $unit_value = isset( $_REQUEST['unit_value'] ) ? (float) $_REQUEST['unit_value'] : 1;
        $unit_level_price = isset( $_REQUEST['unit_price'] ) ? (float) $_REQUEST['unit_price'] : 0;
        $lbl_value  = isset( $_REQUEST['lbl_value'] ) ? $_REQUEST['lbl_value'] : 1;
        $quantity   = isset( $_REQUEST['quantity'] ) ? $_REQUEST['quantity'] : 1;
        $from_unit  = isset( $_REQUEST['from_unit'] ) ? $_REQUEST['from_unit'] : '';
        $to_unit    = isset( $_REQUEST['to_unit'] ) ? $_REQUEST['to_unit'] : '';

        $product_id = WooUnits_Common::wut_get_product_id( $product_id );
        $product = wc_get_product( $product_id );
        // $unit_price = WooUnits_Common::wut_get_per_unit_price( $product_id );

        if ( ! empty( $unit_level_price ) ) {
            $unit_price = $unit_level_price;
        } else {
            $unit_price = WooUnits_Common::wut_get_price( $product_id );

            $unit_price = WooUnits_Common::wut_calc_from_to_to_units( $from_unit, $to_unit, 'value', $unit_price );

            $unit_price = $unit_price * $unit_value;
        }

        if ( ! empty( $unit_price ) ) {
			$price_html = wc_price( $unit_price * $quantity );
			$price_html = __( 'Total :', 'woounits' ) . ' ' . $price_html;

            $message = '';
            if ($product->is_in_stock()) {
                $stock_quantity = $product->get_stock_quantity();

                $is_unit = WooUnits_Common::wut_get_is_unit( $product_id );
                if ( $is_unit ) {
                    $p_from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
                    $p_to_unit = get_post_meta( $product_id, '_wut_to_unit', true );

                    $new_unit_val = WooUnits_Common::wut_calc_from_to_to_units( $from_unit, $to_unit, 'value', $unit_value );

                    if ( $to_unit !== $p_to_unit ) {
                        $stock_quantity = WooUnits_Common::wut_calc_from_to_to_units( $p_from_unit, $p_to_unit, 'value', $stock_quantity );
                    } else {
                        $stock_quantity = WooUnits_Common::wut_calc_from_to_to_units( $from_unit, $to_unit, 'value', $stock_quantity );
                    }

                    $availability   = ( $stock_quantity > 0 ) ? intval( $stock_quantity / $new_unit_val ) : $stock_quantity; 
                    $message = __( $availability . ' quantities are available per ' . $lbl_value . '.', 'woounits' );
                }
            }

            $data = array(
                'stock_quantity' => $stock_quantity,
                'availability' => $availability,
                'av_message' => $message,
                'unit_price' => $unit_price,
                'total_price' => $unit_price * $quantity,
                'wut_price' => $price_html,
            );
            wp_send_json_success( array( 'msg' => __( 'Data updated successfully.', 'woounits' ), 'html' => $data ) );
        }

    }
}
new WooUnits_Public_Display();
