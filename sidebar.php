<?php 
/**
 * Sidebar.php
 *
 */ ?>
<div id="sidebar">

	<?php if(is_single()){ ?>
		<?php dynamic_sidebar( 'post-sidebar' ); ?>
	<?php //} else if(is_page_template('template-unique-sidebar.php')) { ?>
		<?php //dynamic_sidebar( 'unique-sidebar' ); ?>
	<?php //} else if(is_archive() || is_home()) { ?>
		<?php //dynamic_sidebar( 'gallery-sidebar' ); ?>
	<?php } else { ?>
    
</div><!-- #primary .widget-area -->