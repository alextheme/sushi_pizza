<?php

$path = preg_replace('/wp-content.*$/', '', __DIR__);
include($path . 'wp-load.php');

$lang = $_GET['lang'];


$category1 = "checkout";
$url = "/zamowienie";
$url = home_url('/pl/zamowienie');//esc_url(get_permalink(get_page_by_title('Koszyk')));
$order = "Nie, dziękuję";
$dfind = "Niestety nie znaleźliśmy takich produktów!";
$lang1 = "pl";
$header = "Dodatki  do zamowlenia";
if ($lang == 'ru') {
    $header = "Дополнения к заказу";
    $category1 = "checkout-ru";
    $url = home_url( '/ru/заказ/' );
    $order = "Нет, спасибо";
    $dfind = "К сожалению, таких товаров мы не нашли!";
    $lang1 = "ru";
} elseif ($lang == 'ua') {
    $header = "Доповнення до замовлення";
    $category1 = "checkout-ua";
    $url = home_url( '/ua/замовлення/' );
    $order = "Ні, дякую";
    $dfind = "На жаль, таких товарів ми не знайшли!";
    $lang1 = "ua";
}

?>
<div class="container">
    <section class="before_checkout__header lg100 offset-top-30 d-flex justify-spaceb">
        <h2 class="before_checkout__header_title"><?php echo $header; ?></h2>

        <div class="before_checkout__header_btn_w">
            <a href="<?php echo $url; ?>" class="before_checkout__header_btn button2 button-before">
                <span><img src="<?php echo esc_url( get_template_directory_uri() . '/images/icons/icon_shop.svg' ); ?>" alt=""> <?php echo $order; ?></span>
            </a>
        </div>

        <span class="before_checkout__close_btn" aria-label="button close popup before checkout"><span></span></span>

    </section>
    <div class="row row-margin products before-check">
        <?php

        $args = array('post_type' => 'product', 'product_cat' => $category1, 'lang' => $lang1, 'posts_per_page' => 20);
        $query = new WP_Query($args);

        if ($query->have_posts()): while ($query->have_posts()) : $query->the_post(); ?>
            <div class="lg25 sm33 xs100 padding-15 product offset-bottom-30 alignMe">
                <div class="lg100 product-content corner-radius">
                    <div class="row product-content-row justify-spaceb">
                        <div class="lg100 xs30 product-height corner-radius-img">
                            <a class="lg100 plink coverimg" href="<?php the_permalink(); ?>" id="<?php the_ID(); ?>">
                                <?php the_post_thumbnail(); ?>
                            </a>
                        </div>
                        <a class="plink ptext xs50" href="<?php the_permalink(); ?>" id="<?php the_ID(); ?>">
                            <h3><?php the_title(); ?></h3>
                            <?php echo mb_strcut(get_the_excerpt(), 0, 45); ?>...
                        </a>
                    </div>
                    <div class="lg100 product-footer">
                        <span class="price"><?php $product = wc_get_product(get_the_ID());
                            echo $product->get_price(); ?> zł</span>
                        <?php echo sprintf('<a href="%s" pamount="0" data-quantity="1" class="%s" %s>' . pll__('Chcę') . '</a>',
                            esc_url($product->add_to_cart_url()),
                            esc_attr(implode(' ',
                                array_filter(array(
                                    'button',
                                    'product_type_' . $product->get_type(),
                                    $product->is_purchasable() && $product->is_in_stock()
                                        ? 'add_to_cart_button' : '',
                                    $product->supports('ajax_add_to_cart')
                                        ? 'ajax_add_to_cart' : ''
                                ))
                            )),
                            wc_implode_html_attributes(array(
                                'data-product_id' => $product->get_id(),
                                'data-product_sku' => $product->get_sku(),
                                'aria-label' => $product->add_to_cart_description(),
                                'rel' => 'nofollow'
                            )),
                            esc_html($product->add_to_cart_text())
                        ); ?>
                    </div>
                    <div class="card-bckg"></div>
                </div>
            </div>
        <?php endwhile;
            wp_reset_query(); else:  // Remember to reset ?>
            <div class="lg100"><h2 class="p-top text-center"><?php echo $dfind; ?></h2></div>
        <?php endif; ?>
    </div>
</div>

<script>
    // before_checkout__header_btn

    (function ($) {
        $(document).ready(function () {

            $('.before_checkout__close_btn').on('click', function (e) {
                e.preventDefault();
                $('.before-checkout-products').removeClass('active-prom')
                $('body').css( { overflow: 'unset' })
            })

        })
    })(jQuery);

</script>
