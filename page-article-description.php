<?php
/*
 * Template Name: Article w/Description
 * Author: Fritz Healy
 * Version: 1.0
 */

get_header(); ?>


<?php get_sidebar('banner');?>
<header class="post">
	<h1><?php echo get_the_title();?></h1>
</header>
<?php if(have_posts()){
	the_post();?>
	<article class="post <?php echo $post->post_name;?>">
		<?php the_content();?>
	</article>
<?php }?>
<?php
/*
 * The call to get_template_part gets the template function display_loop_tile
 * which queries the posts based on the supplied args
 * The arguments for the query are supplied as arguments for the function.
 * The loop cleans up and resets the query after it is called
 */
get_template_part('loop','article');
$categories=get_field("categories");
if($categories){
	display_loop_article(array('category__in'=>$categories,'post_type'=>'post','order'=>'ASC','posts_per_page'=>'-1'));
}
?>

<?php get_footer(); ?>