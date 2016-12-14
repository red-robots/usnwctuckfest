<?php
/*
 * @author: Fritz Healy
 * NOTE: Working from existing templates
 *
 * This function is the generic loop to display the queried posts as tiles on the tuckfest site
 * The $args value is passed from the template as the argument used for the query
 * NOTE: This function is faster than the recursive loop-recursive but the two can be 
 * used interchangably for all intensive purposes
 */
function display_loop_tile($args){
	/*
	 * Queries the posts with WP_Query and set up The Loop
	 */
	$query=new WP_Query($args);
	if($query->have_posts()){?>
		<div class="slideable tile container <?php 
			if($args['post_type']&&$args['post_type']==='page'){
				echo strtolower(preg_replace("/\s+/","-",preg_replace("/&#[0-9]+;/"
				,"",get_the_title($args['post_parent']))));
			}
			elseif($args['post_type']&&$args['post_type']==='post'){
				if($args['category__in']){
					for($i=0;$i<count($args['category__in']);$i++){
						echo " ".strtolower(preg_replace("/\s+/","-",preg_replace("/&#[0-9]+;/"
						,"",get_the_category_by_ID($args['category__in'][$i]))));
					}
				}
			}
		?>">
   	   	<?php 
		//The Loop
		while($query->have_posts()){
			$query->the_post();
			
			/*
			 * Get all of the page options using get_post_meta and get_field
			 */
    	    $embed_code = get_post_meta($query->post->ID, 'soy_vid', true);
    	    $vid_url = get_post_meta($query->post->ID, 'soy_vid_url', true);
   	 	    $force_feat_img = get_post_meta($query->post->ID, 'soy_hide_vid', true);
     	   	$show_title = get_post_meta($query->post->ID, 'soy_show_title', true);
     	   	$show_desc = get_post_meta($query->post->ID, 'soy_show_desc', true);
     	   	$box_size = get_field('box_size');
			/*
			 * Set the embed size for videos and the size for featured images using the
			 * box size gathered from the page
			 */
   		    if(strcmp($box_size,'medium')===0){
   			    $my_size = 'size-Medium';
       		    $embed_size = '495';
	        } else if(strcmp($box_size,'large')===0){
    		    $my_size = 'size-Large';
   	    		$embed_size = '670';
    		} else if(strcmp($box_size,'tiny')===0){
 	  	        $my_size = 'size-Tiny';
       		   	$embed_size = '145';
 	    	} else { 
   		   	 	$my_size = 'size-Default';
       			$embed_size = '320';
       		}
   	     	/*
   	     	 * Display a tile for the post only if there is some image/video to display
   	     	 */
        	if($embed_code||$vid_url||(has_post_format('gallery')&&!$_force_feat_img)||has_post_thumbnail()){
	        	/*
    	    	 * Create a loop to get all the category names 
        		 */
        		$category_classes = '';
        		foreach((get_the_category()) as $category){
	       			$category_classes .= $category->category_nicename . ' '; 
	    		} 
	    		/*
	    		 * Set up an <a> tag to link the whole tile div to the post permalink
	   			 * Assign class names including tile, the category, size, and post name of the 
	    		 * tile
	    		 */
	    		 if(get_field('exclude')!=="yes"){
	    		?>
	    			<a href="<?php the_permalink(); ?>"><div class="tile slideable <?php 
	    				echo $category_classes . $my_size. ' '.$query->post->post_name; 
	    			?>">
	    			        <?php
	        			    // Display video if available
    	    	   	 		if( ( $embed_code || $vid_url ) && !$force_feat_img ){
        	   		 			if( $vid_url ){ 
            						echo '<div class="vid-container">'.apply_filters('the_content', '[embed width="' . $embed_size . '"]' . $vid_url . '[/embed]').'</div>';
            					} 
            					else { 
            						echo '<div class="vid-container">'.$embed_code.'</div>';
 	        		   			} 
    	    		    	// Display gallery
        	   	 			} elseif( has_post_format( 'gallery' ) && !$force_feat_img ){
           			 			get_template_part( 'includes/loop-gallery' );
          	  				/* Display featured image using the url of the featured image instead of 
          	 				 * a thumbnail to avoid formatting issues with inline styling
          	 				 * Display div with event complete image of apropriate size if event complete
          	 				 * with featured image in background 
          	 				 */
            				} elseif( has_post_thumbnail() ){  
           						$img_url=wp_get_attachment_image_src(get_post_thumbnail_id($query->post->ID),'tile')[0]; //absolute path
            					$img_url_rel=str_replace(home_url(),"",$img_url); //relative path ?>                 
 	              					<img class="<?php echo $my_size;?>" src="<?php echo $img_url_rel;?>">
             				<?php } ?>
             				<header>
             					<img src="/wp-content/uploads/2016/11/W_button.png">
             					<h2><?php the_title(); ?></h2>
             				</header>
   	 				</div></a>   	
				<?php }
			}
		} //end of while ?>
		</div>
	<?php } //end of if have posts 
	else {
		//if there are no posts then say nothing
	}
	wp_reset_postdata();
}
?>