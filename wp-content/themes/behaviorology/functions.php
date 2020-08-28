<?php
/**
 * behaviorology functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package behaviorology
 * 
 * 
 * 
 * bhv_get_theme_text_domain()			 | Textdomain stored in a variable
 * 										 | Load CMB2 functions
 * bhv_choose_template() 				 | Chose a custom template
 * bhv_get_terms() 						 | get terms without link
 * bhv_add_title_as_category() 			 | ADMIN: automatically saves a post tytle as taxonomy term
 * bhv_update_term 					 	 | ADMIN: changes a assigned term when according post title is edited
 * bhv_set_terms_to_attached_posts()	 | ADMIN: set terms to attached posts (studen projects) when current post (class) is saved (admin-side)
 * sf_ajax_loader_handler() 			 | Ajax handler for loading posts
 * bhv_loop_start()						 | A set of functions to fire before the loop
 * bhv_loop_end() 						 | A set of functions to fire after the loop
 * bhv_get_class_list()					 | List all class (taxonomy)
 * bhv_get_list_header()				 | Markup for list header
 * bhv_get_list_entries()				 | Markup and values for list entries
 * bhv_custom_head()					 | Add meta tags to the head
 * bhv_get_media_group_entries() 		 | Get Custom Field Values: Media Group
 * bhv_get_text_content_term() 		     | Get Custom Field Values: Text Content Term 
 * bhv_create_slug()					 | Creates an url friendly string
 * bhv_filter_menu_attributes()			 | Adds a data-slug attribute to the naviagtion links
 * bhv_gutenberg_blocks()				 | Limiting Gutenbergs Block elements
 * bhv_custom_post_order()				 | Order Posts by Post Type
 * bhv_modify_wp_query()				 | Modifying the initial WP query with pre_get_posts hook --> Pluginize ?
 * bhv_get_quarter()					 | Determines the quarter of the academic year by month number 
 *  
 * 
 * 
 * 
 * 
 * 
 *  
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'behaviorology_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function behaviorology_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on behaviorology, use a find and replace
		 * to change 'behaviorology' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'behaviorology', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'behaviorology' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'behaviorology_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'behaviorology_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function behaviorology_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'behaviorology_content_width', 640 );
}
add_action( 'after_setup_theme', 'behaviorology_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function behaviorology_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'behaviorology' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'behaviorology' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'behaviorology_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function behaviorology_scripts() {
	wp_enqueue_style( 'behaviorology-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'behaviorology-style', 'rtl', 'replace' );

	wp_enqueue_script( 'behaviorology-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'behaviorology-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	wp_enqueue_script( 'behaviorology-ui-interaction', get_template_directory_uri() . '/js/bhv-ui-interaction.js',  array( 'jquery' ), _S_VERSION, true );
	
	wp_enqueue_script( 'tinysort-js', get_template_directory_uri() . '/js/tinysort.min.js', array( 'jquery' ), _S_VERSION, true );
	
	// Ajax Loader Scripts
	$sfAjaxLoaderParams = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'contentContainer' => '#content-container', // html main tag / #main-content id
		'nonce' => wp_create_nonce( 'sf_ajax_loader_nonce' )
	);
	wp_enqueue_script( 'behaviorology-ajax-loader', get_template_directory_uri() . '/js/bhv-ajax-loader.js',  array( 'jquery' ), _S_VERSION, true );
	wp_localize_script( 'behaviorology-ajax-loader', 'sf_ajax_loader_params', $sfAjaxLoaderParams );
	
}
add_action( 'wp_enqueue_scripts', 'behaviorology_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



/** SF:
 * Retrieve the text domain of the theme.
 *
 * @since     1.0.0
 * @return    string    The text domain of the plugin.
 */
function bhv_get_theme_text_domain() {
	$textdomain = 'behaviorology';
	return $textdomain;
}



/** SF:
 * Load CMB2 functions
 */
require_once( get_template_directory() . '/inc/bhv-cmb2-functions.php');



/** SF:
 * Chose a custom template
 */
function bhv_choose_template( $template ) {
	
	if ( is_admin() ) {
		return $template;
	}
	
	// HOME
	if ( is_home() ) {
		$new_template = locate_template( array( 'tmpl_project_list.php' ) );
		if ( !empty( $new_template ) ) {
			return $new_template;
		}
	}
	
	// SINGLE // fallback for when a project is accessed directly
	if ( is_single() ) {
		$new_template = locate_template( array( 'tmpl_project.php' ) );
		if ( !empty( $new_template ) ) {
			return $new_template;
		}
	}
	
	// PAGE // fallback for when a project is accessed directly
	if ( is_page() ) {
		$new_template = locate_template( array( 'tmpl_page.php' ) );
		if ( !empty( $new_template ) ) {
			return $new_template;
		}
	}	

	return $template;
}
add_filter( 'template_include', 'bhv_choose_template', 99 );



