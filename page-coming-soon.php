<?php
/*
 * Template Name: Coming Soon
 * Author: Fritz Healy
 * Version: 1.0
 */

get_header(); ?>

<div class="banner post">
    <?php $slider=get_field("soliloquy");
	if(function_exists('soliloquy_slider')&&$slider) soliloquy_slider($slider->ID); 
	?>
</div>

<?php get_footer(); ?>