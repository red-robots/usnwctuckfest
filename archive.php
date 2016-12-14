<?php
/* Archive Template
 *
 * @since   1.0
 * @alter   1.6
*/

if(!is_user_logged_in()){
	header("Location: http://www.tuckfest.org/");
	exit(0);
}
get_header('archive'); ?>

<div id="grid">
<?php
/* Run the loop to output the posts.
* If you want to overload this in a child theme then include a file
* called loop-archive.php and that will be used instead.
*/
get_template_part( 'loop', 'archive' );
?>
</div><!-- #grid -->
<?php get_footer('archive'); ?>