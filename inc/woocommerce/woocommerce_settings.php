<?php

if ( !in_array( 'woocommerce/woocommerce', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    add_action( 'wp', 'my_remove_sidebar_product_pages' );
    function my_remove_sidebar_product_pages() {
        if ( is_product() ) {
            remove_action( 'woocommerce_sidebar','woocommerce_get_sidebar', 10 );
        }
    }



    add_action('after_setup_theme', 'woocommerce_support');
    function woocommerce_support() {
        add_theme_support('woocommerce');
    }



    //Quantity change buttons

    // Remove product in the cart using ajax
    function warp_ajax_product_remove() {
        // Get mini cart
        ob_start();

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
        {
            if($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key'] )
            {
                if($_POST['todo'] == "product_remove"){
                    WC()->cart->remove_cart_item($cart_item_key);
                }elseif ($_POST['todo'] == "product_update"){
                    WC()->cart->set_quantity($cart_item_key, $_POST['amount']);
                    if($_POST['amount'] == 0){
                        WC()->cart->remove_cart_item($cart_item_key);
                    }
                }

            }
        }

        WC()->cart->calculate_totals();
        WC()->cart->maybe_set_cart_cookies();

        woocommerce_mini_cart();

        $mini_cart = ob_get_clean();

        // Fragments and mini cart are returned
        $data = array(
            'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
                    'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                )
            ),
            'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
        );

        wp_send_json( $data );

        die();
    }
    add_action( 'wp_ajax_product_remove', 'warp_ajax_product_remove' );
    add_action( 'wp_ajax_nopriv_product_remove', 'warp_ajax_product_remove' );



    /*
    add_filter( 'woocommerce_checkout_order_processed', 'sendSmsOrder' );

    function sendSmsOrder($order_id) {

        $order = wc_get_order( $order_id );
        $link_address =  $order->get_checkout_order_received_url();

        $ww = sendSms("883089982","Nr. zam: ".$order->get_id().", link: ".$link_address);

    }
    */



    add_filter( 'woocommerce_get_order_item_totals', 'bbloomer_add_recurring_row_email', 10, 2 );
    function bbloomer_add_recurring_row_email( $total_rows, $order ) {

        printOrder($order, '6891', 'Online');

        $total_rows['recurr_not'] = array(
            'label' => __( 'Addres:', 'woocommerce' ),
            'value'   =>  $order->billing_address_1
        );
        if( get_post_meta( $order->get_id(), 'delivery_date', true )){
            $total_rows['recurr_not1'] = array(
                'label' => __( 'Przedział czasowy:', 'woocommerce' ),
                'value'   =>   get_post_meta( $order->get_id(), 'delivery_date', true )
            );
        }
        if( get_post_meta( $order->get_id(), 'after_deliver', true )){
            $total_rows['recurr_not2'] = array(
                'label' => __( 'Po dojechaniu:', 'woocommerce' ),
                'value'   =>   get_post_meta( $order->get_id(), 'after_deliver', true )
            );
        }
        if( get_post_meta( $order->get_id(), 'apartment', true )){
            $total_rows['recurr_not3'] = array(
                'label' => __( 'Mieszkanie:', 'woocommerce' ),
                'value'   =>   get_post_meta( $order->get_id(), 'apartment', true )
            );
        }
        if( get_post_meta( $order->get_id(), 'square', true )){
            $total_rows['recurr_not4'] = array(
                'label' => __( 'Klatka:', 'woocommerce' ),
                'value'   =>   get_post_meta( $order->get_id(), 'square', true )
            );
        }
        if( get_post_meta( $order->get_id(), 'floor', true )){
            $total_rows['recurr_not5'] = array(
                'label' => __( 'Piętro:', 'woocommerce' ),
                'value'   =>   get_post_meta( $order->get_id(), 'floor', true )
            );
        }
        if( get_post_meta( $order->get_id(), 'delivery_way', true )){
            $total_rows['recurr_not6'] = array(
                'label' => __( 'Sposób dostawy:', 'woocommerce' ),
                'value'   =>   get_post_meta( $order->get_id(), 'delivery_way', true )
            );
        }

        $total_rows['recurr_not7'] = array(
            'label' => __( 'Telefon:', 'woocommerce' ),
            'value'   =>  $order->billing_phone
        );

        return $total_rows;
    }



    add_action( 'woocommerce_checkout_update_order_meta', 'custom_checkout_fields_update_order_meta' );
    function custom_checkout_fields_update_order_meta( $order_id ) {

        update_post_meta( $order_id, 'delivery_way', sanitize_text_field( $_POST['delivery_way'] ) );
        update_post_meta( $order_id, 'apartment', sanitize_text_field( $_POST['apartment'] ) );
        update_post_meta( $order_id, 'square', sanitize_text_field( $_POST['square'] ) );
        update_post_meta( $order_id, 'floor', sanitize_text_field( $_POST['floor'] ) );
        update_post_meta( $order_id, 'after_deliver', sanitize_text_field( $_POST['after_deliver'] ) );
        update_post_meta( $order_id, 'delivery_date', sanitize_text_field( $_POST['delivery_date'] ) );
    }



    // Hook in
    add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
    // Our hooked in function - $fields is passed via the filter!
    function custom_override_checkout_fields( $fields ) {
        unset($fields['billing']['billing_last_name']);
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);;
        unset($fields['billing']['billing_email']);
        unset($fields['billing']['billing_postcode']);

        $fields['billing']['billing_address_1']['placeholder'] = 'np. Krakowska 71, Wrocław';
        $fields['billing']['billing_first_name']['placeholder'] = 'Imię i nazwisko';

        $fields['billing']['billing_phone'] = array(
            'label'     => __('Telefon', 'woocommerce'),
            'placeholder'   => _x('Telefon', 'placeholder', 'woocommerce'),
            'required'  => true,
            'class'     => array('form-row form-row-first'),
            'clear'     => true
        );

        $fields['billing']['delivery_way'] = array(
            'label'     => __('Spsób dostawy*', 'woocommerce'),
            'placeholder'   => _x('Wprowadź', 'placeholder', 'woocommerce'),
            'required'  => false,
            'class'     => array('form-row-wide hide-input'),
            'clear'     => true
        );

        $fields['billing']['apartment'] = array(
            'label'     => __('Miszkanie', 'woocommerce'),
            'placeholder'   => _x('Wprowadź', 'placeholder', 'woocommerce'),
            'required'  => true,
            'class'     => array('form-row-add'),
            'clear'     => true
        );

        $fields['billing']['square'] = array(
            'label'     => __('Klatka', 'woocommerce'),
            'placeholder'   => _x('Wprowadź', 'placeholder', 'woocommerce'),
            'required'  => true,
            'class'     => array('form-row-add'),
            'clear'     => true
        );

        $fields['billing']['floor'] = array(
            'label'     => __('Piętro', 'woocommerce'),
            'placeholder'   => _x('Wprowadź', 'placeholder', 'woocommerce'),
            'required'  => true,
            'class'     => array('form-row-add'),
            'clear'     => true
        );


        $fields['billing']['map'] = array(
            'label'     => __('Mapa', 'woocommerce'),
            'placeholder'   => _x('Mapa', 'placeholder', 'woocommerce'),
            'required'  => false,
            'class'     => array('form-row-wide map'),
            'id'        => "map",
            'clear'     => true
        );

        $fields['billing']['after_deliver'] = array(
            'label'     => __('Po przybyciu*', 'woocommerce'),
            'placeholder'   => _x('Wprowadź', 'placeholder', 'woocommerce'),
            'required'  => false,
            'class'     => array('form-row-wide hide-input'),
            'clear'     => true
        );

        $fields['billing']['delivery_date'] = array(
            'label'       => __('Czas dostawy', 'woocommerce'),
            'required'    => false,
            'class'     => array('form-row-add hide-input'),
            'type'        => 'select',
            'options'     => array(
                'Jak najszybciej' => __('Wybierz przedział czasowy', 'woocommerce', 'selected'),
                '14-17h' => __('14-17h', 'woocommerce' ),
                '17-20h' => __('17-20h', 'woocommerce' ),
                '20-21h' => __('20-21h', 'woocommerce' ),
                '21-22h' => __('21-22h', 'woocommerce' ),
                '22-24h' => __('22-24h', 'woocommerce' )
            )
        );

        $fields['billing']['restaurant'] = array(
            'label'     => __('Restauracja', 'woocommerce'),
            'placeholder'   => _x('Wprowadź', 'placeholder', 'woocommerce'),
            'required'  => false,
            'class'     => array('form-row-wide hide-input'),
            'clear'     => true
        );

        return $fields;
    }



    /**
     * Display field value on the order edit page
     */
    add_action( 'woocommerce_admin_order_data_after_shipping_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
    function my_custom_checkout_field_display_admin_order_meta($order){
        echo '<p><strong>'.__('Czas dostawy').':</strong> ' . get_post_meta( $order->get_id(), 'delivery_date', true ) . '</p>';
        echo '<p><strong>'.__('Po przybyciu').':</strong> ' . get_post_meta( $order->get_id(), 'after_deliver', true ) . '</p>';
        echo '<p><strong>'.__('Sposób dostawy').':</strong> ' . get_post_meta( $order->get_id(), 'delivery_way', true ) . '</p>';
        echo '<p><strong>'.__('Piętro').':</strong> ' . get_post_meta( $order->get_id(), 'apartment', true ) . '</p>';
        echo '<p><strong>'.__('Klatka').':</strong> ' . get_post_meta( $order->get_id(), 'square', true ) . '</p>';
        echo '<p><strong>'.__('Numer mieszkania').':</strong> ' . get_post_meta( $order->get_id(), 'apartment', true ) . '</p>';
    }



    /*Add image in checkout table*/
    add_filter( 'woocommerce_cart_item_name', 'ts_product_image_on_checkout', 10, 3 );
    function ts_product_image_on_checkout( $name, $cart_item, $cart_item_key ) {

        /* Return if not checkout page */
        if ( ! is_checkout() ) {
            return $name;
        }

        /* Get product object */
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

        /* Get product thumbnail */
        $thumbnail = $_product->get_image();

        /* Add wrapper to image and add some css */
        $image = '<div class="ts-product-image" style="width: 80px; display: inline-block; padding-right: 7px; vertical-align: middle; border-radius: 5px;">'
            . $thumbnail .
            '</div>';

        /* Prepend image to name and return it */
        return $image . $name;
    }



    add_filter( 'woocommerce_checkout_get_value', 'bks_remove_values', 10, 2 );
    function bks_remove_values( $value, $input ) {
        $item_to_set_null = array(
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_postcode',
            'billing_country',
            'billing_state',
            'billing_email',
            'billing_phone',
            'shipping_first_name',
            'shipping_last_name',
            'shipping_company',
            'shipping_address_1',
            'shipping_address_2',
            'shipping_city',
            'shipping_postcode',
            'shipping_country',
            'shipping_state',
        ); // All the fields in this array will be set as empty string, add or remove as required.

        if (in_array($input, $item_to_set_null)) {
            $value = '';
        }

        return $value;
    }



    add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
    function woocommerce_header_add_to_cart_fragment( $fragments ) {
        global $woocommerce;

        ob_start();

        ?>
        <span class="cart-amount h-amount"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
        <?php

        $fragments['span.cart-amount'] = ob_get_clean();

        return $fragments;

    }

    /**
     * Deactivate Woocommerce Breadcrumb
     */
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);


    /**
     * Checkout page
     * - Coupon info
     * woocommerce_before_checkout_form
     */
    add_action( 'woocommerce_before_checkout_form', 'add_button_back_to_menu' );
    function add_button_back_to_menu( $checkout ) {
        ?>
            <div class="lg100 p-top-30 d-flex p-bottom-30 buttom_back_wrapper">
                <a class="button d-flex align-center" href="<?php echo esc_url( home_url('/') ); ?>">
                    <svg width="8" height="10" viewBox="0 0 8 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="6.19678" y1="0.709609" x2="1.19678" y2="5.67428" stroke="#ffffff" stroke-width="2"/>
                        <line x1="1.70711" y1="4.29289" x2="6.68948" y2="9.27526" stroke="#ffffff" stroke-width="2"/>
                    </svg>
                    <?php echo pll__( 'Wróć do menu' ); ?>
                </a>
            </div>
            <script>
                const buttonBack = document.querySelector('.buttom_back_wrapper')
                const parentButton = buttonBack.parentNode
                parentButton.prepend(buttonBack);
            </script>
        <?php
    }

    add_action('woocommerce_checkout_before_customer_details', 'check_for_coupon');
    function check_for_coupon() {
        global $wpdb;

        // Get an array of all existing coupon codes
        $coupons = $wpdb->get_col("SELECT post_name FROM $wpdb->posts WHERE post_type = 'shop_coupon' AND post_status = 'publish' ORDER BY post_name ASC");
        $auto_coupon = get_field('coupon', 'options');

        $coupon = new WC_Coupon(get_field('coupon', 'options'));
        $expiry_date     = $coupon->get_date_expires();
        $today = date('Y-m-d');

        $active_coupon = strtotime($today) < strtotime($expiry_date);
        ?>

        <div class="wrapper_customer_details <?php if ($active_coupon) esc_attr_e('auto_coupon_active'); ?>"
             data-auto_coupon="<?= get_field( 'coupon', 'options' ); ?>"
             data-map-areas="<?= esc_attr(json_encode(get_field('shipping', 'options'))); ?>"
        >
            <div class="row row-margin">

    <?php }


    add_action( 'woocommerce_checkout_after_customer_details', function () {
        echo '</div></div>';
    });
    add_action( 'woocommerce_checkout_after_order_review', function () {
        echo '<span id="address-filled">Filled</span>';
    });



    // Add info to after order (check)
    add_action( 'woocommerce_checkout_create_order_line_item', 'save_cart_item_data_as_order_item_meta_data', 20, 4 );
    function save_cart_item_data_as_order_item_meta_data( $item, $cart_item_key, $values, $order ) {

        foreach ($values as $key => $value) {

            if ( in_array( $key, $values['keys'] ) ) {
                $item->update_meta_data(
                    __( $key ),
                    $value,
                );
            }
        }
    }


    add_filter( 'woocommerce_add_cart_item_data', 'add_cart_unique_key', 20, 2 );
    function add_cart_unique_key( $cart_item_data, $product_id ) {
        $is_composite_product = $cart_item_data['composite_product'];

        if ( $is_composite_product === true ) {
            // generate a unique hash key and add a unique key to the product data in the cart
            $cart_item_data['unique_key'] = $product_id . '__' . md5( microtime() . rand() );
            return $cart_item_data;
        }
    }



    /*
    Plugin Name: Custom Payment Method
    Description: Add payment by card to the courier.
    Version: 1.0
    Author: Your Name
    */

//    function add_custom_payment_gateway($methods) {
//        $methods[] = 'Custom_Payment_Method';
//        return $methods;
//    }
//    add_filter('woocommerce_payment_gateways', 'add_custom_payment_gateway');

}

class Custom_Payment_Method extends WC_Payment_Gateway {
    public function __construct() {
        $this->id = 'custom_payment_method';
        $this->method_title = 'Custom Payment Method';
        $this->title = 'Payment by Card to the Courier';
        $this->has_fields = false;
        $this->init_form_fields();
        $this->init_settings();
        $this->enabled = $this->get_option('enabled');
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }
}