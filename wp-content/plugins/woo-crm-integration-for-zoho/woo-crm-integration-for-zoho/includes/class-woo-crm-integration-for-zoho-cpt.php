<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */

if ( ! class_exists( 'Woo_Crm_Integration_Connect_Framework' ) ) {
	wp_die( 'Woo_Crm_Integration_Connect_Framework does not exists.' );
}

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
class Woo_Crm_Integration_For_Zoho_Cpt {

	/**
	 *  The slug prefix for this crm.
	 *
	 * @since    1.0.0
	 * @var      string    $crm_prefix    The crm prefix of this class.
	 */
	private $crm_prefix;

	/**
	 * Set crm prefix.
	 */
	public function __construct() {
		$this->crm_prefix = 'zoho';
	}

	/**
	 * Register custom post types.
	 */
	public function register_plugin_cpt() {

		// Set UI labels for Custom Post Type.
		$labels = array(
			'name'               => _x( 'Feeds', 'Post Type General Name', 'woo-crm-integration-for-zoho' ),
			'singular_name'      => _x( 'Feed', 'Post Type Singular Name', 'woo-crm-integration-for-zoho' ),
			'menu_name'          => _x( 'Crm Feeds', 'Admin menu name', 'woo-crm-integration-for-zoho' ),
			'parent_item_colon'  => __( 'Parent Feed', 'woo-crm-integration-for-zoho' ),
			'all_items'          => __( 'All Feeds', 'woo-crm-integration-for-zoho' ),
			'view_item'          => __( 'View Feed', 'woo-crm-integration-for-zoho' ),
			'add_new_item'       => __( 'Add New Feed', 'woo-crm-integration-for-zoho' ),
			'add_new'            => __( 'Add New', 'woo-crm-integration-for-zoho' ),
			'edit_item'          => __( 'Edit Feed', 'woo-crm-integration-for-zoho' ),
			'update_item'        => __( 'Update Feed', 'woo-crm-integration-for-zoho' ),
			'search_items'       => __( 'Search Feed', 'woo-crm-integration-for-zoho' ),
			'not_found'          => __( 'Not Found', 'woo-crm-integration-for-zoho' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'woo-crm-integration-for-zoho' ),
		);

		$args = array(
			'label'               => __( 'Feeds', 'woo-crm-integration-for-zoho' ),
			'description'         => __( 'Feeds for crm', 'woo-crm-integration-for-zoho' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'show_in_rest'        => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
		);
		register_post_type( 'wciz_crm_feed', $args );

		// Set UI labels for Custom Post Type.
		$labels = array(
			'name'               => _x( 'Feeds', 'Post Type General Name', 'woo-crm-integration-for-zoho' ),
			'singular_name'      => _x( 'Feed', 'Post Type Singular Name', 'woo-crm-integration-for-zoho' ),
			'menu_name'          => _x( 'Crm Feeds', 'Admin menu name', 'woo-crm-integration-for-zoho' ),
			'parent_item_colon'  => __( 'Parent Feed', 'woo-crm-integration-for-zoho' ),
			'all_items'          => __( 'All Feeds', 'woo-crm-integration-for-zoho' ),
			'view_item'          => __( 'View Feed', 'woo-crm-integration-for-zoho' ),
			'add_new_item'       => __( 'Add New Feed', 'woo-crm-integration-for-zoho' ),
			'add_new'            => __( 'Add New', 'woo-crm-integration-for-zoho' ),
			'edit_item'          => __( 'Edit Feed', 'woo-crm-integration-for-zoho' ),
			'update_item'        => __( 'Update Feed', 'woo-crm-integration-for-zoho' ),
			'search_items'       => __( 'Search Feed', 'woo-crm-integration-for-zoho' ),
			'not_found'          => __( 'Not Found', 'woo-crm-integration-for-zoho' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'woo-crm-integration-for-zoho' ),
		);

		$args = array(
			'label'               => __( 'Feeds', 'woo-crm-integration-for-zoho' ),
			'description'         => __( 'Feeds for crm', 'woo-crm-integration-for-zoho' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'show_in_rest'        => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
		);

		register_post_type( 'wciz_cf7_zoho_feeds', $args );
	}

	/**
	 * Add meta boxes for feeds.
	 */
	public function add_feed_meta_boxes() {

		add_meta_box(
			'crm-feed-meta-box',
			__( 'Feed Details', 'woo-crm-integration-for-zoho' ),
			array( $this, 'crm_feed_meta_box_data' ),
			'wciz_crm_feed',
			'normal',
			'default'
		);

		add_meta_box(
			'wciz_zoho_feeds_condition_meta_box',
			__( 'Conditional Statements', 'woo-crm-integration-for-zoho' ),
			array( $this, 'feeds_wciz_condition_render' ),
			'wciz_crm_feed',
			'normal',
			'default'
		);

		add_meta_box(
			'wciz_zoho_cf7_feeds_meta_box',
			esc_html__( 'Feed details', 'woo-crm-integration-for-zoho' ),
			array( $this, 'feeds_mb_render' ),
			'wciz_cf7_zoho_feeds',
			'normal',
			'default'
		);

		add_meta_box(
			'wciz_zoho_cf7_feeds_condition_meta_box',
			esc_html__( 'Conditional Statements', 'woo-crm-integration-for-zoho' ),
			array( $this, 'feeds_mb_condition_render' ),
			'wciz_cf7_zoho_feeds',
			'normal',
			'default'
		);
	}

	/**
	 * Add meta boxes for manual sync.
	 */
	public function add_manual_sync_meta_boxes() {

		$screenid = Integration_With_Woo_Zoho_Order_Handler::wciz_get_shop_order_screen_id();

		add_meta_box(
			'wciz-zoho-meta-box',
			__( 'Zoho Manual Sync', 'woo-crm-integration-for-zoho' ),
			array( $this, 'shop_order_meta_box_data' ),
			$screenid,
			'side',
			'low'
		);

		add_meta_box(
			'wciz-zoho-meta-box',
			__( 'Zoho Manual Sync', 'woo-crm-integration-for-zoho' ),
			array( $this, 'shop_order_meta_box_data' ),
			'product',
			'side',
			'low'
		);

		add_meta_box(
			'wciz-zoho-order-feed-meta-box',
			__( 'Zoho Feeds', 'woo-crm-integration-for-zoho' ),
			array( $this, 'shop_order_zoho_feed_meta_box' ),
			$screenid,
			'side',
			'low'
		);

		add_meta_box(
			'wciz-zoho-order-feed-meta-box',
			__( 'Zoho Feeds', 'woo-crm-integration-for-zoho' ),
			array( $this, 'shop_order_zoho_feed_meta_box' ),
			'product',
			'side',
			'low'
		);

		add_meta_box(
			'wciz-zoho-meta-box-woo',
			__( 'Reset Zoho Feed Ids', 'woo-crm-integration-for-zoho' ),
			array( $this, 'shop_order_meta_box_delete_data' ),
			$screenid,
			'side',
			'low'
		);

		add_meta_box(
			'wciz-zoho-meta-box-reset',
			__( 'Reset Zoho Feed Ids', 'woo-crm-integration-for-zoho' ),
			array( $this, 'shop_order_meta_box_delete_data' ),
			'product',
			'side',
			'low'
		);
	}

	/**
	 * Add shop order feeds meta box.
	 */
	public function shop_order_zoho_feed_meta_box( $post_or_order_object ) {

		if ( isset( $post_or_order_object->post_type ) && 'product' == $post_or_order_object->post_type ) {
			global $post;

			$feed_list     = array();
			$zoho_api      = Woo_Crm_Integration_Zoho_Api::get_instance();
			$feeds         = $this->get_available_feeds_for_mapping();
			$variation_ids = array();
			$item_type     = ( 'product' === $post->post_type ) ? 'Product id' : 'Order id';
			if ( 'product' === $post->post_type ) {
				$product = wc_get_product( $post->ID );
				if ( 'variable' === $product->get_type() || 'variable-subscription' === $product->get_type() ) {
					$variations = $product->get_children();
					if ( ! empty( $variations ) && is_array( $variations ) ) {
						foreach ( $variations as $key => $variation_id ) {
							$variation = wc_get_product( $variation_id );
							if ( ! $variation || ! $variation->exists() ) {
								continue;
							}
							$variation_ids[] = $variation_id;
						}
					}
				}
			}

			if ( count( $feeds ) ) {
				foreach ( $feeds as $feed_id => $feed_title ) {
					$crm_object = $this->get_feed_data( $feed_id, 'crm_object', '-1' );
					if ( count( $variation_ids ) ) {
						$product_feed_id = WCIZ_Data_Handler::get_option( 'wciz_woo_zoho_default_Products_feed_id', '' );
						if ( ! empty( $product_feed_id ) ) {
							$is_default_feed = $this->get_feed_data( $product_feed_id, 'default_feed', '' );
							if ( $is_default_feed ) {
								$sync_parent_product = $this->get_feed_data( $product_feed_id, 'sync_parent_product', 'no' );
								if ( 'yes' === $sync_parent_product ) {
									$reference_id = WCIZ_Data_Handler::get_post_meta( $post->ID, 'wciz_zoho_feed_' . $feed_id . '_association', true );
									if ( ! empty( $reference_id ) ) {
										$data_href   = $zoho_api->get_crm_link( $reference_id, $crm_object );
										$feed_list[] = compact( 'feed_title', 'reference_id', 'data_href' );
									}
								}
							}
						}
						foreach ( $variation_ids as $vk => $vid ) {
							$reference_id = WCIZ_Data_Handler::get_post_meta( $vid, 'wciz_zoho_feed_' . $feed_id . '_association', true );
							if ( ! empty( $reference_id ) ) {
								$data_href   = $zoho_api->get_crm_link( $reference_id, $crm_object );
								$item_id     = $vid;
								$feed_list[] = compact( 'feed_title', 'reference_id', 'item_type', 'item_id', 'data_href' );
							}
						}
					} else {
						$reference_data = WCIZ_Data_Handler::get_post_meta( $post->ID, 'wciz_zoho_feed_' . $feed_id . '_association', true );
						if ( ! empty( $reference_data ) ) {
							if ( is_array( $reference_data ) ) {
								foreach ( $reference_data as $k => $v ) {
									$data_href    = $zoho_api->get_crm_link( $v, $crm_object );
									$reference_id = $v;
									$item_id      = $post->ID;
									$feed_list[]  = compact( 'feed_title', 'reference_id', 'data_href' );
								}
							} else {
								$data_href    = $zoho_api->get_crm_link( $reference_data, $crm_object );
								$reference_id = $reference_data;
								$item_id      = $post->ID;
								$feed_list[]  = compact( 'feed_title', 'reference_id', 'data_href' );
							}
						}
					}
				}
			}
		} elseif ( OrderUtil::custom_orders_table_usage_is_enabled() ) {

			if ( isset( $post_or_order_object->post_type ) ) {
				$item_type     = ( 'product' === $post_or_order_object->post_type ) ? 'Product id' : 'Order id';
			} else {
				$item_type     = 'Order id';
			}
			$feed_list     = array();
			$zoho_api      = Woo_Crm_Integration_Zoho_Api::get_instance();
			$feeds         = $this->get_available_feeds_for_mapping();
			$variation_ids = array();
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
				$o_id = !empty( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
				$p_id = !empty( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : '';
			}
			if ( empty( $o_id ) && !empty( $p_id ) ) {
				$object_type = Integration_With_Woo_Zoho_Order_Handler::wciz_get_object_type($post_or_order_object->ID);
				$item_type     = ( 'product' === $post_or_order_object->post_type ) ? 'Product id' : 'Order id';
				if ( 'product' === $post_or_order_object->post_type ) {
					$product = wc_get_product( $post_or_order_object->ID );
					if ( 'variable' === $product->get_type() || 'variable-subscription' === $product->get_type() ) {
						$variations = $product->get_children();
						if ( ! empty( $variations ) && is_array( $variations ) ) {
							foreach ( $variations as $key => $variation_id ) {
								$variation = wc_get_product( $variation_id );
								if ( ! $variation || ! $variation->exists() ) {
									continue;
								}
								$variation_ids[] = $variation_id;
							}
						}
					}
				}
	
				if ( count( $feeds ) ) {
					foreach ( $feeds as $feed_id => $feed_title ) {
						$crm_object = $this->get_feed_data( $feed_id, 'crm_object', '-1' );
						if ( count( $variation_ids ) ) {
							$product_feed_id = get_option( 'wciz_woo_zoho_default_Products_feed_id', '' );
							if ( ! empty( $product_feed_id ) ) {
								$is_default_feed = $this->get_feed_data( $product_feed_id, 'default_feed', '' );
								if ( $is_default_feed ) {
									$sync_parent_product = $this->get_feed_data( $product_feed_id, 'sync_parent_product', 'no' );
									if ( 'yes' === $sync_parent_product ) {
										$reference_id = wciz_zoho_get_meta_data( $post_or_order_object->ID, 'wciz_zoho_feed_' . $feed_id . '_association', true );
										if ( ! empty( $reference_id ) ) {
											$data_href   = $zoho_api->get_crm_link( $reference_id, $crm_object );
											$feed_list[] = compact( 'feed_title', 'reference_id', 'data_href' );
										}
									}
								}
							}
							foreach ( $variation_ids as $vk => $vid ) {
								$reference_id = wciz_zoho_get_meta_data( $vid, 'wciz_zoho_feed_' . $feed_id . '_association', true );
								if ( ! empty( $reference_id ) ) {
									$data_href   = $zoho_api->get_crm_link( $reference_id, $crm_object );
									$item_id     = $vid;
									$feed_list[] = compact( 'feed_title', 'reference_id', 'item_type', 'item_id', 'data_href' );
								}
							}
						} else {
							$object_type = Integration_With_Woo_Zoho_Order_Handler::wciz_get_object_type($post_or_order_object->ID);
								
							if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
								if ('product' == $object_type ) {
									$reference_data = wciz_zoho_get_meta_data( $post_or_order_object->ID, 'wciz_zoho_feed_' . $feed_id . '_association', true );
								} else {
									$order = wc_get_order($post_or_order_object->ID);
									$reference_data = $order->get_meta('wciz_zoho_feed_' . $feed_id . '_association');
								}
							} else {
								$reference_data = wciz_zoho_get_meta_data( $post_or_order_object->ID, 'wciz_zoho_feed_' . $feed_id . '_association', true );
							}
							if ( ! empty( $reference_data ) ) {
								if ( is_array( $reference_data ) ) {
									foreach ( $reference_data as $k => $v ) {
										$data_href    = $zoho_api->get_crm_link( $v, $crm_object );
										$reference_id = $v;
										$item_id      = $post_or_order_object->ID;
										$feed_list[]  = compact( 'feed_title', 'reference_id', 'data_href' );
									}
								} else {
									$data_href    = $zoho_api->get_crm_link( $reference_data, $crm_object );
									$reference_id = $reference_data;
									$item_id      = $post_or_order_object->ID;
									$feed_list[]  = compact( 'feed_title', 'reference_id', 'data_href' );
								}
							}
						}
					}
				}
			} else {
				$object_type = Integration_With_Woo_Zoho_Order_Handler::wciz_get_object_type($post_or_order_object->get_id());
	
				if ( count( $feeds ) ) {
					foreach ( $feeds as $feed_id => $feed_title ) {
						$crm_object = $this->get_feed_data( $feed_id, 'crm_object', '-1' );
						if ( count( $variation_ids ) ) {
							$product_feed_id = get_option( 'wciz_woo_zoho_default_Products_feed_id', '' );
							if ( ! empty( $product_feed_id ) ) {
								$is_default_feed = $this->get_feed_data( $product_feed_id, 'default_feed', '' );
								if ( $is_default_feed ) {
									$sync_parent_product = $this->get_feed_data( $product_feed_id, 'sync_parent_product', 'no' );
									if ( 'yes' === $sync_parent_product ) {
										$reference_id = wciz_zoho_get_meta_data( $post_or_order_object->get_id(), 'wciz_zoho_feed_' . $feed_id . '_association', true );
										if ( ! empty( $reference_id ) ) {
											$data_href   = $zoho_api->get_crm_link( $reference_id, $crm_object );
											$feed_list[] = compact( 'feed_title', 'reference_id', 'data_href' );
										}
									}
								}
							}
							foreach ( $variation_ids as $vk => $vid ) {
								$reference_id = wciz_zoho_get_meta_data( $vid, 'wciz_zoho_feed_' . $feed_id . '_association', true );
								if ( ! empty( $reference_id ) ) {
									$data_href   = $zoho_api->get_crm_link( $reference_id, $crm_object );
									$item_id     = $vid;
									$feed_list[] = compact( 'feed_title', 'reference_id', 'item_type', 'item_id', 'data_href' );
								}
							}
						} else {
							$object_type = Integration_With_Woo_Zoho_Order_Handler::wciz_get_object_type($post_or_order_object->get_id());
								
							if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
								if ('product' == $object_type ) {
									$reference_data = wciz_zoho_get_meta_data( $post_or_order_object->get_id(), 'wciz_zoho_feed_' . $feed_id . '_association', true );
								} else {
									$order = wc_get_order($post_or_order_object->get_id());
									$reference_data = $order->get_meta('wciz_zoho_feed_' . $feed_id . '_association');
								}
							} else {
								$reference_data = wciz_zoho_get_meta_data( $post_or_order_object->get_id(), 'wciz_zoho_feed_' . $feed_id . '_association', true );
							}
							if ( ! empty( $reference_data ) ) {
								if ( is_array( $reference_data ) ) {
									foreach ( $reference_data as $k => $v ) {
										$data_href    = $zoho_api->get_crm_link( $v, $crm_object );
										$reference_id = $v;
										$item_id      = $post_or_order_object->get_id();
										$feed_list[]  = compact( 'feed_title', 'reference_id', 'data_href' );
									}
								} else {
									$data_href    = $zoho_api->get_crm_link( $reference_data, $crm_object );
									$reference_id = $reference_data;
									$item_id      = $post_or_order_object->get_id();
									$feed_list[]  = compact( 'feed_title', 'reference_id', 'data_href' );
								}
							}
						}
					}
				}
			}
	
		} else {
			if ( isset( $post_or_order_object->post_type ) ) {
				$item_type     = ( 'product' === $post_or_order_object->post_type ) ? 'Product id' : 'Order id';
			} else {
				$item_type     = 'Order id';
			}
			$feed_list     = array();
			$zoho_api      = Woo_Crm_Integration_Zoho_Api::get_instance();
			$feeds         = $this->get_available_feeds_for_mapping();
			$variation_ids = array();
			$object_type = Integration_With_Woo_Zoho_Order_Handler::wciz_get_object_type($post_or_order_object->ID);

			if ( isset( $post_or_order_object->post_type ) && 'product' === $post_or_order_object->post_type ) {
				$product = wc_get_product( $post_or_order_object->ID );
				if ( 'variable' === $product->get_type() || 'variable-subscription' === $product->get_type() ) {
					$variations = $product->get_children();
					if ( ! empty( $variations ) && is_array( $variations ) ) {
						foreach ( $variations as $key => $variation_id ) {
							$variation = wc_get_product( $variation_id );
							if ( ! $variation || ! $variation->exists() ) {
								continue;
							}
							$variation_ids[] = $variation_id;
						}
					}
				}
			}
		
			if ( count( $feeds ) ) {
				foreach ( $feeds as $feed_id => $feed_title ) {
					$crm_object = $this->get_feed_data( $feed_id, 'crm_object', '-1' );
					if ( count( $variation_ids ) ) {
						$product_feed_id = get_option( 'wciz_woo_zoho_default_Products_feed_id', '' );
						if ( ! empty( $product_feed_id ) ) {
							$is_default_feed = $this->get_feed_data( $product_feed_id, 'default_feed', '' );
							if ( $is_default_feed ) {
								$sync_parent_product = $this->get_feed_data( $product_feed_id, 'sync_parent_product', 'no' );
								if ( 'yes' === $sync_parent_product ) {
									$reference_id = wciz_zoho_get_meta_data( $post_or_order_object->ID, 'wciz_zoho_feed_' . $feed_id . '_association', true );
									if ( ! empty( $reference_id ) ) {
										$data_href   = $zoho_api->get_crm_link( $reference_id, $crm_object );
										$feed_list[] = compact( 'feed_title', 'reference_id', 'data_href' );
									}
								}
							}
						}
						foreach ( $variation_ids as $vk => $vid ) {
							$reference_id = wciz_zoho_get_meta_data( $vid, 'wciz_zoho_feed_' . $feed_id . '_association', true );
							if ( ! empty( $reference_id ) ) {
								$data_href   = $zoho_api->get_crm_link( $reference_id, $crm_object );
								$item_id     = $vid;
								$feed_list[] = compact( 'feed_title', 'reference_id', 'item_type', 'item_id', 'data_href' );
							}
						}
					} else {
						$object_type = Integration_With_Woo_Zoho_Order_Handler::wciz_get_object_type($post_or_order_object->ID);
							
						if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
							if ('product' == $object_type ) {
								$reference_data = wciz_zoho_get_meta_data( $post_or_order_object->ID, 'wciz_zoho_feed_' . $feed_id . '_association', true );
							} else {
								$order = wc_get_order($post_or_order_object->ID);
								$reference_data = $order->get_meta('wciz_zoho_feed_' . $feed_id . '_association');
							}
						} else {
							$reference_data = wciz_zoho_get_meta_data( $post_or_order_object->ID, 'wciz_zoho_feed_' . $feed_id . '_association', true );
						}
						if ( ! empty( $reference_data ) ) {
							if ( is_array( $reference_data ) ) {
								foreach ( $reference_data as $k => $v ) {
									$data_href    = $zoho_api->get_crm_link( $v, $crm_object );
									$reference_id = $v;
									$item_id      = $post_or_order_object->ID;
									$feed_list[]  = compact( 'feed_title', 'reference_id', 'data_href' );
								}
							} else {
								$data_href    = $zoho_api->get_crm_link( $reference_data, $crm_object );
								$reference_id = $reference_data;
								$item_id      = $post_or_order_object->ID;
								$feed_list[]  = compact( 'feed_title', 'reference_id', 'data_href' );
							}
						}
					}
				}
			}
		}
		$this->render_meta_box_data( 'feed-list-meta-box', compact( 'feed_list' ) );
	}

