<?php
/* Water Category Archive
 *
 * @since   1.0
 * @alter   1.6
*/

get_header('category'); ?>

<div id="grid">



<script type="text/javascript">

jQuery(document).ready(function($){
	$(".title").click(function () {
	//alert('test');
    $header = $(this);
    //getting the next element
    $content = $header.parent().next();
    //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
   	$content.slideToggle(300);
   	$content.parent().siblings().children().next().slideUp();
    
	}); 
	
	$("img.category-img").click(function () {
	//alert('test');
    $header = $(this);
    //getting the next element
    $content = $header.parent().next();
    //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
   	$content.slideToggle(300);
   	$content.parent().siblings().children().next().slideUp();
    
	}); 
	
	
});



</script>


<?php
  if ( is_category(array('adventure-obstacle', 'trail-running', 'climbing', 'mountain-biking', 'whitewater', 'flatwater')) ) {
  include(TEMPLATEPATH . '/competitions.php');
  }
?>


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
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '306' ); ?>


  <?php } ?>

</div>

<div class="wrap">



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
if ( have_posts() ) : 

/* Anything placed in #sort is positioned by jQuery Masonry */ ?>

<div id="categories">

<?php $category = get_category( get_query_var( 'cat' ) ); ?>
<?php $cat_id = $category->cat_ID; ?>

<?php $categories = get_categories("child_of=$cat_id"); foreach ($categories as $cat) { ?>
<?php query_posts("cat=$cat->cat_ID&showposts=-1&order=ASC&orderby=ID"); ?>

<div class="subcat-container">
	<div class="subcategory" id="<?php single_cat_title(); ?>">

		<?php 

		$image = get_field("category_image", $cat);

		if( !empty($image) ): ?>

		<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="category-img"/>

		<?php endif; ?>

		<div class="title">
			<img src="/wp-content/uploads/2014/10/TF_WButton.png" height="50"/>
			<p><?php single_cat_title(); ?></p>
		</div>
	</div> <!-- end subcategory -->

	<div class="all-posts">

    	<?php while ( have_posts() ) : the_post();?>   
    
    		<div class="post">
    
    
				<?php $category_link = get_category_link( $cat ); 
					  $sponsor = get_field("sponsor");
				?>

    			<div class="post-title<?php if (has_category('music') ) { ?> music<?php } ?>" ><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?><span style="font-size:11pt;"><?php if ($sponsor) { echo " presented by ".$sponsor; } ?></span></a>
    			
    			<?php
    			
    			$friday = get_field("friday_time");
    			$saturday = get_field("saturday_time");
    			$sunday = get_field("sunday_time");

				if( !empty($friday) && $cat->slug == 'friday' ) {

				echo " - <span class='time'>".$friday."</span>";
				
				}
				
				if( !empty($saturday) && $cat->slug == 'saturday' ) {

				echo " - <span class='time'>".$saturday."</span>";
				
				}
				
				if( !empty($sunday) && $cat->slug == 'sunday' ) {

				echo " - <span class='time'>".$sunday."</span>";
				
				}
    			
    			 ?></div>
    			 

			</div> <!-- end post -->

    	<?php endwhile; ?>
    
    </div> <!-- end all posts -->
    
    
 </div> <!-- end subcat container --> 
    
    <?php }?>

</div><!-- categories -->
</div>


</div>

<?php else :
/* If there are no posts */ ?>
<div id="sort">
  <!--  <div class="box">
        <div class="box-content not-found">
        <h2><?php echo('Sorry, no posts were found'); ?></h2>
        <?php get_search_form(); ?>
        </div>
    </div> -->
</div><!-- #sort -->
<?php endif; ?>


</div><!-- #grid -->
<?php get_footer('category'); ?>