<?php

function restaurants_init_tax() {

        register_taxonomy(
			'restaurants',
        	'product',
			array(
            'labels' => array(
                'name' => 'Restauracje',
                'singular_name' => 'Restauracja',
                'all_items' => 'Restauracje',
                'add_new' => 'Dodaj nową Restauracje',
                'add_new_item' => 'Dodaj nową restaurację',
                'edit_item' => 'Edytuj restaurację',
                'new_item' => 'Nowe restauracja',
                'view_item' => 'Zobacz restaurację',
                'search_items' => 'Szukaj',
                'not_found' =>  'Nie znaleziono żadnych restauracji',
                'not_found_in_trash' => 'Nie znaleziono żadnych restauracji w koszu', 
                'parent_item_colon' => ''
            ),
            'public' => true,
            'show_ui' => true, 
            'query_var' => true,
            'hierarchical' => true,
            'supports' => array(
                'title','editor','author','thumbnail','excerpt','comments', 'post-formats'
            ),
            'has_archive' => true,
        ));
       
}

add_action( 'init',  'restaurants_init_tax' );


?>
