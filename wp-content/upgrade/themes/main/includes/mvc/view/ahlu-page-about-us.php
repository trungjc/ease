<?php 



global $lang;

$upload_dir = wp_upload_dir();



$page = get_page_by_title( 'About us' );

?>



 

 

<div class="motopress-wrapper content-holder clearfix">

<div class="container">

<div class="row">

<div class="span12" data-motopress-wrapper-file="page-fullwidth.php" data-motopress-wrapper-type="content">

<div class="row">

<div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-title.php">

<section class="title-section">

<h1 class="title-header">

About us </h1>

 

<ul class="breadcrumb breadcrumb__t"><li><a href="<?php echo site_url(); ?>">Home</a></li><li class="divider"></li><li class="active">About us</li></ul>  

</section>  </div>

</div>

<div id="content" class="row">

<div class="span12" data-motopress-type="loop" data-motopress-loop-file="loop/loop-page.php">

<div id="post-1797" class="post-1797 page type-page status-publish hentry">

<div class="custom_about_content">
<div class="custom_about_content_wrap_inner">
<?php

echo $page->post_content;



 ?>
</div>
</div>

<div class="custom_adv_testi">
<div class="custom_adv_testi_wrap_inner">

<div class="row ">





<?php

echo $page->post_excerpt;;



 ?>



<div class="span6 "><h3>Testimonials</h3>



<div class="testimonials ">



<?php 

		$Testimonial = Ahlu::getObject("Testimonial_model");

			$data = $Testimonial->listPostType(2,URI::getInstance()->page);

					

					if($data!=null){

				

					foreach($data->data as $k=>$item) { 					

?>

<div class="testi-item list-item-1">

<blockquote class="testi-item_blockquote">
	<span><?php echo date("F j, Y, g:i a",strtotime($item->post_date)); ?></span>
	<figure class="featured-thumbnail">
	
	<img src="<?php echo $item->thumbnail; ?>" alt=""/>
	
	</figure>
	
	<a href="testimonial/"  style="margin-top: 5px;"><?php echo substr($item->post_content,0,400); ?>&hellip;</a>
	
	<div class="clear"></div>
</blockquote>

<small class="testi-meta" style="margin-top: -38px;"><span class="user"><?php echo $item->post_title; ?></span></small></div>

					<?php }} ?>

</div>

</div>





</div> 

</div></div>

<div class="clear"></div>

 

</div> 

</div>

</div>

</div>

</div>

</div>

</div>
