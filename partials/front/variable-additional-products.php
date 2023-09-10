<?php

// $product_id, $lang
// $lang = $_GET['lang'];

switch ($lang) {
    case 'ua':
        $button_text = 'Купую!';
        $title_text = 'Обери складові';
        $dodatki_title = 'Додатки';
        $skladniki_title = 'Інгредієнти';
        break;
    case 'ru':
        $button_text = 'Покупаю!';
        $title_text = 'Выбери состав';
        $dodatki_title = 'Дополнения';
        $skladniki_title = 'Ингредиенты';
        break;

    default:
        $button_text = 'Kupuję!';
        $title_text = 'Wybierz skŁadnik';
        $dodatki_title = 'Dodatki';
        $skladniki_title = 'Składniki';

}


$product = wc_get_product($product_id);
$available_variations = $product->get_available_variations();
$variations_json = wp_json_encode($available_variations);
$product_variations = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);

$attributes = $product->get_variation_attributes();

include_once '_variable-product-functions.php';
?>

<div class="variable_product variable_product--additional_product">
    <div class="variable_product__wrapper">

        <span onclick="document.body.classList.remove('body--preloader_show')"
              class="variable_product__close_btn"
              aria-label="button close popup variable product"><span></span></span>

        <h3 class="variable_product__title"><?php echo esc_html($product->get_title()); ?></h3>

        <div class="variable_product__container">
            <div class="variable_product__inner_container">

                <div class="variable_product__image_w">
                    <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>"  alt="img product"/>
                </div>

                <form
                    id="variable_product__form"
                    class="variable_product__form variations_form"
                    method="post"
                    enctype="multipart/form-data"
                    action="<?php echo esc_url($product->get_permalink()); ?>"
                    data-product_id="<?php echo absint($product->get_id()); ?>"
                    data-product_variations="<?php echo $product_variations; // WPCS: XSS ok. ?>">

                    <ul class="variable_product__list accordion_box">

<!-- Radio buttons ( Variants ) -->

                        <?php render_list_variants($product, $attributes); ?>

<!-- Checkboxes Components ( Additional / Ingredients ) -->

                        <?php
                        $components_prod = get_field('components', $product_id);
                        $additional_prod = get_field('additional', $product_id);
                        ?>

                        <?php if ($components_prod) : ?>
                            <?php render_list_components($components_prod, $skladniki_title); ?>
                        <?php endif; ?>

                        <?php if ($additional_prod) : ?>
                            <?php render_list_components($additional_prod, $dodatki_title); ?>
                        <?php endif; ?>

<!--  Additional Products                   -->

                        <?php render_list_additional_products(); ?>

                    </ul>

                    <input type="hidden" name="quantity" value="0"/>
                    <input type="hidden" name="add-to-cart" value="<?php echo absint($product_id); ?>"/>
                    <input type="hidden" name="product_id" value="<?php echo absint($product_id); ?>"/>
                    <input type="hidden" name="variation_id" class="variation_id" value="0"/>

                    <input type="hidden" name="additional_components" value=""/>
                    <input type="hidden" name="additional_products" value=""/>
                </form>
            </div>
        </div>

        <!-- button -->
        <div class="variable_product__btn_submit button button2">
            <button form="variable_product__form" type="submit" class="single_add_to_cart_button"
                    alt="<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>">
                <span><?php echo esc_html($button_text); ?></span>
            </button>
        </div>

    </div>
</div>

