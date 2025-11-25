<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woounits
 * @subpackage Woounits/includes
 */
class Woounits_Activator {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'woounits_do_required_plugin_check' ) );
		add_action( 'before_woocommerce_init', array( $this, 'woounits_custom_order_tables_compatibility' ), 999 );
	}

	/**
	 * Activate plugin.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		if ( ! self::woounits_is_required_plugin_active() ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( self::woounits_woocommerce_plugin_not_found_error_message() ); //phpcs:ignore
		}

		self::woounits_activate();
	}

	/**
	 * Create a custom table upon plugin activation.
	 */
	public static function woounits_activate() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}unit_history (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`post_id` int(11) NOT NULL,
			`unit_level` int(11) NOT NULL,
			`unit_level_key` varchar(50) NULL,
			`unit_level_value` decimal(10,4) NULL,
			`unit_level_price` decimal(10,4) NULL,
			`from_unit` varchar(50) NOT NULL,
			`to_unit` varchar(50) NOT NULL,
			`total_unit` decimal(10,4) NOT NULL,
			`available_unit` decimal(10,4) NOT NULL,
			`from_total` decimal(10,5) NOT NULL,
			`to_total` decimal(10,4) NOT NULL,
			`status` varchar(20) NOT NULL DEFAULT 'active',
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
		);

		dbDelta(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}unit_order_history (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`order_id` int(11) NOT NULL,
			`unit_id` int(11) NOT NULL,
			PRIMARY KEY (`id`)
			)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
		);

		update_option( 'woounits_db_version', WOOUNITS_VERSION );
	}

	/**
	 * Verifies whether WooCommerce is installed and activated.
	 *
	 * @since 1.0.0
	 */
	public static function woounits_is_required_plugin_active() {
		$woocommerce_path = 'woocommerce/woocommerce.php';
		$active_plugins   = (array) get_option( 'active_plugins', array() );

		$active = false;
		if ( is_multisite() ) {
			$plugins = get_site_option( 'active_sitewide_plugins' );
			if ( isset( $plugins[ $woocommerce_path ] ) ) {
				$active = true;
			}
		}

		return in_array( $woocommerce_path, $active_plugins ) || array_key_exists( $woocommerce_path, $active_plugins ) || $active;
	}

	/**
	 * Return Error Message when WooCommerce plugin is not found.
	 *
	 * @since 1.0.0
	 */
	public static function woounits_woocommerce_plugin_not_found_error_message() {
		return sprintf(
			/* translators: Plugin Name */
			__( 'WooCommerce not found. %s requires a minimum of WooCommerce v3.3.0.', 'woounits' ),
			WOOUNITS_PLUGIN_NAME
		);
	}

	/**
	 * Verifies whether WooCommerce is installed and activated.
	 *
	 * @since 1.0.0
	 */
	public static function woounits_do_required_plugin_check() {
		if ( ! self::woounits_is_required_plugin_active() ) {
			if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
				deactivate_plugins( plugin_basename( __FILE__ ) );
				add_action( 'admin_notices', array( $this, 'woounits_required_plugin_error_notice' ) );
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			}
		}
	}

	/**
	 * Displays WooCommerce Required Notice.
	 *
	 * @since 1.0.0
	 */
	public static function woounits_required_plugin_error_notice() {
		echo '<div class="error"><p><strong>' . self::woounits_woocommerce_plugin_not_found_error_message() . '</strong></p></div>'; // phpcs:ignore
	}

	/**
	 * Configure the plugin for compatibility with the WC Custom Order Table (HPOS) feature.
	 */
	public static function woounits_custom_order_tables_compatibility() {
		if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

}
