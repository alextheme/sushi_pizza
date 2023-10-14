<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_payment() ) : ?>

		<div class="lg100 paymeny-methods offset-top-30">
			<h3><?php echo pll__( 'Płatność' ); ?></h3>
		</div>

		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) { ?>

				<?php foreach ( $available_gateways as $gateway ) {
					wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
		</ul>

		<div class="info-ack">
			<?php if (get_locale() == 'pl_PL'): ?>
				<span>Twoje dane osobowe będą użyte do przetworzenia zamówienia, ułatwienia korzystania ze strony internetowej oraz innych celów opisanych w naszej <a href="/polityka-prywatnosci" class="woocommerce-privacy-policy-link" target="_blank">polityka prywatności</a>.</span>
			<?php elseif(get_locale() == 'uk_UK'): ?>
				<span>Ваші особисті дані будуть використані для обробки замовлення, полегшення використання веб-сайту та інших цілей, описаних у нашій статті <a href="/uk/політика-конфіденційності" class="woocommerce-privacy-policy-link" target="_blank">політика конфіденційності</a>.</span>
			<?php else:  ?>
				<span>Ваши личные данные будут использоваться для обработки заказа, облегчения использования веб-сайта и других целей, описанных в нашей <a href="/ru/политика-конфиденциальности/" class="woocommerce-privacy-policy-link" target="_blank">политика конфиденциальности</a>.</span>
			<?php endif;?>
		</div>

	<?php endif; ?>
	<div class="form-row place-order">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php $order_total =  strip_tags(WC()->cart->get_total()); ?>
		<?php echo apply_filters(
			'woocommerce_order_button_html',
			'<button 
			type="submit" 
			class="button alt' . esc_attr(
				wc_wp_theme_get_element_class_name( 'button' )
					? ' ' . wc_wp_theme_get_element_class_name( 'button' )
					: ''
			) . '" 
			name="woocommerce_checkout_place_order" 
			id="place_order" 
			value="' . esc_attr( $order_button_text . ' ' . $order_total ) . '" 
			data-value="' . esc_attr( $order_button_text . ' ' . $order_total ) . '">' .
			esc_html( $order_button_text . ' ' . $order_total ) .
			'</button>' ); // @codingStandardsIgnoreLine ?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
<?php
if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
