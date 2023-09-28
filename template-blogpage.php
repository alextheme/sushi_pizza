<?php

/* Template Name: Blog Template */

get_header();

?>

<div class="blog blog_template lg100 page-blackcontent offset-header offset-bottom">
    <div class="container">
        <div class="blog__header row">
            <header class="lg100 p-top p-bottom blog__header_w">

                <div class="blog__header_bg_wrap">
                    <div class="blog__header_bg" style="background-image: url(<?php the_post_thumbnail_url('large' ); ?>)"></div>
                </div>

                <h1 class="txt-center blog__title"><?php echo pll__( the_title() ); ?></h1>

                <div class="blog__text lg60">
                    <?php the_content(); ?>
                </div>
            </header>
        </div>

        <div class="categories">
            <ul class="categories__list">
                <li class="categories__item categories__item--current"><?php echo esc_html_e('All','pizza_sushi'); ?></li>
                <?php
                // Get all post categories
                $categories = get_categories(array(
                    'taxonomy' => 'category',  // Specify the taxonomy (category for default post categories)
                    'hide_empty' => 0,         // Show empty categories as well (change to 1 to hide empty categories)
                ));

                // Loop through the categories and display them
                if (!empty($categories)) {

                    foreach ($categories as $category) {
                        echo '<li class="categories__item"><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
                    }

                } else {
                    echo 'No categories found.';
                }
                ?>
            </ul>
        </div>


        <div class="blog__content row row-margin">

        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 4,
            'paged' => $paged,
        );

        $blogs = new WP_Query($args); ?>

        <?php if ($blogs->have_posts()) : while ($blogs->have_posts()) : $blogs->the_post(); ?>

            <article <?php post_class('grid-item blog__item lg50 sm100 padding-15'); ?> id="post-<?php the_ID()?>" data-post-id="<?php the_ID()?>">
                <div class="post__wrapper">
                    <?php if(get_the_post_thumbnail($blogs->ID,'post-small')){?>
                        <div class="post__thumbnail">
                            <?php echo get_the_post_thumbnail($blogs->ID,'post-small'); ?>
                        </div>
                    <?php  } ?>

                    <div class="post__data">

                    <span class="post__category">
                      <?php the_category(', '); ?>
                    </span>

                        <h2 class="post__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>

                        <?php if(get_field('post_sub_title')) { ?>
                            <span class="post__subtitle">
                            <?php echo esc_attr(get_field('post_sub_title')); ?>
                        </span>
                        <?php } ?>

                        <div class="post__info">
                            <span class="read_more_button font_two"><a href="<?php the_permalink(); ?>"><span class="icon"><i class="fa fa-arrow-right" aria-hidden="true"></i></span> <?php esc_html_e('Read More','gardener'); ?></a></span>

<!--                        <span class="comments_count"><i class="fa fa-commenting-o" aria-hidden="true"></i><?php //comments_number(); ?></span>-->

                            <span class="date">
                            <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo get_the_date(); ?>
                        </span>

                        </div>
                    </div>
                </div>
            </article>



        <?php endwhile; else: ?>
            <?php get_template_part('template-parts/notfound')?>
        <?php endif; ?>

        </div>

        <?php get_template_part('template-parts/pagination'); ?>

        <?php wp_reset_query(); ?>

    </div>
</div>

<?php get_footer(); ?>
