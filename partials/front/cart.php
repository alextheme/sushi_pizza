<?php

$path = preg_replace('/wp-content.*$/', '', __DIR__);
include($path . 'wp-load.php');

// Update Cart Amount
WC()->cart->calculate_totals();
$cart_count = WC()->cart->get_cart_contents_count();


$lang = $_GET['lang'];
$basket = '';
$amount = '';
if ($lang == 'ru' || get_locale() == "ru_RU") {
    $basket = "Корзина";
    $order = "Заказываю!";
    $empty = 'Корзина пуста!';
    $amount = 'Сумма';
} else if ($lang == 'ua' || get_locale() == "ua_UA") {
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
    <button class="cart__clear_basket hide"></button>
    <span class="close-cart-mobile">×</span>

    <h2 class="cart-title"><?php echo $basket; ?></h2>



    <div class="lg100 cart-items-sc">
        <?php
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $is_composite_product = $cart_item['composite_product'] === true;
            $composite_key = $cart_item['composite_key'];
            $owner_variation_id = $cart_item['owner_variation_id'];
            $owner_product_id = $cart_item['owner_product_id'];
            $owner = $owner_variation_id || $owner_product_id;
            $components_array_ids = json_encode( $cart_item['id_components'] );
            $no_update_product = $is_composite_product && $owner;
            ?>

            <div class="lg100 cart-item <?php echo esc_attr( $no_update_product ? "cart-item--component" : "" ); ?>">

                <?php
                $product = $cart_item['data'];
                $variation_id = $cart_item['variation_id'];
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];
                $subtotal = WC()->cart->get_product_subtotal($product, $cart_item['quantity']);
                $link = $product->get_permalink($cart_item);

                if ( $no_update_product ) { ?>

                    <div class="row">
                        <a href="<?php echo esc_url( $link )?>">
                            <div class="cproduct-data">
                                <h4><?php echo esc_html( $product->name ); ?></h4>
                                <span class="cproduct-price"><?php echo esc_html( $product->price ); ?> zł</span>

                                <span class="remove-in-cart"
                                      aria-label="<?php echo esc_html__('Remove this item', 'americansushi'); ?>"
                                      data-product_id="<?php echo esc_attr($product_id); ?>"
                                      data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                                      data-cart_item_key="<?php echo esc_attr($cart_item_key); ?>"
                                      data-composite_key="<?php echo esc_attr($composite_key); ?>">
                                </span>

                                <div class="lg100">
                                    <input type="text" title="Szt." size="4" placeholder="" inputmode="numeric" readonly
                                           class="input-text qty qty-cart text" step="1" min="-1" max="100"
                                           data-product_id="<?php echo esc_attr( $product_id ); ?>"
                                           data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>"
                                           data-owner="<?php echo esc_attr( $owner ); ?>"
                                           data-owner-product-id="<?php echo esc_attr( $owner_product_id ); ?>"
                                           data-owner-variation-id="<?php echo esc_attr( $owner_variation_id ); ?>"
                                           data-composite_key="<?php echo esc_attr( $composite_key ); ?>"
                                           value="<?php echo esc_attr( $quantity ); ?>"
                                    >
                                </div>
                            </div>
                        </a>
                    </div>

                <?php } else { ?>

                    <div class="row row-margin">
                        <div class="lg40 sm100 xs30 cproduct-thumbnail coverimg padding-15">
                            <a href="<?php echo esc_url( $link )?>">
                                <?php echo $product->get_image(array(100, 100)) ?>
                            </a>
                        </div>

                        <div class="lg60 sm100 xs70 sm-offset-top xs100 cproduct-data padding-15">
                            <h4><?php echo esc_html( $product->name ); ?></h4>
                            <span class="cproduct-price"><?php echo esc_html( $product->price ) ?> zł</span>

                            <?php
                            echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                                '<a href="%s" class="remove-in-cart" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s" data-composite_key="%s">×</a>',
                                esc_url(WC()->cart->get_remove_url($cart_item_key)),
                                esc_html__('Remove this item', 'deepsoul'),
                                esc_attr($product_id),
                                esc_attr($product->get_sku()),
                                esc_attr($cart_item_key),
                                esc_attr($composite_key),
                            ), $cart_item_key);
                            ?>

                            <div class="lg100 d-flex align-center justify-spaceb">
                                <div class="buttons-qnt d-flex align-center">

                                    <button type="button" class="minus-btn">
                                        <svg width="21" height="22" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10.5" cy="11" r="10" fill="#464646" stroke="#DEDDE0"/>
                                            <path d="M6 11.5C12.9048 11.5 14.631 11.5 14.631 11.5" stroke="white"/>
                                        </svg>
                                    </button>

                                    <input type="text" step="1" min="1" max="100" title="Szt." size="4" placeholder="" inputmode="numeric"
                                           class="input-text qty qty-cart text" readonly
                                           data-product_id="<?php echo esc_attr( $product_id ); ?>"
                                           data-variation_id="<?php echo esc_attr( $variation_id ); ?>"
                                           data-variable_id="<?php echo esc_attr( $product_id ); ?>"
                                           data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>"
                                           data-is-composible-product="<?php echo esc_attr( $is_composite_product ); ?>"
                                           data-components-array-ids="<?php echo esc_attr( $components_array_ids ); ?>"
                                           data-composite_key="<?php echo esc_attr( $composite_key ); ?>"
                                           value="<?php echo esc_attr( $quantity ); ?>"
                                    >

                                    <button type="button" class="plus-btn">
                                        <svg width="21" height="22" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10.5" cy="11" r="10" fill="white" stroke="#DEDDE0"/>
                                            <circle cx="10.5" cy="11" r="10" fill="white" stroke="#DEDDE0"/>
                                            <path d="M6 10.8155C12.9048 10.8155 14.631 10.8155 14.631 10.8155" stroke="#191A1F"/>
                                            <path d="M10.3154 15.131C10.3154 8.22619 10.3154 6.5 10.3154 6.5" stroke="#191A1F"/>
                                        </svg>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            </div>

        <?php } ?>
    </div>

    <?php if (WC()->cart->get_cart_contents_count() == 0) { ?>

        <span class="lg100 empty-cart"><?php echo $empty; ?></span>

    <?php } else { ?>

        <div class="lg100 cproduct-footer">
            <span class="total-header"><?php echo $amount; ?>:</span>
            <span class="total-price"><?php echo WC()->cart->get_total(); ?></span>
            <span class="button button2 lg100" id="checkout1"><span><?php echo $order; ?></span></span>
        </div>

    <?php } ?>

    <div class="cart_content_preloader hide">
        <div id="preloader" class="preloader preloader2">
            <div class="cssload-loader">
                <div class="cssload-inner cssload-one"></div>
                <div class="cssload-inner cssload-two"></div>
                <div class="cssload-inner cssload-three"></div>
            </div>
        </div>
    </div>

