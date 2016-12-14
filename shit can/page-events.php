<?php 
/* 
 * Template Name: Events Page
*/
if(!is_user_logged_in()){
	header("Location: http://www.tuckfest.org/");
	exit(0);
}
get_header(); ?>

<div id="grid">

<div class="wrap">
<div id="filtering-nav">
	
      	<!--
        
        Uncomment to hide the filter. Also need to uncomment in "js/scripts.js"
        
        
        <a href="#" class="filter-btn"><span><?php _e('Filter by Category', 'usnwc'); ?></span></a>-->
      	<ul>
   			<li><a href="#" data-filter="*"><?php echo ('Everything'); ?></a></li>
            <li><a href="#" data-filter=".riverjam"><?php echo ('RiverJam Band Schedule'); ?></a></li>
            <li><a href="#" data-filter=".whitewater-race-series"><?php echo ('Races'); ?></a></li>
            <li><a href="#" data-filter=".festivals"><?php echo ('Festivals'); ?></a></li>
            <a href="<?php bloginfo(url); ?>/events/event-calendar" class="event-calendar" >Event Calendar</a>
        	
    	</ul>
        <div class="clearfix"></div>
	</div><!-- #filtering-nav -->
    </div>



<?php 
/* Let's query some posts first... */
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
  'posts_per_page' => 5,      // post per page
  'paged' => $paged,           // we need to have it paginated
  'cat' => 1,                  // the category => "Genral" Will let them sort later
  'orderby' => 'menu_order',   // post in order for Post Mash post menu orderer
  'order' => 'asc',            // now reverse it so it actually works 
);
query_posts( $args );    // array passed along into the query
 
/* Say hello to the Loop... */
if ( have_posts() ) : 

/* Anything placed in #sort is positioned by jQuery Masonry */ ?>
<div class="sort">
    
 
    

    
    <?php while ( have_posts() ) : the_post(); 
    	
    	global $my_size, $force_feat_img, $embed_code, $vid_url;
    	
        // Gather custom fields
        //$embed_code = get_post_meta($post->ID, 'soy_vid', true);
        //$vid_url = get_post_meta($post->ID, 'soy_vid_url', true);
        $force_feat_img = get_post_meta($post->ID, 'soy_hide_vid', true);
        $show_title = get_post_meta($post->ID, 'soy_show_title', true);
        $show_desc = get_post_meta($post->ID, 'soy_show_desc', true);
        $box_size = get_post_meta($post->ID, 'soy_box_size', true); 
        
        if( $box_size == 'Medium (485px)' ){
            $my_size = 'col3';
            $embed_size = '495';
        } else if( $box_size == 'Large (660px)' ){
            $my_size = 'col4';
            $embed_size = '670';
        } else if( $box_size == 'Tiny (135px)' ){
            $my_size = 'col1';
            $embed_size = '145';
        }else{
            $my_size = 'col2';
            $embed_size = '320';
        }
        
        /* Check whether content is being displayed
         * This determines whether a border should be applied
         * above the postmeta section
        */
        if($show_title != 'No'){
            $content_class = 'has-content';
        } else if($show_desc != 'No' && $post->post_content){
            $content_class = 'has-content';
        }else {
            $content_class = 'no-content';
        }
        
        // Assign categories as class names to enable filtering
        $category_classes = '';
        
        foreach( ( get_the_category() ) as $category ) {
            $category_classes .= $category->category_nicename . ' ';
        } 
    ?>
    
    <div class="all box <?php echo $category_classes . $my_size; ?>">
    
        
        <div <?php post_class( 'box-content '.$content_class ) ?>>
            <?php // Display featured image
            if ( has_post_thumbnail() ): ?>
            
                <div class="img-container">    
                    <?php 
                    // Display the appropriate sized featured image
                    if( $my_size != 'col2' ): ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail($my_size, array( 'class' => 'feat-img' ) ); ?></a>
                    <?php else: ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('post-thumbnail', array( 'class' => 'feat-img' ) ); ?></a>
                    <?php endif; ?>
                    <?php // Display View/Share/Comment buttons
                       get_template_part( 'includes/action-buttons' ); ?>
                </div><!-- #img-container -->
                
                <?php if( has_post_format( 'gallery' ) ) get_template_part( 'includes/gallery-list' ); ?>
                
            <?php endif; // #has_post_thumbnail() ?>
            
            <div class="post-content">
            
	            <?php // Display post title ?>
	           <h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
	                	<?php the_title(); ?>
	                </a></h2>
	           
                <?php // Display post content
	            
	            
	                if( has_excerpt() ){ 
	                    the_excerpt(); 
	                } else {
	                    the_content(__('Continue Reading &rarr;', 'shaken'));
	                }
	                
	             ?>
	            
	             
            </div><!-- #entry -->
            
            <?php // Display post footer ?>
                <div class="post-footer">
                <a href="<?php the_permalink(); ?>">
                    MORE
                 </a><!-- link to the post -->  
                </div>
            
            
        </div><!-- #box-content -->
        
    </div><!-- #box -->
    <?php endwhile; ?>
</div><!-- #sort -->

<?php // Display pagination when applicable
if (  $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-below" class="navigation">
        <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older', 'shaken') ); ?></div>
        <div class="nav-next"><?php previous_posts_link( __( 'Newer <span class="meta-nav">&rarr;</span>', 'shaken') ); ?></div>
        <div class="clearfix"></div>
    </div><!-- #nav-below -->
<?php endif; ?>

<?php else :
/* If there are no posts */ ?>
<div id="sort">
    <div class="box">
        <div class="box-content not-found">
        <h2><?php _e('Sorry, no posts were found', 'shaken'); ?></h2>
        <?php get_search_form(); ?>
        </div><!-- #not-found -->
    </div>
</div><!-- #sort -->
<?php endif; ?>










</div><!-- #grid -->
    
<?php get_footer(); ?>