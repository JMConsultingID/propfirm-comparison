<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://fundedtrading.com
 * @since             1.0.0
 * @package           Propfirm_Comparison
 *
 * @wordpress-plugin
 * Plugin Name:       A - Funded Trading Propfirm Comparison
 * Plugin URI:        https://fundedtrading.com
 * Description:       This Plugin for Generate Comparison of Propfirm
 * Version:           1.0.0
 * Author:            Ardika JM Consulting
 * Author URI:        https://fundedtrading.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       propfirm-comparison
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
define( 'PROPFIRM_COMPARISON_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-propfirm-comparison-activator.php
 */
function activate_propfirm_comparison() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-propfirm-comparison-activator.php';
	Propfirm_Comparison_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-propfirm-comparison-deactivator.php
 */
function deactivate_propfirm_comparison() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-propfirm-comparison-deactivator.php';
	Propfirm_Comparison_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_propfirm_comparison' );
register_deactivation_hook( __FILE__, 'deactivate_propfirm_comparison' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-propfirm-comparison.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-propfirm-comparison-functions.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-propfirm-comparison-elementor.php';

function filter_action_propfirm_comparison_links( $links ) {
     $links['settings'] = '<a href="#">' . __( 'Settings', 'propfirm-comparison' ) . '</a>';
     $links['support'] = '<a href="#">' . __( 'Doc', 'propfirm-comparison' ) . '</a>';
     // if( class_exists( 'Fyfx_Payment' ) ) {
     //  $links['upgrade'] = '<a href="https://fundyourfx.com">' . __( 'Upgrade', 'propfirm-comparison' ) . '</a>';
     // }
     return $links;
}
add_filter( 'plugin_action_links_propfirm-comparison/propfirm-comparison.php', 'filter_action_propfirm_comparison_links', 10, 1 );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_propfirm_comparison() {

	$plugin = new Propfirm_Comparison();
	$plugin->run();

}
run_propfirm_comparison();
