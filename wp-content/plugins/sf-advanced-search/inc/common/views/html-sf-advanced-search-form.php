<?php
/*
 * Markup for the custom search form goes here.
 *
 * Note: Form input is stored inside an array with the plugin's name
 * e.g. $_POST['plugin_name']
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


// TAXONOMY OPTIONS


// retrieve the post types taxonomy options to search from the plugin settings.
$plugin_options = get_option( $this->plugin_name );
$post_type_for_taxonomy = $plugin_options[ 'post_type_for_taxonomy' ];


// get taxonomies associated with the specified post type.
$custom_taxonomies = get_object_taxonomies( $post_type_for_taxonomy, 'objects' ); // PLUGINIZED: get options form the plugin setting
// $custom_taxonomies = get_object_taxonomies( 'student_project', 'objects' ); // PLUGINIZE
$custom_tax_checkbox_list = '';
foreach ( $custom_taxonomies as $custom_taxonomy ) {
	// https://developer.wordpress.org/reference/classes/wp_term_query/__construct.
	$args = array(
		'hide_empty' => false,
		'parent' => 0, // only top level terms.
		'taxonomy' => $custom_taxonomy->name,
	);

	// get terms for the specified taxonomies.
	$custom_taxonomy_terms = get_terms( $args );
	
	// create a checkbox list for the the terms.
	$custom_tax_checkbox_list .= '<fieldset class="checkboxgroup">';
	$custom_tax_checkbox_list .= '<legend>' . $custom_taxonomy->label . ' </legend>';

	foreach ( $custom_taxonomy_terms as $taxonomy_term ) {

		$custom_tax_checkbox_list .= '<p>
			<label for="' . $taxonomy_term->slug . '">
				<input type="checkbox" id="' . $taxonomy_term->slug . '" name="' . $this->plugin_name . '[sf_taxonomies][' . $taxonomy_term->taxonomy . '][]" value="' . $taxonomy_term->slug . '"> ' . $taxonomy_term->name .
			'</label></p>';

	}
	$custom_tax_checkbox_list .= '</fieldset>';
}



// FORM SECURITY
// prepare nonce field for form security
$sf_advanced_search_nonce = wp_create_nonce( 'sf_advanced_search_form_nonce' );

?>

<div class="sf-advanced-search-form-container">
	<form id="sf-advanced-search-form" role="search" method="POST" class="search-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<button class="sf-search-button" type="submit" class="search-submit"><i class="sf-search" aria-hidden="true"><?php echo esc_html_x( 'Search', 'submit button', $this->plugin_text_domain ); ?></i></button>
		<div class="sf-input-container">
			<label for="sf-search-box">
				<span class="screen-reader-text"><?php echo esc_attr_x( 'Search for:', 'label', $this->plugin_text_domain ); ?></span>
				<input class="sf-search-input" id="sf-search-box" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', $this->plugin_text_domain ); ?>" name="<?php echo esc_attr( $this->plugin_name ); ?>[search_key]" />
			</label>
			<div class="sf-search-options-container">
				<div id="sf-search-options" class="sf-search-options">
					<div class="sf-search-options-left">
						<?php echo $custom_tax_checkbox_list; ?>
					</div> <!-- sf-search-options-left -->
				</div> <!-- sf-search-options -->
			</div> <!-- sf-search-options-container -->			
			<input type="hidden" name="action" value="sf_advanced_search_form_response">
			<input type="hidden" name="sf_advanced_search_nonce" value="<?php echo $sf_advanced_search_nonce ?>" />			
		</div> <!-- sf-input-container -->
	</form> <!-- sf-advanced-search-form -->
	<div id="sf-form-response-container">
	</div> <!-- sf-form-response-container -->
</div> <!-- sf-advanced-search-form-container -->