<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category behaviorology theme
 * @package  behaviorology
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/CMB2/CMB2
 */


/** BHV CMB2 Functions Inventory
 *  
 * bhv_attached_posts_field_metabox()				 | Metabox posts can be attached to a post (creating post type relations)
 * bhv_hero_image_option_box()						 | Hero image that is featured on the start page
 * bhv_content_link_term()							 | Text field for setting the navigation-link-term for the main content
 * bhv_register_media_box()							 | Image or movies type repeatable group
 *  
 */



/* ATTACHED POSTS
*
* [custom_attached_posts] student projects
*
*/
add_action( 'cmb2_admin_init', 'bhv_attached_posts_field_metabox' );

function bhv_attached_posts_field_metabox() {
	
	$prefix = 'bhv_';
	
	// METABOX
	$bhv_attached_posts = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Student Projects', bhv_get_theme_text_domain() ),
		'object_types'  => array( 'class' ), // Post type
	) );
	
	// CUSTOM ATTACHED POST FIELD
	$bhv_attached_posts->add_field( array(
		'name'    => __( 'Student Projects assigned to Class', bhv_get_theme_text_domain() ),
		'desc'    => __( 'Drag posts from the left column to the right column to attach them to this page.<br />You may rearrange the order of the posts in the right column by dragging and dropping.', bhv_get_theme_text_domain() ),
		'id'      => $prefix . 'attached_post',
		'type'    => 'custom_attached_posts',
		'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
		'options' => array(
			'show_thumbnails' => true, // Show thumbnails on the left
			'filter_boxes'    => true, // Show a text box for filtering the results
			'query_args'      => array(
				'posts_per_page' => 50,
				'post_type'      => 'student_project',
				'post_status'	=> 'publish',
			), // override the get_posts args
		),
	) );
}



/* HERO IMAGE
*
* [radio] 
*
*/
add_action( 'cmb2_admin_init', 'bhv_hero_image_option_box' );

function bhv_hero_image_option_box() {
	
	$prefix = 'bhv_hero_image_';
	
	// METABOX
	$bhv_hero_image = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Hero Image', bhv_get_theme_text_domain() ),
		'object_types'  => array( 'student_project' ), // Post type
	) );	
	
	// CHECKBOX FIELD
	$bhv_hero_image->add_field( array(
		'name' => esc_html__( 'Hero Image', bhv_get_theme_text_domain() ),
		'desc' => esc_html__( 'Displayes the featured image on the start page', bhv_get_theme_text_domain() ),
		'id'   => $prefix . 'check',
		'type' => 'checkbox',
	) );	
}



/* HERO IMAGE
*
* [radio] 
*
*/
add_action( 'cmb2_admin_init', 'bhv_content_link_term' );

function bhv_content_link_term() {
	
	$prefix = 'bhv_content_link_';
	
	// METABOX
	$bhv_content_term = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Text Content', bhv_get_theme_text_domain() ),
		'object_types'  => array( 'student_project' ), // Post type
	) );	
	
	// CHECKBOX FIELD
	$bhv_content_term->add_field( array(
		'name'    => esc_html__( 'Content Term', bhv_get_theme_text_domain() ),
		'desc'    => esc_html__( 'A term for the text content, which will be displayed in the project navigation.', bhv_get_theme_text_domain() ),
		'default' => esc_html__( 'Hypothesis', bhv_get_theme_text_domain() ),
		'id'      => $prefix . 'term',
		'type'    => 'text_medium'
	) );
}




/* MEDIA BOX
*
* [group] 
* [select] 
* [file_list] 
* [oembed] 
*
*/
add_action( 'cmb2_admin_init', 'bhv_register_media_box' );

function bhv_register_media_box() {
	
	$prefix = 'bhv_media_';

	// META BOX
	$cmb_media_group = new_cmb2_box( array(
		'id'           => $prefix . 'mediabox',
		'title'        => esc_html__( 'Media (Image or Movie)', bhv_get_theme_text_domain() ),
		'object_types' => array( 'student_project' ),
	) );	
	
	// GROUP FIELD
	$group_field_id = $cmb_media_group->add_field( array(
		'id'          => $prefix . 'repeat_group',
		'type'        => 'group',
		'title'       => esc_html__( 'Media (Image or Movie)', bhv_get_theme_text_domain() ),		
		'description' => esc_html__( 'Generates reusable form entries', bhv_get_theme_text_domain() ),
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'       => __( 'Media Entry {#}', bhv_get_theme_text_domain() ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'        => __( 'Add Another Entry', bhv_get_theme_text_domain() ),
			'remove_button'     => __( 'Remove Entry', bhv_get_theme_text_domain() ),
			'sortable'          => true,
			// 'closed'         => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );
	
	// TEXT_MEDIUM FIELD
	$cmb_media_group->add_group_field( $group_field_id, array(
		'name'    => __( 'Section Title', bhv_get_theme_text_domain() ),
		'desc'    => __( 'The title will be displayed in the project navigation on the frontend', bhv_get_theme_text_domain() ),
		'id'      => $prefix . 'title',
		'type'    => 'text_medium'
	) );	

	// SELECT FIELD (Media Type)
	$cmb_media_group->add_group_field( $group_field_id, array(
		'name'    => __( 'Media Type', bhv_get_theme_text_domain() ),
		'id'      => $prefix . 'select_media',
		'type'    => 'select',
		'options' => array(
			'img' => __( 'Image', bhv_get_theme_text_domain() ),
			'mov' => __( 'Movie', bhv_get_theme_text_domain() ),
		),
		'default' => 'img',
	) );
	
	// FILE LIST FIELD
	$cmb_media_group->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Image', bhv_get_theme_text_domain() ),
		'id'   => 'image',
		'type' => 'file_list',
		'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
		'query_args' => array( 'type' => 'image' ), // Only images attachment
	) );	
	
	// OEMBED FIELD
	$cmb_media_group->add_group_field( $group_field_id, array(
		'name' => __( 'Movie', bhv_get_theme_text_domain() ),
		'desc' => __( 'Enter an Movie-URL (e.g. from Youtube or Vimeo)', bhv_get_theme_text_domain() ),
		'id'   => 'movie',
		'type' => 'oembed',
	) );
	
	// SELECT FIELD (Amount of Columns)
	$cmb_media_group->add_group_field( $group_field_id, array(
		'name'             => __( 'Layout Columns', bhv_get_theme_text_domain() ),
		'desc'             => __( 'Choose the amount of columns to display the content', bhv_get_theme_text_domain() ),
		'id'      		   => $prefix . 'select_columns',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			1	 	   	   => '1' . __( ' Colum', bhv_get_theme_text_domain() ),
			2	 	   	   => '2' . __( ' Columns', bhv_get_theme_text_domain() ),
			3	 	   	   => '3' . __( ' Columns', bhv_get_theme_text_domain() ),
			4	 	   	   => '4' . __( ' Columns', bhv_get_theme_text_domain() ),
			6	 	   	   => '6' . __( ' Columns', bhv_get_theme_text_domain() ),
		),
		'default'          => 2,		
	) );	
}

