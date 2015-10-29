<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
?>

<li  <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
			
		<h3><?php echo substr($post->post_title,0,25).'...'; ?></h3>
	</a>
			<div class="short_desc"><?php echo get_the_excerpt() ; ?> </div>
		
		<?php

		/**
		 * woocommerce_after_shop_loop_item hook
		 *
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item' ); 

	?>
		
		
		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			
		?>
	<div class="product-list-buttons">
	<a href="/asm/?action=yith-woocompare-add-product&amp;id=<?php echo $post->ID; ?>&amp;_wpnonce=7184b04fe4" class="compare" data-product_id="<?php echo $post->ID; ?>">Compare</a>
	

	<?php echo do_shortcode("[yith_wcwl_add_to_wishlist]"); ?>
	<div class="clear"></div> 
	<script type="text/javascript">
        if( !jQuery( '#yith-wcwl-popup-message' ).length ) {
            jQuery( 'body' ).prepend(
                '<div id="yith-wcwl-popup-message" style="display:none;">' +
                    '<div id="yith-wcwl-message"></div>' +
                    '</div>'
            );
        }
    </script>
</div>
	

	

</li>
