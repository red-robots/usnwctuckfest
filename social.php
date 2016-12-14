<div class="sidebar-item box-content">
<h3 class="widget-title">Share</h3>
 <!-- Facebook share -->
 <div class="share"><div class="fb-like" data-href="<?php the_permalink() ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></div>
<!-- Google +1 -->
<div class="share"><g:plusone></g:plusone></div>

<!-- Tweet share -->
<div class="share"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>

<!-- Pintrest share -->
<div class="share">
<?php // ANH add pinterest 
if ( has_post_thumbnail() ) { $imgdata = get_the_post_thumbnail($post->ID, 'large');
$imgdata = explode('=',$imgdata);
$src = explode('"',$imgdata[3]);
$title = explode('"',$imgdata[6]);
?>
<a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo $src[1]; ?>&description=<?php the_title(); ?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
 <?php unset($src); unset($title);
 } // end add pinterest ?>

</div><!-- pintrest  -->
</div><!-- sidebar-item  -->