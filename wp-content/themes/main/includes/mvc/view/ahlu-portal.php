
<div class="breadcrumb-wrapper row">

            <div class="container">
                <!-- breadcrumbs start -->
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="breadcrumb">
							<?php
								$breadCrumbs = $category->breadCrumbs(lang("home"),"/","&raquo; ",array(),true);
							
								if(is_array($breadCrumbs) && count($breadCrumbs)==1){
									$breadCrumbs[] = array("Sự Kiện","#");
								}
									foreach($breadCrumbs as $i=> $v){
									 echo '<li><a href="'.$v[1].'">'.$v[0].'</a></li>';
									}
								
							?>
                        </ul>
                    </div>
                </div>
            </div>
</div>
<div class="content-wrapper container">
    <div id="bodyContent" class="row">
		
		<?php
			echo '<div class="list-post">';
			$data = $category->listPostType(10,URI::getInstance()->paged);
			if(is_array($data->data)){
				
				foreach($data->data as $item){
				$url = site_url($item->post_name);
echo <<<AHLU
	<div class="item">
				<h3><a href="{$url}" target="_blank">{$item->post_title}</a></h3>
				<p>
					<a href="{$url}" target="_blank"> <img alt="{$item->post_title}" title="{$item->post_title}" src="{$item->thumbnail}" /></a>
					<span>{$item->post_excerpt}</span>
				</p>
				<a class="more" target="_blank" href="{$url}">Xem chi tiết</a>
	</div>
AHLU;
				}
				echo $data->link;
			}
			
			echo '</div>';
		?>
			
		
    </div>
</div>	