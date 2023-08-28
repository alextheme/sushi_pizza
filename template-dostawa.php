<?php
	get_header();
	/* Template Name: Dostawa */
?>

<div class="lg100 page-blackcontent offset-header offset-bottom">
<div class="container">
	<div class="row">
		<section class="lg100 header-section offset-top p-top"> 
			<h1 class="txt-center"><?php the_title(); ?></h1>
		</section>
	</div>
	<div class="row maps">
		<span class="zawalnaspan">Miasto Lubin</span>
		<div class="lg100 map zawalna" id="mapz">
			
		</div>
	</div>
</div>
</div>


<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo 'api key'?>&v=weekly" defer></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="/wp-content/themes/americansushi/js/maps.js"></script>

<?php get_footer();?>   
