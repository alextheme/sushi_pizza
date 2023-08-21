<div class="lg100 banner-subpage">
	<div class="lg100 banner-subpage-img coverimg">
		<?php if(get_field('post_banner') != null): ?>
			<img src="<?php the_field('post_banner'); ?>" alt="">
		<?php elseif(is_search()): ?>
		<img src="/wp-content/uploads/2022/08/search-bg.jpg" alt="">
		<?php else: ?>
			<img src="/wp-content/uploads/2022/07/banner_pod.jpg" alt="">
		<?php endif;?>
	</div>
	<h1><?php if(!is_home() && !is_search()) {the_title();}elseif(!is_search()){echo 'Aktualności';} ?></h1>
	<?php if(is_search()): ?>
	<div class="form-search search-page-form">
		<h1>W czym możemy Ci pomóc ?</h1>
						<form role="search" <?php echo $aria_label;?> method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<input type="search" id="search-input" class="search-field" placeholder="Wpisz czego szukasz?" value="<?php echo get_search_query(); ?>" name="s" />

							<input type="submit" class="search-submit" value="Szukaj" />
						</form>
					</div>
	<?php endif;?>
</div>