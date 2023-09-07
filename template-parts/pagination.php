<?php

global $wp_query, $blogs, $paged;

if (is_archive()) {
    $total = $wp_query->max_num_pages;
} else {
    $total = $blogs->max_num_pages;
}
?>

<div class="pagination">
    <div class="pagination__wrapper">
        <?php
        $big = 999999999;// This is a number for a page that does not exist, but is required to generate pagination correctly.

        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, $paged),
            'total' => $total,
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
        ));
        ?>
    </div>
</div>

