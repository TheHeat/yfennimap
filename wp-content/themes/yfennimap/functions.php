<?php
/**
 * yfennimap functions and definitions
 *
 * @package yfenni
 */

// Error Reporting for DEV
ini_set('display_errors',1); 
error_reporting(E_ALL);

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

add_action('init', 'yfenniStartSession', 1);
add_action('wp_logout', 'yfenniEndSession');
add_action('wp_login', 'yfenniEndSession');

function yfenniStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function yfenniEndSession() {
    session_destroy ();
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
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-position' );
		wp_enqueue_script( 'jquery-effects-core' );
		wp_enqueue_script( 'jquery-effects-slide' );
		wp_enqueue_script( 'jquery-ui-widget');

	//if (is_home()){
		wp_register_script( 'map-functions', get_template_directory_uri() . '/js/map-functions.js', array('jquery'), true );

		
		wp_enqueue_script( 'google_map_api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyC1ssxs7SdqghQui-UadBDVF3bHCarfsng&sensor=false');	

		wp_enqueue_script('map-functions');
		wp_localize_script( 'map-functions', 'pinsMap', get_pins() );
		wp_localize_script( 'map-functions', 'activeCategories', get_categories(array( 'taxonomy' => 'pin_category')) );
		// make the ajaxurl var available to the map-functions script
		wp_localize_script( 'map-functions', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
		wp_enqueue_script( 'facebook',get_template_directory_uri() . '/js/facebook.js', array(), true );
	//}
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

	// //filter by category if there is one specified in the url
	// $category = null; 

	// if(isset($_GET['category'])) $category = $_GET['category'];

	// if($category) {

	// 	$pin_args['tax_query'] = array(
	// 		array(
	// 			'taxonomy' => 'category',
	// 			'field' => 'slug',
	// 			'terms' => $category
	// 		)
	// 	);
	// }


	if($pin_query->have_posts()):
		while($pin_query->have_posts()):

			// Create a container array for pin information
			$pin = array();

			$pin_query->the_post();

			// Location from ACF Google Maps field
			$location 			= get_field('location');
			$pin['lat']			= $location['lat'];
			$pin['lng'] 		= $location['lng'];
			$pin['title'] 		= get_the_title();
			$pin['wpid']		= get_the_id();
			$pin['fbURL']		= get_post_meta( get_the_ID(), 'fb_post_url', true);
			$pin['icon']		= get_stylesheet_directory_uri() . '/img/mapicon_' . get_field('media_type') . '.svg';
			$pin['year']		= get_field('year-created');
			$pin['categories'] 	= wp_get_post_terms( get_the_ID(), 'pin_category', array( 'fields' => 'names' ) );

			// Push to the $pins object
			$pins[ get_the_ID() ] = $pin;

		endwhile;

	endif;

	return $pins;
}

// Ajax Test

function yfenni_pin_template() {

	// first check if data is being sent and that it is the data we want
  	if ( isset( $_POST["pin_id"] ) ) {

		// now set our response var equal to that of the POST var pin_id
		$pin_obect = get_post( $_POST["pin_id"] );

		// send the response back to the front end
		wp_send_json( $pin_obect ); 
	}
}
add_action('wp_ajax_pin_loader', 'yfenni_pin_template');
add_action('wp_ajax_nopriv_pin_loader', 'yfenni_pin_template');

/**
 * Get the ACF plugin
 */
//define( 'ACF_LITE' , true );
include_once('advanced-custom-fields/acf.php' );
/**
 * Load our Custom Fields
 */
require get_template_directory() . '/inc/acf-custom-fields.php';

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
require get_template_directory() . '/inc/facebook-functions.php';


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
        
        'supports' => array( 'title', 'custom-fields', 'taxonomies' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'pin', $args );
}
// Register Custom Taxonomy
function pin_categories() {

	$labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Categories', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'pin_category', array( 'pin' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'pin_categories', 0 );

function post_published( $new_status, $old_status, $post ) {
	if( $post->post_type == 'pin'){
	    if ( $old_status != 'publish' && $new_status == 'publish' ) {

	    	// insert post so that custom fields are saved before we need them
			wp_insert_post( $post );

			require get_template_directory() . '/inc/publish-to-facebook.php';
	    }
	}
}
add_action( 'transition_post_status', 'post_published', 20, 3 );

function my_acf_save_post( $post_id )
{
	// vars
	$fields = false;
 
	// load from post
	if( isset($_POST['fields']) )
	{
		$fields = $_POST['fields'];
	}
 
	// ...
}
 
// run before ACF saves the $_POST['fields'] data
add_action('transition_post_status', 'my_acf_save_post', 1);
 
// run after ACF saves the $_POST['fields'] data
add_action('acf/save_post', 'my_acf_save_post', 20);


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

if( function_exists('acf_add_options_sub_page') )
{
    acf_add_options_sub_page( 'Facebook Settings' );
}

add_filter('show_admin_bar', '__return_false');


function pin_display($media_type, $fb_media) {
	switch ($media_type) {
		case 'text':

			echo $fb_media->get_text();

			break;

		case 'image':

			echo $fb_media->get_images();
			echo $fb_media->get_text();

			break;

		case 'video':

			echo $fb_media->get_video();
			echo $fb_media->get_text();

			break;

		case 'link':

			echo $fb_media->get_link();

			break;
	}
}
