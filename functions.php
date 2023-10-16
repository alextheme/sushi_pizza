<?php
if (!defined('DS_THEME')) {
    define('DS_THEME', get_theme_root() . '/' . get_template() . '/');
}

require_once DS_THEME . 'libs/utils.php';
require_once DS_THEME . 'libs/posttypes.php';

function mojeStyleiSkrypty()
{
    wp_enqueue_style('themecss', get_template_directory_uri() . '/css/general.css', false, '1.1', 'all');
    wp_enqueue_style('lgcss', get_template_directory_uri() . '/css/lightgallery.min.css', false, '1.1', 'all');
    wp_enqueue_style('aocss', get_template_directory_uri() . '/css/aos.css', false, '1.1', 'all');
    wp_enqueue_script('lgjs', get_template_directory_uri() . '/js/lightgallery.min.js', array('jquery'), 1.1, true);
    wp_enqueue_script('slickjs', get_template_directory_uri() . '/js/slick.min.js', array('jquery'), 1.1, true);
    wp_enqueue_script('aojs', get_template_directory_uri() . '/js/aos.js', array('jquery'), 1.1, true);
    wp_enqueue_script('themejs', get_template_directory_uri() . '/js/theme.js', array('jquery'), 1.1, true);
    wp_enqueue_script('ajax-load', get_template_directory_uri() . '/js/ajax-load.js', array('jquery'), 1.1, true);
    wp_enqueue_script('translations', get_template_directory_uri() . '/js/translations.js', array('jquery'), 1.1, true);

    // Accordion
    wp_enqueue_style('accordion-css', get_template_directory_uri() . '/libs/accordion_js/accordion.min.css');
    wp_enqueue_script('accordion-js', get_template_directory_uri() . '/libs/accordion_js/accordion.min.js', array('jquery'), null, true);

    wp_enqueue_script('scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), 1.1, true);

    wp_register_script('map_scripts', get_template_directory_uri() . '/js/maps.js', array('jquery'), 1.1, true);
    wp_register_script(
        'google_map_js',
        'https://maps.googleapis.com/maps/api/js?key='
        . esc_attr(get_field('key_google_map', 'options'))
        . '&v=weekly&callback=initMap&libraries=drawing,geometry,places', array('jquery'), 1.1, true);
    wp_register_script('polyfill', 'https://polyfill.io/v3/polyfill.js?features=es5,es6,es7&flags=gated', array('jquery'), 1.1, true);


    // Page is (pl, ru, ua)
    $checkout_pages = [27, 269, 271];
    $delivery_pages = [102, 205, 207];
    $pages_ids = array_merge($checkout_pages, $delivery_pages);

    if (is_page($pages_ids)) {
        wp_enqueue_script('map_scripts');
        wp_enqueue_script('google_map_js');
        wp_enqueue_script('polyfill');
    }

//    if ( get_current_user_id() === 1 ) {
//        wp_enqueue_style('test_for_admin_login', get_template_directory_uri() . '/css/test_for_admin_login.css', false, '1.1', 'all');
//    }
}

function mojeStyleiSkryptyAdmin()
{
    if (is_admin()) {
        wp_enqueue_script('scripts-admin', get_template_directory_uri() . '/js/scripts-admin.js', array('jquery'), 1.1, true);
    }
}

add_action('wp_enqueue_scripts', 'mojeStyleiSkrypty');

add_action('admin_print_styles', 'mojeStyleiSkryptyAdmin');

add_theme_support('post-thumbnails', array('post', 'page', 'product', 'barcamp'));

if (function_exists('register_nav_menus')) {
    register_nav_menus(array(
        'header-menu' => 'Menu header',
        'mobile-pop-up' => 'Mobile Pop-up menu',
        'footer-menu' => 'Menu w stopce',
    ));
}


/**
 * WOOCOMMERCE SETTINGS
 */
require_once get_template_directory() . '/inc/woocommerce/woocommerce_settings.php';


add_action('wp_loaded', 'output_buffer_start');
function output_buffer_start()
{
    ob_start("output_callback");
}

add_action('shutdown', 'output_buffer_end');
function output_buffer_end()
{
    if (ob_get_length() > 0) {
        ob_end_flush();
    }
}

function output_callback($buffer)
{
    return preg_replace("%[ ]type=[\'\"]text\/(javascript|css)[\'\"]%", '', $buffer);
}

add_action('wpmm_head', 'wpmm_custom_css');

function wpmm_custom_css()
{
    echo '<style>

        .wrap {
            width: 100%;
        }
		</style>';
}


function post_to_api($url, $post_data)
{

    set_time_limit(60);
    $output = array();

    $fields = http_build_query($post_data);

    $curlSession = curl_init();
    curl_setopt($curlSession, CURLOPT_URL, $url);
    curl_setopt($curlSession, CURLOPT_HEADER, 0);
    curl_setopt($curlSession, CURLOPT_POST, 1);
    curl_setopt($curlSession, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlSession, CURLOPT_TIMEOUT, 30);
    curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 1);

    $rawresponse = curl_exec($curlSession);

    $response_array = simplexml_load_string($rawresponse);

    if ($response_array->status == 'OK') {
        $output['status'] = 'OK';
        $output['details'] = (string)$response_array->details->msg;
    } else {
        $output['status'] = 'FAILED';
        $output['error'] = array();


        foreach ($response_array->details->error as $val) {

            $output['error'][] = (string)$val;
        }
    }

    curl_close($curlSession);

    return $output;
}

