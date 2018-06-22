<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://lenineto.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Smart_Coupons
 * @subpackage Woocommerce_Smart_Coupons/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Smart_Coupons
 * @subpackage Woocommerce_Smart_Coupons/includes
 * @author     Leni Neto <leni@cyber5.net>
 */
class Woocommerce_Smart_Coupons_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-smart-coupons',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
