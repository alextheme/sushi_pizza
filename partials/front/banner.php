<?php
	$id = -1;
	if (isset($args) && is_array($args) && array_key_exists( 'id', $args ) ) {
		$id = $args['id'];
	}

	$blocked = is_blocked();

?>
<span class="blocked_shops" id="blocked_shops"><?php echo $blocked; ?></span>
<div class="row row-margin-30">
<div class="lg66 md100 pagebaner padding-30">
	<div class="slider-home">
		<?php $x = 0; while ( have_rows('banner_grafika', $id) ): $x++; the_row(); $hsimage = get_sub_field('grafika'); ?>
		<div class="slide">
			<div class="lg100 pb-img corner-radius-img coverimg">
				<?php 		
					echo wp_get_attachment_image( $hsimage, 'large' ); 				
				?>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
</div>
	<section class="lg33 md100 xs100 right-banner-info padding-30">
		<div class="row">
		<?php if(!wp_is_mobile()):?>
		<div class="lg100 md50 xs100 bckg-info txt-white corner-radius">
			<h3><?php echo pll__( 'Czas pracy' ); ?>:</h3>
			<span class="work-time"><?php the_field('czas_pracy',$id);?></span>
			<div class="lg100 info-tabs text-center">
				<span class="info-tab"><img src="/wp-content/uploads/2022/10/bag2-1.svg" alt=""> <?php the_field('min_zam', $id);?></span>
				<span class="info-tab"><img src="/wp-content/uploads/2022/10/timer1-1.svg" alt=""><?php the_field('czas_dostawy', $id);?></span>
				<span class="lg100 info-tab"><img src="/wp-content/uploads/2022/10/Vector-2-2.svg" alt=""> <?php the_field('przewoz', $id);?></span>
				<span id="addr1" class="info-tab"><img src="/wp-content/uploads/2022/10/location-2.svg" alt=""><?php the_field('adres', $id);?></span>
			</div>
		</div>
		<?php endif; ?>
		<div class="lg100 md50 xs100 p-top-30 why-we">
			<h3 class="txt-white"><?php echo pll__( 'Dlaczego my' ); ?>?</h3>
			<div class="lg100 d-flex align-center justify-spaceb why-we-content">
				<div class="lg30 xs33">
					<img src="<?php the_field('dlaczego_my_obraz1', $id);?>" alt="Najwyższa jakość">
				</div>
				<div class="lg30 xs33">
					<img src="<?php the_field('dlaczego_my_obraz2', $id);?>" alt="Najlepsze ryby">
				</div>
				<div class="lg30 xs33">
					<img src="<?php the_field('dlaczego_my_obraz3', $id);?>" alt="Świeże produkty">
				</div>
			</div>
		</div>
		</div>
	</section>
</div>