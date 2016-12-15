<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<?php wp_head(); ?>
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'shaken' ), max( $paged, $page ) );

?></title>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="google-site-verification" content="QE1ud00S7EAZ5Lveel-VWoVZQbyxzeRTK3YPjHGDuY4" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="stylesheet" href="<?php echo get_bloginfo('stylesheet_url').'?v=1019323214'; ?>" />
<link rel="stylesheet" href="http://tuck.usnwc.mobi/wp-content/themes/tuckfest/Webfonts/tuckfest/tuckstyle.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="http://tuckfest.org/wp-content/themes/tuckfest/Webfonts/tuckfest2016/tuckfest2016.css" type="text/css" charset="utf-8" />
<link rel="apple-touch-icon" sizes="57x57" href="http://tuckfest.org/wp-content/uploads/2016/11/Favicon_57.png">
<link rel="apple-touch-icon" sizes="72x72" href="http://tuckfest.org/wp-content/uploads/2016/11/Favicon_72.png">
<link rel="apple-touch-icon" sizes="114x114" href="http://tuckfest.org/wp-content/uploads/2016/11/Favicon_114.png">
<link rel="apple-touch-icon" sizes="144x144" href="http://tuckfest.org/wp-content/uploads/2016/11/Favicon_144.png">
<link rel="icon" type="image/png" sizes="16x16" href="http://tuckfest.org/wp-content/uploads/2016/11/Favicon_16.png">
<link rel="icon" type="image/png" sizes="32x32" href="http://tuckfest.org/wp-content/uploads/2016/11/Favicon_32.png">
<link rel="icon" type="image/png" sizes="96x96" href="http://tuckfest.org/wp-content/uploads/2016/11/Favicon_96.png">
<link rel="stylesheet" href="http://tuckfest.org/wp-content/themes/tuckfest/css/addtohomescreen.css">
<script src="<?php echo get_template_directory_uri(); ?>/js/addtohomescreen.min.js?"></script>
<script src="https://use.fontawesome.com/4945cee666.js"></script>
	<script>
addToHomescreen({skipFirstVisit:true,maxDisplayCount:1});
</script>

</head>
<body>
<div class="base container <?php 
if(is_category()){
	$cats="";
	foreach(get_the_category($post->ID) as $cat){
		$cats.=$cat->category_nicename." ";
	}
	echo $cats;
} else {
	echo $post->post_name;
}
?>">
<nav class="mobile">
</nav>
<div class="page container">
<header class="page">
	<!--<?php
		wp_title( '|', true, 'right' );
		bloginfo( 'name' ); 
	?>-->
	<gcse:search></gcse:search>
	<img class="showsearch" src="/wp-content/uploads/2015/09/Search.png">
	<img class="showmenu hamburger" src="/wp-content/uploads/2016/11/Menu_hamburger-1.png">
	<a href="http://tuckfest.org/" title="Tuck Fest 2015" rel="home"><img class="logo-tf" src="/wp-content/uploads/2016/11/TF_Header.png"></a>
	<nav>
		<?php wp_nav_menu( array( 'theme_location' => 'header', 'container' => '' ) ); ?>
	</nav>
</header>
<main class="page">