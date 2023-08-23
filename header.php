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
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css">
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

?>
<div class="header lg100">
  <div class="container">
	  <div class="row align-center">
		  <header class="logo <?php echo $sushi_pizza_option['header_logo_around'] ? 'round' : ''; ?>" >
			  <a href="<?php echo home_url();?>"><img src="<?php echo $sushi_pizza_option['header_logo']['url']; ?>" alt="<?php echo $sushi_pizza_option['header_logo_alt']; ?>"></a>
		  </header>

		  <div class="main_menu_box">
			  <div class="main_menu_wrapper">

				  <nav class="navbar-menu">
					  <?php wp_nav_menu( array( 'theme_location' => 'header-menu', ) ); ?>
				  </nav>

				  <!-- MOBILE MENU Additional Fields -->
				  <div class="menu_mobile_additional_fields">

					  <a href="#" class="menu_mobile__address">
						  <span>
							  <?php echo $sushi_pizza_option['address']; ?>
						  </span>
						  <svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.86603 7.5C5.48113 8.16667 4.51887 8.16667 4.13397 7.5L0.669874 1.5C0.284974 0.833333 0.7661 -8.94676e-07 1.5359 -8.27378e-07L8.4641 -2.21695e-07C9.2339 -1.54397e-07 9.71503 0.833333 9.33013 1.5L5.86603 7.5Z" fill="#FFF388"/></svg>
					  </a>

					  <a class="menu_mobile__insta" href="<?php echo $sushi_pizza_option['social_insta']; ?>">
						  <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
							  <path d="M14.4987 9.94596C11.9899 9.94596 9.94255 11.9933 9.94255 14.5021C9.94255 17.0109 11.9899 19.0583 14.4987 19.0583C17.0075 19.0583 19.0549 17.0109 19.0549 14.5021C19.0549 11.9933 17.0075 9.94596 14.4987 9.94596ZM28.1637 14.5021C28.1637 12.6154 28.1808 10.7458 28.0749 8.86247C27.9689 6.67497 27.4699 4.73356 25.8703 3.13395C24.2673 1.53092 22.3293 1.03532 20.1418 0.929361C18.255 0.823404 16.3854 0.840494 14.5021 0.840494C12.6154 0.840494 10.7458 0.823404 8.86247 0.929361C6.67497 1.03532 4.73356 1.53434 3.13395 3.13395C1.53092 4.73698 1.03532 6.67497 0.929361 8.86247C0.823404 10.7492 0.840494 12.6188 0.840494 14.5021C0.840494 16.3854 0.823404 18.2585 0.929361 20.1418C1.03532 22.3293 1.53434 24.2707 3.13395 25.8703C4.73698 27.4733 6.67497 27.9689 8.86247 28.0749C10.7492 28.1808 12.6188 28.1637 14.5021 28.1637C16.3888 28.1637 18.2585 28.1808 20.1418 28.0749C22.3293 27.9689 24.2707 27.4699 25.8703 25.8703C27.4733 24.2673 27.9689 22.3293 28.0749 20.1418C28.1842 18.2585 28.1637 16.3888 28.1637 14.5021ZM14.4987 21.5124C10.6193 21.5124 7.48844 18.3815 7.48844 14.5021C7.48844 10.6227 10.6193 7.49186 14.4987 7.49186C18.3781 7.49186 21.509 10.6227 21.509 14.5021C21.509 18.3815 18.3781 21.5124 14.4987 21.5124ZM21.7961 8.84196C20.8903 8.84196 20.1589 8.11051 20.1589 7.20475C20.1589 6.29899 20.8903 5.56754 21.7961 5.56754C22.7018 5.56754 23.4333 6.29899 23.4333 7.20475C23.4335 7.41983 23.3914 7.63285 23.3092 7.8316C23.227 8.03036 23.1064 8.21095 22.9543 8.36303C22.8023 8.51512 22.6217 8.6357 22.4229 8.71788C22.2242 8.80007 22.0111 8.84223 21.7961 8.84196Z" fill="#FCB326"/>
						  </svg>
					  </a>

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
				  <a href="<?php echo $sushi_pizza_option['social_insta']; ?>">
					  <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
						  <path d="M14.4987 9.94596C11.9899 9.94596 9.94255 11.9933 9.94255 14.5021C9.94255 17.0109 11.9899 19.0583 14.4987 19.0583C17.0075 19.0583 19.0549 17.0109 19.0549 14.5021C19.0549 11.9933 17.0075 9.94596 14.4987 9.94596ZM28.1637 14.5021C28.1637 12.6154 28.1808 10.7458 28.0749 8.86247C27.9689 6.67497 27.4699 4.73356 25.8703 3.13395C24.2673 1.53092 22.3293 1.03532 20.1418 0.929361C18.255 0.823404 16.3854 0.840494 14.5021 0.840494C12.6154 0.840494 10.7458 0.823404 8.86247 0.929361C6.67497 1.03532 4.73356 1.53434 3.13395 3.13395C1.53092 4.73698 1.03532 6.67497 0.929361 8.86247C0.823404 10.7492 0.840494 12.6188 0.840494 14.5021C0.840494 16.3854 0.823404 18.2585 0.929361 20.1418C1.03532 22.3293 1.53434 24.2707 3.13395 25.8703C4.73698 27.4733 6.67497 27.9689 8.86247 28.0749C10.7492 28.1808 12.6188 28.1637 14.5021 28.1637C16.3888 28.1637 18.2585 28.1808 20.1418 28.0749C22.3293 27.9689 24.2707 27.4699 25.8703 25.8703C27.4733 24.2673 27.9689 22.3293 28.0749 20.1418C28.1842 18.2585 28.1637 16.3888 28.1637 14.5021ZM14.4987 21.5124C10.6193 21.5124 7.48844 18.3815 7.48844 14.5021C7.48844 10.6227 10.6193 7.49186 14.4987 7.49186C18.3781 7.49186 21.509 10.6227 21.509 14.5021C21.509 18.3815 18.3781 21.5124 14.4987 21.5124ZM21.7961 8.84196C20.8903 8.84196 20.1589 8.11051 20.1589 7.20475C20.1589 6.29899 20.8903 5.56754 21.7961 5.56754C22.7018 5.56754 23.4333 6.29899 23.4333 7.20475C23.4335 7.41983 23.3914 7.63285 23.3092 7.8316C23.227 8.03036 23.1064 8.21095 22.9543 8.36303C22.8023 8.51512 22.6217 8.6357 22.4229 8.71788C22.2242 8.80007 22.0111 8.84223 21.7961 8.84196Z" fill="#FCB326"/>
					  </svg>
				  </a>
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

<div id="preloader" class="preloader">
	<div class="cssload-loader">
		<div class="cssload-inner cssload-one"></div>
		<div class="cssload-inner cssload-two"></div>
		<div class="cssload-inner cssload-three"></div>
	</div>
</div>