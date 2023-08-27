<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}



function o10_ajax_data() {
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
add_action('wp_enqueue_scripts', 'o10_ajax_data');



/**
 * Before Checkout. Ajax.
 */
function o10_before_checkout() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    $lang = 'null';
    if (isset($_REQUEST['lang'])) {
        $lang = esc_html($_REQUEST['lang']);
    }

    require_once get_template_directory() . '/partials/front/before-checkout.php';

    // at the end of the function in ajax should be
    die;
}
add_action('wp_ajax_o10_before_checkout', 'o10_before_checkout');
add_action('wp_ajax_nopriv_o10_before_checkout', 'o10_before_checkout');



/**
 * Add to cart Simple product
 */
function o10_add_to_cart_simple_product() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    $lang = 'null';
    $product_id = -1;
    if (isset($_REQUEST['lang'])) {
        $lang = esc_html($_REQUEST['lang']);
    }
    if (isset($_REQUEST['product_id'])) {

        $product_id = intval($_REQUEST['product_id']);

        // Додаємо товар до кошика
        WC()->cart->add_to_cart($product_id);
//        WC()->cart->add_to_cart($product_id, $quantity);
        WC_AJAX::get_refreshed_fragments();

        echo 'The Variable Product has been added to the cart.';
    }
    die; // at the end of the function in ajax should be
}
add_action('wp_ajax_o10_add_to_cart_simple_product', 'o10_add_to_cart_simple_product');
add_action('wp_ajax_nopriv_o10_add_to_cart_simple_product', 'o10_add_to_cart_simple_product');



/**
 * Show popup before added to cart Variable product
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

    require_once get_template_directory() . '/partials/front/variable-product.php';

    die; // at the end of the function in ajax should be
}
add_action('wp_ajax_o10_show_popup_select_variable_product', 'o10_show_popup_select_variable_product');
add_action('wp_ajax_nopriv_o10_show_popup_select_variable_product', 'o10_show_popup_select_variable_product');


function o10_add_to_cart_variable_product() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);

        WC()->cart->add_to_cart($product_id, $quantity);
        WC_AJAX::get_refreshed_fragments();

        echo 'The Variable Product has been added to the cart.';
    }

    wp_die();
}
add_action('wp_ajax_o10_add_to_cart_variable_product', 'o10_add_to_cart_variable_product');
add_action('wp_ajax_nopriv_o10_add_to_cart_variable_product', 'o10_add_to_cart_variable_product');



function o10_update_cart() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    require_once get_template_directory() . '/partials/front/cart.php';
}
add_action('wp_ajax_o10_update_cart', 'o10_update_cart');
add_action('wp_ajax_nopriv_o10_update_cart', 'o10_update_cart');



function o10_remove_product_from_cart() {
    if (isset($_POST['cart_item_key'])) {
//        $cart_item_key = sanitize_key($_POST['cart_item_key']);
//
//        // Видаляємо товар з кошика за ключем
//        WC()->cart->remove_cart_item($cart_item_key);
//
//        // Оновити дані кошика
//        WC()->cart->calculate_totals();
//
//        // Отримати HTML-код списку товарів у кошику
//        ob_start();
//        wc_get_template_part('mini-cart');
//        $cart_html = ob_get_clean();
//
//        // Підготувати дані для відправки у відповідь
//        $response = array(
//            'cart_count' => WC()->cart->get_cart_contents_count(),
//            'cart_html' => $cart_html
//        );
//
//        // Надіслати відповідь у форматі JSON
//        wp_send_json($response);

        echo '100';
    }
}
add_action('wp_ajax_o10_remove_product_from_cart', 'o10_remove_product_from_cart');
add_action('wp_ajax_nopriv_o10_remove_product_from_cart', 'o10_remove_product_from_cart');