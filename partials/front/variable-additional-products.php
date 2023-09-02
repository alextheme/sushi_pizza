<?php

// $product_id, $lang
// $lang = $_GET['lang'];

switch ($lang) {
    case 'ua':
        $button_text = 'Купую!';
        $title_text = 'Обери складові';
        break;
    case 'ru':
        $button_text = 'Покупаю!';
        $title_text = 'Выбери состав';
        break;

    default:
        $button_text = 'Kupuję!';
        $title_text = 'Wybierz skŁadnik';

}

$product = wc_get_product($product_id);
$available_variations = $product->get_available_variations();
$variations_json = wp_json_encode($available_variations);
$product_variations = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);

$attributes = $product->get_variation_attributes();
?>

<div class="variable_product variable_product--additional_product">
    <div class="variable_product__wrapper">

        <span onclick="document.body.classList.remove('body--preloader_show')"
              class="variable_product__close_btn"
              aria-label="button close popup variable product"><span></span></span>

        <h3 class="variable_product__title"><?php echo esc_html($product->get_title()); ?>
            - <?php echo $product->get_id(); ?></h3>

        <div class="variable_product__container">
            <div class="variable_product__inner_container">
                <!--TODO temp-->
                <!--                <div class="variable_product__image_w">-->
                <!--                    <img src="-->
                <?php //echo wp_get_attachment_url( $product->get_image_id() ); ?><!--" />-->
                <!--                </div>-->

                <form
                    id="variable_product__form"
                    class="variable_product__form variations_form"
                    method="post"
                    enctype="multipart/form-data"
                    action="<?php echo esc_url($product->get_permalink()); ?>"
                    data-product_id="<?php echo absint($product->get_id()); ?>"
                    data-product_variations="<?php echo $product_variations; // WPCS: XSS ok. ?>">

                    <ul class="variable_product__list accordion_box">

                        <!-- Radio buttons ( Variants )                -->
                        <?php foreach ($attributes as $attribute_name => $options) : ?>
                            <?php $attribute_label = wc_attribute_label($attribute_name); ?>

                            <li class="variable_product__item variable_product__item--radio">
                                <h4 class="variable_product__attr_title"><?php echo esc_html($attribute_label); ?></h4>

                                <ul class="variable_product__attr_list">
                                    <?php
                                    $opt_i = 0;
                                    foreach ($options as $option) :
                                        $variation_default_attribute = $product->get_variation_default_attribute($attribute_name);
                                        $term = get_term_by('slug', $option, $attribute_name);
                                        $var_default_attr = $variation_default_attribute ? $variation_default_attribute === $option : $opt_i === 0;
                                        ?>
                                        <li class="variable_product__attr_item">
                                            <input type="radio"
                                                   name="variable_<?php echo esc_attr($attribute_name); ?>"
                                                   class="variable_product__input"
                                                   id="variable_<?php echo esc_attr($attribute_name . '_' . $option); ?>"
                                                   data-attribute_name="<?php echo esc_attr($attribute_name); ?>"
                                                   data-term_slug="<?php echo esc_attr($term ? $term->slug : $option); ?>"
                                                <?php echo $var_default_attr ? 'checked="checked"' : ''; ?>
                                            >
                                            <label class="variable_product__label"
                                                   for="variable_<?php echo esc_attr($attribute_name . '_' . $option); ?>">
                                                <span
                                                    class="variable_product__item_title"><?php echo esc_html($term ? $term->name : $option); ?></span>
                                                <span class="variable_product__icon"></span>
                                            </label>
                                        </li>

                                        <?php $opt_i++; endforeach;
                                    unset($opt_i); ?>
                                </ul>
                            </li>

                        <?php endforeach; ?>

                        <!-- Checkboxes ( Ingredients )                 -->
                        <?php

                        $cat_arr_slugs = ['pizza_ingredients', 'sosy'];

                        // let's put all simple products in one array
                        $categories_with_products = array();

                        foreach ($cat_arr_slugs as $cat_slug) {
                            $category = get_term_by('slug', $cat_slug, 'product_cat');
                            $categories_with_products[$category->term_id] = array(
                                'category' => $category
                            );
                        }

                        $additional = new WP_Query(array(
                            'post_type' => 'product',
                            'posts_per_page' => -1,
                            'orderby' => 'parent',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field' => 'term_id',
                                    'terms' => array_keys($categories_with_products),
                                ),
                            ),
                        ));

                        if ($additional->have_posts()) :
                            while ($additional->have_posts()) :
                                $additional->the_post();
                                global $product;
                                ?>

                                <!-- Checkboxes ( Ingredients )  variable product-->
                                <?php
                                if ($product->is_type('variable')) {

                                    $curr_product = wc_get_product($product->get_id());
                                    $curr_available_variations = $curr_product->get_available_variations();
                                    $curr_variations_json = wp_json_encode($curr_available_variations);
                                    $curr_product_variations = function_exists('wc_esc_json') ? wc_esc_json($curr_variations_json) : _wp_specialchars($curr_variations_json, ENT_QUOTES, 'UTF-8', true);


                                    $var_attr = $product->get_variation_attributes();

                                    foreach ($var_attr as $attr_name => $attr_options) :
                                        $attr_label = wc_attribute_label($attr_name); ?>

                                        <li class="ac variable_product__item variable_product__item--checkbox"
                                            data-product_id="<?php echo absint($product->get_id()); ?>"
                                            data-product_variations="<?php echo $curr_product_variations; // WPCS: XSS ok.
                                            ?>"
                                        >
                                            <h4 class="ac-header">
                                                <button type="button"
                                                        class="ac-trigger variable_product__attr_title"><?php echo esc_html($attr_label); ?></button>
                                                <div class="variable_product__attr_title_icon">
                                                    <svg width="10" height="8" viewBox="0 0 10 8" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.86603 7.5C5.48113 8.16667 4.51887 8.16667 4.13397 7.5L0.669874 1.5C0.284974 0.833333 0.7661 -8.94676e-07 1.5359 -8.27378e-07L8.4641 -2.21695e-07C9.2339 -1.54397e-07 9.71503 0.833333 9.33013 1.5L5.86603 7.5Z"
                                                            fill="#FCB326"/>
                                                    </svg>
                                                </div>
                                            </h4>
                                            <div class="ac-panel">
                                                <ul class="variable_product__terms">

                                                    <?php $index = 0; foreach ($attr_options as $opt) :
                                                        $var_default_attr = $product->get_variation_default_attribute($attr_name);
                                                        $term = get_term_by('slug', $opt, $attr_name);

                                                        // // select the first element by default, if none is selected in the admin
                                                        $var_default_attr = $var_default_attr ? $var_default_attr === $opt : $index === 0;
                                                        ?>

                                                        <li class="variable_product__term">
                                                            <input type="checkbox"
                                                                   name="variable_<?php echo esc_attr($attr_name); ?>"
                                                                   class="variable_product__input"
                                                                   id="variable_<?php echo esc_attr($attr_name . '_' . $opt); ?>"
                                                                   data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                                                                   data-attribute_name="<?php echo esc_attr($attr_name); ?>"
                                                                   data-term_slug="<?php echo esc_attr($term ? $term->slug : $opt); ?>"
                                                                <?php echo $var_default_attr ? 'checked="checked"' : ''; ?>
                                                            >
                                                            <label class="variable_product__label"
                                                                   for="variable_<?php echo esc_attr($attr_name . '_' . $opt); ?>">
                                                        <span class="variable_product__checkbox_icon">
                                                            <svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <rect class="rect_border" stroke="#FCB326" x="0.5"
                                                                      y="0.5" width="18" height="18" rx="3.5"/>
                                                                <path class="rect_checked" fill="#FCB326"
                                                                      d="M15.2585 5.36598C15.4929 5.60039 15.6245 5.91828 15.6245 6.24973C15.6245 6.58119 15.4929 6.89907 15.2585 7.13348L9.00853 13.3835C8.77412 13.6178 8.45623 13.7495 8.12478 13.7495C7.79332 13.7495 7.47544 13.6178 7.24103 13.3835L4.74103 10.8835C4.51333 10.6477 4.38734 10.332 4.39018 10.0042C4.39303 9.67649 4.52449 9.36297 4.75625 9.13121C4.98801 8.89945 5.30153 8.76799 5.62927 8.76514C5.95702 8.76229 6.27277 8.88829 6.50853 9.11598L8.12478 10.7322L13.491 5.36598C13.7254 5.13164 14.0433 5 14.3748 5C14.7062 5 15.0241 5.13164 15.2585 5.36598Z"/>
                                                            </svg>
                                                        </span>
                                                                <span
                                                                    class="variable_product__item_title"><?php echo esc_html($term ? $term->name : $opt); ?></span>
                                                            </label>
                                                        </li>


                                                    <?php $index++; endforeach; ?>
                                                </ul>
                                            </div>
                                        </li>

                                    <?php endforeach; ?>

                                <?php } ?>

                                <!-- simple product      -->
                                <?php
                                if ($product->is_type('simple')) {
                                    foreach ($product->category_ids as $category_id) {

                                        // Если массив для данной категории ещё не создан, создайте его
                                        if (!isset($categories_with_products[$category_id])) {
                                            $categories_with_products[$category_id] = [];
                                        }

                                        // Добавьте продукт в массив данной категории
                                        $categories_with_products[$category_id]['products'][] = array(
                                            'product_id' => $product->get_id(),
                                            'product_title' => $product->get_title(),
                                        );
                                    }
                                } ?>


                            <?php endwhile; ?>
                        <?php endif; ?>
                        <?php wp_reset_postdata(); ?>

                        <?php foreach ($categories_with_products as $cat_id => $item) { ?>
                            <?php if (!isset($item['products'])) {
                                continue;
                            } ?>

                            <li class="ac variable_product__item variable_product__item--checkbox">
                                <h4 class="ac-header">
                                    <button type="button"
                                            class="ac-trigger variable_product__attr_title"><?php echo esc_html($item['category']->name); ?></button>
                                    <div class="variable_product__attr_title_icon">
                                        <svg width="10" height="8" viewBox="0 0 10 8" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.86603 7.5C5.48113 8.16667 4.51887 8.16667 4.13397 7.5L0.669874 1.5C0.284974 0.833333 0.7661 -8.94676e-07 1.5359 -8.27378e-07L8.4641 -2.21695e-07C9.2339 -1.54397e-07 9.71503 0.833333 9.33013 1.5L5.86603 7.5Z"
                                                fill="#FCB326"/>
                                        </svg>
                                    </div>
                                </h4>
                                <div class="ac-panel">
                                    <ul class="variable_product__terms">

                                        <?php foreach ($item['products'] as $pr0duct) { ?>

                                            <li class="variable_product__term">
                                                <?php $input_id = 'cat_' . $cat_id . '_prod_' . $pr0duct['product_id']; ?>

                                                <input type="checkbox"
                                                       name="product_<?php echo esc_attr($cat_id); ?>"
                                                       class="variable_product__input"
                                                       id="<?php echo esc_attr($input_id); ?>"
                                                       data-product_id="<?php echo esc_attr($pr0duct['product_id']); ?>"
                                                >
                                                <label class="variable_product__label"
                                                       for="<?php echo esc_attr($input_id); ?>">
                                                    <span class="variable_product__checkbox_icon">
                                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <rect class="rect_border" stroke="#FCB326" x="0.5" y="0.5" width="18"
                                                  height="18" rx="3.5"/>
                                            <path class="rect_checked" fill="#FCB326"
                                                  d="M15.2585 5.36598C15.4929 5.60039 15.6245 5.91828 15.6245 6.24973C15.6245 6.58119 15.4929 6.89907 15.2585 7.13348L9.00853 13.3835C8.77412 13.6178 8.45623 13.7495 8.12478 13.7495C7.79332 13.7495 7.47544 13.6178 7.24103 13.3835L4.74103 10.8835C4.51333 10.6477 4.38734 10.332 4.39018 10.0042C4.39303 9.67649 4.52449 9.36297 4.75625 9.13121C4.98801 8.89945 5.30153 8.76799 5.62927 8.76514C5.95702 8.76229 6.27277 8.88829 6.50853 9.11598L8.12478 10.7322L13.491 5.36598C13.7254 5.13164 14.0433 5 14.3748 5C14.7062 5 15.0241 5.13164 15.2585 5.36598Z"/>
                                        </svg>
                                    </span>
                                                    <span
                                                        class="variable_product__item_title"><?php echo esc_html($pr0duct['product_title']); ?></span>
                                                </label>
                                            </li>

                                        <?php } ?>
                                    </ul>
                                </div>
                            </li>

                        <?php } ?>


                        <!--TODO work processing -->
                        <!-- Quantity buttons ( Categories )                   -->
                        <!--                        --><?php
                        //                        $args = array(
                        //                            'taxonomy' => 'product_cat', // WooCommerce product category taxonomy
                        //                            'hide_empty' => false,       // Show empty categories as well
                        //                        );
                        //                        $categories = get_terms($args); ?>
                        <!---->
                        <!--                        --><?php //foreach ($categories as $category) { ?>
                        <!---->
                        <!--                            --><?php //if( $category->slug === $term_slug ) { continue; } ?>
                        <!---->
                        <!--                            <li class="ac variable_product__item variable_product__item--buttons_quantity">-->
                        <!--                                <h4 class="ac-header">-->
                        <!--                                    <button type="button" class="ac-trigger variable_product__attr_title">-->
                        <?php //echo esc_html( $category->name ); ?><!--</button>-->
                        <!--                                    <div class="variable_product__attr_title_icon">-->
                        <!--                                        <svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">-->
                        <!--                                            <path d="M5.86603 7.5C5.48113 8.16667 4.51887 8.16667 4.13397 7.5L0.669874 1.5C0.284974 0.833333 0.7661 -8.94676e-07 1.5359 -8.27378e-07L8.4641 -2.21695e-07C9.2339 -1.54397e-07 9.71503 0.833333 9.33013 1.5L5.86603 7.5Z" fill="#FCB326"/>-->
                        <!--                                        </svg>-->
                        <!--                                    </div>-->
                        <!--                                </h4>-->
                        <!---->
                        <!--                                <div class="ac-panel">-->
                        <!--                                    <ul class="variable_product__products_list">-->
                        <!--                                        --><?php //$products_query = new WP_Query(array(
                        //                                            'post_type' => 'product',
                        //                                            'posts_per_page' => -1,
                        //                                            'tax_query' => array(
                        //                                                array(
                        //                                                    'taxonomy' => 'product_cat',
                        //                                                    'field' => 'term_id',
                        //                                                    'terms' => $category->term_id,
                        //                                                ),
                        //                                            ),
                        //                                        ));
                        //
                        //                                        if ($products_query->have_posts()) : while ($products_query->have_posts()) : $products_query->the_post(); global $product; ?>
                        <!---->
                        <!--                                            <li class="variable_product__product_item zero_product">-->
                        <!---->
                        <!--                                                <div class="variable_product__product_buttons">-->
                        <!---->
                        <!--                                                    <div class="variable_product__product_quantity_wrap">-->
                        <!---->
                        <!--                                                        <button type="button" class="variable_product__product_button btn_minus">-->
                        <!--                                                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">-->
                        <!--                                                                <rect width="28" height="28" rx="4" fill="#FCB326"/>-->
                        <!--                                                                <path d="M14.625 13.375H19C19.1658 13.375 19.3247 13.4408 19.4419 13.5581C19.5592 13.6753 19.625 13.8342 19.625 14C19.625 14.1658 19.5592 14.3247 19.4419 14.4419C19.3247 14.5592 19.1658 14.625 19 14.625H14.625H9C8.83424 14.625 8.67527 14.5592 8.55806 14.4419C8.44085 14.3247 8.375 14.1658 8.375 14C8.375 13.8342 8.44085 13.6753 8.55806 13.5581C8.67527 13.4408 8.83424 13.375 9 13.375H14.625Z" fill="#1E1D1A"/>-->
                        <!--                                                            </svg>-->
                        <!--                                                        </button>-->
                        <!---->
                        <!--                                                        <input type="text" data-product_id="-->
                        <?php // ?><!--" data-cart_item_key="--><?php // ?><!--"-->
                        <!--                                                               name="variable_product__product_quantity"-->
                        <!--                                                               class="variable_product__product_quantity"  title="Szt." size="4" placeholder=""-->
                        <!--                                                               step="1" min="-1" max="100" value="0" inputmode="numeric" readonly="readonly">-->
                        <!---->
                        <!--                                                        <button type="button" class="variable_product__product_button btn_plus">-->
                        <!--                                                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">-->
                        <!--                                                                <g class="init">-->
                        <!--                                                                    <rect x="0.5" y="0.5" width="27" height="27" rx="3.5" stroke="white" stroke-opacity="0.5"/>-->
                        <!--                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14 8.375C14.1658 8.375 14.3247 8.44085 14.4419 8.55806C14.5592 8.67527 14.625 8.83424 14.625 9V13.375H19C19.1658 13.375 19.3247 13.4408 19.4419 13.5581C19.5592 13.6753 19.625 13.8342 19.625 14C19.625 14.1658 19.5592 14.3247 19.4419 14.4419C19.3247 14.5592 19.1658 14.625 19 14.625H14.625V19C14.625 19.1658 14.5592 19.3247 14.4419 19.4419C14.3247 19.5592 14.1658 19.625 14 19.625C13.8342 19.625 13.6753 19.5592 13.5581 19.4419C13.4408 19.3247 13.375 19.1658 13.375 19V14.625H9C8.83424 14.625 8.67527 14.5592 8.55806 14.4419C8.44085 14.3247 8.375 14.1658 8.375 14C8.375 13.8342 8.44085 13.6753 8.55806 13.5581C8.67527 13.4408 8.83424 13.375 9 13.375H13.375V9C13.375 8.83424 13.4408 8.67527 13.5581 8.55806C13.6753 8.44085 13.8342 8.375 14 8.375Z" fill="white" fill-opacity="0.5"/>-->
                        <!--                                                                </g>-->
                        <!--                                                                <g class="active">-->
                        <!--                                                                    <rect width="28" height="28" rx="4" fill="#FCB326"/>-->
                        <!--                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14 8.375C14.1658 8.375 14.3247 8.44085 14.4419 8.55806C14.5592 8.67527 14.625 8.83424 14.625 9V13.375H19C19.1658 13.375 19.3247 13.4408 19.4419 13.5581C19.5592 13.6753 19.625 13.8342 19.625 14C19.625 14.1658 19.5592 14.3247 19.4419 14.4419C19.3247 14.5592 19.1658 14.625 19 14.625H14.625V19C14.625 19.1658 14.5592 19.3247 14.4419 19.4419C14.3247 19.5592 14.1658 19.625 14 19.625C13.8342 19.625 13.6753 19.5592 13.5581 19.4419C13.4408 19.3247 13.375 19.1658 13.375 19V14.625H9C8.83424 14.625 8.67527 14.5592 8.55806 14.4419C8.44085 14.3247 8.375 14.1658 8.375 14C8.375 13.8342 8.44085 13.6753 8.55806 13.5581C8.67527 13.4408 8.83424 13.375 9 13.375H13.375V9C13.375 8.83424 13.4408 8.67527 13.5581 8.55806C13.6753 8.44085 13.8342 8.375 14 8.375Z" fill="#1E1D1A"/>-->
                        <!--                                                                </g>-->
                        <!--                                                            </svg>-->
                        <!--                                                        </button>-->
                        <!---->
                        <!--                                                    </div>-->
                        <!--                                                </div>-->
                        <!---->
                        <!--                                                <span class="variable_product__product_text">-->
                        <!--                                                    <span class="variable_product__product_title">-->
                        <!--                                                        --><?php //the_title() ?>
                        <!--                                                    </span>-->
                        <!---->
                        <!--                                                    <span class="variable_product__product_price">-->
                        <!--                                                        + -->
                        <?php //echo $product->get_price_html(); ?><!--zl-->
                        <!--                                                    </span>-->
                        <!--                                                </span>-->
                        <!---->
                        <!---->
                        <!--                                            </li>-->
                        <!---->
                        <!--                                        --><?php //endwhile; else : echo 'No products found.'; endif; ?>
                        <!--                                    </ul>-->
                        <!--                                </div>-->
                        <!--                            </li>-->
                        <!---->
                        <!--                            --><?php //wp_reset_postdata(); ?>
                        <!--                        --><?php //} ?>

                    </ul>

                    <input type="hidden" name="quantity" value="0"/>
                    <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>"/>
                    <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>"/>
                    <input type="hidden" name="variation_id" class="variation_id" value="0"/>

                    <input type="hidden" name="additional_products_variations" value=""/>
                </form>
            </div>
        </div>

        <!-- button -->
        <div class="variable_product__btn_submit button button2">
            <button form="variable_product__form" type="submit" class="single_add_to_cart_button"
                    alt="<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>">
                <span><?php echo esc_html($button_text); ?></span>
            </button>
        </div>

    </div>
