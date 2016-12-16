<?php 
/**
 * Sidebar-banner.php
 *
 */ ?>
<div id="banner">
	<?php $flexslider = get_field("flexslider");
	if($flexslider):?>
		<div class="flexslider">
			<ul class="slides">
				<?php foreach($flexslider as $row):?>
					<li>
						<?php if(strcmp($row['video_or_image'],"video")===0&&$row['video']):?>
							<div class="iframe-wrapper">
								<iframe src="<?php echo $row['video'];?>" allowfullscreen="true" frameborder="0"></iframe>
							</div><!--.iframe-wrapper-->
						<?php elseif(strcmp($row['video_or_image'],"image")===0&&$row['link']&&$row['image']): ?>
							<div class="image-wrapper" style="background-image: url(<?php echo $row['image']['url'];?>);">
								<a href="<?php echo $row['link'];?>" <?php if($row['target']):echo 'target="_blank"'; endif;?>>
									<img src="<?php echo $row['image']['url'];?>" alt="<?php echo $row['image']['alt'];?>">
								</a>
							</div><!--.image-wrapper-->
						<?php elseif(strcmp($row['video_or_image'],"image")===0&&$row['image']): ?>
							<div class="image-wrapper" style="background-image: url(<?php echo $row['image']['url'];?>);">
								<img src="<?php echo $row['image']['url'];?>" alt="<?php echo $row['image']['alt'];?>">
							</div><!--.image-wrapper-->
						<?php endif;?>
					</li>
				<?php endforeach;?>
			</ul>
		</div>
	<?php endif;?>
</div>
