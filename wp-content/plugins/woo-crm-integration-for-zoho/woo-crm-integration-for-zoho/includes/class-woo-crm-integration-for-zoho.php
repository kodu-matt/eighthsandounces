<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */
class Woo_Crm_Integration_For_Zoho {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      Woo_Crm_Integration_For_Zoho_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WOO_CRM_INTEGRATION_FOR_ZOHO_VERSION' ) ) {
			$this->version = WOO_CRM_INTEGRATION_FOR_ZOHO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woo-crm-integration-for-zoho';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->initialize_cpt();
		$this->define_woo_hooks();
		$this->define_ajax_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woo_Crm_Integration_For_Zoho_Loader. Orchestrates the hooks of the plugin.
	 * - Woo_Crm_Integration_For_Zoho_I18n. Defines internationalization functionality.
	 * - Woo_Crm_Integration_For_Zoho_Admin. Defines all hooks for the admin area.
	 * - Woo_Crm_Integration_For_Zoho_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function load_dependencies() {

		/**
		 * The file reponsible for defining all gobal functions used in plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'woo-includes/woo-functions.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'woo-crm-fw/api/class-woo-crm-integration-api-base.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'woo-crm-fw/api/class-woo-crm-integration-zoho-api.php';

		/**
		 * The class responsible for defining all woo concerned data retrival.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'woo-crm-fw/woo/class-woo-crm-integration-connect-framework.php';

		/**
		 * The class responsible for defining all woo concerned requests.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'woo-crm-fw/woo/class-woo-crm-integration-request-module.php';

		/**
		 * The class responsible for defining all actions that occur in the ajax request.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-woo-crm-integration-for-zoho-ajax.php';

		/**
		 * The class responsible for defining all actions related to cpt.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-woo-crm-integration-for-zoho-cpt.php';

		/**
		 * The class responsible for defining api modules respective to zoho.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-woo-crm-integration-for-zoho-fw.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-woo-crm-integration-for-zoho-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-woo-crm-integration-for-zoho-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-woo-crm-integration-for-zoho-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-woo-crm-integration-for-zoho-public.php';
		$this->loader = new Woo_Crm_Integration_For_Zoho_Loader();

		/**
		 * The class responsible for defining ajax callbacks for CF7 
		 * integration to zoho.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-cf7-woo-crm-integration-with-zoho-ajax-handler.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woo_Crm_Integration_For_Zoho_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function set_locale() {

		$plugin_i18n = new Woo_Crm_Integration_For_Zoho_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woo_Crm_Integration_For_Zoho_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add plugins menu page.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu_page', 99 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'remove_default_submenu', 99 );

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'wciz_add_plugins_menus_array', $plugin_admin, 'crm_admin_submenu_page', 15 );
		$this->loader->add_filter( 'wciz_add_crm_plugins_menus_array', $plugin_admin, 'crm_admin_submenu_page', 15 );

		// Initiate all functionality of crm.
		$this->loader->add_action( 'woocommerce_init', $plugin_admin, 'woo_crm_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init_action' );

		// Specify components.
		$this->loader->add_filter( 'wciz_crm_woo_tabs', $plugin_admin, 'return_tabs' );

		// Includes all the templates.
		$this->loader->add_action( 'wciz_crm_connect_render_tab', $plugin_admin, 'render_tabs' );
		$this->loader->add_action( 'wciz_crm_connect_render_tab_content', $plugin_admin, 'render_tab_contents' );

		// Clear log callback.
		$this->loader->add_action( 'wciz_woo_zoho_clear_log', $plugin_admin, 'clear_sync_log' );

		// Add cron schedule for 5 mins.
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'add_background_sync_schedule' );

		// Schedule background sync.
		$this->loader->add_filter( 'admin_init', $plugin_admin, 'schedule_background_sync_event' );

		// Background Sync callback.
		$this->loader->add_action( 'wciz_woo_zoho_background_sync', $plugin_admin, 'perform_background_sync' );

		// Fetch Products callback.
		$this->loader->add_action( 'wciz_woo_zoho_product_fetch', $plugin_admin, 'perform_product_fetch' );

		// Fetch Orders callback.
		$this->loader->add_action( 'wciz_woo_zoho_order_fetch', $plugin_admin, 'perform_order_fetch' );

		// Add nounce field.
		$this->loader->add_action( 'woocommerce_product_options_general_product_data', $plugin_admin, 'add_nonce_product_nonce' );

		$this->loader->add_filter( 'woo_crm_woo_zoho_new_order_request', $plugin_admin, 'filter_salesorder_request', 10, 3 );

		$this->loader->add_filter( 'woo_crm_woo_zoho_user_request', $plugin_admin, 'get_filtered_user_request', 10, 3 );
		$this->loader->add_filter( 'woo_crm_woo_zoho_account_request', $plugin_admin, 'get_filtered_account_request', 10, 3 );

		$this->loader->add_filter( 'woo_crm_woo_zoho_product_request', $plugin_admin, 'get_filtered_product_request', 10, 3 );

		// Create dummy hook to manage param count.
		$this->loader->add_filter( 'edit_user_profile_update', $plugin_admin, 'add_wciz_edit_user_profile_update_hook', 10, 1 );
		$this->loader->add_filter( 'personal_options_update', $plugin_admin, 'add_wciz_edit_user_profile_update_hook', 10, 1 );
	
		$this->loader->add_action( 'parent_file', $plugin_admin, 'wciz_prefix_highlight_taxonomy_parent_menu' );
		$this->loader->add_filter( 'submenu_file', $plugin_admin, 'wciz_set_submenu_file_to_handle_menu_for_wp_pages', 10, 2 );

		$this->loader->add_filter( 'woo_zoho_order_field_values_filter', $plugin_admin, 'wciz_woo_zoho_order_field_values_filter_callback', 10, 2 );

		// new code
		if ( 'yes' == get_option( 'wciz_zoho_enable_product_deletion_woo_to_zoho', 'no' ) ) {
			$this->loader->add_action( 'before_delete_post', $plugin_admin, 'custom_function_on_product_delete' );
			$this->loader->add_action( 'woocommerce_before_delete_product_variation', $plugin_admin, 'custom_function_on_product_delete',10,1 );

			$this->loader->add_action( 'wp_trash_post', $plugin_admin, 'action_wp_trash_post', 10, 1 );

			$this->loader->add_action( 'untrash_post', $plugin_admin, 'action_wp_untrash_post', 10, 1 );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_public_hooks() {

		$plugin_public = new Woo_Crm_Integration_For_Zoho_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'profile_update', $plugin_public, 'add_wciz_edit_user_profile_update_hook', 10, 1 );

		$this->loader->add_filter( 'wpcf7_before_send_mail', $plugin_public, 'wciz_cf7_integration_fetch_input_data', 99, 1 );
	
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_action( 'wp_ajax_nopriv_zoho_capture_billing_email', $plugin_public, 'zoho_capture_billing_email' );
		$this->loader->add_action( 'wp_ajax_zoho_capture_billing_email', $plugin_public, 'zoho_capture_billing_email' );
	
		$this->loader->add_action( 'woocommerce_cart_updated', $plugin_public, 'zoho_crm_capture_cart_data' );

		$this->loader->add_action( 'template_redirect', $plugin_public, 'zoho_load_cart_contents' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woo_Crm_Integration_For_Zoho_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the ajax requests.
	 *
	 * @since  1.0.0
	 */
	private function define_ajax_hooks() {

		$ajax_module = new Woo_Crm_Integration_For_Zoho_Ajax();
		// On new/update products.
		$this->loader->add_action(
			'wp_ajax_wciz_woo_zoho_ajax',
			$ajax_module,
			'wciz_woo_zoho_ajax_cb'
		);

		$this->loader->add_action(
			'wp_ajax_get_datatable_data',
			$ajax_module,
			'get_datatable_data_cb'
		);

		$this->loader->add_action(
			'wp_ajax_get_zoho_to_woocommerce_sync_log_data',
			$ajax_module,
			'get_zoho_to_woocommerce_sync_log_data'
		);

		$ajax_cb = new wciz_Cf7_Integration_With_Zoho_Ajax_Handler();

		// // All ajax callbacks.
		$this->loader->add_action( 'wp_ajax_mwb_cf7_zoho_ajax_request', $ajax_cb, 'mwb_cf7_integration_ajax_callback' );
		// Data table callback.
		$this->loader->add_action( 'wp_ajax_get_datatable_logs', $ajax_cb, 'get_datatable_data_cb' );
	}

	/**
	 * Register all of the hooks related to the woocommerce objects add/updates.
	 *
	 * @since 1.0.0
	 *
	 * @throws Exception Exception.
	 *
	 * @return null
	 */
	private function define_woo_hooks() {

		if ( ! Woo_Crm_Integration_For_Zoho_Admin::is_connection_active() ) {
			return;
		}

		$request_module = new Woo_Crm_Integration_Request_Module();

		// Get all Feeds and then extend the hooks.
		$zoho = Woo_Crm_Integration_For_Zoho_Fw::get_instance();

		// Add all callbacks.
		$hookable_callbacks = $zoho->get_hook_requests_for_feed();

		if ( empty( $hookable_callbacks ) ) {
			return;
		}

		/**
		 * Remove all order status change sync feeds. Run them once.
		 * 'woocommerce_order_status_pending'
		 * 'woocommerce_order_status_failed'
		 * 'woocommerce_order_fully_refunded'
		 * 'woocommerce_order_partially_refunded'
		 * 'woocommerce_order_status_cancelled'
		 * 'woocommerce_order_status_completed'
		 * 'woocommerce_order_status_on-hold'
		 * 'woocommerce_order_status_processing'
		 */
		$feeds      = $request_module->get_feed_id_by_request();

		$hook_delay = 100;

		if ( ! empty( $feeds ) && is_array( $feeds ) ) {

			$hooks = array(
				'woocommerce_order_status_pending',
				'woocommerce_order_status_failed',
				'woocommerce_order_fully_refunded',
				'woocommerce_order_partially_refunded',
				'woocommerce_order_status_cancelled',
				'woocommerce_order_status_completed',
				'woocommerce_order_status_on-hold',
				'woocommerce_order_status_processing',
				'woocommerce_order_status_pre-ordered',
			);

			foreach ( $hooks as $key => $hook ) {
				$this->loader->add_action(
					$hook,
					$request_module,
					'shop_order_status_changed',
					$hook_delay,
					$zoho->get_hook_param_count( $hook )
				);
			}

			foreach ( $feeds as $key => $feeds_id ) {
				unset( $hookable_callbacks[ $feeds_id ] );
			}
		}

		// Same events and callbacks entires will be handled in the feeds.
		$hookable_callbacks = array_unique( $hookable_callbacks, SORT_REGULAR );

		// Now add single event callbacks.
		if ( ! empty( $hookable_callbacks ) && is_array( $hookable_callbacks ) ) {
			foreach ( $hookable_callbacks as $feed_id => $hookable_callback ) {

				try {

					if ( is_array( $hookable_callback['hook'] ) ) {
						foreach ( $hookable_callback['hook'] as $key => $single_hook ) {
							$this->loader->add_action(
								$single_hook,
								$request_module,
								$hookable_callback['callback'],
								$hook_delay,
								$zoho->get_hook_param_count(
									$single_hook
								)
							);

							$hook_delay++;
						}
					} else {
						$this->loader->add_action(
							$hookable_callback['hook'],
							$request_module,
							$hookable_callback['callback'],
							$hook_delay,
							$zoho->get_hook_param_count(
								$hookable_callback['hook']
							)
						);
					}

					$hook_delay++;

				} catch ( \Throwable $th ) {
					throw new Exception( esc_html( $th->getMessage() ), 1 );
				}
			}
		}

		$this->loader->add_action(
			'wciz_zoho_crm_trigger_associated_feeds',
			$request_module,
			'may_be_trigger_associated_zoho_feeds',
			10,
			3
		);
	}

	/**
	 * Initialize custom post type class.
	 *
	 * @since  1.0.0
	 */
	private function initialize_cpt() {

		$cpt_module = new Woo_Crm_Integration_For_Zoho_Cpt();
		// Register custom posttypes.
		$this->loader->add_action( 'init', $cpt_module, 'register_plugin_cpt' );
		// Add meta box for feeds.
		$this->loader->add_action( 'add_meta_boxes', $cpt_module, 'add_feed_meta_boxes', 10, 2 );
		// Save meta box data.
		$this->loader->add_action( 'save_post', $cpt_module, 'save_metabox_data' );

		if ( Woo_Crm_Integration_For_Zoho_Admin::is_connection_active() ) {
			$this->loader->add_action( 'add_meta_boxes', $cpt_module, 'add_manual_sync_meta_boxes', 10, 2 );
		}
	}
}
