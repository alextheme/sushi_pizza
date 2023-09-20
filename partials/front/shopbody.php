<?php

$path = preg_replace('/wp-content.*$/','',__DIR__);
include($path.'wp-load.php');
$args = array( 'type' => 'product', 'taxonomy' => 'product_cat', 'hide_empty' => false ); 
$categories = get_categories( $args );
$first_category = '';
?>

<div class="row shop-body-front">
<div class="lg100 shop-header-tabs p-bottom-30 psticky">
	<ul>
		<?php
		$array_ignore_categories = get_list_ignore_categories();

		if ( count($categories) > 0 ) {

			foreach ( $categories as $categoryT ) {

				if ( ! in_array( $categoryT->slug, $array_ignore_categories ) ) {
					if ( $first_category === '' ) $first_category = $categoryT->slug; ?>

					<li class="category-filtr">
						<?php if(wp_is_mobile()):  ?>
							<a href="#<?php echo $categoryT->slug; ?>"><?php echo $categoryT->name; ?></a>
						<?php else: ?>
							<span id="<?php echo $categoryT->slug; ?>"><?php echo $categoryT->name; ?></span>
						<?php endif; ?>
					</li>

				<?php } ?>
			<?php } ?>
		<?php } ?>
		
	</ul>
</div>
<div class="row row-margin">
	<div class="lg70 sm100 xs100 products product-list-left padding-15" id="products-list" data-first-category="<?php echo esc_attr($first_category)?>">

		<?php if( ! wp_is_mobile() ): ?>

			<?php get_template_part( 'partials/front/products-list', null, array( 'first_category' => $first_category )); ?>

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
			} elseif (get_locale() == 'ua_UA') {
				$lang1 = "ua";
				$want = "Купую!";
				$want2 = "Додано!";
			}

		foreach( $categories as $category ): if ( ! in_array( $category->slug, $array_ignore_categories ) ) :
			echo '<div class="row row-margin products" id="'.$category->slug.'"><h3 class="category_title text_uppercase lg100 padding-15 xs-offset-top"><span>'.$category->name.'</span></h3>';
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
									<?php echo get_the_post_thumbnail( null, 'product-small'); ?>
								</a>
							</div>
							<a class="plink ptext xs70" href="<?php the_permalink(); ?>" id="<?php the_ID(); ?>">
								<h3><?php the_title(); ?></h3>
								<?php echo get_the_excerpt(); ?>
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
		<?php //wc_get_template_part( 'cart/cart', '' ); ?>

	</div>
	</div>
</div>