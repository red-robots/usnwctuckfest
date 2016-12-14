<?php
/* Water Category Archive
 *
 * @since   1.0
 * @alter   1.6
*/
if(!is_user_logged_in()){
	header("Location: http://www.tuckfest.org/");
	exit(0);
}
get_header('category'); ?>

<div id="grid">


<?php
  if ( is_category(array('adventure-obstacle', 'trail-running', 'climbing', 'mountain-biking', 'whitewater-kayak', 'whitewater-sup', 'flatwater', 'music-friday', 'music-saturday', 'music-sunday', 'demos')) ) :
  include(TEMPLATEPATH . '/competitions.php');
  
  
  elseif ( is_category(array('schedule')) ) :
  include(TEMPLATEPATH . '/dropdown-category.php');
  
  
  else : ?>


<?php
/**

 * Post Filters
 *
 * Displays filter options if on frontpage and if they aren't 
 * disabled in the theme options. The filters are based on
 * the categories. Each post has its category slug assigned
 * as class names. The Isotope plugin handles the filtering.
 */ ?>

<div class="category-banners">
    <?php if (is_category('schedule')) { ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '105' ); ?>
	<?php } elseif (is_category('music')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '622' );  ?>
    <?php } elseif (is_category('about')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '248' );  ?>
	<?php } elseif (is_category('water')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '273' );  ?>
    <?php } elseif (is_category('land')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '267' );  ?>
    <?php } elseif (is_category('demos')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '263' );  ?>
    <?php } elseif (is_category('competitions-demos')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '261' );  ?>
    
    
  <?php } ?>

</div>

<div class="wrap">

<div id="page">

<?php if(is_category('about') ) { ?><h1 class="page-title"><?php single_cat_title() ?></h1><?php } ?>

<div class="category-description">
	<?php echo category_description(); ?>
</div>
</div>

<?php 
/**
 * Display ALL posts
 *
 * If this is the homepage and the "show all posts on blog" option
 * is checked in the theme options, then display all posts on one 
 * page without pagination.
 */ 
/*if( is_home() && !is_search() && ( of_get_option( 'show_all' ) || of_get_option( 'frontpage_category' ) ) ):
	
	$query_string = false;
	
	if( of_get_option('show_all') ){
		$query_string = 'posts_per_page=-1';
	}
	
	if( of_get_option('frontpage_category') && of_get_option('frontpage_category') != 'all' ){
	
		if(of_get_option('show_all')){
			$query_string .= '&';
		}
		
		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$query_string .= 'cat='.of_get_option('frontpage_category').'&paged='.$paged;
	}
	
	if( $query_string ){
		query_posts($query_string);
	}
	
endif;*/


//query_posts( 'cat=-72' );



global $query_string;

/* Say hello to the Loop... */
if ( have_posts() || is_category('about')) : 

/* Anything placed in #sort is positioned by jQuery Masonry */ ?>


<div id="categories">



<?php $category = get_category( get_query_var( 'cat' ) ); ?>
<?php $cat_id = $category->cat_ID; ?>
<?php $categories = get_categories("parent=$cat_id&hide_empty=0"); foreach ($categories as $cat) { ?>

<div class="subcat-container">
	<div class="subcategory" id="<?php single_cat_title(); ?>">

		<?php 
		
		$category_link = get_category_link( $cat );
		
		$image = get_field("category_image", $cat);
		$alternate = get_field("alternate_link", $cat);

		if( !empty($image) ): 

			if( $alternate ) : ?>
				<?php $link = $alternate; ?>
				<a href="<?php echo esc_url( $alternate ); ?>" > <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" /></a>
			<?php else : ?>
				<?php $link = $category_link; ?>
				<a href="<?php echo esc_url( $category_link ); ?>" > <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" /></a>
			<?php endif; ?>
			
		<?php endif; ?>

		<div class="title">
			<a href="<?php echo esc_url( $link ); ?>" > <img src="/wp-content/uploads/2014/10/TF_WButton.png" height="50"/></a>
			<p><a href="<?php echo esc_url( $link ); ?>" > <?php echo $cat->cat_name; ?></a></p>
		</div>
	</div> <!-- end subcategory -->    
    
 </div> <!-- end subcat container --> 

    
    <?php }?>

</div><!-- categories -->
</div>


</div>

<?php else :
/* If there are no posts */ ?>
<div id="sort">

  <?php $category = get_category( get_query_var( 'cat' ) ); ?>
	<?php $cat_id = $category->cat_ID; ?>
  <?php echo category_description( $cat_id ); ?> 
  
  
</div><!-- #sort -->
<?php endif; ?>


</div><!-- #grid -->
<?php get_footer('category'); 

  endif;
?>