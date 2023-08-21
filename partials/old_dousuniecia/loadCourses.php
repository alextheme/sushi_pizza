<?php 


$path = preg_replace('/wp-content.*$/','',__DIR__);
include($path.'wp-load.php');

 $courses = json_decode(stripslashes($_POST['courses']), true);



  global $wpdb;
$skus = $wpdb->get_results ("SELECT `product_id`, `sku` FROM `wp_wc_product_meta_lookup` WHERE `sku` NOT LIKE ''");



	foreach ($courses as $course) {

	foreach ($skus as $sku) {
		if($sku->sku == $course['sku']){	 

			 $terms_del_id = $wpdb->get_results ("SELECT `term_id` FROM `wp_wc_product_attributes_lookup` WHERE `product_id`=".$sku->product_id." AND `taxonomy`='pa_data'");

			 foreach ($terms_del_id as $term_del_id) {

			 	$wpdb->query  ("
                    DELETE FROM `wp_terms` WHERE `term_id` = ".$term_del_id->term_id."
                  ");

			 	$wpdb->query  ("
                    DELETE FROM `wp_wc_product_attributes_lookup` WHERE `term_id` = ".$term_del_id->term_id."
                  ");

			 	$wpdb->query  ("
                    DELETE FROM `wp_termmeta` WHERE `term_id` = ".$term_del_id->term_id."
                  ");

			 	$wpdb->query  ("
                    DELETE FROM `wp_term_taxonomy` WHERE `term_id` = ".$term_del_id->term_id."
                  ");

			 	
			 	print_r($term_del_id->term_id);
			 }

}
}
}

foreach ($courses as $course) {

	foreach ($skus as $sku) {
		if($sku->sku == $course['sku']){

			$startAT = explode("+", str_replace("T"," ",$course['startAt']))[0];
			$eventPlace = $course['eventPlace']['address'];

			  $wpdb->query  ("
                    Insert INTO `wp_terms`(`name`, `slug`, `term_group`) VALUES ('".$startAT." - ".$eventPlace."','".$startAT." - ".$eventPlace."','0');
                  ");


			  $term_id = $lastid = $wpdb->insert_id;

			 $wpdb->query  ("
                    Insert INTO `wp_wc_product_attributes_lookup`(`product_id`, `product_or_parent_id`, `taxonomy`, `term_id`, `is_variation_attribute`, `in_stock`) VALUES ('".$sku->product_id."','".$sku->product_id."','pa_data','".$term_id."', '0', '1');
                  ");

			  $wpdb->query  ("
                    Insert INTO `wp_termmeta`(`term_id`, `meta_key`, `meta_value`) VALUES ('".$term_id."','order_pa_data','0');
                  ");

			   $meta_id = $lastid = $wpdb->insert_id;


			   $wpdb->query  ("
                    Insert INTO `wp_term_taxonomy`(`term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES ('".$term_id."','pa_data','','0', '1');
                  ");

			   $tt_id = $lastid = $wpdb->insert_id;
				
				$wpdb->query  ("
                    Insert INTO `wp_term_relationships`(`term_taxonomy_id`, `object_id`, `term_order`) VALUES ('".$tt_id."','".$sku->product_id."', '1');
                  ");



				



		}
	}
	
}

 


	



 ?>