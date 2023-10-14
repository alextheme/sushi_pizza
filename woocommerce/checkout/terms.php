<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );

	?>
	<div class="woocommerce-terms-and-conditions-wrapper">
		<?php
		/**
		 * Terms and conditions hook used to inject content.
		 *
		 * @since 3.4.0.
		 * @hooked wc_checkout_privacy_policy_text() Shows custom privacy policy text. Priority 20.
		 * @hooked wc_terms_and_conditions_page_content() Shows t&c page content. Priority 30.
		 */
		do_action( 'woocommerce_checkout_terms_and_conditions' );
		?>

		<?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>
			<p class="form-row validate-required">
				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> id="terms" />
				<label for="terms" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">

					<svg width="365" height="383" viewBox="0 0 365 383" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M250 56H56C25.0721 56 0 81.072 0 112V327C0 357.928 25.072 383 56 383H269C299.928 383 325 357.928 325 327V201H302V327C302 345.225 287.225 360 269 360H56C37.7746 360 23 345.225 23 327V112C23 93.7746 37.7746 79 56 79H250V56Z" fill="black" fill-opacity="0.63"/>
						<path d="M104 153.5L58.5 181L141 313H180.5C253.31 209.832 293.794 158.882 365 88.5L359.5 0.5C291.976 63.4993 247.428 120.274 160.5 245.5L104 153.5Z" fill="black"/>
						<rect x="11.5" y="67.5" width="302" height="304" rx="44.5" stroke="#FCB326" stroke-width="23"/>
					</svg>

					<span class="woocommerce-terms-and-conditions-checkbox-text"><?php wc_terms_and_conditions_checkbox_text(); ?></span>&nbsp;<abbr class="required" title="<?php esc_attr_e( 'required', 'woocommerce' ); ?>">*</abbr>
				</label>
				<input type="hidden" name="terms-field" value="1" />
			</p>
		<?php endif; ?>
	</div>
	<?php

	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}
