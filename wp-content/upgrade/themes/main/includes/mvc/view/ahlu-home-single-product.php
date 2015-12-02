<?php global $lang; ?>
<?php if(isset($WP_enable) && $WP_enable) get_header(); ?> 

<div class="motopress-wrapper content-holder clearfix woocommerce">
<div class="container">
 <?php echo $content; ?>
 

<div class="sidebar span3" id="sidebar" data-motopress-type="static-sidebar" data-motopress-sidebar-file="sidebar.php">



<div id="categories-4" class="widget"><h3>Categories</h3> 

<ul>
<?php
			
	$categories = $category->menuTop();

	foreach($categories as $item){

		echo '<li class="cat-item cat-item-'.$item->term_id.'"><a href="'.site_url("{$item->slug}.html").'">'.$item->name.'</a> <span class="count">('.$item->count.')</span></li>';

	}
?>
</ul>


</div>
<div id="woocommerce_product_categories-2" class="widget">
<style>
#woocommerce_product_categories-2{list-style: none;} </style>
<h3>Product Categories</h3>

<?php  echo do_shortcode('[do_widget "WooCommerce Product Categories"]'); ?>
</div>

<div id="woocommerce_top_rated_products-2" class="widget">
<style>
#woocommerce_top_rated_products-2{list-style: none;} </style>
<h3>Top Rated Products</h3>
<?php  echo do_shortcode('[do_widget "WooCommerce Top Rated Products"]'); ?>
</div>

 </div>
</div>
</div>
</div>