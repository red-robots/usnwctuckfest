<?php 
get_header(); ?>

<?php $post = get_post('1878');
setup_postdata($post);
get_sidebar('banner');?>

<?php get_footer(); ?>