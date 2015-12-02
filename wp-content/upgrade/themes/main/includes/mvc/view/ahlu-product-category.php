<?php 

	$cate = $category->getMe();
	
	
?> 
<div class="wrapper product-bg">
	
	<div class="ahlu-box panel-1 col-md-10 center-no-center">
		<div class="ahlu-body">
			<a href="<?php echo site_url("product"); ?>" class="return-back" title="return back" alt="return back" ><img src="<?php bloginfo('template_directory');?>/images/go_back.png" title="return back" alt="return back" /></a>
			<div class="col-md-12 no-space cate-title">
				<div class="col-md-5 no-space"><span class="head-title"><?php echo $cate->name; ?></span></div>
				<div class="col-md-7 no-space">
					<span>
						<a href="#"><img src="<?php bloginfo('template_directory');?>/images/brand2.png" /></a>
						<a href="#"><img src="<?php bloginfo('template_directory');?>/images/brand1.png" /></a>
						<a href="#"><img src="<?php bloginfo('template_directory');?>/images/brand3.png" /></a>
					</span>
					<span>
						<select>
							<option value="-1">Other Filter</option>
						</select>
						<input type="number" value="" placeholder="Size" style="width: 70px;padding-left: 5px;" />
					</span>
				</div>
			</div>
			<script>
				$(document).ready(function(){
					$(".product-items .item .title").on({
						mouseenter: function(e) { 
							$(this).closest(".wrapper").addClass("wrapper-hover"); 
						}
					});
					$(".product-items .item").on({
						mouseleave: function(e) { 
							$(this).find(".wrapper").removeClass("wrapper-hover"); 
						}
					});
				});
			</script>
			<div class="col-md-12 no-space product-items">
				
				<?php
					$data = $category->toPostAndCate();

					if(is_object($data)){
					
						foreach($data->data as $item){
						$url= site_url($item->post_name);
echo <<<AHLU
						<div class="col-md-3 no-space item">
							
								<div class="wrapper">
										<a href="?add_to_cart" class="add_to_cart">&nbsp;</a>
										<a href="{$url}"><img src="{$item->thumbnail}" />
										<p class="title">{$item->post_title}</p></a>
										<div class="ahlu-body-layer">
											<a href="{$url}"><h3>{$item->post_title}</h3>
											<p>{$item->post_excerpt}</p></a>
										</div>
									
								</div>
							
						</div>
AHLU;
						}	
					}
				?>
			</div>
		</div>
	</div>
</div>
