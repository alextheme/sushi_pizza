<?php

$path = preg_replace('/wp-content.*$/', '', __DIR__);
include($path . 'wp-load.php');
$category = $_GET['category'];
$lang = $_GET['lang'];
$categoryname = $_GET['categoryname'];


echo '<h3 class="category-name" id="category-name1">' . $categoryname . '</h3>';

?>

<div class="row row-margin products alignerContainer">
    <?php

    $lang1 = "pl";
    $want = "Kupuję!";
    $want2 = "Dodano!";
    $want4 = "Brak!";
    $dfind = "Niestety nie znaleźliśmy takich produktów!";
    if ($lang == 'ru') {
        $lang1 = "ru";
        $want = "Покупаю!";
        $dfind = "К сожалению, таких товаров мы не нашли!";
        $want2 = "Добавлен!";
        $want4 = "недостаток!";
    } elseif ($lang == 'ua') {
        $lang1 = "ua";
        $want = "Купую!";
        $dfind = "На жаль, таких товарів ми не знайшли!";
        $want2 = "Додано!";
        $want4 = "відсутність!";
    }

    if (get_locale() == 'ru_RU') {
        $lang1 = "ru";
        $want = "Покупаю!";
        $dfind = "К сожалению, таких товаров мы не нашли!";
        $want2 = "Добавлен!";
    } elseif (get_locale() == 'ua_UA') {
        $lang1 = "ua";
        $want = "Купую!";
        $dfind = "На жаль, таких товарів ми не знайшли!";
        $want2 = "Додано!";
    }


    if ($category) {
        $args = array('post_type' => 'product', 'product_cat' => $category, 'lang' => $lang1, 'orderby' => 'menu_order', 'posts_per_page' => 20);
    } else {
        if (get_locale() == 'ru_RU') {
            $cat = "сеты";
        } elseif (get_locale() == 'ua_UA') {
            $lang1 = "сети";
        } else {
            $cat = "zestawy";
        }
        $args = array('post_type' => 'product', 'product_cat' => $cat, 'orderby' => 'menu_order', 'posts_per_page' => 20);
    }

    $query = new WP_Query($args);

    if ($query->have_posts()):
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class="lg33 sm33 xs100 padding-15 product txt-white offset-bottom-30 alignMe">
                <div class="lg100 product-content corner-radius">
                    <div class="row product-content-row justify-spaceb">
                        <div class="lg100 xs30 product-height corner-radius-img">
                            <a class="lg100 plink coverimg" href="<?php the_permalink(); ?>" id="<?php the_ID(); ?>">
                                <?php the_post_thumbnail(); ?>
                            </a>
                        </div>
                        <a class="plink ptext xs70" href="<?php the_permalink(); ?>" id="<?php the_ID(); ?>">
                            <h3><?php the_title(); ?></h3>
                            <?php echo mb_strcut(get_the_excerpt(), 0, 135); ?>...
                        </a>
                    </div>
                    <div class="lg100 product-footer">
                        <span class="price">
                            <?php $product = wc_get_product(get_the_ID()); echo $product->get_price(); ?> zł
                        </span>
                        <?php if ($product->is_in_stock()) {
                            echo sprintf('<a href="%s" data-quantity="1" class="%s" %s>' . $want . '<span class="was-added">' . $want2 . '</span></a>',
                                esc_url($product->add_to_cart_url()),
                                esc_attr(implode(' ', array_filter(array(
                                    'button',
                                    'product_type_' . $product->get_type(),
                                    $product->is_purchasable() && $product->is_in_stock()
                                        ? 'add_to_cart_button'
                                        : 'dsdsds',
                                    $product->supports('ajax_add_to_cart')
                                        ? 'ajax_add_to_cart' : ''
                                )))),
                                wc_implode_html_attributes(array(
                                    'data-product_id' => $product->get_id(),
                                    'data-product_sku' => $product->get_sku(),
                                    'aria-label' => $product->add_to_cart_description(),
                                    'rel' => 'nofollow'
                                )),
                                esc_html($product->add_to_cart_text())
                            );
                        } else {
                            echo '<a class="button not_in_stock" href="' . get_permalink() . '">' . $want4 . '</a>';
                        } ?>
                    </div>

                    <div class="card-bckg"></div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_query();
    else:  // Remember to reset ?>
        <div class="lg100"><h2 class="p-top text-center"><?php echo $dfind; ?></h2></div>
    <?php endif; ?>
</div>

<script>
    (function ($) {
        $(document).ready(function () {


            // ADD TO CART -- PRODUCT
            (() => {
                $('.add_to_cart_button').on('click', function(e) {
                    e.preventDefault();

                    console.log( 'product-list.php | add to cart')

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
            })(); // - ADD TO CART PRODUCT


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