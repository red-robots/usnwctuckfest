 <?php
/*
 * Template Name: Activity Page Top Level
*/
if(!is_user_logged_in()){
	header("Location: http://www.tuckfest.org/");
	exit(0);
}
get_header('page'); ?>

  
<div id="grid">
<div class="category-banners">
    <?php if (is_page('educational-adventures')) { ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '9105' ); ?>
    <?php } elseif (is_category('whitewater-kayak')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '275' );  ?>
    <?php } elseif (is_category('whitewater-sup')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '462' );  ?>
    <?php } elseif (is_category('trail-running')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '271' );  ?>
	<?php } elseif (is_category('mountain-biking')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '269' );  ?>
    <?php } elseif (is_category('climbing')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '259' );  ?>
    <?php } elseif (is_category('adventure-obstacle')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '257' );  ?>
    <?php } elseif (is_category('flatwater')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '265' );  ?>
    <?php } elseif (is_category('demos')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '263' );  ?>
    <?php } elseif (is_category('music-friday')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '310' );  ?>
    <?php } elseif (is_category('music-saturday')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '312' );  ?>
    <?php } elseif (is_category('music-sunday')) {  ?>
    <?php if ( function_exists( 'soliloquy_slider' ) ) soliloquy_slider( '314' );  ?>

  <?php } ?>

</div>
   
<div id="page">
<?php $category = get_the_category(); ?>
<h1 class="page-title"><?php single_cat_title() ?></h1>

        <div class="box-content post">
           
<?php global $query_string;


/* Say hello to the Loop... */
if ( have_posts() ) : 


/* Anything placed in #sort is positioned by jQuery Masonry */ ?>
<div class="activities">
    


    
    <?php while ( have_posts() ) : the_post(); 
    	
    	global $my_size, $force_feat_img, $embed_code, $vid_url;
    	
        // Gather custom fields
        //$embed_code = get_post_meta($post->ID, 'soy_vid', true);
        //$vid_url = get_post_meta($post->ID, 'soy_vid_url', true);
        $force_feat_img = get_post_meta($post->ID, 'soy_hide_vid', true);
        $show_title = get_post_meta($post->ID, 'soy_show_title', true);
        $show_desc = get_post_meta($post->ID, 'soy_show_desc', true);
        $box_size = get_post_meta($post->ID, 'soy_box_size', true); 
		$date = date('h:ia', time());
        
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
        
        /* Check whether content is being displayeda
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
    
    <a name="<?php echo($post->post_name) ?>"></a>
    <div id="single-activity">
    

            
             <?php // Display featured image
            if ( has_post_thumbnail() ): ?>
            
            <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'col5' ); 
            $url = $thumb['0']; ?>
            
            
                <div class="activity-img" style="background:url(<?php echo $url; ?>);">     
                    
                
                </div><!-- activity img -->
                
                            <?php endif; // #has_post_thumbnail() ?>
            
            <div class="activity-all-content">
            	<div class="activity-content">
            	    	
            	
	            	<?php // Display post title ?>
	           		<h1><?php the_title(); ?><?php $video=get_post_meta($post->ID, "video-url", $single = true); ?></h1>

				
	           		<?php the_content(); ?>
	           		
	           		<?php 
	           		
	           		$logo = get_field("sponsor_logo");
	           		$sponsor_link = get_field("sponsor_link");
				$logo2 = get_field("sponsor_logo_2");
	           		$sponsor_link2 = get_field("sponsor_link_2");
	           		
	           		if( !empty($logo) && !empty($sponsor_link) ) { ?>
	           		
	           		<table style="padding-bottom:0px;">
	           			<tr>
	           				<td style="vertical-align:middle; padding-left:0px;">
	           					<h1 class="sponsor">Presented by</h1>
	           				</td>
	           				<td>
	           					<a href="<?php echo $sponsor_link; ?>" target="_blank"><img src="<?php echo $logo; ?>" height="100"/></a>
	           				</td>
	           			

<?php if( !empty($logo2) && !empty($sponsor_link2) ) { ?>
	           				<td style="vertical-align:middle; padding-left:0px;">
	           					<h1 class="sponsor">and</h1>
	           				</td>
	           				<td>
	           					<a href="<?php echo $sponsor_link2; ?>" target="_blank"><img src="<?php echo $logo2; ?>" height="100"/></a>
	           				</td>
	           		
	           		<?php } ?> <!-- end sponsor2 -->


</tr>
	           		</table>
	           		
	           		<?php } ?>
	           		
	           		<?php
    			
    				$friday = get_field("friday_time");
    				$saturday = get_field("saturday_time");
    				$sunday = get_field("sunday_time");
	
					if( !empty($friday) ) {

					echo "Friday, April 17: "."<span class='time'>".$friday."</span><br/>";
				
				}
				if( !empty($saturday) ) {

					echo "Saturday, April 18: "."<span class='time'>".$saturday."</span><br/>";
				
				}
				if( !empty($sunday) ) {

					echo "Sunday, April 19: "."<span class='time'>".$sunday."</span><br/>";
				
				}
    			
    			 ?>
<?php edit_post_link(__('Edit this post', 'shaken')); ?>



	           	</div> <!--content -->
	        
	      
	        		
	        </div><!-- all content -->
                	    
            
        
    </div><!-- single activity -->
        
    <?php endwhile; 
    
    else: ?>
    
    <img src="http://tuckfest.org/wp-content/uploads/2014/12/Music_ComingSoon.png" class="aligncenter" style="max-width:950px;">
    
    <?php endif; ?>


    
    </div><!-- box content -->
    </div><!-- #page -->
    
</div><!-- #sort -->

  </div><!-- grid -->
    

<?php get_footer('page'); ?>