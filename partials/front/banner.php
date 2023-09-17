<?php
global $post;

$id = $post->ID;
if ( isset($args) && is_array($args) && array_key_exists( 'id', $args ) ) {
	$id = $args['id'];
}

$blocked = is_blocked();
?>
<span class="blocked_shops" id="blocked_shops"><?php echo $blocked; ?></span>
<div class="hero_box row row-margin-30">
	<div class="hero_box__slider lg66 md100 pagebaner padding-30">
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
	<section class="hero_box__schedule lg33 md100 xs100 right-banner-info padding-30">
		<div class="row">
			<div class="hero_box__schedule_w lg100 md50 xs100 bckg-info">
				<h3 class="hero_box__schedule_title"><?php echo pll__( 'Czas pracy' ); ?>:</h3>
				<span class="hero_box__schedule_time work-time"><?php the_field('czas_pracy', $id, false);?></span>
				<div class="hero_box__schedule_info_w lg100 info-tabs text-center">
					<span class="hero_box__schedule_info">
						<img src="<?php echo get_template_directory_uri() . '/images/icons/icon_bag.svg' ?>" alt="" loading="lazy">
						<?php the_field('min_zam', $id, false);?>
					</span>
					<span class="hero_box__schedule_info">
						<img src="<?php echo get_template_directory_uri() . '/images/icons/icon_clock.svg' ?>" alt="" loading="lazy">
						<?php the_field('czas_dostawy', $id, false);?>
					</span>
					<span class="hero_box__schedule_info lg100">
						<img src="<?php echo get_template_directory_uri() . '/images/icons/icon_car.svg' ?>" alt="" loading="lazy">
						<?php the_field('przewoz', $id, false);?>
					</span>
					<span id="addr1" class="hero_box__schedule_info">
						<img src="<?php echo get_template_directory_uri() . '/images/icons/icon_mark.svg' ?>" alt="" loading="lazy">
						<?php the_field('adres', $id, false);?>
					</span>
				</div>
			</div>
		</div>
	</section>
</div>