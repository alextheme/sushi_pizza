<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--    <link rel="stylesheet" href="--><?php //echo get_template_directory_uri(); ?><!--/font-awesome/css/all.min.css">-->
      <title><?php if(is_front_page()){the_title();}else{wp_title();} ?></title>
    <?php wp_head() ?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/general.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Facebook Pixel Code -->
<!--<script>-->
<!--  !function(f,b,e,v,n,t,s)-->
<!--  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?-->
<!--  n.callMethod.apply(n,arguments):n.queue.push(arguments)};-->
<!--  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';-->
<!--  n.queue=[];t=b.createElement(e);t.async=!0;-->
<!--  t.src=v;s=b.getElementsByTagName(e)[0];-->
<!--  s.parentNode.insertBefore(t,s)}(window, document,'script',-->
<!--  'https://connect.facebook.net/en_US/fbevents.js');-->
<!--  fbq('init', '606140736938344');-->
<!--  fbq('track', 'PageView');-->
<!--</script>-->

<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=606140736938344&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
</head>
<body <?php body_class(); ?>>
<?php
global $sushi_pizza_option;
global $post;

$id = $post->ID;
if ( isset($args) && is_array($args) && array_key_exists( 'id', $args ) ) {
	$id = $args['id'];
}

$blocked = is_blocked();

//print_pre_die( $sushi_pizza_option );

//	$url = '/';
//	if (get_locale() == 'ru_RU'){
//		$url = '/ru/';
//	}elseif (get_locale() == 'ua_UA') {
//		$url = '/ua/';
//	}

