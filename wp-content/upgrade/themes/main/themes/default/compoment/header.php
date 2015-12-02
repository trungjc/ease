<div class="wrapper navigation">
					<div class="left no-space col-md-4">
						<div class="logo left">
							<a href="<?php echo site_url(); ?>"><span>EASE</span></a>
						</div>
						<div class="slogan left">Emerald Air Starters & Equipment</div>
					</div>
					<div class="menu-container right">
						<div class="menu-vertical left">
							<ul>
								<li><a href="" target="_self">about us</a></li>
								<li><a href="<?php echo site_url("contact-us"); ?>" target="_self">contact us</a></li>
							</ul>
						</div>
						<div class="menu-horizontal left">
							<div class="menu-horizontal-container">
								<a href="#" class="open-menu"><img src="<?php bloginfo('template_directory');?>/images/3_rows.png" /></a>
								<div class="group">
									<input type="text" class="search" placeholder="Search here" />
									<div class="menu">
										<script>
											( function( $ ) {
											$( document ).ready(function() {
												$('.menu > ul > li > a').click(function() {
												  $('.menu li').removeClass('active');
												  $(this).closest('li').addClass('active');
												  
												  $('.menu > ul ul').slideUp('normal');
												  
												  var checkElement = $(this).next();
												  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
													$(this).closest('li').removeClass('active');
													checkElement.slideUp('normal');
												  }
												  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
													$('.menu ul ul:visible').slideUp('normal');
													checkElement.slideDown('normal');
												  }
												  if($(this).closest('li').find('ul').children().length == 0) {
													return true;
												  } else {
													
													return false;	
												  }		
												});
												});
												
												
											} )( jQuery );
										</script>
										<ul>
										   <li class='has-sub equipment'><a href='#'>Equipment</a>
												<ul>
													<li><a href='<?php echo site_url("mpact-tools"); ?>'>ATEX Approved Tools</a></li>
													<li><a href='<?php echo site_url("rambor-roofbolters"); ?>'>RAMBOR Roofbolters</a></li>
													<li><a href='<?php echo site_url("kh-equipment"); ?>'>KH Equipment</a></li>
													<li><a href='<?php echo site_url("grounting"); ?>'>Grouting</a></li>
													<li><a href='<?php echo site_url("#"); ?>'>Drilling,cutting</a></li>
													<li><span class="close1">&nbsp;</span><a href='<?php echo site_url("atlas-copco-mine-spec"); ?>'>Atlas Copco</a></li>
												</ul>
										   </li>
										   <li class='tools'><a href='#'>Tools</a></li>
										   <li class='con'><a href='#'>CONSUMABLES</a></li>
										   <li class='com'><a href='#'>COMPRESSORS</a></li>
										   <li class='welding'><a href='#'>WELDING</a></li>
										</ul>
										
									</div>
								</div>
							</div>
						</div>
						<div class="right cart-holder">
								<a href="<?php echo site_url("checkout"); ?>"><img src="<?php bloginfo('template_directory');?>/images/holder.png" />
								<span>0</span>
								</a>
						</div>
					</div>
				</div>
			