<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Woounits
 * @subpackage Woounits/admin/partials
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class for adding the Admin Menus.
 */
class WooUnits_Product_Metabox {

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( &$this, 'wut_add_product_units_meta_box' ) );
        add_action( 'save_post', array( &$this, 'wut_save_product_unit_data' ) );
    }

    /**
     * Adds the settings menu to the admin menu.
     *
     * @since 1.0.0
     */
    public function wut_add_product_units_meta_box() {
        add_meta_box(
            'product_units_meta_box',
            __( 'Product Units', 'woounits' ),
            array( &$this, 'wut_render_product_units_meta_box' ),
            'product',
            'normal',
            'default'
        );
    }


    /**
     * Metabox callback function.
     *
	 * @since    1.0.0
	 * @param    array    $post   Product object.
     */
    public function wut_render_product_units_meta_box( $post ) {
        // Retrieve current meta data
        $wut_price_unt     = get_post_meta( $post->ID, '_wut_price_unit', true );
        $wut_price_per_unt = get_post_meta( $post->ID, '_wut_price_per_unit', true );
        $price_units       = array( 'wut_from_unit' => __( 'Per From Unit', 'woounits' ), 'wut_to_unit' => __( 'Per To Unit', 'woounits' ) );

        ?>
        <div class="wrap wut-mb-wrap">
            <table class="form-table">
                <tr class="form-field">
                    <th scope="row"><label for="wut_price_unit"><?php echo __( 'Price by Unit', 'woounits' ); ?></label></th>
                    <td>
                        <select name="wut_price_unit" id="wut_price_unit">
                            <?php
                            if ( ! empty( $price_units ) ) {
                                foreach ( $price_units as $key => $value ) {
                                    ?>
                                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $key, $wut_price_unt ); ?> ><?php echo esc_html( $value ); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="wut_price_per_unit"><?php echo __( 'Price Per Unit', 'woounits' ); ?> </label></th>
                    <td><input name="wut_price_per_unit" type="number" step="any" id="wut_price_per_unit" value="<?php echo esc_attr( $wut_price_per_unt ); ?>" /></td>
                </tr>
            </table>
        </div>
        <?php
    }


    /**
     * Save Metabox data.
     *
	 * @since    1.0.0
	 * @param    array    $post_id   Product ID.
     */
    public static function wut_save_product_unit_data( $post_id ) {
        if ( isset( $_POST['wut_price_unit'] ) ) {
            update_post_meta( $post_id, '_wut_price_unit', $_POST['wut_price_unit'] );
        }
        if ( isset( $_POST['wut_price_per_unit'] ) ) {
            update_post_meta( $post_id, '_wut_price_per_unit', $_POST['wut_price_per_unit'] );
        }
    }

    /**
     * Get unit level HTML.
     *
	 * @since    1.0.0
	 * @param    string    $from    From unit.
	 * @param    string    $to      To unit.
	 * @param    int       $level   Unit level.
	 * @return   $html.
     */
    public function wut_get_unit_level_html( $from, $to, $level = 1 ) {
        $html      = '';
        $conv_rate = WooUnits_Common::wut_calc_from_to_to_units( $from, $to );

        for ( $i = 1; $i <= $level; $i++ ) {
            $lbl       = ! empty( get_option( 'wut_ul_'. $i ) ) ? get_option( 'wut_ul_' . $i ) : 'Level ' . $i;
            $lbl_level = esc_attr__( $lbl, 'woounits' );
            $value     = ! empty( get_option( 'wut_ul_val_'. $i ) ) ? get_option( 'wut_ul_val_' . $i ) : $i;
            $rec       = $conv_rate * $value;

            $html .= "<tr>
                <td><input id='wut_ul_$i' name='wut_ul_$i' type='text' value='$lbl_level' /></td>
                <td><input id='wut_ul_val_$i' name='wut_ul_val_$i' type='number' min='1' step='any' value='$value'/></td>
                <td id='wut_ul_rec_$i'>1 to $rec</td>
            </tr>";
        }
        return apply_filters( 'woounits_get_unit_level_tbl_html', $html, $from, $to, $level );
    }

} 
new WooUnits_Product_Metabox();