<?php
/**
 * Template part for displaying attached posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package behaviorology
 */


$data = array(
	'term_id'   => '',
	'name' => get_the_title(),
	'post_id' => url_to_postid( get_permalink() )
);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'list-entry' ); ?>>
	<?php
	
	// CLASS INTRODUCTION
	if( 'class' == get_post_type( get_the_ID() ) ){
		echo '<div class="class-introdcution">' .  __( 'Introduction', bhv_get_theme_text_domain() ) . '</div>';
	}

	// PROJECT LIST ENTRIES
	bhv_get_list_entries();
	
		
	if( 'student_project' == get_post_type( get_the_ID() ) ){
	?>
		<div class="list-image"><a class="list-link" href="<?php echo esc_url( get_permalink() ); ?>" data-target="<?php echo esc_attr( json_encode( $data ) ); ?>" ><?php the_post_thumbnail(); ?></a></div>
		<div class="list-content"><a class="list-link" href="<?php echo esc_url( get_permalink() ); ?>" data-target="<?php echo esc_attr( json_encode( $data ) ); ?>" ><?php echo wp_strip_all_tags( wp_trim_words( get_the_content(), 80, '...' ) ); ?></a></div>		
	<?php	
	}else{
	?>
		<div class="list-content"><?php echo get_the_content(); ?></a></div>
	<?php	
	}
	?>

	<footer class="entry-footer">
		<?php behaviorology_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->