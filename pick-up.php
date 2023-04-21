<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://chirag.wisdmlabs.net
 * @since             1.0.0
 * @package           Pick_Up
 *
 * @wordpress-plugin
 * Plugin Name:       pick-up
 * Plugin URI:        https://chirag.wisdmlabs.net
 * Description:       Adds the functionality of pick up option besides shipping
 * Version:           1.0.0
 * Author:            Chirag Rakh
 * Author URI:        https://chirag.wisdmlabs.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pick-up
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
define( 'PICK_UP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pick-up-activator.php
 */
function activate_pick_up() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pick-up-activator.php';
	Pick_Up_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pick-up-deactivator.php
 */
function deactivate_pick_up() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pick-up-deactivator.php';
	Pick_Up_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pick_up' );
register_deactivation_hook( __FILE__, 'deactivate_pick_up' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pick-up.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pick_up() {

	$plugin = new Pick_Up();
	$plugin->run();

}
run_pick_up();
