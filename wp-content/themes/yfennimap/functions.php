<?php
/**
 * yfennimap functions and definitions
 *
 * @package yfennimap
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'yfenni_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function yfenni_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on yfennimap, use a find and replace
	 * to change 'yfenni' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'yfenni', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'yfenni' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'yfenni_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );
}
endif; // yfenni_setup
add_action( 'after_setup_theme', 'yfenni_setup' );

//start the session
add_action('init', 'start_the_session');

function start_the_session(){
	session_start();
}

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function yfenni_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'yfenni' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'yfenni_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function yfenni_scripts() {
	wp_enqueue_style( 'yfenni-style', get_stylesheet_uri() );

	wp_enqueue_script( 'yfenni-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if (is_home()){
		wp_register_script( 'map-functions', get_template_directory_uri() . '/js/map-functions.js', array('jquery'), true );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-effects-core' );
		wp_enqueue_script( 'jquery-effects-slide' );
		wp_enqueue_script( 'google_map_api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyC1ssxs7SdqghQui-UadBDVF3bHCarfsng&sensor=false');	

		wp_enqueue_script('map-functions');
		wp_localize_script( 'map-functions', 'pinsMap', get_pins() );

	}
}
add_action( 'wp_enqueue_scripts', 'yfenni_scripts' );


// Populate the pins array

function get_pins(){
	
	// Query the pin post-type
	// populate the arrays with the *title*, *location*, *media_type* and *facebook-object*

	$pins = array();

	// The pins
	$pin_args = array('post_type' => 'pin','posts_per_page'=>-1, 'orderby' => 'title', 'order' => 'ASC');
	$pin_query = new WP_query($pin_args);

	if($pin_query->have_posts()):
		while($pin_query->have_posts()):

			// Create a container array for pin information
			$pin = array();

			$pin_query->the_post();

			// Location from ACF Google Maps field
			$location 		= get_field('location');
			$pin['lat']		= $location['lat'];
			$pin['lng'] 	= $location['lng'];
			$pin['title'] 	= get_the_title();
			$pin['wpid']	= get_the_id();

			// Push to the $pins object
			$pins[ get_the_ID() ] = $pin;

		endwhile;

	endif;

	return $pins;
}



/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load our Facebook PHP SDK
 */
require_once(get_template_directory() . '/inc/facebook-functions.php');

// Ajax function

function yfenni_modal_content($post_id){

	// if($pin_id > 0){

		$post_id = get_post($pin_id ); 
		?>

		<div>
			YOYOYO
		</div>
		<?php
	// }
	die();
}

add_action('wp_ajax_nopriv_modal_content', 'yfenni_modal_content' );
add_action('wp_ajax_modal_content', 'yfenni_modal_content' );

add_action( 'init', 'register_cpt_pin' );

function register_cpt_pin() {

    $labels = array( 
        'name' => _x( 'Pins', 'pin' ),
        'singular_name' => _x( 'Pin', 'pin' ),
        'add_new' => _x( 'Add New', 'pin' ),
        'add_new_item' => _x( 'Add New Pin', 'pin' ),
        'edit_item' => _x( 'Edit Pin', 'pin' ),
        'new_item' => _x( 'New Pin', 'pin' ),
        'view_item' => _x( 'View Pin', 'pin' ),
        'search_items' => _x( 'Search Pins', 'pin' ),
        'not_found' => _x( 'No pins found', 'pin' ),
        'not_found_in_trash' => _x( 'No pins found in Trash', 'pin' ),
        'parent_item_colon' => _x( 'Parent Pin:', 'pin' ),
        'menu_name' => _x( 'Pins', 'pin' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array( 'title', 'custom-fields' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'pin', $args );
}
function my_pre_save_post( $post_id ){

		// check if this is to be a new post. Modified to look at the left-3 characters of the post ID
		if( substr($post_id,0,3) != 'new' ){
			return $post_id;
		}

		//Get the type of post that we'd like to create, e.g. if we pass new_route, we want to create a new 'route'
		$the_post_type = substr($post_id, 4);

		//Create a post title
		$post_title = 'Route ';
		$post_title .= wp_count_posts('route')->publish +1;

		// Create a new post
		$post = array(
				'post_status' 	  	=> 'publish' ,
				'post_title'  	  	=> $post_title, //get the title field
				'submit_value'	  	=> 'Add',
				'post_type'			=> $the_post_type,
				'return' 			=> add_query_arg( 'updated', 'true', get_permalink() ),
				'updated_message' 	=> 'Route added.'
		);

		// insert the post
		$post_id = wp_insert_post( $post );

		// update $_POST['return']
		$_POST['return'] = add_query_arg( array('post_id' => $post_id), $_POST['return'] );

		// return the new ID
		return $post_id;
}

function publish_pin( $post ) {

	require get_template_directory() . '/inc/publish-to-facebook.php';

}
add_action( 'pending_to_publish', 'publish_pin', 9);
add_action( 'draft_to_publish', 'publish_pin', 9);


function insert_attachment($file_handler,$post_id,$setthumb='false') {

//inserts images attachments to post
 
  // check to make sure its a successful upload
  if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
 
  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');
 
  $attach_id = media_handle_upload( $file_handler, $post_id );
 
  if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
  return $attach_id;
 }

function current_url_outside_loop(){
	/**
	*Equivalent of get_permalink() for use outside of the loop
	*
	*
	**/
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

	return $current_url;
}
add_filter('show_admin_bar', '__return_false');

