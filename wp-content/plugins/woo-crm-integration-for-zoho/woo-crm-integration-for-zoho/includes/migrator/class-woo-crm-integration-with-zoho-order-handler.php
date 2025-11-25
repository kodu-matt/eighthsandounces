<?php
/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes/migrator
 */

 use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
 use Automattic\WooCommerce\Utilities\OrderUtil;
/**
 * Fired during plugin migration.
 *
 * This class defines all code necessary to run during the plugin's migration.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes/migrator
 */
class Integration_With_Woo_Zoho_Order_Handler {

	/**
	 * Define the core functionality of the migrator.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Get_order_page
	 * Serves for new meta keys only.
	 *
	 * @param string $option_name   The option name.
	 * @param string $def_value     The default option value.
	 *
	 * @since     1.0.0
	 */
	public static function wciz_get_shop_order_screen_id() {
		$screenid = wc_get_container()->get(CustomOrdersTableController::class)->custom_orders_table_usage_is_enabled() ?  wc_get_page_screen_id( 'shop-order' ) :  'shop_order';
		return $screenid;
	}

	public static function wciz_get_cot_enabled_order_enabled() {
		$is_enabled = wc_get_container()-> get(CustomOrdersTableController::class)-> custom_orders_table_usage_is_enabled() ?  true :  false;
		return $is_enabled;
	}

	/**
	 * Undocumented function
	 *
	 * @param boolean $option_name
	 * @param boolean $def_value
	 * @return string
	 */
	public static function wciz_get_object_type( $object_id ) {
		if ( wc_get_container()-> get(CustomOrdersTableController::class)-> custom_orders_table_usage_is_enabled() ) {
			$post_type = empty ( OrderUtil::get_order_type( $object_id ) ) ? get_post_type( $object_id ) : OrderUtil::get_order_type( $object_id ) ;
		} else {
			$post_type = get_post_type( $object_id );
		}
		return $post_type;
	}

	// End of class.
}
