<?php

/**
 * Get image sizes for images
 *
 * @return array
 */
function ole_get_images_sizes() {
    return array(
        'post' => array(
            array(
                'name'      => 'post-large',
                'width'     => 1380,
                'height'    => 550,
                'crop'      => true,
            ),
            array(
                'name'      => 'post-medium',
                'width'     => 960,
                'height'    => 360,
                'crop'      => true,
            ),
            array(
                'name'      => 'post-small',
                'width'     => 410,
                'height'    => 150,
                'crop'      => true,
            ),
        ),

        'product' => array(
            array(
                'name'      => 'product-large',
                'width'     => 600,
                'height'    => 600,
                'crop'      => true,
            ),
            array(
                'name'      => 'product-medium',
                'width'     => 217,
                'height'    => 150,
                'crop'      => true,
            ),
            array(
                'name'      => 'product-small',
                'width'     => 100,
                'height'    => 100,
                'crop'      => true,
            ),
        ),
    );
}