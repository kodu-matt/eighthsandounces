<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Woounits
 * @subpackage Woounits/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woounits
 * @subpackage Woounits/admin
 */
class Woounits_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name  The name of this plugin.
	 * @param    string    $version      The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// By default: integer.
		remove_filter( 'woocommerce_stock_amount', 'intval' );
		// update float
		add_filter( 'woocommerce_stock_amount', 'floatval' );
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
		 * defined in Woounits_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woounits_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, WOOUNITS_PLUGIN_URL . '/admin/css/woounits-admin.css', array(), $this->version, 'all' );

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
		 * defined in Woounits_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woounits_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( function_exists( 'WC' ) ) {
			wp_enqueue_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI.js', array( 'jquery' ), '2.70', true );
		}
		wp_enqueue_script( 'wut-woounits-admin', WOOUNITS_PLUGIN_URL . '/admin/js/woounits-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			'wut-woounits-admin',
			'wut_admin',
			array(
				'admin_url' => AJAX_URL,
			)
		);

	}

	/**
	 * This function will include select2 scripts
	 *
	 * @since 1.0.0
	 */
	function wut_incl_select2_scripts() {

		wp_enqueue_style(
			'wut-woocommerce_admin_styles',
			plugins_url() . '/woocommerce/assets/css/admin.css',
			'',
			$this->version,
			false
		);

		wp_enqueue_style(
			'wut-select2_styles',
			plugins_url() . '/woocommerce/assets/css/select2.css',
			'',
			$this->version,
			false
		);

		wp_register_script(
			'select2',
			plugins_url() . '/woocommerce/assets/js/select2/select2.min.js',
			array( 'jquery', 'jquery-ui-widget', 'jquery-ui-core' ),
			$this->version,
			false
		);

		wp_enqueue_script( 'select2' );
	}

}
