<?php 
//
///////////    Micro Brews and Zipline and Dine    < -----------
//
?>
<?php  // If page is Zipline and Dine show Microbrews box?>
    <?php if ( is_single( 'zipline-dine-2013-schedule') ) { ?>
<div class="sidebar-item box-content">
    <h3 class="widget-title">Micro Brews Cruise</h3>
            <p><a href="<?php bloginfo(url); ?>/microbrews-cruise-2013-schedule/"><img src="<?php bloginfo('template_url'); ?>/images/brews-cruise.jpg" align="left" width="100px" height="100px" /></a><a href="<?php bloginfo(url); ?>/microbrews-cruise-2013-schedule/">Sunset paddle along the Catawba River followed by a fireside dinner with craft beer tastings.</a></p>
    </div><!-- #sidebar item -->
<?php } // End Microbrews box ?>


    <?php  // If page is Microbrews show Zipline and Dine box ?>
    <?php if ( is_single( 'microbrews-cruise-2013-schedule') ) { ?>
    <div class="sidebar-item box-content">
    <h3 class="widget-title">Zipline &amp; Dine</h3>
            <p><a href='http://usnwc.org/zipline-dine-2013-schedule/'><img src="<?php bloginfo('template_url'); ?>/images/ziplineanddine.jpg" align="left" width="100px" height="100px" /></a><a href='http://usnwc.org/zipline-dine-2013-schedule/'>Zip-line excursion from the south ridge of our facility to the Catawba River followed by a fireside dinner with craft beer and wine.</a></p>
    </div><!-- #sidebar item -->  
<?php } // end Zipline and Dine box ?>