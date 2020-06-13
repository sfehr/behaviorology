<?php
/**
 * Template part for displaying attached posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package behaviorology
 */

// Attached Posts
if( ! is_home() ){
	// get attached ids
	$attached_ids = get_post_meta( get_the_ID(), 'bhv_attached_post', true );

	if( ! empty( $attached_ids ) ){
			
		// class
		$teacher = bhv_get_terms( get_the_ID(), 'teacher', '–' );
		
		foreach ( $attached_ids as $id ) {
					
			// sutent projects, get attached post
			$project = get_post( $id, 'OBJECT' );
			$project_title = apply_filters( 'the_title', $project->post_title );
			$project_content = apply_filters( 'the_content', $project->post_content );
			$project_thumbnail = get_the_post_thumbnail( $id, 'post-thumbnail' );
			$project_type = '';
			$project_link = get_permalink( $id );
			$project_students = bhv_get_terms( $id, 'student', '–' );
			$project_type = bhv_get_terms( $id, 'student_project_type', '–' );		
		
		?>
		<article id="post-<?php echo $id ?>" <?php post_class(); ?>>
			<div id="list-container">
				<div class="list-header">List Header</div>
				<div class="list-entry">
					<div class="list-project"><?php echo $project_title; ?></div>
					<div class="list-students"><?php echo $project_students ?></div>
					<div class="list-teacher"><?php echo $teacher ?></div>
					<?php the_title( '<div class="list-class">', '</div>' ); ?>
					<div class="list-type"><?php echo $project_type ?></div>
					<?php the_date( 'Y', '<div class="list-date">', '</div>' ); ?>
					<div class="list-image"><a class="list-link" href="<?php echo esc_url( $project_link ); ?>" ><?php echo $project_thumbnail ?></a></div>
					<div class="list-content"><a class="list-link" href="<?php echo esc_url( $project_link ); ?>" ><?php echo $project_content ?></a></div>
				</div><!-- .list-entry -->
			</div><!-- #list-container -->
			<footer class="entry-footer">
				<?php behaviorology_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		</article><!-- #post-<?php echo $id ?> -->
<?php
		} //end foreach
	} //end if			
} //end if			
?>