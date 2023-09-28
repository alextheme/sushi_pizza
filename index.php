<?php get_header(); ?>
<div class="lg100 page-blackcontent offset-header">
<?php get_template_part( 'partials/pagebaner-subpage' ); ?>	
<?php if(is_home()) :?>
<div class="container p-bottom">
	<div class="lg100 filters-b p-top xs-offset-top">
		<span class="filter-button" id="youtube">
			Youtube	
		</span>
		<span class="filter-button" id="news">
			Aktualnosci
		</span>
		<span class="filter-button active-filter-b" id="all">
			All	
		</span>
	</div>
	<div class="lg100" id="news-archive">
	<?php $wpb_work_query2 = new WP_Query(
					array('post_type'=>'post',  'category_name' => 'news', 'posts_per_page' => 8)); ?>
				<?php if ( $wpb_work_query2->have_posts() ) : ?>
				<div class="row">
					<div class='lg100 p-30'>
						<div class="row row-margin">
							<?php $w=0;  while ( $wpb_work_query2->have_posts() ) : $wpb_work_query2->the_post(); ?>
							<article class="lg50 xs10 padding-15 work-offer-art <?php if(has_category('youtube')){echo 'youtube';}else{echo 'news';} ?>">
								<div class="lg100 work-offer-art-content promotions-item p-30">
									<div class="lg100">
										<a href="<?php the_permalink(); ?>" class="coverimg zoomIt"><?php the_post_thumbnail(); ?></a>
									</div>
									<h5><?php the_title(); ?></h5>
									<?php the_excerpt(); ?>
									<a class="txt-red" href="<?php the_permalink(); ?>">Pokaż</a>
								</div>
							</article>
							<?php endwhile; ?>
						</div>
					</div>
				</div>
				<div class="row centerMe offset-top-30">
					<a class="button" id="more-posts-arch" href="/">Zobacz więcej</a>	
				</div>
			<?php else: ?>
	<h2>Niestety nie znalexlismy takich aktualności!</h2>
	<?php endif; ?>
	</div>
</div>
<?php else: ?>
	
<div class="container p-bottom p-top">
       <?php


	$search ='';

	if ($_GET['s'] && !empty($_GET['s'])) {
			$search = $_GET['s'];
		}

	$argsEvents = array( 'post_type' => 'barcamp',  's' => $search);
	$argsCourses = array( 'post_type' => 'product',  's' => $search);
	$argsPosts= array( 'post_type' => 'post',  's' => $search);
			
	$query = new WP_Query( $argsEvents );
	$query2 = new WP_Query( $argsCourses );
	$query3 = new WP_Query( $argsPosts );
	
	echo '<div class="lg100 stags">Wyniki wyszukiwania: <a href="/">'.$search.'</a></div>';
	
	if($query2->have_posts()):
		echo '<div class="row">';
		while ( $query2->have_posts() ) : $query2->the_post();
	

			?>
		
			<article class="lg100 event-body d-flex align-center bckg-white shadowed">
				<div class="lg20 xs100 event-body-left">
					<div class="ev-image">
						<a href="<?php the_permalink(); ?>"><img src="/wp-content/uploads/2022/08/flag-course-exam.png" alt="" loading="lazy"></a>
					</div>
				</div>
				<div class="lg80 xs100 xs-offset-top event-body-middle">
					<div class="row align-center">
						<div class="lg80 event-body-middle-text">
							<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
							<?php the_excerpt(); ?>
						</div>
						<div class="lg20 xs-offset-top event-body-right">
							<a href="<?php the_permalink(); ?>">
								<img src="/wp-content/uploads/2022/07/Vector.svg" alt="" loading="lazy">
							</a>
						</div>
					</div>
				</div>
			</article>

		<?php  endwhile; wp_reset_query();  endif; echo '</div>'; ?>
	
   <?php  if($query->have_posts()):
		echo '<div class="row">';
		while ( $query->have_posts() ) : $query->the_post();	?>
	
			<article class="lg100 event-body d-flex align-center bckg-white shadowed">
				<div class="lg20 xs100 event-body-left">
					<div class="ev-image">
						<a href="<?php the_permalink(); ?>"><img src="/wp-content/uploads/2022/08/flag-barcamp.png" alt="" loading="lazy"></a>
					</div>
				</div>
				<div class="lg80 xs100 xs-offset-top event-body-middle">
					<div class="row align-center">
						<div class="lg80 event-body-middle-text">
							<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
							<?php the_excerpt(); ?>
						</div>
						<div class="lg20 xs-offset-top event-body-right">
							<a href="<?php the_permalink(); ?>">
								<img src="/wp-content/uploads/2022/07/Vector.svg" alt="" loading="lazy">
							</a>
						</div>
					</div>
				</div>
			</article>

		<?php  endwhile; wp_reset_query(); endif; echo ''; ?>
		
		<?php if($query3->have_posts()):
		echo '<div class="row">';
		while ( $query3->have_posts() ) : $query3->the_post();
	

			?>
		
			<article class="lg100 event-body d-flex align-center bckg-white shadowed">
				<div class="lg20 xs100 event-body-left">
					<div class="ev-image">
						<a href="<?php the_permalink(); ?>"><img src="/wp-content/uploads/2022/08/flag-news.png" alt="" loading="lazy"></a>
					</div>
				</div>
				<div class="lg80 xs100 xs-offset-top event-body-middle">
					<div class="row align-center">
						<div class="lg80 event-body-middle-text">
							<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
							<?php the_excerpt(); ?>
						</div>
						<div class="lg20 xs-offset-top event-body-right">
							<a href="<?php the_permalink(); ?>">
								<img src="/wp-content/uploads/2022/07/Vector.svg" alt="" loading="lazy">
							</a>
						</div>
					</div>
				</div>
			</article>

		<?php  endwhile; wp_reset_query(); endif; echo '</div>'; ?>
		
		<?php if($query->have_posts() || $query2->have_posts() || $query3->have_posts()) : ?>
		<?php else: ?>
		<h2 class="p-top-30 p-bottom text-center">Niestety nie udało nam się znaleźć tego czego szukasz</h2>
		
		<?php endif; echo '</div>'; ?>
		
	</div>
</div>
<?php endif; ?>
<?php get_footer();?>   
