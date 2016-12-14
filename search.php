<?php
/* Search Results
 *
 * @since   1.0
 * @alter   2.1.3
*/
header("Location: http://tuckfest.org/");
exit(0);
get_header('search'); ?>
<div id="grid">

<?php /* Say hello to the Loop... */
if ( have_posts() ) : 





/* Anything placed in #sort is positioned by jQuery Masonry */ ?>
<div id="page">
        
    <h1 class="page-title">Search Results</h1>
        
        
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
    
    <div class="search">
    
        
        <div <?php post_class( ''.$content_class ) ?>>
            <?php // Display featured image
            if ( has_post_thumbnail() ): ?>
            
                
                
                <?php //if( has_post_format( 'gallery' ) ) get_template_part( 'includes/gallery-list' ); ?>
                
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
	                   the_content(__('Continue Reading &rarr;', 'usnwc'));
	                }
	                
	             ?>
	            
	             
            </div><!-- #entry -->
            
            <?php // Display post footer ?>
                <div class="post-footer">
                <a href="<?php the_permalink(); ?>">
                   LEARN MORE
                 </a><!-- link to the post -->  
                </div>
            
            
        </div><!-- #box-content -->
        
    </div><!-- #box -->
    <?php endwhile; //endif; ?>


<?php else : ?>
	<div class="wrap">
    <div class="box">
    	<div class="box-content not-found">
        	<h2><?php _e('Sorry, nothing was found for your search', 'shaken'); ?></h2>
            <p><?php _e('Maybe try searching for something different.', 'shaken'); ?></p>
        </div>
    </div>
     </div><!-- #wrap -->
    </div><!-- #page -->

<?php endif; ?>
</div><!-- #grid -->
<?php get_footer('search'); ?>