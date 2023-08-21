<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/font-awesome/css/all.min.css">
      <title><?php if(is_front_page()){the_title();}else{wp_title();} ?></title>
    <?php wp_head() ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/general.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '606140736938344');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=606140736938344&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
</head>
<body <?php body_class(); ?>>
<?php
global $sushi_pizza_option;

//	$url = '/';
//	if (get_locale() == 'ru_RU'){
//		$url = '/ru/';
//	}elseif (get_locale() == 'ua_UA') {
//		$url = '/ua/';
//	}

//echo '<pre>';
//
//print_r( $sushi_pizza_option );
//echo '</pre>';
//
//die();
?>
<div class="header lg100">
  <div class="container">
	  <div class="row align-center">
		  <header class="lg10 md20 xs60 logo">		
			  <a href="<?php echo home_url();?>"><img src="<?php echo $sushi_pizza_option['header_logo']['url']; ?>" alt="<?php echo $sushi_pizza_option['header_logo_alt']; ?>"></a>
		  </header>
		  <div class="lg90 md80 xs100 right-navbar d-flex justify-spaceb align-center">
			  <nav class="lg90 md80 xs100 text-center navbar-menu">
				 <?php wp_nav_menu( array( 'theme_location' => 'header-menu', ) ); ?>
			  </nav>
			  <aside class="lg10 md20 xs100 navbar-shop-buttons">
				 <?php pll_the_languages( array('display_names_as' => 'slug', 'hide_current' => 1) ); ?>
				  <?php

					if ( isset( $sushi_pizza_option['social_slides'] ) && !empty( $sushi_pizza_option['social_slides'] ) ) {
						foreach ($sushi_pizza_option['social_slides'] as $social_slide) {
							?>
							<a href="<?php echo $social_slide['url']; ?>"><img class="inst-btn" src="<?php echo $social_slide['thumb']; ?>" alt="social-link"></a>
							<?php
						}
					}

				  ?>
			  </aside>
			  <div class="nav-bottom">
				  <a href="/polityka-prywatnosci">Polityka prywatności</a>
				  <a href="/regulamin">Regulamin</a>
			  </div>
		  </div>
		  <?php if( wp_is_mobile() ): ?>
		  <div class="on-mobile xs40">
<!--			  <a href="--><?php //if($_SESSION['address'] == "Zawalna 11, Wrocław"){echo 'https://www.instagram.com/american_sushi_express_wroclaw/?hl=en'; }else { echo 'https://www.instagram.com/american_sushi_wroclaw_gaj/'; }?><!--"><img class="inst-btn" src="/wp-content/uploads/2022/10/Vector-1-1.svg" alt="Shop-cart"></a>-->
			  <span class="change-lang">
			   <?php pll_the_languages( array('display_names_as' => 'slug', 'hide_current' => 1, 'dropdown' => 1) ); ?>
			  </span>
			  <div id="burger">
				 <img class="hidem" src="/wp-content/uploads/2022/10/Menu.svg" alt="">
				 <img class="hidem1" src="/wp-content/uploads/2022/11/closem.svg" alt="">
			  </div> 
		  </div>
		  <?php endif;?>
	  </div>
	</div>
</div>

<div id="preloader" class="preloader">
	<div class="cssload-loader">
		<div class="cssload-inner cssload-one"></div>
		<div class="cssload-inner cssload-two"></div>
		<div class="cssload-inner cssload-three"></div>
	</div>
</div>