/** SF:
 * get terms without link
 */
function bhv_get_terms( $post_id, $taxonomy, $placeholder ) {
	
	$term_list = '';
	$terms = get_the_terms( $post_id, $taxonomy );
	if( ! empty( $terms ) ){
		foreach( $terms as $term ){
			$term_list .= '<span class="term-item">' . $term->name . '</span>'; 
		}				
	}
	else{
		$term_list = $placeholder;
	}
	return $term_list;
}



/** SF:
 * ADMIN: automatically saves a post tytle as taxonomy term
 */
add_action( 'save_post', 'bhv_add_title_as_category' );
function bhv_add_title_as_category( $postid ) {
	
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if( get_post_status( $postid ) == 'auto-draft' ) return; // don't create or update terms for system generated posts
		
	$post = get_post( $postid );
	
	if( $post->post_type == 'class' ) { // target a custom post type
		$term = get_term_by( 'slug', $post->post_name, 'class_category' );
	  
		if ( empty( $term ) ) {
			$add = wp_insert_term( $post->post_title, 'class_category', array( 'slug' => $post->post_name ) );
			
			if ( is_array( $add ) && isset( $add[ 'term_id' ]) ) {
				wp_set_object_terms( $postid, $add[ 'term_id' ], 'class_category', true );
			}
		}
	}
}



/** SF:
 * ADMIN: changes a assigned term when according post title is edited
 */
add_action( 'post_updated', 'bhv_update_term', 10, 3 ); 
function bhv_update_term( $postid, $post_after, $post_before ) {
	
	if ( 'class' != get_post_type( $postid ) ) {
		return; // exit if different post type
	}	
	
	$post_title_old = apply_filters( 'the_title', $post_before->post_title );
	$post_title_new = apply_filters( 'the_title', $post_after->post_title );
	
	// check if the post title has been updated
	if( $post_title_old == $post_title_new ){
		return; // exit if no changes
	}
	
	// check if a term is assigned already create new term on false
	if( has_term( $post_title_old, 'class_category', $post_before ) ){
		$term = get_term_by( 'name', $post_title_old, 'class_category' ); // get existing term
		wp_update_term( $term->term_id, 'class_category', array( 'name' => $post_title_new ) ); // update existing term
	}
}



/** SF:
 * ADMIN: set terms to attached posts (studen projects) when current post (class) is saved (admin-side)
 */
add_action( 'added_post_meta', 'bhv_set_terms_to_attached_posts', 10, 4 );
add_action( 'updated_post_meta', 'bhv_set_terms_to_attached_posts', 10, 4 );
function bhv_set_terms_to_attached_posts( $meta_id, $object_id, $meta_key, $meta_value ) {
		
	if ( 'class' != get_post_type( get_the_ID() ) ) {
		return; // exit if different post type
	}
	
	$attached_post_ids = get_post_meta( get_the_ID(), 'bhv_attached_post', true );

	if( ! empty( $attached_post_ids ) ){
		
		// collect the taxonomies of class to retrieve
		$taxonomies = array( 'teacher', 'class_category' );

		// GET TERMS of current post (class)
		foreach( $taxonomies as $key => $value ){
				
			$term_obj = get_the_terms( $object_id, $value ); //$object_id is post_id
			foreach( $term_obj as $key){
				$taxonomies[ $value ][] = $key->term_id;
			}			
		} // end foreach
		
		
		// SET TERMS to attache post object
		foreach ( $attached_post_ids as $attached_post_id ) { // post object
			
			foreach( $taxonomies as $key => $value ){ // taxonomy level
				
				$term_ids = $taxonomies[ $value ]; // passing term ids as an array
				wp_set_object_terms( $attached_post_id, $term_ids, $value ); // Will replace all existing related terms in this taxonomy. Passing an empty value will remove all related terms.

			} // end foreach taxonomy level
		} // end foreach post object		
	}
}



/** SF:
 * Ajax handler for loading posts
 */
