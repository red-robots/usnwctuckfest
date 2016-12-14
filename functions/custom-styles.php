<?php function shaken_get_custom_styles(){ ?>
<style type="text/css">
<?php $background = of_get_option('header_color');
if( !empty($background['image']) || !empty($background['color']) ): ?>
    #header{
    	<?php if ($background['image']) :
    			echo "background: url($background[image]) $background[position] $background[repeat] $background[attachment] $background[color];";
    			echo 'border:none 0;';
    		elseif($background['color']):
    			echo "background: $background[color];";
    			echo 'border:none 0;';
    		endif;	
        ?>
    }
<?php endif; ?>
<?php $background = of_get_option('footer_color');
if( !empty($background['image']) || !empty($background['color']) ): ?>
    #footer{
    	<?php if ($background['image']) :
    			echo "background: url($background[image]) $background[position] $background[repeat] $background[attachment] $background[color];";
    		elseif($background['color']):
    			echo "background: $background[color];";
    		endif;	
        ?>
    }
<?php endif; ?>
<?php 
$background = of_get_option('container_color');
if( !empty($background['image']) || !empty($background['color']) ): ?> 
    .box-content, .page-content, .flickr_badge_image, .jta-tweet-profile-image, .post-thumb{
    	<?php if ($background['image']) :
    			echo "background: url($background[image]) $background[position] $background[repeat] $background[attachment] $background[color];";
    		elseif($background['color']):
    			echo "background: $background[color];";
    		endif;	
        ?>
    }
    
    .postmetadata, ol.commentlist li{
    	border: 1px solid rgba(0, 0, 0, 0.12);
    	background: none;
    }
<?php endif; ?>
<?php // ===============================
//				Links
// ================================ ?>
<?php if(of_get_option('link_color')): ?>
    a, a.more-link p, #sidebar a{
    	color:<?php echo of_get_option('link_color'); ?>;
    }
<?php endif; ?>
<?php if(of_get_option('footer_link_color')): ?>
    #footer a{
    	color:<?php echo of_get_option('footer_link_color'); ?>;
    }
<?php endif; ?>
<?php // ===============================
//				Text Color
// ================================ ?>
<?php if(of_get_option('headline_color')): ?>
    h1, h1 a, h2, h2 a, .box h2 a, .box h2, #page h2, #full-page h2, h3, h3 a, #page h3, #sidebar h3 a, .widget ul h3 a, .widget .cat-post-item h3 a, .recent-posts h3 a, h4, h5, h6{
    	color:<?php echo of_get_option('headline_color'); ?>;
    	text-shadow:none;
    }
<?php endif; ?>
<?php if(of_get_option('body_text_color')): ?>
    body, blockquote, #single .post p, #single .post, .entry, .postmetadata, .postmetadata a, ol.commentlist li, .author-name{
    	color:<?php echo of_get_option('body_text_color'); ?>;
    }
<?php endif; ?>
<?php if(of_get_option('small_color')): ?>
    .post-info p, #archives-page .box .post-info p, .jta-tweet-timestamp, cite, .box, .box blockquote, .comment-date, .reply a{
    	color:<?php echo of_get_option('small_color'); ?>;
    }
<?php endif; ?>
<?php if(of_get_option('footer_text_color')): ?>
    #footer{
    	color:<?php echo of_get_option('footer_text_color'); ?>;
    }
<?php endif; ?>
<?php // ===============================
//				Header
// ================================ ?>
<?php if(of_get_option('logo_title_color')): ?>
    #logo a, a #logo, #logo a:hover{
    	color:<?php echo of_get_option('logo_title_color'); ?>;
    }
<?php endif; ?>
<?php if(of_get_option('logo_tagline_color')): ?>
    #site-description{
    	color:<?php echo of_get_option('logo_tagline_color'); ?>;
    }
<?php endif; ?>
<?php if(of_get_option('nav_text_color')): ?>
    .header-nav li a, .header-nav li.current-menu-item li a, .header-nav li.current_page_ancestor li a{
    	color:<?php echo of_get_option('nav_text_color'); ?>;
    }
<?php endif; ?>
<?php if(of_get_option('nav_special_text_color')): ?>
    .header-nav li a:hover, .header-nav li.current-menu-item li a:hover, .header-nav li.current-menu-item a, .header-nav li.current_page_ancestor a, .header-nav li.current_page_ancestor li a:hover, .header-nav li.current_page_ancestor li.current-menu-item a{
        color:<?php echo of_get_option('nav_special_text_color'); ?>;
    }
<?php endif; ?>
<?php $background = of_get_option('submenu_bg');
if( !empty($background['image']) || !empty($background['color']) ): ?>
    .header-nav ul ul{
    	<?php if ($background['image']) :
    			echo "background: url($background[image]) $background[position] $background[repeat] $background[attachment] $background[color];";
    		elseif($background['color']):
    			echo "background: $background[color];";
    		endif;	
        ?>
    }
<?php endif; ?>
<?php $background = of_get_option('social_bg');
if( !empty($background['image']) || !empty($background['color']) ): ?>
    #social-networks{
    	<?php if ($background['image']) :
    			echo "background: url($background[image]) $background[position] $background[repeat] $background[attachment] $background[color];";
    			echo "-webkit-box-shadow:none;
    			-moz-box-shadow:none;
    			-o-box-shadow:none;
    			box-shadow:none;";
    		elseif($background['color']):
    			echo "background: $background[color];";
    			echo "-webkit-box-shadow:none;
    			-moz-box-shadow:none;
    			-o-box-shadow:none;
    			box-shadow:none;";
    		endif;	
        ?>
    }
<?php endif; ?>
<?php // ===============================
//				Misc.
// ================================ ?>

<?php $background = of_get_option('widget_title_bg');
if( !empty($background['image']) || !empty($background['color']) || of_get_option('widget_title_color')): ?>
    h3.widget-title{
    	<?php if ($background['image']) :
    			echo "background: url($background[image]) $background[position] $background[repeat] $background[attachment] $background[color];";
    			echo 'text-shadow:none;';
    		elseif($background['color']):
    			echo "background: $background[color];";
    			echo 'text-shadow:none;';
    		endif;	
        ?>
        color:<?php echo of_get_option('widget_title_color'); ?>;
    }
<?php endif; ?>
<?php // ======== Font families ========= ?>
<?php if(of_get_option('header_style') == 'serif') { ?>
    h1, h2, h3, h4, h5, h6, #logo, #logo a, #site-description, .postmetadata, .postmetadata strong{
        font-family:Georgia, "Times New Roman", Times, serif;
    }
    .wf-active .postmetadata{
        font-size:14px;
    }
<?php } ?>
<?php if(of_get_option('content_style') == 'serif') { ?>
    body, input, textarea, .header-nav ul ul li a, .header-nav li a{
        font-family:Georgia, "Times New Roman", Times, serif;
    }
    .wf-active .header-nav li{
    	font-size:18px;
    }
<?php } ?>
<?php echo get_option('shaken_custom_styles'); ?>
</style>
<?php } ?>