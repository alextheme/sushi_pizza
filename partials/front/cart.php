<?php

$path = preg_replace('/wp-content.*$/', '', __DIR__);
include($path . 'wp-load.php');

// Update Cart Amount
WC()->cart->calculate_totals();
$cart_count = WC()->cart->get_cart_contents_count();

?>
<?php

$lang = $_GET['lang'];
$basket = '';
$amount = '';
if ($lang == 'ru') {
    $basket = "Корзина";
    $order = "Заказываю!";
    $empty = 'Корзина пуста!';
    $amount = 'Сумма';
} else if ($lang == 'ua') {
    $basket = "Кошик";
    $order = "Замовляю!";
    $empty = 'Кошик порожній!';
    $amount = 'Сума';
} else if ($lang == 'pl' || get_locale() == "pl_PL") {
    $basket = "Koszyk";
    $order = "Zamawiam!";
    $empty = 'Koszyk jest pusty!';
    $amount = 'Kwota';
}

?>
<div class="lg100 cart-content corner-radius psticky" data-cart_count="<?= esc_attr($cart_count); ?>">
    <h2><?php echo $basket; ?></h2>
    <span class="close-cart-mobile">×</span>
    <div class="lg100 cart-items-sc">
        <?php
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            echo '<div class="lg100 cart-item">';
            $product = $cart_item['data'];
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $subtotal = WC()->cart->get_product_subtotal($product, $cart_item['quantity']);
            $link = $product->get_permalink($cart_item);
            $thumbnail = $product->get_image(array(100, 100));
            echo '<div class="row row-margin">';
            echo '<div class="lg40 sm100 xs30 cproduct-thumbnail coverimg padding-15"><a href="' . $link . '">';
            echo $thumbnail;
            echo '</a></div>';
            echo '<div class="lg60 sm100 xs70 sm-offset-top xs100 cproduct-data padding-15">';
            echo '<h4>' . $product->name . '</h4>';
            echo '<span class="cproduct-price">' . $product->price . ' zł</span>';
            echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                '<a href="%s" class="remove-in-cart" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">×</a>',
                esc_url(WC()->cart->get_remove_url($cart_item_key)),
                esc_html__('Remove this item', 'deepsoul'),
                esc_attr($product_id),
                esc_attr($product->get_sku()),
                esc_attr($cart_item_key)
            ), $cart_item_key);
            $btn_plus = get_template_directory_uri() . '/images/icons/icon_plus.svg';
            $btn_minus = get_template_directory_uri() . '/images/icons/icon_minus.svg';
            echo '<div class="lg100 d-flex align-center justify-spaceb">';
            echo '<div class="buttons-qnt d-flex align-center">';

            echo '<button type="button" class="minus-btn"><img src="' . $btn_minus . '" alt="+"></button>';
            echo '<input type="text" data-product_id="' . $product_id . '" data-cart_item_key="' . $cart_item_key . '" class="input-text qty qty-cart text" step="1" min="-1" max="100"  value="' . $quantity . '" title="Szt." size="4" placeholder="" inputmode="numeric">';
            echo '<button type="button" class="plus-btn"><img src="' . $btn_plus . '" alt="+"></button>';

            echo '</div></div></div></div></div>';
        }
        ?>
    </div>
    <?php if (WC()->cart->get_cart_contents_count() == 0): ?>
        <span class="lg100 empty-cart"><?php echo $empty; ?></span>
    <?php else : ?>
        <div class="lg100 cproduct-footer">
            <span class="total-header"><?php echo $amount; ?>:</span> <span
                class="total-price"><?php echo WC()->cart->get_total(); ?></span>
            <span class="button button2 lg100" id="checkout1">
			 <img src="<?php echo esc_url(get_template_directory_uri() . '/images/icons/icon_shop.svg'); ?>"
                  alt="basket">
			 <span><?php echo $order; ?></span>
		 </span>
        </div>
    <?php endif; ?>


    <div class="cart_content_preloader hide">
        <div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>
    </div>