function sf_ajax_loader_handler() {
	
	// SECURITY
	check_ajax_referer( 'sf_ajax_loader_nonce', 'nonce', true );	
			
	// VARIABLES
	$target = filter_input( INPUT_POST, 'target' , FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ); // JS input
	$term_id = isset( $target[ 'term_id' ] ) && ! empty( $target[ 'term_id' ] ) ? (int)$target[ 'term_id' ] : '';
	$post_id = isset( $target[ 'post_id' ] ) && ! empty( $target[ 'post_id' ] ) ? (int)$target[ 'post_id' ] : '';
	$object_type = isset( $target[ 'type' ] ) && ! empty( $target[ 'type' ] ) ? $target[ 'type' ] : '';
	$post_type = array( 'class', 'student_project', 'page' );
	
	// ARGS
	$args = array(
		'post_type' 		=> $post_type,
		'p'		    		=> $post_id,
		'posts_per_page' 	=> -1
	);
		
	// TAX QUERY
	$tax_query = array(
					array(
						'taxonomy' => 'class_category',
						'field'    => 'term_id',
						'terms'    => $term_id
					)
	);	
	
	// CONDITIONS
	if( 0 == $term_id && empty( $post_id ) ){
		$args[ 'post_type' ] = array( 'student_project' ); // ALL-Case: if term_id and post_id are empty set post type to student_project only
		$tax_query = null; // query all posts
	}
	
	if ( ! empty( $tax_query ) ) {
		$args[ 'tax_query' ] = $tax_query; // check if a tax argument exist, apply if yes
	}
	
	// LOOP
	$bhv_query = null;
	$bhv_query = new WP_Query( $args );
	static $listed = null; // variable to determine that an if block will execute only once whithin the while loop
	
	ob_start();
	if ( $bhv_query->have_posts() ) :
	
		while( $bhv_query->have_posts() ) : 

			$bhv_query->the_post();
	
				// SINGLE ($post_id)
				if ( $post_id && empty( $object_type ) ) :
					get_template_part( 'template-parts/content', 'tmpl_project' ); // choose standard template on post query ($post_id) / or else	
				endif;
	
				// HOME (tax_query)
				if ( ! $post_id ) :
					if( 'student_project' == get_post_type( get_the_ID() ) && null == $listed  ) : // list header needs to be printed only once
						bhv_get_list_header();
						$listed = true;
					endif;
					get_template_part( 'template-parts/content', 'tmpl_project_list' ); // choose list template on tax query ($term_id)	
				endif;
	
				// PAGE ($post_id, $object_type)
				if ( $post_id && !empty( $object_type ) ) :
					get_template_part( 'template-parts/content', 'tmpl_page' ); // template for pages
				endif;	

		endwhile; wp_reset_query();
	
 	endif;
	$data = ob_get_contents();
	ob_end_clean();
	
	$resp = array(
		'success' => true,
		'data'    => $data
	);
	wp_send_json( $resp );
	
	
}
add_action( 'wp_ajax_sf_ajax_loader', 'sf_ajax_loader_handler' );
add_action( 'wp_ajax_nopriv_sf_ajax_loader', 'sf_ajax_loader_handler' );



/** SF:
 *  A set of functions to fire before the loop
 */
function bhv_loop_start( $query ) {
		
	// fire only on the initial (main) query
	if( $query->is_main_query() ){
		?>
		<div class="studio-container">
			<ul class="menu-container">
				<?php print bhv_get_class_list(); // get terms ?>
			</ul><!-- End .menu-container -->	
		</div><!-- End .studio-container -->
		<div id="content-container" class="sf-search-result-container">
		<?php // open tag content-container: element to display ajax results
	}
}
add_action( 'loop_start', 'bhv_loop_start' );



/** SF:
 *  A set of functions to fire after the loop
 */
function bhv_loop_end( $query ) {
	
	// fire only on the initial (main) query
	if( $query->is_main_query() ){	
		?>
		</div><!-- #content-container -->	
		<?php	// close tag: content-container
	}	
}
add_action( 'loop_end', 'bhv_loop_end' );



/** SF:
 *  List all class (taxonomy)
 */
function bhv_get_class_list() {
	
	$taxonomy = 'class_category';
	$link_class = 'link-class';
	$terms = get_terms( array(
		'taxonomy' => $taxonomy,
		'hide_empty' => false,
	) );
	
	// declare variable and manually add default "All" to the class list
	$term_list = '<li class="menu-item"><a class="' . $link_class . '" href="#" data-target="' . esc_attr( json_encode( array( 'term_id' => 0, 'name' => __( 'All', bhv_get_theme_text_domain() ), 'post_id' => '' ) ) ) . '">' . __( 'All', bhv_get_theme_text_domain() ) . '</a></li>';
	
	foreach( $terms as $term ){
		// pack id and term in to a data attribute
		$data = array(
			'term_id'   => $term->term_id,
			'name' => $term->name,
			'post_id' => ''
			
		);		
		// render link
		$term_list .= '<li class="menu-item"><a class="' . $link_class . '" href="#" data-target="' . esc_attr( json_encode( $data ) ) . '">' . $term->name . '</a></li>';
	}
	
	return $term_list;
}



