<!-- header wrapper end -->
<?php

?>
  <script src="http://tutorials.app.com:2491/themes/ahlu_admin/widgets/slider/carousel/carouFredSel-master/jquery.carouFredSel-6.2.1.js"></script>
  
  <link rel="stylesheet" href="http://bxslider.com/lib/jquery.bxslider.css" type="text/css" />
  <script>
	$(document).ready(function(){
		$('.product-items ul').carouFredSel({
			responsive: true,
			auto: false,
			prev: '.prev',
			next: '.next',
			pagination: ".pager",
			mousewheel: true,
			swipe: {
				onMouse: true,
				onTouch: true
			}
		});

	});
  </script>
  
<div class="wrapper product-bg">
	<div class="ahlu-box panel-1 col-md-10 center-no-center" style="clear:inherit">
		<div class="ahlu-body">
			<div class="col-md-12">
				<div class="col-md-4 no-space">
					<h1 class="head-title">Your <span>ENQUIRES<span></h1>
				</div>
				<div class="col-md-4 no-space">			
					<div class="pagation_slide">
						<a class="prev left">&nbsp;</a>
							<div class="pager left"></div>
						<a class="next left" href="#">&nbsp;</a>
						
					</div>
				</div>
				<div class="col-md-4 no-space">
					<div class="pagation_select">
						<select name="filer_enquire">
							<option value="service enquiry">Service Enquiry</option>
							<option value="service enquiry">Product Enquiry</option>
						</select>
					</div>
					
				</div>				
			</div>
			<div class="col-md-12 no-space product-items">
				<ul>
				<li>
					<table>
						<tbody>
						<tr>
							<td class="img"><img src="<?php bloginfo('template_directory');?>/images/thumb_1.png" /></td>
							<td class="title"><span>Di ROCKDRILL</span> BOAR SERIES</td>
							<td class="note"><input type="text" name="" value="" placeholder="Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><span></span></td>
						</tr>
						<tr>
							<td class="img"><img src="<?php bloginfo('template_directory');?>/images/thumb_1.png" /></td>
							<td class="title"><span>Di ROCKDRILL</span> BOAR SERIES</td>
							<td class="note"><input type="text" name="" value="" placeholder="Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><span></span></td>
						</tr>
						<tr>
							<td class="img"><img src="<?php bloginfo('template_directory');?>/images/thumb_1.png" /></td>
							<td class="title"><span>Di ROCKDRILL</span> BOAR SERIES</td>
							<td class="note"><input type="text" name="" value="" placeholder="Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><span></span></td>
						</tr>
						<tr>
							<td class="img"><img src="<?php bloginfo('template_directory');?>/images/thumb_1.png" /></td>
							<td class="title"><span>Di ROCKDRILL</span> BOAR SERIES</td>
							<td class="note"><input type="text" name="" value="" placeholder="Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><span></span></td>
						</tr>
						<tr>
							<td class="img"><img src="<?php bloginfo('template_directory');?>/images/thumb_1.png" /></td>
							<td class="title"><span>Di ROCKDRILL</span> BOAR SERIES</td>
							<td class="note"><input type="text" name="" value="" placeholder="Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><span></span></td>
						</tr>
						</tbody>
					</table>
				</li>
				<li>
					<table>
						<tbody>
						<tr>
							<td class="img"><img src="<?php bloginfo('template_directory');?>/images/thumb_1.png" /></td>
							<td class="title"><span>Di ROCKDRILL</span> BOAR SERIES</td>
							<td class="note"><input type="text" name="" value="" placeholder="Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><span></span></td>
						</tr>
						<tr>
							<td class="img"><img src="<?php bloginfo('template_directory');?>/images/thumb_1.png" /></td>
							<td class="title"><span>Di ROCKDRILL</span> BOAR SERIES</td>
							<td class="note"><input type="text" name="" value="" placeholder="Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><span></span></td>
						</tr>
						<tr>
							<td class="img"><img src="<?php bloginfo('template_directory');?>/images/thumb_1.png" /></td>
							<td class="title"><span>Di ROCKDRILL</span> BOAR SERIES</td>
							<td class="note"><input type="text" name="" value="" placeholder="Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><span></span></td>
						</tr>
						<tr>
							<td class="img"><img src="<?php bloginfo('template_directory');?>/images/thumb_1.png" /></td>
							<td class="title"><span>Di ROCKDRILL</span> BOAR SERIES</td>
							<td class="note"><input type="text" name="" value="" placeholder="Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><span></span></td>
						</tr>
						<tr>
							<td class="img"><img src="<?php bloginfo('template_directory');?>/images/thumb_1.png" /></td>
							<td class="title"><span>Di ROCKDRILL</span> BOAR SERIES</td>
							<td class="note"><input type="text" name="" value="" placeholder="Write your Note" style="height: 80px; width: 100%;border: none;background: transparent;" /></td>
							<td class="status"><span></span></td>
						</tr>
						</tbody>
					</table>
				</li>
				</ul>
					<table>
						<tfoot>
							<tr>
								<td class="email" colspan="2"><input type="text" name="" value="" placeholder="|Write your Note" /></td>
								<td colspan="2" class="button"><a href="#">Send me my Enquires</a></td>
							</tr>
						</tfoot>
					</table>
			</div>	
		</div>
	</div>
</div>