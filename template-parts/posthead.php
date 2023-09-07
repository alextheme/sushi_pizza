<article <?php post_class('cf'); ?> id="post-<?php the_ID()?>" data-post-id="<?php the_ID()?>">
	<?php if( get_the_post_thumbnail_url(null,'post-large') ) { ?>
	<div
		class="post_image"
		style="background-image: url('<?php echo get_the_post_thumbnail_url(null,'post-large'); ?>')">
	</div>
	<?php } else { ?>
		<div class="no_image_margin_top" style="margin-top: 100px;"></div>
	<?php } ?>
