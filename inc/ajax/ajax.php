<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}



function o10_ajax_theme() {
    // Function - data transfer in ajax request
    wp_localize_script(
        'scripts',
        'ajax_data',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce-12345-string'),
        )
    );
}
add_action('wp_enqueue_scripts', 'o10_ajax_theme');



/**
 * Before Checkout. Ajax.
 */
function o10_before_checkout() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) die;

    require_once get_template_directory() . '/partials/front/before-checkout.php';

    die;
}
add_action('wp_ajax_o10_before_checkout', 'o10_before_checkout');
add_action('wp_ajax_nopriv_o10_before_checkout', 'o10_before_checkout');

/**
 * SUSHI - variable product
 * Show a pop-up window before adding the product
 * to the cart to select one of the options
 */
function o10_show_popup_select_variable_product() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    $lang = 'null';
    $product_id = -1;
    if (isset($_REQUEST['lang'])) {
        $lang = esc_html($_REQUEST['lang']);
    }
    if (isset($_REQUEST['productId'])) {
        $product_id = esc_html($_REQUEST['productId']);
    }

    if ($product_id <= 0) {
        wp_send_json(array(
            'error' => true,
        ));
    }

    require_once get_template_directory() . '/partials/front/variable-product.php';

    die; // at the end of the function in ajax should be
}
add_action('wp_ajax_o10_show_popup_select_variable_product', 'o10_show_popup_select_variable_product');
add_action('wp_ajax_nopriv_o10_show_popup_select_variable_product', 'o10_show_popup_select_variable_product');

/**
 * PIZZA - variable + additional products
 * Show a pop-up window before adding a product to the cart
 * to select a product variant along with additional products
 */
function o10_show_popup_select_variant_with_additional_products() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    $lang = 'null';
    $product_id = -1;
    if (isset($_REQUEST['lang'])) {
        $lang = esc_html($_REQUEST['lang']);
    }
    if (isset($_REQUEST['productId'])) {
        $product_id = esc_html($_REQUEST['productId']);
    }

    if ($product_id <= 0) {
        wp_send_json(array(
            'error' => true,
        ));
    }

    require_once get_template_directory() . '/partials/front/variable-additional-products.php';

    die; // at the end of the function in ajax should be
}
add_action('wp_ajax_o10_show_popup_select_variant_with_additional_products', 'o10_show_popup_select_variant_with_additional_products');
add_action('wp_ajax_nopriv_o10_show_popup_select_variant_with_additional_products', 'o10_show_popup_select_variant_with_additional_products');

/**
 * @return void
 */
// o10_show_popup_select_grouped_products
function o10_show_popup_select_grouped_product() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    $lang = 'null';
    $product_id = -1;
    if (isset($_REQUEST['lang'])) {
        $lang = esc_html($_REQUEST['lang']);
    }
    if (isset($_REQUEST['productId'])) {
        $product_id = esc_html($_REQUEST['productId']);
    }

    if ($product_id <= 0) {
        wp_send_json(array(
            'error' => true,
        ));
    }

    require_once get_template_directory() . '/partials/front/popup-grouped-product.php';

    die; // at the end of the function in ajax should be
}
add_action('wp_ajax_o10_show_popup_select_grouped_product', 'o10_show_popup_select_grouped_product');
add_action('wp_ajax_nopriv_o10_show_popup_select_grouped_product', 'o10_show_popup_select_grouped_product');


function o10_update_cart() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    require_once get_template_directory() . '/partials/front/cart.php';
}
add_action('wp_ajax_o10_update_cart', 'o10_update_cart');
add_action('wp_ajax_nopriv_o10_update_cart', 'o10_update_cart');


function o10_woocommerce_ajax_add_to_cart() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }

//        WC_AJAX::get_refreshed_fragments();

        wp_send_json( array( 'success' => true ) );

    } else {

        wp_send_json( array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
        ) );
    }

    wp_die();
}
add_action('wp_ajax_o10_woocommerce_ajax_add_to_cart', 'o10_woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_o10_woocommerce_ajax_add_to_cart', 'o10_woocommerce_ajax_add_to_cart');


/**
 * Добавлення Компоновочного продукту та його компонентів
 */
add_action('wp_ajax_o10_add_products_to_cart', 'o10_add_products_to_cart');
add_action('wp_ajax_nopriv_o10_add_products_to_cart', 'o10_add_products_to_cart');
function o10_add_products_to_cart() {
    if(isset($_POST['products']) && is_array($_POST['products'])) {

        $composite_key = md5( microtime() . rand() );

        foreach($_POST['products'] as $product) {
            $product_id = absint( $product['product_id'] );
            $quantity = absint( $product['quantity'] );
            $variation_id = absint( $product['variation_id'] );
            $group_components = $product['group_components'];
            $id_components = $product['id_components'];

            $cart_item_data = array(
                'composite_product' => true,
            );

            if ( $group_components ) {
                foreach ( $group_components as $group_name => $components ) {
                    $cart_item_data[$group_name] = build_coment( $components );
                }

                $cart_item_data['keys'] = array_keys( $cart_item_data );
                $cart_item_data['id_components'] = $id_components;
            }

            $parent_id =  $product['parent'];
            if ( $parent_id ) {
                $cart_item_data['owner_product_id'] = $parent_id['product_id'];
                $cart_item_data['owner_variation_id'] = $parent_id['variation_id'];
            }

            $cart_item_data['composite_key'] = $composite_key;

            if($variation_id) {
                WC()->cart->add_to_cart($product_id, $quantity, $variation_id, array(), $cart_item_data);
            } else {
                WC()->cart->add_to_cart($product_id, $quantity, 0, array(), $cart_item_data);
            }

            unset( $cart_item_data );
        }

        echo 'Продукти успішно додано до кошика!';
    } else {
        echo 'Помилка: немає даних про продукти.';
    }

    wp_die();
}

