<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.sebastianfehr.com
 * @since      1.0.0
 *
 * @package    Sf_Modify_Wp_Query
 * @subpackage Sf_Modify_Wp_Query/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sf_Modify_Wp_Query
 * @subpackage Sf_Modify_Wp_Query/admin
 * @author     Sebastian Fehr <sf@sebastianfehr.com>
 */
class Sf_Modify_Wp_Query_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since      1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      string    $plugin_text_domain The text domain of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;

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
		 * defined in Sf_Modify_Wp_Query_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sf_Modify_Wp_Query_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sf-modify-wp-query-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sf_Modify_Wp_Query_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sf_Modify_Wp_Query_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sf-modify-wp-query-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	
	/**
	 * Callback for the admin menu
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$plugin_screen_hook_suffix = add_options_page(
			__( 'Modify WP Query Settings', $this->plugin_text_domain ), // page title.
			__( 'Modify WP Query Settings', $this->plugin_text_domain ), // menu title.
			'manage_options', // capability.
			$this->plugin_name, // menu_slug.
			array( $this, 'load_settings_page' )
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

		include_once( 'partials/sf-modify-wp-query-admin-display.php' );
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
			$post_types = get_post_types( $args, 'objects' );

			foreach ( $post_types as $post_type ) {
				$the_post_type = $post_type->name;
				$valid_options[ $the_post_type ] = ( isset( $input[ $the_post_type ] ) && ! empty( $input[ $the_post_type ] ) ) ? 1 : 0;
			}

			// as builtin post types are excluded above, manually add post and page.
			$valid_options['post'] = ( isset( $input['post'] ) && ! empty( $input['post'] ) ) ? 1 : 0;
			$valid_options['page'] = ( isset( $input['page'] ) && ! empty( $input['page'] ) ) ? 1 : 0;

			return $valid_options;

		} else {

			// Display and error as nothing was selected.
			 add_settings_error(
				 $this->plugin_name,
				 esc_attr( $this->plugin_name ),
				 __( 'At least one Post Type must be selected', 'my-text-domain' ),
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
		$message = __( 'Please select the post types to include in the search from the ', $this->plugin_text_domain );
	}		

}
