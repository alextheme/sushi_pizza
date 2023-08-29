<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

$google_map_key = $_ENV['GOOGLE_MAP_KEY'];
echo $google_map_key;

?>


<div class="lg100 p-top-30 d-flex p-bottom-30 breadcrumbs">
	<a class="button d-flex align-center" href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo esc_url( get_template_directory() . '/images/icons/Group-33.svg' ); ?>" alt=""><?php echo pll__( 'Wróć do menu' ); ?></a>
</div>
<form name="checkout" method="post" class="checkout woocommerce-checkout p-bottom" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
		<div class="row row-margin">
			<div class="col1-set" id="customer_details">
				<div class="col-1">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>

				<div class="col-1">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>
			</div>
		</div>
		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

	<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
	<span id="address-filled">Filled</span>


</form>


<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr($google_map_key)?>&v=weekly&libraries=places&callback=initMap" defer></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="<?php echo esc_url( get_template_directory() . '/js/maps.js' ); ?>"></script>