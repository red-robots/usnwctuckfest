<?php
/**
 * Theme Functions

 *
 */

add_action( 'after_setup_theme', 'shaken_setup' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override shaken_setup() in a child theme, add your own shaken_setup to your child theme's
 * functions.php file.
 *
 * @since 1.5.0
 */
if(!function_exists('shaken_setup')):
function shaken_setup() {
	// Theme support
		
		if ( ! isset( $content_width ) ){
			$content_width = 620;
		}
		
		// Enable support for default WordPress components 
		add_theme_support( 'post-formats', array( 'quote', 'gallery' ) );
		add_editor_style();
		add_theme_support('automatic-feed-links');
		add_custom_background('shaken_custom_background_cb');
		
		// Set featured image sizes
		add_theme_support( 'post-thumbnails');
		add_image_size( 'tile', 275, 175, true);
		add_image_size( 'article', 200, 267, true);
		
		/**
		 * Support added for theme specific components 
		 * To remove support, create a child theme and use remove_theme_support()
		 * in its functions.php file.
		 *
		 * We can remove the parent theme's hook only after it is attached, which means we need to
 		 * wait until setting up the child theme:
		 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
		 * function my_child_theme_setup() {
		 *     // We are removing support for the hover buttons
		 *     remove_theme_support('shaken_action_buttons');
 		 *     ...
		 * }
		 * @since 1.5.0
		*/
		
		// Buttons shown on image hover
		add_theme_support('shaken_action_buttons');
		
		// Footer credit text
		//add_theme_support('shaken_footer_credit');
		
		/* Uncomment the line below to enable comments
		   on all page templates. */
		//add_theme_support('shaken_page_comments');
	
	// Actions
		
		/* Add your nav menus function to the 'init' action hook. */
		add_action( 'init', 'shaken_register_menus' );
		
		/* Add your sidebars function to the 'widgets_init' action hook. */
		add_action( 'widgets_init', 'shaken_register_sidebars' );
		
		// Threaded comments
		add_action('get_header', 'enable_threaded_comments');
		
		// Customize dashboard widgets
		add_action('wp_dashboard_setup', 'shaken_dashboard_widgets');
		
	// Filters
		
		// No more jumping on Read More link
		add_filter('excerpt_more', 'no_more_jumping');
		
		
		
		// Add featured images to RSS
		add_filter('pre_get_posts','feedFilter');
		
		// Add wmode='transparent' to auto embedded Flash videos
		add_filter('embed_oembed_html', 'add_video_wmode', 10, 3);
		
		// Allow shortcodes in text widgets
		add_filter('widget_text', 'shortcode_unautop');
		add_filter('widget_text', 'do_shortcode');
		
	/* Make theme available for translation
	 * Translations can be filed in the /languages/ directory */
	load_theme_textdomain( 'shaken', TEMPLATEPATH . '/languages' );
	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );
}

/**
* Plugin Name: Soliloquy - Exclude from Search
* Plugin URI: http://soliloquywp.com
* Version: 1.0
* Author: Tim Carr
* Author URI: http://www.n7studios.co.uk
* Description: Exclude Soliloquy Sliders from Search Results
*/

/**
* Exclude the Soliloquy CPT from search results
*
* @param array $args register_post_type() args
* @return array register_post_type() args
*/
function soliloquy_exclude_from_search( $args ) {
	
	$args['exclude_from_search'] = true;
	return $args;
	
}
add_filter( 'soliloquy_post_type_args', 'soliloquy_exclude_from_search' );

endif;

/** 
 * shaken_custom_background_cb()
 * Create a callback for custom backgrounds
 * Removes the old background so the user defined background can display.
 *
 * @since 1.6.0
**/
/*if(!function_exists('shaken_custom_background_cb')){
function shaken_custom_background_cb() {
	$background = get_background_image();
	$color = get_background_color();
	if ( ! $background && ! $color )
		return;
 
	$style = $color ? "background-color: #$color;" : '';
 
	if ( $background ) {
		$image = " background-image: url('$background');";
 
		$repeat = get_theme_mod( 'background_repeat', 'repeat' );
		if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
			$repeat = 'repeat';
		$repeat = " background-repeat: $repeat;";
 
		$position = get_theme_mod( 'background_position_x', 'left' );
		if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
			$position = 'left';
		$position = " background-position: top $position;";
 
		$attachment = get_theme_mod( 'background_attachment', 'scroll' );
		if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
			$attachment = 'scroll';
		$attachment = " background-attachment: $attachment;";
 
		$style .= $image . $repeat . $position . $attachment;
	}
?>
<style type="text/css">
body {background:none; <?php echo trim( $style ); ?> }
</style>
<?php }
}*/

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 */
function shaken_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}

