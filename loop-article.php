<?php 
/*
 * @author: Fritz Healy
 * NOTE: Working from existing templates
 *
 * This function is the loop to display the content of the queried posts as articles on 
 * the usnwc site.
 * The header given to the section of articles is either the title of the page or "upgrades"
 * if upgrades are queried this is specific to the usnwc requirements. 
 * The $args value is passed from the template as the argument used for the query
 */
function display_loop_article($args){
	/*
	 * Queries the posts with WP_Query and set up The Loop
	 */
	$query=new WP_Query($args);
	if($query->have_posts()){
		/*
		 * Articles will be held within a section container
		 * The header of the section will be either the name of the page that called this 
		 * fucntion or "upgrades" if upgrades were queried 
		 */
		?>
		<section class="post container <?php 
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
			<?php while($query->have_posts()){
				$query->the_post();
				/*
				 * Get the video code if any
				 */
   	   			$video=get_field('video'); 
       			/*
    			 * Create a loop to get all the category names 
   				 */
   				$category_classes = '';
   				foreach(get_the_category() as $category){
   					$category_classes .= $category->category_nicename . ' '; 
   				} 
   				/* 
   				 * The article will contain all of the content of the queried post
   				 * Assign category names and post name to article class
					*/
				$centered = get_field("centered");
   				?>
   				<a name="<?php echo $query->post->post_name;?>"></a>
   				<article class="row <?php echo $category_classes.$query->post->post_name;?> <?php if($centered && strcmp($centered,"yes")===0) echo "centered";?>">
 	  				<?php 
 	  				/*
 	  				 * Display the video or post_thumbnail featured image (if any)
 	  				 */
   					if(has_post_thumbnail() && !($centered && strcmp($centered,"yes")===0)){ 
   						$img_url=str_replace(home_url(),"",wp_get_attachment_image_src(get_post_thumbnail_id($query->post->ID),array(200,267))[0]);
   						/*
   						Commenting and if($img_url) to remove video from thumbnails
   						Remove the commenting and the following if($img_url) if you want video with shown thumbnails
   						if($video){ ?>
							<figure class="video" style="background-image:url(<?php echo $img_url;?>)">
								<a href="<?php echo $video; ?>" rel="video">
									<img src="/wp-content/uploads/2014/11/play_button_smaller.png">
								</a> 
							</figure>  
   						<?php }
   						else */
   						if($img_url){ ?>
   							<figure class="featured_image">
 	  							<img class="<?php echo $my_size?>" src="<?php echo $img_url;?>">
 							</figure>
   						<?php } 
   					} 
   					/*
   					 * Display the title of the post and the content
   					 */
   					?>
   					<section class="copy">
	   					<header>
   							<h2><?php the_title(); ?></h2>
   						</header>
   						<?php the_content();

						//display the option to edit the post if logged in
						$logo = get_field("sponsor_logo");
		           		$sponsor_link = get_field("sponsor_link");
						$logo2 = get_field("sponsor_logo_2");
	        	   		$sponsor_link2 = get_field("sponsor_link_2");
	
		           		if( !empty($logo) && !empty($sponsor_link) ) { ?>           		
			           		<table style="padding-bottom:0px;">
	    		       			<tr>
	           						<td style="vertical-align:middle; padding-left:0px;">
	           							<h3 class="sponsor">Presented by</h3>
	           						</td>
	           						<td>
	           							<a href="<?php echo $sponsor_link; ?>" target="_blank"><img src="<?php echo $logo; ?>"/></a>
	           						</td>
									<?php if( !empty($logo2) && !empty($sponsor_link2) ) { ?>
	           							<td style="vertical-align:middle; padding-left:0px;">
	           								<h3 class="sponsor">and</h3>
	           							</td>
	           							<td>
	           								<a href="<?php echo $sponsor_link2; ?>" target="_blank"><img src="<?php echo $logo2; ?>"/></a>
	           							</td>
		           					<?php } ?>
								</tr>
	           				</table>
	           			<?php } ?>
						<?php $below_schedule_section = get_field("below_schedule_section");
    					if($below_schedule_section) echo $below_schedule_section;
						edit_post_link(__('Edit this post', 'shaken')); ?>
					</section>
   				</article>
			<?php } //end of while ?>
		</section>
	<?php }//end of if have posts
	wp_reset_postdata();
}?>
