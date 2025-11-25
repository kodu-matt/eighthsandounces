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
class WooUnits_Admin_Menu {

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'admin_menu', array( &$this, 'wut_main_admin_menu' ) );

        add_action( 'woocommerce_product_options_stock', array( $this, 'wut_add_product_stock_qty' ) );

        add_filter( 'plugin_action_links_woounits/woounits.php', array( $this, 'wut_plugin_settings_link') );

        add_action( 'wp_ajax_wut_add_unit_level_raw', array( $this, 'wut_add_unit_level_raw' ) );
        add_action( 'wp_ajax_nopriv_wut_add_unit_level_raw', array( $this, 'wut_add_unit_level_raw') );

        add_action( 'wp_ajax_wut_add_sub_block', array( $this, 'wut_add_sub_block' ) );
        add_action( 'wp_ajax_nopriv_wut_add_sub_block', array( $this, 'wut_add_sub_block') );

        add_action( 'wp_ajax_wut_save_global_settings', array( $this, 'wut_save_global_settings' ) );
        add_action( 'wp_ajax_nopriv_wut_save_global_settings', array( $this, 'wut_save_global_settings') );

        // add_action( 'admin_head', array( $this, 'woounits_remove_duplicate_submenu') );

    }

    /**
     * Adds the settings menu to the admin menu.
     *
     * @since 1.0.0
     */
    public function wut_main_admin_menu() {
        add_menu_page(
            __( 'WooUnits', 'woounits' ),
            __( 'WooUnits', 'woounits' ),
            'manage_options',
            'woounits-setting',
            array( &$this, 'wut_woounits_settings_page' ),
            'dashicons-admin-generic',
        );

        add_submenu_page(
            'woounits-setting',                        // Parent slug
            __( 'Settings', 'woounits' ),             // Page title
            __( 'Settings', 'woounits' ),             // Menu title
            'manage_options',                         // Capability
            'wut-setting',                       // Menu slug (same as main menu = highlight this as default)
            array( &$this, 'wut_settings_page' )      // Callback function
        );
    }

    public function woounits_remove_duplicate_submenu() {
        remove_submenu_page('woounits-setting', 'woounits-setting');
    }

    /**
     * Add the custom field in stock management.
     *
     * @since 1.0.0
     */
    public function wut_add_product_stock_qty() {
        global $product_object;

        $product_id = $product_object->get_id();
        $product_id = WooUnits_Common::wut_get_product_id( $product_id );
        $is_unit    = WooUnits_Common::wut_get_is_unit( $product_id );

        if ( $is_unit ) {
            $from_unit = get_post_meta( $product_id, '_wut_from_unit', true );
            $message = __( 'The stock quantity & Product price is considered the '. $from_unit .' unit.', 'woounits' );
        } else {
            $message = __( 'If the unit is enabled, the stock quantity will be considered as the default unit.', 'woounits' );
        }

        echo '<p class="form-field"><span class="custom-stock-message" style="font-weight: bold;">'. $message .'</span></p>';
    }

    /**
     * Main menu Settings page.
     *
     * @since 1.0.0
     */
    public function wut_woounits_settings_page() {

        $wut_g_settings = ! empty( get_option( 'wut_g_settings' ) ) ? get_option( 'wut_g_settings' ) : array();
        $ind = count( $wut_g_settings );

        ?>
        <div class="wrap wut-wrap">
            <h1><?php echo __( 'WooUnits Settings', 'woounits' ); ?></h1>
            <form id="wut_settings_form" name="wut_settings_form" class="wut_form" method="post">
                <input type="hidden" id="wut_cid" name="wut_cid" value="<?php echo $ind; ?>">
                <?php wp_nonce_field( 'wut_settings', '_wpnonce_wut_settings_form' ); ?>

                <div class="wut-main-container">
                    <table id="wut_tbl_main">
                        <tbody id="wut_tb_main">
                        <?php
                            if ( ! empty( $wut_g_settings ) && count( $wut_g_settings ) > 0 ) {
                                $data = array();
                                foreach ( $wut_g_settings as $gs_key => $gs_val ) {
                                    echo $this->wut_get_main_sub_block_html( $gs_key, $gs_val );
                                }
                            } else {
                                echo $this->wut_get_main_sub_block_html();
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="wut-button-wrap">
                        <button type="button" id="wut_btn_add_sub" data-ind="<?php echo $ind; ?>"><?php esc_html_e( 'Add New Measurement', 'woounits' ); ?></button>
                    </div>
                </div>
                <div class="notice is-dismissible wut-notice">
                    <p class="wut-msg"></p>
                </div>
                <p class="submit">
                    <input type="submit" value="<?php echo esc_attr_e( "Save Settings", 'woounits' ); ?>" class="button button-primary button-large wut_save_settings" id="wut_save_settings" name="wut_save_settings">
                </p>
            </form>
        </div>
        <?php
    }

    /**
     * Sub menu Settings page.
     *
     * @since 1.0.0
     */
    public function wut_settings_page() {
        $display_mode = get_option( 'wut_units_display_mode', 'radio' );
        $def_selected_unit = get_option( 'wut_def_selected_unit', 'yes' );
        $display_views = array(
            'dropdown' => __('Dropdown View', 'woounits'),
            'radio'    => __('Radio View', 'woounits'),
        );
        $default_selected = array(
            'yes' => __('Yes', 'woounits'),
            'no'    => __('No', 'woounits'),
        );

        ?>
        <div class="wrap wut-wrap">
            <h1><?php echo __( 'General Settings', 'woounits' ); ?></h1>
            <form id="wut_settings_form" name="wut_settings_form" class="wut_form" method="post">
                <?php wp_nonce_field( 'wut_settings', '_wpnonce_wut_settings_form' ); ?>

                <div class="wut-sub-menu-container">
                    <table id="wut_tbl_sub">
                        <tbody>
                            <tr>
                                <th scope="row"><?php echo esc_attr__( 'Display mode for Units :', 'woounits' ); ?></th>
                                <td>
                                    <select id="wut_units_display_mode" name="wut_units_display_mode">
                                        <?php
                                        foreach ($display_views as $key => $val) {
                                            ?>
                                            <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $key, $display_mode, false ); ?>><?php echo esc_html( $val ); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <label for="wut_units_display_mode"><?php echo esc_attr__( 'Choose the units display mode on single product page.', 'woounits' ); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo esc_attr__( 'Default selected Unit :', 'woounits' ); ?></th>
                                <td>
                                    <!-- <input type="checkbox" id="wut_def_selected_unit" name="wut_def_selected_unit" value="<?php // echo $def_selected_unit; ?>"> -->
                                    <select id="wut_def_selected_unit" name="wut_def_selected_unit">
                                        <?php
                                        foreach ($default_selected as $key => $val) {
                                            ?>
                                            <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $key, $def_selected_unit, false ); ?>><?php echo esc_html( $val ); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <label for="wut_def_selected_unit"><?php echo esc_attr__( 'Choose the default unit selected on the single product page.', 'woounits' ); ?></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="notice is-dismissible wut-notice">
                    <p class="wut-msg"></p>
                </div>
                <p class="submit">
                    <input type="submit" value="<?php echo esc_attr_e( "Save Settings", 'woounits' ); ?>" class="button button-primary button-large wut_save_settings" id="wut_save_settings" name="wut_save_settings">
                </p>
            </form>
        </div>
        <?php
    }

	/**
	 * Gets all Products.
	 *
	 * @since 1.0.0
	 */
	public static function get_all_products() {

		$products = get_posts(
			array(
				'post_type'      => array( 'product' ),
				'posts_per_page' => -1,
				'post_status'    => array( 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash' ),
			)
		);

		foreach ( $products as $key => $item ) {
			$settings = get_post_meta( $item->ID, 'woocommerce_unit_settings', true );

			if ( ! isset( $settings['unit_enable_date'] ) || ( isset( $settings['unit_enable_date'] ) && 'on' !== $settings['unit_enable_date'] ) ) {
				unset( $products[ $key ] );
			}
		}

		return $products;
	}

    /**
     * Generates the HTML for a unit level row with validation.
     */
    public function wut_generate_unit_row( $index = 0, $is_removable = '', $unit_label = 'Unit Label', $selected_unit = '', $unit_value = 1, $unit_price = '' ) {
        $unit_label = __( $unit_label, 'woounits' );
        $measurement_units = WooUnits_Common::wut_get_all_units();
        $btn_remove = '<td><button class="wut_btn_remove_unt_level">'. esc_attr__( 'Remove', 'woounits' ) .'</button></td>';
        $def_unit = '<td>' . esc_html__( 'Default Unit', 'woounits' ) . '</td>';

        $html = '<tr>
            <td><input type="text" name="wut_global_settings[wut_unit_label][' . $index .'][]" value="' . esc_attr( $unit_label ) . '" placeholder="' . esc_attr__( 'Enter unit label', 'woounits' ) . '" required></td>
            <td><select class="wut_meas_unit" name="wut_global_settings[wut_meas_unit][' . $index .'][]" class="" required>';
            if ( ! empty( $measurement_units ) ) {
                foreach ($measurement_units as $group_id => $group) {
                    $html .= '<optgroup data-group-id="' . $group_id . '" label="' . $group['label'] . '">';
                    foreach ($group['options'] as $value => $details) {
                        $html .= '<option value="' . esc_attr( $value ) . '" ' . selected( $value, $selected_unit, false ) . '>' . esc_html( $details[0] ) . '</option>';
                    }
                    $html .= '</optgroup>';
                }
            }
        $html .= '</select></td>';
        $html .= '<td><input type="number" name="wut_global_settings[wut_unit_value][' . $index .'][]" value="' . esc_attr( $unit_value ) . '" placeholder="' . esc_attr__( 'Enter unit rate', 'woounits' ) . '" step="any" required></td>';
        $html .= '<td><input type="number" name="wut_global_settings[wut_unit_price][' . $index .'][]" value="' . esc_attr( $unit_price ) . '" placeholder="' . esc_attr__( 'Enter price', 'woounits' ) . '" step="any" required></td>';

        $html .= ( 0 == $is_removable ) ? $def_unit : $btn_remove;
        $html .= '</tr>';

        return $html;
    }

    public function wut_get_main_sub_block_html( $index = 0, $b_data = array() ) {
        $args = array(
            'taxonomy'     => 'product_cat',
            'order'        => 'ASC',
            'pad_counts'   => false,
            'hierarchical' => true,
            'number'       => 0,
        );
        $categories = get_categories( $args );

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => array( 'publish', 'pending', 'draft', 'private' ),
        );
        $products = get_posts( $args );

		$select_product_category_data = array(
			__( 'All Products', 'woounits' ) => array(
				'all_products' => __( 'All Products', 'woounits' ),
			),
		);

        if ( ! empty( $categories ) ) {
            foreach ( $categories as $key => $category ) {
                $select_product_category_data[ __( 'Select by Product Category', 'woounits' ) ][ 'cat_' . $category->slug ] = ( 'uncategorized' === $category->slug ) ? 'Uncategorized Products' : 'Products in ' . $category->cat_name . ' Category';
            }
        }

        if ( ! empty( $categories ) ) {
            foreach ( $this->get_all_products() as $item ) {
                $select_product_category_data[ __( 'Select by Product Name', 'woounits' ) ][ $item->ID ] = $item->post_title;
            }
        }

        $description     = __( 'Add unit settings for multiple products. To avoid excess system resource consumption, Bulk unit Settings will be processed in the background for product count greater than 50.', 'woounits' );
        $pro_desc        = __( 'Select the product for which you want to add the unit settings. You can also select a Product Category to add unit settings to all the products in the selected category.', 'woounits' );
        $pro_placeholder = __( 'Select a Product', 'woounits' );

        $b_pro_type = array();
        $b_products = array();
        $b_unt_labels = array();
        $b_meas_unit = array();
        $b_unt_values = array();
        $b_unt_prices = array();
        if ( ! empty( $b_data ) ) {
            $b_products   = isset( $b_data['wut_products'] ) ? $b_data['wut_products'] : array();
            $b_unt_labels = isset( $b_data['wut_unit_label'] ) ? $b_data['wut_unit_label'] : array();
            $b_meas_unit = isset( $b_data['wut_meas_unit'] ) ? $b_data['wut_meas_unit'] : array();
            $b_unt_values = isset( $b_data['wut_unit_value'] ) ? $b_data['wut_unit_value'] : array();
            $b_unt_prices = isset( $b_data['wut_unit_price'] ) ? $b_data['wut_unit_price'] : array();

        }
        $sub_index = count( $b_unt_labels );

        $opt_all = in_array( "all_products", $b_products ) ? ' selected' : '';
        $html = '<tr class="wut_main_block">
                    <td>
                        <div class="wut_block_wrapper">
                            <table class="form-table">';
                                $html .= '<tr>
                                    <th>
                                        <label for="wut_global_settings[wut_products]['. $index .'][][]">' . esc_html__( 'Select Products or Categories', 'woounits' ) . '</label>
                                    </th>
                                    <td>
                                        <select name="wut_global_settings[wut_products]['. $index .'][][]" class="wut_product_cats" multiple="multiple" required>
                                            <optgroup label="' . esc_attr__( 'Select All Products', 'woounits' ) . '">
                                                <option value="all_products"' . $opt_all . '>' . esc_html__( 'All Products', 'woounits' ) . '</option>
                                            </optgroup>
                                            <optgroup label="' . esc_attr__( 'Select by Product Category', 'woounits' ) . '">';
                                                foreach ( $categories as $category ) {
                                                    $category_text = ( 'uncategorized' === $category->slug ) ? esc_html__( 'Uncategorized Products', 'woounits' ) : esc_html__( 'Products in ' . $category->cat_name . ' Category', 'woounits' );
                                                    $option_value  = 'cat_' . $category->slug;
                                                    $opt_cat = in_array( $option_value, $b_products ) ? ' selected' : '';
                                                    $html .= '<option value="' . esc_attr( $option_value ) . '" ' . $opt_cat . '>' . esc_html( $category_text ) . '</option>';
                                                }
                                            $html .= '</optgroup>
                                            <optgroup label="' . esc_attr__( 'Select by Product Name', 'woounits' ) . '">';
                                                foreach ( $products as $product ) {
                                                    $opt_pro = in_array( $product->ID, $b_products ) ? ' selected' : '';
                                                    $html .= '<option value="' . esc_attr( $product->ID ) . '" ' . $opt_pro . '>' . esc_html( $product->post_title ) . '</option>';
                                                }
                                                $productss = implode(',', array_column($products, 'ID'));
                                            $html .= '</optgroup>
                                        </select>
                                        <div>
                                            <p>' . esc_html( $pro_desc ) . '</p>
                                        </div>
                                        <input type="hidden" class="wut_all_products" name="wut_global_settings[custId]['. $index .'][]" value="' . esc_attr( $productss ) . '">
                                        <input type="hidden" class="wut_bid" name="wut_bid" value="' . esc_attr( $index ) . '">
                                    </td>
                                </tr>
                                <tr class="form-field">
                                    <th scope="row">
                                        <label>' . esc_html__( 'Measurements', 'woounits' ) . '</label>
                                    </th>
                                    <td>
                                        <div class="wut-units-container">
                                            <table class="wut_tbl_measurement">
                                                <thead>
                                                    <tr>
                                                        <th>' . esc_html__( 'Unit Label', 'woounits' ) . '</th>
                                                        <th>' . esc_html__( 'Measurement', 'woounits' ) . '</th>
                                                        <th>' . esc_html__( 'Unit Value', 'woounits' ) . '</th>
                                                        <th>' . esc_html__( 'Unit Price', 'woounits' ) . '</th>
                                                        <th>' . esc_html__( 'Action', 'woounits' ) . '</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="wut_tb_meas_'. $index .'">';
                                                $btn_remove = '<td><button class="wut_btn_remove_unt_level">'. esc_attr__( 'Remove', 'woounits' ) .'</button></td>';
                                                $def_unit = '<td>' . esc_html__( 'Default Unit', 'woounits' ) . '</td>';

                                                if ( ! empty( $b_unt_labels ) ) {
                                                    foreach ( $b_unt_labels as $b_lkey => $b_lbl ) {
                                                        $html .= $this->wut_generate_unit_row( $index, $b_lkey, $b_lbl, $b_meas_unit[$b_lkey], $b_unt_values[$b_lkey], $b_unt_prices[$b_lkey] );
                                                    }
                                                } else {
                                                    $html .= $this->wut_generate_unit_row( $index, $sub_index );
                                                }
                                                $html .= '</tbody>
                                            </table>
                                            <div class="wut-button-wrap">
                                                <button type="button" class="wut_btn_add_unt_level" data-ind="'. $index .'">' . esc_html__( 'Add Unit', 'woounits' ) . '</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>';

        return $html;
    }

    /**
     * Generate unit history table records.
     *
	 * @since    1.0.0
	 * @param    float     $unit_rate   Unit rate.
	 * @param    string    $from_unit   From unit.
	 * @param    string    $to_unit     To unit.
	 * @param    int       $post_id     Product ID.
     */
    public function wut_generate_unit_history_records( $data, $product_id = 0 ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'unit_history';
        $unit_level = isset( $data['wut_unit_level'] ) ? (int) $data['wut_unit_level'] : 1;

        if ( $unit_level > 0 ) {
            for ( $i = 0; $i < $unit_level; $i++ ) {
                $lbl        = ! empty( $data['wut_unit_label'][$i] ) ? $data['wut_unit_label'][$i] : 'Level ' . $i;
                $lbl_key    = esc_attr__( $lbl, 'woounits' );
                $lbl_value  = ! empty( $data['wut_unit_value'][$i] ) ? (float) $data['wut_unit_value'][$i] : $i;
                $unit_price = ! empty( $data['wut_unit_price'][$i] ) ? (float) $data['wut_unit_price'][$i] : $i;
                $from_unit  = ! empty( $data['wut_meas_unit'][0] ) ? $data['wut_meas_unit'][0] : '';
                $to_unit    = ! empty( $data['wut_meas_unit'][$i] ) ? $data['wut_meas_unit'][$i] : '';
                $to_unit    = ( $i > 0 ) ? $to_unit : $from_unit;
                $total_unit = WooUnits_Common::wut_calc_from_to_to_units( $from_unit, $to_unit, 'rate', $lbl_value );

                update_post_meta( $product_id, '_wut_from_unit', $from_unit );
                update_post_meta( $product_id, '_wut_to_unit', $to_unit );

                $uh_obj = array(
                    'post_id'           => $product_id,
                    'unit_level'        => $i,
                    'unit_level_key'    => $lbl_key,
                    'unit_level_value'  => $lbl_value,
                    'unit_level_price'  => $unit_price,
                    'from_unit'         => $from_unit,
                    'to_unit'           => $to_unit,
                    'total_unit'        => $total_unit,
                    'available_unit'    => $total_unit,
                    'from_total'        => 1,
                    'to_total'          => 0,
                    'status'            => 'active',
                );
                $format = array( '%d', '%d', '%s', '%f', '%f', '%s', '%s', '%f', '%f', '%f', '%f', '%s' );

                $exist = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name WHERE post_id = %d AND from_unit = %s AND unit_level = %d",
                        $product_id,
                        $from_unit,
                        $i
                    )
                );

                if ( ! empty( $exist ) ) {
                    $uh_obj['total_unit']     = $exist->total_unit;
                    $uh_obj['available_unit'] = $exist->available_unit;
                    $uh_obj['from_total']     = $exist->from_total;
                    $uh_obj['to_total']       = $exist->to_total;
                    $wpdb->update( $table_name, $uh_obj, array( 'id' => $exist->id), $format, array( '%d' ) );
                } else {
                    $inserted = $wpdb->insert( $table_name, $uh_obj, $format );
                    if ( ! $inserted ) {
                        $message = apply_filters( 'woounits_validation_message_uh_db_add_fail', __( 'Failed to add new record.', 'woounits' ) );
                        wp_send_json_error( array( 'msg' => $message ) );
                    }
                }
            }
        }
    }

    /**
     * This function saves the data from the tabs.
     * Different save buttons are present in each tab.
     * They all will call this function, which will check
     * the data present and save the same.
     *
     * @since 1.0.0
     */
    public static function wut_ss_get_product_ids( $post_id, $all_product_ids ) {

        if ( is_array( $post_id ) ) {

            if ( in_array( 'all_products', $post_id ) ) { // If all product is selected then get all product ids.
                $all_product_ids = is_array( $all_product_ids ) ? $all_product_ids[0] : $all_product_ids;
                $post_id = explode( ',', $all_product_ids );
            } else {
                $slugs        = array();
                $all_products = $post_id;

                // Get Product Category values in Post ID array.
                $product_categories = array_filter(
                    $post_id,
                    function ( $data ) {
                        return strpos( $data, 'cat_' ) !== false;
                    }
                );

                // Check if Product Category has been selected.
                if ( count( $product_categories ) > 0 ) {
                    foreach ( $product_categories as $category ) {

                        // Remove category id from product array.
                        if ( ( $key = array_search( $category, $all_products ) ) !== false ) {
                            unset( $all_products[ $key ] );
                        }

                        $slugs[] = str_replace( 'cat_', '', $category );
                    }

                    if ( count( $slugs ) > 0 ) {

                        $args = array(
                            'posts_per_page' => -1,
                            'post_type'      => 'product',
                            'tax_query'      => array(
                                'relation' => 'AND',
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field'    => 'slug',
                                    'terms'    => $slugs,
                                ),
                            ),
                        );

                        $products = get_posts( $args );

                        // Add array of products to original array.
                        foreach ( $products as $key => $product ) {
                            $all_products[] = $product->ID;
                        }

                        // Return unique Post IDs to remove Products that were selected and are also existing in Product Category.
                        $post_id = array_unique( $all_products );
                    }
                }
            }

            // Get Product Count. If it is more than 50, then we invoke the background processing action.

            // $products_count = count( $post_id );

            // if ( $products_count >= 50 ) {

            //     $wut_bulk_unit_settings = wut_bulk_unit_settings();

            //     if ( isset( $wut_bulk_unit_settings ) ) {

            //         $sent_for_processing = 0;
            //         $product_settings    = array();

            //         // if ( isset( $_POST['unit_options'] ) ) { //phpcs:ignore
            //         //     $product_settings['unit_options'] = $_POST['unit_options']; //phpcs:ignore
            //         // }

            //         foreach ( $post_id as $pk => $pv ) {
            //             $product_id = WooUnits_Common::wut_get_product_id( $pv );

            //             $wut_bulk_unit_settings->push_to_queue(
            //                 array(
            //                     'product_id'       => $product_id,
            //                     'product_settings' => $product_settings,
            //             ) ); //phpcs:ignore

            //             $sent_for_processing++;
            //         }

            //         if ( ! $sent_for_processing ) {
            //             return;
            //         }

            //         set_transient( 'wut_bulk_unit_settings_background_process_running', 0 );
            //         $wut_bulk_unit_settings->save()->dispatch();

            //         $return = array( 'message' => 'Settings are being saved in the background.' );
            //     }
            // } else {
            //     foreach ( $post_id as $pk => $pv ) {
            //         $product_id = WooUnits_Common::wut_get_product_id( $pv );
            //         self::wut_inactive_old_records_for_product( $product_id );
            //         self::wut_save_settingss( $product_id );
            //     }

            //     $return = array( 'message' => 'Settings have been saved!!!' );
            // }

            // wp_send_json( $return );
        } else {
            // $product_id = WooUnits_Common::wut_get_product_id( $post_id );
            // self::wut_save_settingss( $product_id );
            // $return = array( 'message' => "Settings are saved for $product_id!!!" );

            // wp_send_json( $return );
        }
        return $post_id;
    }

    /**
     * Adds the plugin settings link to the plugin listing page.
     *
	 * @since    1.0.0
	 * @param    array    $links   Plugin related links array.
     * @return   array    $links   Return links array.
     */
    public function wut_plugin_settings_link( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=woounits-setting' ) . '" aria-label="' . esc_attr__( 'View WooUnit settings', 'woounits' ) . '">' . esc_html__( 'Settings', 'woounits' ) . '</a>',
		);

		return array_merge( $action_links, $links );
    }

    /**
     * Handles the AJAX request.
     */
    public function wut_add_unit_level_raw( $is_default = false ) {
		$index = isset( $_REQUEST['cid'] ) ? $_REQUEST['cid'] : 0;
        $html  = $this->wut_generate_unit_row( $index );

        if ( $is_default ) {
            return $html;
        } else {
            wp_send_json_success( array( 'msg' => __( 'Data updated successfully.', 'woounits' ), 'html' => $html ) );
        }
    }

    /**
     * Handles the AJAX request.
     */
    public function wut_add_sub_block() {
        $ind = isset( $_REQUEST['ind'] ) ? $_REQUEST['ind'] : 1;
        $html = $this->wut_get_main_sub_block_html( $ind );

        wp_send_json_success( array( 'msg' => esc_html__( 'Data updated successfully.', 'woounits' ), 'html' => $html ) );
    }

    /**
     * Handles the AJAX request.
     */
    public function wut_save_global_settings() {
        if ( isset( $_REQUEST['wut_units_display_mode'] ) ) {
            // Save sub menu data.
            update_option( 'wut_units_display_mode', $_REQUEST['wut_units_display_mode'] );

            if ( isset( $_REQUEST['wut_def_selected_unit'] ) ) {
                update_option( 'wut_def_selected_unit', $_REQUEST['wut_def_selected_unit'] );
            }
        } else {
            // Save main menu data.
            $wut_gss = array();
            $old_pids = ! empty( get_option( 'wut_product_ids' ) ) ? get_option( 'wut_product_ids' ) : array();

            update_option( 'wut_old_prod_ids', $old_pids );

            if ( ! empty( $old_pids ) ) {
                foreach( $old_pids as $id ) {
                    update_post_meta( $id, '_wut_enable_unit', '' );
                }
            }

            if ( isset( $_REQUEST['wut_global_settings'] ) && isset( $_REQUEST['wut_global_settings']['wut_products'] ) ) {
                $wut_total_block = count( $_REQUEST['wut_global_settings']['wut_products'] ); // Get total block count

                update_option( 'wut_total_block', $wut_total_block );
                for ( $i = 0; $i < $wut_total_block; $i++ ) {
                    $new_data = array();

                    foreach ( $_REQUEST['wut_global_settings'] as $key => $val ) {
                        if ( isset( $val[$i] ) ) {
                            if ( 'wut_unit_label' === $key ) {
                                $new_data['wut_unit_level'] = count( $val[$i] );
                            }
                            $new_data[$key] = ( 'wut_products' === $key && is_array( $val[$i] ) ) ? array_reduce($val[$i], 'array_merge', []) : $val[$i];
                        }
                    }

                    if ( ! empty( $new_data ) ) {
                        $wut_gss[] = $new_data;
                    }
                }
            }

            update_option( 'wut_g_settings', $wut_gss );

            if ( ! empty( $wut_gss ) ) {
                $new_pro_ids = array();
                foreach ( $wut_gss as $key => $val ) {
                    if ( isset( $val['custId'] ) && isset( $val['wut_products'] ) ) {
                        if ( empty( $old_pids ) && ! empty( $val['custId'] ) ) {
                            $all_product_ids = is_array( $val['custId'] ) ? $val['custId'][0] : $val['custId'];
                            $pro_ids = explode( ',', $all_product_ids );
                            foreach( $pro_ids as $id ) {
                                update_post_meta( $id, '_wut_enable_unit', '' );
                            }
                        }
                        $post_ids = $this->wut_ss_get_product_ids( $val['wut_products'], $val['custId'] );
                        
                        if ( ! empty( $post_ids ) ) {
                            // $this->wut_generate_unit_history_records( $val );
                            foreach ( $post_ids as $product_id ) {
                                $new_pro_ids[] = $product_id;
                                $product_id = WooUnits_Common::wut_get_product_id( $product_id );
                                update_post_meta( $product_id, '_wut_enable_unit', 'yes' );
                                $this->wut_generate_unit_history_records( $val, $product_id );
                            }
                        }
                    }
                }
                update_option( 'wut_product_ids', $new_pro_ids );
            }
        }

        wp_send_json_success( array( 'msg' => __( 'Settings save successfully.', 'woounits' ) ) );
    }

} 
new WooUnits_Admin_Menu();