function printOrder($order, $printer, $place)
{
    $order_id = $order->get_id();
    $currency = 'PLN';

    $order_type = 'delivery';
    $payment_status = 'paid';
    $payment_method = 'creditcard';
    $auth_code = '443AD453454'; //identification code of payment

    $order_time = date('Y-m-d H:i:s', strtotime($order->order_date)); //h:m dd-mm-yy
    $delivery_time = date('Y-m-d H:i:s', strtotime($order->order_date)); //h:m dd-mm-yy

    $deliverycost = $order->get_shipping_total();
    $card_fee = 0.00;
    $extra_fee = 0.00;
    $total_discount = 0.00;
    $total_amount = $order->get_total();

    $apartment = get_post_meta($order->get_id(), 'apartment', true);
    $square = get_post_meta($order->get_id(), 'square', true);
    $floor = get_post_meta($order->get_id(), 'floor', true);
    $after = get_post_meta($order->get_id(), 'after_deliver', true);
    $adnote = $order->get_customer_note();
    $cust_name = $order->get_billing_first_name();
    if (get_post_meta($order->get_id(), 'delivery_way', true) != "Odbiór osobisty" && $order->get_shipping_total() != 0) {
        $cust_address = 'Adres: ' . $order->billing_address_1 . ', Nr: ' . $apartment . ', Klatka: ' . $square . ', Piętro: ' . $floor;
    } else {
        $cust_address = 'Odbiór osobisty';
    }

    if (str_contains($order->billing_phone, '+48')) {
        $cust_phone = $order->billing_phone;
    } else {
        $cust_phone = "+48" . $order->billing_phone;
    }

    $isVarified = 'verified';
    $cust_instruction = 'Kiedy:' . get_post_meta($order->get_id(), "delivery_date", true) . '%%Po dojechaniu: ' . $after;

// =============================================
// Part 3. Save the items ordered into an array
// =============================================
    $menu_item = [];

    foreach ($order->get_items() as $item_id => $item) {
        $arr_c = [];
        $arr_c['category'] = "aaa";
        $arr_c['item'] = $item->get_name();
        $arr_c['item_description'] = '';
        $arr_c['item_qty'] = $item->get_quantity();
        $arr_c['item_price'] = $item->get_total();
        $menu_item[] = $arr_c;
    }

// =============================
// PART 4. API access credential - Find this in your MyPanel dashboard under My account > Edit Information.
// =============================
    $api_key = '0nm76s';
    $api_password = 'Kjd1&d!@d1324rtfg#';


// ============================
// PART 5. PrinterCo printer ID - visit your MyPanel dashboard and then the Printer List page to find the printer ID.
// ============================
    $printer_id = $printer;


// =====================================
// PART 6. Set receipt header and footer
// =====================================
    $receipt_header = 'Restauracja: ' . $place;
    $receipt_footer = "Uwaga: " . $adnote . ", Dziekujemy";


// ==========================
// PART 7. Notification URL - When an order is either accepted or rejected on the printer, this info will be sent to this URL.
// ==========================
    $notify_url = 'https://americansushiexpress.pl/';
// Even if this will not be used, this is a mandatory field needed to send orders to the API


// =========================================
// PART 8. Preparing post fields as an array
// before sending to the PrinterCo API
// =========================================
    $post_array = array();

    $post_array['api_key'] = $api_key;
    $post_array['api_password'] = $api_password;

    $post_array['receipt_header'] = $receipt_header;
    $post_array['receipt_footer'] = $receipt_footer;

    $post_array['notify_url'] = $notify_url;
    $post_array['printer_id'] = $printer_id;

    $post_array['order_id'] = $order_id;
    $post_array['currency'] = $currency;

//1=Delivery, 2=Collection/Pickup, 3=Reservation
    if ($order_type == 'delivery') {
        $post_array['order_type'] = 1;
    } else if ($order_type == 'collection' || $order_type == 'pickup') {
        $post_array['order_type'] = 2;
    } else if ($order_type == 'reservation') {
        $post_array['order_type'] = 3;
    }

//6=paid, 7=not paid
    if ($payment_status == 'paid') {
        $post_array['payment_status'] = 7;
    } else {
        $post_array['payment_status'] = 7;
    }
    $post_array['payment_method'] = $payment_method;
    $post_array['auth_code'] = $auth_code;

    $post_array['order_time'] = $order_time;
    $post_array['delivery_time'] = $delivery_time;

    $post_array['deliverycost'] = $deliverycost;
    $post_array['card_fee'] = $card_fee;
    $post_array['extra_fee'] = $extra_fee;
    $post_array['total_discount'] = $total_discount;
    $post_array['total_amount'] = $total_amount;

    $post_array['cust_name'] = $cust_name;
    $post_array['cust_address'] = $cust_address;
    $post_array['cust_phone'] = $cust_phone;

//4=verified, 5=not verified
    if ($isVarified == 'verified') {
        $post_array['isVarified'] = 4;
    } else {
        $post_array['isVarified'] = 5;
    }
    $post_array['cust_instruction'] = $cust_instruction;


    $post_array['line_items'] = $menu_item;

// ========================================
// Part 8. Post order data to PrinterCo API
// ========================================
    $printerco_api_url = 'https://mypanel.printerco.net/submitorderext.php';
    $response = post_to_api($printerco_api_url, $post_array);


//do your necessary things here based on the response status
    if ($response['status'] == 'OK') {
        //order submitted successfully
    } else {
        //order submission failed because of the following reason
    }
}