<script>
    (function ($) {
        $(document).ready(function () {
            // Accordion & height pop-up
            ;(() => {
                $('.variable_product--additional_product .variable_product__wrapper').css({
                    height: `${window.innerHeight - 40}px`,
                });

                const accordions = Array.from(document.querySelectorAll('.accordion_box'));

                new Accordion(accordions, {
                    duration: 300,
                    showMultiple: true,
                    openOnInit: [0, 1, 2],// [...Array(accordions[0].childElementCount).keys()],
                    onOpen: function (currentElement) {
                        console.log(currentElement);
                    }
                })
            })(); // - Accordion & height pop-up


            // Get and set the Variant ID of the product(s).
            ;(() => {
                $('input[name="variation_id"]').val(getVariantId());

                $('.variable_product__item--radio input[type="radio"]').on('change', function () {
                    $('input[name="variation_id"]').val(getVariantId());
                });

                function getVariantId() {
                    var variations = $('.variable_product__form').data('product_variations');
                    var attributes = {};

                    $('.variable_product__item--radio input[type="radio"]').each((i, elem) => {
                        var $elem = $(elem);

                        if ($elem.is(':checked')) {
                            var attrName = 'attribute_' + $elem.data('attribute_name');
                            attributes[attrName] = $elem.data('term_slug');
                        }
                    })

                    var m = findMatchingVariations(variations, attributes);

                    return m.length ? m[0].variation_id : 0;
                }

                function findMatchingVariations(variations, attributes) {
                    var matching = [];
                    for (var i = 0; i < variations.length; i++) {
                        var variation = variations[i];

                        if (isMatch(variation.attributes, attributes)) {
                            matching.push(variation);
                        }
                    }
                    return matching;
                }

                function isMatch(variation_attributes, attributes) {
                    var match = true;
                    for (var attr_name in variation_attributes) {

                        if (variation_attributes.hasOwnProperty(attr_name)) {
                            var val1 = variation_attributes[attr_name];
                            var val2 = attributes[attr_name];
                            if (val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2) {
                                match = false;
                            }
                        }
                    }
                    return match;
                }
            })();
            
            // Add IDs in Hide input - simple Components (additional_components)
            ;(() => {
                const $checkboxes = $('.simpleAdditionalComponents input[type="checkbox"]')

                function getAdditionalComponents( productIds = [] ) {

                    const components = {}

                    $checkboxes.each( (i, checkbox) => {
                        if ( checkbox.checked ) {
                            const groupName = checkbox.dataset.groupName;

                            const product = {
                                product_id: +checkbox.dataset.product_id,
                                quantity: 1,
                                group_name: groupName,
                                parent: productIds,
                            }

                            if ( ! components.hasOwnProperty(groupName) ) {
                                components[groupName] = []
                            }

                            components[groupName].push(product)
                        }
                    })

                    return JSON.stringify( components )
                }

                $('input[name="additional_components"]').val(getAdditionalComponents( getProductIds() ));

                $checkboxes.on('change', function (e) {
                    $('input[name="additional_components"]').val(getAdditionalComponents( getProductIds() ));
                })

                $('.variable_product__item--radio input[type="radio"]').on('change', function () {
                    console.log('radio change 2')
                    $('input[name="additional_components"]').val(getAdditionalComponents( getProductIds() ));
                });

                function getProductIds() {
                    return {
                        product_id: $('.variable_product__form').data('product_id'),
                        variation_id: $('.variable_product__form').find('input[name="variation_id"]').val(),
                    }
                }
            })();

            // Add IDs and Quantity in Hide input - simple Additional Products (additional_products)
            ;(() => {
                const $inputs = $('.simpleAdditionalProducts input[type="text"][data-product_id]')

                function getAdditionalProducts() {

                    const products = []

                    $inputs.each( (i, input) => {
                        if ( +input.value > 0 ) {
                            products.push( { product_id: +input.dataset.product_id, quantity: +input.value } )
                        }
                    })

                    return JSON.stringify( products )
                }

                $('input[name="additional_products"]').val(getAdditionalProducts());

                $inputs.on('change', function () {
                    $('input[name="additional_products"]').val(getAdditionalProducts());
                })
            })();

            // Add to Cart -- PRODUCTS
            ;(() => {
                $('.variable_product__btn_submit .single_add_to_cart_button').on('click', function (e) {
                    e.preventDefault();

                    $.ajax({
                        type: 'POST',
                        url: ajax_data.ajaxUrl,
                        data: {
                            action: 'o10_add_products_to_cart',
                            nonce: ajax_data.nonce,
                            lang: Cookies.get('pll_language'),
                            products: getProducts(),
                        },
                        beforeSend: function () {
                            $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
                        },
                        success: function (response) {
                            updateShoppingCart();

                            console.log( response )
                        },
                        error: function (error) {
                            console.error(error);
                        },
                        complete: function () {
                            $('body').removeClass('body--preloader_show').css({paddingRight: '0px'})
                        }
                    });
                });

                function getProducts() {
                    const components = JSON.parse( $('input[name="additional_components"]').val() )
                    components['Products'] = [...JSON.parse($('input[name="additional_products"]').val())]

                    return [
                        {
                            product_id: $('.variable_product__form').data('product_id'),
                            variation_id: $('.variable_product__form').find('input[name="variation_id"]').val(),
                            quantity: 1,
                            components,
                        },
                        ...Object.keys(components)
                            .map(key => {
                                return components[key]
                            })
                            .reduce((arr, currentValue) => [...currentValue, ...arr], []),
                    ];
                }
            })();

            // +/- buttons quantity
            ;(() => {

                // plus quantity (+)
                $('.variable_product__product_button.btn_plus').on('click', function (e) {
                    var $item = getItem(this)
                    var $inputQuantity = getInput( $item )

                    if ($item.hasClass('zero_product')) {
                        $item.removeClass('zero_product')
                        $inputQuantity.val(1)
                    } else {
                        var quantity = +$inputQuantity.val()
                        $inputQuantity.val(quantity + 1)
                    }

                    $inputQuantity.trigger('change')
                })

                // minus quantity (-)
                $('.variable_product__product_button.btn_minus').on('click', function (e) {
                    var $item = getItem(this)
                    var $inputQuantity = getInput( $item )

                    var quantity = +$inputQuantity.val();
                    $inputQuantity.val(quantity - 1).trigger('change')

                    if (quantity - 1 === 0) {
                        $item.addClass('zero_product')
                    }
                })

                // init 1 quantity
                $('.variable_product__product_text').on('click', function (e) {
                    var $item = getItem(this)
                    var $inputQuantity = getInput( $item )

                    if (+$inputQuantity.val() > 0) {
                        return;
                    }

                    if ($item.hasClass('zero_product')) {
                        $item.removeClass('zero_product')
                        $inputQuantity.val(1).trigger('change')
                    }
                })

                function getItem( elem ) {
                    return $(elem).closest('.simpleAdditionalProducts .variable_product__product_item')
                }

                function getInput( $elem ) {
                    return $elem.find('input[type="text"][data-product_id]')
                }


            })();


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

