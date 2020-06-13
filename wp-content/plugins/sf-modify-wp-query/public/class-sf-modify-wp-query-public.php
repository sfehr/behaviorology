<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.sebastianfehr.com
 * @since      1.0.0
 *
 * @package    Sf_Modify_Wp_Query
 * @subpackage Sf_Modify_Wp_Query/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sf_Modify_Wp_Query
 * @subpackage Sf_Modify_Wp_Query/public
 * @author     Sebastian Fehr <sf@sebastianfehr.com>
 */
class Sf_Modify_Wp_Query_Public {

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
	 * The text domain of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The text domain of the plugin.
	 */
	protected $plugin_text_domain;	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sf-modify-wp-query-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sf-modify-wp-query-public.js', array( 'jquery' ), $this->version, false );

	}
	
	
	/**
	 * WP Query modification handler
	 *
	 * Callback for the "pre_get_posts" in "class-sf-modify-wp-query.php"
	 *
	 * @since    1.0.0
	 */	
	public function modify_wp_query_handler( $query ){

		// retrieve the selected post types from the plugin settings to include in the custom search.
		$plugin_options = get_option( $this->plugin_name );
		$post_types = array_keys( $plugin_options, 1, true );

		// post order
		$post_order = array(
			'post_type' => 'ASC',
			'date' => 'DESC',
		);
		
		// query args
		$args = array(
			'post_type'           => $post_types,
			'post_status'         => 'publish',
			'posts_per_page'      => -1,
			'no_found_rows'       => true, // true by default.
			'suppress_filters'    => false, // true by default.
			'ignore_sticky_posts' => true, // true by default.
			'orderby'             => $post_order,	
		);

		// check if it's on frontend
		if ( !is_admin() && $query->is_main_query() ) {
			
			// check where to apply the query options // should be added to the plugin admin page
			if ( $query->is_home() ) {
			
				// apply the options to the WP query
				$query->query_vars = $args;				
				
			}
		}		
	}

}
