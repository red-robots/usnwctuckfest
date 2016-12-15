<?php
/*
 * Template Name: Top Level
 * Author: Fritz Healy
 * Version: 1.0
 */

get_header(); ?>


<?php get_sidebar('banner');?>
<?php if(have_posts()){
	the_post();
	if($post->post_content){?>
		<header class="post tile-page page-content">
			<h1><?php echo get_the_title();?></h1>
		</header>
		<section class="post <?php echo $post->post_name;?>">
			<?php echo the_content();?>
		</section>
	<?php }
	else { ?>
		<header class="post tile-page no-content">
			<h1><?php echo get_the_title();?></h1>
		</header>
	<?php }
}
/*
 * The call to get_template_part gets the template function display_loop_tile
 * which queries the posts based on the supplied args
 * The arguments for the query are supplied as arguments for the function.
 * The loop cleans up and resets the query after it is called
 */
 get_template_part('loop','tile');
 display_loop_tile(array('post_parent'=>$post->ID,'post_type'=>'page','order'=>'ASC','posts_per_page'=>'-1'));
?>

<?php get_footer(); ?>