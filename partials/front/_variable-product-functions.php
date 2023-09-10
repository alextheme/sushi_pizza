<?php

function render_list_variants($product, $attributes) {

    foreach ($attributes as $attribute_name => $options) : ?>

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

    <?php endforeach;
}

function render_list_components($components, $title) { ?>

    <li class="ac variable_product__item variable_product__item--checkbox simpleAdditionalComponents">
        <h4 class="ac-header">
            <button type="button" class="ac-trigger variable_product__attr_title"><?php echo esc_html( $title ); ?></button>
            <div class="variable_product__attr_title_icon"><svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.86603 7.5C5.48113 8.16667 4.51887 8.16667 4.13397 7.5L0.669874 1.5C0.284974 0.833333 0.7661 -8.94676e-07 1.5359 -8.27378e-07L8.4641 -2.21695e-07C9.2339 -1.54397e-07 9.71503 0.833333 9.33013 1.5L5.86603 7.5Z" fill="#FCB326"/></svg></div>
        </h4>
        <div class="ac-panel">
            <ul class="variable_product__terms">

                <?php
                foreach ($components as $product_item) { ?>

                    <li class="variable_product__term">

                        <input type="checkbox" class="variable_product__input"
                               id="inpt_<?php echo esc_attr($product_item->ID).'_'.esc_attr($title); ?>"
                               data-group-name="<?php echo esc_attr($title); ?>"
                               data-product_id="<?php echo esc_attr($product_item->ID); ?>"
                        >
                        <label class="variable_product__label" for="inpt_<?php echo esc_attr($product_item->ID).'_'.esc_attr($title); ?>">
                            <span class="variable_product__checkbox_icon">
                                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect class="rect_border" stroke="#FCB326" x="0.5" y="0.5" width="18"
                                          height="18" rx="3.5"/>
                                    <path class="rect_checked" fill="#FCB326"
                                          d="M15.2585 5.36598C15.4929 5.60039 15.6245 5.91828 15.6245 6.24973C15.6245 6.58119 15.4929 6.89907 15.2585 7.13348L9.00853 13.3835C8.77412 13.6178 8.45623 13.7495 8.12478 13.7495C7.79332 13.7495 7.47544 13.6178 7.24103 13.3835L4.74103 10.8835C4.51333 10.6477 4.38734 10.332 4.39018 10.0042C4.39303 9.67649 4.52449 9.36297 4.75625 9.13121C4.98801 8.89945 5.30153 8.76799 5.62927 8.76514C5.95702 8.76229 6.27277 8.88829 6.50853 9.11598L8.12478 10.7322L13.491 5.36598C13.7254 5.13164 14.0433 5 14.3748 5C14.7062 5 15.0241 5.13164 15.2585 5.36598Z"/>
                                </svg>
                            </span>
                            <span class="variable_product__item_title"><?php echo esc_html($product_item->post_title); ?></span>
                        </label>
                    </li>

                <?php } ?>
            </ul>
        </div>
    </li>

<?php }

