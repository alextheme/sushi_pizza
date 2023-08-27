<?php
if(!defined('DS_THEME')) {
	define('DS_THEME', get_theme_root().'/'.get_template().'/');
}

require_once DS_THEME.'libs/utils.php';
require_once DS_THEME.'libs/posttypes.php';

function mojeStyleiSkrypty() {
	wp_enqueue_style( 'themecss', get_template_directory_uri() . '/css/theme.css',false,'1.1','all');
	wp_enqueue_style( 'lgcss', get_template_directory_uri() . '/css/lightgallery.min.css',false,'1.1','all');
	wp_enqueue_style( 'aocss', get_template_directory_uri() . '/css/aos.css',false,'1.1','all');
	wp_enqueue_script( 'lgjs', get_template_directory_uri() . '/js/lightgallery.min.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'slickjs', get_template_directory_uri() . '/js/slick.min.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'aojs', get_template_directory_uri() . '/js/aos.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'themejs', get_template_directory_uri() . '/js/theme.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'ajax-load', get_template_directory_uri() . '/js/ajax-load.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'translations', get_template_directory_uri() . '/js/translations.js', array ( 'jquery' ), 1.1, true);

	wp_enqueue_script( 'scripts', get_template_directory_uri() . '/js/scripts.js', array ( 'jquery' ), 1.1, true);
}
function mojeStyleiSkryptyAdmin() {
	if (is_admin()) {
		wp_enqueue_script( 'scripts-admin', get_template_directory_uri() . '/js/scripts-admin.js', array ( 'jquery' ), 1.1, true);
    }
}
add_action( 'wp_enqueue_scripts', 'mojeStyleiSkrypty' );

add_action('admin_print_styles', 'mojeStyleiSkryptyAdmin');

add_theme_support('post-thumbnails', array('post', 'page', 'product', 'barcamp'));

if(function_exists('register_nav_menus')) {
	register_nav_menus(array(
	'header-menu' => 'Menu header',
    'footer-menu' => 'Menu w stopce'
	));
}

add_action( 'wp', 'my_remove_sidebar_product_pages' );
 
function my_remove_sidebar_product_pages() {
 
  if ( is_product() ) {
 
    remove_action( 'woocommerce_sidebar','woocommerce_get_sidebar', 10 );
 
  }
 
}

add_action('wp_loaded', 'output_buffer_start');
function output_buffer_start() {
    ob_start("output_callback");
}
add_action('shutdown', 'output_buffer_end');
function output_buffer_end() {
	if(ob_get_length() > 0) {
		ob_end_flush();
	}
}
function output_callback($buffer) {
    return preg_replace( "%[ ]type=[\'\"]text\/(javascript|css)[\'\"]%", '', $buffer );
}

add_action('wpmm_head', 'wpmm_custom_css');

function wpmm_custom_css() {
	echo '<style>

        .wrap {
            width: 100%;
        }
		</style>';
}

add_action('after_setup_theme', 'woocommerce_support');
function woocommerce_support() {
  add_theme_support('woocommerce');
}
//Quantity change buttons

// Remove product in the cart using ajax
function warp_ajax_product_remove()
{
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


function post_to_api($url, $post_data) {
     
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
   
  if($response_array->status == 'OK'){
    $output['status'] = 'OK';
    $output['details'] = (string)$response_array->details->msg;
  }
  else{
    $output['status'] = 'FAILED';
    $output['error'] = array();
     
     
    foreach($response_array->details->error as $val){
       
      $output['error'][] = (string)$val;
    }
  }
       
  curl_close($curlSession);
   
  return $output;
}

function printOrder($order, $printer, $place){
$order_id = $order->get_id();
$currency = 'PLN';
 
$order_type = 'delivery';
$payment_status = 'paid'; 
$payment_method = 'creditcard';
$auth_code = '443AD453454'; //identification code of payment
 
$order_time = date('Y-m-d H:i:s',strtotime($order->order_date)); //h:m dd-mm-yy
$delivery_time = date('Y-m-d H:i:s',strtotime($order->order_date)); //h:m dd-mm-yy
 
$deliverycost =  $order->get_shipping_total();
$card_fee = 0.00;
$extra_fee = 0.00;
$total_discount = 0.00;
$total_amount =$order->get_total();
 
$apartment = get_post_meta( $order->get_id(), 'apartment', true );
$square = get_post_meta( $order->get_id(), 'square', true );
$floor = get_post_meta( $order->get_id(), 'floor', true );
$after = get_post_meta( $order->get_id(), 'after_deliver', true );	
$adnote = $order->get_customer_note();
$cust_name = $order->get_billing_first_name();
if(get_post_meta( $order->get_id(), 'delivery_way', true ) != "Odbiór osobisty" && $order->get_shipping_total() != 0){
$cust_address = 'Adres: '.$order->billing_address_1.', Nr: '.$apartment.', Klatka: '.$square.', Piętro: '.$floor;
}else{
	$cust_address = 'Odbiór osobisty';
}	
	
if (str_contains($order->billing_phone, '+48')) {
    $cust_phone = $order->billing_phone;
}else{
	$cust_phone = "+48".$order->billing_phone;
}
	
$isVarified = 'verified'; 
$cust_instruction = 'Kiedy:'.get_post_meta( $order->get_id(), "delivery_date", true ).'%%Po dojechaniu: '.$after;
	
// =============================================
// Part 3. Save the items ordered into an array
// =============================================
$menu_item = [];
 
foreach ( $order->get_items() as $item_id => $item ) {
		$arr_c = [];
		$arr_c['category'] = "aaa";
		$arr_c['item'] = $item->get_name();
		$arr_c['item_description'] = '';
		$arr_c['item_qty'] = $item->get_quantity();
		$arr_c['item_price'] =  $item->get_total();
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
$receipt_header = 'Restauracja: '.$place;
$receipt_footer = "Uwaga: ".$adnote.", Dziekujemy";
 
 
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
if($order_type=='delivery'){
  $post_array['order_type'] = 1;
}
else if($order_type=='collection' || $order_type=='pickup'){
  $post_array['order_type'] = 2;
}
else if($order_type=='reservation'){
  $post_array['order_type'] = 3;
}
 
//6=paid, 7=not paid
if($payment_status=='paid'){
  $post_array['payment_status'] = 7;
}
else{
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
if($isVarified=='verified'){
  $post_array['isVarified'] = 4;
}
else{
  $post_array['isVarified'] = 5;
}
$post_array['cust_instruction'] = $cust_instruction;
 

 
$post_array['line_items'] = $menu_item;
 
// ========================================
// Part 8. Post order data to PrinterCo API
// ========================================
$printerco_api_url = 'https://mypanel.printerco.net/submitorderext.php';
$response = post_to_api($printerco_api_url,$post_array);
 
 
//do your necessary things here based on the response status
if($response['status']=='OK'){
  //order submitted successfully
}   
else{
  //order submission failed because of the following reason
}
}


function sendSms($number, $sms) {
	
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
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $params_string);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close ($ch); 
	return $response; 
}

/*add_filter( 'woocommerce_checkout_order_processed', 'sendSmsOrder' );
 
function sendSmsOrder($order_id) {
 
	$order = wc_get_order( $order_id );
	$link_address =  $order->get_checkout_order_received_url();
	
	$ww = sendSms("883089982","Nr. zam: ".$order->get_id().", link: ".$link_address);
	
}*/

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


add_action( 'wp_ajax_product_remove', 'warp_ajax_product_remove' );
add_action( 'wp_ajax_nopriv_product_remove', 'warp_ajax_product_remove' );

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
	
	/*$fields['billing']['delivery_date'] = array(
        'label'     => __('Czas dostawy', 'woocommerce'),
    'placeholder'   => _x('Wprowadź', 'placeholder', 'woocommerce'),
    'required'  => false,
    'class'     => array('form-row-wide'),
    'clear'     => true
     );
	
	$fields['billing']['delivery_time'] = array(
        'label'     => __('Data i godzina', 'woocommerce'),
    'placeholder'   => _x('Wprowadź', 'placeholder', 'woocommerce'),
    'required'  => false,
    'class'     => array('form-row-wide dtime'),
	'id'     => 'flatpickr',	
    'clear'     => true
     );*/
	
	$fields['billing']['delivery_date'] = array(
    'label'       => __('Czas dostawy', 'woocommerce'),
    'required'    => false,
    'class'     => array('form-row-add hide-input'),
    'type'        => 'select',
    'options'     => array(
		'Jak najszybciej' => __('Wybierz przedział czasowy', 'woocommerce', 'selected'),
        '12-14h' => __('12-14h', 'woocommerce' ),
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

add_action('wp_ajax_custom_base_address', 'custom_base_address');
add_action('wp_ajax_nopriv_custom_base_address', 'custom_base_address');

function custom_base_address() {
	$address = $_POST['address'];
	$_SESSION['address'] = $address;
	return 1;
}



/*Najpierw trzeba zainnicjować sesje*/
add_action('init', 'start_session', 1);

function start_session() {
    if( ! session_id() ) {
        session_start();
    }
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

/*Movr button*/
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_review_order_after_order_total_coupon', 'woocommerce_checkout_coupon_form', 10 );

/*Redirect*/
add_action('template_redirect','redirect_visitor');
function redirect_visitor(){
	global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM `wp_block_site` WHERE 1");
    if ( is_page( 'cart' )  ) {
        wp_safe_redirect(site_url().'/zamowienie');
        exit(); // Don't forget this one
    }else if ( is_page( 'shop' ) ) {
        wp_safe_redirect(site_url());
        exit(); // Don't forget this one
    }else if ( is_checkout() ) {
		wp_safe_redirect(site_url());
		exit(); // Don't forget this one
	}
}

if (is_plugin_active('polylang/polylang.php')) {
	error_reporting( 0 );
	// Polylang string registration
	pll_register_string($cback , "Wróć do menu" );
	pll_register_string($cempty , "Koszyk jest pusty!" );
	pll_register_string($rest , "Restauracja" );
	pll_register_string($t_work , "Czas pracy" );
	pll_register_string($why_we , "Dlaczego my" );
	pll_register_string($cart , "Koszyk" );
	pll_register_string($checkout , "Zamówienie" );
	pll_register_string($amount , "Kwota" );
	pll_register_string($iwant , "Chcę" );
	pll_register_string($cheader , "Masz jakieś pytania lub propozycje?" );
	pll_register_string($ycart , "Twoje zamówienie" );
	pll_register_string($ship , "Wysyłka" );
	pll_register_string($tdeliever , "Czas przygotowania oraz dostawy" );
	pll_register_string($smessage , "Otrzymasz SMS z czasem" );
	pll_register_string($cash , "Gotówka" );
	pll_register_string($paymentorder , "Kupuję i płacę" );
	pll_register_string($payment , "Płatność" );
	pll_register_string($copyr , "Strona stworzona i prowadzona przez" );
	pll_register_string($con , "Czas pracy" );
	pll_register_string($con1 , "Masz jakieś pytania lub propozycje?" );
	pll_register_string($emp , "Niestety nie znaleźliśmy takich produktów!" );
	pll_register_string($emp1 , "Wybierz restaurację z której chcesz zamówić*" );
	pll_register_string($emp2 , "Istnieje możliwość późniejszej zmiany restauracji poprzez menu*" );
	pll_register_string($emp3 , "Chwilowo niedostępny" );
	error_reporting( E_ALL );
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

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
  
function my_custom_dashboard_widgets() {
global $wp_meta_boxes;
 
wp_add_dashboard_widget('custom_help_widget', 'Czy otwarte zamówienia online?', 'custom_dashboard_help');
}

function is_blocked(){
	global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM `wp_block_site` WHERE 1");
	return $data[0]->blokada;
}

function custom_dashboard_help() {
	$blocked_shops = is_blocked();
	echo '<div class="shop-admin-btns" style="width: 100%; display: flex; align-items: center; justify-content: space-between;">';
	if($blocked_shops == "zawalna" || $blocked_shops == "calysklep"){
		echo '<a id="calysklep"class="clicked" style="color: #ff0000" href="/">Zablokuj cały sklep!</a>';
	}else{
		echo '<a id="calysklep" href="/">Zablokuj cały sklep!</a>';
	}
echo '<a id="" href="/">Odblokuj!</a>';
echo '</div>';
}

add_action('wp_ajax_block_shop', 'block_shop_func');
add_action('wp_ajax_nopriv_block_shop', 'block_shop_func');

function block_shop_func() {
	global $wpdb;
	$to_block = $_POST['address'];
    $wpdb->query("UPDATE wp_block_site SET `blokada`='".$to_block."' WHERE `id` = 1");
	return "Zablokowano: ".$to_block;
}

/**
 * TGM Plugin Activation
 */
require_once get_template_directory() . '/inc/tgm/options.php';

/**
 * Redux Option Include
 */
if ( class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/inc/redux/options.php' ) ) {
	require_once get_template_directory() . '/inc/redux_framework/options.php';
	add_filter( 'woodmart_redux_settings', '__return_false' );
}

/**
 * AJAX
 */
require_once get_template_directory() . '/inc/ajax/index.php';


/**
 * TEMP TODO
 */
function print_pre( $obj ) {
	echo '<pre>';
	print_r( $obj );
	echo '</pre>';
}
function var_dump_pre( $obj ) {
	echo '<pre>';
	var_dump( $obj );
	echo '</pre>';
}

function print_pre_die( $obj ) {
	echo '<pre>';
	print_r( $obj );
	echo '</pre>';
	die(-1);
}



