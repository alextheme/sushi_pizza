<?php
/* Template Name: Dostawa */

get_header();
?>

<div class="lg100 page-blackcontent offset-header offset-bottom">
<div class="container">
	<div class="row">
		<section class="lg100 header-section offset-top p-top"> 
			<h1 class="txt-center"><?php the_title(); ?></h1>
		</section>
	</div>
	<div class="row maps" id="wrapper_map" data-map-areas="<?= esc_attr(json_encode(get_field('shipping', 'options'))); ?>">
		<div class="lg100 map" id="mapz"></div>
	</div>
</div>
</div>

<?php get_footer();?>
