<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;


/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

get_header(); 

		$want = "Kupuję!";
		$seeMore = "Zobacz więcej!";
		$dfind = "Niestety nie znaleźliśmy takich produktów!";
		$want4 = "Aktualnie brak!";
		$id=2;
		if (get_locale() == 'ru_RU'){
			$want = "Покупаю!";
			$seeMore = "Узнать больше!";
			$dfind = "К сожалению, таких товаров мы не нашли!";
			$id=87;
			$want4 = "недостаток!";
		}elseif (get_locale() == 'ua_UA') {
			$want = "Купую!";
			$seeMore = "Побачити більше!";
			$dfind = "На жаль, таких товарів ми не знайшли!";
			$id=90;
			$want4 = "відсутність!";
		}

 ?>

<div class="lg100 page-blackcontent">
	<div class="container offset-header p-bottom-30 p-top-30">
		<?php get_template_part( 'partials/front/banner', '',['id' => $id]); ?>
	</div>
	<div class="container product-simple-content">
		<div class="row row-margin">
			<div class="lg70 sm100 xs100 products product-list-left padding-15" id="products-list">
				<div class="lg100 d-flex p-bottom-30 breadcrumbs">
					<a class="button d-flex align-center" href="/"><img src="/wp-content/uploads/2022/10/Group-33.svg" alt=""><?php echo pll__( 'Wróć do menu' ); ?></a>
				</div>
				<div class="row row-margin p-bottom">
					<div class="lg50 xs100 padding-15 left-product-img">
						<?php the_post_thumbnail(); ?>
					</div>
					<section class="lg50 xs100 padding-15 right-product-text">
						<h1 class="xs-offset-top"><?php the_title(); ?></h1>
						<span class="price"><?php $product = wc_get_product( get_the_ID() ); echo $product->get_price(); ?> zł</span>
						<?php the_content(); ?>
						<?php if($product->is_in_stock()){ echo sprintf('<a href="%s" data-quantity="1" class="%s" %s>'.$want.'</a>', esc_url($product->add_to_cart_url()), esc_attr(implode(' ', array_filter(array('button','product_type_' . $product->get_type(),$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '', $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '')))), wc_implode_html_attributes(array('data-product_id' => $product->get_id(), 'data-product_sku' => $product->get_sku(),'aria-label' => $product->add_to_cart_description(),'rel' => 'nofollow')), esc_html($product->add_to_cart_text()));}else {echo '<a class="button not_in_stock offset-top-30" href="'.get_permalink().'">'.$want4.'</a>';} ?> 
					</section>
				</div>
				<h3 class="xs-offset-top border-top"><?php echo $seeMore; ?></h3>
				<?php get_template_part( 'partials/front/products-list' ); ?>
			</div>
			<div class="lg30 xs100 product-sidebar padding-15" id="product-sidebar-cart">
				<?php get_template_part( 'partials/front/cart' ); ?>
			</div>
		</div>
	</div>
	<span class="basket-mobile">
		<span class="cart-amount h-amount"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
		<img src="/wp-content/uploads/2022/10/353439-basket-buy-cart-ecommerce-online-purse-shop-shopping_107515-1.svg" alt="basket">
	</span>
	<div class="lg100 before-checkout-products pbefore-checkout-products p-bottom offset-header" id="before-checkout"></div>
</div>

<?php get_footer();?>   
