<?php

function read_cart_enquiry(){
	$file = dirname(__FILE__)."/woo.txt";
	if(!file_exists($file)){
		touch($file);
		file_put_contents($file,serialize(array()));
		return array();
	}
	//read file
	$data = file_get_contents($file);
	return unserialize($data);
}

function add_service_enquiry(){
	if(isset($_POST) && count($_POST)>0){
		
		//look up the file to check if this product has been added as service enquiry
		$file = dirname(__FILE__)."/woo.txt";
		if(!file_exists($file)){
			touch($file);
			file_put_contents($file,serialize(array($_POST["id_product"]=>$_POST["type"])));
			echo 1;
			die();
		}
		//read file
		$data = file_get_contents($file);
		if(empty($data)){$data = array();}else{$data = unserialize($data);}
		//just add once
		
		if(isset($data[$_POST["id_product"]])) {echo 0; die();}

		//here we go
		$data[$_POST["id_product"]] = $_POST["type"];

		//save 
		file_put_contents($file,serialize($data));
		echo 1;
		die();
	}
}
add_action( 'wp_ajax_add_service_enquiry', 'add_service_enquiry' );
add_action( 'wp_ajax_nopriv_add_service_enquiry', 'add_service_enquiry' );

function get_cart_service(){
	if(isset($_POST) && count($_POST)>0){
		//look up the file to check if this product has been added as service enquiry
		$file = dirname(__FILE__)."/woo.txt";
		if(!file_exists($file)){
			touch($file);
			echo 0;
			die();
		}
		$data = file_get_contents($file);
		if(empty($data)){echo 0;die();}

		$data = unserialize($data);
		echo  count($data);
		die();
	}
}
add_action( 'wp_ajax_get_service_enquiry', 'get_cart_service' );
add_action( 'wp_ajax_nopriv_get_service_enquiry', 'get_cart_service' );


function delete_cart_service($id=null,$ok=false){
	if(isset($_POST) && count($_POST)>0){
		//look up the file to check if this product has been added as service enquiry
		$file = dirname(__FILE__)."/woo.txt";
		if(!file_exists($file)){
			touch($file);
			echo 0;
			die();
		}
		$data = file_get_contents($file);
		if(empty($data)){echo 0;die();}

		$data = unserialize($data);
		if($id!=null && isset($data[$id])){
			unset($data[$id]);
			
			if($ok){
				file_put_contents($file,serialize($data));
				return;
			}
		}
		if(isset($data[$_POST["id_product"]])) unset($data[$_POST["id_product"]]);
		//save
		file_put_contents($file,serialize($data));
		echo 1;
		die();
	}
}
add_action( 'wp_ajax_delete_cart_service', 'delete_cart_service' );
add_action( 'wp_ajax_nopriv_delete_cart_service', 'delete_cart_service' );


function clear_cart_service($ok=false){
	if(isset($_POST) && count($_POST)>0){
		//look up the file to check if this product has been added as service enquiry
		$file = dirname(__FILE__)."/woo.txt";
		file_put_contents($file,serialize(array()));
		if(!$ok){
			echo 1;
			die();
		}
	}
}
add_action( 'wp_ajax_clear_cart_service', 'clear_cart_service' );
add_action( 'wp_ajax_nopriv_clear_cart_service', 'clear_cart_service' );

/*
//custom breadcrumb
add_filter( 'woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs' );
function jk_woocommerce_breadcrumbs() {
    return array(
            'delimiter'   => ' &#47; ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
            'wrap_after'  => '</nav>',
            'before'      => '',
            'after'       => '',
            'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
        );
}
*/
/*
//custom detail product
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
add_action( 'woocommerce_single_product_summary', function(){
	echo "ok here";
}, 50 );
*/
/**
 * Product Add to cart
 *
 * @see woocommerce_template_single_add_to_cart()
 * @see woocommerce_simple_add_to_cart()
 * @see woocommerce_grouped_add_to_cart()
 * @see woocommerce_variable_add_to_cart()
 * @see woocommerce_external_add_to_cart()
 */
/*
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
add_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
add_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
add_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
add_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
add_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
*/

/////////////////
/**
 * Clear Cart for WooCommerce
 */
add_action( "init", "we_woocommerce_clear_cart_url" );
function we_woocommerce_clear_cart_url() {
	global $woocommerce;
	if(isset($_REQUEST["ahlu"])) {
		$ahlu = json_decode(stripslashes($_POST["ahlu"]));
		if($ahlu->clear_cart){
			//clear cart from woo

			$woocommerce->cart->empty_cart();
			echo site_url()."/shop";
			//remove fake cart
			clear_cart_service(true);
			
			die();
		}
	}
	
	
}

/**
 * Remove product on begin to load template 
 */
//add_action( "template_redirect", "remove_product_from_cart" );
add_filter('template_include', 'remove_product_from_cart');
function remove_product_from_cart($template) {
	
    // Run only in the Cart or Checkout Page
        // Set the product ID to remove
      

		if(isset($_POST) && count($_POST)>0){
			//global $woocommerce;
			$ahlu = json_decode(stripslashes($_POST["ahlu"]));
			//print_r($_POST["ahlu"]);
			$prod_to_remove = $ahlu->id_product;
			//print_r($woocommerce->cart->cart_contents);
			
			delete_cart_service($prod_to_remove,true);
 			//die();
	
	        // Cycle through each product in the cart
	        foreach( WC()->cart->cart_contents as $i=> $prod_in_cart ) {
	            // Get the Variation or Product ID
	            $prod_id = ( isset( $prod_in_cart["variation_id"] ) && $prod_in_cart["variation_id"] != 0 ) ? $prod_in_cart["variation_id"] : $prod_in_cart["product_id"];
			
	            // Check to see if IDs match
	            if( $prod_to_remove == $prod_id ) {
	                // Get it's unique ID within the Cart
	                $prod_unique_id = WC()->cart->generate_cart_id( $prod_id );
	                // Remove it from the cart by un-setting it
	                WC()->cart->remove_cart_item($prod_unique_id);
					echo "Delete Product successfully.";
					die();
	            }
	        }
			echo "error";
			die();
		}

	return $template;
}
?>