<!-- header wrapper end -->
<?php

?>
        <div class="breadcrumb-wrapper row">

            <div class="container">
                <!-- breadcrumbs start -->
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="breadcrumb">
							<?php 
								$breadCrumbs = $search->breadCrumbs(lang("home"),site_url(),"&raquo; ",true);
									foreach($breadCrumbs as $i=> $v){
									 echo '<li><a href="'.$v[1].'">'.$v[0].'</a></li>';
									}
							?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumbs end -->
<?php
		$data = $search->search_post_type($search->query,10,URI::getInstance()->paged);
		
?>
<div class="content-wrapper container">
	<div id="bodyContent" class="row">
	<h1 style="color:#EC1C24;"><?php echo $search->name; ?></h1>
	<div class="woocommerce columns-4">
		
		<?php
		if($data==null)
		{
		?>
		<h4 style="font-weight: bold;">Không tìm thấy dữ liệu bạn đang cần: '<?php echo $search->name; ?>'</h4>
		<?php 
		}else{
		
		foreach($data->data as $k=>$item){
			if($k==0){
				echo '<ul class="products" style="padding:0;">';
			}
			if($k!=0 && $k%10==0){
				echo '</ul>';
				echo '<ul class="products">';
			}
			$url = site_url($item->post_name);
			$cart_url =site_url()."?add-to-cart=".$item->ID; 
			$lang_cart= lang('Add to cart');
echo <<<AHLU
			<li style="margin:0 2.8% 2.992em 0;" class="post-598 product type-product status-publish has-post-thumbnail product_cat-hoa-dai-sanh featured shipping-taxable purchasable product-type-simple product-cat-hoa-dai-sanh instock">

				
				<a href="{$url}">

					<img width="300" height="300" src="{$item->thumbnail}" class="attachment-shop_catalog wp-post-image" alt="{$item->post_title}" />			
					<h3>{$item->post_title}</h3>
				</a>
						<div class="short_desc"></div>
					
					<a href="{$cart_url}" rel="nofollow" data-product_id="{$item->ID}" data-product_sku="{$item->sku}" data-quantity="1" class="button add_to_cart_button product_type_simple">{$lang_cart}</a>		
					
					

				<span class="price">Giá <span class="amount">{$item->product->get_price_html()}</span></span>

				

			</li>
AHLU;
			}
			if(count($data->data)%10 !=0){
				echo '</ul>';
			}
			
			if(!empty ($data->link)){
			echo $data->link;
			}
		}
			?>
			</div>	
		</div>
	</div>
</div>