<!-- header wrapper end -->
<?php
$me = $post->getMe();
?>
<div class="wrapper product-bg">
	<div class="ahlu-box panel-1 col-md-10 center-no-center" style="clear:inherit">
		<div class="ahlu-body">
			<a href="#" onclick="window.history.back();"; class="return-back" title="return back" alt="return back" ><img src="<?php bloginfo('template_directory');?>/images/go_back.png" title="return back" alt="return back" /></a>
			
			<div style="col-md-12">
				<h1 class="head-title left">DI ROCKDRILL <span>BOAR SERIES<span></h1>
				<div class="right"><a href="?add_to_cart"><img src="<?php bloginfo('template_directory');?>/images/add_to_enquiry.png" style="position: relative;right: -68px;" /></a></div>
			</div>
			<div class="col-md-12 no-space product-items">
				<div class="col-md-7 no-space">
					<div class="col-md-12">
							<p style="color: black;font-size: 20px;font-family: sans-serif;">
							The new <b>Boar Lightweight Series</b> Di RockDrills are designed for use in the <b>mining and construction industries</b>. Australian
						designed and European made according to exact engineering and <b>quality standards</b>.
								<p style="color: darkgrey;font-size: 15px;font-family: sans-serif;">
						The new generation Boar Rockdrills are lighter and better balanced. These improvements have been achieved through a combination ol new designs, materials, 
						heat treatment processes and manufacturing techniques. Three diflerent models are available, each suited to various ground and operating conditions.
							</p>
					</div>
					<div class="col-md-12" style="margin-top:30px;">
						<table border="1" cellpadding="1" cellspacing="1" style="width:500px;">
							<tbody>
								<tr>
									<td><span style="color:#fda584;font-size: 18px;font-weight: bold;">Model Di</span></td>
									<td><span style="color:#fda584;font-size: 18px;font-weight: bold;">Part<br />
									Number</span></td>
									<td><span style="color:#fda584;font-size: 18px;font-weight: bold;">Impact<br />
									Rate</span></td>
									<td><span style="color:#fda584;font-size: 18px;font-weight: bold;">Hz<br />
									Rotation</span></td>
								</tr>
								<tr>
									<td><span style="color:#000000;">Boar</span></td>
									<td><em><span style="color:#000000;">9 307 01</span></em></td>
									<td><em><span style="color:#000000;">3,100</span></em></td>
									<td><span style="color:#000000;">320 RPM</span></td>
								</tr>
								<tr>
									<td><span style="color:#000000;">Boar Max</span></td>
									<td><em><span style="color:#000000;">9 307 05</span></em></td>
									<td><em><span style="color:#000000;">2,750</span></em></td>
									<td><span style="color:#000000;"><strong>320</strong> RPM</span></td>
								</tr>
								<tr>
									<td><span style="color:#000000;">Max Plus</span></td>
									<td><em><span style="color:#000000;">9 307 13</span></em></td>
									<td><em><span style="color:#000000;">2,650</span></em></td>
									<td><span style="color:#000000;"><strong>320</strong> RPM</span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-5 no-space">
					<div class="col-md-12 slider no-space">
						<div class="slider-container">
						<?php
							echo implode("\n",array_map(function($img){
								return '<img src="'.$img.'" />';
							},$post->gallery()));
						?>
						
						</div>
						<p style="color: #333;text-align: center;"><img src="<?php bloginfo('template_directory');?>/images/eye.png" /> SERVICING AVAILABLE</p>
					</div>
					<div class="col-md-10" style=" text-align: right;margin: 50px -12px;">
						<a href="#"><img src="<?php bloginfo('template_directory');?>/images/read-brochure_1.png" style="position: relative;right:-68px;" /></a>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>