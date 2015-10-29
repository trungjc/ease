<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$content=null;
ob_clean();
ob_start();?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		 
		//do_action( 'woocommerce_before_main_content' );						
	?>	
		<?php while ( have_posts() ) : the_post(); ?>	
<div class="row">
<div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-title.php"><section class="title-section">

<h1 class="title-header"><?php the_title(); ?> </h1>
 
<?php  woocommerce_breadcrumb();  ?>
 
</section>
</div>
</div>
<div class="row">
<div class="span9 right" id="content">
	
			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		//do_action( 'woocommerce_after_main_content' );
	?>
	</div>
	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		//do_action( 'woocommerce_sidebar' );
	?>

<?php

$content=ob_get_clean();
 $menuright = Ahlu::getObject("Blog_model");
 $template = Ahlu::Library("Template");
 $template->pathTheme = TEMPLATEPATH."/themes/default";
 $template->template = TEMPLATEPATH."/themes/default/default.php";
			$template->assign("header",$template->load->view( $template->pathTheme."/compoment/header.php",null,true));

			//$template->assign("content",$content);
			$template->assign("content",$template->load->view(TEMPLATEPATH ."/includes/mvc/view/ahlu-home-single-product.php",array("content"=>$content,"category"=>$menuright),true));
		
			$template->assign("footer",$template->load->view( $template->pathTheme."/compoment/footer.php",null,true));

           //output html
           $template->render(FALSE);
		   


 ?>
