<!-- header wrapper end -->
<?php
$me = $post->getMe();


?>
        <div class="breadcrumb-wrapper row">

            <div class="container">
                <!-- breadcrumbs start -->
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="breadcrumb">
							<?php 
								$breadCrumbs = $post->breadCrumbs(lang("home"),"/","&raquo; ",array(),true,array(array("Sự Kiện",site_url("su-kien"))));
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

<div class="content-wrapper container">
	<div id="bodyContent" class="row">
	<h1 style="color:#EC1C24;"><?php echo $me->post_title;?></h1>
	<?php echo $me->post_content;?>
		
	</div>
</div>