<?php if( have_rows('taby') ): ?>
<div class="container offset-top">
	<div class="tabbler lg100">
		<ul class="tab-links">
			<?php $x=0; while ( have_rows('taby') ) : the_row(); ?>
			<li><a href="#<?php ++$x; echo 'tab'.$x; ?>"><?php the_sub_field('tytul'); ?></a></li>
			<?php endwhile; ?>
		</ul>
		<?php $y=0; while ( have_rows('taby') ) : the_row(); ?>
		<div id="<?php ++$y; echo 'tab'.$y; ?>" class="tab-content lg100">
			<?php the_sub_field('tresc'); ?>
		</div>
		<?php endwhile; ?>
	</div>
</div>
<?php endif; ?>