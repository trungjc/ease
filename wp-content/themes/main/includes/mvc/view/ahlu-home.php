<div class="wrapper slideshow-container">
	<link rel="stylesheet" href="<?php echo plugins_url(); ?>/revslider/rs-plugin/css/settings.css">
					<style>
						.tp-caption.big_white{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 500; 
									font-size: 36px; 
									line-height: 36px; 
									font-family: Conv_AGaramondPro-Bold; 
									padding: 0px 4px; 
									padding-top: 2px;
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									letter-spacing: -0.5px;										
								}

						.tp-caption.big_orange{
									position: absolute; 
									color: #ff7302; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 36px; 
									line-height: 36px; 
									font-family: Arial; 
									padding: 0px 4px; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									background-color:#fff;	
									letter-spacing: -1.5px;															
								}	
											
						.tp-caption.big_black{
									position: absolute; 
									color: #000; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 36px; 
									line-height: 36px; 
									font-family: Arial; 
									padding: 0px 4px; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									background-color:#fff;	
									letter-spacing: -1.5px;															
								}		

						.tp-caption.medium_grey{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 20px; 
									line-height: 20px; 
									font-family: Arial; 
									padding: 2px 4px; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									background-color:#888;		
									white-space:nowrap;	
									text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);		
								}	
											
						.tp-caption.small_text{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 14px; 
									line-height: 20px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);		
								}
											
						.tp-caption .medium_text{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 20px; 
									line-height: 20px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);		
								}
											
						.tp-caption .large_text{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 40px; 
									line-height: 40px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);		
								}	
											
						.tp-caption.very_large_text{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 60px; 
									line-height: 60px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);
									letter-spacing: -2px;		
								}
											
						.tp-caption.very_big_white{
									position: absolute; 
									color: #fff; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 60px; 
									line-height: 60px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									padding: 0px 4px; 
									padding-top: 1px;
									background-color:#000;		
											}	
											
						.tp-caption.very_big_black{
									position: absolute; 
									color: #000; 
									text-shadow: none; 
									font-weight: 700; 
									font-size: 60px; 
									line-height: 60px; 
									font-family: Arial; 
									margin: 0px; 
									border-width: 0px; 
									border-style: none; 
									white-space:nowrap;	
									padding: 0px 4px; 
									padding-top: 1px;
									background-color:#fff;		
											}
											
						.tp-caption.boxshadow{
								-moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
								-webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
								box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
							}
																	
						.tp-caption.black{
								color: #000; 
								text-shadow: none;		
							}	
											
						.tp-caption.noshadow {
								text-shadow: none;		
							}	
											
						.tp-caption a { 
							color: #ff7302; text-shadow: none;	-webkit-transition: all 0.2s ease-out; -moz-transition: all 0.2s ease-out; -o-transition: all 0.2s ease-out; -ms-transition: all 0.2s ease-out;	 
						}			
							
						.tp-caption a:hover { 
							color: #ffa902; 
						}
						
						.tp-rightarrow {
							z-index: 100;
							cursor: pointer;
							position: relative;
							background: url(<?php bloginfo('template_directory');?>/images/arraw_right_gray.png) no-Repeat 0 0!important;
							width: 40px;
							height: 40px;
						}
						.tp-rightarrow:hover{
							background: url(<?php bloginfo('template_directory');?>/images/arrow_right.png) no-repeat 0 0!important;
							    right: 19px !important;
								top: 328px !important;
						}
						
						.tp-leftarrow{
							z-index: 100;
							cursor: pointer;
							position: relative;
							background: url(<?php bloginfo('template_directory');?>/images/arraw_left_gray.png) no-Repeat 0 0!important;
							width: 40px;
							height: 40px;
						}
						.tp-leftarrow:hover{
							background: url(<?php bloginfo('template_directory');?>/images/arraw_left.png) no-repeat 0 0!important;
							    left: 21px !important;
								top: 328px !important;
								
						}
						
						.tp-bullets .bullet {
							background: url(<?php bloginfo('template_directory');?>/images/bullet.png) no-Repeat -27px -3px !important;
							width: 22px !important;
							height: 22px !important;
						}
						.tp-bullets .bullet.selected {
							background: url(<?php bloginfo('template_directory');?>/images/bullet.png) no-Repeat -5px -2px  !important;
							width: 22px !important;
							height: 22px !important;
						}
					</style>
					
					<?php echo do_shortcode('[rev_slider slideshow]') ?>
				</div>
				<div class="wrapper slider-container">
						<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory');?>/js/jquery.carouFredSel-6.2.1.js"></script>
						<!-- optionally include helper plugins -->
						<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory');?>/js/helper-plugins/jquery.mousewheel.min.js"></script>
						<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory');?>/js/helper-plugins/jquery.touchSwipe.min.js"></script>
						<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory');?>/js/helper-plugins/jquery.transit.min.js"></script>
						<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory');?>/js/helper-plugins/jquery.ba-throttle-debounce.min.js"></script>
						<script type="text/javascript" language="javascript">
							jQuery(document).ready(function() {
									$.fn.goTo = function() { $('html, body').animate({ scrollTop:($(this).offset().top-77) + 'px' }, 'fast'); return this; };
									$(".go_to_1").live("click",function(e){ 
										$('.go_to_2').closest(".panel-2").goTo(); $(this).die(e.type); 
									});
									$(".go_to_2").live("click",function(e){ 
										$('.go_to_3').closest(".panel-1").goTo(); $(this).die(e.type); 
									});
									//Responsive layout, resizing the items
									jQuery('.nav_slider').carouFredSel({
										responsive: true,
										width: '100%',
										auto: false,
										prev: '.prev',
										next: '.next',
										items: {
											width: '200px',
											height: '150px',	//	optionally resize item-height
											visible: {
												min: 1,
												max: 6
											}
										}
									}).find("li").prepend("<div class='ahlu-body-layer'></div>").prepend("<div class='line'></div>");
									
							});
						</script>
						<div class="list_carousel">
					<?php
						
						function md_nmi_custom_content( $content, $item_id, $original_content ) {
							//get content get_post_field( 'post_content', $item_id )
						    $content = '<span class="page-title">' . $original_content . '</span> <br />'.$content;
						    return $content;
						}
						add_filter( 'nmi_menu_item_content', 'md_nmi_custom_content', 10, 3 );
							$defaults = array(

								'menu'            => 'nav_slider',

								//s'container'       => 'div',

								'menu_class'      => 'nav_slider',

								'echo'            => true,

								'fallback_cb'     => 'wp_page_menu',

								'items_wrap'      => '<ul class="%2$s">%3$s</ul>',

							);

							wp_nav_menu( $defaults );
					?>
						<a style="position:absolute;top:37%;" class="prev" style="float:left;" href="#">&nbsp;</a>
						<a style="position:absolute;top:37%;right: -20px;" class="next" style="float:right;" href="#">&nbsp;</a>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<div class="wrapper ahlu-box panel-1" style="background:url(<?php bloginfo('template_directory');?>/images/bg_pic1.jpg) no-repeat center  center;background-attachment: fixed; background-size: cover;">
					<div class="ahlu-body">
						<div class="ahlu-body-layer"></div>
						<div class="panel-box col-md-6">
							<p style="font-size:26px;">
							<span style="font-size:30px;font-weight: bold;">EASE</span> stock a large range of air starter motors,underground
							mining tools and equipment,<span style="font-weight: bold;">air operated diaphragm pumps,</span> 
							hand held air tools, compressors and pneumatic accessories. 
							</p>
							
							<p style="font-size:20px;">
								<a href="#" class="btn-circle btn-circle-orange">+</a>
							</p>
							<p style="font-size:20px;">
							<span style="font-size:20px;font-weight: bold;">EASE</span> can provide sales and speciality services of all air operated equipment from a basic hand held air toll through to roof bolters and rib drills  - including operating Queensland's only <span style="font-size:20px;font-weight: bold;">Rambor Test Bench</span> offering customers a specialised form of roof bolter testing 