function shaken_register_menus(){
	register_nav_menus( array(
			'header' => __( 'Header Menu'),
			'footer' => __( 'footer Menu'),
			'programs' => __( 'programs Menu'),
	) );
}

// --------------  Register Menus -------------- 

add_action( 'widgets_init', 'baker_register_sidebars' );

function baker_register_sidebars(){
	register_sidebar( array (
		'name' => 'Page Sidebar',
		'id' => 'page-sidebar',
		'description' => __( 'The sidebar on basic pages'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array (
		'name' => "Event Post Sidebar",
		'id' => 'post-sidebar',
		'description' => __( 'The sidebar on blog post pages'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array (
		'name' => "Programs Sidebar",
		'id' => 'programs-sidebar',
		'description' => __( 'The sidebar on pages with the template of "Programs Sidebar" assigned to them.'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array (
		'name' => 'Trail Status',
		'id' => 'trails-sidebar',
		'description' => __( 'Widget for the Trail Status'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array (
		'name' => 'Activity Schedule',
		'id' => 'activity-sidebar',
		'description' => __( 'Widget for the Activity Schedule'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

// smart jquery inclusion
function shaken_jquery(){
    if (!is_admin()) {
    	wp_enqueue_script('jquery');
    }
}
add_action( 'wp_enqueue_scripts', 'shaken_jquery' );

// enable threaded comments
function enable_threaded_comments(){
	if (!is_admin()) {
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
			wp_enqueue_script('comment-reply');
		}
}

// no more jumping for read more link
if(!function_exists('no_more_jumping')){
function no_more_jumping($post) {
	return ' ...<a href="'.get_permalink($post->ID).'" class="read-more">'.'Continue Reading'.'</a>';
}}

// -------------- Add featured images to RSS feed --------------
if(!function_exists('feedContentFilter')){
function feedContentFilter($content) {
	$thumbId = get_post_thumbnail_id();
	
	$embed_code = get_post_meta(get_the_id(), 'soy_vid', true);
    $vid_url = get_post_meta(get_the_id(), 'soy_vid_url', true);
 	
	if($thumbId) {
		$img = wp_get_attachment_image_src($thumbId, 'col3');
		$image = '<img src="'. $img[0] .'" alt="" width="'. $img[1] .'" height="'. $img[2] .'" />';
		echo $image;
	}
 	
 	if($embed_code){
 		echo $embed_code;
 	} else if($vid_url){
 		echo '<p><strong><a href="'.$vid_url.'">View Video</a></strong></p>';
 	}
 	
	return $content;
}}

if(!function_exists('feedFilter')){
function feedFilter($query) {
	if ($query->is_feed) {
		add_filter('the_content', 'feedContentFilter');
		}
	return $query;
}}

// Add S&S RSS feed to dashboard
//function shaken_rss_output(){
   // echo '<div class="rss-widget">';
     
    //   wp_widget_rss_output(array(
          //  'url' => 'http://feeds.feedburner.com/shakenandstirredweb/MLnE',  //put your feed URL here
          //  'title' => 'Latest News from Shaken &amp; Stirred', // Your feed title
          //  'items' => 5, //how many posts to show
         //   'show_summary' => 1, // 0 = false and 1 = true 
         //   'show_author' => 0,
         //   'show_date' => 0
     //  ));
       
     //  echo "</div>";
//}

// Add text dashboard widget
//function shaken_twitter_dash_output(){
   // echo '<div class="text-widget">';
     
	//echo '<p>Follow Shaken and Stirred on <strong><a href="http://twitter.com/shakenweb" target="_blank">Twitter (@shakenweb)</a></strong> to stay up to date with the latest theme updates and new releases. You can also <strong><a href="http://shakenandstirredweb.com" target="_blank">visit our website</a></strong> to read our Tips &amp; Tricks to get the most out of your theme. We hope you enjoy our theme!</p>';
       
    //echo "</div>";
//}

// Add and remove dashboard widgets
//function shaken_dashboard_widgets(){
	// Add custom widgets
	//wp_add_dashboard_widget( 'shaken-twitter', 'Stay Updated!', 'shaken_twitter_dash_output');
  	//wp_add_dashboard_widget( 'shaken-rss', 'Latest News from Shaken &amp; Stirred', 'shaken_rss_output');
//}

// --------------  Theme Options Panel --------------
require_once(TEMPLATEPATH . '/functions/framework-init.php');

?>
<?php 

// --------------  The Bread Crumb Function --------------
function get_breadcrumbs(){
	global $post;

	$separator = '  &raquo; '; // what to place between the pages

	if ( is_page() ){
		// bread crumb structure only logical on pages
		$trail = array($post); // initially $trail only contains the current page
		$parent = $post; // initially set to current post
		$show_on_front = get_option( 'show_on_front'); // does the front page display the latest posts or a static page
		$page_on_front = get_option( 'page_on_front' ); // if it shows a page, what page
		// while the current page isn't the home page and it has a parent
		while ( $parent->post_parent && !($parent->ID == $page_on_front && 'page') == $show_on_front ){
			$parent = get_post( $parent->post_parent ); // get the current page's parent
			array_unshift( $trail, $parent ); // add the parent object to beginning of array
		}
		if ( 'posts' == $show_on_front ) // if the front page shows latest posts, simply display a home link
			echo "<li class='breadcrumb-item' id='breadcrumb-0'><a href='" . get_bloginfo('home') . "'>Home</a></li>\n"; // home page link
		else{ // if the front page displays a static page, display a link to it
			$home_page = get_post( $page_on_front ); // get the front page object
			echo "<li class='breadcrumb-item' id='breadcrumb-{$home_page->ID}'><a href='" . get_bloginfo('home') . "'>$home_page->post_title</a></li>\n"; // home page link
			if($trail[0]->ID == $page_on_front) // if the home page is an ancestor of this page
				array_shift( $trail ); // remove the home page from the $trail because we've already printed it
		}
		foreach ( $trail as $page){
			// print the link to the current page in the foreach
			echo "<li class='breadcrumb-item' id='breadcrumb-{$page->ID}' >$separator<a href='" . get_page_link( $page->ID ) . "'>{$page->post_title}</a></li>\n";
		}
	}else{
		// if what we're looking at isn't a page, simply display a home link
		echo "<li class='breadcrumb-item' id='breadcrumb-0'><a href='" . get_bloginfo('home') . "'>Home</a></li>\n"; // home page link
	}
}


function bloglow_get_breadcrumb_navigation() {
$delimiter = '&raquo;';
$home = get_bloginfo('name');
$before = '<span>';
$after = '</span>';
echo '<div id="breadcrumb"><!-- Bloglow breadcrumb navigation without a plugin v1.0 - http://bloglow.com/plugins/display-wordpress-breadcrumb-navigation-without-a-plugin/ -->';
global $post;
$homeLink = get_bloginfo('url');
echo '<a href="' . $homeLink . '">' . Home . '</a> ' . $delimiter . ' ';
if ( is_category() ) {
global $wp_query;
$cat_obj = $wp_query->get_queried_object();
$thisCat = $cat_obj->term_id;
$thisCat = get_category($thisCat);
$parentCat = get_category($thisCat->parent);
if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
echo $before . '' . single_cat_title('', false) . '' . $after;
} elseif ( is_day() ) {
echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
echo $before . '' . get_the_time('d') . '' . $after;
} elseif ( is_month() ) {
echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
echo $before . '' . get_the_time('F') . '' . $after;
} elseif ( is_year() ) {
echo $before . '' . get_the_time('Y') . '' . $after;
} elseif ( is_single() && !is_attachment() ) {
if ( get_post_type() != 'post' ) {
$post_type = get_post_type_object(get_post_type());
$slug = $post_type->rewrite;
echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>' . $delimiter . ' ';
echo $before . get_the_title() . $after;
} else {
$cat = get_the_category(); $cat = $cat[0];
echo ' ' . get_category_parents($cat, TRUE, ' ' . $delimiter . ' ') . ' ';
echo $before . '' . get_the_title() . '' . $after;
}
} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
$post_type = get_post_type_object(get_post_type());
echo $before . $post_type->labels->singular_name . $after;
} elseif ( is_attachment() ) {
$parent_id  = $post->post_parent;
$breadcrumbs = array();
while ($parent_id) {
$page = get_page($parent_id);
$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
$parent_id    = $page->post_parent;
}
$breadcrumbs = array_reverse($breadcrumbs);
foreach ($breadcrumbs as $crumb) echo ' ' . $crumb . ' ' . $delimiter . ' ';
echo $before . '' . get_the_title() . '' . $after;
} elseif ( is_page() && !$post->post_parent ) {
echo $before . '' . get_the_title() . '' . $after;
} elseif ( is_page() && $post->post_parent ) {
$parent_id  = $post->post_parent;
$breadcrumbs = array();
while ($parent_id) {
$page = get_page($parent_id);
$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
$parent_id    = $page->post_parent;
}
$breadcrumbs = array_reverse($breadcrumbs);
foreach ($breadcrumbs as $crumb) echo ' ' . $crumb . ' ' . $delimiter . ' ';
echo $before . '' . get_the_title() . '' . $after;
} elseif ( is_search() ) {
echo $before . 'Search results for "' . get_search_query() . '"' . $after;
} elseif ( is_tag() ) {
echo $before . '' . single_tag_title('', false) . '' . $after;
} elseif ( is_author() ) {
global $author;
$userdata = get_userdata($author);
echo $before . 'Articles posted by "' . $userdata->display_name . '"' . $after;
} elseif ( is_404() ) {
echo $before . 'You got it "' . 'Error 404 not Found' . '"&nbsp;' . $after;
}
if ( get_query_var('paged') ) {
if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
echo ('Page') . ' ' . get_query_var('paged');
if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
}
echo '</div><!-- / Bloglow breadcrumb navigation without a plugin -->';
}

function the_breadcrumb() {
	if (!is_home()) {
		echo '<a href="';
		echo get_option('home');
		echo '">';
		echo('Home');
		echo "</a> &raquo; ";
		if (is_category() || is_single()) {
			the_category(', ');
			if (is_single()) {
				echo " &raquo; ";
				the_title();
			}
		} elseif (is_page()) {
			echo the_title();
		}
	}
}


?>
<?php
/*
Plugin Name: Page Excerpt

Description: Adds support for page excerpts - uses WordPress code

*/

add_action( 'edit_page_form', 'pe_add_box');
add_action('init', 'pe_init');

function pe_init() {
	if(function_exists("add_post_type_support")) //support 3.1 and greater
	{
		add_post_type_support( 'page', 'excerpt' );
	}
}

function pe_page_excerpt_meta_box($post) {
?>
<label class="hidden" for="excerpt"><?php _e('Excerpt') ?></label><textarea rows="1" cols="40" name="excerpt" tabindex="6" id="excerpt"><?php echo $post->post_excerpt ?></textarea>
<p><?php _e('Excerpt go here..........'); ?></p>
<?php
}


function pe_add_box()
{
	if(!function_exists("add_post_type_support")) //legacy
	{		add_meta_box('postexcerpt', __('Page Excerpt'), 'pe_page_excerpt_meta_box', 'page', 'advanced', 'core');
	}
}

?>
<?php  // Limit the excerpt without truncating the last word.
function get_excerpt($count){
  $permalink = get_permalink($post->ID);
  $excerpt = get_the_content();
  $excerpt = strip_tags($excerpt);
  $excerpt = substr($excerpt, 0, $count);
  $excerpt = $excerpt.'... <a href="'.$permalink.'">more</a>';
  return $excerpt;
}
?>
<?php
     add_filter( 'avatar_defaults', 'newgravatar' );

    function newgravatar ($avatar_defaults) {
    $myavatar = get_bloginfo('template_url') . '/images/usnwc-gravatar.png';
    $avatar_defaults[$myavatar] = "WPBeginner";
    return $avatar_defaults;
    } 
?>
<?php
function custom_login_logo() {
        echo '<style type="text/css">h1 a { background: url('.get_bloginfo('template_directory').'/images/USNWC-logo.jpg) 50% 50% no-repeat !important; }</style>';
}
add_action('login_head', 'custom_login_logo'); 
?>
<?php
//This Creates a page for the Summer Camp Profile Page
// checks they are authoized
function auth_redirect_login() {
$user = wp_get_current_user();
if ( $user->id == 0 ) {
nocache_headers();
wp_redirect(get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
exit();
}
} 
if(!function_exists('return_100')){
	function return_100(){
		return 100;
	}
}
add_filter( 'jpeg_quality', 'return_100' );
?>