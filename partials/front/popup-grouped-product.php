<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

switch ($lang) {
    case 'ua':
        $button_text = 'Купую!';
        $title_text = 'Обери складові';
        break;
    case 'ru':
        $button_text = 'Покупаю!';
        $title_text = 'Выбери состав';
        break;

    default:
        $button_text = 'Kupuję!';
        $title_text = 'WYBIERZ SKŁADNIK';

}

?>
<div class="group_product">
    <div class="group_product__wrapper">
        <span onclick="document.body.classList.remove('body--preloader_show')"
              class="group_product__close_btn"
              aria-label="button close popup group product"><span></span></span>

        <?php while ( have_posts() ) : ?>
            <?php the_post(); ?>

            <div id="product-<?php the_ID(); ?>">

                <?php the_title(); ?>
                <div class="summary entry-summary">
                    <?php
                    /**
                     * Hook: woocommerce_single_product_summary.
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_rating - 10
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_meta - 40
                     * @hooked woocommerce_template_single_sharing - 50
                     * @hooked WC_Structured_Data::generate_product_data() - 60
                     */
                    do_action( 'woocommerce_single_product_summary' );
                    ?>
                </div>

                <?php
                /**
                 * Hook: woocommerce_after_single_product_summary.
                 *
                 * @hooked woocommerce_output_product_data_tabs - 10
                 * @hooked woocommerce_upsell_display - 15
                 * @hooked woocommerce_output_related_products - 20
                 */
                do_action( 'woocommerce_after_single_product_summary' );
                ?>
            </div>

        <?php endwhile; // end of the loop. ?>
    </div>
</div>

<script>
    (function ($) {
        $(document).ready(function () {

            // CLOSED POPUP SELECT VARIANT
            (() => {
                $('.variable_product__close_btn').on('click', e => {
                    $('body').removeClass('body--preloader_show');
                });
            })();// - CLOSED POPUP SELECT VARIANT


            // ADD TO CART -- VARIABLE PRODUCT
            (() => {
                $('.variable_product__btn_submit .single_add_to_cart_button').on('click', function(e) {
                    e.preventDefault();
                    if (!window.workTime) return;

                    var product_id = $('.variations_form').find('input[name="product_id"]').val();
                    var variation_id = $('.variations_form').find('input[name="variation_id"]').val();

                    $.ajax({
                        type: 'POST',
                        url: ajax_data.ajaxUrl,
                        data: {
                            action: 'o10_woocommerce_ajax_add_to_cart',
                            nonce: ajax_data.nonce,
                            lang: Cookies.get('pll_language'),
                            product_id: product_id,
                            variation_id: variation_id,
                            quantity: 1,
                        },
                        beforeSend: function () {
                            $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
                        },
                        success: function(response) {
                            updateShoppingCart();
                        },
                        error: function (error) {
                            console.error(error);
                        },
                        complete: function () {
                            $('body').removeClass('body--preloader_show').css({ paddingRight: '0px' })
                        }
                    });
                });
            })(); // - ADD TO CART VARIABLE PRODUCT


            function updateShoppingCart() {

                $.ajax({
                    // url: wc_add_to_cart_params.ajax_url,
                    url: ajax_data.ajaxUrl,
                    type: 'get',
                    data: {
                        action: 'o10_update_cart',
                        nonce: ajax_data.nonce,
                        lang: Cookies.get('pll_language'),
                    },
                    success: function (response) {
                        $('#product-sidebar-cart').html(response);
                    },
                    error: function (response) {
                        console.error(response.statusText);
                        console.error(response.responseText);
                    },
                    beforeSend: function () {
                        $('.cart_content_preloader').addClass('show');
                    },
                    complete: function () {
                        $('.cart_content_preloader').removeClass('show');
                    }
                })
            }
        })
    })(jQuery);
</script>