</div>
<script>
    (function ($) {
        // Change quantity in the cart
        $('button.plus-btn, button.minus-btn').on('click', function () {
            console.log( '+++/---' )
            let product_id = $(this).attr("data-product_id"),
                cart_item_key = $(this).attr("data-cart_item_key");

            // Get current quantity values
            let qty = $(this).closest('.cart-item').find('.qty-cart');
            let val = parseFloat(qty.val());
            let max = parseFloat(qty.attr('max'));
            let min = parseFloat(qty.attr('min'));
            let step = parseFloat(qty.attr('step'));

            // Change the value if plus or minus
            if ($(this).is('.plus-btn')) {
                if (max && (max <= val)) {
                    qty.val(max).trigger('change');
                } else {
                    qty.val(val + step).trigger('change');
                }
            } else {
                if (min && (min >= val)) {
                    qty.val(min).trigger('change');
                } else if (val > 0) {
                    qty.val(val - step).trigger('change');
                }
            }


        });

        $('input[type="text"].qty-cart').on('change', function () {
            var product_id = $(this).attr("data-product_id")
            var cart_item_key = $(this).attr("data-cart_item_key")
            var amount = $(this).val();
            var action = amount <= 0 ? 'product_remove' : 'product_update'

            console.log( wc_add_to_cart_params, product_id, cart_item_key, amount, action)

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: wc_add_to_cart_params.ajax_url,
                data: {
                    action: "product_remove",
                    todo: action,
                    product_id: product_id,
                    cart_item_key: cart_item_key,
                    amount: amount
                },
                success: function (response) {
                    $('.cart-item').removeClass('disabled-product');
                    updateShoppingCartAjax();
                }
            });
        });

        /* Remove product from cart -- Product */
        $('.remove-in-cart').on('click', function (event) {
            event.preventDefault();

            console.log( 'remove', $(this).data('product_id')  )

            let productId = $(this).data('product_id');
            let cartItemKey = $(this).data('cart_item_key');

            // Get Popup for select variant product
            $.ajax({
                // url: wc_add_to_cart_params.ajax_url,
                url: ajax_data.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'o10_remove_product_from_cart',
                    nonce: ajax_data.nonce,
                    lang: Cookies.get('pll_language'),
                    product_id: productId,
                    cart_item_key: cartItemKey,
                },
                success: function (result) {
                    updateShoppingCartAjax();
                },
                error: function (msg) {
                    console.log(msg);
                },
                beforeSend: function () {
                    $('.cart_content_preloader').addClass('show');
                },
                complete: function () {
                    $('.cart_content_preloader').removeClass('show');
                }
            })
        })

        // UPDATE Cart
        function updateShoppingCartAjax() {

            console.log( 'update Cart | cart.php' )

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
                    $('.product-sidebar .cart-content').addClass('active-cart');
                }
            })
        }

        // Update Cart Amount
        $('.basket-mobile .cart-amount').text( $('.cart-content').data('cart_count') );

        // Zamawiam!
        $('#checkout1').on('click', function (e) {
            e.preventDefault();

            console.log('Zamawiam! | cart.php')

            let productId = $(this).attr('data-product_id');

            // Get Popup for select variant product
            $.ajax({
                // url: wc_add_to_cart_params.ajax_url,
                url: ajax_data.ajaxUrl,
                data: {
                    action: 'o10_before_checkout',
                    nonce: ajax_data.nonce,
                    lang: Cookies.get('pll_language'),
                },
                success: function (result) {
                    $('#before-checkout').html(result);
                    $('body').css( { overflow: 'hidden' })
                },
                error: function (msg) {
                    console.log(msg)
                },
                beforeSend: function () {
                    $('.before-checkout-products').addClass('active-prom');
                    $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
                    $('.product-sidebar .cart-content').removeClass('active-cart');
                    $('body')
                        .css({ paddingRight: `${window.innerWidth - document.body.offsetWidth}px` })
                        .addClass('body--preloader_show');
                },
                complete: function () {
                    $('body')
                        .css({ paddingRight: '0px' })
                        .removeClass('body--preloader_show');
                }
            })
        })

        // Click button close cart
        $('.close-cart-mobile').on('click', function (e) {
            $('.product-sidebar .cart-content').removeClass('active-cart');
        })
    })(jQuery);
</script>