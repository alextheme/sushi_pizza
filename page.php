<?php

$pages_redirect = [26, 1603, 1597];
if (is_page($pages_redirect)) {
	wp_redirect('/');
}

?>

<?php get_header(); ?>

<?php if(have_posts()) :
	while (have_posts()) : the_post(); 
    	get_template_part( 'template' );
    endwhile;
endif; ?>

<?php get_footer();?>   