/** SF:
 *  Markup for the list header
 */
function bhv_get_list_header() {
	// MARKUP
	?>
	<div class="list-header">
		<div class="header-project"><?php print __( 'Project', bhv_get_theme_text_domain() ); ?></div>
		<div class="header-students"><?php print __( 'Students', bhv_get_theme_text_domain() ); ?></div>
		<div class="header-teacher"><?php print __( 'Teacher', bhv_get_theme_text_domain() ); ?></div>
		<div class="header-class"><?php print __( 'Class', bhv_get_theme_text_domain() ); ?></div>
		<div class="header-type"><?php print __( 'Type', bhv_get_theme_text_domain() ); ?></div>
		<div class="header-date"><?php print __( 'Year', bhv_get_theme_text_domain() ); ?></div>
	</div><!-- End .list-header -->	
	<?php
}



/** SF:
 *  Markup and values for list entries
 */
function bhv_get_list_entries() {
	
	// VALUES	
	$project_student = bhv_get_terms( get_the_ID(), 'student', '–' );
	$project_teacher = bhv_get_terms( get_the_ID(), 'teacher', '–' );
	$project_class = bhv_get_terms( get_the_ID(), 'class_category', '–' );
	$project_type = bhv_get_terms( get_the_ID(), 'student_project_type', '–' );	
	$project_year = get_the_date( 'Y' );
	$quarter = ' ' . bhv_get_quarter( get_the_date( 'n' ) ) . 'Q';
	
	// MARKUP
	?>	
	<div class="list-project"><h2 class="entry-title"><?php echo get_the_title(); ?></h2></div>
	<div class="list-students"><?php echo $project_student ?></div>
	<div class="list-teacher"><?php echo $project_teacher ?></div>
	<div class="list-class"><?php echo $project_class ?></div>
	<div class="list-type"><?php echo $project_type ?></div>
	<div class="list-date"><?php echo $project_year . $quarter ?></div>
	<?php
}



/** SF:
 *  Add meta tags to the head
 */