?>
<div class="header lg100">
  <div class="container">
	  <div class="row align-center">
		  <header class="logo <?php echo $sushi_pizza_option['header_logo_around'] ? 'round' : ''; ?>" >
			  <a href="<?php echo esc_url( home_url() );?>">
				  <?php if ($sushi_pizza_option) { ?>
					  <img src="<?php echo esc_url( $sushi_pizza_option['header_logo']['url'] ); ?>"
						   alt="<?php echo esc_attr( $sushi_pizza_option['header_logo_alt'] ); ?>">
				  <?php } ?>
			  </a>
		  </header>

		  <div class="main_menu_box">
			  <div class="main_menu_wrapper">

				  <nav class="navbar-menu">
					  <?php wp_nav_menu( array( 'theme_location' => 'header-menu', ) ); ?>
				  </nav>

				  <!-- MOBILE MENU Additional Fields -->
				  <div class="menu_mobile_additional_fields">


					  <a href="#" class="menu_mobile__address">
						  <span><?php the_field('adres', $id);?></span>
						  <svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.86603 7.5C5.48113 8.16667 4.51887 8.16667 4.13397 7.5L0.669874 1.5C0.284974 0.833333 0.7661 -8.94676e-07 1.5359 -8.27378e-07L8.4641 -2.21695e-07C9.2339 -1.54397e-07 9.71503 0.833333 9.33013 1.5L5.86603 7.5Z" fill="#FFF388"/></svg>
					  </a>

					  <?php if ($sushi_pizza_option) { ?>
						  <ul class="menu_mobile__socials">
							  <?php if ($sushi_pizza_option['social_insta']) { ?>
								  <li class="menu_mobile__social">
									  <a class="menu_mobile__social_link" href="<?php echo esc_url( $sushi_pizza_option['social_insta'] ); ?>">
										  <?php if ($sushi_pizza_option['social_insta_icon_m']) { ?>
											  <img class="menu_mobile__social_img" src="<?php echo esc_url( $sushi_pizza_option['social_insta_icon_m']['url'] ); ?>" alt="logo">
										  <?php } ?>
									  </a>
								  </li>
							  <?php } ?>
							  <?php if ($sushi_pizza_option['social_fb']) { ?>
								  <li class="menu_mobile__social">
									  <a class="menu_mobile__social_link" href="<?php echo esc_url( $sushi_pizza_option['social_fb'] ); ?>">
										  <?php if ($sushi_pizza_option['social_fb_icon_m']) { ?>
											  <img class="menu_mobile__social_img" src="<?php echo esc_url( $sushi_pizza_option['social_fb_icon_m']['url'] ); ?>" alt="logo">
										  <?php } ?>
									  </a>
								  </li>
							  <?php } ?>
						  </ul>
					  <?php } ?>

					  <ul class="menu_mobile__lang_bottom">
						  <?php pll_the_languages( array('display_names_as' => 'slug', 'hide_current' => 1) ); ?>
					  </ul>

					  <div class="menu_mobile__bottom_links">
						  <a href="/polityka-prywatnosci">Polityka prywatności</a>
						  <a href="/regulamin">Regulamin</a>
						  <a href="/alergeny">Alergeny</a>
					  </div>

				  </div>

			  </div>
		  </div>


		  <div class="header_buttons_w">
			  <aside class="navbar-shop-buttons">
				  <?php pll_the_languages( array('display_names_as' => 'slug', 'hide_current' => 1) ); ?>

				  <?php if ($sushi_pizza_option) { ?>
					  <ul class="menu__socials">
						  <?php if ($sushi_pizza_option['social_insta']) { ?>
							  <li class="menu__social">
								  <a class="menu__social_link" href="<?php echo esc_url( $sushi_pizza_option['social_insta'] ); ?>">
									  <?php if ( $sushi_pizza_option['social_insta_icon'] && $sushi_pizza_option['social_insta_icon']['url']  !== '' ) { ?>
										  <img class="menu__social_img" src="<?php echo esc_url( $sushi_pizza_option['social_insta_icon']['url'] ); ?>" alt="logo">
									  <?php } ?>
									  <?php if ( $sushi_pizza_option['social_insta_icon_m'] && $sushi_pizza_option['social_insta_icon_m']['url']  !== '' ) { ?>
										  <img class="menu__social_img menu__social_img--mobile_mod" src="<?php echo esc_url( $sushi_pizza_option['social_insta_icon_m']['url'] ); ?>" alt="logo">
									  <?php } ?>
								  </a>
							  </li>
						  <?php } ?>
						  <?php if ($sushi_pizza_option['social_fb']) { ?>
							  <li class="menu__social">
								  <a class="menu__social_link" href="<?php echo esc_url( $sushi_pizza_option['social_fb'] ); ?>">
									  <?php if ( $sushi_pizza_option['social_fb_icon'] &&  $sushi_pizza_option['social_fb_icon']['url'] !== '' ) { ?>
										  <img class="menu__social_img" src="<?php echo esc_url( $sushi_pizza_option['social_fb_icon']['url'] ); ?>" alt="logo">
									  <?php } ?>
									  <?php if ( $sushi_pizza_option['social_fb_icon_m'] &&  $sushi_pizza_option['social_fb_icon_m']['url'] !== '' ) { ?>
										  <img class="menu__social_img menu__social_img--mobile_mod" src="<?php echo esc_url( $sushi_pizza_option['social_fb_icon_m']['url'] ); ?>" alt="logo">
									  <?php } ?>
								  </a>
							  </li>
						  <?php } ?>
					  </ul>
				  <?php } ?>

			  </aside>
		  </div>

		  <div class="menu_mobile__handlers">

			  <ul class="menu_mobile__lang_top">
				<?php  pll_the_languages( array('display_names_as' => 'slug' ) ); ?>
			  </ul>

			  <!-- Button open/close mobile menu -->
			  <div id="burger" class="menu_mobile__button_switch">
				  <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
					  <rect width="40" height="40" rx="10" fill="#FCB326"/>
					  <path class="line_1" d="M29.5 14H11" stroke="white" stroke-width="2" stroke-linecap="round"/>
					  <path class="line_2" d="M29.5 20H11" stroke="white" stroke-width="2" stroke-linecap="round"/>
					  <path class="line_3" d="M29.5 26H11" stroke="white" stroke-width="2" stroke-linecap="round"/>

					  <path class="line_4" d="M26.7909 26.5407L13.7095 13.4592" stroke="white" stroke-width="2" stroke-linecap="round"/>
					  <path class="line_5" d="M13.7086 26.5407L26.79 13.4592" stroke="white" stroke-width="2" stroke-linecap="round"/>
				  </svg>
			  </div>
		  </div>

	  </div>
	</div>
</div>

<div id="preloader" class="preloader preloader_header">
	<div class="cssload-loader">
		<div class="cssload-inner cssload-one"></div>
		<div class="cssload-inner cssload-two"></div>
		<div class="cssload-inner cssload-three"></div>
	</div>
</div>