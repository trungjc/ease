<?php 
$page = get_page_by_title("footer");
//echo $page->post_content;

if(URI::getInstance()->isIndex){?>

<?php } ?>
<div class="col-md-11 center center-no-center" style="padding:30px 0;">
	<div class="col-md-4">
		<div class="logo-footer left">
			<a href="http://run.no-ip.info:2491/wp/equipment"><span>EASE</span></a>
		</div>
		<img class="left more-slogan" src="<?php bloginfo('template_directory');?>/images/logo_footer.png" />
	</div>
	<div class="col-md-4 social">
		<a href="" class="ahlu-icon ahlu-icon-facebook">&nbsp;</a>
		<a href="" class="ahlu-icon ahlu-icon-gplus">&nbsp;</a>
		<a href="" class="ahlu-icon ahlu-icon-linkin">&nbsp;</a>
		<a href="" class="ahlu-icon ahlu-icon-twitter">&nbsp;</a>
		<a href="" class="ahlu-icon ahlu-icon-wifi">&nbsp;</a>
	</div>
	<div class="col-md-4"><img  class="right" style="position: relative;top: -10px;" src="<?php bloginfo('template_directory');?>/images/downens_group.png" /></div>
</div>