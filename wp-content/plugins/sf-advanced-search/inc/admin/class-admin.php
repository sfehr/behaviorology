<?php

namespace SF_Advanced_Search\Inc\Admin;
use SF_Advanced_Search as NS;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://sebastianfehr.com
 * @since      1.0.0
 *
 * @author    Sebastian Fehr
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_text_domain    The text domain of this plugin.
	 */
	private $plugin_text_domain;
	
	/**
	 * The transients for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $plugin_transients    The transients for this plugin.
	 */
	private $plugin_transients;	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 * @param       string $plugin_name        The name of this plugin.
	 * @param       string $version            The version of this plugin.
	 * @param       string $plugin_text_domain The text domain of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;
		$this->plugin_transients = json_decode( NS\PLUGIN_TRANSIENT, true );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sf-advanced-search-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/*
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sf-advanced-search-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	
	/**
	 * Callback for the admin menu
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$plugin_screen_hook_suffix = add_options_page(
			__( 'Advanced Search Settings', $this->plugin_text_domain ), // page title.
			__( 'Advanced Search Settings', $this->plugin_text_domain ), // menu title.
			'manage_options', // capability.
			$this->plugin_name, // menu_slug.
			array( $this, 'load_settings_page' ) // function to call
		);
	}
	
	/**
	 * Callback to load the admin menu page
	 *
	 * @since    1.0.0
	 */
	public function load_settings_page() {
		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		include_once( 'views/html-sf-advanced-search-admin-display.php' );
	}
	
	/**
	 * Callback to save the plugin options
	 *
	 * @since    1.0.0
	 */
	public function update_plugin_options() {
		register_setting(
			$this->plugin_name, // option group.
			$this->plugin_name, // option name.
			array( $this, 'validate_settings' ) // santize callback.
		);
		
	}
	
	/**
	 * Callback to validate settings for the option group
	 *
	 * @since    1.0.0
	 * @param array $input The setting.
	 */
	public function validate_settings( $input ) {

		$old_plugin_options = get_option( $this->plugin_name );

		// check that at least one post type is selected.
		if ( ! empty( $input ) ) {
			$valid_options = array();

			$args = array(
				'public' => true,
				'_builtin' => false,
			);
			
			// POST TYPES
			$post_types = get_post_types( $args, 'objects' );		
			
			foreach ( $post_types as $post_type ) {
				$the_post_type = $post_type->name; // post type
				$valid_options[ 'post_type' ][ $the_post_type ] = ( isset( $input[ 'post_type' ][ $the_post_type ] ) && ! empty( $input[ 'post_type' ][ $the_post_type ] ) ) ? 1 : 0;
			}
			// as builtin post types are excluded above, manually add post and page.
			$valid_options[ 'post_type' ][ 'post' ] = ( isset( $input[ 'post_type' ][ 'post' ] ) && ! empty( $input[ 'post_type' ][ 'post' ] ) ) ? 1 : 0;
			$valid_options[ 'post_type' ][ 'page' ] = ( isset( $input[ 'post_type' ][ 'page' ] ) && ! empty( $input[ 'post_type' ][ 'page' ] ) ) ? 1 : 0;
			
			// POST TYPE FOR TAXONOMY
			$valid_options[ 'post_type_for_taxonomy' ] = ( isset( $input[ 'post_type_for_taxonomy' ] ) && ! empty( $input[ 'post_type_for_taxonomy' ] ) ) ? $input[ 'post_type_for_taxonomy' ] : 0;
			

			// delete the transitent.
			$transient_name = $this->plugin_transients['autosuggest_transient'];
			if ( get_transient( $transient_name ) ) {

				// delete transient as settings are updated.
				delete_transient( $transient_name );
			}
			
			return $valid_options;

		} else {

			// Display and error as nothing was selected.
			 add_settings_error(
				 $this->plugin_name,
				 esc_attr( $this->plugin_name ),
				 __( 'At least one Post Type must be selected', $this->plugin_text_domain ),
				 'error'
			 );

			 // return the previous settings.
			 return $old_plugin_options;
		}
	}
	
	/**
	 * Workaround to show an admin notice on plugin activation
	 *
	 * @since 1.0.0
	 */
	public function activation_admin_notice() {

		$transient_name = $this->plugin_transients['admin_notice_transient'];

		// Show admin notice if transient is available.
		if ( get_transient( $transient_name ) ) {

			$message = __( 'Please select the post types to include in the search from the ', $this->plugin_text_domain );
			$plugin_settings_url = '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'plugin\'s settings here', $this->plugin_text_domain ) . '</a>';

			echo '<div class="updated notice-success is-dismissible">
					<p>' . $message . $plugin_settings_url . '</p>
				</div>';

			// Delete the transient to display notice only once.
			delete_transient( $transient_name );
		}
	}	

}
