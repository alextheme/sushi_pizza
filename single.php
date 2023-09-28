<?php get_header(); ?>

<?php if (is_singular()) { ?>

    <section class="site_container container">
        <!-- Content -->
        <div class="story single_post cf">
            <div class="cf">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/posthead' );?>
                    <?php get_template_part('template-parts/postcontent' );?>
                    <?php get_template_part('template-parts/postfooter' );?>

                <?php endwhile; else: ?>
                    <?php get_template_part('template-parts/notfound')?>
                <?php endif; ?>
            </div>


            <?php //if (comments_open() || get_comments_number()) : comments_template(); endif; ?>
        </div>
    </section>

<?php } else { ?>
    <?php if(have_posts()) :
        while (have_posts()) : the_post();
            get_template_part( 'template' );
        endwhile;
    endif; ?>
<?php } ?>



<?php get_footer();?>   
