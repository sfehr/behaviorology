<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://sebastianfehr.com
 * @since             1.0.0
 * @package           SF_Advanced_Search
 *
 * @wordpress-plugin
 * Plugin Name:       SF Advanced Search
 * Plugin URI:        http://sebastianfehr.com/sf-advanced-search-uri/
 * Description:       AJAX powered search function including auto-suggest and working with transients. Shortcode is used for implementation.
 * Version:           1.0.0
 * Author:            Sebastian Fehr
 * Author URI:        http://sebastianfehr.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sf-advanced-search
 * Domain Path:       /languages
 */

namespace SF_Advanced_Search;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$plugin_name = 'sf-advanced-search';
$plugin_text_domain = 'sf-advanced-search';
$plugin_version = '1.0.0';

/**
 * Define Constants
 */
define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );

define( NS . 'PLUGIN_NAME', $plugin_name );

define( NS . 'PLUGIN_VERSION', $plugin_version );

define( NS . 'PLUGIN_NAME_DIR', plugin_dir_path( __FILE__ ) );

define( NS . 'PLUGIN_NAME_URL', plugin_dir_url( __FILE__ ) );

define( NS . 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

define( NS . 'PLUGIN_TEXT_DOMAIN', $plugin_text_domain );

$plugin_transient = array(
	'autosuggest_transient' => 'nds_autosuggest_' . $plugin_version, // appending version in cases when the plugin updates and we want the newer transient version.
	'autosuggest_transient_expiration' => ( 6 * HOUR_IN_SECONDS ),
	'admin_notice_transient' => 'nds-advanced-search-activation-notice' . $plugin_version,
	'admin_notice_transient_expiration' => 5,
);

// no need to json_encode in PHP 7.0.
define( NS . 'PLUGIN_TRANSIENT', json_encode( $plugin_transient ) );


/**
 * Autoload Classes
 */

require_once( PLUGIN_NAME_DIR . 'inc/libraries/autoloader.php' );

/**
 * Register Activation and Deactivation Hooks
 * This action is documented in inc/core/class-activator.php
 */

register_activation_hook( __FILE__, array( NS . 'Inc\Core\Activator', 'activate' ) );

/**
 * The code that runs during plugin deactivation.
 * This action is documented inc/core/class-deactivator.php
 */

register_deactivation_hook( __FILE__, array( NS . 'Inc\Core\Deactivator', 'deactivate' ) );


/**
 * Plugin Singleton Container
 *
 * Maintains a single copy of the plugin app object
 *
 * @since    1.0.0
 */
class SF_Advanced_Search {

	/**
	 * The instance of the plugin.
	 *
	 * @since    1.0.0
	 * @var      Init $init Instance of the plugin.
	 */
	private static $init;
	/**
	 * Loads the plugin
	 *
	 * @access    public
	 */
	public static function init() {

		if ( null === self::$init ) {
			self::$init = new Inc\Core\Init();
			self::$init->run();
		}

		return self::$init;
	}

}

/**
 * Begins execution of the plugin
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * Also returns copy of the app object so 3rd party developers
 * can interact with the plugin's hooks contained within.
 **/
function SF_Advanced_Search_init() {
		return SF_Advanced_Search::init();
}

$min_php = '5.6.0';

// Check the minimum required PHP version and run the plugin.
if ( version_compare( PHP_VERSION, $min_php, '>=' ) ) {
		SF_Advanced_Search_init();
}
