<?php

$path = preg_replace('/wp-content.*$/','',__DIR__);
include($path.'wp-load.php');

?>
<?php

		$lang = $_GET['lang'];
		$basket = '';
		$amount = '';
		if ($lang == 'ru'){
				$basket="Корзина";
				$order="Заказываю!";
				$empty = 'Корзина пуста!';
				$amount = 'Сумма';
			}else if ($lang == 'ua') {
				$basket="Кошик";
				$order="Замовляю!";
				$empty = 'Кошик порожній!';
				$amount = 'Сума';
			}else if ($lang == 'pl' || get_locale() =="pl_PL") {
			$basket="Koszyk";
			$order="Zamawiam!";
			$empty = 'Koszyk jest pusty!';
			$amount = 'Kwota';
		}

?>
<div class="lg100 cart-content corner-radius psticky">
	<h2><?php echo $basket; ?></h2>
	<span class="close-cart-mobile">×</span>
	<div class="lg100 cart-items-sc">
	<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			echo '<div class="lg100 cart-item">';
				$product = $cart_item['data'];
			$product_id = $cart_item['product_id'];
			$quantity = $cart_item['quantity'];
			$subtotal = WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] );
			$link = $product->get_permalink( $cart_item );
			$thumbnail   = $product->get_image(array( 100, 100)); 
			echo '<div class="row row-margin">';
			echo '<div class="lg40 sm100 xs30 cproduct-thumbnail coverimg padding-15"><a href="'.$link.'">';
			echo $thumbnail;
			echo '</a></div>';
			echo '<div class="lg60 sm100 xs70 sm-offset-top xs100 cproduct-data padding-15">';
			echo '<h4>'.$product->name.'</h4>';
			echo '<span class="cproduct-price">'.$product->price.' zł</span>';			
			echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
			 '<a href="%s" class="remove-in-cart" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">×</a>',
			 	esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
			esc_html__( 'Remove this item', 'deepsoul' ),
			esc_attr( $product_id ),
			esc_attr( $product->get_sku() ),
			esc_attr( $cart_item_key )
			), $cart_item_key );
			echo '<div class="lg100 d-flex align-center justify-spaceb">';
			echo '<div class="buttons-qnt d-flex align-center">';
			echo '<button type="button" class="plus-btn"><img src="/wp-content/uploads/2022/10/Group-18912470.svg" alt="+"></button>';
			echo '<input type="text" data-product_id="'.$product_id.'" data-cart_item_key="'.$cart_item_key.'" class="input-text qty qty-cart text" step="1" min="-1" max="100"  value="'.$quantity.'" title="Szt." size="4" placeholder="" inputmode="numeric">';
			echo '<button type="button" class="minus-btn" ><img src="/wp-content/uploads/2022/10/Vector-2-1.svg" alt="+"></button>';	
			echo '</div></div></div></div></div>';
		}				
	?>
	</div>
	<?php if ( WC()->cart->get_cart_contents_count() == 0 ): ?>
		<span class="lg100 empty-cart"><?php echo $empty; ?></span>
	<?php else : ?>
	 <div class="lg100 cproduct-footer">
		 <span class="total-header"><?php echo $amount; ?>:</span> <span class="total-price"><?php echo  WC()->cart->get_total(); ?></span>
 		 <span class="button button2 lg100" id="checkout1"><span><?php echo $order; ?><span></span>
	 </div>
	<?php endif; ?>
</div>
<script>
	(function($) { 

	// Change qantity in the cart	
 $('button.plus-btn, button.minus-btn').on( 'click', function() {
	 
	 let product_id = $(this).attr("data-product_id"),
        cart_item_key = $(this).attr("data-cart_item_key");

        // Get current quantity values
        let qty = $( this ).closest( '.cart-item' ).find( '.qty-cart' );
        let val   = parseFloat(qty.val());
        let max = parseFloat(qty.attr( 'max' ));
        let min = parseFloat(qty.attr( 'min' ));
        let step = parseFloat(qty.attr( 'step' ));

        // Change the value if plus or minus
        if ( $( this ).is( '.plus-btn' ) ) {
           if ( max && ( max <= val ) ) {
              qty.val( max ).trigger('change');;
           } else {
              qty.val( val + step ).trigger('change');;
           }
        } else {
           if ( min && ( min >= val ) ) {
              qty.val( min ).trigger('change');;
           } else if ( val > 0 ) {
              qty.val( val - step ).trigger('change');;
           }
        }

     });
})(jQuery);
</script>