- including <span style="font-size:20px;font-weight: bold;">motor rotation and leg force testing</span> of all underground mining roof bolters! 
							</p>
							
						</div>
						<a href="#" class="go_to_3" style="float: right;position: relative;top: -50px;left: -50px;"><img src="<?php bloginfo('template_directory');?>/images/icons_04_02.png" /></a>
					</div>
				</div>
				<div class="wrapper ahlu-box panel-2" style="background:url(<?php bloginfo('template_directory');?>/images/bg_pic2.jpg) no-repeat center  center;background-attachment: fixed; background-size: cover;">
					<div class="ahlu-body">
						<div class="ahlu-body-layer"></div>
						<div class="panel-box col-md-5">
							<p style="font-size:28px;">
							<span style="font-size:30px;font-weight: bold;">EASE</span> operate <span style="font-size:30px;font-weight: bold;">QLD’s</span> only <br />
							<span style="font-size:30px;font-weight: bold;">RAMBOR / PPK Firefly Mining Equipment</span><br />
							<span style="font-size:30px;font-weight: bold;">Roofbolter Test Bench </span>
							</p>
							<p style="font-size:20px;">
								Offering customers a specialised and <span style="font-weight: bold;">reliable method</span><br />
								of roof bolter testing including motor rotation and<br />
								<span style="font-weight: bold;">complete leg for testing.</span>
							</p>
							<p >
								<a href="#" class="btn-circle" style="background-color: #ccc;color:#333;"><i class="fa fa-play"></i></a>
							</p>
						</div>
						<a href="#" class="go_to_2" style="float: right;position: relative;top: -50px;left: -50px;"><img src="<?php bloginfo('template_directory');?>/images/icons_04_02.png" /></a>
					
					</div>
				</div>
				<div class="wrapper ahlu-box panel-3" style="background:url(<?php bloginfo('template_directory');?>/images/bg_pic3.jpg) no-repeat center  center;background-attachment: fixed; background-size: cover;">
					<div class="ahlu-body">
						<div class="ahlu-body-layer"></div>
						<div class="panel-box col-md-10">
							<p style="font-size:26px;text-align: right;    line-height: 35px;">
							<span style="font-size:28px;font-weight: bold;">EASE</span> is a member of the I.S.G. - Industrial <br />
							Supply Group. The I.S.G. is a coalition <br />
