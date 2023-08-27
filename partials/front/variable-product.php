<?php

//  $product_id, $lang
//$lang = $_GET['lang'];

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

$product = wc_get_product( $product_id );
$attributes = $product->get_variation_attributes();
//$attributes = $product->get_available_variations();
$available_variations = $product->get_available_variations();

$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

?>

<div class="variable_product">
    <div class="variable_product__wrapper">
        <span class="variable_product__close_btn" aria-label="button close popup variable product"><span></span></span>

        <form
            class="variable_product__form variations_form"
            method="post"
            enctype="multipart/form-data"
            action="<?php echo esc_url( $product->get_permalink() ); ?>"
            data-product_id="<?php echo absint( $product->get_id() ); ?>"
            data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">

        <?php $attr_i = 0;
        foreach ( $attributes as $attribute_name => $options ) { ?>
            <?php $attribute_label = wc_attribute_label( $attribute_name ); ?>

            <h5 class="variable_product__title"><?php echo esc_html( $title_text ); ?><?php //echo esc_html( $attribute_label ); ?></h5>
            <ul class="variable_product__list">

                <?php
                $opt_i = 0;
                foreach ($options as $option) {
                    $selected = $product->get_variation_default_attribute( $attribute_name );
                    $term = get_term_by( 'slug', $option, $attribute_name );
                    $default_select = $selected ? $selected === $option : $opt_i === 0;
                    ?>
                    <li class="variable_product__item">
                        <input type="radio"
                               name="variable_<?php echo esc_attr( $attribute_name ); ?>"
                               class="variable_product__radio variable_product__radio_<?php echo esc_attr( $attr_i ); ?>"
                               id="variable_<?php echo esc_attr( $attribute_name.'_'.$option ); ?>"
                               data-attribute_name="<?php echo esc_attr( $attribute_name ); ?>"
                               data-term_id="<?php echo esc_attr( $term->term_id ); ?>"
                               data-term_slug="<?php echo esc_attr( $term->slug ); ?>"
                            <?php echo $default_select ? 'checked="checked"' : ''; ?>
                        >
                        <label class="variable_product__radio_label" for="variable_<?php echo esc_attr( $attribute_name.'_'.$option ); ?>">
                            <span class="variable_product__radio_title"><?php echo esc_html( $term->name ); ?></span>
                            <span class="variable_product__icon"></span>
                        </label>
                    </li>
                <?php $opt_i++; } ?>

                </ul>
            <?php $attr_i++; } ?>

<!--            button-->
            <div class="woocommerce-variation-add-to-cart variations_button variable_product__btn_submit button button2">

                <button type="submit" class="single_add_to_cart_button" alt="<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>">
<!--                    <span>--><?php //echo esc_html( $product->single_add_to_cart_text() ); ?><!--</span>-->
                    <span><?php echo esc_html( $button_text ); ?></span>
                </button>

                <input type="hidden" name="quantity" value="0" />
                <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
                <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
                <input type="hidden" name="variation_id" class="variation_id" value="0" />
            </div>

        </form>
    </div>
</div>

<script>
    (function ($) {
        $(document).ready(function () {

            // CLOSED POPUP SELECT VARIANT
            (() => {
                $('.variable_product__close_btn').on('click', e => {
                    $('body').removeClass('variable_popup');
                });
            })();// - CLOSED POPUP SELECT VARIANT


            // GET Variant Product ID
            (() => {
                $('input[name="variation_id"]').val(getVariantId());

                $('.variable_product__radio').on('change', function() {
                    var selected = $('.variable_product__radio_0:checked');
                    $('input[name="variation_id"]').val( getVariantId() );
                });

                function getVariantId() {
                    var variations = $('.variable_product__form').data('product_variations');
                    var attributes = {};

                    $('.variable_product__radio').each( (i, elem) => {
                        var $elem = $(elem);

                        if ( $elem.is(':checked' ) ) {
                            var attrName = 'attribute_' + $elem.data( 'attribute_name' );
                            attributes[attrName] = $elem.data( 'term_slug' );
                        }
                    })

                    var m = findMatchingVariations(variations, attributes);

                    return m.length ? m[0].variation_id : 0;
                }

                function findMatchingVariations( variations, attributes ) {
                    var matching = [];
                    for ( var i = 0; i < variations.length; i++ ) {
                        var variation = variations[i];

                        if ( isMatch( variation.attributes, attributes ) ) {
                            matching.push( variation );
                        }
                    }
                    return matching;
                }

                function isMatch( variation_attributes, attributes ) {
                    var match = true;
                    for ( var attr_name in variation_attributes ) {

                        if ( variation_attributes.hasOwnProperty( attr_name ) ) {
                            var val1 = variation_attributes[ attr_name ];
                            var val2 = attributes[ attr_name ];
                            if ( val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2 ) {
                                match = false;
                            }
                        }
                    }
                    return match;
                }
            })(); // - GET Variant Product ID


            // ADD TO CART VARIABLE PRODUCT
            (() => {
                $('.variable_product__btn_submit .single_add_to_cart_button').on('click', function(e) {
                    e.preventDefault();

                    console.log( 'single_add_to_cart_button | variable-producn.php' )

                    var variation_id = $('.variations_form').find('input[name="variation_id"]').val();
                    var quantity = 1;

                    $.ajax({
                        type: 'POST',
                        url: ajax_data.ajaxUrl,
                        data: {
                            action: 'o10_add_to_cart_variable_product',
                            nonce: ajax_data.nonce,
                            lang: Cookies.get('pll_language'),
                            product_id: variation_id,
                            quantity: quantity,
                        },
                        success: function(response) {
                            // Оновити міні-кошик або виконати інші дії
                            console.log( response || 'Товар додано до кошика.');

                            $('body').removeClass('variable_popup');

                            updateShoppingCart();
                        },
                        error: function (error) {
                            console.error(error);
                            $('body').removeClass('variable_popup');
                        },
                        beforeSend: function () {
                            $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
                        }
                    });
                });
            })(); // - ADD TO CART VARIABLE PRODUCT


            function updateShoppingCart() {

                console.log( 'update Shopping Cart | before-checkout-variable-product' )

                $.ajax({
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
                    }
                })
            }

        })
    })(jQuery);
</script>

