<?php
/* Template Name: Print Template */

get_header();

?>
<style>
    input {display: inline-block;width: 33%;margin: 0 auto;}
    input[type="submit"] {display:block;}
</style>


<div style="margin: 50px 0 50px 0; position: relative;">
    <?php
    $zone = new \DateTimeZone('Europe/Kiev');
    $date = new \DateTime('now', $zone);
    echo 'Time: ', $date->format('H:i d-m-y');
    ?>

<form method="post" action="https://mypanel.printerco.net/submitorderext.php">
    <input name="api_key" value="7o67ro" />
    <input name="api_password" value="vwGpMhtPmN" />
    <!-URL-адреса сповіщення – це URL-адреса вашого веб-сайту, на який ви хочете отримувати сповіщення від isynctel api -->
<!--    <input name="notify_url" value="http://localhost/sushi_pizza_d" />-->
    <input name="notify_url" value="http://localhost/sushi_pizza_d/pl/receive-notification/?v=9b7d173b068d" />
<!--    <input name="notify_url" value="http://localhost/sushi_pizza_d/pl/printerco_notify/?v=9b7d173b068d" />-->

    <input name="receipt_header" value="Header Line1@@123 Street Address, City, Zip Code" />
    <input name="receipt_footer" value="Thanks for your custom..." />
    <input name="printer_id" value="SN2456721653" />
    <input name="order_id" value="00001" />
    <input name="currency" value="pln" /><!--ex. USD/GBP -->
    <input name="order_type" value="2" /><!--Delivery=1, Pick up=2, Reservation=3-->
    <input name="payment_status" value="7" /><!--Paid=6, Not Paid=7-->
    <input name="payment_method" value="Payment Method" />
    <input name="delivery_time" value="<?php echo $date->format('H:i d-m-y'); ?>" /><!--Format=HH:MM DD-MM-YY-->
    <input name="auth_code" value="Payment authorization code" />

    <br><br>

    <input name="line_items[0][category]" value="Category 1" />
    <input name="line_items[0][item]" value="Item Name 1" />
    <input name="line_items[0][item_description]" value="Item description 1" />
    <input name="line_items[0][item_qty]" value="1" />
    <input name="line_items[0][item_price]" value="10.50" />
    <input name="line_items[0][item_addon][0][title]" value="Additional item title 1" />
    <input name="line_items[0][item_addon][0][name]" value="Additional item name 1" />
    <input name="line_items[0][item_addon][0][price]" value="5.50" />

<!--    <input name="line_items[0][category]" value="Category 2" />-->
<!--    <input name="line_items[0][item]" value="Item Name 2" />-->
<!--    <input name="line_items[0][item_description]" value="Item description 2" />-->
<!--    <input name="line_items[0][item_qty]" value="1" />-->
<!--    <input name="line_items[0][item_price]" value="10.50" />-->
<!---->
<!--    <input name="line_items[0][category]" value="Category 3" />-->
<!--    <input name="line_items[0][item]" value="Item Name 3" />-->
<!--    <input name="line_items[0][item_description]" value="Item description 3" />-->
<!--    <input name="line_items[0][item_qty]" value="1" />-->
<!--    <input name="line_items[0][item_price]" value="10.50" />-->
<!--    <input name="line_items[0][item_addon][0][title]" value="Additional item title 3" />-->
<!--    <input name="line_items[0][item_addon][0][name]" value="Additional item name 3" />-->
<!--    <input name="line_items[0][item_addon][0][price]" value="5.50" />-->

<!--    ----------->
<!--    You can place more items here using above format.-->
<!--    Please note that you don't need to send category name for the items after first item if the category name is same and if you put all same category items consecutively.-->
<!--    ----------->
    <input name="deliverycost" value="3.50" />
    <input name="card_fee" value="0.50" />
    <input name="extra_fee" value="1.50" />
    <input name="total_discount" value="4.50" />
    <input name="total_amount" value="56.50" /><!--Grand Total -->
    <input name="cust_name" value="Customer Name" />
    <input name="cust_address" value="Customer address" />
    <input name="cust_phone" value="Phone number" />
    <input name="cust_instruction" value="Special instruction" />
    <input name="isVarified" value="4" /><!-Verified=4, Not verified=5 -->
    <input name="num_prev_order" value="Number of previous order" />
    <br><br>
    <input type="submit" value="Submit" />
</form>

</div>
<?php get_footer(); ?>
