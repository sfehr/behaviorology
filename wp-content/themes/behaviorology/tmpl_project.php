<?php
/**
 * Template Name: Project List
 *  
 * Template Post Type:
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package behaviorology
 */

get_header( 'bhv' ); // custom header

?>
	<main id="primary" class="site-main">

		<?php

			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'tmpl_project' );

			endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
