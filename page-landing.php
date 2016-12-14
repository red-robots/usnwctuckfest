<?php
/* 
 * Template Name: Landing
 * @version 1.0
 * @author Fritz Healy
 */

get_header("simpleHead"); 

echo $post->post_content; ?>

<script src="<?php echo get_template_directory_uri(); ?>/js/centerLanding.js"></script>

<?php get_footer("simpleFoot"); ?>