</div>

<script>
    (function ($) {

        /**
         * Change quantity in the cart
         */
        $('button.plus-btn, button.minus-btn').on('click', function () {

            // Get current quantity values
            const inputQuantity = this.closest('.cart-item').querySelector('.qty-cart')
            const value = +inputQuantity.value // 12
            const max = +inputQuantity.getAttribute('max') // 100
            const min = +inputQuantity.getAttribute('min') // 1
            const step = +inputQuantity.getAttribute('step') // 1

            // Change the value if plus or minus
            if ( this.classList.contains('plus-btn') ) {

                if ( value < max ) {
                    inputQuantity.value = value + step
                    componentUpdateQty( inputQuantity )
                }

            } else {

                if ( value > min ) {
                    inputQuantity.value = value - step
                    componentUpdateQty( inputQuantity )
                }

            }

            function componentUpdateQty( inputQuantity, operation = 'set_quantity' ) {
                const componentsInput = Array.from( document.querySelectorAll(`.cart-item--component input[data-owner="1"][data-composite_key="${ inputQuantity.dataset.composite_key }"]` ))
                const target_cart_item_keys = [ inputQuantity.dataset.cart_item_key, ...componentsInput.map( c => c.dataset.cart_item_key ) ]

                ajaxUpdateCartItemQuantity(target_cart_item_keys, inputQuantity.value, operation )
            }
        });

        /**
         * Remove products from cart -- Product
         */
        $('.remove-in-cart').on('click', function (event) {
            event.preventDefault();

            this.closest('.cart-item').classList.add('removal')

            const target_cart_item_keys = this.dataset.composite_key
                ?  Array.from( document.querySelectorAll(`.remove-in-cart[data-composite_key="${this.dataset.composite_key}"]`) )
                    .map( p => p.dataset.cart_item_key )
                : [ this.dataset.cart_item_key ]

            console.log(target_cart_item_keys)

            // Zero quantity - Remove items
            ajaxUpdateCartItemQuantity(target_cart_item_keys, 0, 'set_quantity' )
        })

        /**
         * Update Quantity product(s) or Remove product(s) if set quantity == 0
         * @param target_cart_item_keys
         * @param quantity
         * @param operation
         */
        function ajaxUpdateCartItemQuantity(target_cart_item_keys, quantity, operation = 'set_quantity' /* plus1, minus1 */ ) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajax_data.ajaxUrl,
                data: {
                    action: "update_cart_item_quantity",
                    nonce: ajax_data.nonce,
                    target_cart_item_keys,
                    operation,
                    quantity,
                },
                success: function (response) {
                    updateShoppingCartAjax();
                },
                error: function (reject) {
                    console.error('reject: ', reject);
                },
                beforeSend: function () {
                    setTimeout( () => {
                        $('.cart_content_preloader').addClass('show');
                    }, 1000)
                },
                complete: function () {
                    $('.cart_content_preloader').removeClass('show');
                    $('.product-sidebar .cart-content').addClass('active-cart');
                }
            });
        }

        /**
         * UPDATE Cart
         */
        function updateShoppingCartAjax() {

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

        /**
         * Update Cart Amount
         */
        $('.basket-mobile .cart-amount').text( $('.cart-content').data('cart_count') );

        /**
         * Zamawiam!
         */
        $('#checkout1').on('click', function (e) {
            e.preventDefault();

            if (!window.workTime) return;

            // Get Popup for select variant product
            $.ajax({
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

        /**
         * Click button close cart
         */
        $('.close-cart-mobile').on('click', function (e) {
            $('.product-sidebar .cart-content').removeClass('active-cart');
        })


        /**
         * Cart & menu category scroll position sticky
         * @type {*|jQuery|HTMLElement}
         */
        var $headerTabs = $('.shop-header-tabs')
        if( $headerTabs.length ) {
            function setTopPositionCart() {
                $('.cart-content').css({ top: `${$headerTabs.height() + 30}px` })
            }

            $( window ).on( "resize", function() {
                setTopPositionCart();
            })

            $( window ).on( "scroll", function(e) {
                var scrollTop = $(window).scrollTop();
                var elementTop = $headerTabs.offset().top;

                if ( scrollTop >= elementTop - 50 ) {
                    if (!$headerTabs.hasClass('shadow')) {
                        $headerTabs.addClass('shadow')
                    }
                } else {
                    $headerTabs.removeClass('shadow');
                }
            })

            setTopPositionCart();
        }


        /**
         * REMOVE ALL ITEMS FROM CART
         */
        $('.cart__clear_basket').on('click', function () {
            $.ajax({
                url: ajax_data.ajaxUrl,
                data: {
                    action: 'o10_remove_items_from_cart',
                    nonce: ajax_data.nonce,
                },
                success: function (result) {
                    console.log(result)
                    updateShoppingCartAjax();
                },
                error: function (msg) {
                    console.log(msg)
                },
            })
        });


    })(jQuery);
</script>
<script id="delAllTxtAfterMe">
    setTimeout(()=>{
        const n = document.getElementById('delAllTxtAfterMe')?.nextSibling;
        if ( n ) {
            n.nodeType === Node.TEXT_NODE ? n.remove() : ''
        }
    }, 0)
</script>