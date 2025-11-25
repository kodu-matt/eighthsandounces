<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/admin
 */

use Automattic\WooCommerce\Internal\Admin\Orders\MetaBoxes\CustomMetaBox;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/admin
 */
class Woo_Crm_Integration_For_Zoho_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Crm_Integration_For_Zoho_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Crm_Integration_For_Zoho_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		$orderpage_screenid = Integration_With_Woo_Zoho_Order_Handler::wciz_get_shop_order_screen_id();

		wp_enqueue_style( 'crm-zoho-global', plugin_dir_url( __FILE__ ) . 'css/woo-crm-integration-for-zoho-global-admin.css', array(), $this->version, 'all' );

		$min = '.min';

		if ( ! empty( $screen ) && ( in_array( $screen->id, $this->get_plugin_admin_screens(), true )
		|| ( ( $orderpage_screenid === $screen->id ) || ( 'product' === $screen->id ) ) ) ) { //phpcs:ignore
			wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url( __DIR__ ) . 'packages/select2/select2.min.css', array(), $this->version, 'all' );

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-crm-integration-for-zoho-admin.css', array(), time(), 'all' );
		}

		if ( $this->is_valid_screen() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wciz-cf7-integration-with-zoho-admin.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url( __DIR__ ) . 'packages/select2/select2.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-tooltip', plugin_dir_url( __DIR__ ) . 'packages/jq-tiptip/tooltip.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-datatable-css', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/css/jquery.dataTables.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-animate', plugin_dir_url( __DIR__ ) . 'packages/animate/animate.min.css', array(), $this->version, 'all' );

		}

		wp_enqueue_style( $this->plugin_name . '-menu-style', plugin_dir_url( __FILE__ ) . 'css/woo-crm-integration-for-zoho-admin-menu' . $min . '.css', array(), $this->version, 'all' );

		wp_register_style( 'woocommerce_select2', WC()->plugin_url() . '/assets/css/select2.css', array(), WC_VERSION );

		wp_enqueue_style( 'woocommerce_select2' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Crm_Integration_For_Zoho_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Crm_Integration_For_Zoho_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		$screen = get_current_screen();
		$orderpage_screenid = Integration_With_Woo_Zoho_Order_Handler::wciz_get_shop_order_screen_id();

		// Deactivation screen.
		wp_enqueue_script( 'crm-zoho-global-script', plugin_dir_url( __FILE__ ) . 'js/woo-crm-integration-for-zoho-global-admin.js', array( 'jquery' ), $this->version, false );

		$min = '.min';

		if ( ! empty( $screen ) && ( ( $orderpage_screenid === $screen->id ) || ( 'product' === $screen->id ) ) ) {

			wp_enqueue_script( 'crm-connect-sweetalert', plugin_dir_url( __FILE__ ) . 'js/sweet-alert' . $min . '.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( 'crm-connnect-meta-box', plugin_dir_url( __FILE__ ) . 'js/order-meta-box-script.js', array( 'jquery' ), time(), false );
			$ajax_data = array(
				'crm'         => 'zoho',
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'ajax_action' => 'wciz_woo_zoho_ajax',
				'ajax_nonce'  => wp_create_nonce( 'ajax_nonce' ),
			);
			wp_localize_script( 'crm-connnect-meta-box', 'ajax_data', $ajax_data );
		}

		if ( ! empty( $screen ) && in_array( $screen->id, $this->get_plugin_admin_screens() ) ) { //phpcs:ignore

			$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
			$all_feeds        = $framework->get_all_feed();
			$default_feeds    = array();
			$additional_feeds = array();
			$product_feeds    = array();
			$request_module   = Woo_Crm_Integration_Request_Module::get_instance();
			if ( ! empty( $all_feeds ) && is_array( $all_feeds ) ) {
				foreach ( $all_feeds as $key => $feed_id ) {
					$is_default_feed = $framework->get_feed( $feed_id, 'default_feed' );
					if ( $is_default_feed ) {
						array_push( $default_feeds, $feed_id );
					} else {
						array_push( $additional_feeds, $feed_id );
					}
				}
			}
			if ( ! empty( $default_feeds ) ) {
				foreach ( $default_feeds as $key => $feed_id ) {
					$record_type = $framework->get_feed( $feed_id, 'crm_object' );
					if ( 'Products' === $record_type ) {
						$product_feeds['0'] = $feed_id;
					}
				}
			}
			if ( ! empty( $additional_feeds ) ) {
				foreach ( $additional_feeds as $key => $feed_id ) {
					$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
					if ( 'product' === $woo_object ) {
						array_push( $product_feeds, $feed_id );
					}
				}
			}

			if ( !empty( $product_feeds ) ) {
				ksort( $product_feeds );

				$feed_id = $product_feeds[0];
			} else {
				$feed_id = 0;
			}

			wp_enqueue_script( 'crm-connect-sweetalert', plugin_dir_url( __FILE__ ) . 'js/sweet-alert' . $min . '.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-crm-integration-for-zoho-admin.js', array( 'jquery', 'wc-enhanced-select' ), time(), false );

			$ajax_data = array(
				'crm'                => 'zoho',
				'ajax_url'           => admin_url( 'admin-ajax.php' ),
				'ajax_action'        => 'wciz_woo_zoho_ajax',
				'ajax_nonce'         => wp_create_nonce( 'ajax_nonce' ),
				'feed_back_link'     => admin_url( 'admin.php?page=woo-crm-integration-for-zoho#wciz-feeds' ),
				'feed_back_text'     => __( 'Back to feeds', 'woo-crm-integration-for-zoho' ),
				'delete_icon'        => WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/trash.svg',
				'notice'             => $this->get_admin_notice(),
				'disconnect'         => array(
					'title'      => __( 'Attempt to disconnect Account! Are you sure?', 'woo-crm-integration-for-zoho' ),
					'yes'        => __( 'Disconnect', 'woo-crm-integration-for-zoho' ),
					'no'         => __( 'Cancel', 'woo-crm-integration-for-zoho' ),
					'processing' => __( 'Disconnecting...', 'woo-crm-integration-for-zoho' ),
				),
				'sync_product'         => array(
					'title'      => __( 'Please choose one way from below options to sync data', 'woo-crm-integration-for-zoho' ),
					'instant'        => __( 'Instant', 'woo-crm-integration-for-zoho' ),
					'background'         => __( 'In Background', 'woo-crm-integration-for-zoho' ),
					'processing' => __( 'Syncing...', 'woo-crm-integration-for-zoho' ),
				),
				'prod_feed_id'           => $feed_id,
				'skip'               => array(
					'title'             => __( 'Your have more than 1000 entries to be synced. We recommend you to skip for now. Please click on Initiate Sync to continue with the sync.', 'woo-crm-integration-for-zoho' ),
					'yes'               => __( 'Initiate Sync', 'woo-crm-integration-for-zoho' ),
					'no'                => __( 'Skip', 'woo-crm-integration-for-zoho' ),
					'processing'        => __( 'Skipping...', 'woo-crm-integration-for-zoho' ),
					'update_keys_title' => __( 'Attention Required', 'woo-crm-integration-for-zoho' ),
					'update_keys_msg'   => __( 'Before syncing, please update the existing keys to WCIZ.', 'woo-crm-integration-for-zoho' ),
					'update_keys'       => __( 'Update keys', 'woo-crm-integration-for-zoho' ),
				),
				'connect'            => array(
					'authorize' => __( 'Authorize', 'woo-crm-integration-for-zoho' ),
					'login'     => __( 'Sign in & Authorize', 'woo-crm-integration-for-zoho' ),
				),
				'boolean'            => array(
					'true'  => __( 'True', 'woo-crm-integration-for-zoho' ),
					'false' => __( 'False', 'woo-crm-integration-for-zoho' ),
				),
				'ajax_error'         => __( 'An error occured!', 'woo-crm-integration-for-zoho' ),
				'date_range_note'         => __( 'If no date is selected, all data will be synced.', 'woo-crm-integration-for-zoho' ),
				'order_status'       => $this->get_woo_order_statuses(),
				'mapped_zoho_status' => get_option( 'wciz_zoho_status_mapping', array() ),
				'plugin_directory_url' => WOO_CRM_INTEGRATION_ZOHO_URL,
			);

			if ( 'woo-crm_page_woo-crm-integration-for-zoho' === $screen->id || 'wciz_crm_feed' === $screen->post_type ) {
				$ajax_data['feed_form_settings'] = $this->get_feed_form_settings();
			}

			$this->clear_admin_notice();
			wp_localize_script( $this->plugin_name, 'ajax_data', $ajax_data );
			wp_enqueue_script( $this->plugin_name . '-select2', plugin_dir_url( __DIR__ ) . 'packages/select2/select2.min.js', array( 'jquery' ), $this->version, false );

		}

		if ( $this->is_valid_screen() ) {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb-cf7-integration-with-zoho-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-select2', plugin_dir_url( __DIR__ ) . 'packages/select2/select2.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-swal2', plugin_dir_url( __DIR__ ) . 'packages/sweet-alert2/sweet-alert2.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-tooltip', plugin_dir_url( __DIR__ ) . 'packages/jq-tiptip/jquery.tipTip.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-datatable-responsive-js', plugin_dir_url( __DIR__ ) . 'packages/datatables.net-responsive/js/dataTables.responsive.min.js', array(), $this->version, false );

			wp_enqueue_script( $this->plugin_name . '-datatable-js', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/js/jquery.dataTables.min.js', array(), $this->version, false );

		wp_enqueue_style( 'wciz-jquery-uic', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/css/jquery-ui.css', array(), $this->version, 'all');
		wp_enqueue_script( 'wciz-jquery-uij', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/js/jquery-ui.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_style( 'wciz-datatables-css', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/css/jquery.dataTables.min.css', array(), $this->version, 'all');
		wp_enqueue_style( 'wciz-buttons-datatables-css', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/css/buttons.dataTables.min.css', array(), $this->version, 'all');

		// wp_enqueue_script( 'wciz-datatables-js', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'wciz-datatables-buttons-js', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/js/dataTables.buttons.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'wciz-jszip-js', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/js/jszip.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'wciz-pdfmake-js', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/js/pdfmake.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'wciz-vfs_fonts-js', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/js/vfs_fonts.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'wciz-buttons-print-js', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/js/buttons.print.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'wciz-buttons-html5-js', plugin_dir_url( __DIR__ ) . 'packages/datatables/media/js/buttons.html5.min.js', array( 'jquery' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name,
				'mwb_cf7_integration_ajax_data',
				array(
					'crm'           => 'zoho',
					'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
					'ajaxNonce'     => wp_create_nonce( 'mwb_cf7_zoho_nonce' ),
					'ajaxAction'    => 'mwb_cf7_zoho_ajax_request',
					'feedBackLink'  => admin_url( 'admin.php?page=wciz_zoho_cf7&tab=feeds' ),
					'feedBackText'  => esc_html__( 'Back to feeds', 'woo-crm-integration-for-zoho' ),
					'isPage'        => isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '', // phpcs:ignore
					'criticalError' => esc_html__( 'Internal server error', 'woo-crm-integration-for-zoho' ),
					'trashIcon'     => WOO_CRM_INTEGRATION_ZOHO_URL . 'admin/images/trash.svg',
					'api_key_image' => WOO_CRM_INTEGRATION_ZOHO_URL . 'admin/images/api.png',
					'adminUrl'      => rtrim( admin_url(), '/' ),
				)
			);
		}
	}

	/**
	 * Get plugin admin screens.
	 *
	 * @return array Array of admin screens.
	 */
	public function get_plugin_admin_screens() {
		return array( 'woo-crm_page_woo-crm-integration-for-zoho', 'wciz_crm_feed' );
	}

	/**
	 * Check if boilerplate is active.
	 *
	 * @param  string $submenu Submenu array.
	 * @return bool
	 */
	public function is_boilerplate_active( $submenu = '' ) {
		if ( ! empty( $submenu ) ) {
			return ( is_array( $submenu ) && array_key_exists( 'wciz-plugins', $submenu ) );
		} else {
			return false;
		}
	}

	/**
	 * Include admin menu
	 */
	public function add_admin_menu_page() {

		global $submenu;

		if ( false == $this->is_boilerplate_active( $submenu ) ) { //phpcs:ignore

			add_menu_page(
				'Woo CRM',
				'Woo CRM',
				'manage_options',
				'wciz-crm-plugins',
				'',
				'dashicons-admin-links',
				// WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/wciz_logo.png',
				15
			);
			/**
			 * Filters the value of Zoho plugin menu array.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed  array.
			 */
			$wdc_menus = apply_filters( 'wciz_add_crm_plugins_menus_array', array() );

			if ( is_array( $wdc_menus ) && ! empty( $wdc_menus ) ) {
				foreach ( $wdc_menus as $wdc_key => $wdc_value ) {
					add_submenu_page( 'wciz-crm-plugins', $wdc_value['name'], $wdc_value['name'], 'manage_options', $wdc_value['menu_link'], array( $wdc_value['instance'], $wdc_value['function'] ) );
				}
			}
		}
	}

	/**
	 * CRM Submenu crm_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function crm_admin_submenu_page( $menus = array() ) {

		$menus[] = array(
			'name'      => 'Woo CRM connect - Zoho',
			'slug'      => 'woo-crm-integration-for-zoho',
			'menu_link' => 'woo-crm-integration-for-zoho',
			'instance'  => $this,
			'function'  => 'include_admin_menu_display',
		);
		if ( $this->wciz_zcf7_is_plugin_active() && '1' == get_option( 'wciz_woo_zoho_authorised', false ) && '1' == get_option( 'wciz_woo_zoho_onboarding_completed', false ) ) {
			$menus[] = array(
				'name'      => 'Woo CRM Zoho - CF7',
				'slug'      => 'wciz_zoho_cf7',
				'menu_link' => 'wciz_zoho_cf7',
				'instance'  => $this,
				'function'  => 'wciz_zoho_cf7_submenu_cb',
			);
		}

		return $menus;
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since   1.0.0
	 */
	public function remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'wciz-crm-plugins', $submenu ) ) {
			if ( isset( $submenu['wciz-crm-plugins'][0] ) ) {
				unset( $submenu['wciz-crm-plugins'][0] );
			}
		}
	}

	/**
	 * Include_settings_display function
	 */
	public function include_admin_menu_display() {
		$file_path = '/partials/woo-crm-integration-for-zoho-admin-display.php';
		self::load_template( $file_path );
	}

	/**
	 * Zoho sub-menu callback function.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function wciz_zoho_cf7_submenu_cb() {
		$file_path          = '/partials/zoho-cf7-integration-admin-display.php';
		$params['headings'] = array(
			'name'    => esc_html__( 'WCIZ CF7 Integration with ZOHO CRM', 'woo-crm-integration-for-zoho' ),
			'version' => '1.0.0',
		);
		self::load_template( $file_path, $params );
	}

	/**
	 * Check and include admin view file
	 *
	 * @param string $file_path Relative path of file.
	 * @param array  $params Array of extra params.
	 * @param bool   $base   If is base.
	 */
	public static function load_template( $file_path, $params = array(), $base = false ) {

		try {

			$result = wc_get_template(
				$file_path,
				$params,
				'',
				$base ? $base : plugin_dir_path( __FILE__ )
			);

		} catch ( \Throwable $th ) {
			echo esc_html( $th->getMessage() );
			wp_die();
		}
	}

	/**
	 * Initiate all functionality of crm.
	 */
	public function woo_crm_init() {

		// Add authorisation screen.
		add_action( 'wciz_woo_connect_authorisation_screen', array( $this, 'init_authorisation' ) );

		// Add dashbo screen.
		add_action( 'wciz_woo_connect_dashboard_screen', array( $this, 'init_dashboard' ) );

		// Add dashbo screen.
		add_action( 'wciz_zcf7_cf7_nav_tab', array( $this, 'render_navigation_tab' ) );
	}

	/**
	 * Initiate authorisation screen.
	 */
	public function init_authorisation() {

		$settings  = array();
		$file_path = 'partials/templates/authorisation-screen.php';
		self::load_template( $file_path );
	}

	/**
	 * Initiate all functionality of crm.
	 */
	public function init_dashboard() {

		$settings  = array();
		$file_path = 'partials/templates/dashboard-screen.php';
		self::load_template( $file_path );
	}

	/**
	 * Map all redirects on admin portal.
	 */
	public function admin_init_action() {
		// Get authorization code.
		if ( isset( $_GET['wciz_get_zoho_code'] ) && true == $_GET['wciz_get_zoho_code'] ) { //phpcs:ignore
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
                
				$client_id = ! empty( $_GET['client_id'] ) ? sanitize_text_field( wp_unslash( $_GET['client_id'] ) ) : '';
				$secret_id = ! empty( $_GET['secret_id'] ) ? sanitize_text_field( wp_unslash( $_GET['secret_id'] ) ) : '';
				$domain    = ! empty( $_GET['domain'] ) ? sanitize_text_field( wp_unslash( $_GET['domain'] ) ) : '';
				$use_gapp  = ! empty( $_GET['use_gapp'] ) ? sanitize_text_field( wp_unslash( $_GET['use_gapp'] ) ) : true;
				if ( false === $use_gapp || 'false' === $use_gapp ) {
					if ( empty( $client_id ) || empty( $secret_id ) || empty( $domain ) ) {
						return false;
					}
				} elseif ( true === $use_gapp || 'true' === $use_gapp ) {
					if ( empty( $domain ) ) {
						return false;
					}

					if ( 'com.cn' == $domain ) {
						$client_id = '1000.T8QDVP4L47W1LMB607YGWZYUQ6JZDI';
						$secret_id = '3ea5940728055d03a45cb6bbf5a86ca12ba0065f7f';
					} else {
						$client_id = '1000.904FIO5R5X73RBFUZ6UG1V9RN4GNXH';
						$secret_id = 'c9b53699f92c53fe7ee11ca783a4aa0d1f64da5c88';
					}
				}

				update_option( 'wciz_zoho_client_id', $client_id );
				update_option( 'wciz_zoho_secret_id', $secret_id );
				update_option( 'wciz_zoho_domain', $domain );
				update_option( 'wciz_zoho_use_gapp', $use_gapp );

				$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
				$auth_url = $zoho_api->get_auth_code_url();
				

				add_filter('allowed_redirect_hosts', function ( $hosts ) {
					$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
					$auth_url = $zoho_api->get_auth_code_url();
					$host = str_replace('https://', '', $auth_url);
					$host = explode('/', $host);
					$hosts[] = $host[0];
					$hosts[] = 'auth.makewebbetter.com';
					return $hosts;
				});

				wp_safe_redirect( $auth_url );
				exit;
			}
		}

		// Get refesh token using authcode and api keys.
		if ( isset( $_GET['code'] ) && isset( $_GET['accounts-server'] ) && isset( $_GET['location'] ) && isset( $_GET['mwb_plugin'] ) && 'zoho-crm' == $_GET['mwb_plugin'] ) {
            
			if ( !get_option( 'wciz_woo_zoho_authorised' ) ) {
               
				if ( empty( $_GET['state'] ) ) {

					$message = __( 'Security check failed, missing state parameter!', 'woo-crm-integration-for-zoho' );
					$this->add_admin_notice( $message, false );
	
				} elseif ( ! wciz_woo_zoho_verify_nonce( sanitize_text_field( wp_unslash( $_GET['state'] ) ) ) ) {
	
					$message = __( 'Security check failed, state not verified!!', 'woo-crm-integration-for-zoho' );
					$this->add_admin_notice( $message, false );
	
				} else {
	        
					$code     = sanitize_text_field( wp_unslash( $_GET['code'] ) );
					$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
					$success  = $zoho_api->get_refresh_token_data( $code );
	                
	               // print_r($success);
	               // exit;
					update_option( 'wciz_woo_zoho_authorised', $success );
					if ( ! $success ) {
						$message = __( 'Confirm your login details!', 'woo-crm-integration-for-zoho' );
						$this->add_admin_notice( $message, $success );
					}
				}
				wp_safe_redirect( admin_url( 'admin.php?page=woo-crm-integration-for-zoho' ) );
			} else {
			
				wp_safe_redirect( admin_url( 'admin.php?page=woo-crm-integration-for-zoho' ) );
			}
			exit();
		} else if ( isset( $_GET['code'] ) && isset( $_GET['accounts-server'] ) && isset( $_GET['location'] ) && isset( $_GET['state'] ) && false !== strpos( sanitize_text_field( wp_unslash($_GET['state'] ) ), 'zoho-crm') && ! isset( $_GET['mwb_plugin'] ) ) {
            
			$state_params = explode( '?', sanitize_text_field( wp_unslash( $_GET['state'] ) ) );
			if ( !empty( $state_params[1] ) ) {
				$plugin_name_params = explode( '&', $state_params[1] );
				$plugin_name = explode( '=', $plugin_name_params[2] );
				if ( 'mwb_plugin' == $plugin_name[0] && 'zoho-crm' == $plugin_name[1] ) {
					if ( !get_option( 'wciz_woo_zoho_authorised' ) ) {
						if ( empty( $_GET['state'] ) ) {
		
							$message = __( 'Security check failed, missing state parameter!', 'woo-crm-integration-for-zoho' );
							$this->add_admin_notice( $message, false );
			
						} elseif ( ! wciz_woo_zoho_verify_nonce( sanitize_text_field( wp_unslash( $_GET['state'] ) ) ) ) {
			
							$message = __( 'Security check failed, state not verified!!', 'woo-crm-integration-for-zoho' );
							$this->add_admin_notice( $message, false );
			
						} else {
			
							$code     = sanitize_text_field( wp_unslash( $_GET['code'] ) );
							$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
							$success  = $zoho_api->get_refresh_token_data( $code );
			             //   print_r($success);
	               //         exit;
							update_option( 'wciz_woo_zoho_authorised', $success );
							if ( ! $success ) {
								$message = __( 'Confirm your login details!', 'woo-crm-integration-for-zoho' );
								$this->add_admin_notice( $message, $success );
							}
						}
						wp_safe_redirect( admin_url( 'admin.php?page=woo-crm-integration-for-zoho' ) );
					} else {
						wp_safe_redirect( admin_url( 'admin.php?page=woo-crm-integration-for-zoho' ) );
					}
				}
			}
			
			exit();
		}

		// Refresh access token on expiry.
		if ( isset( $_GET['wciz_refresh_zoho_access_token'] ) && '1' == $_GET['wciz_refresh_zoho_access_token'] ) { //phpcs:ignore
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
				$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
				$success  = $zoho_api->renew_access_token() ? '1' : '0';
				wp_safe_redirect( admin_url( 'admin.php?page=woo-crm-integration-for-zoho&renew_token=' . $success ) );
				exit();
			}
		}

		// Download log file.
		if ( isset( $_GET['wciz_download'] ) ) {
			$filename = WC_LOG_DIR . 'wciz-woo-zoho-sync-log.log';
			header( 'Content-type: text/plain' );
			header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
			readfile( $filename ); //phpcs:ignore
			exit;
		}

		// Download log file.
		if ( isset( $_GET['wciz_cf7_download'] ) ) {
			$filename = WC_LOG_DIR . 'wciz-zcf7-sync-log.log';
			header( 'Content-type: text/plain' );
			header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
			readfile( $filename ); //phpcs:ignore
			exit;
		}

		// Download log file.
		if ( isset( $_GET['wciz_log_download'] ) ) {
			$filename = WC_LOG_DIR . 'wciz-woo-zoho-fetch-log.log';
			header( 'Content-type: text/plain' );
			header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
			readfile( $filename ); //phpcs:ignore
			exit;
		}

		if ( ! get_option( 'wciz_woo_zoho_cf7_log_table_created', false ) ) {
			$this->wciz_zcf7_create_log_table();
		}
	}

	/**
	 * Expiry for current Token.
	 */
	public static function get_access_token_expiry() {
		$token_data = get_option( 'wciz_woo_zoho_token_data' );
		if ( $token_data['expiry'] > time() ) {
			return ceil( ( $token_data['expiry'] - time() ) / 60 );
		}
		return false;
	}

	/**
	 * Save current Token.
	 *
	 * @param array $token_data Api token data.
	 */
	public function save_token_data( $token_data ) {
		$old_token_data = get_option( 'wciz_woo_zoho_token_data', array() );
		foreach ( $token_data as $key => $value ) {
			if ( is_array( $old_token_data ) ) {
				$old_token_data[ $key ] = $value;
				if ( 'expires_in' === $key ) {
					$old_token_data['expiry'] = time() + $value;
				}
			}
		}
		update_option( 'wciz_woo_zoho_token_data', $old_token_data );
	}

	/**
	 * Get Tabs labels.
	 */
	public function render_tabs() {
		/**
		 * Filters the value to tabs array.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  array.
		 */
		$tabs      = apply_filters( 'wciz_crm_woo_tabs', array() );
		$file_path = 'woo-crm-fw/templates/crm-woo-tabs.php';
		self::load_template( $file_path, array( 'tabs' => $tabs ), WOO_CRM_INTEGRATION_ZOHO_PATH );
	}

	/**
	 * Get Tabs Contents.
	 */
	public function render_tab_contents() {
		/**
		 * Filters the value of zoho tabs array.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  array.
		 */
		$tabs = apply_filters( 'wciz_crm_woo_tabs', array() );
		if ( ! empty( $tabs ) && is_array( $tabs ) ) {
			foreach ( $tabs as $file_name => $value ) {

				switch ( $file_name ) {
					case 'wciz-data-sync':
						$options = $this->get_available_feed_objects();

						$settings[] = array(
							'title'       => esc_html__( 'Synchronize Large Datasets Efficiently', 'woo-crm-integration-for-zoho' ),
							'description' => esc_html__( 'Easily sync all WooCommerce data to your CRM with one click.', 'woo-crm-integration-for-zoho' ),
							'button'      => array(
								'title'  => esc_html__( 'Bulk Objects', 'woo-crm-integration-for-zoho' ),
								'href'   => '',
								'newtab' => false,
								'class'  => 'bulk_data_sync',
							),
						);
						$settings[] = array(
							'title'       => esc_html__( 'One Click Synchronization', 'woo-crm-integration-for-zoho' ),
							'description' => esc_html__( 'Sync all new or failed WooCommerce objects to the CRM with one click.', 'woo-crm-integration-for-zoho' ),
							'button'      => array(
								'title'  => esc_html__( 'One Click Synchronization', 'woo-crm-integration-for-zoho' ),
								'href'   => '',
								'newtab' => false,
								'class'  => 'one_click_sync',
							),
						);

						$params = array(
							'settings' => $settings,
							'options'  => $options,
						);

						break;

					case 'wciz-dashboard':
						$connection_status = self::get_connection_status();

						$token_expiry = self::get_access_token_expiry();
						$params       = compact( 'connection_status', 'token_expiry' );
						break;

					case 'wciz-logs':
						$params = array();
						break;

					case 'wciz-feeds':
						$args         = array( 'publish', 'draft' );
						$cpt_instance = new Woo_Crm_Integration_For_Zoho_Cpt();
						$feeds        = $cpt_instance->get_available_feeds( $args );
						$feed_options = $cpt_instance->get_feed_event_options();
						$params       = compact( 'feeds', 'cpt_instance', 'feed_options' );
						break;

					default:
						$params = array();
						break;
				}

				$file_path = 'woo-crm-fw/templates/tabs-contents/' . $file_name . '.php';
				self::load_template( $file_path, $params, WOO_CRM_INTEGRATION_ZOHO_PATH );
			}
		}
	}

	/**
	 * Get connection status.
	 */
	public static function get_connection_status() {
		return get_option( 'wciz_woo_zoho_authorised', false );
	}


	/**
	 * Specify Tabs Lables/Contents.
	 *
	 * @param  array $tabs Tabs array.
	 * @return array Tabs array.
	 */
	public function return_tabs( $tabs = array() ) {

		return array(
			'wciz-dashboard'       => __( 'Dashboard', 'woo-crm-integration-for-zoho' ),
			'wciz-feeds'           => __( 'Feeds', 'woo-crm-integration-for-zoho' ),
			'wciz-data-sync'       => __( 'Data Sync', 'woo-crm-integration-for-zoho' ),
			'wciz-cart-settings'   => __( 'Cart Settings', 'woo-crm-integration-for-zoho' ),
			'wciz-abandoned-carts' => __( 'Abandoned Carts', 'woo-crm-integration-for-zoho' ),
			'wciz-logs'            => __( 'Logs', 'woo-crm-integration-for-zoho' ),
			'wciz-settings'        => __( 'Settings', 'woo-crm-integration-for-zoho' ),
			'wciz-support'         => __( 'Support', 'woo-crm-integration-for-zoho' ),
		);
	}

	/**
	 * Get authorization settings.
	 *
	 * @return array $settings Settings array.
	 */
	public static function get_authorization_settings() {

		$settings[] = array(
			'type'  => 'title',
			'title' => esc_html__( 'Link your Zoho CRM account.', 'woo-crm-integration-for-zoho' ),
			'class' => 'wciz-wfw-sub-heading',
		);
		$settings[] = array(
			'id'                => 'wciz-zoho-use-global-app',
			'type'              => 'checkbox',
			'title'             => esc_html__( 'Use Global App', 'woo-crm-integration-for-zoho' ),
			'class'             => 'wciz-crm-auth-toggle-switch',
			'value'             => get_option( 'wciz_zoho_use_gapp', true ),
			'custom_attributes' => array( 'required' => 'required' ),
		);
		$settings[] = array(
			'id'                => 'wciz-zoho-client-id',
			'type'              => 'text',
			'title'             => esc_html__( 'Client ID', 'woo-crm-integration-for-zoho' ),
			'placeholder'       => 'Enter your Client ID',
			'value'             => get_option( 'wciz_zoho_client_id', false ),
			'custom_attributes' => array( 'required' => 'required' ),
		);
		$settings[] = array(
			'id'                => 'wciz-zoho-secret-id',
			'type'              => 'text',
			'title'             => esc_html__( 'Secret ID', 'woo-crm-integration-for-zoho' ),
			'placeholder'       => 'Enter your Secret ID',
			'value'             => get_option( 'wciz_zoho_secret_id', false ),
			'custom_attributes' => array( 'required' => 'required' ),
		);
		$settings[] = array(
			'id'                => 'wciz-zoho-domain',
			'type'              => 'select',
			'title'             => esc_html__( 'Zoho Domain', 'woo-crm-integration-for-zoho' ),
			'placeholder'       => 'Select your Zoho Domain',
			'value'             => get_option( 'wciz_zoho_domain', 'in' ),
			'options'           => self::get_available_domains(),
			'custom_attributes' => array( 'required' => 'required' ),
		);
		$settings[] = array(
			'id'                => 'wciz-zoho-redirect-uri',
			'type'              => 'text',
			'title'             => esc_html__( 'Redirect Uri', 'woo-crm-integration-for-zoho' ),
			'value'             => rtrim( admin_url(), '/' ),
			'custom_attributes' => array(
				'required' => 'required',
				'readonly' => true,
			),
		);
		$settings[] = array(
			'type' => 'sectionend',
		);
		return $settings;
	}

	/**
	 * Get available zoho objects
	 *
	 * @return array Available modules.
	 */
	public static function get_available_crm_objects() {
		$zoho_api    = Woo_Crm_Integration_Zoho_Api::get_instance();
		$module_data = $zoho_api->get_modules_data();
		return isset( $module_data['modules'] ) ? $module_data['modules'] : array();
	}

	/**
	 * Get feed form settings.
	 *
	 * @return array Feed settings array.
	 */
	public function get_feed_form_settings() {

		$obj_type = array();
		$admin_instance = new Woo_Crm_Integration_For_Zoho_Admin( 'CRM Integration For Zoho', WOO_CRM_INTEGRATION_ZOHO_VERSION );
		$feed_id = isset( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : '';
		if ( !empty( $feed_id ) ) {
			$feed_event = get_post_meta( $feed_id, 'feed_event', true );
			
			if ( is_numeric( $feed_event ) ) {
				if ( get_post_type( $feed_event) === 'wciz_crm_feed' ) {
					$feed_event = get_post_meta( $feed_event, 'feed_event', true );
				}
			}
			if ( strpos($feed_event, 'order') !== false || in_array( $feed_event, array_keys(wc_get_order_statuses()) ) || 'status_change' == $feed_event ) {
				$obj_type = array(
					'shop_order',
					'abandoned_cart',
				);
			} else if ( strpos($feed_event, 'product') !== false ) {
				$obj_type = array(
					'product',
					'abandoned_cart',
				);
			} else if ( strpos($feed_event, 'user') !== false ) {
				$obj_type = array(
					'users',
					'abandoned_cart',
				);
			} else if ( strpos($feed_event, 'membership') !== false ) {
				$obj_type = array(
					'abandoned_cart',
					( $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) ? 'wc_user_membership' : '',
				);
			} else if ( 'send_manually' == $feed_event ) {
				$obj_type = array(
					'shop_order',
					'product',
					'users',
					'abandoned_cart',
					( $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) ? 'wc_user_membership' : '',
				);
			} else if ( 'create_subscription' == $feed_event ) {
				$obj_type = array(
					'shop_order',
				);
			} else if ( 'subscription_status_changed' == $feed_event ) {
				$obj_type = array(
					'shop_order',
				);
			} else if ( 'subscription_updated' == $feed_event ) {
				$obj_type = array(
					'shop_order',
				);
			}
		} else {
			$obj_type = array(
				'shop_order',
				'product',
				'users',
				'abandoned_cart',
				( $admin_instance->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) ? 'wc_user_membership' : '',
			);
		}
	
		$class           = Woo_Crm_Integration_For_Zoho_Fw::get_instance();
		$options         = $class->getMappingDataset( $obj_type );
		$cpt_instance    = new Woo_Crm_Integration_For_Zoho_Cpt();
		$available_feeds = $cpt_instance->get_available_feeds_for_mapping();
		if ( count( $available_feeds ) ) {
			$options['feeds'] = $available_feeds;
		}
		$settings['field_type']  = array(
			'label'   => __( 'Field Type', 'woo-crm-integration-for-zoho' ),
			'options' => array(
				'standard_field' => __( 'Standard Fields', 'woo-crm-integration-for-zoho' ),
				'custom_value'   => __( 'Custom Value', 'woo-crm-integration-for-zoho' ),
			),
		);
		$settings['field_value'] = array(
			'label'   => __( 'Field Value', 'woo-crm-integration-for-zoho' ),
			'options' => $options,
		);

		$settings['custom_value'] = array(
			'label' => __( 'Custom Value', 'woo-crm-integration-for-zoho' ),
		);

		return $settings;
	}

	/**
	 * Get available domain options.
	 *
	 * @return array Array of domains.
	 */
	public static function get_available_domains() {
		return array(
			'in'     => 'India (.in)',
			'com'    => 'USA & Others (.com)',
			'eu'     => 'Europe (.eu)',
			'com.cn' => 'China (.com.cn)',
			'com.au' => 'Australia (.com.au)',
			'ca'     => 'Canada(.ca)',
		);
	}

	/**
	 * Get general settings.
	 *
	 * @return array Array of settings.
	 */
	public static function get_general_settings() {

		$settings[] = array(
			'type' => 'title',
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-sync',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Instant sync', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_sync', 'no' ),
			'desc_tip' => __( 'Instantly sync data in real time based on feed events.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-background-sync',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Background sync', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_background_sync_allowed', 'no' ),
			'desc_tip' => __( 'Enable background sync to automatically sync data every 5 minutes.', 'woo-crm-integration-for-zoho' ) . '<br/>' . __( 'Use either background sync or instant sync, but not both.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-log',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Woo sync logging', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_log', 'yes' ),
			'desc_tip' => __( 'Enable logging to monitor synced data status.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'                => 'wciz-zoho-delete-log',
			'type'              => 'number',
			'title'             => esc_html__( 'Delete log after x days', 'woo-crm-integration-for-zoho' ),
			'value'             => get_option( 'wciz_zoho_delete_log', '30' ),
			'custom_attributes' => array( 'min' => 7 ),
			'desc'              => __( 'Set the number of days before log entries are deleted from the database.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-wipe-data',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Wipe data on disconnect', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_wipe_data', 'no' ),
			'desc_tip' => __( 'Allows deleting sync history when disconnecting the account.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'type' => 'sectionend',
		);

		$settings[] = array(
			'type' => 'title',
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-update-stock',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Synchronize product stock in WooCommerce', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_update_stock', 'no' ),
			'desc_tip' => __( 'Sync WooCommerce stock with Zoho updates.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-update-order-status',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Synchronize order status in WooCommerce', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_update_order_status', 'no' ),
			'desc_tip' => __( 'Sync WooCommerce order status with Zoho updates.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-data-log',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Data log', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_data_log', 'yes' ),
			'desc_tip' => __( 'Enable log creation to track order status and stock updates in WooCommerce.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-reduce-stock',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Reduce the stock quantity of product when an order placed', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_reduce_stock', 'no' ),
			'desc_tip' => __( 'Enable this to decrease product count in Zoho CRM when a WooCommerce order is placed.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-discount-in-line-items',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Sync coupon discount in line items', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_discount_in_line_items', 'no' ),
			'desc_tip' => __( 'Enable this option to synchronize coupon amounts with each line item.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-discount-in-line-items-parallel-subtotal',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Sync coupon discount in both, line items as well in parallel to subtotal', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_discount_in_both', 'no' ),
			'desc_tip' => __( 'Enable this option to sync coupon amounts with each line item and the subtotal field.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'title'    => esc_html__( 'Sync coupon discount in subtotal', 'woo-crm-integration-for-zoho' ),
			'id'       => 'wciz-zoho-enable-discount-in-subtotal',
			'type'     => 'checkbox',
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_discount_in_subtotal', 'no' ),
			'desc_tip' => __( 'Enable this option to sync coupon amounts to subtotal field.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-product-deletion-woo-to-zoho',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Product deletion from WooCommerce to Zoho', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_product_deletion_woo_to_zoho', 'no' ),
			'desc_tip' => __( 'Enable this option to auto-delete products from Zoho when removed from WooCommerce. Products will be marked as inactive or active in Zoho when moved to trash or restored in WooCommerce.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-product-deletion-zoho-to-woo',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Product deletion from Zoho to WooCommerce', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_product_deletion_zoho_to_woo', 'no' ),
			'desc_tip' => __( 'Enable to auto-delete WooCommerce products when removed from Zoho.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-product-syncing-zoho-to-woo',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Product syncing from Zoho to WooCommerce', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_product_syncing_zoho_to_woo', 'no' ),
			'desc_tip' => __( 'Enable this option to auto-sync Zoho products to WooCommerce on any update.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-product-creation-zoho-to-woo',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Product creation from Zoho to WooCommerce', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_product_creation_zoho_to_woo', 'no' ),
			'desc_tip' => __( 'Enable this option to auto-sync Zoho products with WooCommerce for new and existing items.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'type' => 'sectionend',
		);
		return $settings;
	}

	/**
	 * Get abandoned_cart settings.
	 *
	 * @return array Array of settings.
	 */
	public static function get_abandoned_cart_settings() {

		$settings[] = array(
			'type' => 'title',
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-enable-abandoned-cart-sync',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Abandoned cart synchronization', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_enable_abandoned_cart_sync', 'no' ),
			'desc_tip' => __( 'Instantly sync data in real time based on feed events.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'                => 'wciz-zoho-abn-track-data',
			'type'              => 'select',
			'title'             => esc_html__( 'Track data', 'woo-crm-integration-for-zoho' ),
			'placeholder'       => 'Select page',
			'value'             => get_option( 'wciz_zoho_abn_track_data', 'cart' ),
			'options'           => array(
									'cart'     => __( 'Cart page', 'woo-crm-integration-for-zoho' ),
									'checkout' => __( 'Checkout page', 'woo-crm-integration-for-zoho' ),
								),
			'custom_attributes' => array( 'required' => 'required' ),
			'desc'              => __( 'You can collect data from the cart or checkout page to analyze abandoned carts.<br /><strong> NOTE: </strong>The default email is required to send data for cart or checkout page tracking.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-abn-data-fields',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Data fields', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-zoho-abn-data',
			'value'    => get_option( 'wciz_zoho_abn_data_fields', 'no' ),
			'desc_tip' => __( 'Email and name are required to send data for checkout page tracking.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'                => 'wciz-zoho-cart-timer',
			'type'              => 'number',
			'title'             => esc_html__( 'Cart countdown timer (in minutes)', 'woo-crm-integration-for-zoho' ),
			'value'             => get_option( 'wciz_zoho_cart_timer', '5' ),
			'custom_attributes' => array( 'min' => 5 ),
			'desc'              => __( 'Set the number of minutes before the cart is abandoned.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'                => 'wciz-zoho-delete-data',
			'type'              => 'number',
			'title'             => esc_html__( 'Automatically delete data after (days)', 'woo-crm-integration-for-zoho' ),
			'value'             => get_option( 'wciz_zoho_delete_data', '1' ),
			'custom_attributes' => array( 'min' => 1 ),
			'desc'              => __( 'Set the number of days before abandoned cart data is deleted.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'id'       => 'wciz-zoho-save-html-encoded',
			'type'     => 'checkbox',
			'title'    => esc_html__( 'Save the cart HTML in an encoded format', 'woo-crm-integration-for-zoho' ),
			'class'    => 'wciz-crm-feed-chk',
			'value'    => get_option( 'wciz_zoho_save_html_encoded', 'no' ),
			'desc_tip' => __( 'Allows saving the cart HTML in an encoded format.', 'woo-crm-integration-for-zoho' ),
		);

		$settings[] = array(
			'type' => 'sectionend',
		);

		return $settings;
	}

	/**
	 * Clear sync log callback.
	 */
	public function clear_sync_log() {
		$delete_duration  = get_option( 'wciz_zoho_delete_log', 30 );
		$delete_timestamp = time() - ( $delete_duration * 24 * 60 * 60 );
		wciz_woo_zoho_clear_log_data( $delete_timestamp );
	}

	/**
	 * Clear log table.
	 */
	public static function delete_sync_log() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wciz_woo_zoho_log';
		$query      = "TRUNCATE TABLE {$table_name}";
		wciz_woo_zoho_execute_db_query( $query );
	}

	/**
	 * Clear admin notices.
	 */
	public function clear_admin_notice() {
		delete_option( 'wciz_woo_zoho_admin_notice' );
	}

	/**
	 * Get admin notices to show.
	 *
	 * @return array notice array.
	 */
	public function get_admin_notice() {
		return get_option( 'wciz_woo_zoho_admin_notice', array() );
	}

	/**
	 * Add notice to options.
	 *
	 * @param string $message Notice error.
	 * @param bool   $success Notice type.
	 */
	public function add_admin_notice( $message, $success ) {
		update_option( 'wciz_woo_zoho_admin_notice', compact( 'message', 'success' ) );
	}

	/**
	 * Check if instant sync is enable.
	 *
	 * @return bool
	 */
	public static function is_instant_sync_enable() {
		$enable = get_option( 'wciz_zoho_enable_sync', 'no' );
		$enable = ( 'yes' === $enable );
		return $enable;
	}

	/**
	 * Check if have active connection.
	 *
	 * @return bool
	 */
	public static function is_connection_active() {
		$authorized = get_option( 'wciz_woo_zoho_authorised', false );
		$setup      = get_option( 'wciz_woo_zoho_onboarding_completed', false );
		$active     = $setup && $authorized;
		return $active;
	}

	/**
	 * Check if instant sync is enable.
	 *
	 * @return bool
	 */
	public static function is_wipe_allowed() {
		$enable = get_option( 'wciz_zoho_wipe_data', 'no' );
		$enable = ( 'yes' === $enable );
		return $enable;
	}

	/**
	 * Check if log is enable.
	 *
	 * @return bool
	 */
	public static function is_log_enable() {
		$enable = get_option( 'wciz_zoho_enable_log', 'yes' );
		$enable = ( 'yes' === $enable );
		return $enable;
	}

	/**
	 * Check if data log for product, order is enable.
	 *
	 * @return bool
	 */
	public static function is_data_log_enable() {
		$enable = get_option( 'wciz_zoho_enable_data_log', 'yes' );
		$enable = ( 'yes' === $enable );
		return $enable;
	}

	/**
	 * Check if log is enable.
	 *
	 * @return bool
	 */
	public static function is_background_sync_allowed() {
		$enable = get_option( 'wciz_zoho_background_sync_allowed', 'no' );
		$enable = ( 'yes' === $enable );
		return $enable;
	}

	/**
	 * Add product field nonce.
	 */
	public function add_nonce_product_nonce() {
		?>
		<input type="hidden" name="meta_box_nonce" value="<?php echo esc_attr( wp_create_nonce( 'meta_box_nonce' ) ); ?>">
		<?php
	}

	/**
	 * Add background sync schedule.
	 *
	 * @param array $schedules Array of available schedules.
	 * @return array $schedules
	 */
	public function add_background_sync_schedule( $schedules ) {
		if ( ! isset( $schedules['wciz_woo_bg_sync'] ) ) {

			$schedules['wciz_woo_bg_sync'] = array(
				'interval' => 5 * 60,
				'display'  => __( 'Once every 5 minutes', 'woo-crm-integration-for-zoho' ),
			);

			$schedules['wciz_woo_product_fetch'] = array(
				'interval' => 5 * 60,
				'display'  => __( 'Once every 5 minutes', 'woo-crm-integration-for-zoho' ),
			);

			$schedules['wciz_woo_order_fetch'] = array(
				'interval' => 5 * 60,
				'display'  => __( 'Once every 5 minutes', 'woo-crm-integration-for-zoho' ),
			);
		}

		return $schedules;
	}

	/**
	 * Schedule background sync event.
	 */
	public function schedule_background_sync_event() {

		// Scheduled event for background syncing.
		if ( ! wp_next_scheduled( 'wciz_woo_zoho_background_sync' ) ) {
			wp_schedule_event( time(), 'wciz_woo_bg_sync', 'wciz_woo_zoho_background_sync' );
		}

		// Scheduled event for fetching products from zoho to update stock.
		if ( ! wp_next_scheduled( 'wciz_woo_zoho_product_fetch' ) ) {
			wp_schedule_event( time() + 2 * 60, 'wciz_woo_product_fetch', 'wciz_woo_zoho_product_fetch' );
		}

		// Scheduled event for fetching orders from zoho to update order status.
		if ( ! wp_next_scheduled( 'wciz_woo_zoho_order_fetch' ) ) {
			wp_schedule_event( time() + 4 * 60, 'wciz_woo_order_fetch', 'wciz_woo_zoho_order_fetch' );
		}
	}

	/**
	 * Perform background sync.
	 */
	public function perform_background_sync() {

		$background_sync = self::is_background_sync_allowed();
		$connected       = self::is_connection_active();

		if ( $connected && $background_sync ) {

			if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
				$order_query = new WC_Order_Query( array(
					'status' => 'all',
					'orderby' => 'date',
					'order' => 'DESC',
					'return' => 'ids',
					'limit'  => 50,
					'meta_query' =>array( //phpcs:ignore
						'relation' => 'OR',
						array(
							'key'     => 'wciz_zoho_allow_background_syncing',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => 'wciz_zoho_allow_background_syncing',
							'compare' => '=',
							'value'   => 'yes',
						),
					),
				) );

				foreach ( $order_query->get_orders() as $key => $value ) {
					$woo_id[] = $value;
				}
			} else {
				$args = array(
					'post_type'      => 'shop_order',
					'posts_per_page' => 50,
					'post_status'    => 'any',
					'meta_query'     => array(  //phpcs:ignore
						'relation' => 'OR',
						array(
							'key'     => 'wciz_zoho_allow_background_syncing',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => 'wciz_zoho_allow_background_syncing',
							'compare' => '=',
							'value'   => 'yes',
						),
					),
				);

				$order_query = new WP_Query( $args );
				while ( $order_query->have_posts() ) :
						$order_query->the_post();
						$woo_id[] = get_the_ID();
				endwhile;
			}

			$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
			$all_feeds        = $framework->get_all_feed();
			$default_feeds    = array();
			$additional_feeds = array();
			$product_feeds    = array();
			$order_feeds      = array();
			$user_feeds       = array();
			$request_module   = Woo_Crm_Integration_Request_Module::get_instance();
			if ( ! empty( $all_feeds ) && is_array( $all_feeds ) ) {
				foreach ( $all_feeds as $key => $feed_id ) {
					$is_default_feed = $framework->get_feed( $feed_id, 'default_feed' );
					if ( $is_default_feed ) {
						array_push( $default_feeds, $feed_id );
					} else {
						array_push( $additional_feeds, $feed_id );
					}
				}
			}
			if ( ! empty( $default_feeds ) ) {
				foreach ( $default_feeds as $key => $feed_id ) {
					$record_type = $framework->get_feed( $feed_id, 'crm_object' );
					if ( 'Products' === $record_type ) {
						$product_feeds['0'] = $feed_id;
					} elseif ( 'Contacts' === $record_type ) {
						$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
						if ( 'users' === $woo_object ) {
							$user_feeds['0'] = $feed_id;
						} else {
							$order_feeds['0'] = $feed_id;
						}
					} elseif ( 'Deals' === $record_type ) {
						$order_feeds['1'] = $feed_id;
					} elseif ( 'Sales_Orders' === $record_type ) {
						$order_feeds['2'] = $feed_id;
					}
				}
			}
			if ( ! empty( $additional_feeds ) ) {
				foreach ( $additional_feeds as $key => $feed_id ) {
					$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
					if ( 'product' === $woo_object ) {
						array_push( $product_feeds, $feed_id );
					} elseif ( 'users' === $woo_object ) {
						array_push( $user_feeds, $feed_id );
					} else {
						array_push( $order_feeds, $feed_id );
					}
				}
			}

			ksort( $product_feeds );
			ksort( $order_feeds );
			ksort( $user_feeds );
			if ( ! empty( $order_feeds ) && ! empty( $woo_id ) ) {
				foreach ( $order_feeds as $key => $order_feed_id ) {
					if ( is_array( $woo_id ) ) {
						foreach ( $woo_id as $id_key => $id_value ) {
							$request_module->perform_shop_order_sync( $order_feed_id, $id_value );
							if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
								$order = wc_get_order($id_value);
								$order->update_meta_data( 'wciz_zoho_allow_background_syncing', 'no' );
								$order->save();
							} else {
								update_post_meta( $id_value, 'wciz_zoho_allow_background_syncing', 'no' );
							}
						}
					} else {
						$request_module->perform_shop_order_sync( $order_feed_id, $woo_id );
						if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
							$order = wc_get_order($woo_id);
							$order->update_meta_data( 'wciz_zoho_allow_background_syncing', 'no' );
							$order->save();
						} else {
							update_post_meta( $woo_id, 'wciz_zoho_allow_background_syncing', 'no' );
						}
					}
					
				}
			}

			$product_args = array(
				'post_type'      => 'product',
				'posts_per_page' => 1,
				'post_status'    => 'all',
				'meta_query'     => array(  //phpcs:ignore
					'relation' => 'OR',
					array(
						'key'     => 'wciz_zoho_allow_background_syncing',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'wciz_zoho_allow_background_syncing',
						'compare' => '=',
						'value'   => 'yes',
					),
				),
			);

			$product_query = new WP_Query( $product_args );
			while ( $product_query->have_posts() ) :
				$product_query->the_post();
				$prod_woo_id[] = get_the_ID();
			endwhile;

			if ( ! empty( $product_feeds ) && ! empty( $prod_woo_id ) ) {
				foreach ( $product_feeds as $key => $product_feed_id ) {
					if ( is_array( $prod_woo_id ) ) {
						foreach ( $prod_woo_id as $product_key => $prod_id ) {
							$request_module->trigger_product_related_feed( $product_feed_id, $prod_id );
							update_post_meta( $prod_id, 'wciz_zoho_allow_background_syncing', 'no' );
						}
					} else {
						$request_module->trigger_product_related_feed( $product_feed_id, $prod_woo_id );
						update_post_meta( $prod_woo_id, 'wciz_zoho_allow_background_syncing', 'no' );
					}
				}
			}

			$user_args = array(
				'number'     => 1,
				'meta_query' => array( //phpcs:ignore
					'relation' => 'OR',
					array(
						'key'     => 'wciz_zoho_allow_background_syncing',
						'value'   => 'yes',
						'compare' => '=',
					),
					array(
						'key'     => 'wciz_zoho_allow_background_syncing',
						'compare' => 'NOT EXISTS',
					),
				),
			);

			$user_query = new WP_User_Query( $user_args );

			// Get the user.
			$users = $user_query->get_results();
			if ( ! empty( $users ) ) {
				foreach ( $users as $user ) {
					$woo_user_id = $user->ID;
				}
			}

			if ( ! empty( $user_feeds ) && ! empty( $woo_user_id ) ) {
				foreach ( $user_feeds as $key => $user_feed_id ) {
					$request_module->perform_wp_user_sync( $user_feed_id, $woo_user_id );
					update_user_meta( $woo_user_id, 'wciz_zoho_allow_background_syncing', 'no' );
				}
			}

		}

		if ( 'yes' == get_option( 'wciz_zoho_enable_product_deletion_zoho_to_woo', 'no' ) ) {
			
			$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
			$all_feeds        = $framework->get_all_feed();
			$default_feeds    = array();
			$additional_feeds = array();
			$product_feeds    = array();
			$request_module   = Woo_Crm_Integration_Request_Module::get_instance();
			if ( ! empty( $all_feeds ) && is_array( $all_feeds ) ) {
				foreach ( $all_feeds as $key => $feed_id ) {
					$is_default_feed = $framework->get_feed( $feed_id, 'default_feed' );
					if ( $is_default_feed ) {
						array_push( $default_feeds, $feed_id );
					} else {
						array_push( $additional_feeds, $feed_id );
					}
				}
			}
			if ( ! empty( $default_feeds ) ) {
				foreach ( $default_feeds as $key => $feed_id ) {
					$record_type = $framework->get_feed( $feed_id, 'crm_object' );
					if ( 'Products' === $record_type ) {
						$product_feeds['0'] = $feed_id;
					}
				}
			}
			if ( ! empty( $additional_feeds ) ) {
				foreach ( $additional_feeds as $key => $feed_id ) {
					$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
					if ( 'product' === $woo_object ) {
						array_push( $product_feeds, $feed_id );
					}
				}
			}

			ksort( $product_feeds );

			$f_id = $product_feeds[0];

			$product_args = array(
				'post_type'      => array( 'product', 'product_variation' ),
				'posts_per_page' => -1,
				'post_status'    => 'all',
				'meta_query'     => array(  //phpcs:ignore
					array(
						'key'     => 'wciz_zoho_feed_' . $f_id . '_association',
						'compare' => 'EXISTS',
					),
				),
			);

			$product_query = new WP_Query( $product_args );
			while ( $product_query->have_posts() ) :
				$product_query->the_post();
					$prd_id[] = get_the_ID();
					$zoho_prod_id = get_post_meta( get_the_ID(), 'wciz_zoho_feed_' . $f_id . '_association', true );
					$this->delete_product_from_wordpress( $zoho_prod_id, get_the_ID() );
				$product = wc_get_product(get_the_ID());
				if ($product->is_type('variable')) {
					$variations = $product->get_children(); // Get IDs of variations
					foreach ($variations as $variation_id) {
						$prd_id[] = $variation_id;
						$zoho_prod_id = get_post_meta( $variation_id, 'wciz_zoho_feed_' . $f_id . '_association', true );
						$this->delete_product_from_wordpress( $zoho_prod_id, $variation_id );
					}
				}
			endwhile;
		}

		if ( 'yes' == get_option( 'wciz_zoho_enable_abandoned_cart_sync', 'no' ) ) {
			$feed_id = get_option( 'wciz_zoho_abandoned_cart_feed_id' );
			$cart_status = 'Active';
			$connect_framework = new Woo_Crm_Integration_Connect_Framework();
			$zoho_abundant_guests = wciz_zoho_abundant_guests();
			if ( count( $zoho_abundant_guests ) > 0 ) {
	
				foreach ( $zoho_abundant_guests as $key => $single_cart ) {
					$user_type = 'guest';
					$user_id = 0;
					if ( isset( $single_cart['option_value'] ) ) {
						$data = unserialize( $single_cart['option_value'] );
						
						if ( ! ( isset( $data['sync_status'] ) && !empty( $data['sync_status'] ) ) ) {
							$zoho_abundant_timer = (int) get_option( 'wciz_zoho_cart_timer', 5 );
							$cart_expiry_time = $zoho_abundant_timer * 60 + $data['last_updated'];
							if ( $cart_expiry_time < time() ) {
	
								$cart_status = 'Abandoned';
							} else {
								$cart_status = 'Active';
							}
							if ( 'Abandoned' == $cart_status ) {
								$cart_email = $data['cart_email'];
								$crm_connect_manager = new Woo_Crm_Integration_For_Zoho_Fw();
								$record_type = $crm_connect_manager->get_feed(
									$feed_id,
									'crm_object'
								);
								$request = $connect_framework->get_request(
									'abandoned_cart',
									$feed_id,
									0,
									$cart_email
								);
			
								$log_data = array(
									'woo_id'     => 0,
									'feed_id'    => $feed_id,
									'woo_object' => 'abandoned_cart',
									'feed_title' => $crm_connect_manager->get_feed_title( $feed_id ),
								);
					
								$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
								
								$result                   = $crm_integration_zoho_api->create_single_record(
									$record_type,
									$request,
									false,
									$log_data
								);
								if ( 'SUCCESS' == $result['data'][0]['code'] ) {
									wciz_set_abandoned_cart_sync_status( $cart_email, $user_type, $user_id );
								}
							}
						}
					}
				}
			}

			$abandoned_customers = zoho_abundant_users();
			if ( isset( $abandoned_customers ) && null != $abandoned_customers && count( $abandoned_customers ) ) {

				$current_time = time();
				$user_type = 'user';
				foreach ( $abandoned_customers as $key => $single_user ) {
					if ( in_array('administrator', $single_user->roles ) ) {
						continue;
					}
					$customer_cart = get_user_meta( $single_user->ID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true );
					$last_time = get_user_meta( $single_user->ID, 'zoho_last_addtocart', true );

					if ( empty( $last_time ) && ! $last_time ) {

						continue;
					}

					$sync_status = get_user_meta( $single_user->ID, 'zoho_abdn_sync_status', true );

					$zoho_abundant_delete = (int) get_option( 'wciz_zoho_delete_data', 1 );

					if ( ! empty( $sync_status ) && 'not_synced' !== $sync_status ) {

						$sync_time = $sync_status;

						$abundant_delete_time = $zoho_abundant_delete * 24 * 60 * 60;

						if ( $current_time > ( $sync_time + $abundant_delete_time ) ) {

							update_user_meta( $single_user->ID, 'zoho_user_left_cart', 'no' );

						}
					}

					$user_email = $single_user->data->user_email;
					if ( ! empty( $sync_status ) && 'not_synced' == $sync_status ) {
						$zoho_abundant_timer = (int) get_option( 'wciz_zoho_cart_timer', 5 );
						$cart_expiry_time = $zoho_abundant_timer * 60 + $data['last_updated'];
						if ( $cart_expiry_time < time() ) {

							$cart_status = 'Abandoned';
						} else {
							$cart_status = 'Active';
						}
						if ( 'Abandoned' == $cart_status ) {
							$cart_email = $user_email;
							$crm_connect_manager = new Woo_Crm_Integration_For_Zoho_Fw();
							$record_type = $crm_connect_manager->get_feed(
								$feed_id,
								'crm_object'
							);
							$request = $connect_framework->get_request(
								'abandoned_cart',
								$feed_id,
								$single_user->ID,
								$cart_email
							);

							$log_data = array(
								'woo_id'     => $single_user->ID,
								'feed_id'    => $feed_id,
								'woo_object' => 'abandoned_cart',
								'feed_title' => $crm_connect_manager->get_feed_title( $feed_id ),
							);
				
							$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
							
							$result                   = $crm_integration_zoho_api->create_single_record(
								$record_type,
								$request,
								false,
								$log_data
							);
							if ( 'SUCCESS' == $result['data'][0]['code'] ) {
								wciz_set_abandoned_cart_sync_status( $cart_email, $user_type, $single_user->ID );
							}
						}
					}
				}
			}

		}
	}

	/**
	 * Get objects with active feeds
	 *
	 * @return array.
	 */
	public function get_available_feed_objects() {
		$cpt_instance    = new Woo_Crm_Integration_For_Zoho_Cpt();
		$available_feeds = $cpt_instance->get_available_feeds();
		if ( ! $available_feeds ) {
			return array();
		}
		$options = array();
		foreach ( $available_feeds as $key => $feed ) {
			$feed_object             = get_post_meta( $feed->ID, 'crm_object', true );
			if ( 'abandoned_cart' == $feed_object ) {
				continue;
			}
			$options[ $feed_object ] = str_replace( '_', ' ', $feed_object );
		}
		$options[''] = __( 'Choose option', 'woo-crm-integration-for-zoho' );
		return array_unique( array_reverse( $options ) );
	}

	/**
	 * Filter salesorder request to check empty values.
	 *
	 * @param array  $request Request data.
	 * @param array  $order_id Woo order id.
	 * @param string $record_type record type.
	 * @return array.
	 */
	public function filter_salesorder_request( $request, $order_id, $record_type = '' ) {

		if ( isset( $request['Discount'] ) && empty( $request['Discount'] ) ) {
			unset( $request['Discount'] );
		}

		if ( isset( $request['Discount'] ) && !empty( $request['Discount'] ) && 'yes' == get_option( 'wciz_zoho_enable_discount_in_line_items', 'no' ) ) {
			unset( $request['Discount'] );
		}

		if ( !isset( $request['Discount'] ) && 'yes' != get_option( 'wciz_zoho_enable_discount_in_line_items', 'no' ) ) {
			$order    = wc_get_order( $order_id );
			// $request['Discount'] = $order->discount_total;
			$request['Discount'] = $order->get_discount_total();
		}

		if ( 'yes' == get_option( 'wciz_zoho_enable_discount_in_both', 'no' ) ) {
			$order    = wc_get_order( $order_id );
			// $request['Discount'] = $order->discount_total;
			$request['Discount'] = $order->get_discount_total();
		} else {
			$request['Discount'] = 0;
		}

		$all_picklist_fields = get_option( 'wciz_woo_zoho_multiselectpicklist_fields', array() );

		if ( array_key_exists( $record_type, $all_picklist_fields ) ) {
			if ( is_array( $all_picklist_fields[ $record_type ] ) ) {
				foreach ( $all_picklist_fields[ $record_type ] as $key => $field_key ) {
					if ( ! empty( $request[ $field_key ] ) ) {
						if ( ! is_array( $request[ $field_key ] ) ) {
							$request[ $field_key ] = explode( ',', $request[ $field_key ] );
						}
					}
				}
			}
		}

		return $request;
	}

	/**
	 * Filter contact request to check empty values.
	 *
	 * @param array  $request Request data.
	 * @param array  $user_id Woo order id.
	 * @param string $record_type record type.
	 * @return array.
	 */
	public function get_filtered_user_request( $request, $user_id, $record_type = '' ) {

		$user = get_user_by( 'id', $user_id );

		if ( $user && ! empty( $user ) ) {

			$request['Last_Name'] = empty( $request['Last_Name'] ) ? $user->user_nicename : $request['Last_Name'];

			$request['First_Name'] = empty( $request['First_Name'] ) ? $user->user_nicename : $request['First_Name'];

			if ( isset( $request['Full_Name'] ) && empty( trim( $request['Full_Name'] ) ) ) {
				$request['Full_Name'] = $user->user_nicename . ' ' . $user->user_nicename;
			}

			$request['Email'] = empty( $request['Email'] ) ? $user->user_email : $request['Email'];
		}

		$all_picklist_fields = get_option( 'wciz_woo_zoho_multiselectpicklist_fields', array() );

		if ( array_key_exists( $record_type, $all_picklist_fields ) ) {
			if ( is_array( $all_picklist_fields[ $record_type ] ) ) {
				foreach ( $all_picklist_fields[ $record_type ] as $key => $field_key ) {
					if ( ! empty( $request[ $field_key ] ) ) {
						if ( ! is_array( $request[ $field_key ] ) ) {
							$request[ $field_key ] = explode( ',', $request[ $field_key ] );
						}
					}
				}
			}
		}

		return $request;
	}

	/**
	 * Filter contact request to check empty values.
	 *
	 * @param array  $request Request data.
	 * @param array  $user_id Woo order id.
	 * @param string $record_type record type.
	 * @return array.
	 */
	public function get_filtered_account_request( $request, $user_id, $record_type = '' ) {

		$user = get_user_by( 'id', $user_id );

		if ( $user && ! empty( $user ) ) {

			$request['Account_Name'] = empty( $request['Account_Name'] ) ? $user->user_nicename : $request['Account_Name'];
		}

		$all_picklist_fields = get_option( 'wciz_woo_zoho_multiselectpicklist_fields', array() );

		if ( array_key_exists( $record_type, $all_picklist_fields ) ) {
			if ( is_array( $all_picklist_fields[ $record_type ] ) ) {
				foreach ( $all_picklist_fields[ $record_type ] as $key => $field_key ) {
					if ( ! empty( $request[ $field_key ] ) ) {
						if ( ! is_array( $request[ $field_key ] ) ) {
							$request[ $field_key ] = explode( ',', $request[ $field_key ] );
						}
					}
				}
			}
		}

		return $request;
	}

	/**
	 * Filter product request to check empty values.
	 *
	 * @param array  $request Request data.
	 * @param array  $product_id Woo product id.
	 * @param string $record_type record type.
	 * @return array.
	 */
	public function get_filtered_product_request( $request, $product_id, $record_type = '' ) {

		$all_picklist_fields = get_option( 'wciz_woo_zoho_multiselectpicklist_fields', array() );

		if ( array_key_exists( $record_type, $all_picklist_fields ) ) {
			if ( is_array( $all_picklist_fields[ $record_type ] ) ) {
				foreach ( $all_picklist_fields[ $record_type ] as $key => $field_key ) {
					if ( ! empty( $request[ $field_key ] ) ) {
						if ( ! is_array( $request[ $field_key ] ) ) {
							$request[ $field_key ] = explode( ',', $request[ $field_key ] );
						}
					}
				}
			}
		}

		return $request;
	}

	/**
	 * Add dummy edit user profile hook to manage param count.
	 *
	 * @param int $user_id Id of updated user.
	 *
	 * @since 1.0.0
	 */
	public function add_wciz_edit_user_profile_update_hook( $user_id ) {
		/**
		 * Trigger associated Feeds.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  $user_id  Woo user id.
		 * @param mixed array.
		 */
		do_action( 'wciz_edit_user_profile_update', $user_id, array() );
	}

	/**
	 * Get available order statuses in Zoho.
	 *
	 * @param bool $force Force request.
	 * @return array Zoho order statuses.
	 */
	public static function get_zoho_order_statuses( $force = false ) {

		$zoho_order_statuses      = array();
		$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
		$module_data              = $crm_integration_zoho_api->get_module_fields( 'Sales_Orders', $force );
		$module_fields            = ! empty( $module_data['fields'] ) ? $module_data['fields'] : array();

		$field_key = array_search( 'Status', array_column( $module_fields, 'api_name' ) );

		if ( false !== $field_key ) {
			$zoho_order_statuses = $module_fields[ $field_key ]['pick_list_values'];
		}

		return $zoho_order_statuses;
	}

	/**
	 * Get woocommerce order statuses.
	 *
	 * @return array woo order statuses.
	 */
	public static function get_woo_order_statuses() {
		$cpt_instance = new Woo_Crm_Integration_For_Zoho_Cpt();
		return $cpt_instance->get_woo_order_statuses();
	}

	/**
	 * Fetch products from Zoho crm.
	 */
	public function perform_product_fetch() {

		$update_qty = get_option( 'wciz_zoho_update_stock', 'no' );

		$prod_syncing_from_zoho_to_woo = get_option( 'wciz_zoho_enable_product_syncing_zoho_to_woo', 'no' );

		$prod_creation_from_zoho_to_woo = get_option( 'wciz_zoho_enable_product_creation_zoho_to_woo', 'no' );

		$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();

		$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
		$all_feeds        = $framework->get_all_feed();
		$default_feeds    = array();
		$additional_feeds = array();
		$product_feeds    = array();
		$request_module   = Woo_Crm_Integration_Request_Module::get_instance();
		if ( ! empty( $all_feeds ) && is_array( $all_feeds ) ) {
			foreach ( $all_feeds as $key => $feed_id ) {
				$is_default_feed = $framework->get_feed( $feed_id, 'default_feed' );
				if ( $is_default_feed ) {
					array_push( $default_feeds, $feed_id );
				} else {
					array_push( $additional_feeds, $feed_id );
				}
			}
		}
		if ( ! empty( $default_feeds ) ) {
			foreach ( $default_feeds as $key => $feed_id ) {
				$record_type = $framework->get_feed( $feed_id, 'crm_object' );
				if ( 'Products' === $record_type ) {
					$product_feeds['0'] = $feed_id;
				}
			}
		}
		if ( ! empty( $additional_feeds ) ) {
			foreach ( $additional_feeds as $key => $feed_id ) {
				$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
				if ( 'product' === $woo_object ) {
					array_push( $product_feeds, $feed_id );
				}
			}
		}

		ksort( $product_feeds );

		$feed_id = $product_feeds[0];

		$has_more_records = true;
		$current_page = 1;

		do {

			$args = array(
				'per_page' => 200,
				'page'     => $current_page,
			);
	
			$response = $zoho_api->fetch_records( 'Products', $args );

			$zoho_products = ! empty( $response['data'] ) ? $response['data'] : array();
			$info          = ! empty( $response['info'] ) ? $response['info'] : array();
			foreach ( $zoho_products as $key => $zoho_product ) {
				$zoho_id = ! empty( $zoho_product['id'] ) ? $zoho_product['id'] : 0;
				if ( $zoho_id ) {
					$woo_product_id = $this->get_woo_product_id_by_zoho_id( $zoho_id );
					if ( $woo_product_id ) {
						$woo_product = wc_get_product( $woo_product_id );
						if ( $woo_product ) {
							if ( 'yes' == $update_qty ) {
								$product_stock = $woo_product->get_stock_quantity();
								$zoho_stock    = $zoho_product['Qty_in_Stock'];
		
								if ( $product_stock != $zoho_stock ) {
									wc_update_product_stock( $woo_product, $zoho_stock );
		
									$log_args = array(
										'event'         => 'update_stock',
										'woo_id'        => $woo_product_id,
										'zoho_id'       => $zoho_id,
										'product_stock' => $product_stock,
										'zoho_stock'    => $zoho_stock,
										'product_current_stock'=>$woo_product->get_stock_quantity(),
									);
		
									$this->create_sync_log_entry( $log_args );
								}
							}

							if ( 'yes' == $prod_syncing_from_zoho_to_woo ) {
								$zoho_product_desc = $zoho_product['Description'];
								$zoho_unitprice = $zoho_product['Unit_Price'];
								if ( !empty( $zoho_unitprice ) ) {
									$woo_product->set_regular_price( $zoho_unitprice );
								}
								if ( !empty( $zoho_product_desc ) ) {
									$woo_product->set_description( $zoho_product_desc );
								}
								$woo_product->save();
							}
						}
					} else {
						if ( 'yes' == $prod_creation_from_zoho_to_woo ) {
							$zoho_product_name = $zoho_product['Product_Name'];
							$zoho_product_desc = $zoho_product['Description'];
							if ( !empty( $zoho_product['Unit_Price'] ) ) {
								$zoho_unitprice = $zoho_product['Unit_Price'];
							} else {
								$zoho_unitprice = 0;
							}
		
							$product_obj = new WC_Product();
							if ($product_obj instanceof WC_Product) {
								$product_obj->set_name( $zoho_product_name );
								if ( 0 != $zoho_unitprice ) {
									$product_obj->set_regular_price( $zoho_unitprice );
								}
								if ( !empty( $zoho_product_desc ) ) {
									$product_obj->set_description( $zoho_product_desc );
								}
								$product_obj->save();
		
								if ( 'yes' == $update_qty ) {
									$zoho_stock    = $zoho_product['Qty_in_Stock'];
									if ( $product_obj->managing_stock() ) {
										if ( !empty( $zoho_stock ) ) {
											update_post_meta( $product_obj->get_id(), '_stock', $zoho_stock );
										}
									} else {
										update_post_meta( $product_obj->get_id(), '_manage_stock', 'yes' );
										if ( !empty( $zoho_stock ) ) {
											update_post_meta( $product_obj->get_id(), '_stock', $zoho_stock );
										}
									}
								}
		
								update_post_meta( $product_obj->get_id(), 'wciz_zoho_feed_' . $feed_id. '_association', $zoho_id );
							}
						}
					}
				}
			}

			if ( ! empty( $info['more_records'] ) && $info['more_records'] ) {
				$current_page++;
			} else {
				$current_page = 1;
				$has_more_records = false;
			}

		} while ($has_more_records);
	}

	/**
	 * Create log for product quantity and order status.
	 *
	 * @param array $log_args log data.
	 * @return void
	 */
	public function create_sync_log_entry( $log_args ) {

		if ( ! $this->is_data_log_enable() ) {
			return;
		}

		$log_dir = WC_LOG_DIR . 'wciz-woo-zoho-fetch-' . gmdate( 'Y-m-d' ) . '.log';

		if ( ! is_dir( $log_dir ) ) {
			@fopen( WC_LOG_DIR . 'wciz-woo-zoho-fetch-' . gmdate( 'Y-m-d' ) . '.log', 'a' ); //phpcs:ignore
		}

		global $wp_filesystem;  // Define global object of WordPress filesystem.
		WP_Filesystem();

		$file_data = '';
		if ( file_exists( $log_dir ) ) {
			$file_data = $wp_filesystem->get_contents( $log_dir );
		} else {
			$file_data = '';
		}

		$log = '------------------------------------' . PHP_EOL;

		foreach ( $log_args as $l_key => $l_value ) {
			$log .= strtoupper( $l_key ) . ' : ' . $l_value . PHP_EOL;
		}

		$log .= 'Time: ' . current_time( 'F j, Y  g:i a' ) . PHP_EOL;
		$log .= '------------------------------------' . PHP_EOL;

		$file_data .= $log;
		$wp_filesystem->put_contents( $log_dir, $file_data );
	}

	/**
	 * Get woo product id by zoho product id.
	 *
	 * @param  string $zoho_id Zoho record id.
	 * @return int|bool        Woo product id.
	 */
	public function get_woo_product_id_by_zoho_id( $zoho_id ) {

		$product_types = array(
			'product',
			'product_variation',
		);

		$woo_product_id = false;

		$product_feed_id = get_option( 'wciz_woo_zoho_default_Products_feed_id', false );

		if ( ! $product_feed_id ) {
			return false;
		}

		$args = array(
			'post_type'   => $product_types,
			'post_status' => 'any',
			'meta_query'     => array(  //phpcs:ignore,
				array(
					'key'     => 'wciz_zoho_feed_' . $product_feed_id . '_association',
					'compare' => '=',
					'value'   => $zoho_id,
				),
			),
		);

		$product_post = get_posts( $args );

		if ( ! empty( $product_post ) && ! empty( $product_post[0] ) ) {
			$woo_product_id = $product_post[0]->ID;
		}

		return $woo_product_id;
	}

	/**
	 * Get woo order id by zoho order id.
	 *
	 * @param  string $zoho_id Zoho record id.
	 * @return int|bool        Woo order id.
	 */
	public function get_woo_order_id_by_zoho_id( $zoho_id ) {

		$woo_order_id = false;

		$order_feed_id = get_option( 'wciz_woo_zoho_default_Sales_Orders_feed_id', false );

		if ( ! $order_feed_id ) {
			return false;
		}

		if ( Integration_With_Woo_Zoho_Order_Handler::wciz_get_cot_enabled_order_enabled() ) {
			$args = array(
				'type'   => 'shop_order',
				'status' => 'any',
				'meta_query'  => array(  //phpcs:ignore,
					array(
						'key'     => 'wciz_zoho_feed_' . $order_feed_id . '_association',
						'compare' => '=',
						'value'   => $zoho_id,
					),
				),
			);
	
			$order_post = wc_get_orders( $args );
	
			if ( ! empty( $order_post ) && ! empty( $order_post[0] ) ) {
				$woo_order_id = $order_post[0]->ID;
			}
		} else {
			$args = array(
				'post_type'   => 'shop_order',
				'post_status' => 'any',
				'meta_query'  => array(  //phpcs:ignore,
					array(
						'key'     => 'wciz_zoho_feed_' . $order_feed_id . '_association',
						'compare' => '=',
						'value'   => $zoho_id,
					),
				),
			);
	
			$order_post = get_posts( $args );
	
			if ( ! empty( $order_post ) && ! empty( $order_post[0] ) ) {
				$woo_order_id = $order_post[0]->ID;
			}
		}

		return $woo_order_id;
	}

	/**
	 * Fetch orders from Zoho crm.
	 */
	public function perform_order_fetch() {

		$update_status = get_option( 'wciz_zoho_update_order_status', 'no' );

		if ( 'yes' != $update_status ) {
			return;
		}

		$status_mapping = get_option( 'wciz_zoho_status_mapping', array() );

		$current_page = get_option( 'wciz_zoho_order_current_page', 1 );

		$zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();

		$args = array(
			'per_page' => 20,
			'page'     => $current_page,
		);

		$response = $zoho_api->fetch_records( 'Sales_Orders', $args );

		$zoho_orders = ! empty( $response['data'] ) ? $response['data'] : array();

		foreach ( $zoho_orders as $key => $zoho_order ) {

			$zoho_id = ! empty( $zoho_order['id'] ) ? $zoho_order['id'] : 0;
			if ( $zoho_id ) {
				$woo_order_id = $this->get_woo_order_id_by_zoho_id( $zoho_id );

				if ( $woo_order_id ) {
					$woo_order = wc_get_order( $woo_order_id );
					if ( $woo_order ) {
						$zoho_status    = $zoho_order['Status'];
						$woo_status     = 'wc-' . $woo_order->get_status();
						$updated_status = ! empty( $status_mapping[ $zoho_status ] ) ? $status_mapping[ $zoho_status ] : '';
						if ( $woo_status != $updated_status &&
							$this->is_valid_woo_status( $updated_status ) ) {
							$woo_order->update_status( $updated_status );
							$log_args = array(
								'event'       => 'update_order_status',
								'woo_id'      => $woo_order_id,
								'zoho_id'     => $zoho_id,
								'woo_status'  => $woo_status,
								'zoho_status' => $zoho_status,
								'woo_current_order_status'=>$woo_order->get_status(),
							);
							$this->create_sync_log_entry( $log_args );
						}
					}
				}
			}
		}

		$info = ! empty( $response['info'] ) ? $response['info'] : array();

		if ( ! empty( $info['more_records'] ) && $info['more_records'] ) {
			update_option( 'wciz_zoho_order_current_page', $current_page + 1 );
		} else {
			delete_option( 'wciz_zoho_order_current_page' );
		}
	}

	/**
	 * Check if order status is valid woo order status.
	 *
	 * @param  string $status Status key.
	 * @return boolean
	 */
	public function is_valid_woo_status( $status ) {

		if ( empty( $status ) ) {
			return false;
		}

		$woo_order_statuses = $this->get_woo_order_statuses();
		$status_keys        = array_keys( $woo_order_statuses );
		return in_array( $status, $status_keys );
	}

	/**
	 * Initiate deactivation screen.
	 */
	public function init_deactivation() {
		$file_path = 'admin/partials/templates/deactivation-screen.php';
		self::load_template( $file_path, array(), WOO_CRM_INTEGRATION_ZOHO_PATH );
	}

	/**
	 * Add a header panel for all screens in plugin.
	 * Returns :: HTML
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function render_navigation_tab() {
			$this->get_nav_tabs();
	}

	/**
	 * Get navigaton tabs.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function get_nav_tabs() {

		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'accounts'; // phpcs:ignore

		/* Tabs section start. */
		?>

		<nav class="mwb-cf7-integration-navbar">
			<div class="mwb-cf7-intergation-nav-collapse">
				<ul class="mwb-cf7-nav mwb-cf7-integration-nav-tabs" role="tablist">
					<?php $tabs = $this->retrieve_nav_tabs(); ?>
					<?php if ( ! empty( $tabs ) && is_array( $tabs ) ) : ?>
						<?php foreach ( $tabs as $href => $label ) : ?>
							<li class="mwb-cf7-integration-nav-item">
								<a class="mwb-cf7-integration-nav-link nav-tab <?php echo esc_html( $active_tab == $href ? 'nav-tab-active' : '' ); // phpcs:ignore ?>" href="?page=wciz_zoho_cf7&tab=<?php echo esc_html( $href ); ?>"><?php echo esc_html( $label ); ?></a>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
		</nav>

		<?php
		/* Tabs section end */

		switch ( $active_tab ) {

			case 'accounts':
				$params = array();

				$params['is_auth']    = get_option( 'wciz_woo_zoho_authorised' );
				$params['expires_in'] = self::get_access_token_expiry();
				$params['count']       = get_option( 'wciz_zcf7_synced_forms_count', 0 );
				$file_path       = '/partials/templates/accounts-tab.php';
				self::load_template( $file_path, $params );
				break;

			case 'feeds':
				$params          = array();
				$cpt_instance    = new Woo_Crm_Integration_For_Zoho_Cpt();
				$params['wpcf7'] = $cpt_instance->get_available_cf7_forms();
				$framework       = Woo_Crm_Integration_Connect_Framework::get_instance();
				$params['feeds'] = $framework->get_available_crm_feeds();
				$file_path       = 'woo-crm-fw/templates/tabs-contents/feeds-tab.php';
				self::load_template( $file_path, $params, WOO_CRM_INTEGRATION_ZOHO_PATH );
				break;

			case 'logs':
				$params               = array();
				$params['log_enable'] = $this->is_log_enable();
				$file_path            = 'woo-crm-fw/templates/tabs-contents/wciz-cf7-logs-tab.php';
				self::load_template( $file_path, $params, WOO_CRM_INTEGRATION_ZOHO_PATH );
				break;

			default:
				'';

		}
	}
	/**
	 * Get all nav tabs of current screen.
	 *
	 * @since     1.0.0
	 * @return    array   An array of screen tabs.
	 */
	public function retrieve_nav_tabs() {

		$current_screen = ! empty( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : false; // phpcs:ignore

		$tabs = '';

		switch ( $current_screen ) {

			case 'wciz_zoho_cf7':
				$tabs = array(
					'accounts' => esc_html__( 'Dashboard', 'woo-crm-integration-for-zoho' ),
				);

				$tabs_arr = array(
					'feeds' => esc_html__( 'Feeds', 'woo-crm-integration-for-zoho' ),
					'logs'  => esc_html__( 'Logs', 'woo-crm-integration-for-zoho' ),
				);

				$tabs = array_merge( $tabs, $tabs_arr );

				break;
		}

		/**
		 * Filters the value of Zoho plugin cf7 menu array.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  array.
		 */
		return apply_filters( $current_screen . '_tab', $tabs );
	}

		/**
		 * Check for the screens provided by the plugin.
		 *
		 * @since     1.0.0
		 * @return    bool
		 */
	public function is_valid_screen() {

		$result = false;

		$valid_screens = array(
			'woo-crm_page_wciz_zoho_cf7',
			'wciz_cf7_zoho_feeds',
		);

		$screen = get_current_screen();

		if ( ! empty( $screen->id ) ) {

			$pages = $screen->id;

			foreach ( $valid_screens as $screen ) {
				if ( false !== strpos( $pages, $screen ) ) { // phpcs:ignore
					$result = true;
				}
			}
		}

		return $result;
	}
	/**
	 * Get individual field mapping section.
	 *
	 * @param    array $field_options             CF7 field mapping options.
	 * @param    array $fields_data               CRM fields.
	 * @param    array $default_data              Default mapping data.
	 * @since    1.0.0
	 */
	public static function get_field_section_html( $field_options, $fields_data, $default_data = array() ) {
		if ( empty( $default_data ) ) {

			$default_data = array(
				'field_type'  => 'standard_field',
				'field_value' => '',
			);
		}

		$row_stndrd   = ( 'standard_field' === $default_data['field_type'] ) ? '' : 'row-hide';
		$row_custom   = ( 'custom_value' === $default_data['field_type'] ) ? '' : 'row-hide';
		$custom_value = ! empty( $default_data['custom_value'] ) ? $default_data['custom_value'] : '';
		$field_value  = ! empty( $default_data['field_value'] ) ? $default_data['field_value'] : '';
		?>
		<div class="mwb-feeds__form-wrap mwb-fields-form-row">
			<div class="mwb-form-wrapper">

				<div class="mwb-fields-form-section-head">
					<span class="field-label-txt"><?php echo esc_html( $fields_data['field_label'] ); ?></span>
					<input type="hidden" class="crm-field-name" name="crm_field[]" value="<?php echo esc_html( $fields_data['api_name'] ); ?>">
					<?php if ( isset( $fields_data['system_mandatory'] ) && ! $fields_data['system_mandatory'] ) : ?>
						<span class="field-delete dashicons">
							<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/trash.svg' ); ?>" style="max-width: 20px;">
						</span>
					<?php endif; ?>
				</div>

				<div class="mwb-fields-form-section-meta">
					<span>
						<?php esc_html_e( 'API Name : ', 'woo-crm-integration-for-zoho' ); // phpcs:ignore ?>
						<?php echo esc_html( $fields_data['api_name'] ); ?>
					</span>
					<span>
						<?php esc_html_e( 'Type : ', 'woo-crm-integration-for-zoho' ); ?>
						<?php echo esc_html( $fields_data['data_type'] ); ?>
					</span>
					<?php if ( ! empty( $fields_data['length'] ) ) : ?>
						<span>
							<?php esc_html_e( 'Length : ', 'woo-crm-integration-for-zoho' ); ?>
							<?php echo esc_html( ! empty( $fields_data['length'] ) ? $fields_data['length'] : '' ); ?> 
						</span>
					<?php endif; ?>
					<?php if ( ! empty( $fields_data['pick_list_values'] ) ) : ?>
						<span>
							<?php esc_html_e( 'Options : ', 'woo-crm-integration-for-zoho' ); ?>
							<?php foreach ( $fields_data['pick_list_values'] as $value ) : ?>
								<?php echo esc_html( $value['display_value'] . ' = ' . $value['actual_value'] . ',' ); ?> 
							<?php endforeach; ?>
						</span>
					<?php endif; ?>
					<?php if ( ! empty( $fields_data['field'] ) ) : ?>
						<span>
							<?php esc_html_e( 'Field : ', 'woo-crm-integration-for-zoho' ); ?><?php echo esc_html( $fields_data['field'] ); ?> 
						</span>
					<?php endif; ?>
					<?php if ( isset( $fields_data['system_mandatory'] ) && $fields_data['system_mandatory'] ) : ?>
						<span class="mwb-required-tag">
							<b><?php printf( '%s ', esc_html__( 'Required Field', 'woo-crm-integration-for-zoho' ) ); ?></b>
						</span>
					<?php endif; ?>
				</div>

				<div class="mwb-fields-form-section-form">

					<div class="form-field-row row-field-type">
						<label>
							<?php esc_html_e( 'Field Type', 'woo-crm-integration-for-zoho' ); ?>
						</label>
						<select class="field-type-select" name="field_type[]">
							<option value=""><?php esc_html_e( 'Select an Option', 'woo-crm-integration-for-zoho' ); ?></option>
							<option value="standard_field" <?php echo esc_attr( selected( 'standard_field', $default_data['field_type'] ) ); ?> >
								<?php esc_html_e( 'Standard Value', 'woo-crm-integration-for-zoho' ); ?>
							</option>
							<option value="custom_value" <?php echo esc_attr( selected( 'custom_value', $default_data['field_type'] ) ); ?>>
								<?php esc_html_e( 'Custom Value', 'woo-crm-integration-for-zoho' ); ?>
							</option>
						</select>
					</div>

					<div class="form-field-row row-field-value row-standard_field <?php echo esc_attr( $row_stndrd ); ?>">
						<label><?php esc_html_e( 'Field Value', 'woo-crm-integration-for-zoho' ); ?></label>
						<select class="field-value-select" name="field_value[]">
							<option value=""><?php esc_html_e( 'Select an Option', 'woo-crm-integration-for-zoho' ); ?></option>
							<?php foreach ( $field_options as $k1 => $options ) : ?>
								<optgroup label="<?php echo esc_attr( ucfirst( str_replace( '_', ' ', $k1 ) ) ); ?>">
								<?php foreach ( $options as $k2 => $name ) : ?>
									<option value="<?php echo esc_attr( $k1 . '_' . $k2 ); ?>" <?php echo esc_attr( selected( $k1 . '_' . $k2, $field_value ) ); ?>>
										<?php echo esc_html( $name ); ?>
									</option>
								<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-field-row row-custom_value row-field-value <?php echo esc_attr( $row_custom ); ?>">
						<label>
							<?php esc_html_e( 'Custom Value', 'woo-crm-integration-for-zoho' ); ?>
						</label>
						<input type="text" class="custom-value-input" name="custom_value[]" value="<?php echo esc_attr( $custom_value ); ?>">
						<select class="custom-value-select" name="custom_field[]">
							<option value=""><?php esc_html_e( 'Select an Option', 'woo-crm-integration-for-zoho' ); ?></option>
							<?php foreach ( $field_options as $k1 => $options ) : ?>
								<optgroup label="<?php echo esc_attr( ucfirst( str_replace( '_', ' ', $k1 ) ) ); ?>">
								<?php foreach ( $options as $k2 => $name ) : ?>
									<option value="<?php echo esc_attr( $k1 . '_' . $k2 ); ?>">
										<?php echo esc_html( $name ); ?>
									</option>
								<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>
					</div>

				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Create database table for logs.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function wciz_zcf7_create_log_table() {

		global $wpdb;
		$table_name = $wpdb->prefix . 'wciz_zcf7_log';

		$result = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if ( empty( $result ) || $result != $table_name ) { // phpcs:ignore

			try {
				global $wpdb;
				$table_name      = $wpdb->prefix . 'wciz_zcf7_log';

				$query = "CREATE TABLE IF NOT EXISTS $table_name (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`feed` varchar(255) NOT NULL,
					`feed_id` int(11) NOT NULL,
					`zoho_object` varchar(255) NOT NULL,
					`zoho_id` varchar(255) NOT NULL,
					`event` varchar(255) NOT NULL,
					`request` text NOT NULL,
					`response` text NOT NULL,
					`time` int(11) NOT NULL,
					PRIMARY KEY (`id`)
				  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				wciz_woo_zoho_execute_db_query( $query );
				update_option( 'wciz_woo_zoho_cf7_log_table_created', true );

			} catch ( \Throwable $th ) {
				wp_die( esc_html( $th->getMessage() ) );
			}
		} else {
			update_option( 'wciz_woo_zoho_cf7_log_table_created', true );
		}
	}


	/**
	 * Return mwb-plugin menu at customtaxonomy page.
	 *
	 * @param mixed $parent_file parent_file.
	 * @return mixed
	 */
	public function wciz_prefix_highlight_taxonomy_parent_menu( $parent_file ) {
		global $submenu_file, $current_screen, $pagenow;

		if ( get_current_screen()->id == 'wciz_cf7_zoho_feeds' ) {
			$parent_file = 'wciz-crm-plugins';
		}
		return $parent_file;
	}
	/**
	 * Get feeds filter html.
	 *
	 * @param     object $feed    Feed object.
	 * @since     1.0.0
	 * @return    mixed
	 */
	public static function get_filter_section_html( $feed ) {

		$cpt_instance = new Woo_Crm_Integration_For_Zoho_Cpt();

		if ( ! empty( $feed ) ) {
			$_status        = get_post_status( $feed->ID );
			$edit_link      = get_edit_post_link( $feed->ID );
			$cf7_from       = $cpt_instance->get_feed_data( $feed->ID, 'wciz_zcf7_form', '-' );
			$crm_object     = $cpt_instance->get_feed_data( $feed->ID, 'wciz_zcf7_object', '-' );
			$primary_field  = $cpt_instance->get_feed_data( $feed->ID, 'wciz_zcf7_primary_field', '-' );
			$checked        = 'publish' == $_status ? 'checked="checked"' : ''; // phpcs:ignore
			$filter_applied = $cpt_instance->get_feed_data( $feed->ID, 'wciz_zcf7_enable_filters', '-' );
			?>
			<li class="mwb-cf7-integration__feed-row">
				<div class="mwb-cf7-integration__left-col">
					<h3 class="mwb-about__list-item-heading"><?php echo esc_html( $feed->post_title ); ?></h3>
					<div class="mwb-feed-status__wrap">
						<p class="mwb-feed-status-text_<?php echo esc_attr( $feed->ID ); ?>" ><strong><?php echo esc_html( 'publish' == $_status ? 'Active' : 'Sandbox' ); ?></strong></p>
						<p><input type="checkbox" class="mwb-feed-status" value="publish" <?php echo esc_html( $checked ); ?> feed-id=<?php esc_attr( $feed->ID ); ?>></p>
					</div>
					<p>
						<span class="mwb-about__list-item-sub-heading"><?php esc_html_e( 'Form : ', 'woo-crm-integration-for-zoho' ); ?></span>
						<span><?php echo esc_html( get_the_title( $cf7_from ) ); ?></span>
					</p>
					<p>
						<span class="mwb-about__list-item-sub-heading"><?php esc_html_e( 'Object : ', 'woo-crm-integration-for-zoho' ); ?></span>
						<span><?php echo esc_html( $crm_object ); ?></span>
					</p>
					<p>
						<span class="mwb-about__list-item-sub-heading"><?php esc_html_e( 'Primary Key : ', 'woo-crm-integration-for-zoho' ); ?></span>
						<span><?php echo esc_html( $primary_field ); ?></span>
					</p>
					<p>
						<span class="mwb-about__list-item-sub-heading"><?php esc_html_e( 'Conditions : ', 'woo-crm-integration-for-zoho' ); ?></span>
						<span><?php echo esc_html( 'yes' != $filter_applied ? '-' : esc_html__( 'Applied', 'woo-crm-integration-for-zoho' ) ); // phpcs:ignore ?></span> 
					</p>
				</div>
				<div class="mwb-cf7-integration__right-col">
					<a href="<?php echo esc_url( $edit_link ); ?>"><img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/edit.svg' ); ?>" alt="<?php esc_html_e( 'Edit feed', 'woo-crm-integration-for-zoho' ); ?>"></a>
					<div class="mwb-cf7-integration__right-col1">
						<a href="javascript:void(0)" class="mwb_cf7_integration_trash_feed" feed-id="<?php echo esc_html( $feed->ID ); ?>">
							<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/trash.svg' ); ?>" alt="<?php esc_html_e( 'Trash feed', 'woo-crm-integration-for-zoho' ); ?>">
						</a>
					</div>
				</div>
			</li>
			<?php
		}
	}
	/**
	 * Return booking sub-menu at custom taxonomy page.
	 *
	 * @param mixed $submenu_file submenu_file.
	 * @param mixed $parent_file parent_file.
	 * @return mixed
	 */
	public function wciz_set_submenu_file_to_handle_menu_for_wp_pages( $submenu_file, $parent_file ) {

		if ( ( get_current_screen()->id == 'wciz_cf7_zoho_feeds' ) ) {
			$submenu_file = 'wciz_zoho_cf7';
		}

		return $submenu_file;
	}

	/**
	 * Function to check for plugin activation.
	 *
	 * @return   bool
	 */
	public static function wciz_zcf7_is_plugin_active() {

		$slug = 'contact-form-7/wp-contact-form-7.php';

		$active_plugins = get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_option( 'active_sitewide_plugins', array() ) );
		}

		return in_array( $slug, $active_plugins, true ) || array_key_exists( $slug, $active_plugins );
	}

	/**
	 * Add options in order values dropdown
	 *
	 * @param mixed $order_callbacks
	 * @return void
	 */
	public function wciz_woo_zoho_order_field_values_filter_callback( $order_callbacks ) {
		
		if ( ! $this->wciz_is_compatible_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
			$order_call_subscription = array();
		} else {
			$order_call_subscription = array(
				'if_subscription_or_one_shot'    => 'get_if_subscription_or_one_shot',
				'subscription_next_payment_date' => 'get_subscription_next_payment_date',
				'subscription_status'            => 'get_subscription_status',
				'subscription_price'             => 'get_subscription_price',
				'subscription_products_details'  => 'get_subscription_products_details',
				'subscription_id'       => 'get_subscription_id',
				'subscription_customer'   => 'get_subscription_customer',
				'subscription_payment_method'   => 'get_subscription_payment_method',
				'subscription_payment_schedule'   => 'get_subscription_payment_schedule',
				'subscription_start_date'   => 'get_subscription_start_date',
				'subscription_end_date'   => 'get_subscription_end_date',
				'subscription_next_payment' => 'get_subscription_next_payment',
				'subscription_information'  => 'get_subscription_information',
				'subscription_variation_id' => 'get_subscription_variation_id',
				'subscription_user_id'      => 'get_subscription_user_id',
				'subscription_email_id'     => 'get_subscription_email_id',
			);
		}

		if ( ! $this->wciz_is_compatible_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
			$order_call_membership = array();
		} else {
			$order_call_membership = array(
				'membership_plan'         => 'get_membership_plan',
				'membership_plan_id'      => 'get_membership_plan_id',
				'user_membership_id'      => 'get_user_membership_id',
				'membership_status'       => 'get_membership_status',
				'membership_product_id'   => 'get_membership_product_id',
				'membership_product_name' => 'get_membership_product_name',
				'membership_next_bill_on' => 'get_membership_next_bill_on',
				'membership_subscription' => 'get_membership_subscription',
				'membership_order_total' => 'get_membership_order_total',
				'membership_order_date' => 'get_membership_order_date',
				'membership_purchased_in' => 'get_membership_purchased_in',
				'membership_expiry' => 'get_membership_expiry',
				'membership_since' => 'get_membership_since',
				'membership_user_id' => 'get_membership_user_id',
			);
		}

		return array_merge( $order_callbacks, $order_call_subscription, $order_call_membership );
	}

	/**
	 * Function to check for plugin activation.
	 *
	 * @param [type] $slug
	 * @return bool
	 */
	public function wciz_is_compatible_plugin_active( $slug ) {

		$active_plugins = get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_option( 'active_sitewide_plugins', array() ) );
		}

		return in_array( $slug, $active_plugins, true ) || array_key_exists( $slug, $active_plugins );
	}

	// new code
	public function custom_function_on_product_delete( $post_id ) {

		if ( get_post_type($post_id) === 'product'|| 'product_variation' === get_post_type($post_id) ) {
			// Perform your actions here
			
			$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
			$all_feeds        = $framework->get_all_feed();
			$default_feeds    = array();
			$additional_feeds = array();
			$product_feeds    = array();
			$request_module   = Woo_Crm_Integration_Request_Module::get_instance();
			if ( ! empty( $all_feeds ) && is_array( $all_feeds ) ) {
				foreach ( $all_feeds as $key => $feed_id ) {
					$is_default_feed = $framework->get_feed( $feed_id, 'default_feed' );
					if ( $is_default_feed ) {
						array_push( $default_feeds, $feed_id );
					} else {
						array_push( $additional_feeds, $feed_id );
					}
				}
			}
			if ( ! empty( $default_feeds ) ) {
				foreach ( $default_feeds as $key => $feed_id ) {
					$record_type = $framework->get_feed( $feed_id, 'crm_object' );
					if ( 'Products' === $record_type ) {
						$product_feeds['0'] = $feed_id;
					}
				}
			}
			if ( ! empty( $additional_feeds ) ) {
				foreach ( $additional_feeds as $key => $feed_id ) {
					$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
					if ( 'product' === $woo_object ) {
						array_push( $product_feeds, $feed_id );
					}
				}
			}

			ksort( $product_feeds );

			$feed_id = $product_feeds[0];

			$record_id = get_post_meta( $post_id, 'wciz_zoho_feed_' . $feed_id . '_association', true );
			$api_class = new Woo_Crm_Integration_Zoho_Api();
			$response = $api_class->delete_record( $record_id, 'Products', array() );
		}
	}

	public function action_wp_trash_post( $post_id ) {
		if (get_post_type($post_id) === 'product') {

			$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
			$all_feeds        = $framework->get_all_feed();
			$default_feeds    = array();
			$additional_feeds = array();
			$product_feeds    = array();
			$request_module   = Woo_Crm_Integration_Request_Module::get_instance();
			if ( ! empty( $all_feeds ) && is_array( $all_feeds ) ) {
				foreach ( $all_feeds as $key => $feed_id ) {
					$is_default_feed = $framework->get_feed( $feed_id, 'default_feed' );
					if ( $is_default_feed ) {
						array_push( $default_feeds, $feed_id );
					} else {
						array_push( $additional_feeds, $feed_id );
					}
				}
			}
			if ( ! empty( $default_feeds ) ) {
				foreach ( $default_feeds as $key => $feed_id ) {
					$record_type = $framework->get_feed( $feed_id, 'crm_object' );
					if ( 'Products' === $record_type ) {
						$product_feeds['0'] = $feed_id;
					}
				}
			}
			if ( ! empty( $additional_feeds ) ) {
				foreach ( $additional_feeds as $key => $feed_id ) {
					$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
					if ( 'product' === $woo_object ) {
						array_push( $product_feeds, $feed_id );
					}
				}
			}

			ksort( $product_feeds );

			$feed_id = $product_feeds[0];

			$connect_framework = new Woo_Crm_Integration_Connect_Framework();

			$request = $connect_framework->get_request(
				'Products',
				$feed_id,
				$post_id
			);
			if ( isset( wc_get_product($post_id)->get_data()['name'] ) ) {

				$prod_name = wc_get_product($post_id)->get_data()['name'];
				$prod_price = wc_get_product($post_id)->get_data()['price'];
				$prod_desc = wc_get_product($post_id)->get_data()['description'];
				if ( array_key_exists( 'Product_Name', $request ) && empty( $request['Product_Name'] ) ) {
					$request['Product_Name'] = $prod_name;
				}
				$request['Product_Active'] = false;
				$request['Product_Code'] = 'woo-product-' . $post_id;
				if ( array_key_exists( 'Unit_Price', $request ) && empty( $request['Unit_Price'] ) ) {
					$request['Unit_Price'] = $prod_price;
				}
				if ( array_key_exists( 'Description', $request ) && empty( $request['Description'] ) ) {
					$request['Description'] = $prod_desc;
				}
			}

			$record_type = $connect_framework->get_feed( $feed_id, 'crm_object' );

			$log_data = array();

			$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
			$result                   = $crm_integration_zoho_api->create_single_record(
				$record_type,
				$request,
				false,
				$log_data
			);
		}
	}

	public function action_wp_untrash_post( $post_id ) {

		if (get_post_type($post_id) === 'product') {
			
			$framework        = Woo_Crm_Integration_Connect_Framework::get_instance();
			$all_feeds        = $framework->get_all_feed();
			$default_feeds    = array();
			$additional_feeds = array();
			$product_feeds    = array();
			$request_module   = Woo_Crm_Integration_Request_Module::get_instance();
			if ( ! empty( $all_feeds ) && is_array( $all_feeds ) ) {
				foreach ( $all_feeds as $key => $feed_id ) {
					$is_default_feed = $framework->get_feed( $feed_id, 'default_feed' );
					if ( $is_default_feed ) {
						array_push( $default_feeds, $feed_id );
					} else {
						array_push( $additional_feeds, $feed_id );
					}
				}
			}
			if ( ! empty( $default_feeds ) ) {
				foreach ( $default_feeds as $key => $feed_id ) {
					$record_type = $framework->get_feed( $feed_id, 'crm_object' );
					if ( 'Products' === $record_type ) {
						$product_feeds['0'] = $feed_id;
					}
				}
			}
			if ( ! empty( $additional_feeds ) ) {
				foreach ( $additional_feeds as $key => $feed_id ) {
					$woo_object = $request_module->get_woo_object_based_on_field_mapping( $feed_id );
					if ( 'product' === $woo_object ) {
						array_push( $product_feeds, $feed_id );
					}
				}
			}

			ksort( $product_feeds );

			$feed_id = $product_feeds[0];

			$connect_framework = new Woo_Crm_Integration_Connect_Framework();

			$request = $connect_framework->get_request(
				'Products',
				$feed_id,
				$post_id
			);
			if ( isset( wc_get_product($post_id)->get_data()['name'] ) ) {
				$prod_name = wc_get_product($post_id)->get_data()['name'];
				$prod_price = wc_get_product($post_id)->get_data()['price'];
				$prod_desc = wc_get_product($post_id)->get_data()['description'];
				if ( array_key_exists( 'Product_Name', $request ) && empty( $request['Product_Name'] ) ) {
					$request['Product_Name'] = $prod_name;
				}
				$request['Product_Active'] = true;
				$request['Product_Code'] = 'woo-product-' . $post_id;
				if ( array_key_exists( 'Unit_Price', $request ) && empty( $request['Unit_Price'] ) ) {
					$request['Unit_Price'] = $prod_price;
				}
				if ( array_key_exists( 'Description', $request ) && empty( $request['Description'] ) ) {
					$request['Description'] = $prod_desc;
				}
			}

			$record_type = $connect_framework->get_feed( $feed_id, 'crm_object' );

			$log_data = array();

			$crm_integration_zoho_api = Woo_Crm_Integration_Zoho_Api::get_instance();
			$result                   = $crm_integration_zoho_api->create_single_record(
				$record_type,
				$request,
				false,
				$log_data
			);
		}
	}


	public function delete_product_from_wordpress( $zoho_prod_id, $woo_prod_id ) {
		$api_class = new Woo_Crm_Integration_Zoho_Api();
		$response = $api_class->get_record( $zoho_prod_id, 'Products', array() );
		if ( isset( $response['code'] ) && empty( $response['data'] ) && '204' == $response['code'] ) {
			wp_delete_post( $woo_prod_id );
		}
	}

	/**
	 * Get time in ago format
	 */

	public static function zoho_get_time_ago( $ptime ) {

		$estimate_time = time() - $ptime;

		if ( $estimate_time < 1 ) {
			return 'less than 1 second ago';
		}

		$condition = array(
		   12 * 30 * 24 * 60 * 60  => 'year',
		   30 * 24 * 60 * 60       => 'month',
		   24 * 60 * 60            => 'day',
		   60 * 60                 => 'hour',
		   60                      => 'minute',
		   1                       => 'second',
		);

		foreach ( $condition as $secs => $str ) {
			$d = $estimate_time / $secs;

			if ( $d >= 1 ) {
				$r = round( $d );
				return $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
			}
		}
	}
	// End of class.
}