	/**
	 * Add meta box call back for crm feeds.
	 */
	public function crm_feed_meta_box_data() {
		global $post;
		$param                        = array();
		$order_statuses               = $this->get_woo_order_statuses();
		$params['objects']            = Woo_Crm_Integration_For_Zoho_Admin::get_available_crm_objects();
		$params['selected_object']    = $this->get_feed_data( $post->ID, 'crm_object', '-1' );
		$params['mapping_data']       = $this->get_feed_data( $post->ID, 'mapping_data', array() );
		$params['status_mapping']     = $this->get_feed_data( $post->ID, 'status_mapping', array() );
		$params['add_line_item']      = $this->get_feed_data( $post->ID, 'add_line_item', 'no' );
		$params['woo_statuses']       = $order_statuses;
		$params['primary_field']      = $this->get_feed_data( $post->ID, 'primary_field', '' );
		$params['feed_event_options'] = $this->get_feed_event_options( $order_statuses, $post->ID );
		$params['feed_event']         = $this->get_feed_data( $post->ID, 'feed_event', '' );
		$params['taxable_product']    = $this->get_feed_data( $post->ID, 'taxable_product', 'no' );
		$params['tax_rates']          = $this->get_feed_data( $post->ID, 'tax_rates', array() );
		$params['tax_rate_mapping']   = $this->get_feed_data( $post->ID, 'tax_rate_mapping', array() );

		$params['shipping_product_id']    = get_option( 'wciz_woo_shipping_product_id', false );
		$params['add_shipping_line_item'] = $this->get_feed_data( $post->ID, 'add_shipping_line_item', 'no' );

		$params['shipping_line_item_total'] = $this->get_feed_data( $post->ID, 'shipping_line_item_total', 'order_shipping' );

		$params['contact_only_guest_feed'] = $this->get_feed_data( $post->ID, 'contact_only_guest_feed', 'no' );

		$params['contact_customer_feed'] = $this->get_feed_data( $post->ID, 'contact_customer_feed', '' );

		$params['contact_feeds'] = $this->get_user_contact_feeds();

		$params['add_product_image']   = $this->get_feed_data( $post->ID, 'add_product_image', 'no' );
		$params['sync_parent_product'] = $this->get_feed_data( $post->ID, 'sync_parent_product', 'no' );

		$this->render_meta_box_data( 'select-event', $params );
		$this->render_meta_box_data( 'select-object', $params );
		$this->render_meta_box_data( 'select-fields', $params );
		$this->render_meta_box_data( 'add-new-field', $params );
		$this->render_meta_box_data( 'order-status-mapping', $params );
		$this->render_meta_box_data( 'product-details', $params );
		$this->render_meta_box_data( 'add-product-image', $params );
		$this->render_meta_box_data( 'sync-variable-product', $params );
		$this->render_meta_box_data( 'tax-rates', $params );
		$this->render_meta_box_data( 'primary-field', $params );
		$this->render_meta_box_data( 'user-type', $params );
		$this->render_meta_box_data( 'nonce-field', $params );
	}

