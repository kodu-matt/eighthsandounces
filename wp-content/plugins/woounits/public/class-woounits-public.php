<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Woounits
 * @subpackage Woounits/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woounits
 * @subpackage Woounits/public
 */
class Woounits_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @access private
	 * @var    string  $plugin_name    The ID of this plugin.
	 *
	 * @since    1.0.0
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access private
	 * @var    string  $version The current version of this plugin.
	 *
	 * @since    1.0.0
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
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

		wp_enqueue_style( $this->plugin_name, WOOUNITS_PLUGIN_URL . '/public/css/woounits-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
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

		wp_enqueue_script( 'wut-woounits-public', WOOUNITS_PLUGIN_URL . '/public/js/woounits-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			'wut-woounits-public',
			'wut_public',
			array(
				'admin_url' => AJAX_URL,
				'def_selected_unit' => get_option( 'wut_def_selected_unit', 'yes' ),
			)
		);
	}

}
