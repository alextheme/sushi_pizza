<?php

$path = preg_replace('/wp-content.*$/','',__DIR__);
include($path.'wp-load.php');
$args = array( 'type' => 'product', 'taxonomy' => 'product_cat', 'hide_empty' => false ); 
$categories = get_categories( $args ); 

?>

<div class="row shop-body-front">
<div class="lg100 shop-header-tabs p-bottom-30 psticky">
	<ul>
		<?php 
		
			$category1 = "checkout";
			if (get_locale() == 'ru_RU'){
				$category1="checkout-ru";
			}elseif (get_locale() == 'ua_UA') {
				$category1="checkout-ua";
			}
		
			if ( count($categories) > 0 ) :
				if(wp_is_mobile()): 
					foreach ( $categories as $categoryT ) : if($categoryT->slug != $category1) : ?>
							<li class="<?php if($category == $categoryT->slug){echo 'selected';} ?>category-filtr"><a href="#<?php echo $categoryT->slug; ?>"><?php echo $categoryT->name; ?></a></li>
					<?php endif; endforeach;
				else:
					foreach ( $categories as $categoryT ) : if($categoryT->slug != $category1) : ?>
							<li class="<?php if($category == $categoryT->slug){echo 'selected';} ?>category-filtr"><span id="<?php echo $categoryT->slug; ?>"><?php echo $categoryT->name; ?></span></li>
		<?php endif; endforeach;
				endif;
			endif;
		?>
		
	</ul>
</div>
<div class="row row-margin">
	<div class="lg70 sm100 xs100 products product-list-left padding-15" id="products-list">
		<?php if(!wp_is_mobile()): ?>
			<?php get_template_part( 'partials/front/products-list'); ?>
		<?php else: ?>
		<?php
			$lang1 = "pl";
			$want = "Kupuję!";
			$want2 = "Dodano!";
			$want4 = "Brak!";
			if (get_locale() == 'ru_RU'){
				$lang1 = "ru";
				$want = "Покупаю!";
				$want2 = "Добавлен!";
			}elseif (get_locale()== 'ua_UA') {
				$lang1 = "ua";
				$want = "Купую!";
				$want2 = "Додано!";
			}	
			
		foreach( $categories as $category ): if($category->slug != $category1) :
		echo '<div class="row row-margin products alignerContainer" id="'.$category->slug.'"><h3 class="text_uppercase lg100 padding-15 xs-offset-top">'.$category->name.'</h3>';
		$args = array( 'post_type' => 'product',  'product_cat' =>  $category->slug, 'orderby' => 'menu_order', 'posts_per_page' => 20);
		$query = new WP_Query( $args );
			if($query->have_posts()):
			while ( $query->have_posts() ) : $query->the_post();
			?>
			<div class="lg33 sm33 xs100 padding-15 product txt-white offset-bottom-30 alignMe">
				<div class="lg100 product-content corner-radius">
					<div class="row product-content-row justify-spaceb">
						<div class="lg100 xs30 product-height corner-radius-img">
							<a class="lg100 plink coverimg" href="<?php the_permalink(); ?>" id="<?php the_ID(); ?>">
								<?php the_post_thumbnail(); ?>
							</a>
						</div>
						<a class="plink ptext xs70" href="<?php the_permalink(); ?>" id="<?php the_ID(); ?>">
							<h3><?php the_title(); ?></h3>
							<?php echo mb_strcut(get_the_excerpt(), 0, 135); ?>...
						</a>
					</div>
					<div class="lg100 product-footer">
						<span class="price"><?php $product = wc_get_product( get_the_ID() ); echo $product->get_price(); ?> zł</span>
						<?php if($product->is_in_stock()){ echo sprintf('<a href="%s" data-quantity="1" class="%s" %s>'.$want.'<span class="was-added">'.$want2.'</span></a>', esc_url($product->add_to_cart_url()), esc_attr(implode(' ', array_filter(array('button','product_type_' . $product->get_type(),$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : 'dsdsds', $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '')))), wc_implode_html_attributes(array('data-product_id' => $product->get_id(), 'data-product_sku' => $product->get_sku(),'aria-label' => $product->add_to_cart_description(),'rel' => 'nofollow')), esc_html($product->add_to_cart_text()));}else {echo '<a class="button not_in_stock" href="'.get_permalink().'">'.$want4.'</a>';}  ?>
					</div>	
					<div class="card-bckg"></div>
				</div>
			</div>
		<?php endwhile; endif; wp_reset_postdata();  echo '</div>'; endif; endforeach; endif;?>
	</div>
	<div class="lg30 xs100 product-sidebar padding-15" id="product-sidebar-cart">
		<?php get_template_part( 'partials/front/cart' ); ?>
	</div>
	</div>
</div>