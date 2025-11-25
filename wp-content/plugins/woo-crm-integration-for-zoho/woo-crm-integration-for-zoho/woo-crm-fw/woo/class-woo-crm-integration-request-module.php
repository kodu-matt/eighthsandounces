<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */

use Automattic\WooCommerce\Utilities\OrderUtil;

class Woo_Crm_Integration_Request_Module
{

	/**
	 *  The instance of this class.
	 *
	 * @since  1.0.0
	 * @var    string    $_instance    The instance of this class.
	 */
	private static $_instance; //phpcs:ignore

	/**
	 *  The instance of this class.
	 *
	 * @since  1.0.0
	 * @var    string    $feed id to record  The feed to sync.
	 */
	public static $feed_id;

	/**
	 *  The instance of this class.
	 *
	 * @since  1.0.0
	 * @var    string    $feed id to record  The feed to sync.
	 */
	private $crm_connect_manager;

	/**
	 * Main Woo_Crm_Integration_Request_Module Instance.
	 *
	 * Ensures only one instance of Woo_Crm_Integration_Request_Module is loaded.
	 *
	 * @since 1.0.0
	 * @return Woo_Crm_Integration_Request_Module - Main instance.
	 */
	public static function get_instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Main Woo_Crm_Integration_Request_Module Instance.
	 *
	 * Ensures only one instance of Woo_Crm_Integration_Request_Module is loaded.
	 *
	 * @since  1.0.0
	 */
	public function __construct()
	{
		$this->crm_connect_manager = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
	}

