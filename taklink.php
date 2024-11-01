<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              Takl.ink
 * @since             1.0.0
 * @package           TakLink
 *
 * @wordpress-plugin
 * Plugin Name:       Taklink
 * Plugin URI:        https://takl.ink/
 * Description:       Takl.ink is a tools to make a bio link with multiple links. You can use your TakL.ink as Instagram bio link or other social networks like Telegram, Facebook, Twitter, ...
 * Version:           1.1.3
 * Author:            taklink
 * Author URI:        https://profiles.wordpress.org/taklink/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       taklink
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
define( 'TAKLINK_VERSION', '1.1.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-taklink-activator.php
 */
function activate_taklink() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-taklink-activator.php';
	TakLink_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-taklink-deactivator.php
 */
function deactivate_taklink() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-taklink-deactivator.php';
	TakLink_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_taklink' );
register_deactivation_hook( __FILE__, 'deactivate_taklink' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-taklink.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_taklink() {

	$plugin = new TakLink();
	$plugin->run();

}
run_taklink();