</div>

<script>
    (function ($) {
        $(document).ready(function () {
            // Accordion & height pop-up
            ;(() => {
                $('.variable_product--additional_product .variable_product__wrapper').css({
                    height: `${window.innerHeight - 40}px`,
                });

                const accordions = Array.from(document.querySelectorAll('.accordion_box'));

                new Accordion(accordions, {
                    duration: 300,
                    showMultiple: true,
                    openOnInit: [0, 1, 2, 6],//[...Array(accordions[0].childElementCount).keys()],
                    onOpen: function (currentElement) {
                        console.log(currentElement);
                    }
                })
            })(); // - Accordion & height pop-up


            // Get and set the Variant ID of the product(s).
            ;(() => {
                $('input[name="variation_id"]').val(getVariantId());

                $('.variable_product__item--radio input[type="radio"]').on('change', function () {
                    $('input[name="variation_id"]').val(getVariantId());
                });

                function getVariantId() {
                    var variations = $('.variable_product__form').data('product_variations');
                    var attributes = {};

                    $('.variable_product__item--radio input[type="radio"]').each((i, elem) => {
                        var $elem = $(elem);

                        if ($elem.is(':checked')) {
                            var attrName = 'attribute_' + $elem.data('attribute_name');
                            attributes[attrName] = $elem.data('term_slug');
                        }
                    })

                    var m = findMatchingVariations(variations, attributes);

                    return m.length ? m[0].variation_id : 0;
                }

                function findMatchingVariations(variations, attributes) {
                    var matching = [];
                    for (var i = 0; i < variations.length; i++) {
                        var variation = variations[i];

                        if (isMatch(variation.attributes, attributes)) {
                            matching.push(variation);
                        }
                    }
                    return matching;
                }

                function isMatch(variation_attributes, attributes) {
                    var match = true;
                    for (var attr_name in variation_attributes) {

                        if (variation_attributes.hasOwnProperty(attr_name)) {
                            var val1 = variation_attributes[attr_name];
                            var val2 = attributes[attr_name];
                            if (val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2) {
                                match = false;
                            }
                        }
                    }
                    return match;
                }


                function getDataProductsVariations() {
                    const $items = $('.variable_product__item--checkbox[data-product_id]')
                    const $checkboxes = $items.find( 'input[type="checkbox"]' )

                    const args = {};

                    Array.from( $items )
                        .map(e => ({
                            product_id: e.dataset.product_id,
                            product_variations: JSON.parse( e.dataset.product_variations ),
                        }))
                        .forEach(elem => args[elem.product_id] = elem )

                    $checkboxes.each( (i, checkbox) => {
                        if ( checkbox.checked ) {
                            var product_id = $(checkbox).data( 'product_id' );
                            var attr = 'attribute_' + $(checkbox).data( 'attribute_name' ).toLowerCase();
                            var term = $(checkbox).data( 'term_slug' );

                            if ( ! args[product_id].hasOwnProperty('selected') ) {
                                args[product_id]['selected'] = {}
                            }

                            if ( ! args[product_id]['selected'].hasOwnProperty(attr) ) {
                                args[product_id]['selected'][attr] = [ term ]
                            } else {
                                args[product_id]['selected'][attr].push( term )
                            }
                        }
                    })

                    Object.keys(args).forEach(id => {
                        const combinations = generateCombinations( args[id].selected )
                        args[id].combinations = combinations

                        combinations.forEach( combination => {
                            const m = findMatchingVariations( args[id].product_variations, combination )
                            console.log( combination, '|', m[0]?.variation_id )
                        })
                    })
                    console.log( args )

                    /********/
                    function generateCombinations(obj) {
                        const keys = Object.keys(obj);

                        // Базовий випадок: об'єкт порожній, повертаємо пустий об'єкт
                        if (keys.length === 0) {
                            return [{}];
                        }

                        const firstKey = keys[0];
                        const restKeys = keys.slice(1);
                        const firstArray = obj[firstKey];

                        const restCombinations = generateCombinations(
                            restKeys.reduce((acc, key) => {
                                acc[key] = obj[key];
                                return acc;
                            }, {})
                        );

                        const combinations = [];

                        for (const item of firstArray) {
                            for (const restCombo of restCombinations) {
                                combinations.push({ [firstKey]: item, ...restCombo });
                            }
                        }

                        // Додавання комбінацій, де перший ключ відсутній
                        restCombinations.forEach((restCombo) => {
                            combinations.push({ ...restCombo });
                        });

                        return combinations;
                    }

                    return JSON.stringify([ 'args' ]);
                }

                $('input[name="additional_products_variations"]').val(getDataProductsVariations());

                $('.variable_product__item--checkbox[data-product_id] input[type="checkbox"]').on('change', function (e) {
                    $('input[name="additional_products_variations"]').val(getDataProductsVariations());
                })


                // // test
                // const dataProductsVariations = $('input[name="additional_products_variations"]').val();
                // console.log(dataProductsVariations && JSON.parse(dataProductsVariations))
            })();



            // Add to Cart -- PRODUCTS
            ;(() => {
                $('.variable_product__btn_submit .single_add_to_cart_button').on('click', function (e) {
                    e.preventDefault();

                    $.ajax({
                        type: 'POST',
                        url: ajax_data.ajaxUrl,
                        data: {
                            action: 'o10_add_products_to_cart',
                            nonce: ajax_data.nonce,
                            lang: Cookies.get('pll_language'),
                            products: getProducts(),
                        },
                        beforeSend: function () {
                            $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
                        },
                        success: function (response) {
                            updateShoppingCart();
                        },
                        error: function (error) {
                            console.error(error);
                        },
                        complete: function () {
                            $('body').removeClass('body--preloader_show').css({paddingRight: '0px'})
                        }
                    });
                });

                function getProducts() {
                    var products = [];
                    var product_id = $('.variable_product__form').data('product_id');
                    var variation_id = $('.variable_product__form').find('input[name="variation_id"]').val();

                    products.push({id: product_id, quantity: 1, variation: variation_id})

                    console.log(products)
                    return products;
                }
            })(); // - Add to Cart -- PRODUCTS

            // +/- buttons quantity
            ;(() => {

                // plus quantity (+)
                $('.variable_product__product_button.btn_plus').on('click', function (e) {
                    var $item = $(this).closest('.variable_product__product_item')
                    var $inputQuantity = $item.find('input[name="variable_product__product_quantity"]')

                    if ($item.hasClass('zero_product')) {
                        $item.removeClass('zero_product')
                        $inputQuantity.val(1)
                    } else {
                        var quantity = +$inputQuantity.val()
                        $inputQuantity.val(quantity + 1)
                    }
                })

                // minus quantity (-)
                $('.variable_product__product_button.btn_minus').on('click', function (e) {
                    var $item = $(this).closest('.variable_product__product_item')
                    var $inputQuantity = $item.find('input[name="variable_product__product_quantity"]')

                    var quantity = +$inputQuantity.val();
                    $inputQuantity.val(quantity - 1)

                    if (quantity - 1 === 0) {
                        $item.addClass('zero_product')
                    }
                });

                // init 1 quantity
                $('.variable_product__product_text').on('click', function (e) {
                    var $item = $(this).closest('.variable_product__product_item')
                    var $inputQuantity = $item.find('input[name="variable_product__product_quantity"]')

                    if (+$inputQuantity.val() > 0) {
                        return;
                    }

                    if ($item.hasClass('zero_product')) {
                        $item.removeClass('zero_product')
                        $inputQuantity.val(1)
                    } else {
                        var quantity = +$inputQuantity.val()
                        $inputQuantity.val(quantity + 1)
                    }
                });
            })(); // - +/- buttons quantity


            function updateShoppingCart() {

                $.ajax({
                    // url: wc_add_to_cart_params.ajax_url,
                    url: ajax_data.ajaxUrl,
                    type: 'get',
                    data: {
                        action: 'o10_update_cart',
                        nonce: ajax_data.nonce,
                        lang: Cookies.get('pll_language'),
                    },
                    success: function (response) {
                        $('#product-sidebar-cart').html(response);
                    },
                    error: function (response) {
                        console.error(response.statusText);
                        console.error(response.responseText);
                    },
                    beforeSend: function () {
                        $('.cart_content_preloader').addClass('show');
                    },
                    complete: function () {
                        $('.cart_content_preloader').removeClass('show');
                    }
                })
            }
        })
    })(jQuery);
</script>

