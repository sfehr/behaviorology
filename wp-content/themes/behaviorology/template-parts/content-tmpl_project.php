<?php
/**
 * Template part for displaying projects singular and pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package behaviorology
 */

// GET CUSTOM FIELDS
$group_entries = bhv_get_media_group_entries(); // img size
$section_links = '<li class="menu-item"><a class="section-link" href="#content">' . esc_html( bhv_get_text_content_term() ) . '</a></li>';
// retrieve the titles
foreach( $group_entries as $entry ){
//	print '<li class="menu-item"><a class="section-link" href="#' . esc_attr( bhv_create_slug( $entry[ 'group_title' ] ) ) . '">' . $entry[ 'group_title' ] . '</a></li>';
	$section_links .= '<li class="menu-item"><a class="section-link" href="#' . esc_attr( bhv_create_slug( $entry[ 'group_title' ] ) ) . '">' . $entry[ 'group_title' ] . '</a></li>';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		the_title( '<h1 class="entry-title">', '</h1>' );
		?>
	</header><!-- .entry-header -->

	<?php // behaviorology_post_thumbnail(); ?>
	
	<section class="section section-navigation">
		<ul class="section-navigation-container">
			<?php
			
			print $section_links;
			
			?>
		</ul>
	</section>
	
	<section class="section section-content">
		<div id="content" class="entry-content">
			<?php
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'behaviorology' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			?>		
		</div><!-- .entry-content -->
	</section>
	
	<?php
	// retrieve the media
	foreach( $group_entries as $entry ){
		print '<section id="' . esc_attr( bhv_create_slug( $entry[ 'group_title' ] ) ) . '" class="section section-media" data-columns="' . esc_attr( $entry[ 'columns' ] ) . '">' . $entry[ 'media' ] . '</section>';
	}
	?>	
	
	<section class="section section-credits">
		<?php 
		bhv_get_list_header();
		bhv_get_list_entries();
		?>
	</section>
		
	<footer class="entry-footer">
		<?php behaviorology_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
