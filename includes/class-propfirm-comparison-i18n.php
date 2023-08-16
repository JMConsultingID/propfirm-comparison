<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://fundedtrading.com
 * @since      1.0.0
 *
 * @package    Propfirm_Comparison
 * @subpackage Propfirm_Comparison/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Propfirm_Comparison
 * @subpackage Propfirm_Comparison/includes
 * @author     Ardika JM Consulting <ardi@jm-consulting.id>
 */
class Propfirm_Comparison_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'propfirm-comparison',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
