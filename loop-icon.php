<?php
/*
 * @author: Fritz Healy
 * NOTE: Working from existing templates
 *
 * This function is the loop to display the queried posts as icons on the usnwc site
 * The $args value is passed from the template as the argument used for the query
 */
function display_loop_icon($args){
	/*
	 * Queries the posts with WP_Query and set up The Loop
	 */
	$query=new WP_Query($args);
	if($query->have_posts()){?>
		<div class="icon container <?php 
			if($args['post_type']&&$args['post_type']==='page'){
				echo strtolower(preg_replace("/\s+/","-",preg_replace("/&#[0-9]+;/"
				,"",get_the_title($args['post_parent']))));
			}
			elseif($args['post_type']&&$args['post_type']==='post'){
				if($args['cat']){
					for($i=0;$i<count($args['cat']);$i++){
						echo " ".strtolower(preg_replace("/\s+/","-",preg_replace("/&#[0-9]+;/"
						,"",get_the_category_by_ID($args['cat'][$i]))));
					}
				}
			}
		?>">
		<?php //The Loop
		while($query->have_posts()){
			$query->the_post();
			
			/*
			 * Get all of the page options using get_post_meta and get_field
			 */
     	   	$box_size = get_post_meta($query->post->ID, 'soy_box_size', true);
			/*
			 * Set the embed size for videos and the size for featured images using the
			 * box size gathered from the page
			 */
   		    if( $box_size === 'Medium (485px)' ){
   	    	    $my_size = 'size-Medium';
   	    	} else if( $box_size === 'Large (660px)' ){
   	       		$my_size = 'size-Large';
   	     	} else if( $box_size === 'Tiny (135px)' ){
   	        	$my_size = 'size-Tiny';
   	     	} else { 
   	     		$my_size = 'size-Default';
   	     	}
   	     	/*
   	     	 * Display a tile for the post only if there is some image to display
   	     	 */
        	if(has_post_thumbnail()){
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
		    		<a href="<?php the_permalink(); ?>"><div class="icon slideable <?php 
		    			echo $category_classes . $my_size. ' '.$query->post->post_name; 
	    			?>">
    			        <?php
       	  				// Display featured image using the url of the featured image instead of 
       	 				//a thumbnail to avoid formatting issues with inline styling ?>
	       			    <img class="<?php echo $my_size?>" src="<?php echo str_replace(home_url(),"",wp_get_attachment_image_src(get_post_thumbnail_id($query->post->ID),array(115,115))[0]);?>">
    	       			<header>
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