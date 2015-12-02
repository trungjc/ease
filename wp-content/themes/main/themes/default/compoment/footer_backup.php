<?php  global $lang;  $page = get_page_by_title('footerPage');?>

<footer class="motopress-wrapper footer">
<div class="container">
<div class="row">
<div class="span12" data-motopress-wrapper-file="wrapper/wrapper-footer.php" data-motopress-wrapper-type="footer" data-motopress-id="54f7ca26df10c">
<div class="row footer-widgets">
<div class="span2" data-motopress-type="dynamic-sidebar" data-motopress-sidebar-id="footer-sidebar-1">
<div id="text-2" class=""><h4>Information</h4> 
<div class="textwidget">


<?php

$defaultsproduct = array(
	'menu'            => 'MenuInformation',
	'menu_class'      => 'product-categories',
	'menu_id'         => '',
	'echo'            => true,
	'fallback_cb'     => 'wp_page_menu',
	'items_wrap'      => '<ul>%3$s</ul>',
	'depth'           => 0,
	
);

wp_nav_menu( $defaultsproduct );

?>


</div>
</div> </div>
<div class="span2" data-motopress-type="dynamic-sidebar" data-motopress-sidebar-id="footer-sidebar-2">
<div id="text-3" class="">
<h4>Why buy from us </h4> <div class="textwidget">

<?php

$defaultsproduct = array(
	'menu'            => 'MenuWhyBuy',
	'menu_class'      => 'product-categories',
	'menu_id'         => '',
	'echo'            => true,
	'fallback_cb'     => 'wp_page_menu',
	'items_wrap'      => '<ul>%3$s</ul>',
	'depth'           => 0,
	
);

wp_nav_menu( $defaultsproduct );

?>

</div>
</div> </div>
<div class="span3" data-motopress-type="dynamic-sidebar" data-motopress-sidebar-id="footer-sidebar-3">
<div id="my_facebook_widget-2" class=""><h4>Follow Us On Facebook</h4> <div class="facebook_like_box">
<div id="fb-root"></div>
<script>
					(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
						fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));
					</script>
<div style="overflow:hidden;" class="fb-like-box " data-href=" <?php echo $page->post_excerpt; ?>" data-width="100%" data-height="100%" data-show-faces="true" data-stream="false" data-show-border="false" data-header="false"></div>
</div>
</div> </div>

 <?php echo $page->post_content; ?>


</div>
<div class="row copyright">
<div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-footer-nav.php">
</div>
</div> </div>
</div>
</div>
</footer>