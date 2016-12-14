<?php
/*
 * Normal Page Template
*/

get_header('page'); ?>

<div class="banner">
    <?php $slider=get_field("soliloquy");
	if(function_exists('soliloquy_slider')&&$slider) soliloquy_slider($slider->ID); 
	?>
</div>
<header class="post page-content">
	<h1><?php echo get_the_title();?></h1>
</header>
<?php if(have_posts()){
	the_post();?>
<article class="post <?php echo $post->post_name;?>">
	<?php echo the_content();?>
</article>
<?php }
get_footer('page'); ?>