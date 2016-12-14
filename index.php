<?php
/* Frontpage (Blog)
 *
 * @since   1.0
 * @alter   1.6
 */

get_header(); ?>

<div class="banner">
    <?php if(function_exists('soliloquy_slider')) soliloquy_slider('16'); 	?>
</div>
    
<?php get_footer(); ?>