of major independent industrial <br />
supply companies servicing <br />
customers Australia wide, <br />
with the proven capacity <br />
to service <span style="font-weight: bold;">national</span><br />
<span style="font-weight: bold;">accounts.</span><br />
							</p>
							<p style="padding-top: 20px;font-size:28px;text-align: right;    margin-bottom: 140px;">
								<span style="font-size:30px;font-weight: bold;">Read</span> our <span style="font-size:30px;font-weight: bold;">Capability Statement</span> <a href="#" class="btn-circle btn-circle-no-border" style="margin-left: 40px;background-color:orange;">+</a>
							</p>
						</div>
						<a href="#" class="go_to_1" style="float: right;position: relative;top: -50px;left: -50px;"><img src="<?php bloginfo('template_directory');?>/images/icons_04_02.png" /></a>
					
					</div>
				</div>
				<div class="wrapper ahlu-box panel-4" style="background:#f7f7f7">
					<div class="ahlu-body">
						<div class="panel-box col-md-10">
							<p style="font-size:20px;color:#005596;    text-align: left;" class="col-md-10">
									<img src="<?php bloginfo('template_directory');?>/images/watch.png" />
									<br />
									<br />
									Emerald Air Starters & Equipment, or EASE as our customers know us are the sole authorised Emerald distributor for quality Atlas-Copco Compressors and Tools, including the new Oil-injected Rotary Screw Compressor range.
							</p>
							<p class="col-md-2">
								<img style="position: absolute;top: -150px;left: 0;" src="<?php bloginfo('template_directory');?>/images/pic1.png" />
							</p>
						</div>
					</div>
				</div>
				<div class="wrapper ahlu-box panel-5" style="background:#000">
					<div class="ahlu-body">
						<div class="panel-box col-md-12" style="padding: 80px 0;">
							<link href="<?php bloginfo('template_directory');?>/css/default_skin_variation.css" type="text/css" rel="stylesheet" />
							<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/jquery.sky.carousel-1.0.2.min.js"></script>
							<script type="text/javascript">
								jQuery(function() {
									jQuery('.sky-carousel').carousel({
										itemWidth: 200,
										itemHeight: 240,
										distance: 15,
										selectedItemDistance: 50,
										selectedItemZoomFactor: 1,
										unselectedItemZoomFactor: 0.67,
										unselectedItemAlpha: 0.6,
										motionStartDistance: 170,
										topMargin: 119,
										gradientStartPoint: 0.35,
										gradientOverlayColor: "#000",
										gradientOverlaySize: 190,
										reflectionDistance: 1,
										reflectionAlpha: 0.35,
										reflectionVisible: true,
										reflectionSize: 70,
										selectByClick: true
									});
								});
							</script>
				<div class="sky-carousel">
					<div class="sky-carousel-wrapper">
						<ul class="sky-carousel-container">
							<li>
								<img src="<?php bloginfo('template_directory');?>/images/pdf/pdf1.png" alt="" />
								<a href="http://docs.google.com/gview?url=http://www.objectmentor.com/resources/articles/Principles_and_Patterns.pdf&embedded=true" target="_blank" class="btn-reader btn btn-success" style="position: absolute;top: 35%;left: 15px;background:#0cd411;border: none;padding: 7px 30px;    font-size: 18px;position:absolute;">Read .Pdf</a>
								<div class="sc-content">
									<h2>Halloween Party Poster</h2>
									<p>Poster showing a green witch.</p>
								</div>
							</li>
							<li>
								<img src="<?php bloginfo('template_directory');?>/images/pdf/pdf2.png" alt="" />
								<a href="http://docs.google.com/gview?url=http://www.objectmentor.com/resources/articles/Principles_and_Patterns.pdf&embedded=true" target="_blank" class="btn-reader btn btn-success" style="position: absolute;top: 35%;left: 15px;background:#0cd411;border: none;padding: 7px 30px;    font-size: 18px;position:absolute;">Read .Pdf</a>
								
								<div class="sc-content">
									<h2>Bloody Skull</h2>
									<p>Party poster with a bloody skull.</p>
								</div>
							</li>
							<li>
								<img src="<?php bloginfo('template_directory');?>/images/pdf/pdf3.png" alt="" />
								<a href="http://docs.google.com/gview?url=http://www.objectmentor.com/resources/articles/Principles_and_Patterns.pdf&embedded=true" target="_blank" class="btn-reader btn btn-success" style="position: absolute;top: 35%;left: 15px;background:#0cd411;border: none;padding: 7px 30px;    font-size: 18px;position:absolute;">Read .Pdf</a>
								
								<div class="sc-content">
									<h2>Halloween Party Poster</h2>
									<p>Poster showing a Halloween pumpkin.</p>
								</div>
							</li>
							<li>
								<img src="<?php bloginfo('template_directory');?>/images/pdf/pdf4.png" alt="" />
								<a href="http://docs.google.com/gview?url=http://www.objectmentor.com/resources/articles/Principles_and_Patterns.pdf&embedded=true" target="_blank" class="btn-reader btn btn-success" style="position: absolute;top: 35%;left: 15px;background:#0cd411;border: none;padding: 7px 30px;    font-size: 18px;position:absolute;">Read .Pdf</a>
								
								<div class="sc-content">
									<h2>Grunge Skull</h2>
									<p>Party poster with a grunge skull.</p>
								</div>
							</li>
							<li>
								<img src="<?php bloginfo('template_directory');?>/images/pdf/pdf5.png" alt="" />
								<a href="http://docs.google.com/gview?url=http://www.objectmentor.com/resources/articles/Principles_and_Patterns.pdf&embedded=true" target="_blank" class="btn-reader btn btn-success" style="position: absolute;top: 35%;left: 15px;background:#0cd411;border: none;padding: 7px 30px;    font-size: 18px;position:absolute;">Read .Pdf</a>
								
								<div class="sc-content">
									<h2>Halloween Party Poster</h2>
									<p>Poster showing a werewolf.</p>
								</div>
							</li>
							
						</ul>
					</div>
				</div>
						</div>
					</div>
				</div>
				<div class="wrapper ahlu-box panel-6" style="background:url(<?php bloginfo('template_directory');?>/images/bg_pic3.jpg) no-repeat top center;">
					<div class="ahlu-body">
						<div class="ahlu-body-layer"></div>
						<div class="panel-box col-md-3">
							<p style="font-size:20px;text-align: center;">
								We believe we can ensure <br />
quality results through the quality of <br />
our people & products...<br />
							</p>
							<p style="font-size:20px;">
								<a href="#" class="btn btn-success" style="background:#faa736;border:none;border: none;
    padding: 15px 30px;    font-size: 18px;">GET A <span style="color:#fff;font-weight: bold;">FREE</span> QUOTE</a>
							</p>
						</div>
					</div>
				</div>