	/**
	 * If instant sync is required.
	 *
	 * @since  1.0.0
	 * @return string|array  Feeds ids.
	 */
	public static function is_instant_sync_required()
	{
		// Get Zoho Instance.
		$zoho = Woo_Crm_Integration_For_Zoho_Fw::get_instance();

		//phpcs:disable
		$current_function = function_exists('debug_backtrace') &&
			! empty(debug_backtrace()[1]['function']) ?
			debug_backtrace()[1]['function'] :
			'';
		//phpcs:enable
		if (! empty($current_function)) {
			$args = array(
				'hook'     => current_action(),
				'callback' => $current_function,
			);
		} else {

			// Get from WP filter.
			global $wp_filter;
			if (! empty(! empty($wp_filter[current_action()]->callbacks))) {
				$callbacks = $wp_filter[current_action()]->callbacks;
				foreach ($callbacks as $key => $_callback) {
					$function = ! empty($_callback) ?
						$_callback :
						array();

					foreach ($_callback  as $key => $function) {
						if (false !== strpos(wp_json_encode($function), 'crm_connect_manager')) {
							foreach ($function as $key => $func) {
								$current_function = ! empty($func[1])
									? $func[1] :
									false;
								break;
							}
						}
					}
				}
			}

			$args = array(
				'hook'     => current_action(),
				'callback' => $current_function,
				'params'   => $zoho->get_hook_param_count(current_action()),
			);
		}

		// Get all probable Feeds callbacks.
		$hookable_callbacks = $zoho->get_hook_requests_for_feed();

		$feed_id = self::get_feed_id_by_request($args);

		if (! empty($feed_id) && is_array($feed_id)) {

			if (1 === count($feed_id)) {

				$event = $zoho->get_feed($feed_id[0], 'feed_event');
				// Check if sync is need to be instantly synced or not.
				if ('send_manually' === $event) {
					if (isset($_POST['meta_box_nonce'])) {
						if (wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['meta_box_nonce'])), 'meta_box_nonce')) {
							$maybe_perform_manual_sync = is_admin() && ! empty($_POST['wciz_manual_sync'])
								&& true == $_POST['wciz_manual_sync'] ? true : false; //phpcs:ignore
							if (true == $maybe_perform_manual_sync) { //phpcs:ignore
								return $feed_id[0];
							} else {
								return false;
							}
						}
					}
				} else {
					return $feed_id[0];
				}
			} else {

				foreach ($feed_id as $key => $f_id) {
					$event = $zoho->get_feed($f_id, 'feed_event');
					// Check if sync is need to be instantly synced or not.
					if ('send_manually' === $event) {
						if (isset($_POST['meta_box_nonce'])) {
							if (wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['meta_box_nonce'])), 'meta_box_nonce')) {
								$maybe_perform_manual_sync = is_admin() && ! empty($_POST['wciz_manual_sync'])
									&& true == $_POST['wciz_manual_sync'] ? true : false; //phpcs:ignore
								if (false == $maybe_perform_manual_sync) { //phpcs:ignore
									unset($feed_id[$key]);
								}
							}
						}
					}
				}
				return $feed_id;
			}
		} else {
			return false;
		}
	}

	/**
	 * If Feed id is not justified due to multiple hooks.
	 *
	 * @param array $args The args for hook and callback.
	 *
	 * @since  1.0.0
	 * @return bool  true|false.
	 */
	public static function get_feed_id_by_request($args = array())
	{
		// Get Zoho Instance.
		$zoho               = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$hookable_callbacks = $zoho->get_hook_requests_for_feed();

		if (empty($hookable_callbacks)) {
			return;
		}

		if (empty($args)) {
			$hooks = array(
				'woocommerce_order_status_pending',
				'woocommerce_order_status_failed',
				'woocommerce_order_fully_refunded',
				'woocommerce_order_partially_refunded',
				'woocommerce_order_status_cancelled',
				'woocommerce_order_status_completed',
				'woocommerce_order_status_on-hold',
				'woocommerce_order_status_processing',
			);

			$args = array(
				'hook'     => $hooks,
				'callback' => 'shop_order_status_changed',
			);
		}

		$feed_ids = array();
		foreach ($hookable_callbacks as $feed_id => $feed_args) {
			if ($feed_args == $args) {  //phpcs:ignore
				if (! empty($feed_id)) {
					$feed_ids[] = $feed_id;
				}
			} elseif (is_array($feed_args['hook']) && in_array($args['hook'], $feed_args['hook'])) {
				$feed_ids[] = $feed_id;
			}
		}
		return $feed_ids;
	}

	/**
	 * Prepare/Fire a request whenever a product is created/updated.
	 *
	 * This hooks works only for admin side create/update.
	 *
	 * @param string $post_id Woo Object post ID.
	 * @param string $post    Woo Object post.
	 * @param int    $feed_id    Feed id.
	 *
	 * @since  1.0.0
	 * @return null
	 */
	public function create_and_update_product($post_id, $post, $feed_id = '')
	{
		// // Return if doing autosave.
		// if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		// 	return;
		// }

		// // Return if doing ajax :: Quick edits.
		// if (defined('DOING_AJAX') && DOING_AJAX) {
		// 	return;
		// }

		// if (! isset($_POST['meta_box_nonce'])) {
		// 	return;
		// }

		// if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['meta_box_nonce'])), 'meta_box_nonce')) {
		// 	return;
		// }

		// // Return on post trash, quick-edit, new post.
		// if (empty($_POST['action']) || 'editpost' != $_POST['action']) { //phpcs:ignore
		// 	return;
		// }

		// if (isset($_POST['post_status']) && 'draft' == $_POST['post_status']) {
		// 	return;
		// }

		
		update_post_meta($post_id, 'wciz_zoho_allow_background_syncing', 'yes');

		if (! Woo_Crm_Integration_For_Zoho_Admin::is_instant_sync_enable()) {
			return;
		}

		$cache_id = wp_cache_get('wciz_zoho_product_synced');

		if ($cache_id == $post_id) {
			return;
		}

		$feed_id = empty($feed_id) ? self::is_instant_sync_required() : false;

		if (! empty($feed_id) && is_numeric($feed_id)) {

			$zoho       = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
			$feed_title = $zoho->get_feed_title($feed_id);

			$record_type = $this->crm_connect_manager->get_feed(
				$feed_id,
				'crm_object'
			);

			// Get product.
			$product      = wc_get_product($post_id);
			$bulk_request = array();
			$bulk_ids     = array();

			if ('variable' === $product->get_type() || 'variable-subscription' === $product->get_type()) {
				$cpt_instance    = new Woo_Crm_Integration_For_Zoho_Cpt();
				$product_feed_id = get_option('wciz_woo_zoho_default_Products_feed_id', '');
				if (! empty($product_feed_id)) {
					$is_default_feed = $cpt_instance->get_feed_data($product_feed_id, 'default_feed', '');
					if ($is_default_feed) {
						$sync_parent_product = $cpt_instance->get_feed_data($product_feed_id, 'sync_parent_product', 'no');
						if ('yes' === $sync_parent_product) {
							$request    = $this->crm_connect_manager->get_request(
								$post->post_type,
								$feed_id,
								$post_id
							);
							$bulk_ids[] = $post_id;
							/**
							 * Filters the value of product request array.
							 *
							 * @since 1.0.0
							 *
							 * @param mixed $request request.
							 * @param mixed $post_id prod_id.
							 * @param mixed $record_type record_type.
							 */
							$request    = apply_filters(
								'woo_crm_woo_zoho_product_request',
								$request,
								$post_id,
								$record_type
							);
							array_push($bulk_request, $request);
						}
					}
				}

				$available_variations = $product->get_children();
				if (! empty($available_variations) && is_array($available_variations)) {
					foreach ($available_variations as $variation_id) {
						$variation = wc_get_product($variation_id);
						if (! $variation || ! $variation->exists()) {
							continue;
						}
						$request = $this->crm_connect_manager->get_request(
							$post->post_type,
							$feed_id,
							$variation_id
						);

						$bulk_ids[] = $variation_id;
						/** 
						 * Filters the value product record array.
						 *
						 * @since 1.0.0
						 *
						 * @param mixed $request request.
						 * @param mixed $variation_id variable_prod_id.
						 * @param mixed $record_type record_type.
						 */
						$request = apply_filters(
							'woo_crm_woo_zoho_product_request',
							$request,
							$variation_id,
							$record_type
						);

						global $wpdb;

						$results = $wpdb->get_var(
							$wpdb->prepare(
								"
						SELECT order_items.order_id
						FROM {$wpdb->prefix}woocommerce_order_items AS order_items
						LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta
							ON order_items.order_item_id = itemmeta.order_item_id
						WHERE order_items.order_item_type = 'line_item'
						AND itemmeta.meta_key = '_product_id'
						AND itemmeta.meta_value = %d
						ORDER BY order_items.order_id DESC
						LIMIT 1
						",
								$post_id
							)
						);


						if ($results) {
							$order = wc_get_order($results);
							foreach ($order->get_items() as $item_id => $item) {
								$product_name = $item->get_name();

								if ($product_name) {
									$request['Product_Name'] = $product_name;
								}
							}
						}

						array_push($bulk_request, $request);
					}
				}
			} else {
				$request = $this->crm_connect_manager->get_request(
					$post->post_type,
					$feed_id,
					$post_id
				);

				/**
				 * Filters the value of product requesy array.
				 *
				 * @since 1.0.0
				 *
				 * @param mixed $request request.
				 * @param mixed $post_id post_id.
				 * @param mixed $record_type record_type.
				 */
				$request = apply_filters(
					'woo_crm_woo_zoho_product_request',
					$request,
					$post_id,
					$record_type
				);

				global $wpdb;

				$results = $wpdb->get_var(
					$wpdb->prepare(
						"
						SELECT order_items.order_id
						FROM {$wpdb->prefix}woocommerce_order_items AS order_items
						LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta
							ON order_items.order_item_id = itemmeta.order_item_id
						WHERE order_items.order_item_type = 'line_item'
						AND itemmeta.meta_key = '_product_id'
						AND itemmeta.meta_value = %d
						ORDER BY order_items.order_id DESC
						LIMIT 1
						",
						$post_id
					)
				);


				if ($results) {
					$order = wc_get_order($results);
					foreach ($order->get_items() as $item_id => $item) {
						$product_name = $item->get_name();

						if ($product_name) {
							$request['Product_Name'] = $product_name;
						}
					}
				}
			}

			$request = ! empty($bulk_request) ?
				$bulk_request :
				$request;

			$feed_title = $zoho->get_feed_title($feed_id);

			$log_data = array(
				'woo_id'     => $post_id,
				'bulk_ids'   => $bulk_ids,
				'feed_id'    => $feed_id,
				'woo_object' => $post->post_type,
				'feed_title' => $feed_title,
			);

			$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
			$result                   = $crm_integration_zoho_api->create_single_record(
				$record_type,
				$request,
				! empty($bulk_request) ? true : false,
				$log_data
			);

			$ajax_module = new Woo_Crm_Integration_For_Zoho_Ajax();

			if ('Products' == $record_type) {
				$add_product_image = $this->crm_connect_manager->get_feed($feed_id, 'add_product_image');
				if ('yes' === $add_product_image) {
					// May be sync product images.
					$product_ids = ! empty($bulk_ids) ? $bulk_ids : array($post_id);
					$ajax_module->may_be_sync_product_images($feed_id, $product_ids);
				}
			}
			$associated_feeds = $this->get_associated_feeds($feed_id);

			/**
			 * Trigger associated Feeds.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed  $post_id  Woo post id.
			 * @param mixed $associated_feeds associated feeds.
			 * @param mixed shop_order_updated callback.
			 */
			do_action(
				'wciz_zoho_crm_trigger_associated_feeds',
				$post_id,
				$associated_feeds,
				'create_and_update_product'
			);
		}
		wp_cache_set('wciz_zoho_product_synced', $post_id);
	}

	/**
	 * Trigger feed related to product object.
	 *
	 * @param string $post_id Woo Object post ID.
	 * @param string $feed_id Feed id.
	 */
	public function trigger_product_related_feed($post_id, $feed_id)
	{

		// Get product.
		$product = wc_get_product($post_id);
		if (! $product) {
			return false;
		}

		if (! empty($feed_id)) {

			$zoho       = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
			$feed_title = $zoho->get_feed_title($feed_id);

			$record_type = $this->crm_connect_manager->get_feed(
				$feed_id,
				'crm_object'
			);

			$bulk_request = array();
			$bulk_ids     = array();

			if ('variable' === $product->get_type() || 'variable-subscription' === $product->get_type()) {

				$cpt_instance    = new Woo_Crm_Integration_For_Zoho_Cpt();
				$product_feed_id = get_option('wciz_woo_zoho_default_Products_feed_id', '');
				if (! empty($product_feed_id)) {
					$is_default_feed = $cpt_instance->get_feed_data($product_feed_id, 'default_feed', '');
					if ($is_default_feed) {
						$sync_parent_product = $cpt_instance->get_feed_data($product_feed_id, 'sync_parent_product', 'no');
						if ('yes' === $sync_parent_product) {
							$request    = $this->crm_connect_manager->get_request(
								$product->post_type,
								$feed_id,
								$post_id
							);
							$bulk_ids[] = $post_id;
							/**
							 * Filters the value product request array.
							 *
							 * @since 1.0.0
							 *
							 * @param mixed $request request.
							 * @param mixed $post_id product_id.
							 * @param mixed $record_type record_type.
							 */
							$request    = apply_filters(
								'woo_crm_woo_zoho_product_request',
								$request,
								$post_id,
								$record_type
							);
							array_push($bulk_request, $request);
						}
					}
				}

				$available_variations = $product->get_children();
				if (! empty($available_variations) && is_array($available_variations)) {
					foreach ($available_variations as $variation_id) {
						$variation = wc_get_product($variation_id);
						if (! $variation || ! $variation->exists()) {
							continue;
						}
						$request = $this->crm_connect_manager->get_request(
							$product->post_type,
							$feed_id,
							$variation_id
						);

						$bulk_ids[] = $variation_id;

						/**
						 * Filters the value of product request array.
						 *
						 * @since 1.0.0
						 *
						 * @param mixed $request request.
						 * @param mixed $items_id item_id.
						 * @param mixed $record_type record_type.
						 */
						$request = apply_filters(
							'woo_crm_woo_zoho_product_request',
							$request,
							$variation_id,
							$record_type
						);

						array_push($bulk_request, $request);
					}
				}
			} elseif ('variation' === $product->get_type() || 'subscription_variation' === $product->get_type()) {
				$request = $this->crm_connect_manager->get_request(
					'product',
					$feed_id,
					$post_id
				);
				/**
				 * Filters the value of product request array.
				 *
				 * @since 1.0.0
				 *
				 * @param mixed $request request.
				 * @param mixed $variation item_id.
				 * @param mixed $record_type record_type.
				 */
				$request = apply_filters(
					'woo_crm_woo_zoho_product_request',
					$request,
					$post_id,
					$record_type
				);
			} else {
				$request = $this->crm_connect_manager->get_request(
					$product->post_type,
					$feed_id,
					$post_id
				);
				/**
				 * Filters the value of product request array.
				 *
				 * @since 1.0.0
				 *
				 * @param mixed $request request.
				 * @param mixed $items_id item_id.
				 * @param mixed $record_type record_type.
				 */
				$request = apply_filters(
					'woo_crm_woo_zoho_product_request',
					$request,
					$post_id,
					$record_type
				);
			}

			$request = ! empty($bulk_request) ?
				$bulk_request :
				$request;

			$feed_title = $zoho->get_feed_title($feed_id);

			$log_data = array(
				'woo_id'     => $post_id,
				'bulk_ids'   => $bulk_ids,
				'feed_id'    => $feed_id,
				'woo_object' => $product->post_type,
				'feed_title' => $feed_title,
			);

			$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
			$result                   = $crm_integration_zoho_api->create_single_record(
				$record_type,
				$request,
				! empty($bulk_request) ? true : false,
				$log_data
			);

			$ajax_module = new Woo_Crm_Integration_For_Zoho_Ajax();
			if ('Products' === $record_type) {
				$add_product_image = $this->crm_connect_manager->get_feed($feed_id, 'add_product_image');
				if ('yes' === $add_product_image) {
					// May be sync product images.
					$product_ids = ! empty($bulk_ids) ? $bulk_ids : array($post_id);
					$ajax_module->may_be_sync_product_images($feed_id, $product_ids);
				}
			}
		}
	}

	/**
	 * Prepare/Fire a request whenever a shop order is manually synced.
	 *
	 * This hooks works only for admin side create/update.
	 *
	 * @param string $post_id Woo Object post ID.
	 * @param string $post    Woo Object post.
	 * @param string $update  Woo Object is updated or not.
	 *
	 * @since  1.0.0
	 * @return null
	 */
	public function create_and_update_shop_order($post_id, $post, $update)
	{
		// Return if doing autosave.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// Return if doing ajax :: Quick edits.
		if (defined('DOING_AJAX') && DOING_AJAX) {
			return;
		}

		if (! isset($_POST['meta_box_nonce'])) {
			return;
		}

		if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['meta_box_nonce'])), 'meta_box_nonce')) {
			return;
		}

		// Return on post trash, quick-edit, new post.
		if (empty($_POST['action']) || 'editpost' != $_POST['action']) { //phpcs:ignore
			return;
		}
		if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {

			$order = wc_get_order($post_id);

			$order->update_meta_data('wciz_zoho_allow_background_syncing', 'yes');

			$order->save();
		} else {
			update_post_meta($post_id, 'wciz_zoho_allow_background_syncing', 'yes');
		}

		if (! Woo_Crm_Integration_For_Zoho_Admin::is_instant_sync_enable()) {
			return;
		}

		$feed_id = self::is_instant_sync_required();

		if (! empty($feed_id) && is_numeric($feed_id)) {

			$zoho = Woo_Crm_Integration_For_Zoho_Fw::get_instance();

			$request = $this->crm_connect_manager->get_request(
				'shop_order',
				$feed_id,
				$post_id
			);

			$record_type = $this->crm_connect_manager->get_feed(
				$feed_id,
				'crm_object'
			);
			/**
			 * Filters the value of order request array.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed $request request.
			 * @param mixed $post_id post_id.
			 * @param mixed $record_type record_type.
			 */
			$request = apply_filters(
				'woo_crm_woo_zoho_new_order_request',
				$request,
				$post_id,
				$record_type
			);

			$feed_title = $zoho->get_feed_title($feed_id);

			$log_data = array(
				'woo_id'     => $post_id,
				'feed_id'    => $feed_id,
				'woo_object' => $post->post_type,
				'feed_title' => $feed_title,
			);

			$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
			$result                   = $crm_integration_zoho_api->create_single_record(
				$record_type,
				$request,
				false,
				$log_data
			);
		}
	}

	/**
	 * Prepare/Fire a request whenever a shop order is created/updated.
	 *
	 * @param string $post_id - Woo Object post ID.
	 * @param string $post    - Woo Object post.
	 *
	 * @since  1.0.0
	 */
	public function shop_order_updated($post_id, $post = array())
	{
		$wc_order = wc_get_order($post_id);

		// Get the ID of the selected checkout page from WooCommerce settings.
		$checkout_page_id = get_option('woocommerce_checkout_page_id');

		// Get the content of the checkout page.
		$checkout_page_content = get_post_field('post_content', $checkout_page_id);


		// Check if the content contains a class associated with the block editor.
		if (strpos($checkout_page_content, 'wp-block-woocommerce-checkout') === false) {
			$wciz_traditional_checkout = true;
		} else {
			$wciz_traditional_checkout = false;
		}



		if (! $wciz_traditional_checkout) {
			if ('checkout-draft' == $wc_order->get_status()) {
				return;
			}
		}


		if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {

			$order = wc_get_order($post_id);

			$order->update_meta_data('wciz_zoho_allow_background_syncing', 'yes');

			$order->save();
		} else {
			update_post_meta($post_id, 'wciz_zoho_allow_background_syncing', 'yes');
		}

		if (! Woo_Crm_Integration_For_Zoho_Admin::is_instant_sync_enable()) {
			return;
		}

		$feed_id = self::is_instant_sync_required();

		if (! empty($feed_id) && is_numeric($feed_id)) {

			$this->perform_shop_order_sync($feed_id, $post_id);
			$associated_feeds = $this->get_associated_feeds($feed_id);

			/**
			 * Trigger associated Feeds.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed  $post_id  Woo post id.
			 * @param mixed $associated_feeds associated feeds.
			 * @param mixed shop_order_updated callback.
			 */
			do_action(
				'wciz_zoho_crm_trigger_associated_feeds',
				$post_id,
				$associated_feeds,
				'shop_order_updated'
			);
		} elseif (! empty($feed_id) && is_array($feed_id)) {
			foreach ($feed_id as $key => $f_id) {
				$this->perform_shop_order_sync($f_id, $post_id);
				$associated_feeds = $this->get_associated_feeds($f_id);

				/**
				 * Trigger associated Feeds.
				 *
				 * @since 1.0.0
				 *
				 * @param mixed  $post_id  Woo post id.
				 * @param mixed $associated_feeds associated feeds.
				 * @param mixed shop_order_updated callback.
				 */
				do_action(
					'wciz_zoho_crm_trigger_associated_feeds',
					$post_id,
					$associated_feeds,
					'shop_order_updated'
				);
			}
		}
	}


	public function subscription_created($subscription)
	{

		$post_id = $subscription->get_data()['parent_id'];
		if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {

			$order = wc_get_order($post_id);
			if ($order) {

				$order->update_meta_data('mwb_salesforce_allow_background_syncing', 'yes');

				$order->save();
			} else {
				update_post_meta($post_id, 'mwb_salesforce_allow_background_syncing', 'yes');
			}
		} else {
			update_post_meta($post_id, 'mwb_salesforce_allow_background_syncing', 'yes');
		}

		if (! Woo_Crm_Integration_For_Zoho_Admin::is_instant_sync_enable()) {
			return;
		}

		$feed_id = self::is_instant_sync_required();

		if (empty($feed_id)) {
			return;
		}

		$feeds_array = array();

		if (is_numeric($feed_id)) {
			$feeds_array[] = $feed_id;
		} elseif (is_array($feed_id)) {
			sort($feed_id);
			$feeds_array = $feed_id;
		}

		foreach ($feeds_array as $key => $f_id) {

			// Sync current feed.
			$this->perform_shop_order_sync($f_id, $post_id);
			$associated_feeds = $this->get_associated_feeds($f_id);

			/**
			 * Trigger associated Feeds.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed  $post_id  Woo post id.
			 * @param mixed $associated_feeds associated feeds.
			 * @param mixed shop_order_updated callback.
			 */
			do_action(
				'wciz_zoho_crm_trigger_associated_feeds',
				$post_id,
				$associated_feeds,
				'shop_order_updated'
			);
		}
	}


	public function subscription_status_change($subscription_id, $old_status, $new_status)
	{
		if (function_exists('wcs_get_subscription')) {
			$subscription = wcs_get_subscription($subscription_id);

			// Check if the subscription object exists
			if ($subscription) {
				// Get the parent ID
				$post_id = $subscription->get_parent_id();

				if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {

					$order = wc_get_order($post_id);
					if ($order) {

						$order->update_meta_data('mwb_salesforce_allow_background_syncing', 'yes');

						$order->save();
					} else {
						update_post_meta($post_id, 'mwb_salesforce_allow_background_syncing', 'yes');
					}
				} else {
					update_post_meta($post_id, 'mwb_salesforce_allow_background_syncing', 'yes');
				}

				if (! Woo_Crm_Integration_For_Zoho_Admin::is_instant_sync_enable()) {
					return;
				}

				$feed_id = self::is_instant_sync_required();

				if (empty($feed_id)) {
					return;
				}

				$feeds_array = array();

				if (is_numeric($feed_id)) {
					$feeds_array[] = $feed_id;
				} elseif (is_array($feed_id)) {
					sort($feed_id);
					$feeds_array = $feed_id;
				}

				foreach ($feeds_array as $key => $f_id) {

					// Sync current feed.
					$this->perform_shop_order_sync($f_id, $post_id);
					$associated_feeds = $this->get_associated_feeds($f_id);

					/**
					 * Trigger associated Feeds.
					 *
					 * @since 1.0.0
					 *
					 * @param mixed  $post_id  Woo post id.
					 * @param mixed $associated_feeds associated feeds.
					 * @param mixed shop_order_updated callback.
					 */
					do_action(
						'wciz_zoho_crm_trigger_associated_feeds',
						$post_id,
						$associated_feeds,
						'shop_order_updated'
					);
				}
			}
		}
	}

	public function subscription_update($subscription_id, $subscription)
	{
		if (function_exists('wcs_get_subscription')) {
			$subscription = wcs_get_subscription($subscription_id);

			// Check if the subscription object exists
			if ($subscription) {
				// Get the parent ID
				$post_id = $subscription->get_parent_id();

				if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {

					$order = wc_get_order($post_id);
					if ($order) {

						$order->update_meta_data('mwb_salesforce_allow_background_syncing', 'yes');

						$order->save();
					} else {
						update_post_meta($post_id, 'mwb_salesforce_allow_background_syncing', 'yes');
					}
				} else {
					update_post_meta($post_id, 'mwb_salesforce_allow_background_syncing', 'yes');
				}

				if (! Woo_Crm_Integration_For_Zoho_Admin::is_instant_sync_enable()) {
					return;
				}

				$feed_id = self::is_instant_sync_required();

				if (empty($feed_id)) {
					return;
				}

				$feeds_array = array();

				if (is_numeric($feed_id)) {
					$feeds_array[] = $feed_id;
				} elseif (is_array($feed_id)) {
					sort($feed_id);
					$feeds_array = $feed_id;
				}

				foreach ($feeds_array as $key => $f_id) {

					// Sync current feed.
					$this->perform_shop_order_sync($f_id, $post_id);
					$associated_feeds = $this->get_associated_feeds($f_id);

					/**
					 * Trigger associated Feeds.
					 *
					 * @since 1.0.0
					 *
					 * @param mixed  $post_id  Woo post id.
					 * @param mixed $associated_feeds associated feeds.
					 * @param mixed shop_order_updated callback.
					 */
					do_action(
						'wciz_zoho_crm_trigger_associated_feeds',
						$post_id,
						$associated_feeds,
						'shop_order_updated'
					);
				}
			}
		}
	}

	public function sync_membership_data($object, $args)
	{

		if (isset($args['is_update']) && true === $args['is_update']) {

			$post_id = $args['user_membership_id'];

			$feed_id = self::is_instant_sync_required();

			if (empty($feed_id)) {
				return;
			}

			$feeds_array = array();

			if (is_numeric($feed_id)) {
				$feeds_array[] = $feed_id;
			} elseif (is_array($feed_id)) {
				sort($feed_id);
				$feeds_array = $feed_id;
			}

			foreach ($feeds_array as $key => $f_id) {

				$this->perform_shop_order_sync($f_id, $post_id);
				$associated_feeds = $this->get_associated_feeds($f_id);

				/**
				 * Trigger associated Feeds.
				 *
				 * @since 1.0.0
				 *
				 * @param mixed  $post_id  Woo post id.
				 * @param mixed $associated_feeds associated feeds.
				 * @param mixed shop_order_updated callback.
				 */
				do_action(
					'wciz_zoho_crm_trigger_associated_feeds',
					$post_id,
					$associated_feeds,
					'shop_order_updated'
				);
			}
		}
	}

	/**
	 * Do the shop order update by feed id.
	 *
	 * @param string $feed_id Feed id.
	 * @param string $post_id Order id.
	 *
	 * @since  1.0.0
	 */
	public function perform_shop_order_sync($feed_id = false, $post_id = false)
	{
		if (! empty($feed_id) && is_numeric($feed_id)) {

			$post = get_post($post_id);

			$record_type = $this->crm_connect_manager->get_feed(
				$feed_id,
				'crm_object'
			);

			$post_type = OrderUtil::get_order_type($post_id);
			if (empty($post_type)) {
				$post_type           = get_post($post_id)->post_type;
			}

			if ('wc_user_membership' != $post_type) {
				if (!empty($post->post_parent) && 0 != $post->post_parent) {
					return;
				}

				if (!empty($post->post_type) && 'shop_subscription' == $post->post_type) {
					return;
				}

				if (0 == $post_id) {
					return;
				}

				$order = wc_get_order($post_id);
				$email = $order->get_billing_email();
				if (get_option('zoho_cart_data_' . $email)) {
					delete_option('zoho_cart_data_' . $email);
				}

				if (! empty($order->get_data()['refunded_by'])) {
					return;
				}

				$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
				$crm_object = $framework->get_feed($feed_id, 'crm_object');
				if ((false !== strpos($crm_object, 'mber') || false !== strpos($crm_object, 'ship'))) {
					$order = wc_get_order($post_id);
					if (!empty($order->get_items())) {
						$plans_exists = array();
						foreach ($order->get_items() as $item_key => $item_value) {
							$prod_id = $item_value->get_data()['product_id'];
							$mem_plans = $this->get_product_membership_plans($prod_id);
							if (!empty($mem_plans)) {
								$plans_exists = $mem_plans;
							}
						}
						if (empty($plans_exists)) {
							return;
						}
					}
				}
			}

			// Check if feed is contact feed.
			if ('Contacts' == $record_type) {
				if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {
					$order = wc_get_order($post_id);
					if (empty($order->get_data()['refunded_by'])) {
						$customer_id = $order->get_customer_id();
					} else {
						return;
					}
				} else {
					$customer_id     = get_post_meta($post_id, '_customer_user', true);
				}
				$only_guest_sync = get_post_meta($feed_id, 'contact_only_guest_feed', true);
				if ('yes' == $only_guest_sync && 0 != $customer_id) {
					$contact_customer_feed = get_post_meta($feed_id, 'contact_customer_feed', true);
					if (! empty($contact_customer_feed)) {
						$zoho_contact_id = get_user_meta($customer_id, 'wciz_zoho_feed_' . $contact_customer_feed . '_association', true);
						if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {

							$order = wc_get_order($post_id);

							$order->update_meta_data('wciz_zoho_feed_' . $feed_id . '_association', $zoho_contact_id);

							$order->save();
						} else {
							update_post_meta($post_id, 'wciz_zoho_feed_' . $feed_id . '_association', $zoho_contact_id);
						}
					}

					return;
				}
			}

			if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {

				// HPOS enabled
				$post_type = OrderUtil::get_order_type($post_id);
				if (empty($post_type)) {
					$post_type           = get_post($post_id)->post_type;
				}
			} else {
				$post_type           = get_post($post_id)->post_type;
			}

			$zoho      = Woo_Crm_Integration_For_Zoho_Fw::get_instance();

			$request = $this->crm_connect_manager->get_request(
				$post_type,
				$feed_id,
				$post_id
			);
			/**
			 * Filters the value of new order array.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed $request request.
			 * @param mixed $post_id post_id.
			 * @param mixed $record_type record_type.
			 */
			$request = apply_filters(
				'woo_crm_woo_zoho_new_order_request',
				$request,
				$post_id,
				$record_type
			);
			
			if ( 'yes' == get_option( 'wciz_zoho_enable_discount_in_subtotal', 'no' ) ) { 
				$order = wc_get_order( $post_id );
				$request['Discount'] = $order->discount_total;
				$request['Grand_Total'] = $order->total;
				$request['Sub_Total'] = $order->subtotal;
			}
			// echo '<pre>';print_r( $request );echo '</pre>';
			$log_data = array(
				'woo_id'     => $post_id,
				'feed_id'    => $feed_id,
				'woo_object' => $post_type,
				'feed_title' => $zoho->get_feed_title($feed_id),
			);

			$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();

			$result                   = $crm_integration_zoho_api->create_single_record(
				$record_type,
				$request,
				false,
				$log_data
			);
		}
	}

	public function get_product_membership_plans($product_id, $return = 'object')
	{

		$product_membership_plans = array();
		if (function_exists('wc_memberships_get_membership_plans')) {
			$membership_plans         = wc_memberships_get_membership_plans();

			if (! empty($membership_plans)) {

				foreach ($membership_plans as $plan) {

					if ($plan->has_product($product_id)) {
						$product_membership_plans[] = 'object' === $return ? $plan : $plan->get_id();
					}
				}
			}
		}

		return $product_membership_plans;
	}

	/**
	 * Prepare/Retrieve a request full of all the post entities specified.
	 *
	 * Retrieves the bulk data in crm request format.
	 *
	 * @param string $object_type      Woo Object post type.
	 * @param string $paged            Woo pagination.
	 * @param string $feed_id          Feed id.
	 * @param string $is_oneclick_sync Is one click sync or not.
	 * @param array  $object_ids       Array of object ids.
	 *
	 * @since  1.0.0
	 * @return array $bulk_request
	 */
	public function get_bulk_data(
		$object_type,
		$paged = 1,
		$feed_id = false,
		$is_oneclick_sync = false,
		$object_ids = array()
	) {
		if (false === $feed_id) {
			return;
		}

		$total = 0;
		$bulk_ids = array();
		if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {
			if ('product' != $object_type) {
				foreach ($object_ids as $k => $v) {
					$ord_id = $v;
					if ('shop_order' === OrderUtil::get_order_type($ord_id)) {
						$wc_order = wc_get_order($ord_id);
						$checkout_page_id = get_option('woocommerce_checkout_page_id');

						// Get the content of the checkout page.
						$checkout_page_content = get_post_field('post_content', $checkout_page_id);

						// Check if the content contains a class associated with the block editor.
						if (strpos($checkout_page_content, 'wp-block-woocommerce-checkout') === false) {
							$wciz_traditional_checkout = true;
						} else {
							$wciz_traditional_checkout = false;
						}

						if (! $wciz_traditional_checkout) {
							if ('checkout-draft' == $wc_order->get_status()) {
								unset($object_ids[$k]);
							}
						}
					}
				}
			}
		}

		$zoho        = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$record_type = $zoho->get_feed($feed_id, 'crm_object');
		if ('Sales_Orders' === $record_type || 'Products' === $record_type) {
			$limit = 30;
		} else {
			$limit = 50;
		}

		switch ($object_type) {

			case 'users':
				$args = array(
					'include' => $object_ids,
				);

				$users        = get_users($args);
				$bulk_request = array();
				$bulk_ids     = array();
				$total        = count($users);

				foreach ($users as $user) {

					$request = $this->crm_connect_manager->get_request(
						$object_type,
						$feed_id,
						$user->ID
					);

					if ('Accounts' === $record_type) {
						/**
						 * Filters the value of account array.
						 *
						 * @since 1.0.0
						 *
						 * @param mixed $request request.
						 * @param mixed $user_id user_id.
						 * @param mixed $record_type record_type.
						 */
						$request = apply_filters(
							'woo_crm_woo_zoho_account_request',
							$request,
							$user->ID,
							$record_type
						);
					} else {
						/**
						 * Filters the value of user account array.
						 *
						 * @since 1.0.0
						 *
						 * @param mixed $request request.
						 * @param mixed $user_id user_id.
						 * @param mixed $record_type record_type.
						 */
						$request = apply_filters(
							'woo_crm_woo_zoho_user_request',
							$request,
							$user->ID,
							$record_type
						);
					}

					array_push($bulk_request, $request);
					$bulk_ids[] = $user->ID;
				}
				/**
				 * Filters the value of bulk request array.
				 *
				 * @since 1.0.0
				 *
				 * @param mixed $bulk_request request.
				 * @param mixed $bulk_ids bulk_id.
				 */
				$bulk_request = apply_filters(
					'woo_crm_woo_zoho_user_bulk_request',
					$bulk_request,
					$bulk_ids
				);

				break;

			case 'shop_order':
				if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {
					$args = array(
						'type'      => $object_type,
						'status'    => 'any',
						'limit' => $limit,
						'paged' => $paged,
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key' => 'wciz_zoho_feed_' . $feed_id . '_association',
								'compare' => 'NOT EXISTS',
							),
						),
					);
				} else {
					$args = array(
						'post_type'      => $object_type,
						'posts_per_page' => $limit,
						'post_status'    => 'any',
						'paged'          => $paged,
						'meta_query'     => array( //phpcs:ignore
							'relation' => 'AND',
							array(
								'key'     => 'wciz_zoho_feed_' . $feed_id . '_association',
								'compare' => 'NOT EXISTS',
							),
						),
					);
				}

				if (false == $is_oneclick_sync) { //phpcs:ignore
					unset($args['meta_query']);
				}

				if (! empty($object_ids)) {
					if (! Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {
						$args = array(
							'post_type'      => $object_type,
							'post_status'    => 'any',
							'post__in'       => $object_ids,
							'posts_per_page' => count($object_ids),
						);
					} else {
						$args['post__in'] = $object_ids;
					}
				}

				try {

					if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {
						$query_result = wc_get_orders($args);
						foreach ($query_result as $k_key => $v_key) {
							$ord_id_new = $v_key->get_id();
							if ('shop_order' === OrderUtil::get_order_type($ord_id_new)) {
								$wc_order = wc_get_order($ord_id_new);
								$checkout_page_id = get_option('woocommerce_checkout_page_id');

								// Get the content of the checkout page.
								$checkout_page_content = get_post_field('post_content', $checkout_page_id);

								// Check if the content contains a class associated with the block editor.
								if (strpos($checkout_page_content, 'wp-block-woocommerce-checkout') === false) {
									$wciz_traditional_checkout = true;
								} else {
									$wciz_traditional_checkout = false;
								}

								if (! $wciz_traditional_checkout) {
									if ('checkout-draft' == $wc_order->get_status()) {
										unset($query_result[$k_key]);
									}
								}
							}
						}
						$args1 = $args;
						unset($args1['limit']);
						unset($args1['paged']);
						$args1['paginate'] = true;
						$query_for_total_count = wc_get_orders($args1);
						$total = $query_for_total_count->total;

						$bulk_request = array();
						$bulk_ids     = array();
						if (!empty($query_result)) {
							foreach ($query_result as $k => $v) {

								$request = $this->crm_connect_manager->get_request(
									$object_type,
									$feed_id,
									$v->get_id()
								);
								/**
								 * Filters the value of new order request array.
								 *
								 * @since 1.0.0
								 *
								 * @param mixed $request request.
								 * @param mixed  item_id.
								 * @param mixed $record_type record_type.
								 */
								$request = apply_filters(
									'woo_crm_woo_zoho_new_order_request',
									$request,
									$v->get_id(),
									$record_type
								);

								array_push($bulk_request, $request);
								$bulk_ids[] = $v->get_id();
								/**
								 * Filters the value of bulk request array.
								 *
								 * @since 1.0.0
								 *
								 * @param mixed $bulk_request request.
								 * @param mixed $bulk_ids bulk_ids.
								 */
								apply_filters(
									'woo_crm_woo_zoho_update_order_bulk_request',
									$bulk_request,
									$bulk_ids
								);
							}
						}
					} else {
						$order_query = new WP_Query($args);

						if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {
							foreach ($order_query as $k_key => $v_key) {
								$ord_id_new = $v_key->get_id();
								if ('shop_order' === OrderUtil::get_order_type($ord_id_new)) {
									$wc_order = wc_get_order($ord_id_new);
									$checkout_page_id = get_option('woocommerce_checkout_page_id');

									// Get the content of the checkout page.
									$checkout_page_content = get_post_field('post_content', $checkout_page_id);

									// Check if the content contains a class associated with the block editor.
									if (strpos($checkout_page_content, 'wp-block-woocommerce-checkout') === false) {
										$wciz_traditional_checkout = true;
									} else {
										$wciz_traditional_checkout = false;
									}

									if (! $wciz_traditional_checkout) {
										if ('checkout-draft' == $wc_order->get_status()) {
											unset($order_query[$k_key]);
										}
									}
								}
							}
						}

						$bulk_request = array();
						$bulk_ids     = array();

						$total = $order_query->found_posts;

						while ($order_query->have_posts()) :
							$order_query->the_post();
							$request = $this->crm_connect_manager->get_request(
								$object_type,
								$feed_id,
								get_the_ID()
							);
							/**
							 * Filters the value of new order request array.
							 *
							 * @since 1.0.0
							 *
							 * @param mixed $request request.
							 * @param mixed  item_id.
							 * @param mixed $record_type record_type.
							 */
							$request = apply_filters(
								'woo_crm_woo_zoho_new_order_request',
								$request,
								get_the_ID(),
								$record_type
							);

							array_push($bulk_request, $request);
							$bulk_ids[] = get_the_ID();
							/**
							 * Filters the value of bulk request array.
							 *
							 * @since 1.0.0
							 *
							 * @param mixed $bulk_request request.
							 * @param mixed $bulk_ids bulk_ids.
							 */
							apply_filters(
								'woo_crm_woo_zoho_update_order_bulk_request',
								$bulk_request,
								$bulk_ids
							);
						endwhile;
					}
				} catch (\Throwable $th) {
					$msg = $th->getMessage();
					if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_woocommerce')) {
						echo esc_html($msg);
					}
				}
				break;
			case 'product':
				$args = array(
					'post_type'      => $object_type,
					'posts_per_page' => $limit,
					'post_status'    => 'all',
					'paged'          => $paged,
					'meta_query'     => array(  //phpcs:ignore
						'relation' => 'AND',
						array(
							'key'     => 'wciz_zoho_feed_' . $feed_id . '_association',
							'compare' => 'NOT EXISTS',
						),
					),
				);

				if (false == $is_oneclick_sync) { //phpcs:ignore
					unset($args['meta_query']);
				}

				$args = array(
					'post_type'      => $object_type,
					'post_status'    => 'any',
					'post__in'       => $object_ids,
					'posts_per_page' => count($object_ids),
				);

				try {

					$product_query = new WP_Query($args);
					$bulk_request  = array();
					$bulk_ids      = array();

					$total = $product_query->found_posts;

					while ($product_query->have_posts()) :

						$product_query->the_post();

						// Get product.
						$product = wc_get_product(get_the_ID());

						if ('variable' === $product->get_type() || 'variable-subscription' === $product->get_type()) {

							$cpt_instance    = new Woo_Crm_Integration_For_Zoho_Cpt();
							$product_feed_id = get_option('wciz_woo_zoho_default_Products_feed_id', '');
							if (! empty($product_feed_id)) {
								$is_default_feed = $cpt_instance->get_feed_data($product_feed_id, 'default_feed', '');
								if ($is_default_feed) {
									$sync_parent_product = $cpt_instance->get_feed_data($product_feed_id, 'sync_parent_product', 'no');
									if ('yes' === $sync_parent_product) {
										$request = $this->crm_connect_manager->get_request(
											$object_type,
											$feed_id,
											get_the_ID()
										);
										/**
										 * Filters the value of product request array.
										 *
										 * @since 1.0.0
										 *
										 * @param mixed $request request.
										 * @param mixed $items_id item_id.
										 * @param mixed $record_type record_type.
										 */
										$request = apply_filters(
											'woo_crm_woo_zoho_product_request',
											$request,
											get_the_ID(),
											$record_type
										);

										array_push($bulk_request, $request);
										$bulk_ids[] = get_the_ID();
									}
								}
							}

							$available_variations = $product->get_children();
							if (! empty($available_variations) && is_array($available_variations)) {
								foreach ($available_variations as $variation_id) {
									$variation = wc_get_product($variation_id);
									if (! $variation || ! $variation->exists()) {
										continue;
									}
									$request = $this->crm_connect_manager->get_request(
										$object_type,
										$feed_id,
										$variation_id
									);
									/**
									 * Filters the value of product request array.
									 *
									 * @since 1.0.0
									 *
									 * @param mixed $request request.
									 * @param mixed $variation_id variation_id.
									 * @param mixed $record_type record_type.
									 */
									$request = apply_filters(
										'woo_crm_woo_zoho_product_request',
										$request,
										$variation_id,
										$record_type
									);

									array_push($bulk_request, $request);
									if (false == $is_oneclick_sync) {
										$bulk_ids[] = $variation_id;
									} elseif (! get_post_meta($variation_id, 'wciz_zoho_feed_' . $feed_id . '_association', true)) {
										$bulk_ids[] = $variation_id;
									}
								}
							}
						} else {
							$request = $this->crm_connect_manager->get_request(
								$object_type,
								$feed_id,
								get_the_ID()
							);
							/**
							 * Filters the value of product request array.
							 *
							 * @since 1.0.0
							 *
							 * @param mixed $request request.
							 * @param mixed $variation_id variation_id.
							 * @param mixed $record_type record_type.
							 */
							$request = apply_filters(
								'woo_crm_woo_zoho_product_request',
								$request,
								get_the_ID(),
								$record_type
							);

							array_push($bulk_request, $request);
							$bulk_ids[] = get_the_ID();
						}
						/**
						 * Filters the value of bulk request array.
						 *
						 * @since 1.0.0
						 *
						 * @param mixed $bulk_request bulk_request.
						 * @param mixed $variation_id variation_id.
						 * @param mixed $record_type record_type.
						 */
						apply_filters(
							'woo_crm_woo_zoho_update_order_bulk_request',
							$bulk_request,
							$bulk_ids
						);

					endwhile;
				} catch (Throwable $th) {
					$msg = $th->getMessage();
					if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_woocommerce')) {
						echo esc_html($msg);
					}
				}
				break;

			default:
				$bulk_request = false;
				break;
		}

		$total_batch    = ceil($total / $limit);
		$current_offset = $paged;
		$result = array(
			'total_posts' => $total,
			'total_batch' => $total_batch,
			'offset'      => ++$paged,
			'request'     => $bulk_request,
			'bulk_ids'    => $bulk_ids,
		);

		return $result;
	}


	/**
	 * Prepare/Retrieve a request full of all the post entities specified.
	 *
	 * Retrieves the bulk data in crm request format.
	 *
	 * @param string $order_id  Woo Object post ID.
	 * @param string $refund_id Woo Refund post ID.
	 *
	 * @since 1.0.0
	 */
	public function shop_order_refunded($order_id, $refund_id)
	{
		$this->shop_order_updated($order_id);
	}

	/**
	 * Prepare/Retrieve a request full of all the post entities specified.
	 *
	 * Retrieves the bulk data in crm request format.
	 *
	 * @param string $order_id                 Woo Object post ID.
	 * @param string $maybe_refund_id_or_order Woo Object for or refund ID.
	 * @since 1.0.0
	 */
	public function shop_order_status_changed($order_id, $maybe_refund_id_or_order = false)
	{

		$wc_order = wc_get_order($order_id);
		if ('pending' == $wc_order->get_status()) {
			return;
		}
		if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {

			$order = wc_get_order($order_id);

			$order->update_meta_data('wciz_zoho_allow_background_syncing', 'yes');

			$order->save();
		} else {
			update_post_meta($order_id, 'wciz_zoho_allow_background_syncing', 'yes');
		}

		if (! Woo_Crm_Integration_For_Zoho_Admin::is_instant_sync_enable()) {
			return;
		}

		$feeds = self::get_feed_id_by_request();

		// Sort so that we might get deal before the sales orders.
		sort($feeds);
		if (! empty($feeds) && is_array($feeds)) {
			foreach ($feeds as $key => $feed_id) {
				$this->perform_shop_order_sync($feed_id, $order_id);
				$associated_feeds = $this->get_associated_feeds($feed_id);

				/**
				 * Trigger associated Feeds.
				 *
				 * @since 1.0.0
				 *
				 * @param mixed  $post_id  Woo post id.
				 * @param mixed $associated_feeds associated feeds.
				 * @param mixed shop_order_updated callback.
				 */
				do_action(
					'wciz_zoho_crm_trigger_associated_feeds',
					$order_id,
					$associated_feeds,
					'shop_order_updated'
				);
			}
		}
	}

	/**
	 * Get object ids required for sync.
	 *
	 * @param string $feed_id   Feed id.
	 * @param string $sync_type bulk sync/one click sync.
	 *
	 * @since  1.0.0
	 * @return array $object_ids
	 */
	public function get_feed_required_object_ids($feed_id, $sync_type, $from_time = '', $to_time = '')
	{
		$zoho        = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$record_type = $zoho->get_feed($feed_id, 'crm_object');
		$woo_object = 'shop_order';
		switch ($record_type) {
			case 'Sales_Orders':
			case 'Deals':
			case 'Invoices':
			case 'Quotes':
				$woo_object = 'shop_order';
				break;
			case 'Contacts':
			case 'Leads':
			case 'Accounts':
				$woo_object = $this->get_woo_object_based_on_field_mapping($feed_id);
				break;
			case 'Products':
				$woo_object = 'product';
				break;
		}
		$meta_key = '';

		if ('one_click_sync' === $sync_type) {
			$meta_key = 'wciz_zoho_feed_' . $feed_id . '_association';
		}

		$only_guest_sync = 'no';

		if ('Contacts' == $record_type) {

			$only_guest_sync = get_post_meta($feed_id, 'contact_only_guest_feed', true);
		}

		$object_ids = $this->get_woo_objects($woo_object, 'ids', $meta_key, $only_guest_sync, $from_time, $to_time);
		if ('Products' == $record_type) {
			$option_key = 'wciz_woo_zoho_' . $feed_id . '_' . $sync_type . '_sync_ids';
		} else if (('' != $to_time) && ('' != $from_time)) {
			$option_key = 'wciz_woo_zoho_' . $feed_id . '_' . $sync_type . '_sync_ids_from_' . $from_time . '_to_' . $to_time;
		} elseif ($to_time) {
			$option_key = 'wciz_woo_zoho_' . $feed_id . '_' . $sync_type . '_sync_ids_to_' . $to_time;
		} elseif ($from_time) {
			$option_key = 'wciz_woo_zoho_' . $feed_id . '_' . $sync_type . '_sync_ids_from_' . $from_time;
		} else {
			$option_key = 'wciz_woo_zoho_' . $feed_id . '_' . $sync_type . '_sync_ids';
		}
		update_option($option_key, $object_ids);
		return $object_ids;
	}

	/**
	 * Query object ids.
	 *
	 * @param string $post_type   Post type.
	 * @param string $return_type Return type.
	 * @param string $meta_key    Meta key to search.
	 * @param string $only_guest_sync    Only guest order sync is required.
	 *
	 * @since  1.0.0
	 * @return array              Array if object ids.
	 */
	public function get_woo_objects($post_type, $return_type = 'ids', $meta_key = '', $only_guest_sync = 'no', $from_time = '', $to_time = '')
	{

		$order_statuses = wc_get_order_statuses();
		unset($order_statuses['wc-checkout-draft']);
		$order_statuses = array_keys($order_statuses);
		$post_status = ('product' === $post_type) ? 'all' : $order_statuses;

		if (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {

			if ('shop_order' == $post_type) {
				$args = array(
					'type'      => $post_type,
					'limit'     => -1,
					'status'    => $post_status,
					'return'    => $return_type,
				);
				if (('' != $to_time) && ('' != $from_time)) {
					$args['date_created'] = $from_time . '...' . $to_time;
				} elseif ($to_time) {
					$args['date_created'] = '<=' . $to_time;
				} elseif ($from_time) {
					$args['date_created'] = '>=' . $from_time;
				}
			} else {
				$args = array(
					'post_type'      => $post_type,
					'posts_per_page' => -1,
					'post_status'    => $post_status,
					'fields'         => $return_type,
				);
			}
		} elseif ('shop_order' == $post_type) {
			$args = array(
				'post_status'    => 'any', // Get all order statuses
				'posts_per_page' => -1, // Get all matching orders
				'return'    => 'ids',
			);
			if (('' != $to_time) && ('' != $from_time)) {
				$args['date_created'] = $from_time . '...' . $to_time;
			} elseif ($to_time) {
				$args['date_created'] = '<=' . $to_time;
			} elseif ($from_time) {
				$args['date_created'] = '>=' . $from_time;
			}
		} else {
			$args = array(
				'post_type'      => $post_type,
				'posts_per_page' => -1,
				'post_status'    => $post_status,
				'fields'         => $return_type,
			);
		}

		$meta_query = array();

		if ('yes' == $only_guest_sync) {
			$meta_query[] = array(
				'key'     => '_customer_user',
				'value'   => '0',
				'compare' => '=',
			);
		}

		if ('' !== $meta_key) {

			$meta_query[] = array( //phpcs:ignore
				'relation' => 'OR',
				array(
					'key'     => $meta_key,
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => $meta_key,
					'compare' => '=',
					'value'   => '',
				),
			);
		}

		if (! empty($meta_query)) {
			$args['meta_query'] = $meta_query;
		}

		if ('users' == $post_type) {

			unset($args['post_type']);
			unset($args['posts_per_page']);
			unset($args['post_status']);
			$args['fields'] = 'ID';
			$posts          = get_users($args);
		} elseif (Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled()) {


			if ('shop_order' == $post_type) {
				$posts = wc_get_orders($args);
			} else {
				$posts = get_posts($args);
				if ('' !== $meta_key) {
					if (!empty($posts)) {
						foreach ($posts as $post_key => $post_value) {
							$prod = wc_get_product($post_value);
							if ('variable' === $prod->get_type()) {
								$available_variations = $prod->get_children();
								if (! empty($available_variations) && is_array($available_variations)) {
									foreach ($available_variations as $variation_id) {
										if (get_post_meta($variation_id, $meta_key, true)) {
											unset($posts[$post_key]);
										}
									}
								}
							}
						}
					}
				} elseif (!empty($posts)) {
					$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
					$all_feeds        = $framework->get_all_feed();
					$default_feeds    = array();
					$additional_feeds = array();
					$product_feeds    = array();
					$request_module   = self::get_instance();
					if (! empty($all_feeds) && is_array($all_feeds)) {
						foreach ($all_feeds as $key => $feed_id) {
							$is_default_feed = $framework->get_feed($feed_id, 'default_feed');
							if ($is_default_feed) {
								array_push($default_feeds, $feed_id);
							} else {
								array_push($additional_feeds, $feed_id);
							}
						}
					}
					if (! empty($default_feeds)) {
						foreach ($default_feeds as $key => $feed_id) {
							$record_type = $framework->get_feed($feed_id, 'crm_object');
							if ('Products' === $record_type) {
								$product_feeds['0'] = $feed_id;
							}
						}
					}
					if (! empty($additional_feeds)) {
						foreach ($additional_feeds as $key => $feed_id) {
							$woo_object = $request_module->get_woo_object_based_on_field_mapping($feed_id);
							if ('product' === $woo_object) {
								array_push($product_feeds, $feed_id);
							}
						}
					}

					if (!empty($product_feeds)) {
						ksort($product_feeds);

						$feed_id = $product_feeds[0];
					} else {
						$feed_id = 0;
					}
					foreach ($posts as $post_key => $post_value) {
						$prod = wc_get_product($post_value);
						if ('variable' === $prod->get_type()) {
							$available_variations = $prod->get_children();
							$count_prod = 1;
							if (! empty($available_variations) && is_array($available_variations)) {
								foreach ($available_variations as $variation_id) {
									$posts[] = $variation_id;
									if ((empty(get_post_meta($feed_id, 'sync_parent_product', true)) || 'no' == get_post_meta($feed_id, 'sync_parent_product', true)) && 1 == $count_prod) {
										unset($posts[count($posts) - 1]);
										$posts = array_values($posts);
										$count_prod++;
									}
								}
							}
						}
					}
				}
			}
		} elseif ('shop_order' == $post_type) {
			$posts = wc_get_orders($args);
		} else {
			$posts = get_posts($args);
			if ('' !== $meta_key) {
				if (!empty($posts)) {
					foreach ($posts as $post_key => $post_value) {
						$prod = wc_get_product($post_value);
						if ('variable' === $prod->get_type()) {
							$available_variations = $prod->get_children();
							if (! empty($available_variations) && is_array($available_variations)) {
								foreach ($available_variations as $variation_id) {
									if (get_post_meta($variation_id, $meta_key, true)) {
										unset($posts[$post_key]);
									}
								}
							}
						}
					}
				}
			} elseif (!empty($posts)) {
				$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
				$all_feeds        = $framework->get_all_feed();
				$default_feeds    = array();
				$additional_feeds = array();
				$product_feeds    = array();
				$request_module   = self::get_instance();
				if (! empty($all_feeds) && is_array($all_feeds)) {
					foreach ($all_feeds as $key => $feed_id) {
						$is_default_feed = $framework->get_feed($feed_id, 'default_feed');
						if ($is_default_feed) {
							array_push($default_feeds, $feed_id);
						} else {
							array_push($additional_feeds, $feed_id);
						}
					}
				}
				if (! empty($default_feeds)) {
					foreach ($default_feeds as $key => $feed_id) {
						$record_type = $framework->get_feed($feed_id, 'crm_object');
						if ('Products' === $record_type) {
							$product_feeds['0'] = $feed_id;
						}
					}
				}
				if (! empty($additional_feeds)) {
					foreach ($additional_feeds as $key => $feed_id) {
						$woo_object = $request_module->get_woo_object_based_on_field_mapping($feed_id);
						if ('product' === $woo_object) {
							array_push($product_feeds, $feed_id);
						}
					}
				}

				if (!empty($product_feeds)) {
					ksort($product_feeds);

					$feed_id = $product_feeds[0];
				} else {
					$feed_id = 0;
				}
				foreach ($posts as $post_key => $post_value) {
					$prod = wc_get_product($post_value);
					if ('variable' === $prod->get_type()) {
						$available_variations = $prod->get_children();
						$count_prod = 1;
						if (! empty($available_variations) && is_array($available_variations)) {
							foreach ($available_variations as $variation_id) {
								$posts[] = $variation_id;
								if ((empty(get_post_meta($feed_id, 'sync_parent_product', true)) || 'no' == get_post_meta($feed_id, 'sync_parent_product', true)) && 1 == $count_prod) {
									unset($posts[count($posts) - 1]);
									$posts = array_values($posts);
									$count_prod++;
								}
							}
						}
					}
				}
			}
		}
		return $posts;
	}

	/**
	 * Create and update wp user when profile is updated.
	 *
	 * @param int   $user_id   Updated or created user id.
	 * @param array $old_user_data Old user data.
	 *
	 * @since  1.0.0
	 */
	public function create_and_update_contact($user_id, $old_user_data)
	{

		if (! $user_id) {
			return;
		}

		update_user_meta($user_id, 'wciz_zoho_allow_background_syncing', 'yes');

		if (! Woo_Crm_Integration_For_Zoho_Admin::is_instant_sync_enable()) {
			return;
		}

		$cache_id = wp_cache_get('wciz_woo_zoho_user_synced');

		if ($cache_id == $user_id) {
			return;
		}

		$feed_id = self::is_instant_sync_required();

		$feeds_array = array();

		if (is_numeric($feed_id)) {
			$feeds_array[] = $feed_id;
		} elseif (is_array($feed_id)) {
			sort($feed_id);
			$feeds_array = $feed_id;
		}

		$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();

		foreach ($feeds_array as $key => $f_id) {
			$this->perform_wp_user_sync($f_id, $user_id);
			// Get associated feeds.
			$associated_feeds = $this->get_associated_feeds($f_id);

			$crm_object = $framework->get_feed($f_id, 'crm_object');

			if ('Accounts' == $crm_object || 'Contacts' == $crm_object || 'Leads' == $crm_object) {
				/**
				 * Trigger associated Feeds.
				 *
				 * @since 1.0.0
				 *
				 * @param mixed  $post_id  Woo post id.
				 * @param mixed $associated_feeds associated feeds.
				 * @param mixed shop_order_updated callback.
				 */
				do_action(
					'wciz_zoho_crm_trigger_associated_feeds',
					$user_id,
					$associated_feeds,
					'create_and_update_contact'
				);
			}
		}

		wp_cache_set('wciz_woo_zoho_user_synced', $user_id);
	}

	/**
	 * Perfrom WP User sync according to feed.
	 *
	 * @param  int $feed_id Feed id.
	 * @param  int $user_id User id.
	 *
	 * @since  1.0.0
	 */
	public function perform_wp_user_sync($feed_id, $user_id)
	{

		$request = $this->crm_connect_manager->get_request(
			'users',
			$feed_id,
			$user_id
		);

		$record_type = $this->crm_connect_manager->get_feed(
			$feed_id,
			'crm_object'
		);

		if ('Accounts' === $record_type) {
			/**
			 * Filters the value of bulk request array.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed $request request.
			 * @param mixed $user_id user_id.
			 * @param mixed $record_type record_type.
			 */
			$request = apply_filters(
				'woo_crm_woo_zoho_account_request',
				$request,
				$user_id,
				$record_type
			);
		} else {
			/**
			 * Filters the value of bulk request array.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed $request request.
			 * @param mixed $user_id user_id.
			 * @param mixed $record_type record_type.
			 */
			$request = apply_filters(
				'woo_crm_woo_zoho_user_request',
				$request,
				$user_id,
				$record_type
			);
		}

		$feed_title = $this->crm_connect_manager->get_feed_title($feed_id);

		$log_data = array(
			'woo_id'     => $user_id,
			'feed_id'    => $feed_id,
			'woo_object' => 'users',
			'feed_title' => $feed_title,
		);

		$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
		$result   = $zoho_api->create_single_record(
			$record_type,
			array($request),
			true,
			$log_data
		);
	}

	/**
	 * Get woo source object based on field mapping.
	 *
	 * @param  int $feed_id Feed id.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_woo_object_based_on_field_mapping($feed_id)
	{

		$mapping = $this->crm_connect_manager->get_feed($feed_id);

		$object_count = array(
			'users'      => 0,
			'shop_order' => 0,
			'product'    => 0,
		);

		if (! empty($mapping)) {
			foreach ($mapping as $key => $field) {
				$field_key = ('standard_field' == $field['field_type']) ? $field['field_value'] : $field['custom_value'];
				if (false !== strpos($field_key, 'users')) {
					$object_count['users']++;
				} elseif (false !== strpos($field_key, 'product')) {
					$object_count['product']++;
				} else {
					$object_count['shop_order']++;
				}
			}
		}
		$max_objects = array_keys($object_count, max($object_count));
		$woo_object  = isset($max_objects[0]) ? $max_objects[0] : 'shop_order';
		return $woo_object;
	}

	/**
	 * Check if filter exists in feed.
	 *
	 * @param     int $feed_id    Feed ID.
	 * @since     1.0.0
	 * @return    bool|array
	 */
	public function maybe_check_filter($feed_id = '')
	{

		if (empty($feed_id)) {
			return;
		}

		if ('yes' == get_post_meta($feed_id, 'wciz-zoho-enable-filters', true)) { // phpcs:ignore
			$meta = get_post_meta($feed_id, 'wciz-zoho-condition-field', true);

			if (! empty($meta) && is_array($meta) && count($meta) > 0) {
				return $meta;
			}
		}

		return false;
	}

	/**
	 * Validate order, products with feeds filter conditions.
	 *
	 * @param     array $filters    An array of filter data.
	 * @param     array $data       Request data.
	 * @since     1.0.0
	 * @return    bool
	 */
	public function wciz_zoho_validate_filter($filters = array(), $data = array())
	{

		if (! empty($filters) && is_array($filters)) {

			foreach ($filters as $or_key => $or_filters) {
				$result = true;

				if (is_array($or_filters)) {

					foreach ($or_filters as $and_key => $and_filter) {
						if ('-1' == $and_filter['field'] || '-1' == $and_filter['option']) { // phpcs:ignore
							return array('result' => false);
						}

						$post_field = $and_filter['field'];
						$feed_value = ! empty($and_filter['value']) ? $and_filter['value'] : '';
						$entry_val  = $this->wciz_zoho_integration_get_entry_values($post_field, $data);
						$result     = $this->is_value_allowed($feed_value, $and_filter['option'], $entry_val);

						if (false == $result) { // phpcs:ignore
							break;
						}
					}
				}

				if (true === $result) {
					break;
				}
			}
		}

		return $result;
	}

	/**
	 * Verify and get entered field values.
	 *
	 * @param     string $field      Post field whose value to verify.
	 * @param     array  $entries    An array of data.
	 * @since     1.0.0
	 * @return    mixed              value of the field
	 */
	public function wciz_zoho_integration_get_entry_values($field, $entries)
	{

		$value = false;

		if (! empty($field) || ! empty($entries) || is_array($entries)) {

			if (isset($entries[$field])) {
				$value = $entries[$field];

				if (is_array($value) && ! empty($value['value'])) {
					$value = $value['value'];
				} elseif (! is_array($value)) {
					$value = maybe_unserialize($value);
				}
			}
		}

		if (is_array($value) && 1 == count($value)) { // phpcs:ignore
			$value = implode(' ', $value);
		}

		return $value;
	}

	/**
	 * Validate form values with conditions.
	 *
	 * @param    string $feed_value     Value to compare with entry value.
	 * @param    string $option_type    Filter conditon type.
	 * @param    string $form_value     Entry value .
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	public function is_value_allowed($feed_value, $option_type = false, $form_value = false)
	{

		if (false == $option_type) { // phpcs:ignore
			return;
		}

		$time   = current_time('timestamp'); // phpcs:ignore
		$result = false;
		if (false !== $form_value) { // phpcs:ignore

			switch ($option_type) {

				case 'exact_match':
					if ($feed_value === $form_value) { // phpcs:ignore
						$result = true;
					}
					break;

				case 'no_exact_match':
					if ($feed_value !== $form_value) { // phpcs:ignore
						$result = true;
					}
					break;

				case 'contains':
					if (false !== strpos($form_value, $feed_value)) {
						$result = true;
					}
					break;

				case 'not_contains':
					if (false === strpos($form_value, $feed_value)) {
						$result = true;
					}
					break;

				case 'exist':
					if (false !== strpos($feed_value, $form_value)) {
						$result = true;
					}
					break;

				case 'not_exist':
					if (false === strpos($feed_value, $form_value)) {
						$result = true;
					}
					break;

				case 'starts':
					if (0 === strpos($form_value, $feed_value)) {
						$result = true;
					}
					break;

				case 'not_starts':
					if (0 !== strpos($form_value, $feed_value)) {
						$result = true;
					}
					break;

				case 'ends':
					if (strlen($form_value) == strpos($form_value, $feed_value) + strlen($feed_value)) { // phpcs:ignore
						$result = true;
					}
					break;

				case 'not_ends':
					if (strlen($form_value) != strpos($form_value, $feed_value) + strlen($feed_value)) { // phpcs:ignore
						$result = true;
					}
					break;

				case 'less_than':
					if ((float) $form_value < (float) $feed_value) {
						$result = true;
					}
					break;

				case 'greater_than':
					if ((float) $form_value > (float) $feed_value) {
						$result = true;
					}
					break;

				case 'less_than_date':
					if (strtotime($form_value, $time) < strtotime($feed_value, $time)) {
						$result = true;
					}
					break;

				case 'greater_than_date':
					if (strtotime($form_value, $time) > strtotime($feed_value, $time)) {
						$result = true;
					}
					break;

				case 'equal_date':
					if (strtotime($form_value, $time) == strtotime($feed_value, $time)) { // phpcs:ignore
						$result = true;
					}
					break;

				case 'empty':
					if (empty($form_value)) {
						$result = true;
					}
					break;

				case 'not_empty':
					if (! empty($form_value)) {
						$result = true;
					}
					break;

				default:
					$result = false;
					break;
			}
		}
		return $result;
	}

	/**
	 * Retrive form data
	 *
	 * @param     object $form   Current submitted Form Data.
	 * @since     1.0.0
	 * @return    array
	 */
	public function retrieve_form_data($form)
	{

		$form_tags    = array();
		$form_id      = $form->id();
		if (class_exists('WPCF7_Submission')) {
			$cf7_submit   = WPCF7_Submission::get_instance();
			$form_entries = $cf7_submit->uploaded_files();

			if (! is_array($form_entries)) {
				$form_entries = array();
			}
		}

		$form_title = $form->title();
		$form_input = get_post_meta($form_id, '_form', true);

		if (class_exists('WPCF7_FormTagsManager')) {
			$tag_manager = WPCF7_FormTagsManager::get_instance();
			$tag_manager->scan($form_input);
			$form_tags = $tag_manager->get_scanned_tags();
		} elseif (class_exists('WPCF7_ShortcodeManager')) {
			$tag_manager = WPCF7_ShortcodeManager::get_instance();
			$tag_manager->do_shortcode($form_input);
			$form_tags = $tag_manager->get_scanned_tags();
		}

		if (! empty($form_tags) && is_array($form_tags)) {
			foreach ($form_tags as $key => $value) {
				if (! empty($value['name'])) {
					$name = $value['name'];
					$val  = $cf7_submit->get_posted_data($name);

					if (! isset($form_entries[$name])) {
						$form_entries[$name] = $val;
					}
				}
			}
		}

		return array(
			'id'     => $form_id,
			'name'   => $form_title,
			'fields' => $form_tags,
			'values' => $form_entries,
		);
	}

	/**
	 * Get all feeds of a respective form id.
	 *
	 * @param     int $form_id    Form id.
	 * @since     1.0.0
	 * @return    array
	 */
	public function get_feeds_by_form_id($form_id = '')
	{

		if (empty($form_id)) {
			return;
		}

		// Get all feeds.
		$active_feeds = get_posts(
			array(
				'numberposts' => -1,
				'fields'      => 'ids', // return only ids.
				'post_type'   => 'wciz_cf7_zoho_feeds',
				'post_staus'  => 'publish',
				'order'       => 'DESC',
				'meta_query'  => array( // phpcs:ignore
					array(
						'relation' => 'AND',
						array(
							'key'     => 'wciz_zcf7_form',
							'compare' => 'EXISTS',
						),
						array(
							'key'     => 'wciz_zcf7_form',
							'value'   => $form_id,
							'compare' => '==',
						),
						array(
							'key'     => 'wciz-zoho-cf7-dependent-on',
							'compare' => 'NOT EXISTS',
						),
					),
				),
			)
		);

		return $active_feeds;
	}

	/**
	 * Check if filter exists in feed.
	 *
	 * @param     int $feed_id    Feed ID.
	 * @since     1.0.0
	 * @return    bool|array
	 */
	public function maybe_check_filter_cf7($feed_id = '')
	{

		if (empty($feed_id)) {
			return;
		}

		if ('yes' == get_post_meta($feed_id, 'wciz_zcf7_enable_filters', true)) { // phpcs:ignore
			$meta = get_post_meta($feed_id, 'wciz_zcf7_condtion_field', true);

			if (! empty($meta) && is_array($meta) && count($meta) > 0) {
				return $meta;
			}
		}

		return false;
	}

	/**
	 * Returns the mapping step we require.
	 *
	 * @param string $crm_obj  The CRM module.
	 * @param string $feed_id  Feed ID.
	 * @param string $entries  Current form entries.
	 *
	 * @since  1.0.0
	 * @return array The current mapping step required.
	 */
	public function get_crm_request_cf7($crm_obj = false, $feed_id = false, $entries = array())
	{
		if (false == $crm_obj || false == $feed_id || ! is_array($entries)) { // phpcs:ignore
			return;
		}

		$feed            = $this->get_feed($feed_id); // Get feed mapping data.

		if (empty($feed)) {
			return false;
		}

		// Process Feeds.
		$response = array();

		foreach ($feed as $k => $mapping) {

			$field_type = ! empty($mapping['field_type']) ? $mapping['field_type'] : 'standard_field';

			switch ($field_type) {

				case 'standard_field':
					$field_format = ! empty($mapping['field_value']) ? $mapping['field_value'] : '';
					$meta_key     = substr($field_format, 11);
					$field_value  = $this->get_prop_value_cf7($meta_key, $entries);
					break;

				case 'custom_value':
					$field_key = ! empty($mapping['custom_value']) ? $mapping['custom_value'] : '';

					preg_match_all('/{(.*?)}/', $field_key, $dynamic_strings);

					if (! empty($dynamic_strings[1])) {
						$dynamic_values = $dynamic_strings[1];

						foreach ($dynamic_values as $key => $value) {
							$field_format = substr($value, 11);
							$field_value  = $this->get_prop_value_cf7($field_format, $entries);

							$substr = '{' . $value . '}';

							$field_key   = str_replace($substr, $field_value, $field_key);
							$field_value = $field_key;
						}
					}
					break;
			}

			$response[$k] = ! empty($field_value) ? $field_value : '';
		}

		$crm_api_module = Woo_Crm_Integration_Zoho_Api::get_instance();
		// Now restructure data as per CRM.
		$crm_fields = $crm_api_module->get_module_fields($crm_obj);

		$request = array();
		if (! empty($response) && is_array($response)) {
			$request = $this->get_structured_object_request($crm_obj, $response, $crm_fields);
		}
		/**
		 * Filters the value of requested data.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $request request.
		 * @param mixed $crm_obj user_id.
		 * @param mixed $feed_id feed_id.
		 * @param mixed $entries entries.
		 */
		$request = apply_filters('wciz_zcf7_request_data', $request, $crm_obj, $feed_id, $entries);

		return $request;
	}

	/**
	 * Returns the feed data we require.
	 *
	 * @param     int    $feed_id      The object id for post type feed.
	 * @param     string $meta_key     The object meta key for post type feed.
	 * @return    array|bool The current data for required object.
	 * @since 1.0.0
	 */
	public function get_feed($feed_id = false, $meta_key = 'mapping_data')
	{
		if (false == $feed_id) { // phpcs:ignore
			return;
		}

		if ('mapping_data' == $meta_key) { // phpcs:ignore
			$meta_key = 'wciz_zcf7_mapping_data';
		}

		$mapping = get_post_meta($feed_id, $meta_key, true);

		if (empty($mapping)) {
			$mapping = false;
		}

		return $mapping;
	}

	/**
	 * Returns the requested values for required form index.
	 *
	 * @param     string $key      The form field meta key.
	 * @param     array  $data     An array of form entries data.
	 * @since     1.0.0
	 * @return    string           The post meta values.
	 */
	public function get_prop_value_cf7($key = false, $data = array())
	{

		if (empty($key) || ! is_array($data)) {
			return;
		}

		$value = '';

		foreach ($data as $field => $value) {
			if ($key == $field) { // phpcs:ignore
				return $value;
			}
		}
		return $value;
	}

	/**
	 * Get structured object request.
	 *
	 * @param string $object          CRM object.
	 * @param array  $response_data   Feed maaping data.
	 * @param array  $obj_fields      Fields of an object.
	 * @since 1.0.0
	 * @return array
	 */
	public function get_structured_object_request($object = false, $response_data = array(), $obj_fields = array())
	{

		if (empty($object) || empty($response_data) || ! is_array($response_data) || empty($obj_fields) || ! is_array($obj_fields)) {
			return;
		}

		$obj_request = array();

		// Now restructure data.
		foreach ($response_data as $key => $value) {
			$type  = ! empty($obj_fields[$key]['type']) ? $obj_fields[$key]['type'] : '';
			$value = $this->maybe_verify_values($value, $type);

			$obj_request[$key] = $value;
		}

		return $obj_request;
	}

	/**
	 * Verify value
	 *
	 * @param mixed  $value  Entry value.
	 * @param string $type   Type of value.
	 * @since 1.0.0
	 * @return mixed
	 */
	public function maybe_verify_values($value, $type)
	{

		switch ($type) {

			case 'number':
				$value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
				break;

			case 'file':
				if (is_string($value)) {
					$files_temp = json_decode($value, true);

					if (! empty($files_temp)) {
						$value = $files_temp;
					}
				}
				break;

			case 'date':
				$value = gmdate('Y-m-d', strtotime(str_replace('/', '-', $value)));
				break;

			case 'datetime':
				$value = gmdate('c', strtotime(str_replace('/', '-', $value)));
				break;

			case 'booleancheckbox':
				$value = ! empty($value) ? 'true' : 'false';
				break;

			default:
				if (is_array($value)) {
					$value = implode(' ', $value);
				}
		}

		return $value;
	}

	/**
	 * Validate form values with conditions.
	 *
	 * @param    string $option_type    Filter conditon type.
	 * @param    string $feed_value     Value to compare with entry value.
	 * @param    string $form_value     Entry value .
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	public function is_value_allowed_cf7($option_type = false, $feed_value = false, $form_value = false)
	{

		if (false == $option_type) { // phpcs:ignore
			return;
		}

		$time   = current_time('timestamp'); // phpcs:ignore
		$result = false;
		if (false != $form_value) { // phpcs:ignore

			switch ($option_type) {

				case 'exact_match':
					if ($feed_value === $form_value) { // phpcs:ignore
						$result = true;
					}
					break;

				case 'no_exact_match':
					if ($feed_value !== $form_value) { // phpcs:ignore
						$result = true;
					}
					break;

				case 'contains':
					if (false !== strpos($form_value, $feed_value)) {
						$result = true;
					}
					break;

				case 'not_contains':
					if (false === strpos($form_value, $feed_value)) {
						$result = true;
					}
					break;

				case 'exist':
					if (false !== strpos($feed_value, $form_value)) {
						$result = true;
					}
					break;

				case 'not_exist':
					if (false === strpos($feed_value, $form_value)) {
						$result = true;
					}
					break;

				case 'starts':
					if (0 === strpos($form_value, $feed_value)) {
						$result = true;
					}
					break;

				case 'not_starts':
					if (0 !== strpos($form_value, $feed_value)) {
						$result = true;
					}
					break;

				case 'ends':
					if (strlen($form_value) == strpos($form_value, $feed_value) + strlen($feed_value)) { // phpcs:ignore
						$result = true;
					}
					break;

				case 'not_ends':
					if (strlen($form_value) != strpos($form_value, $feed_value) + strlen($feed_value)) { // phpcs:ignore
						$result = true;
					}
					break;

				case 'less_than':
					if ((float) $form_value < (float) $feed_value) {
						$result = true;
					}
					break;

				case 'greater_than':
					if ((float) $form_value > (float) $feed_value) {
						$result = true;
					}
					break;

				case 'less_than_date':
					if (strtotime($form_value, $time) < strtotime($feed_value, $time)) {
						$result = true;
					}
					break;

				case 'greater_than_date':
					if (strtotime($form_value, $time) > strtotime($feed_value, $time)) {
						$result = true;
					}
					break;

				case 'equal_date':
					if (strtotime($form_value, $time) == strtotime($feed_value, $time)) { // phpcs:ignore
						$result = true;
					}
					break;

				case 'empty':
					if (empty($form_value)) {
						$result = true;
					}
					break;

				case 'not_empty':
					if (! empty($form_value)) {
						$result = true;
					}
					break;

				default:
					$result = false;
					break;
			}
		}
		return $result;
	}

	/**
	 * Trigger associated feed.
	 *
	 * @param string $post_id            Woo Object post ID.
	 * @param array  $associated_feeds   Array of associated Feeds.
	 * @param string $event              Event to determine type of object.
	 */
	public function may_be_trigger_associated_zoho_feeds($post_id, $associated_feeds, $event)
	{

		foreach ($associated_feeds as $key => $feed_id) {
			if ('create_and_update_product' === $event) {
				$this->trigger_product_related_feed($feed_id, $post_id, 'true');
			} elseif ('shop_order_updated' === $event) {
				$this->perform_shop_order_sync($feed_id, $post_id);
			} elseif ('create_and_update_contact' === $event) {
				$this->perform_wp_user_sync($feed_id, $post_id);
			}
		}
	}

	/**
	 * Get feeds attached after a particular feed.
	 *
	 * @param int $feed_id Feed id.
	 * @return array       Array of feed ids.
	 */
	public function get_associated_feeds($feed_id)
	{

		$feeds = array();
		$args  = array(
			'post_type'   => 'wciz_crm_feed',
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby'     => 'id',
			'order'       => 'ASC',
			'meta_query'  => array(
				array(
					'key'     => 'feed_event',
					'value'   => $feed_id,
					'compare' => '=',
				),
			),
			'fields'      => 'ids',
		);
		$feeds = get_posts($args);
		return $feeds;
	}

	// End of class.
}
