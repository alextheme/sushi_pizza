<?php
get_header();
/* Template Name: Kontakt */

global $post;

$id = $post->ID;
if ( isset($args) && is_array($args) && array_key_exists( 'id', $args ) ) {
	$id = $args['id'];
}

$acf_option = get_field('kontacts-' . pll_current_language(), 'options');

$blocked = is_blocked();
?>

<div class="lg100 page-blackcontent contact offset-header offset-bottom contact_template">
<div class="container">
	<div class="row">
		<section class="lg100 header-section offset-top p-top p-bottom"> 
			<h1 class="txt-center"><?php echo pll__( 'Masz jakieś pytania lub propozycje?' ); ?></h1>
		</section>
	</div>
	<div class="row row-margin-30 contact-data">
		<div class="lg40 xs100 padding-30">
			<div style="display: none" class="lg100 xs100 bckg-info bckg-info2  corner-radius">
				<h2>Miasto Lubin</h2>
				<span class="work-time"><?php the_field('czas_pracy');?></span>
				<div class="lg100 info-tabs row justify-spaceb">
					<span class="info-tab"><img src="/wp-content/uploads/2022/10/bag2-1.svg" alt="" loading="lazy"> <?php the_field('min_zam');?></span>
					<span class="info-tab"><img src="/wp-content/uploads/2022/10/Vector-2-2.svg" alt="" loading="lazy"> <?php the_field('przewoz');?></span>
					<span class="info-tab"><img src="/wp-content/uploads/2022/10/timer1-1.svg" alt="" loading="lazy"><?php the_field('czas_dostawy');?></span>
					<span class="lg100 info-tab"><img src="/wp-content/uploads/2022/10/location-2.svg" alt="" loading="lazy"><?php the_field('adres');?></span>
					<span class="lg100 info-tab"><a href="mailto:<?php the_field('email');?>"><img src="/wp-content/uploads/2022/10/Vector-2.svg" alt="" loading="lazy"><?php the_field('email');?></a></span>
					<span class="lg100 info-tab"><a href="tel:<?php the_field('telefon');?>"><img src="/wp-content/uploads/2022/10/sms-1.svg" alt="" loading="lazy"><?php the_field('telefon');?></a></span>
				</div>
			</div>

			<section class="hero_box__schedule contact_template__schedule md100 xs100 right-banner-info ">
				<div class="row">
					<div class="hero_box__schedule_w lg100 md50 xs100 bckg-info">

						<h3 class="hero_box__schedule_title"><?= $acf_option['title'] ?></h3>
						<span class="hero_box__schedule_time work-time"><?= $acf_option['work-time'] ?></span>

						<div class="hero_box__schedule_info_w lg100 info-tabs text-center">

							<span class="hero_box__schedule_info">
								<img src="<?php echo get_template_directory_uri() . '/images/icons/bag_yellow.svg' ?>" alt="" loading="lazy">
								<?= $acf_option['min_order'] ?>
							</span>

							<span class="hero_box__schedule_info">
								<img src="<?php echo get_template_directory_uri() . '/images/icons/timer_yellow.svg' ?>" alt="" loading="lazy">
								<?= $acf_option['delivery-time'] ?>
							</span>

							<span class="hero_box__schedule_info lg100">
								<img src="<?php echo get_template_directory_uri() . '/images/icons/car_yellow.svg' ?>" alt="" loading="lazy">
								<?= $acf_option['delivery'] ?>
							</span>

							<span id="addr1" class="hero_box__schedule_info">
								<img src="<?php echo get_template_directory_uri() . '/images/icons/location_yellow.svg' ?>" alt="" loading="lazy">
								<?= $acf_option['address'] ?>
							</span>
						</div>
					</div>
				</div>
			</section>
		</div>
		<section class="lg60 xs100 contact-form padding-30">
			<?php the_field('formularz');?>
		</section>
	</div>



</div>
</div>

<?php get_footer();?>   