function render_list_additional_products() {
    $args = array(
        'taxonomy' => 'product_cat', // WooCommerce product category taxonomy
        'hide_empty' => false,       // Show empty categories as well
        'orderby' => 'name',         // Sort by name
        'order' => 'ASC'             // Sort Order (Ascending)
    );
    $categories = get_terms($args); ?>

    <?php foreach ($categories as $category) { ?>

        <?php // ignore categories
        // if( in_array( $category->slug, $ignore_categories ) ) { continue; } ?>

        <?php
        $args = array(
            'post_type' => 'product', // Тип запису "product"
            'posts_per_page' => -1, // Всі продукти
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',    // Taxonomy of product categories
                    'field' => 'term_id',           // Field to compare (can be 'id', 'slug', 'name', etc.)
                    'terms' => $category->term_id,  // The ID of the category you want to select
                ),
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => array( 'simple' ),
                    'operator' => 'IN',
                ),
            ),
        );

        $products_query = new WP_Query($args); ?>

        <?php if ($products_query->have_posts()) : ?>

        <li class="ac variable_product__item variable_product__item--buttons_quantity simpleAdditionalProducts">
            <h4 class="ac-header">
                <button type="button" class="ac-trigger variable_product__attr_title">
                    <?php echo esc_html( $category->name ); ?></button>
                <div class="variable_product__attr_title_icon">
                    <svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.86603 7.5C5.48113 8.16667 4.51887 8.16667 4.13397 7.5L0.669874 1.5C0.284974 0.833333 0.7661 -8.94676e-07 1.5359 -8.27378e-07L8.4641 -2.21695e-07C9.2339 -1.54397e-07 9.71503 0.833333 9.33013 1.5L5.86603 7.5Z" fill="#FCB326"/>
                    </svg>
                </div>
            </h4>

            <div class="ac-panel">
                <ul class="variable_product__products_list">

                    <?php while ($products_query->have_posts()) :
                        $products_query->the_post();
                        global $product; ?>

                        <li class="variable_product__product_item zero_product">

                            <div class="variable_product__product_buttons">

                                <div class="variable_product__product_quantity_wrap">

                                    <button type="button" class="variable_product__product_button btn_minus">
                                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="28" height="28" rx="4" fill="#FCB326"/>
                                            <path d="M14.625 13.375H19C19.1658 13.375 19.3247 13.4408 19.4419 13.5581C19.5592 13.6753 19.625 13.8342 19.625 14C19.625 14.1658 19.5592 14.3247 19.4419 14.4419C19.3247 14.5592 19.1658 14.625 19 14.625H14.625H9C8.83424 14.625 8.67527 14.5592 8.55806 14.4419C8.44085 14.3247 8.375 14.1658 8.375 14C8.375 13.8342 8.44085 13.6753 8.55806 13.5581C8.67527 13.4408 8.83424 13.375 9 13.375H14.625Z" fill="#1E1D1A"/>
                                        </svg>
                                    </button>

                                    <input type="text" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
                                           class="variable_product__product_quantity"  title="Szt." size="4" placeholder=""
                                           step="1" min="-1" max="100" value="0" inputmode="numeric" readonly="readonly">

                                    <button type="button" class="variable_product__product_button btn_plus">
                                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g class="init">
                                                <rect x="0.5" y="0.5" width="27" height="27" rx="3.5" stroke="white" stroke-opacity="0.5"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14 8.375C14.1658 8.375 14.3247 8.44085 14.4419 8.55806C14.5592 8.67527 14.625 8.83424 14.625 9V13.375H19C19.1658 13.375 19.3247 13.4408 19.4419 13.5581C19.5592 13.6753 19.625 13.8342 19.625 14C19.625 14.1658 19.5592 14.3247 19.4419 14.4419C19.3247 14.5592 19.1658 14.625 19 14.625H14.625V19C14.625 19.1658 14.5592 19.3247 14.4419 19.4419C14.3247 19.5592 14.1658 19.625 14 19.625C13.8342 19.625 13.6753 19.5592 13.5581 19.4419C13.4408 19.3247 13.375 19.1658 13.375 19V14.625H9C8.83424 14.625 8.67527 14.5592 8.55806 14.4419C8.44085 14.3247 8.375 14.1658 8.375 14C8.375 13.8342 8.44085 13.6753 8.55806 13.5581C8.67527 13.4408 8.83424 13.375 9 13.375H13.375V9C13.375 8.83424 13.4408 8.67527 13.5581 8.55806C13.6753 8.44085 13.8342 8.375 14 8.375Z" fill="white" fill-opacity="0.5"/>
                                            </g>
                                            <g class="active">
                                                <rect width="28" height="28" rx="4" fill="#FCB326"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14 8.375C14.1658 8.375 14.3247 8.44085 14.4419 8.55806C14.5592 8.67527 14.625 8.83424 14.625 9V13.375H19C19.1658 13.375 19.3247 13.4408 19.4419 13.5581C19.5592 13.6753 19.625 13.8342 19.625 14C19.625 14.1658 19.5592 14.3247 19.4419 14.4419C19.3247 14.5592 19.1658 14.625 19 14.625H14.625V19C14.625 19.1658 14.5592 19.3247 14.4419 19.4419C14.3247 19.5592 14.1658 19.625 14 19.625C13.8342 19.625 13.6753 19.5592 13.5581 19.4419C13.4408 19.3247 13.375 19.1658 13.375 19V14.625H9C8.83424 14.625 8.67527 14.5592 8.55806 14.4419C8.44085 14.3247 8.375 14.1658 8.375 14C8.375 13.8342 8.44085 13.6753 8.55806 13.5581C8.67527 13.4408 8.83424 13.375 9 13.375H13.375V9C13.375 8.83424 13.4408 8.67527 13.5581 8.55806C13.6753 8.44085 13.8342 8.375 14 8.375Z" fill="#1E1D1A"/>
                                            </g>
                                        </svg>
                                    </button>

                                </div>
                            </div>

                            <span class="variable_product__product_text">
                                <span class="variable_product__product_title">
                                    <?php the_title() ?>
                                </span>

                                <span class="variable_product__product_price">+ <?php echo $product->get_price_html(); ?>zl</span>
                            </span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </li>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    <?php }
}

