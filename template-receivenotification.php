<?php
/* Template Name: Receive Notification */

get_header();

$printer_id = $_REQUEST['printer_id'];
$order_id = $_REQUEST['order_id'];
$order_status = $_REQUEST['status'];
$message = $_REQUEST['msg'];
$delivery_time = $_REQUEST['delivery_time'];

?>

<style>
    input {display: inline-block;width: 33%;margin: 0 auto;}
    input[type="submit"] {display:block;}
</style>


<div style="margin: 50px 0 50px 0; position: relative;">

<?php

if($order_status==1)
{
    //order has been accepted from the printer
    //do your necessary task for accepted order like update database, send email to the customer to inform him that his order has been accepted and will be delivered on the returned delivery time (variable $delivery_time).
    echo 'Printer ID: ', $printer_id, '<br>';
    echo 'Order ID: ', $order_id, '<br>';
    echo 'Order status: ', $order_status, '<br>';
    echo 'Message: ', $message, '<br>';
    echo 'Delivery Time: ', $delivery_time, '<br>';
}
else
{
    //order has been rejected from printer
    //do your necessary task for rejected order like update databse, send email to customer to inform him that his order has been rejected for the returned reason (variable $message).
    echo 'Printer ID: ', $printer_id, '<br>';
    echo 'Order ID: ', $order_id, '<br>';
    echo 'Order status: ', $order_status, '<br>';
    echo 'Message: ', $message, '<br>';
    echo 'Delivery Time: ', $delivery_time, '<br>';
}

?>

</div>

<?php get_footer(); ?>

