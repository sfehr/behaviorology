<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://sebastianfehr.com
 * @since      1.0.0
 *
 * @author    Sebastian Fehr
 * @package    SF_Advanced_Search
 * @subpackage SF_Advanced_Search/inc/admin/views 
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<form method="post" name="<?php echo $this->plugin_name; ?>_search_options" action="options.php">
		<h4 class="nav-tab-wrapper"><?php echo esc_html( 'Select post types to include in the Advanced Search', $this->plugin_text_domain ); ?></h4>
		<br>		
	<?php
		
		// PLUGIN ADMIN PAGE
		$plugin_options = get_option( $this->plugin_name );
		// add the nonce, option_page, action and referer.
		settings_fields( $this->plugin_name );
		do_settings_sections( $this->plugin_name );

		
		// POST TYPES OPTION
		// manually add only posts and pages.
		$posts_array = array(
			'name' => 'post',
			'label' => 'Posts',
		);
		$pages_array = array(
			'name' => 'page',
			'label' => 'Pages',
		);

		$args = array(
			'public' => true,
			'_builtin' => false, //exclude attachment, revision etc.
		);

		// append posts and pages to the post types.
		$post_types = get_post_types( $args, 'objects' );
		$post_types['post'] = (object) $posts_array;
		$post_types['page'] = (object) $pages_array;

		foreach ( $post_types as $post_type ) {
			$the_post_type = $post_type->name;
			$the_post_type_label = $post_type->label;
			$option_checked_post_type = isset( $plugin_options[ 'post_type' ][ $the_post_type ] ) ? $plugin_options[ 'post_type' ][ $the_post_type ] : 0;
			?>
			<fieldset>
				<legend class="screen-reader-text"><span><?php echo esc_attr__( 'Setting for ', $this->plugin_text_domain ) . $the_post_type_label; ?></span></legend>
				<label for="<?php echo esc_attr( $this->plugin_name . '_post_type_' . $the_post_type ); ?>">
					<input type="checkbox" id="<?php echo esc_attr( $this->plugin_name . '-' . $the_post_type ); ?>" name="<?php echo $this->plugin_name . '[post_type][' . esc_attr( $the_post_type ) . ']'; ?>" value="<?php echo esc_attr( $the_post_type ); ?>" <?php checked( $option_checked_post_type, 1, true ); ?> />
					<span><?php echo esc_attr__( 'Include: ', $this->plugin_text_domain ) . $the_post_type_label; ?></span>
				</label>
			</fieldset>
		<?php
		} // end foreach
		
		
		// POST TYPE TAXONOMY OPTIONS
		?>
		<h4 class="nav-tab-wrapper"><?php echo esc_html( 'Select post type of which its taxonomy should be included in the Advanced Search', $this->plugin_text_domain ); ?></h4>
		<br>
		<?php

		foreach ( $post_types as $post_type ) {
			$the_post_type = $post_type->name;
			$the_post_type_label = $post_type->label;
			$option_selected_post_type_for_taxonomy = isset( $plugin_options[ 'post_type_for_taxonomy' ] ) ? $plugin_options[ 'post_type_for_taxonomy' ] : 0;			
			?>
			<fieldset>
				<legend class="screen-reader-text"><span><?php echo esc_attr__( 'Setting for Post Type Taxonomy', $this->plugin_text_domain ) ?></span></legend>		
				<label for="<?php echo esc_attr( $this->plugin_name . '_' . $the_post_type ); ?>">
					<input type="radio" id="<?php echo esc_attr( $this->plugin_name . '-' . $the_post_type ); ?>" name="<?php echo $this->plugin_name . '[post_type_for_taxonomy]'; ?>" value="<?php echo esc_attr( $the_post_type ); ?>" <?php checked( $the_post_type, $option_selected_post_type_for_taxonomy, true ); ?> />
					<span><?php echo esc_attr__( 'Include: ', $this->plugin_text_domain ) . $the_post_type_label; ?></span>
				</label>
			</fieldset>				
		<?php
		} // end foreach
		?>	
		<br>
		<fieldset>
			<legend class="screen-reader-text"><span><?php echo esc_attr__( 'Setting for Post Type Taxonomy', $this->plugin_text_domain ) ?></span></legend>		
			<label for="<?php echo esc_attr( $this->plugin_name . '_' . 'no_taxonomy' ); ?>">
				<input type="radio" id="<?php echo esc_attr( $this->plugin_name . '-' . 'no_taxonomy' ); ?>" name="<?php echo $this->plugin_name . '[post_type_for_taxonomy]'; ?>" value="0" <?php checked( '0', $option_selected_post_type_for_taxonomy, true ); ?> />
				<span><?php echo esc_attr__( 'No Taxonomy Options', $this->plugin_text_domain ); ?></span>
			</label>
		</fieldset>
		<?php 
		
		
		// SEARCH RESULT OPTIONS
		$template_search_result = isset( $plugin_options[ 'template' ] ) ? $plugin_options[ 'template' ] : '';
		?>
		<h4 class="nav-tab-wrapper"><?php echo esc_html( 'Include custom template to display the search results', $this->plugin_text_domain ); ?></h4>
		<br>		
		<fieldset>
			<legend class="screen-reader-text"><span><?php echo esc_attr__( 'Setting a custom template for formatting the search results', $this->plugin_text_domain ) ?></span></legend>		
			<label for="<?php echo esc_attr( $this->plugin_name . '_' . 'template_search_result' ); ?>">
				<input type="text" id="<?php echo esc_attr( $this->plugin_name . '-' . 'template_search_result' ); ?>" name="<?php echo $this->plugin_name . '[template_search_result]'; ?>" value="<?php echo esc_attr( $template_search_result ); ?>" />
				<span><?php echo esc_attr__( 'Template to format the search results. The file name stored in the template-parts folder.', $this->plugin_text_domain ); ?></span>
			</label>
		</fieldset>
		
		<h4 class="nav-tab-wrapper"><?php echo esc_html( 'Notes', $this->plugin_text_domain ); ?></h4>
		<p><?php echo esc_html( 'For displaying the search results in a custom container use CSS class ', $this->plugin_text_domain ); ?><input type="text" name="css-class" value=".sf-search-result-container" readonly><p>
		<?php 
		
		
		// SAVE/UPDATE OPTIONS
		submit_button( 'Save all changes', 'primary', 'submit', true ); 
		?>

	</form>

</div>