function sendSms($number, $sms)
{

    $url = "https://api2.smsplanet.pl/sms";
    $params = [
        'key' => '8a4401ea-c8f3-47fc-b55f-37ca59b565d0',
        'password' => 'Sushi123',
        'from' => 'SUSHI EXP',
        'to' => $number,
        'msg' => $sms
    ];

    $params_string = http_build_query($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


add_action('wp_ajax_custom_base_address', 'custom_base_address');
add_action('wp_ajax_nopriv_custom_base_address', 'custom_base_address');

function custom_base_address()
{
    $address = $_POST['address'];
    $_SESSION['address'] = $address;
    return 1;
}


/*Najpierw trzeba zainnicjować sesje*/
add_action('init', 'start_session', 1);

function start_session()
{
    if (!session_id()) {
        session_start();
    }
}


/*Redirect*/
//add_action('template_redirect','redirect_visitor');
function redirect_visitor()
{
    global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM `wp_block_site` WHERE 1");
    if (is_page('cart')) {
        wp_safe_redirect(site_url() . '/zamowienie');
        exit(); // Don't forget this one
    } else if (is_page('shop')) {
        wp_safe_redirect(site_url());
        exit(); // Don't forget this one
    } else if (is_checkout()) {
        wp_safe_redirect(site_url());
        exit(); // Don't forget this one
    }
}

if (is_plugin_active('/polylang.php')) {

    if (!function_exists('pll_register_string')) {
        return;
    }

    error_reporting(0);
    // Polylang string registration
    pll_register_string($cback, "Wróć do menu");
    pll_register_string($cempty, "Koszyk jest pusty!");
    pll_register_string($rest, "Restauracja");
    pll_register_string($t_work, "Czas pracy");
    pll_register_string($why_we, "Dlaczego my");
    pll_register_string($cart, "Koszyk");
    pll_register_string($checkout, "Zamówienie");
    pll_register_string($amount, "Kwota");
    pll_register_string($iwant, "Chcę");
    pll_register_string($cheader, "Masz jakieś pytania lub propozycje?");
    pll_register_string($ycart, "Twoje zamówienie");
    pll_register_string($ship, "Wysyłka");
    pll_register_string($tdeliever, "Czas przygotowania oraz dostawy");
    pll_register_string($smessage, "Otrzymasz SMS z czasem");
    pll_register_string($cash, "Gotówka");
    pll_register_string($paymentorder, "Kupuję i płacę");
    pll_register_string($payment, "Płatność");
    pll_register_string($copyr, "Strona stworzona i prowadzona przez");
    pll_register_string($con, "Czas pracy");
    pll_register_string($con1, "Masz jakieś pytania lub propozycje?");
    pll_register_string($emp, "Niestety nie znaleźliśmy takich produktów!");
    pll_register_string($emp1, "Wybierz restaurację z której chcesz zamówić*");
    pll_register_string($emp2, "Istnieje możliwość późniejszej zmiany restauracji poprzez menu*");
    pll_register_string($emp3, "Chwilowo niedostępny");
    error_reporting(E_ALL);
}

if (!function_exists('pll__')) {
    function pll__($string)
    {
        return $string;
    }
}

if (!function_exists('pll_e')) {
    function pll_e($string)
    {
        echo $string;
    }
}


add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function my_custom_dashboard_widgets()
{
    global $wp_meta_boxes;

    wp_add_dashboard_widget('custom_help_widget', 'Czy otwarte zamówienia online?', 'custom_dashboard_help');
}

function is_blocked()
{
    global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM `wp_block_site` WHERE 1");
    return $data[0]->blokada;
}

function custom_dashboard_help()
{
    $blocked_shops = is_blocked();
    echo '<div class="shop-admin-btns" style="width: 100%; display: flex; align-items: center; justify-content: space-between;">';
    if ($blocked_shops == "zawalna" || $blocked_shops == "calysklep") {
        echo '<a id="calysklep"class="clicked" style="color: #ff0000" href="/">Zablokuj cały sklep!</a>';
    } else {
        echo '<a id="calysklep" href="/">Zablokuj cały sklep!</a>';
    }
    echo '<a id="" href="/">Odblokuj!</a>';
    echo '</div>';
}

add_action('wp_ajax_block_shop', 'block_shop_func');
add_action('wp_ajax_nopriv_block_shop', 'block_shop_func');

function block_shop_func()
{
    global $wpdb;
    $to_block = $_POST['address'];
    $wpdb->query("UPDATE wp_block_site SET `blokada`='" . $to_block . "' WHERE `id` = 1");
    return "Zablokowano: " . $to_block;
}


/**
 * TGM Plugin Activation
 */
require_once get_template_directory() . '/inc/tgm/options.php';

/**
 * AJAX
 */
require_once get_template_directory() . '/inc/ajax/ajax.php';

/**
 * THEME SETTINGS
 */
require_once get_template_directory() . '/inc/theme_settings/system.php';

include_once get_template_directory() . '/partials/front/_variable-product-functions.php';

/**
 * ACF THEME OPTIONS PAGE
 */
include_once get_template_directory() . '/inc/acf/acf-settings.php';


/**
 * TEMP TODO
 */
function print_pre($obj)
{
    echo '<pre style="padding:10px;background:#33384b;width: 100%;color: #ffffff">';
    print_r($obj);
    echo '</pre>';
}
function print_pre_a($obj)
{
    echo '<pre style="position:absolute;top:0;left:0;z-index:999;padding:10px;background:#33384b;width: 100%;">';
    print_r($obj);
    echo '</pre>';
}
function br() {
    echo '<br>';
}

function searchDataInDataBase($search_string = '') {
    if ( gettype($search_string) !== 'string' ) {
        $search_string = (string) $search_string;
    }

    global $wpdb;

    // Отримати список всіх таблиць та стовпців
    $sql = "SELECT table_name, column_name FROM information_schema.columns";
    $result = $wpdb->get_results($sql);

    if (count($result) > 0) {
        foreach ($result as $item) {

            $table = $item->table_name;
            $column = $item->column_name;

            // Скористайтеся конструкцією LIKE для пошуку рядків, що містять вказаний термін
            $search_query = "SELECT * FROM $table WHERE $column LIKE '%$search_string%'";
            $search_result = $wpdb->get_results($search_query);

            if ( count($search_result) > 0) {
                foreach ($search_result as $res) {
                    echo "Table: $table, <br>Column: $column<br><pre style='background:#fff;color:#000;'>" . print_r($res, true) . "</pre><br>";
                }
            }
        }
    } else {
        echo "Немає таблиць у базі даних.";
    }
}



