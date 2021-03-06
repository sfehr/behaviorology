<?php

namespace SF_Advanced_Search\Inc\Core;
use SF_Advanced_Search as NS;

/**
 * Fired during plugin deactivation
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link       http://sebastianfehr.com
 * @since      1.0.0
 *
 * @author     Sebastian Fehr
 **/
class Deactivator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$plugin_name = NS\PLUGIN_NAME;
		$plugin_options_exist = get_option( $plugin_name );
		if ( $plugin_options_exist ) {
			delete_option( $plugin_name );
		}

		$transient_search = json_decode( NS\PLUGIN_TRANSIENT, true );
		$transient_name = $transient_search['autosuggest_transient'];
		if ( $transient_name ) {
			delete_transient( $transient_name );
		}		

	}

}
