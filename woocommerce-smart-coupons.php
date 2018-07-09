<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://lenineto.com/
 * @since             1.0.0
 * @package           Woocommerce_Smart_Coupons
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Smart Coupons
 * Plugin URI:        https://cyber5.net/wp-plugins/woocommerce-smart-coupons
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.1.1
 * Author:            Leni Neto
 * Author URI:        https://lenineto.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-smart-coupons
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-smart-coupons-activator.php
 */
function activate_woocommerce_smart_coupons() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-smart-coupons-activator.php';
	Woocommerce_Smart_Coupons_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-smart-coupons-deactivator.php
 */
function deactivate_woocommerce_smart_coupons() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-smart-coupons-deactivator.php';
	Woocommerce_Smart_Coupons_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_smart_coupons' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_smart_coupons' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-smart-coupons.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_smart_coupons() {

	$plugin = new Woocommerce_Smart_Coupons();
	$plugin->run();

}
run_woocommerce_smart_coupons();