function build_coment( $products ) {
    if ( ! $products ) return '';

    $info_products = '<ul class="info_products__list">';

    foreach ($products as $products_arr) {
        $product = wc_get_product( $products_arr['product_id'] );

        $info_products .= '<li class="info_products__item">';
        $info_products .= '<a class="info_products__link" href="'. get_permalink( $product->get_id() ) .'">';
        $info_products .= '<span class="info_products__title">'. $product->get_title() .'</span>';
        $info_products .= '</a>';
        $info_products .= '<span class="info_products__qty"> × '. $products_arr['quantity'] .'</span>';
        $info_products .= '</li>';
    }

    $info_products .= '</ul>';

    return $info_products;
}




// AJAX-обработчик для увеличения количества товаров в корзине
function update_cart_item_quantity_callback() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    if(isset($_POST['target_cart_item_keys']) && is_array($_POST['target_cart_item_keys'])) {
        $target_cart_item_keys = $_POST['target_cart_item_keys'];
        $operation = $_POST['operation'];
        $quantity = $_POST['quantity'];

        // Получаем текущую корзину
        WC()->cart->calculate_totals();

        if ( $operation === 'plus1' ) {

            // Збільшуємо кількість продуктів на 1
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

                if ( in_array( $cart_item_key, $target_cart_item_keys ) ) {
                    WC()->cart->set_quantity($cart_item_key, $cart_item['quantity'] + 1);
                }

            }

        }

        if ( $operation === 'minus1' ) {

            // Зменьшуємо кількість продуктів на 1
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

                if ( in_array( $cart_item_key, $target_cart_item_keys ) ) {
                    WC()->cart->set_quantity($cart_item_key, $cart_item['quantity'] - 1);
                }
            }
        }

        if ( $operation === 'set_quantity' ) {

            // Зменьшуємо кількість продуктів на 1
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

                if ( in_array( $cart_item_key, $target_cart_item_keys ) ) {
                    WC()->cart->set_quantity($cart_item_key, $quantity);
                }
            }
        }

    }

    wp_send_json( array( 'success' => true, 'message' => __('Товари успішно обновлені в корзині', 'americansuchi') ) );

    // Возвращаем обновленные фрагменты корзины
//    wc_get_template('cart/mini-cart.php', array('show_buttons' => true));
    wp_die();
}
add_action('wp_ajax_update_cart_item_quantity', 'update_cart_item_quantity_callback');
add_action('wp_ajax_nopriv_update_cart_item_quantity', 'update_cart_item_quantity_callback');





// REMOVE ALL ITEMS FROM CART
add_action('wp_ajax_o10_remove_items_from_cart', 'o10_remove_items_from_cart');
add_action('wp_ajax_nopriv_o10_remove_items_from_cart', 'o10_remove_items_from_cart');
function o10_remove_items_from_cart() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    $defaultHref = '';
    if (isset($_REQUEST['defaultHref'])) {
        $defaultHref = $_REQUEST['defaultHref'];
    }

    WC()->cart->empty_cart();

    wp_send_json(array(
        'message' => 'cart is empty',
        'defaultHref' => $defaultHref,
    ));

    wp_die();
}

//
//// calc delivery
//add_action('wp_ajax_o10_calc_delivery', 'o10_calc_delivery_callback');
//add_action('wp_ajax_nopriv_o10_calc_delivery', 'o10_calc_delivery_callback');
//function o10_calc_delivery_callback() {
//    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
//        die;
//    }
//
//    $details = $_REQUEST['details'];
//    $distance_meter = (int) $details['distance']['value']; // meter
//    $distance_km = ceil($distance_meter / 1000);
////    $duration = $details['duration']['text']; // minutes
////    $delivery_address = $details['delivery_address'];
//    $area = $details['area'];
//
//    $delivery_settings = get_field('shipping', 'options');
//    $min_distance = (int) $delivery_settings['min_distance']; //5
//    $max_distance = (int) $delivery_settings['max_distance']; //12
//    $price = (int) $delivery_settings['price']; //2
//
//    // Розрахунок нової вартості доставки відповідно до відстані
//    $shipping_cost = max($min_distance, $distance_km) * $price;
//
////    update_option_cost("71052", $new_shipping_cost);
//
////    if ($area && $area['last'] || !$area && $distance_km <= $max_distance) {
////        echo $new_shipping_cost;
////    } else {
////        echo 'Pick-up only';
////    }
//
//
////    WC()->shipping();
////    // Отримання загальної вартості
////    $cart_total = WC()->cart->get_subtotal();
////    $new_total_cost = $cart_total + $new_shipping_cost;
//
////    // Перевірка статусу сесій
////    if (session_status() === 2) {
////        $_SESSION['shipping_cost'] = $new_shipping_cost;
////        $_SESSION['total_cost'] = $new_total_cost;
////    }
//
//
//    // Відправлення нової вартості доставки та загальної вартості на сторінку клієнта
////    echo json_encode([
////        'shipping_cost' => wc_price($new_shipping_cost),
////        'total_cost' => wc_price($new_total_cost),
////        'shipping_cost_value' => $_SESSION['shipping_cost'],
////        'total_cost_value' => $_SESSION['total_cost'],
////    ]);
//
//    wp_die();
//}
