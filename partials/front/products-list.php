<?php
//include_once '_variable-product-functions.php';

$path = preg_replace('/wp-content.*$/', '', __DIR__);
include($path . 'wp-load.php');
$category = $_GET['category'];
$lang = $_GET['lang'];
$categoryname = $_GET['categoryname'];


echo '<h3 class="category-name" id="category-name1">' . $categoryname . '</h3>';

?>

<div class="row row-margin products">
    <?php

    // pl_PL
    $lang1 = "pl";
    $want = "Kupuję!";
    $want2 = "Dodano!";
    $want4 = "Brak!";
    $dfind = "Niestety nie znaleźliśmy takich produktów!";

    if ($lang == 'ru' || get_locale() == 'ru_RU') {
        $lang1 = "ru";
        $want = "Покупаю!";
        $dfind = "К сожалению, таких товаров мы не нашли!";
        $want2 = "Добавлен!";
        $want4 = "недостаток!";

    } elseif ($lang == 'ua' || get_locale() == 'ua_UA') {
        $lang1 = "ua";
        $want = "Купую!";
        $dfind = "На жаль, таких товарів ми не знайшли!";
        $want2 = "Додано!";
        $want4 = "відсутність!";
    }

    if ($category) {
        $args = array('post_type' => 'product', 'product_cat' => $category, 'lang' => $lang1, 'orderby' => 'menu_order', 'posts_per_page' => -1);
    } else {
        $args = array('post_type' => 'product', 'product_cat' => $args['first_category'], 'orderby' => 'menu_order', 'posts_per_page' => -1);
    }

    $query = new WP_Query($args);

    if ($query->have_posts()):
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class="lg33 sm33 xs100 padding-15 product txt-white offset-bottom-30 alignMe">
                <div class="lg100 product-content corner-radius">
                    <div class="row product-content-row justify-spaceb">
                        <div class="lg100 xs30 product-height corner-radius-img">
                            <a class="lg100 plink coverimg" href="<?php the_permalink(); ?>" id="<?php the_ID(); ?>">
                                <?php echo get_the_post_thumbnail( null, 'product-medium'); ?>
                            </a>
                        </div>
                        <a class="plink ptext xs70" href="<?php the_permalink(); ?>" id="<?php the_ID(); ?>">
                            <h3><?php the_title(); ?></h3>
                            <?php echo get_the_excerpt(); ?>
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

<?php if( ! wp_is_mobile()) { ?>
    <script>
        (function ($) {
            $(document).ready(function () {

                /* Open popup -- Variable + Additional Product */
                $('.product_type_variable.add_to_cart_button').on('click', function (event) {
                    event.preventDefault();
                    console.log('Var+AddProdPopUp');

                    if (blocked_shops === "calysklep") { alert(wecant);  return; }

                    let productId = $(this).attr('data-product_id');

                    const arr_url = window.location.href.split('/')
                    let lang_ru = arr_url.find((element) => element === 'ru' );
                    let lang_ua = arr_url.find((element) => element === 'ua' );

                    // Get Popup for select variant product
                    $.ajax({
                        // url: wc_add_to_cart_params.ajax_url,
                        url: ajax_data.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'o10_show_popup_select_variant_with_additional_products',
                            nonce: ajax_data.nonce,
                            lang: lang_ru || lang_ua || '',
                            productId: productId,
                        },
                        success: function (result) {
                            $('#before-checkout').html(result);
                        },
                        error: function (response) {
                            console.log(response)
                            $('#before-checkout').html(response.responseText);
                        },
                        beforeSend: function () {
                            $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
                            $('body')
                                .css({ paddingRight: `${window.innerWidth - document.body.offsetWidth}px` })
                                .addClass('body--preloader_show');
                        },
                        complete: function (response) {
                            if ( response.responseJSON?.error ) {
                                $('body')
                                    .css({ paddingRight: '0px' })
                                    .removeClass('body--preloader_show');
                            }
                        }
                    })
                });

                /* Add to from cart -- Simple Product */
                $('.product_type_simple.add_to_cart_button').on('click', function (event) {
                    event.preventDefault();
                    console.log('simple add to cart')

                    if (blocked_shops === "calysklep") { alert(wecant);  return; }

                    let product_id = $(this).attr('data-product_id');

                    // Get Popup for select variant product
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        // url: wc_add_to_cart_params.ajax_url,
                        url: ajax_data.ajaxUrl,
                        data: {
                            action: 'o10_woocommerce_ajax_add_to_cart',
                            nonce: ajax_data.nonce,
                            lang: Cookies.get('pll_language'),
                            product_id: product_id,
                            variation_id: 0,
                            quantity: 1,
                        },
                        success: function (result) {
                            $('#before-checkout').html(result);
                            updateShoppingCartAjax();
                        },
                        error: function (msg) {
                            console.log(msg);
                        },
                        beforeSend: function () {
                            $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
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
                });

                // UPDATE Cart
                function updateShoppingCartAjax() {
                    console.log( 'upd cart prodListPhp')

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
                            $('#product-sidebar-cart .cart-content').addClass('active-cart');
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
<?php } ?>