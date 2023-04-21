<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://chirag.wisdmlabs.net
 * @since      1.0.0
 *
 * @package    Pick_Up
 * @subpackage Pick_Up/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pick_Up
 * @subpackage Pick_Up/includes
 * @author     Chirag Rakh <chirag.rakh@wisdmlabs.com>
 */
class Pick_Up_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pick-up',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
