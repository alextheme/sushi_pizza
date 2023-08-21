<?php
	get_header();
	/* Template Name: Kontakt */
?>

<div class="lg100 page-blackcontent contact offset-header offset-bottom">
<div class="container">
	<div class="row">
		<section class="lg100 header-section offset-top p-top p-bottom"> 
			<h1 class="txt-center"><?php echo pll__( 'Masz jakieś pytania lub propozycje?' ); ?></h1>
		</section>
	</div>
	<div class="row row-margin-30 contact-data">
		<div class="lg40 xs100 padding-30">
			<div class="lg100 xs100 bckg-info bckg-info2  corner-radius">
				<h2>Miasto Lubin</h2>
				<span class="work-time"><?php the_field('czas_pracy');?></span>
				<div class="lg100 info-tabs row justify-spaceb">
					<span class="info-tab"><img src="/wp-content/uploads/2022/10/bag2-1.svg" alt=""> <?php the_field('min_zam');?></span>
					<span class="info-tab"><img src="/wp-content/uploads/2022/10/Vector-2-2.svg" alt=""> <?php the_field('przewoz');?></span>
					<span class="info-tab"><img src="/wp-content/uploads/2022/10/timer1-1.svg" alt=""><?php the_field('czas_dostawy');?></span>
					<span class="lg100 info-tab"><img src="/wp-content/uploads/2022/10/location-2.svg" alt=""><?php the_field('adres');?></span>
					<span class="lg100 info-tab"><a href="mailto:<?php the_field('email');?>"><img src="/wp-content/uploads/2022/10/Vector-2.svg" alt=""><?php the_field('email');?></a></span>
					<span class="lg100 info-tab"><a href="tel:<?php the_field('telefon');?>"><img src="/wp-content/uploads/2022/10/sms-1.svg" alt=""><?php the_field('telefon');?></a></span>
				</div>
			</div>
		</div>
		<section class="lg60 xs100 contact-form padding-30">
			<?php the_field('formularz');?>
		</section>
	</div>
</div>
</div>

<?php get_footer();?>   
