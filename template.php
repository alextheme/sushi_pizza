<div class="lg100 page-blackcontent offset-header">
<div class="container">
	<?php if(!is_checkout()) :?>
	<div class="row">
		<section class="lg100 header-section offset-top p-top p-bottom"> 
			<h1 class="txt-center"><?php the_title(); ?></h1>
		</section>
	</div>
	<?php else: ?>
		<div class="row p-top">
		<section class="lg100 header-section"> 
			<h1 class="txt-center"><?php the_title(); ?></h1>
		</section>
	</div>
	<?php endif; ?>
	<div class="row row-margin">
		<div class="lg100 padding-15 txt-content subpagecontent padding-15">
			<div data-aos="fade-up" data-aos-delay="300">
				<?php the_content(); ?>
			</div>
		</div>
	</div>

</div>
</div>
		