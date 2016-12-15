 <?php
/*
 * Template Name: Icons
*/


get_header('page'); ?>


 <?php get_sidebar('banner');?>
<?php if(have_posts()){
	the_post();
	if($post->post_content){?>
		<header class="post icon-page page-content">
			<h1><?php echo get_the_title();?></h1>
		</header>
		<section class="post <?php echo $post->post_name;?>">
			<?php echo the_content();?>
		</section>
	<?php }
	else { ?>
		<header class="post icon-page no-content">
			<h1><?php echo get_the_title();?></h1>
		</header>
	<?php }
}
/*
 * The call to get_template_part gets the template function display_loop_icon
 * which queries the posts based on the supplied args
 * The arguments for the query are supplied as arguments for the function.
 * The loop cleans up and resets the query after it is called
 */
 get_template_part('loop','icon');
 display_loop_icon(array('post_parent'=>$post->ID,'post_type'=>'page','order'=>'ASC','posts_per_page'=>'-1'));
?>
<script src="<?php echo get_template_directory_uri(); ?>/js/sortIcon.js?"></script>
<?php get_footer(); ?>