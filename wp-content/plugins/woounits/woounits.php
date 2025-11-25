<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wordpress.org/
 * @since             1.0.0
 * @package           Woounits
 *
 * @wordpress-plugin
 * Plugin Name:       WooUnits
 * Plugin URI:        https://wordpress.org/
 * Description:       WooUnits enables WooCommerce stores to sell products in custom units for precise customer orders.
 * Version:           1.0.0
 * Author:            WooCommerce
 * Author URI:        https://wordpress.org//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woounits
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOOUNITS_VERSION', '1.0.0' );

define( 'WOOUNITS_PLUGIN_NAME', 'WooUnits' );
define( 'WOOUNITS_FILE', __FILE__ );
define( 'WOOUNITS_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WOOUNITS_PLUGIN_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'WOOUNITS_IMAGE_URL', WOOUNITS_PLUGIN_URL . '/assets/images/' );
define( 'WOOUNITS_DOMAIN', 'woounits' );
define( 'WOOUNITS_INCLUDE_PATH', WOOUNITS_PLUGIN_PATH . '/includes/' );
define( 'AJAX_URL', get_admin_url() . 'admin-ajax.php' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woounits-activator.php
 */
function activate_woounits() {
	require_once WOOUNITS_INCLUDE_PATH . 'class-woounits-activator.php';
	$actv = new Woounits_Activator();
	$actv::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woounits-deactivator.php
 */
function deactivate_woounits() {
	require_once WOOUNITS_INCLUDE_PATH . 'class-woounits-deactivator.php';
	Woounits_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woounits' );
register_deactivation_hook( __FILE__, 'deactivate_woounits' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once WOOUNITS_PLUGIN_PATH . '/includes/class-woounits.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woounits() {

	$plugin = new Woounits();
	$plugin->run();

}
run_woounits();
