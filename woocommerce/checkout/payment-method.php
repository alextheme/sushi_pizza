<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="lg100 coupon-div">
	<?php do_action( 'woocommerce_review_order_after_order_total_coupon' ); ?>
</div>
<div class="info-ack">
	<?php if (get_locale() == 'pl_PL'): ?>
	<span>Twoje dane osobowe będą użyte do przetworzenia zamówienia, ułatwienia korzystania ze strony internetowej oraz innych celów opisanych w naszej <a href="/polityka-prywatnosci" class="woocommerce-privacy-policy-link" target="_blank">polityka prywatności</a>.</span>
	<?php elseif(get_locale() == 'uk_UK'): ?>
	<span>Ваші особисті дані будуть використані для обробки замовлення, полегшення використання веб-сайту та інших цілей, описаних у нашій статті <a href="/uk/політика-конфіденційності" class="woocommerce-privacy-policy-link" target="_blank">політика конфіденційності</a>.</span>
	<?php else:  ?>
	<span>Ваши личные данные будут использоваться для обработки заказа, облегчения использования веб-сайта и других целей, описанных в нашей <a href="/ru/политика-конфиденциальности/" class="woocommerce-privacy-policy-link" target="_blank">политика конфиденциальности</a>.</span>
	<?php endif;?>
</div>
<div class="lg100 paymeny-methods offset-top-30">
	<h3><?php echo pll__( 'Płatność' ); ?></h3>
	<span class="pbutton cash active"><?php echo pll__( 'Gotówka' ); ?></span>
	<!--<span class="pbutton blik"><img src="/wp-content/uploads/2022/10/Group.svg" alt="" loading="lazy"></span>-->
</div>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:none;"<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