function bhv_custom_head() {
	
	// GMT
	echo "
		<!-- Google Tag Manager -->
		<script>
			(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-NL2TTWV');
		</script>
		<!-- End Google Tag Manager -->
	";	

	// TYPEFACE (EN)
	echo '<link rel="stylesheet" href="https://use.typekit.net/wlv6frg.css">';
	
	// TYPEFACE (JP)
	if ( function_exists( 'pll_current_language' ) && ( pll_default_language() != pll_current_language() ) ){	
		echo "
			<script>
			  (function(d) {
				var config = {
				  kitId: 'ept0bzo',
				  scriptTimeout: 3000,
				  async: true
				},
				h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,\"\")+\" wf-inactive\";},config.scriptTimeout),tk=d.createElement(\"script\"),f=false,s=d.getElementsByTagName(\"script\")[0],a;h.className+=\" wf-loading\";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!=\"complete\"&&a!=\"loaded\")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
			  })(document);
			</script>
		";
	}	
	
}

add_action( 'wp_head', 'bhv_custom_head' );



/** SF:
 * Get Custom Field Values: Media Group
 */
function bhv_get_media_group_entries( $img_size = '' ) {
	
	// PREPARE RETURN VARIABLE
	$data = array();
	
	// GET FIELD
	$media_group_entries = get_post_meta( get_the_ID(), 'bhv_media_repeat_group', true );
	
	// LOOP THROUGH GROUP
	foreach ( ( array ) $media_group_entries as $index => $entry ) {
		
		// ENTRY VALUES
		$group_title = isset( $entry[ 'bhv_media_title' ] ) ? $entry[ 'bhv_media_title' ] : ''; // section title for project navigation
		$media_type = isset( $entry[ 'bhv_media_select_media' ] ) ? $entry[ 'bhv_media_select_media' ] : ''; // media type: image or movie 
		$columns = isset( $entry[ 'bhv_media_select_columns' ] ) ? $entry[ 'bhv_media_select_columns' ] : ''; // amount of columns for css-grid-layout
		// resets the array
		$media = null;
		
		// IMAGE (file_list)
		if ( isset( $entry[ 'image' ] ) && !empty( $entry[ 'image' ] ) && $media_type === 'img' ) {
			
			// Loop through the file_list and fill it in the $media array
			foreach ( (array) $entry[ 'image' ] as $attachment_id => $attachment_url ) {
				$image = wp_get_attachment_image( $attachment_id, $img_size );
				$media[] = '<div class="itm itm-' . $media_type . '">' . $image . '</div><!-- .itm itm-' . $media_type . ' -->';
			}
		}
		
		// MOVIE (oembed)
		if ( isset( $entry[ 'movie' ] ) && !empty( $entry[ 'movie' ] ) && $media_type === 'mov' ) {
			$movie = wp_oembed_get( esc_url( $entry[ 'movie' ] ) ); // video embeding over oembed	
			$media[] = '<div class="itm itm-' . $media_type . '">' . $movie . '</div><!-- .itm itm-' . $media_type . ' -->';
		}

		// SAVING THE DATA
		// final check if a value exists before saving the markup
		if ( isset( $media, $group_title, $columns ) ){
			$data[ $index ][ 'media' ] = implode( '', $media );
			$data[ $index ][ 'group_title' ] = $group_title;
			$data[ $index ][ 'columns' ] = $columns;
		}		
	}
	
	return $data;
}



/** SF:
 * Get Custom Field Values: Text Content Term
 */
function bhv_get_text_content_term() {
	
	// GET FIELD
	$data = get_post_meta( get_the_ID(), 'bhv_content_link_term', true );
	$content_term = isset( $data ) ? $data : '–';
	
	return $content_term;
}



/** SF:
 *  Creates an url friendly string
 */
function bhv_create_slug( $string ) {
    return strtolower( trim( preg_replace( '~[^0-9a-z]+~i', '-', html_entity_decode( preg_replace( '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) ), ENT_QUOTES, 'UTF-8' ) ), '-' ) );
}




/** SF:
 *  adds a data-slug attribute to the naviagtion links
 */
add_filter( 'nav_menu_link_attributes', 'bhv_filter_menu_attributes', 10, 4 );

function bhv_filter_menu_attributes( $atts, $item, $args, $depth ) {
	
	if ( 'menu-1' != $args->theme_location ) {
		return $atts; // abort if not main menu or polylang menu-item
	}
	
	// VARS
	$page_id = url_to_postid( $atts[ 'href' ] );
	$data = array(
		'term_id'   => '',
		'name' 		=> $item->title,
		'post_id'   => $page_id,
		'type'	    => 'page'
	);			
	
	// SET ATTS
	$atts[ 'data-target' ] = esc_attr( json_encode( $data ) );
	
	return $atts;
}




/** 
 * 
 * Limiting Gutenbergs Block elements
 *  
 */
function bhv_gutenberg_blocks() {
	
	return array(
		'core/paragraph'
	);
}
add_filter( 'allowed_block_types', 'bhv_gutenberg_blocks' );




/** 
 * 
 * Order Posts by Post Type
 *  
 */
function bhv_custom_post_order( $orderby, $wp_query ){
	
    if( $wp_query->is_main_query() ){
		return $orderby;
	}
	
	global $wpdb;
	$orderby =
		"
		CASE WHEN {$wpdb->prefix}posts.post_type = 'class' THEN '1' 
		WHEN {$wpdb->prefix}posts.post_type = 'student_project' THEN '2' 
		ELSE {$wpdb->prefix}posts.post_type END ASC, 
		{$wpdb->prefix}posts.post_title ASC";
	return $orderby;
}
add_filter( 'posts_orderby', 'bhv_custom_post_order', 10, 2 );




/** SF:
 * Modifying the initial WP query with pre_get_posts hook
 * 
 */
function bhv_modify_wp_query( $query ) {

	if( $query->is_main_query() && is_home() ){	
		
		// VARS
		$post_types = array( 'student_project'  );
		$meta_query = ( is_array( $query->get( 'meta_query' ) ) ) ? $query->get( 'meta_query' ) : []; //Get original meta query before adding additional arguments
		$meta_query[] = array(
							'key'     => 'bhv_hero_image_check',
							'value'   => 'on',
							'compare' => '==',
						); 
		
		// QUERY SET
		$query->set( 'meta_query', $meta_query ); //Add our meta query to the original meta queries
		$query->set( 'post_type', $post_types );
		$query->set( 'posts_per_page', 5 );
		$query->set( 'orderby', 'rand' );
		
	}
}
add_action( 'pre_get_posts', 'bhv_modify_wp_query' );




/** SF:
 * Determines the quarter of the academic year by month number
 * 
 */
function bhv_get_quarter( $month ) {
	
	$quarter = array(
		0 => '',
		1 => '4',
		2 => '4',
		3 => '4',
		4 => '1',
		5 => '1',
		6 => '1',
		7 => '2',
		8 => '2',
		9 => '2',
		10 => '3',
		11 => '3',
		12 => '3',
	);
	
	return $quarter[ $month ];
	
}

