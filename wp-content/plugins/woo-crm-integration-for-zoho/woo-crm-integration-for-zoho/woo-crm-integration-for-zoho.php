<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since                1.0.0
 * @package              Woo_Crm_Integration_For_Zoho
 *
 * @wordpress-plugin
 * Plugin Name:          Woo CRM Integration For Zoho
 * Plugin URI:           https://nebulastores.com/product/woo-crm-integration-of-zoho/
 * Description:          <code><strong>Woo CRM Integration for Zoho</strong></code> Sync WooCommerce store data, including Products and Contacts, with Zoho CRM, making management effortless.
 * Version:              1.0.0
 * Requires Plugins:     woocommerce
 * Author:               Nebulastores
 * Author URI:           https://nebulastores.com
 * License:              GPL-3.0+
 * License URI:          http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:          woo-crm-integration-for-zoho
 * Domain Path:          /languages
 * WP Requires at least: 5.5.0
 * WP Tested up to:      6.7.1
 * WC requires at least: 5.5.0
 * WC tested up to:      9.7.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

use Automattic\WooCommerce\Utilities\OrderUtil;
// HPOS compatibility.
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

/**
 * Activate plugin.
 *
 * @since 1.0.0
 */
function wciz_is_required_plugin_active() {
    if ( ! function_exists( 'is_plugin_active' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    return is_plugin_active( 'woocommerce/woocommerce.php' ) || ( is_multisite() && is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) );
}


/**
 * Checks WooCommerce dependency during plugin validation.
 *
 * @since    1.0.0
 */
if ( wciz_is_required_plugin_active() ) {

	define( 'WOO_CRM_INTEGRATION_ZOHO_VERSION', '1.0.0' );
	define( 'WOO_CRM_INTEGRATION_ZOHO_PATH', plugin_dir_path( __FILE__ ) );
	define( 'WOO_CRM_INTEGRATION_ZOHO_URL', plugin_dir_url( __FILE__ ) );

	/**
	 * Executes during plugin activation.
	 */
	function activate_woo_crm_integration_for_zoho() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-crm-integration-for-zoho-activator.php';
		Woo_Crm_Integration_For_Zoho_Activator::activate();
	}

	/**
	 * Executes during plugin deactivation.
	 */
	function deactivate_woo_crm_integration_for_zoho() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-crm-integration-for-zoho-deactivator.php';
		Woo_Crm_Integration_For_Zoho_Deactivator::deactivate();
	}

	register_activation_hook( __FILE__, 'activate_woo_crm_integration_for_zoho' );
	register_deactivation_hook( __FILE__, 'deactivate_woo_crm_integration_for_zoho' );

	/**
	 * Defines plugin files.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-woo-crm-integration-for-zoho.php';
	require plugin_dir_path( __FILE__ ) . 'includes/migrator/class-wciz-data-handler.php';
	require plugin_dir_path( __FILE__ ) . 'includes/migrator/class-woo-crm-integration-with-zoho-order-handler.php';

	/**
	* Adds a settings link to the plugin listing.
	*/
	add_filter( 'plugin_action_links', 'wciz_zoho_admin_settings', 10, 2 );

	/**
	 * Adds a settings link to the plugin listing.
	 *
	 * @since        1.0.0
	 * @param array  $actions actions.
	 * @param string $plugin_file plugin file path.
	 */
	function wciz_zoho_admin_settings( $actions, $plugin_file ) {
		if ( plugin_basename( __FILE__ ) === $plugin_file ) {
			$actions = array_merge(
				array(
					'settings' => '<a href="' . esc_url( admin_url( 'admin.php?page=woo-crm-integration-for-zoho' ) ) . '">' . esc_html__( 'Settings', 'woo-crm-integration-for-zoho' ) . '</a>',
				),
				$actions
			);
		}
		return $actions;
	}	

	/**
	* Add docs and support link.
	*/
	add_filter( 'plugin_row_meta', 'wciz_zoho_plugin_row_links', 10, 2 );

	/**
	 * Add docs and support link.
	 *
	 * @since        1.0.0
	 * @param array  $links Other links.
	 * @param string $file plugin file path.
	 */
	function wciz_zoho_plugin_row_links( $links, $file ) {

		if ( 'woo-crm-integration-for-zoho/woo-crm-integration-for-zoho.php' === $file ) {
			unset($links[2]);
			$row_meta = array(
				'visit_plugin_site' => '<a href="' . esc_url( 'https://nebulastores.com/product/woo-crm-integration-of-zoho/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'woo-crm-integration-for-zoho' ) . '">' . esc_html__( 'Visit Plugin Site', 'woo-crm-integration-for-zoho' ) . '</a>',
				'docs'     => '<a href="' . esc_url( 'https://nebulastores.com/product/woo-crm-integration-of-zoho/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'woo-crm-integration-for-zoho' ) . '"><svg class="wciz-info-img" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 124.99 169.34"><defs><style>.cls-1{fill:#1e1e1e;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="Layer_2-2" data-name="Layer 2"><g id="Layer_1-2-2" data-name="Layer 1-2"><g id="Documentation_filled" data-name="Documentation filled"><g id="Docs"><path class="cls-1" d="M19,169.34a19.05,19.05,0,0,1-19-19V19.06A19,19,0,0,1,19,0H79.87a6,6,0,0,1,6,6V36.42a2.72,2.72,0,0,0,2.72,2.69H119a6,6,0,0,1,6,6v105.2a19.06,19.06,0,0,1-19,19H19ZM19.06,12A7,7,0,0,0,12,19.05V150.31a7,7,0,0,0,7,7h86.9a7,7,0,0,0,7-7V51.11H88.57a14.76,14.76,0,0,1-14.7-14.67V12Z"/><path class="cls-1" d="M119,51.11H88.57a14.76,14.76,0,0,1-14.7-14.67V6a6,6,0,0,1,6-6h0a10.11,10.11,0,0,1,7.36,3.19l34.63,34.62a10.19,10.19,0,0,1,3.12,7.3,6,6,0,0,1-6,6Zm-5.4-4.61h0ZM85.87,18.79V36.42a2.72,2.72,0,0,0,2.72,2.69H106.2Z"/></g><path class="cls-1" d="M82.28,86.39H37.86a6.25,6.25,0,0,1-6.26-6.25h0a6.25,6.25,0,0,1,6.26-6.25H82.28a6.25,6.25,0,0,1,6.26,6.25h0A6.25,6.25,0,0,1,82.28,86.39Z"/><path class="cls-1" d="M82.28,116.06H37.86a6.25,6.25,0,0,1-6.26-6.25h0a6.25,6.25,0,0,1,6.26-6.25H82.28a6.25,6.25,0,0,1,6.26,6.25h0A6.25,6.25,0,0,1,82.28,116.06Z"/></g></g></g></g></g></svg>' . esc_html__( 'Docs', 'woo-crm-integration-for-zoho' ) . '</a>',
				// 'demo'     => '<a href="' . esc_url( 'https://demo.wpswings.com/woo-crm-integration-for-zoho/?utm_source=wpswings-crmzoho-woo&utm_medium=woo-backend&utm_campaign=demo' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Demo', 'woo-crm-integration-for-zoho' ) . '"><svg class="wciz-info-img" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 169.34 169.34"><defs><style>.cls-1{fill:#1e1e1e;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="Layer_2-2" data-name="Layer 2"><g id="Layer_1-2-2" data-name="Layer 1-2"><g id="dEMO"><path class="cls-1" d="M84.67,169.34a84.67,84.67,0,1,1,84.67-84.67A84.76,84.76,0,0,1,84.67,169.34ZM84.67,12a72.67,72.67,0,1,0,72.67,72.67A72.75,72.75,0,0,0,84.67,12Z"/><path class="cls-1" d="M84.67,145.83a61.16,61.16,0,1,1,61.16-61.16A61.23,61.23,0,0,1,84.67,145.83Zm0-110.32a49.16,49.16,0,1,0,49.16,49.16A49.22,49.22,0,0,0,84.67,35.51Z"/><path class="cls-1" d="M74.18,107.67a4.81,4.81,0,0,1-4.81-4.81V66.49a4.8,4.8,0,0,1,7.21-4.17l31.5,18.18a4.82,4.82,0,0,1,0,8.34L76.58,107A4.82,4.82,0,0,1,74.18,107.67Z"/></g></g></g></g></g></svg>' . esc_html__( 'Demo', 'woo-crm-integration-for-zoho' ) . '</a>',
				// 'video'    => '<a href="' . esc_url( 'https://youtu.be/hug0X9OXxBw' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'woo-crm-integration-for-zoho' ) . '"><img src="' . esc_url( WOO_CRM_INTEGRATION_ZOHO_URL ) . 'assets/src/images/YouTube.svg" class="wciz-info-img" alt="support image">' . esc_html__( 'Video', 'woo-crm-integration-for-zoho' ) . '</a>',
				// 'support'  => '<a href="' . esc_url( 'https://wpswings.com/submit-query/?utm_source=wpswings-crmzoho-woo&utm_medium=woo-backend&utm_campaign=submit-query' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'woo-crm-integration-for-zoho' ) . '"><svg class="wciz-info-img" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 177.04 169.03"><defs><style>.cls-1{fill:#1e1e1e;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="Layer_2-2" data-name="Layer 2"><g id="Layer_1-2-2" data-name="Layer 1-2"><g id="Support"><path class="cls-1" d="M106.85,158.44v-8.8h20.44c5.46,0,12.11-4.43,14.2-9.48l.52-1.24c2.64-6.33,4.31-10.53,4.79-11.81v-3.06l3.07,1h5.7v3c0,1,0,1.31-5.4,14.31l-.52,1.25c-3.48,8.36-13.29,14.9-22.34,14.9Z"/><path class="cls-1" d="M144.55,78.18V65.1A50.8,50.8,0,0,0,94,14.2H83.09A50.79,50.79,0,0,0,32.5,65.1V78.18H18.34V65.1C18.34,29.2,47.39,0,83.09,0H94c35.71,0,64.75,29.2,64.75,65.1V78.18Z"/><path class="cls-1" d="M154.58,141.69a16.13,16.13,0,0,1-4.42-.59c-6.82-1.89-11.24-8.11-11.24-15.85V83.76c0-7.74,4.4-14,11.24-15.85a16.51,16.51,0,0,1,4.42-.58,18.84,18.84,0,0,1,4.36.52c1.5.15,9.1,1.19,14,7.28,5.49,6.87,5.49,51.88,0,58.75-4.88,6.13-12.48,7.15-14,7.28A18.32,18.32,0,0,1,154.58,141.69Z"/><path class="cls-1" d="M22.46,141.69a18.32,18.32,0,0,1-4.36-.53C16.6,141,9,140,4.11,133.88-1.37,127-1.37,82,4.11,75.14,9,69,16.61,68,18.1,67.85a18.84,18.84,0,0,1,4.36-.52,16.51,16.51,0,0,1,4.42.58C33.7,69.81,38.12,76,38.12,83.76v41.49c0,7.74-4.41,13.95-11.24,15.85A16.52,16.52,0,0,1,22.46,141.69Z"/><path class="cls-1" d="M87.12,169a15.91,15.91,0,0,1,0-31.82H100A15.91,15.91,0,0,1,100,169H87.12Z"/></g></g></g></g></g></svg>' . esc_html__( 'Support', 'woo-crm-integration-for-zoho' ) . '</a>',
				// 'services' => '<a href="' . esc_url( 'https://wpswings.com/wordpress-woocommerce-solutions/?utm_source=wpswings-crmzoho-services+&utm_medium=crmzoho-woo-backend+&utm_campaign=woo-services-page' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'woo-crm-integration-for-zoho' ) . '"><svg class="wciz-info-img" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 200 200"><defs><style>.cls-1{fill:#1e1e1e;}</style></defs><g id="Services"><path class="cls-1" d="M174.64,50.44c-4.53,4.53-8.86,8.86-13.09,13.19-1.31,1.41-2.62,1.71-4.43,1.11-4.63-1.71-9.46-3.12-14.09-4.93-1.11-.4-2.42-1.51-2.82-2.72-1.81-4.73-3.22-9.56-4.93-14.29-.5-1.71-.2-3.02,1.01-4.23,2.72-2.62,5.33-5.34,8.05-7.95,1.71-1.61,3.52-3.12,5.33-4.73-.2-.3-.4-.6-.6-.91-2.21,.3-4.53,.3-6.74,.81-14.5,3.72-22.85,16.51-20.13,31.2,.4,2.01-.1,3.42-1.61,4.93-7.65,7.55-15.1,15.1-22.75,22.65-10.02,9.89-19.94,19.89-29.85,29.88-1.5,1.51-2.99,3.02-4.49,4.54-1.25,1.27-2.53,2.87-4.45,3.01-1.45,.1-2.87-.55-4.32-.6-1.21-.04-2.43-.14-3.64-.12-2.45,.04-4.88,.44-7.21,1.17-4.54,1.43-8.64,4.11-11.83,7.63-5.69,6.27-8.27,15.02-6.47,23.34,3.22,14.7,17.41,24.06,31.91,21.04,14.29-3.02,23.65-16.91,20.84-31.51-.5-2.42,.1-3.83,1.71-5.34,19.23-19.23,38.45-38.35,57.58-57.58,1.91-2.01,3.72-2.62,6.34-2.01,12.58,2.42,25.57-5.23,29.59-17.51,1.01-3.12,1.71-6.34,1.11-10.07ZM52.34,162.08c-7.55,0-13.69-6.14-13.69-13.69s6.14-13.69,13.69-13.69,13.59,6.14,13.59,13.69-6.04,13.69-13.59,13.69Zm20.03-31.51c-.91-.91-2.11-2.01-3.12-3.02l58.58-58.58c1.01,1.11,2.11,2.21,3.12,3.12-19.43,19.43-38.96,38.96-58.58,58.48Z"/><path class="cls-1" d="M139.92,117.28c-4.23,2.21-7.85,2.01-10.77-.81-3.02-2.82-3.22-6.34-1.01-11.17-1.31-1.41-2.62-2.82-3.52-3.93-7.75,7.65-15.3,15.2-22.75,22.65,1.11,1.21,2.42,2.52,3.72,3.93,4.43-2.42,7.95-2.32,10.97,.5,3.32,3.12,3.52,6.95,1.21,11.17,10.27,10.27,20.53,20.53,30.4,30.5,7.45-7.45,15-14.9,22.35-22.25-10.07-10.07-20.33-20.33-30.6-30.6Zm-11.78,13.59c1.01-.91,2.11-2.01,3.22-3.02,8.66,8.76,17.52,17.62,26.37,26.47-.91,.91-2.01,2.11-3.12,3.12-8.86-8.86-17.62-17.72-26.47-26.57Z"/><path class="cls-1" d="M32.09,44.18c1.79,4.08,4.51,6.44,9.01,6.79,3.18,.25,5.5,2.08,7.72,4.33,9.79,9.96,19.72,19.78,29.59,29.67,1.87,1.87,3.65,3.83,5.48,5.76,2.58-2.52,4.81-4.69,7.1-6.92-.62-.66-1.19-1.29-1.8-1.89-11.25-11.25-22.46-22.55-33.81-33.71-2.16-2.13-3.57-4.42-4.1-7.35-.26-1.45-.53-2.93-1.03-4.31-.32-.88-.89-1.89-1.65-2.35-4.41-2.65-8.88-5.19-13.4-7.64-.57-.31-1.81-.11-2.31,.33-1.99,1.75-3.76,3.74-5.7,5.54-.94,.87-.89,1.58-.28,2.6,1.8,3.01,3.78,5.96,5.18,9.15Z"/><path class="cls-1" d="M174.03,153.51c-6.79,6.73-13.55,13.43-20.26,20.08,5.27,2.19,12.15,.62,16.56-3.85,4.57-4.65,5.75-12.01,3.71-16.23Z"/></g></svg>' . esc_html__( 'Services', 'woo-crm-integration-for-zoho' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}

	// replace get_post_meta with wciz_zoho_get_meta_data
	function wciz_zoho_get_meta_data( $id, $key, $v ) {
		if ( 'shop_order' === OrderUtil::get_order_type( $id ) && OrderUtil::custom_orders_table_usage_is_enabled() ) {
			// HPOS usage is enabled.
			$order    = wc_get_order( $id );
			if ( '_customer_user' == $key ) {
				$meta_val = $order->get_customer_id();
				return $meta_val;
			}
			$meta_val = $order->get_meta( $key );
			return $meta_val;
		} else {
			// Traditional CPT-based orders are in use.
			$meta_val = get_post_meta( $id, $key, $v );
			return $meta_val; 
		}
	}

	// replace update_post_meta with wciz_zoho_update_meta_data
	function wciz_zoho_update_meta_data( $id, $key, $value ) {
		if ( 'shop_order' === OrderUtil::get_order_type( $id ) && OrderUtil::custom_orders_table_usage_is_enabled() ) {
			// HPOS usage is enabled.
			$order = wc_get_order( $id );
			$order->update_meta_data( $key, $value );
			$order->save();
		} else {
			// Traditional CPT-based orders are in use.
			update_post_meta( $id, $key, $value );
		}
	}

	function wciz_override_checkout_email_field( $fields ) {
		global $woocommerce;
		if (isset($woocommerce->session)) {
			 $email = $woocommerce->session->get('c_email');
			if (!is_null($email)) {
				$fields['billing']['billing_email']['default'] = $email;
			}
		}
		return $fields;
	}
	add_filter( 'woocommerce_checkout_fields' , 'wciz_override_checkout_email_field' );

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function wciz_woo_crm_integration_for_zoho() {

		$plugin   = new Woo_Crm_Integration_For_Zoho();
		$migrator = new Integration_With_Woo_Zoho_Order_Handler();
		$plugin->run();
	}


	wciz_woo_crm_integration_for_zoho();
} else {

	add_action( 'admin_init', 'wciz_zoho_plugin_activation_failure' );

	/**
	 * Deactivate this plugin.
	 *
	 * @since    1.0.0
	 */
	function wciz_zoho_plugin_activation_failure() {

		// To hide Plugin activated notice.
		if ( ! empty( $_GET['activate'] ) ) {  //phpcs:ignore 
			unset( $_GET['activate'] ); //phpcs:ignore 
		}
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	// Add admin error notice.
	add_action( 'admin_notices', 'wciz_zoho_activation_admin_notice' );

	/**
	 * This function is used to display plugin activation error notice.
	 *
	 * @since    1.0.0
	 */
	function wciz_zoho_activation_admin_notice() {
		?>

		<?php if ( ! wciz_is_required_plugin_active() ) { ?>

			<div class="notice notice-error is-dismissible wciz-notice">
				<p><strong><?php esc_html_e( 'WooCommerce', 'woo-crm-integration-for-zoho' ); ?></strong><?php esc_html_e( ' is not activated, Please activate WooCommerce first to activate ', 'woo-crm-integration-for-zoho' ); ?><strong><?php esc_html_e( 'CRM Integration For Zoho', 'woo-crm-integration-for-zoho' ); ?></strong><?php esc_html_e( '.', 'woo-crm-integration-for-zoho' ); ?></p>
			</div>
			<?php
		}
	}
}