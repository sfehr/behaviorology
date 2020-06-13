<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// GET VALUES
$transient_name = $this->plugin_transients['autosuggest_transient']; // plugin setting
$plugin_options = get_option( $this->plugin_name ); // plugin setting
$get_form_input = filter_input( INPUT_POST, $this->plugin_name , FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ); // form input



// SEARCH TERM / POST TYPE PLUGIN SETTINGS
// retrieve the post types to search from the plugin settings.
 $post_types = array_keys( $plugin_options[ 'post_type' ], 1, true );


// check if cached posts are available.
$cached_posts = get_transient( $transient_name );
if ( false === $cached_posts ) {

	// retrieve posts for the specified post types by running get_posts and cache the posts as well.
	$cached_posts = $this->cache_posts_in_post_types();
}

// extract the cached post ids from the transient into an array.
$cached_post_ids = array_column( $cached_posts, 'id' );

// run a new query to against the search key and the cached post ids for the seleted post types.
$args = array(
	'post_type'           => $post_types,
	'posts_per_page'      => -1,
	'no_found_rows'       => true, // as we don't need pagination.
	'post__in'            => $cached_post_ids, // use post ids that were cached in the query earlier.
	'ignore_sticky_posts' => true,
	's'                   => $search_term,  // the keyword/phrase to search.
	'sentence'            => true, // perform a phrase search.
);



// TAX QUERY OPTIONS

$user_selected_taxonomies = isset( $get_form_input['sf_taxonomies'] ) ? wp_unslash( $get_form_input['sf_taxonomies'] ) : array();
// create a dynamic tax_query if the user selected taxonomy terms in the search filters.
$tax_query = array();

// array to hold the selected taxonomies to create a dynamic filter buttons.
$user_tax_filter = array();

foreach ( $user_selected_taxonomies as $taxonomy_slug => $taxonomy_terms ) {

	if ( ! empty( $user_selected_taxonomies[ $taxonomy_slug ] ) ) {

		// tax_query takes an array of arrays.
		if ( ! array_key_exists( 'relation', $tax_query ) && count( $user_selected_taxonomies ) > 1 ) {
			/*
			 * Add 'Relation' only for multiple inner taxonomy arrays.
			 * This is Relation between the Taxonomies. (Not terms)
			 */
			$tax_query['relation'] = 'OR'; // 'AND' for a strict search.
			// With 'OR' the posts can contain either of the specified taxonomies.
		}

		/*
		 * Dynamically create the array of tax query arguments
		 * based on the custom taxonmies that were selected.
		 */
		array_push( $tax_query,
			array(
				'taxonomy' => $taxonomy_slug, // Taxonomy.
				'field' => 'slug', // Select by 'id' or 'slug'
				'terms' => $taxonomy_terms, // Taxonomy term(s).
				'include_children' => true, // Whether or not to include children for hierarchical taxonomies.
				'operator' => 'IN', // Relation between Terms. Possible values are 'IN', 'NOT IN', 'AND'.
			)
		);

		// populate the terms array for creating the filter buttons later.
		$user_tax_filter[ $taxonomy_slug ] = $taxonomy_terms;
	}
}


// CHECK SEARCH OPTIONS

// whether to include the tax_query in the wp_query args.
if ( ! empty( $tax_query ) ) {
	$args['tax_query'] = $tax_query;
}

// whether to include the meta_query in the wp_query args.
/*
if ( ! empty( $meta_query ) ) {
	$args['meta_query'] = $meta_query;
}
*/

// SEARCH QUERY 

$search_query = new \WP_Query( $args );
?>

<!-- Search Results -->
<div class="sf-search-results">
	<?php if ( $search_query->have_posts() ) : ?>
		<ul class="flex-grid-container">
			<!-- Start the Loop. -->
			<?php
			while ( $search_query->have_posts() ) :
					$search_query->the_post();
			?>

					<li class="flex-grid-item">

						<!-- the thumbnail -->
						<p>
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
							<?php endif; ?>
						</p>
						<!-- title -->
						<p class="card-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</p>
						<!-- excerpt -->
						<p class="card-excerpt">
							<?php echo wp_trim_words( get_the_content(), 30, ' ...' ); ?>
						</p>

					</li> <!-- flex-grid-item -->
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
		</ul> <!-- flex-grid-container -->
		<?php else : ?>
			<p>
				<?php echo __( 'Nothing Found ...', $this->plugin_text_domain ); ?>
			</p>
		<?php endif; ?>
</div> <!-- sf-search-results -->


