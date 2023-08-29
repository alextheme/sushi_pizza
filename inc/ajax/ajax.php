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
    if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax-nonce-12345-string')) {
        die;
    }

    $lang = 'null';
    if (isset($_REQUEST['lang'])) {
        $lang = esc_html($_REQUEST['lang']);
    }

    require_once get_template_directory() . '/partials/front/before-checkout.php';

    die;
}
add_action('wp_ajax_o10_before_checkout', 'o10_before_checkout');
add_action('wp_ajax_nopriv_o10_before_checkout', 'o10_before_checkout');

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

    if ($product_id <= 0) {
        return wp_send_json(array(
            'error' => true,
        ));
    }

    require_once get_template_directory() . '/partials/front/variable-product.php';

    die; // at the end of the function in ajax should be
}
add_action('wp_ajax_o10_show_popup_select_variable_product', 'o10_show_popup_select_variable_product');
add_action('wp_ajax_nopriv_o10_show_popup_select_variable_product', 'o10_show_popup_select_variable_product');


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
        $cart_item_key = sanitize_key($_POST['cart_item_key']);

        // Видаляємо товар з кошика за ключем
        WC()->cart->remove_cart_item($cart_item_key);
    }

    die();
}
add_action('wp_ajax_o10_remove_product_from_cart', 'o10_remove_product_from_cart');
add_action('wp_ajax_nopriv_o10_remove_product_from_cart', 'o10_remove_product_from_cart');


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

        WC_AJAX :: get_refreshed_fragments();
    } else {

        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

        echo wp_send_json($data);
    }

    wp_die();
}
add_action('wp_ajax_o10_woocommerce_ajax_add_to_cart', 'o10_woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_o10_woocommerce_ajax_add_to_cart', 'o10_woocommerce_ajax_add_to_cart');