	/**
	 * Render crm feed meta boxes.
	 *
	 * @param string $meta_box Name of meta box.
	 * @param array  $params   Array of parameters.
	 */
	private function render_meta_box_data( $meta_box, $params ) {
		if ( empty( $meta_box ) ) {
			return false;
		}
		$file_path = 'woo-crm-fw/templates/meta-boxes/' . $meta_box . '.php';
		Woo_Crm_Integration_For_Zoho_Admin::load_template( $file_path, $params, WOO_CRM_INTEGRATION_ZOHO_PATH );
	}

	/**
	 * Callback :: Post type feeds mapping metabox.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function feeds_mb_render() {

		global $post;
		$params = array();
		$params['objects']         = Woo_Crm_Integration_For_Zoho_Admin::get_available_crm_objects();
		$params['forms']           = $this->get_available_cf7_forms();
		$params['selected_form']   = $this->get_feed_data( $post->ID, 'wciz_zcf7_form', '' );
		$params['selected_object'] = $this->get_feed_data( $post->ID, 'wciz_zcf7_object', '' );
		$params['dependent_on'] = $this->get_feed_data( $post->ID, 'wciz-zoho-cf7-dependent-on', '' );

		$this->render_meta_box_data( 'header', $params );
		$this->render_meta_box_data( 'select-form', $params );
		$this->render_meta_box_data( 'select-object-cf7', $params );
		$this->render_meta_box_data( 'footer', $params );
	}

	/**
	 * Callback :: Post type feeds conditional filter metabox.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function feeds_mb_condition_render() {
		global $post;
		$params                  = array();
		$class                   = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$form_id                 = $this->get_feed_data( $post->ID, 'wciz_zcf7_form', '' );
		$params['enable_filter'] = $this->get_feed_data( $post->ID, 'wciz_zcf7_enable_filters', '' );
		$params['condition']     = $this->get_feed_data( $post->ID, 'wciz_zcf7_condtion_field', array() );
		$params['fields']        = $class->getMappingDatasetCF7( $form_id );
		$this->render_meta_box_data( 'opt-in-condition-cf7', $params );
	}

	/**
	 * Save meta box data for feed custom post type
	 *
	 * @param  int $post_id Id of the cpt.
	 * @return [type]          [description]
	 */
	public function save_metabox_data( $post_id ) {

		if ( ! isset( $_POST['_wpnonce'] ) ) {
			return;
		}

		if ( ! isset( $_POST['meta_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['meta_box_nonce'] ) ), 'meta_box_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			;
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'wciz_crm_feed' === $_POST['post_type'] ) {

			$crm_object         = isset( $_POST['crm_object'] ) ? sanitize_text_field( wp_unslash( $_POST['crm_object'] ) ) : array();
			$crm_field_arr      = isset( $_POST['crm_field'] ) ? map_deep( wp_unslash( $_POST['crm_field'] ), 'sanitize_text_field' ) : array();
			$field_type_arr     = isset( $_POST['field_type'] ) ? map_deep( wp_unslash( $_POST['field_type'] ), 'sanitize_text_field' ) : array();
			$field_value_arr    = isset( $_POST['field_value'] ) ? map_deep( wp_unslash( $_POST['field_value'] ), 'sanitize_text_field' ) : array();
			$custom_value_arr   = isset( $_POST['custom_value'] ) ? map_deep( wp_unslash( $_POST['custom_value'] ), 'sanitize_text_field' ) : array();
			$custom_field_arr   = isset( $_POST['custom_field'] ) ? map_deep( wp_unslash( $_POST['custom_field'] ), 'sanitize_text_field' ) : array();
			$woo_status         = isset( $_POST['woo_status'] ) ? map_deep( wp_unslash( $_POST['woo_status'] ), 'sanitize_text_field' ) : array();
			$crm_picklist_value = isset( $_POST['crm_picklist_value'] ) ? map_deep( wp_unslash( $_POST['crm_picklist_value'] ), 'sanitize_text_field' ) : array();
			$use_status_mapping = isset( $_POST['use_status_mapping'] ) ? map_deep( wp_unslash( $_POST['use_status_mapping'] ), 'sanitize_text_field' ) : array();
			$add_line_item      = isset( $_POST['add_line_item'] ) ? sanitize_text_field( wp_unslash( $_POST['add_line_item'] ) ) : 'no';
			$primary_field      = isset( $_POST['primary_field'] ) ? sanitize_text_field( wp_unslash( $_POST['primary_field'] ) ) : '';
			$feed_event         = isset( $_POST['feed_event'] ) ? sanitize_text_field( wp_unslash( $_POST['feed_event'] ) ) : '';

			$taxable_product = isset( $_POST['taxable_product'] ) ? sanitize_text_field( wp_unslash( $_POST['taxable_product'] ) ) : 'no';

			$tax_rates = isset( $_POST['tax_rates'] ) ? map_deep( wp_unslash( $_POST['tax_rates'] ), 'sanitize_text_field' ) : array();

			$woo_tax_rate = isset( $_POST['woo_tax_rate'] ) ? map_deep( wp_unslash( $_POST['woo_tax_rate'] ), 'sanitize_text_field' ) : array();

			$zoho_tax_rate = isset( $_POST['zoho_tax_rate'] ) ? map_deep( wp_unslash( $_POST['zoho_tax_rate'] ), 'sanitize_text_field' ) : array();

			$add_shipping_line_item = isset( $_POST['add_shipping_line_item'] ) ? sanitize_text_field( wp_unslash( $_POST['add_shipping_line_item'] ) ) : 'no';

			$shipping_line_item_total = isset( $_POST['shipping_line_item_total'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_line_item_total'] ) ) : 'order_shipping';

			$contact_only_guest_feed = isset( $_POST['contact_only_guest_feed'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_only_guest_feed'] ) ) : 'no';

			$contact_customer_feed = isset( $_POST['contact_customer_feed'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_customer_feed'] ) ) : '';
			$enable_filters        = isset( $_POST['enable_add_condition'] ) ? sanitize_text_field( wp_unslash( $_POST['enable_add_condition'] ) ) : '';
			$condition             = isset( $_POST['condition'] ) ? map_deep( wp_unslash( $_POST['condition'] ), 'sanitize_text_field' ) : array();
			$add_product_image     = isset( $_POST['add_product_image'] ) ? sanitize_text_field( wp_unslash( $_POST['add_product_image'] ) ) : 'no';
			$sync_parent_product   = isset( $_POST['sync_parent_product'] ) ? sanitize_text_field( wp_unslash( $_POST['sync_parent_product'] ) ) : 'no';

			$mapping_data = array();
			foreach ( $crm_field_arr as $key => $field ) {
				$field_key                  = $this->sanitize_input( $field );
				$mapping_data[ $field_key ] = array(
					'field_type'         => $this->sanitize_input( $field_type_arr[ $key ] ),
					'field_value'        => $this->sanitize_input( $field_value_arr[ $key ] ),
					'custom_value'       => $this->sanitize_input( $custom_value_arr[ $key ] ),
					'custom_field'       => $this->sanitize_input( $custom_field_arr[ $key ] ),
					'use_status_mapping' => isset( $use_status_mapping[ $field_key ] ) ? 'yes' : 'no',
				);
			}
			$status_mapping = array();
			if ( count( $woo_status ) === count( $crm_picklist_value ) ) {
				foreach ( $woo_status as $key => $value ) {
					$status_mapping[ $this->sanitize_input( $value ) ] =
					$this->sanitize_input( $crm_picklist_value[ $key ] );
				}
			}

			// Tax rate mapping.
			$tax_rate_mapping = array();
			
			foreach ( $woo_tax_rate as $woo_tax_key => $woo_tax_value ) {
				if ( !empty( $zoho_tax_rate[ $woo_tax_key ] ) ) {
					$tax_rate_mapping[ $woo_tax_value ] = $zoho_tax_rate[ $woo_tax_key ];
				}
			}

			update_post_meta( $post_id, 'crm_object', $crm_object );
			update_post_meta( $post_id, 'mapping_data', $mapping_data );
			update_post_meta( $post_id, 'status_mapping', $status_mapping );
			update_post_meta( $post_id, 'add_line_item', $add_line_item );
			update_post_meta( $post_id, 'primary_field', $primary_field );
			update_post_meta( $post_id, 'feed_event', $feed_event );
			update_post_meta( $post_id, 'wciz-zoho-enable-filters', $enable_filters );
			update_post_meta( $post_id, 'wciz-zoho-condition-field', $condition );

			if ( 'Products' == $crm_object ) {
				update_post_meta( $post_id, 'taxable_product', $taxable_product );
				update_post_meta( $post_id, 'tax_rates', $tax_rates );
				update_post_meta( $post_id, 'add_product_image', $add_product_image );
				update_post_meta( $post_id, 'sync_parent_product', $sync_parent_product );
				update_post_meta( $post_id, 'tax_rate_mapping', $tax_rate_mapping );
			}

			if ( 'Sales_Orders' == $crm_object || 'Invoices' == $crm_object || 'Quotes' ) {
				update_post_meta( $post_id, 'add_shipping_line_item', $add_shipping_line_item );
				update_post_meta( $post_id, 'shipping_line_item_total', $shipping_line_item_total );
			}

			if ( 'Contacts' == $crm_object ) {
				update_post_meta( $post_id, 'contact_only_guest_feed', $contact_only_guest_feed );
				update_post_meta( $post_id, 'contact_customer_feed', $contact_customer_feed );
			}
		}

		if ( isset( $_POST['post_type'] ) && 'wciz_cf7_zoho_feeds' == $_POST['post_type'] ) { // phpcs:ignore

			$crm_form         = isset( $_POST['crm_form'] ) ? sanitize_text_field( wp_unslash( $_POST['crm_form'] ) ) : '';
			$crm_object       = isset( $_POST['crm_object'] ) ? sanitize_text_field( wp_unslash( $_POST['crm_object'] ) ) : '';
			$crm_field_arr    = isset( $_POST['crm_field'] ) ? map_deep( wp_unslash( $_POST['crm_field'] ), 'sanitize_text_field' ) : array();
			$field_type_arr   = isset( $_POST['field_type'] ) ? map_deep( wp_unslash( $_POST['field_type'] ), 'sanitize_text_field' ) : array();
			$field_value_arr  = isset( $_POST['field_value'] ) ? map_deep( wp_unslash( $_POST['field_value'] ), 'sanitize_text_field' ) : array();
			$custom_value_arr = isset( $_POST['custom_value'] ) ? map_deep( wp_unslash( $_POST['custom_value'] ), 'sanitize_text_field' ) : array();
			$custom_field_arr = isset( $_POST['custom_field'] ) ? map_deep( wp_unslash( $_POST['custom_field'] ), 'sanitize_text_field' ) : array();
			$enable_filters   = isset( $_POST['enable_add_condition'] ) ? sanitize_text_field( wp_unslash( $_POST['enable_add_condition'] ) ) : '';
			$condition        = isset( $_POST['condition'] ) ? map_deep( wp_unslash( $_POST['condition'] ), 'sanitize_text_field' ) : array();
			$primary_field    = isset( $_POST['primary_field'] ) ? sanitize_text_field( wp_unslash( $_POST['primary_field'] ) ) : '';

			$mapping_data = array();
			if ( ! empty( $crm_field_arr ) && is_array( $crm_field_arr ) ) {
				foreach ( $crm_field_arr as $key => $field ) {
					$mapping_data[ $field ] = array(
						'field_type'   => $field_type_arr[ $key ],
						'field_value'  => $field_value_arr[ $key ],
						'custom_value' => $custom_value_arr[ $key ],
						'custom_field' => $custom_field_arr[ $key ],
					);
				}
			}

			update_post_meta( $post_id, 'wciz_zcf7_form', $crm_form );
			update_post_meta( $post_id, 'wciz_zcf7_object', $crm_object );
			update_post_meta( $post_id, 'wciz_zcf7_mapping_data', $mapping_data );
			update_post_meta( $post_id, 'wciz_zcf7_primary_field', $primary_field );
			update_post_meta( $post_id, 'wciz_zcf7_enable_filters', $enable_filters );
			update_post_meta( $post_id, 'wciz_zcf7_condtion_field', $condition );
			/**
			 * Save cf7 feed data.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed  $_POST  Post data variable.
			 * @param mixed post_id Post id.
			 */
			do_action( 'wciz_zcf7_save_feed_data', $_POST, $post_id );
		}
	}

	/**
	 * Get all Contact forms.
	 *
	 * @since     1.0.0
	 * @return    array    An array of all contact forms.
	 */
	public function get_available_cf7_forms() {

		$args = array(
			'post_type'   => 'wpcf7_contact_form',
			'numberposts' => -1,
			'order'       => 'ASC',
		);
		return get_posts( $args );
	}

	/**
	 * Create meta box section html via ajax.
	 *
	 * @param  string $meta_box Meta box key.
	 * @param  array  $params   Required params for meta box.
	 * @return string           Meta box section html.
	 */
	public function do_ajax_render( $meta_box, $params ) {

		if ( '' == $meta_box ) { // phpcs:ignore
			return;
		}

		ob_start();
		$this->render_meta_box_data( $meta_box, $params );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * Sanitize input.
	 *
	 * @param  string $input Input string.
	 * @return string        Sanitized input.
	 */
	public function sanitize_input( $input ) {
		return sanitize_text_field( wp_unslash( $input ) );
	}

	/**
	 * Get feed selected object.
	 *
	 * @param  int $post_id Feed post id.
	 * @return string       Name of feed object.
	 */
	public function get_feed_selected_object( $post_id ) {
		$selected_object = get_post_meta( $post_id, 'crm_object', true );
		$selected_object = ! empty( $selected_object ) ? $selected_object : '-1';
		return $selected_object;
	}

	/**
	 * Get feed mapping data.
	 *
	 * @param  int $post_id Id of feed post.
	 * @return array        Mapping data.
	 */
	public function get_feed_mapping_data( $post_id ) {
		$mapping_data = get_post_meta( $post_id, 'mapping_data', true );
		$mapping_data = ! empty( $mapping_data ) ? $mapping_data : array();
		return $mapping_data;
	}

	/**
	 * Get woocommerce order statuses.
	 *
	 * @return array Woo order statuses.
	 */
	public function get_woo_order_statuses() {
		$order_statuses = wc_get_order_statuses();
		/**
		 * Filters the value of order status array.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $order_statuses order_statuses.
		 */
		return apply_filters( 'wciz_woo_crm_order_statuses', $order_statuses );
	}

	/**
	 * Get feed data.
	 *
	 * @param int          $post_id Feed post id.
	 * @param string       $key Data key.
	 * @param string|array $default Default value.
	 * @return string|array         Data value.
	 */
	public function get_feed_data( $post_id, $key, $default ) {
		$feed_data = get_post_meta( $post_id, $key, true );
		$feed_data = ! empty( $feed_data ) ? $feed_data : $default;
		return $feed_data;
	}

	/**
	 * Get feed trigger event options.
	 *
	 * @param  array $order_statuses  WooCommerce order statuses.
	 * @return array                  Event options array.
	 */
	public function get_feed_event_options( $order_statuses = array(), $feed_id = false ) {

		if ( empty( $order_statuses ) ) {
			$order_statuses = $this->get_woo_order_statuses();
		}
		
		$admin_instance = new Woo_Crm_Integration_For_Zoho_Admin( 'CRM Integration For Zoho', WOO_CRM_INTEGRATION_ZOHO_VERSION );
		$options              = array();
		$options['new-order'] = __( 'New order is created', 'woo-crm-integration-for-zoho' );
		$options['order-create-update'] = __( 'Order is created and updated', 'woo-crm-integration-for-zoho' );
		foreach ( $order_statuses as $key => $value ) {
			$options[ $key ] = sprintf( 'Order Status Change to %s', $value );
		}
		$options['status_change']         = __( 'Order Status Change', 'woo-crm-integration-for-zoho' );
		$options['product_update_create'] = __( 'Product Updated/Created', 'woo-crm-integration-for-zoho' );
		$options['user_update_create']    = __( 'WP User Updated/Created', 'woo-crm-integration-for-zoho' );
		$options['send_manually']         = __( 'Send Manually', 'woo-crm-integration-for-zoho' );

		if ( $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
			$options['create_subscription'] = __( 'Subscription is created', 'woo-crm-integration-for-zoho' );
			$options['subscription_status_changed'] = __( 'Subscription Status Change', 'woo-crm-integration-for-zoho' );
			$options['subscription_updated'] = __( 'Subscription Updated', 'woo-crm-integration-for-zoho' );
		}

		if ( $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
			$options['membership_saved']         = __( 'Membership is saved', 'woo-crm-integration-for-zoho' );
			// $options['membership_created_when_adding_new_user_as_member']         = __( 'Membership created when adding new user as member', 'integration-with-salesforce' );
			// $options['membership_created_when_adding_existing_user_as_member']         = __( 'Membership adding existing user', 'integration-with-salesforce' );
		}

		$available_feeds = $this->get_available_feeds_for_mapping( false );

		foreach ( $available_feeds as $f_id => $feed_title ) {
			if ( intval( $f_id ) === intval( $feed_id ) ) {
				continue;
			}
			// translators: feed title.
			$options[ $f_id ] = sprintf( __( 'After %s', 'woo-crm-integration-for-zoho' ), $feed_title );
		}

		return $options;
	}

	/**
	 * Create default feed if not there.
	 *
	 * @return void
	 */
	public function may_be_create_default_feeds() {

		if ( ! post_type_exists( 'wciz_crm_feed' ) ) {
			return;
		}

		if ( get_option( 'wciz_woo_zoho_default_feeds_created', false ) ) {
			return;
		}

		foreach ( $this->get_default_feeds() as $key => $feed ) {

			$args = array(
				'post_type'   => 'wciz_crm_feed',
				'post_status' => 'publish',
				'post_title'  => $feed['title'],
				'meta_input'  => $this->get_feed_meta_data( $feed['crm_object'] ),
			);

			$feed_id = wp_insert_post( $args );

			if ( ! is_wp_error( $feed_id ) ) {
				update_option( 'wciz_woo_zoho_default_' . $feed['crm_object'] . '_feed_id', $feed_id );
			}
		}

		update_option( 'wciz_woo_zoho_default_feeds_created', true );
	}

	/**
	 * Get default feeds.
	 *
	 * @return array
	 */
	public function get_default_feeds() {

		$default_feeds = array(

			array(
				'type'       => 'product',
				'crm_object' => 'Products',
				'title'      => __( 'View your default product feed.', 'woo-crm-integration-for-zoho' ),
			),

			array(
				'type'       => 'contact',
				'crm_object' => 'Contacts',
				'title'      => __( 'View your default contact feed.', 'woo-crm-integration-for-zoho' ),
			),

			array(
				'type'       => 'deal',
				'crm_object' => 'Deals',
				'title'      => __( 'View your primary deals feed.', 'woo-crm-integration-for-zoho' ),
			),

			array(
				'type'       => 'order',
				'crm_object' => 'Sales_Orders',
				'title'      => __( 'Default Sales Order Feed', 'woo-crm-integration-for-zoho' ),
			),

		);
		/**
		 * Filters the value of zoho feeds array.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  $default_feeds default_feeds.
		 */
		return apply_filters( 'wciz_woo_zoho_default_feeds', $default_feeds );
	}

	/**
	 * Get feeds default meta data.
	 *
	 * @param string $feed_object Feed object name.
	 * @return array
	 */
	public function get_feed_meta_data( $feed_object ) {

		$meta_input = array(
			'crm_object'     => $feed_object,
			'mapping_data'   => self::get_default_mapping_data( $feed_object ),
			'status_mapping' => self::get_default_status_mapping( $feed_object ),
			'add_line_item'  => self::get_default_add_line_item( $feed_object ),
			'feed_event'     => self::get_default_event( $feed_object ),
			'primary_field'  => self::get_default_primary_field( $feed_object ),
			'default_feed'   => true,
		);
		return $meta_input;
	}

	/**
	 * Get default line item option value.
	 *
	 * @param  string $module Module name.
	 * @return string         Line item status.
	 */
	public static function get_default_add_line_item( $module ) {

		if ( in_array( $module, array( 'Sales_Orders', 'Deals' ) ) ) { //phpcs:ignore
			return 'yes';
		}
		return 'no';
	}

	/**
	 * Get default status mapping.
	 *
	 * @param  string $module Module name.
	 * @return array          Status mapping array,
	 */
	public static function get_default_status_mapping( $module ) {

		$status_mapping = array();
		$order_statuses = wc_get_order_statuses();
		if ( 'Sales_Orders' === $module ) {
			foreach ( $order_statuses as $key => $value ) {
				switch ( $key ) {
					case 'wc-pending':
						$status_mapping[ $key ] = 'Created';
						break;
					case 'wc-processing':
						$status_mapping[ $key ] = 'Approved';
						break;
					case 'wc-on-hold':
						$status_mapping[ $key ] = 'Created';
						break;
					case 'wc-completed':
						$status_mapping[ $key ] = 'Delivered';
						break;
					case 'wc-cancelled':
						$status_mapping[ $key ] = 'Cancelled';
						break;
					case 'wc-refunded':
						$status_mapping[ $key ] = 'Cancelled';
						break;
					case 'wc-failed':
						$status_mapping[ $key ] = 'Cancelled';
						break;
					default:
						$status_mapping[ $key ] = 'Created';
						break;
				}
			}
		} elseif ( 'Deals' === $module ) {
			foreach ( $order_statuses as $key => $value ) {
				switch ( $key ) {
					case 'wc-pending':
						$status_mapping[ $key ] = 'Needs Analysis';
						break;
					case 'wc-processing':
						$status_mapping[ $key ] = 'Proposal/Price Quote';
						break;
					case 'wc-on-hold':
						$status_mapping[ $key ] = 'Needs Analysis';
						break;
					case 'wc-completed':
						$status_mapping[ $key ] = 'Closed Won';
						break;
					case 'wc-cancelled':
						$status_mapping[ $key ] = 'Closed Lost';
						break;
					case 'wc-refunded':
						$status_mapping[ $key ] = 'Closed Lost';
						break;
					case 'wc-failed':
						$status_mapping[ $key ] = 'Closed Lost';
						break;
					default:
						$status_mapping[ $key ] = 'Needs Analysis';
						break;
				}
			}
		}

		return $status_mapping;
	}


	/**
	 * Get default mapping and data
	 *
	 * @param  string $module Module name.
	 * @return array          Mapping data.
	 */
	public static function get_default_mapping_data( $module ) {

		$contact_feed_id = get_option( 'wciz_woo_zoho_default_Contacts_feed_id', '' );
		$contact_feed    = ! empty( $contact_feed_id ) ? 'feeds_' . $contact_feed_id : '';

		$deal_feed_id = get_option( 'wciz_woo_zoho_default_Deals_feed_id', '' );
		$deal_feed    = ! empty( $deal_feed_id ) ? 'feeds_' . $deal_feed_id : '';

		$mapping_data['Contacts'] = array(
			'First_Name'      => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_first_name',
			),
			'Last_Name'       => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_last_name',
			),
			'Full_Name'       => array(
				'field_type'   => 'custom_value',
				'custom_value' => '{shop_order__billing_first_name} {shop_order__billing_last_name}',
			),
			'Email'           => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_email',
			),
			'Phone'           => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_phone',
			),
			'Mobile'          => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_phone',
			),
			'Mailing_Street'  => array(
				'field_type'   => 'custom_value',
				'custom_value' => '{shop_order__billing_address_1} {shop_order__billing_address_2}',
			),
			'Other_Street'    => array(
				'field_type'   => 'custom_value',
				'custom_value' => '{shop_order__shipping_address_1} {shop_order__shipping_address_2}',
			),
			'Mailing_City'    => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_city',
			),
			'Other_City'      => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__shipping_city',
			),
			'Mailing_State'   => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_state',
			),
			'Other_State'     => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__shipping_state',
			),
			'Mailing_Zip'     => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_postcode',
			),
			'Other_Zip'       => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__shipping_postcode',
			),
			'Mailing_Country' => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_country',
			),
			'Other_Country'   => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__shipping_country',
			),
		);
		$mapping_data['Products'] = array(
			'Product_Name' => array(
				'field_type'  => 'standard_field',
				'field_value' => 'product_product_name',
			),
			'Product_Code' => array(
				'field_type'   => 'custom_value',
				'custom_value' => 'Woo-product-{product_product_id}',
			),
			'Unit_Price'   => array(
				'field_type'  => 'standard_field',
				'field_value' => 'product__price',
			),
			'Description'  => array(
				'field_type'  => 'standard_field',
				'field_value' => 'product_product_short_description',
			),
		);

		$mapping_data['Deals'] = array(
			'Deal_Name'        => array(
				'field_type'   => 'custom_value',
				'custom_value' => 'woo-deal-{shop_order_order_id}',
			),
			'Amount'           => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order_order_total',
			),
			'Stage'            => array(
				'field_type'         => 'standard_field',
				'field_value'        => 'Qualification',
				'use_status_mapping' => 'yes',

			),
			'Expected_Revenue' => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order_order_total',
			),
			'Contact_Name'     => array(
				'field_type'  => 'standard_field',
				'field_value' => $contact_feed,
			),
		);

		$mapping_data['Sales_Orders'] = array(

			'Subject'          => array(
				'field_type'   => 'custom_value',
				'custom_value' => 'woo-order-{shop_order_order_id}',
			),
			'Status'           => array(
				'field_type'         => 'standard_field',
				'field_value'        => 'Created',
				'use_status_mapping' => 'yes',
			),
			'Deal_Name'        => array(
				'field_type'  => 'standard_field',
				'field_value' => $deal_feed,
			),
			'Contact_Name'     => array(
				'field_type'  => 'standard_field',
				'field_value' => $contact_feed,
			),
			'Billing_Street'   => array(
				'field_type'   => 'custom_value',
				'custom_value' => '{shop_order__billing_address_1} {shop_order__billing_address_2}',
			),
			'Shipping_Street'  => array(
				'field_type'   => 'custom_value',
				'custom_value' => '{shop_order__shipping_address_1} {shop_order__shipping_address_2}',
			),
			'Billing_City'     => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_city',
			),
			'Shipping_City'    => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__shipping_city',
			),
			'Billing_State'    => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_state',
			),
			'Shipping_State'   => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__shipping_state',
			),
			'Billing_Code'     => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_postcode',
			),
			'Shipping_Code'    => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__shipping_postcode',
			),
			'Billing_Country'  => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__billing_country',
			),
			'Shipping_Country' => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order__shipping_country',
			),
			'Sub_Total'        => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order_order_subtotal',
			),
			'Grand_Total'      => array(
				'field_type'  => 'standard_field',
				'field_value' => 'shop_order_order_total',
			),
		);

		$mapping_data['abandoned_cart'] = array(
			'Abandoned_cart_email'        => array(
				'field_type'   => 'standard_field',
				'field_value' => 'abandoned_cart_abandoned_cart_email',
			),
			'Name'        => array(
				'field_type'   => 'custom_value',
				'custom_value' => 'WooCommerce Abandoned cart -{abandoned_cart_abandoned_cart_email}',
			),
			'Abandoned_cart_url'        => array(
				'field_type'   => 'standard_field',
				'field_value' => 'abandoned_cart_abandoned_cart_url',
			),
			'Abandoned_cart_amount'        => array(
				'field_type'   => 'standard_field',
				'field_value' => 'abandoned_cart_abandoned_cart_total',
			),
			'Abandoned_cart_products'        => array(
				'field_type'   => 'standard_field',
				'field_value' => 'abandoned_cart_abandoned_cart_products',
			),
			'Abandoned_cart_products_html'        => array(
				'field_type'   => 'standard_field',
				'field_value' => 'abandoned_cart_abandoned_cart_products_html',
			),
			'Abandoned_cart_user_type'        => array(
				'field_type'   => 'standard_field',
				'field_value' => 'abandoned_cart_abandoned_cart_user_type',
			),
			'Abandoned_cart_user_id'        => array(
				'field_type'   => 'standard_field',
				'field_value' => 'abandoned_cart_abandoned_cart_user_id',
			),
		);

		return isset( $mapping_data[ $module ] ) ? $mapping_data[ $module ] : array();
	}

	/**
	 * Get default feed trigger event.
	 *
	 * @param  string $module Module name.
	 * @return string         Event key.
	 */
	public static function get_default_event( $module ) {
		$event = '';
		switch ( $module ) {
			case 'Sales_Orders':
				$event = 'status_change';
				break;
			case 'Products':
				$event = 'product_update_create';
				break;
			case 'Deals':
				$event = 'status_change';
				break;
			case 'Contacts':
				$event = 'new-order';
				break;
			case 'Leads':
				$event = 'new-order';
				break;
			case 'Accounts':
				$event = 'new-order';
				break;
			case 'Quotes':
				$event = 'status_change';
				break;
			default:
				$event = 'send_manually';
				break;
		}
		return $event;
	}

	/**
	 * Get default primary field.
	 *
	 * @param  string $module Module name.
	 * @return string         Default field name.
	 */
	public static function get_default_primary_field( $module ) {

		$default_primary = '';
		switch ( $module ) {
			case 'Sales_Orders':
				$default_primary = 'Subject';
				break;
			case 'Deals':
				$default_primary = 'Deal_Name';
				break;
			case 'Products':
				$default_primary = 'Product_Name';
				break;
			case 'Contacts':
				$default_primary = 'Email';
				break;
			case 'Leads':
				$default_primary = 'Last_Name';
				break;
			case 'Accounts':
				$default_primary = 'Account_Name';
				break;
			case 'Quotes':
				$default_primary = 'Subject';
				break;
			case 'abandoned_cart':
				$default_primary = 'Abandoned_cart_email';
		}
		return $default_primary;
	}

	/**
	 * Create feed post.
	 *
	 * @param  string $title  Feed title.
	 * @param  string $object Feed object.
	 * @return int          Feed id.
	 */
	public function create_feed_post( $title, $object ) {

		$args    = array(

			'post_type'   => 'wciz_crm_feed',
			'post_status' => 'publish',
			'post_title'  => $title,
			'meta_input'  => $this->get_feed_meta_data( $object ),
		);
		$feed_id = wp_insert_post( $args );
		return $feed_id;
	}

	/**
	 * Check is feed post exists.
	 *
	 * @param int $feed_id Feed id.
	 * @return int|bool
	 */
	public function is_valid_feed_post( $feed_id ) {

		$post   = get_post( $feed_id );
		$return = false;

		if ( ! empty( $post ) && ( 'publish' === $post->post_status || 'draft' === $post->post_status ) && 'wciz_crm_feed' === $post->post_type ) {
			$return = $feed_id;
		}
		return $return;
	}

	/**
	 * Get available feeds for mapping.
	 *
	 * @return array
	 */
	public function get_available_feeds_for_mapping( $include_abd_cart = true ) {
		$all_feeds = $this->get_available_feeds();
		if ( ! $all_feeds ) {
			return array();
		}
		$feed_list = array();
		foreach ( $all_feeds as $key => $feed ) {
			if ( ! $include_abd_cart ) {
				if ( 'abandoned-cart' !== $feed->post_name ) {
					$feed_list[ $feed->ID ] = $feed->post_title;
				}
			} else {
				$feed_list[ $feed->ID ] = $feed->post_title;
			}
		}
		return $feed_list;
	}

	/**
	 * Get all available feeds.
	 *
	 * @param array $status Feed statuses.
	 * @return array
	 */
	public function get_available_feeds( $status = array() ) {

		$status = empty( $status ) ? 'publish' : $status;
		$args   = array(
			'post_type'   => 'wciz_crm_feed',
			'post_status' => $status,
			'numberposts' => -1,
		);
		$feeds  = get_posts( $args );
		return $feeds;
	}

	/**
	 * Add shop order meta box.
	 */
	public function shop_order_meta_box_data( $post_or_order_object ) {
		if ( isset( $post_or_order_object->post_type ) && 'product' == $post_or_order_object->post_type ) {
			$params = array();
			$feeds  = $this->get_available_feeds_for_manual_sync();
			$post_order_id  = $post_or_order_object->ID;
			$this->render_meta_box_data( 'order-meta-box', compact( 'feeds', 'post_order_id' ) );
		} else {
			if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
				if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
					$o_id = !empty( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
					$p_id = !empty( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : '';
				}
				if ( empty( $o_id ) && !empty( $p_id ) ) {
					$post_order_id  = $post_or_order_object->ID;
					$params = array();
					$feeds  = $this->get_available_feeds_for_manual_sync();
				} else {
					$post_order_id  = $post_or_order_object->get_id();
					$params = array();
					$feeds  = $this->get_available_feeds_for_manual_sync();
				}
			} else {
				$post_order_id  = $post_or_order_object->ID;
				$params = array();
				$feeds  = $this->get_available_feeds_for_manual_sync();
			}
			$this->render_meta_box_data( 'order-meta-box', compact( 'feeds', 'post_order_id' ) );
		}
	}

	/**
	 * Add shop order meta box to delet the zoho ids on woocommerce.
	 */
	public function shop_order_meta_box_delete_data() {
		$params = array();
		$feeds  = $this->get_available_feeds_for_manual_sync();
		$this->render_meta_box_data( 'order-meta-box-woo-data-delete', compact( 'feeds' ) );
	}

	/**
	 * Check for default feed post.
	 *
	 * @param  string $crm_object  CRM Object.
	 * @return int|bool             Feed id or False.
	 */
	public function check_for_feed_post( $crm_object ) {

		$query_args = array(
			array(
				'key'     => 'crm_object',
				'value'   => $crm_object,
				'compare' => '=',
			),
			array(
				'key'     => 'default_feed',
				'value'   => true,
				'compare' => '=',
			),
		);
		$args       = array(
			'post_type'   => 'wciz_crm_feed',
			'post_status' => array( 'publish', 'draft' ),
			'meta_query'  => $query_args, //phpcs:ignore
		);
		$feeds      = get_posts( $args );

		$feed_id = ! empty( $feeds ) ? $feeds[0]->ID : false;

		return $feed_id;
	}

	/**
	 * Get feeds for contact object.
	 *
	 * @return Array Array of feeds.
	 */
	public function get_user_contact_feeds() {

		$query_args = array(
			array(
				'key'     => 'crm_object',
				'value'   => 'Contacts',
				'compare' => '=',
			),
			array(
				'key'     => 'feed_event',
				'value'   => 'user_update_create',
				'compare' => '=',
			),
		);
		$args       = array(
			'post_type'   => 'wciz_crm_feed',
			'post_status' => 'publish',
			'meta_query'  => $query_args, //phpcs:ignore
		);
		$feeds      = get_posts( $args );
		return $feeds;
	}

	/**
	 * Get feeds for showing in manual sync meta box.
	 *
	 * @return array Array of feeds.
	 */
	public function get_available_feeds_for_manual_sync() {
		
		if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
			if ( isset( $_GET['id'] ) && ( !empty( $_GET['id'] ) ) && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
				$postid = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
				$post_type = OrderUtil::get_order_type( $postid );
				$all_feeds = $this->get_available_feeds();
				if ( ! $all_feeds ) {
					return array();
				}
				$feed_list      = array();
				$request_module = Woo_Crm_Integration_Request_Module::get_instance();
				foreach ( $all_feeds as $key => $feed ) {
					$crm_object = get_post_meta( $feed->ID, 'crm_object', true );
					$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed->ID );
					if ( $post_type === $woo_object ) {
						$feed_object             = get_post_meta( $feed->ID, 'crm_object', true );
						if ( 'abandoned_cart' == $feed_object ) {
							continue;
						}
						$feed_list[ $feed->ID ] = $feed->post_title;
					}
				}
			} elseif ( isset( $_GET['page'] ) && 'wc-orders' == $_GET['page'] ) {
					$all_feeds = $this->get_available_feeds();
				if ( ! $all_feeds ) {
					return array();
				}
					$feed_list      = array();
					$request_module = Woo_Crm_Integration_Request_Module::get_instance();
				foreach ( $all_feeds as $key => $feed ) {
					$crm_object = get_post_meta( $feed->ID, 'crm_object', true );
					$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed->ID );
					if ( 'product' !== $woo_object ) {
						$feed_object             = get_post_meta( $feed->ID, 'crm_object', true );
						if ( 'abandoned_cart' == $feed_object ) {
							continue;
						}
						$feed_list[ $feed->ID ] = $feed->post_title;
					}
				}
			} else {
				global $post;
				$all_feeds = $this->get_available_feeds();
				if ( ! $all_feeds ) {
					return array();
				}
				$feed_list      = array();
				$request_module = Woo_Crm_Integration_Request_Module::get_instance();
				foreach ( $all_feeds as $key => $feed ) {
					$crm_object = get_post_meta( $feed->ID, 'crm_object', true );
					$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed->ID );
					if ( $post->post_type === $woo_object ) {
						$feed_object             = get_post_meta( $feed->ID, 'crm_object', true );
						if ( 'abandoned_cart' == $feed_object ) {
							continue;
						}
						$feed_list[ $feed->ID ] = $feed->post_title;
					}
				}
			}
		} else {
			global $post;
			$all_feeds = $this->get_available_feeds();
			if ( ! $all_feeds ) {
				return array();
			}
			$feed_list      = array();
			$request_module = Woo_Crm_Integration_Request_Module::get_instance();
			foreach ( $all_feeds as $key => $feed ) {
				$crm_object = get_post_meta( $feed->ID, 'crm_object', true );
				$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed->ID );
				if ( $post->post_type === $woo_object ) {
					$feed_object             = get_post_meta( $feed->ID, 'crm_object', true );
					if ( 'abandoned_cart' == $feed_object ) {
						continue;
					}
					$feed_list[ $feed->ID ] = $feed->post_title;
				}
			}
		}
		
		return $feed_list;
	}

	/**
	 * Post type feeds conditional filter metabox.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function feeds_wciz_condition_render() {
		global $post;
		$params                  = array();
		$class_zoho_fw           = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$selected_object         = $this->get_feed_data( $post->ID, 'crm_object', '' );
		$params['enable_filter'] = $this->get_feed_data( $post->ID, 'wciz-zoho-enable-filters', '' );
		$params['condition']     = $this->get_feed_data( $post->ID, 'wciz-zoho-condition-field', array() );
		$params['fields']        = $class_zoho_fw->getObjectFilteringField( $selected_object );
		$this->render_meta_box_data( 'opt-in-condition', compact( 'params' ) );
	}
}
