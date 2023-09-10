<?php global $woocommerce; get_header();?>

<div class="lg100 page-blackcontent">
	<div class="container offset-header p-top offset-bottom-30">
		<?php get_template_part( 'partials/front/banner' ); ?>
	</div>
	<div class="container page-shop-body">
		<?php get_template_part( 'partials/front/shopbody' ); ?>
	</div>
	<span class="basket-mobile">
		<span class="cart-amount h-amount"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
		<img src="<?php echo esc_url( get_template_directory_uri() . '/images/icons/basket_yellow.svg' ); ?>" alt="basket">
	</span>
</div>

<div class="lg100 promotion-products" id="promotion"></div>

<div class="lg100 before-checkout-products"  id="before-checkout"></div>

<!--<script>-->
<!--	(function ($) {-->
<!--		$(document).ready(function () {-->
<!---->
<!--			$('.product_type_variable.add_to_cart_button').on('click', function (event) {-->
<!--				event.preventDefault();-->
<!---->
<!--				console.log('aaa,,,')-->
<!--			})-->
<!--		})-->
<!--	})(jQuery);-->
<!---->
<!--</script>-->

<?php get_footer();?>
