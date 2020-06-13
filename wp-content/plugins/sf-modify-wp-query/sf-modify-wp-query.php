<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.sebastianfehr.com
 * @since             1.0.0
 * @package           Sf_Modify_Wp_Query
 *
 * @wordpress-plugin
 * Plugin Name:       SF-Modify-WP-Query
 * Plugin URI:        www.sebastianfehr.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Sebastian Fehr
 * Author URI:        www.sebastianfehr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sf-modify-wp-query
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


$plugin_name = 'sf-modify-wp-query';
$plugin_text_domain = 'sf-modify-wp-query';
$plugin_version = '1.0.0';

/**
 * Define Constants
 */
define( 'PLUGIN_NAME', $plugin_name );

define( 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

define( 'PLUGIN_VERSION', $plugin_version );

define( 'PLUGIN_TEXT_DOMAIN', $plugin_text_domain );



/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SF_MODIFY_WP_QUERY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sf-modify-wp-query-activator.php
 */
function activate_sf_modify_wp_query() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sf-modify-wp-query-activator.php';
	Sf_Modify_Wp_Query_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sf-modify-wp-query-deactivator.php
 */
function deactivate_sf_modify_wp_query() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sf-modify-wp-query-deactivator.php';
	Sf_Modify_Wp_Query_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sf_modify_wp_query' );
register_deactivation_hook( __FILE__, 'deactivate_sf_modify_wp_query' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sf-modify-wp-query.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sf_modify_wp_query() {

	$plugin = new Sf_Modify_Wp_Query();
	$plugin->run();

}
run_sf_modify_wp_query();
