<?php get_header(); ?>

<?php

global $wp_query;
$curr_cat = $wp_query->get_queried_object();
$active = '';

?>
    <div class="blog blog_categories lg100 page-blackcontent offset-header offset-bottom">
        <div class="container">
            <div class="blog_categories__header row">
                <h2 class="blog_categories__title page__title">
                    <?php echo $curr_cat->name; ?>
                </h2>
            </div>

            <div class="categories">
                <ul class="categories__list">
                    <li class="categories__item"><a
                            href="<?php echo home_url("/blog"); ?>"><?php echo esc_html_e('All', 'pizza_sushi'); ?></a>
                    </li>

                    <?php
                    // Get all post categories
                    $categories = get_categories(array(
                        'taxonomy' => 'category',  // Specify the taxonomy (category for default post categories)
                        'hide_empty' => 0,         // Show empty categories as well (change to 1 to hide empty categories)
                    ));

                    // Loop through the categories and display them
                    if (!empty($categories)) {

                        foreach ($categories as $category) {
                            if ($category->term_id === $curr_cat->term_id) {
                                echo '<li class="categories__item categories__item--current">' . $category->name . '</li>';
                            } else {
                                echo '<li class="categories__item"><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
                            }
                        }

                    } else {
                        echo 'No categories found.';
                    }
                    ?>
                </ul>
            </div>


            <div class="blog__content row row-margin">

            <?php if (have_posts()) : while (have_posts()) : the_post();  ?>

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