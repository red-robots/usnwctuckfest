<?php
/* Single Post Template
 * @since   1.0
 * @alter   2.0
*/
if(!is_user_logged_in()){
	header("Location: http://www.tuckfest.org/");
	exit(0);
}
?>
<div class="wrap-popup">



<?php  if(have_posts()) : while(have_posts()) : the_post(); ?>  
<div id="page" >
<?php if(is_single(array('live-music','live-music-2','live-music-3','live-music-2-pm-2'))) { ?>
      <div id="live-music"> 
        <?php } ?>
			
<div class="box-image">
				<img src="<?php echo get_post_meta($post->ID, "title_image", $single = true); ?>" />
<div class="title-text"><?php the_title(); ?></div><div class="race-sponsor"><?php echo get_post_meta($post->ID, "sponsor", $single = true); ?></div>
			</div>
		<div class="box-content-popup post">
			<div class="post-media">
				<?php 
				// Display media (Video URL >> Wide embed >> Embed >> Image)
				if($vid_url):
		        	echo apply_filters( 'the_content', "[embed width='670']" . $vid_url . "[/embed]" );
		        elseif($vid_wide):
					echo $vid_wide; 
		        elseif($vid): 
					echo $vid;
				// new stuff
				elseif(get_post_meta($post->ID, 'use another poster', true)): { ?>
				<img src="<?php echo get_post_meta($post->ID, "use another poster", $single = true); ?>" class="box-title" />
                <?php }
				// end new stuff 
				elseif ( has_post_thumbnail() ):
		            the_post_thumbnail('col4', array( 'class' => 'feat-img' ) );
		        endif; ?>
		        
		        <?php 
		        // Display Gallery
		        if( has_post_format('gallery') ):
		        	get_template_part( 'includes/single-gallery'); 
		        endif; ?>
	        </div><!-- #post-media -->
	        
	        <?php if( $post_style != 'Minimal' ): ?>
    			
    			<div class="event-page-entry">
			        
			      
			        
			        <?php /* The Post */ ?>
			        
						<?php the_content(); ?>

                                               

	        	</div><!-- #entry -->
	        <?php endif; ?>

        	
        </div><!-- # $feature_container -->

<div class="other-info-popup">

<div class="title-popup">Date & Time:</div> <div class ="date-popup"><?php echo get_post_meta($post->ID, "date_time", $single = true); ?></div><br />
<?php $checkin=get_post_meta($post->ID, "checkin", $single = true); ?>
<?php if($checkin) { ?>
<div class="title-popup">Check-In:</div> <div class ="date-popup"><?php echo get_post_meta($post->ID, "checkin", $single = true); ?></div>
<?php } ?>
<?php $stage=get_post_meta($post->ID, "stage", $single = true); ?>
<?php if($stage) { ?>
<br />
      <div class="title-popup">Stage:</div><div class="date-popup"><?php echo get_post_meta($post->ID, "stage", $single = true); ?></div> 
        <?php } ?>

<?php if(is_single(array('wake-boarding-session','shred-ready-wake-boarding-session-830-pm','shred-ready-wake-boarding-session-830-pm-2' ))) : ?>

<?php elseif($stage) : ?>

<?php elseif(is_single('ultimate-dog-jumping-competition')) : ?>
      <div class="register-button"><a href="http://tuckfest.org/register/">Register</a></div>

<?php elseif(in_category(array('demos-land', 'water-demos'))) : ?>
      <div class="register-button"><a href="http://tuckfest.org/register/">Free Demo</a></div>

<?php elseif(is_single(array('carolina-raptor-center-exhibit-2','carolina-raptor-center-exhibit-12-pm','survival-101-7-pm','survival-101-1-pm','leave-no-trace-info-session-430-pm','kids-water-safety-demo-930-am','kids-water-safety-demo-230-pm','fly-fishing-workshop-3-pm'))) : ?>
      <div class="register-button"><a href="http://tuckfest.org/register/">Free Demo</a></div>

<?php else : ?>
      <div class="register-button"><a href="http://tuckfest.org/register/">Register</a></div>
 <?php endif; ?> 

</div>
        
	</div><!-- #page -->
<?php if(is_single(array('live-music','live-music-2','live-music-3','live-music-2-pm-2'))) { ?>
      </div> <!-- #live-music -->
        <?php } ?>

	<?php 
	/* The Minimal Post Style */ 
	if($post_style == 'Minimal'): ?>
		<div id="sidebar" class="minimal-content">
			 
			 <h1 class="post-title"><?php the_title(); ?></h1>
			 
			 <div class="entry">
				 <?php the_content(); ?>
				 
				 <?php wp_link_pages( array( 'before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
				 
				
		         
		        
	         </div>
	         
		</div><!-- #sidebar -->
	<?php endif; ?>
	
    <?php /* End: loop */
    endwhile; endif; ?>
    

</div><!-- #wrap -->