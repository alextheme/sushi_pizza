<?php
include 'helpers.php';
include 'config.php';

/**
 * Initialize Theme Support Features
 */
function ole_init_theme_support() {
    if (function_exists('ole_get_images_sizes')) {
        foreach (ole_get_images_sizes() as $post_type => $sizes) {
            foreach ($sizes as $config) {
                add_image_size($config['name'], $config['width'], $config['height'], $config['crop']);
            }
        }
    }
}
add_action('init', 'ole_init_theme_support');