<?php

// $product_id, $lang
// $lang = $_GET['lang'];

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
        $title_text = 'Wybierz skŁadnik';

}

$product = wc_get_product( $product_id );
$attributes = $product->get_variation_attributes();
//$attributes = $product->get_available_variations();
$available_variations = $product->get_available_variations();

$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

?>

<div class="variable_product variable_product--additional_product">
    <div class="variable_product__wrapper">
        <span class="variable_product__close_btn" aria-label="button close popup variable product"><span></span></span>

        <h3 class="variable_product__title"><?php echo esc_html( $product->get_title() ); ?></h3>

        <div class="variable_product__container">
            <div class="variable_product__inner_container">

                <div class="variable_product__image_w">
                    <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" />
                </div>

                <form
                    id="variable_product__form"
                    class="variable_product__form variations_form"
                    method="post"
                    enctype="multipart/form-data"
                    action="<?php echo esc_url( $product->get_permalink() ); ?>"
                    data-product_id="<?php echo absint( $product->get_id() ); ?>"
                    data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">

                <?php $attr_i = 0;
                foreach ( $attributes as $attribute_name => $options ) : ?>
                    <?php $attribute_label = wc_attribute_label( $attribute_name ); ?>

                    <?php if($attr_i === 0) : ?>

                        <h4 class="variable_product__attr_title"><?php echo esc_html( $title_text ); ?></h4>
                        <ul class="variable_product__list">

                            <?php
                            $opt_i = 0;
                            foreach ($options as $option) :
                                $selected = $product->get_variation_default_attribute( $attribute_name );
                                $term = get_term_by( 'slug', $option, $attribute_name );
                                $default_select = $selected ? $selected === $option : $opt_i === 0;
                                ?>

                                <li class="variable_product__item">
                                    <input type="radio"
                                           name="variable_<?php echo esc_attr( $attribute_name ); ?>"
                                           class="variable_product__input variable_product__input_<?php echo esc_attr( $attr_i ); ?>"
                                           id="variable_<?php echo esc_attr( $attribute_name.'_'.$option ); ?>"
                                           data-attribute_name="<?php echo esc_attr( $attribute_name ); ?>"
                                           data-term_id="<?php echo esc_attr( $term->term_id ); ?>"
                                           data-term_slug="<?php echo esc_attr( $term->slug ); ?>"
                                        <?php echo $default_select ? 'checked="checked"' : ''; ?>
                                    >
                                    <label class="variable_product__label" for="variable_<?php echo esc_attr( $attribute_name.'_'.$option ); ?>">
                                        <span class="variable_product__item_title"><?php echo esc_html( $term->name ); ?></span>
                                        <span class="variable_product__icon"></span>
                                    </label>
                                </li>

                            <?php $opt_i++; endforeach; ?>
                        </ul>

                    <?php else : ?>

                        <ul class="variable_product__list accordion_box">
                            <li class="ac variable_product__item variable_product__item--checkbox">
                                <h4 class="ac-header">
                                    <button type="button" class="ac-trigger variable_product__attr_title"><?php echo esc_html( $title_text ); ?></button>
                                    <div class="variable_product__attr_title_icon">
                                        <svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.86603 7.5C5.48113 8.16667 4.51887 8.16667 4.13397 7.5L0.669874 1.5C0.284974 0.833333 0.7661 -8.94676e-07 1.5359 -8.27378e-07L8.4641 -2.21695e-07C9.2339 -1.54397e-07 9.71503 0.833333 9.33013 1.5L5.86603 7.5Z" fill="#FCB326"/>
                                        </svg>
                                    </div>
                                </h4>

                                <div class="ac-panel">
                                    <ul class="variable_product__terms">
                                        <?php
                                        $opt_i = 0;
                                        foreach ($options as $option) :
                                            $selected = $product->get_variation_default_attribute( $attribute_name );
                                            $term = get_term_by( 'slug', $option, $attribute_name );
                                            $default_select = $selected ? $selected === $option : $opt_i === 0;
                                            ?>
                                            <li class="variable_product__term">
                                                <input type="checkbox"
                                                       name="variable_<?php echo esc_attr( $attribute_name ); ?>"
                                                       class="variable_product__input variable_product__input_<?php echo esc_attr( $attr_i ); ?>"
                                                       id="variable_<?php echo esc_attr( $attribute_name.'_'.$option ); ?>"
                                                       data-attribute_name="<?php echo esc_attr( $attribute_name ); ?>"
                                                       data-term_id="<?php echo esc_attr( $term->term_id ); ?>"
                                                       data-term_slug="<?php echo esc_attr( $term->slug ); ?>"
                                                    <?php echo $default_select ? 'checked="checked"' : ''; ?>
                                                >
                                                <label class="variable_product__label" for="variable_<?php echo esc_attr( $attribute_name.'_'.$option ); ?>">
                                                    <span class="variable_product__checkbox_icon">
                                                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect class="rect_border" stroke="#FCB326" x="0.5" y="0.5" width="18" height="18" rx="3.5"/>
                                                            <path class="rect_checked" fill="#FCB326" d="M15.2585 5.36598C15.4929 5.60039 15.6245 5.91828 15.6245 6.24973C15.6245 6.58119 15.4929 6.89907 15.2585 7.13348L9.00853 13.3835C8.77412 13.6178 8.45623 13.7495 8.12478 13.7495C7.79332 13.7495 7.47544 13.6178 7.24103 13.3835L4.74103 10.8835C4.51333 10.6477 4.38734 10.332 4.39018 10.0042C4.39303 9.67649 4.52449 9.36297 4.75625 9.13121C4.98801 8.89945 5.30153 8.76799 5.62927 8.76514C5.95702 8.76229 6.27277 8.88829 6.50853 9.11598L8.12478 10.7322L13.491 5.36598C13.7254 5.13164 14.0433 5 14.3748 5C14.7062 5 15.0241 5.13164 15.2585 5.36598Z"/>
                                                        </svg>
                                                    </span>
                                                    <span class="variable_product__item_title"><?php echo esc_html( $term->name ); ?></span>
                                                </label>
                                            </li>
                                        <?php $opt_i++; endforeach; ?>
                                    </ul>
                                </div>
                            </li>
                        </ul>

                    <?php endif; ?>

                <?php $attr_i++; endforeach; ?>

                </form>

            </div>
        </div>

        <!-- button -->
        <div class="woocommerce-variation-add-to-cart variations_button variable_product__btn_submit button button2">

            <button form="variable_product__form" type="submit" class="single_add_to_cart_button" alt="<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>">
                <span><?php echo esc_html( $button_text ); ?></span>
            </button>

            <input type="hidden" name="quantity" value="0" />
            <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
            <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
            <input type="hidden" name="variation_id" class="variation_id" value="0" />
        </div>

    </div>
</div>

<script>
    (function ($) {
        $(document).ready(function () {
            var accordions = Array.from($('.accordion_box'));
            new Accordion(accordions, {});


            // CLOSED POPUP SELECT VARIANT
            (() => {
                $('.variable_product__close_btn').on('click', e => {
                    $('body').removeClass('body--preloader_show');
                });
            })();// - CLOSED POPUP SELECT VARIANT


            // GET Variant Product ID
            (() => {
                $('input[name="variation_id"]').val(getVariantId());

                $('.variable_product__input').on('change', function() {
                    var selected = $('.variable_product__input_0:checked');
                    $('input[name="variation_id"]').val( getVariantId() );
                });

                function getVariantId() {
                    var variations = $('.variable_product__form').data('product_variations');
                    var attributes = {};

                    $('.variable_product__input').each( (i, elem) => {
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


            // ADD TO CART -- VARIABLE PRODUCT
            (() => {
                $('.variable_product__btn_submit .single_add_to_cart_button').on('click', function(e) {
                    e.preventDefault();

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

