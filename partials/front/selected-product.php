<?php

$path = preg_replace('/wp-content.*$/','',__DIR__);
include($path.'wp-load.php');

$id = $_GET['product_id'];

$_product = wc_get_product( $id );

?>
<div class="lg50 md60 sm90 xs90 padding-15 product product-selected txt-black offset-bottom-30">
	<div class="lg100 bckg-white product-content corner-radius">
		<span class="close-product-sel">×</span>
		<div class="row row-margin">
			<div class="lg50 corner-radius-img padding-15">
				<span class="lg100">
					<?php echo $_product->get_image(); ?>
				</span>
			</div>
			<div class="lg50 xs100 padding-15">
				<h3><?php echo $_product->get_name(); ?></h3>
				<?php echo $_product->get_description(); ?>
				<div class="lg100 product-footer">
					<span class="price"><?php echo $_product->get_price(); ?> zł</span>
					<?php  echo sprintf('<a href="%s" data-quantity="1" class="%s" %s>Chcę!</a>', esc_url($_product->add_to_cart_url()), esc_attr(implode(' ', array_filter(array('button','product_type_' . $_product->get_type(),$_product->is_purchasable() && $_product->is_in_stock() ? 'add_to_cart_button' : '', $_product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '')))), wc_implode_html_attributes(array('data-product_id' => $_product->get_id(), 'data-product_sku' => $_product->get_sku(),'aria-label' => $_product->add_to_cart_description(),'rel' => 'nofollow')), esc_html($_product->add_to_cart_text())); ?> 
				</div>
			</div>
		</div>
	</div>
</div>
