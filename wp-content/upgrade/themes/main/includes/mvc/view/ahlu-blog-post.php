<?php
	$me = $post->getMe();
?>
<div class="wrapper ahlu-box panel-1 ">
		
		<div class="wrapper ahlu-box panel-6">
				<div class="ahlu-body" style="margin:0;">
					<div class="ahlu-body-layer"></div>
					<img src="<?php bloginfo('template_directory');?>/images/blog-bg.jpg" style="max-width: 100%;min-width: 100%;" />
					<div class="panel-box col-md-9" style="    display: none;">
						<p style="text-align: center;">
							<img src="<?php echo the_author_meta( 'avatar' , $me->post_author ); ?> " width="140" height="140" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id ); ?>" /><br /><br />
							<span style="font-size: 1.5em;">By <?php echo the_author_meta( 'user_nicename' , $me->post_author ); ?> </span> <br /><br />	<br />
								<br />
							<span style="font-size: 4em;font-family: 'museo_sans500';line-height: 45px;"><?php echo $me->post_excerpt;?></span><br /><br /><br />
								<?php $t = strtotime($me->post_date); ?>
								<br />
								<br />
							
							<span class="date"><i class="fa fa-calendar"></i> on <?php echo date("F",$t)?> <?php echo date("d",$t)?>, <?php echo date("Y",$t)?></span>
						</p>

						<p style="font-size:20px;">
							<a href="#" class="btn btn-info">Read Blog</a>
						</p>
					</div>
				</div>
			</div>
		
		
		
		<div class="wrapper ahlu-box panel-2">
				<div class="ahlu-body">
					<div class="ahlu-body-layer" style="background-color:white"></div>
					<div class="panel-box col-md-10">
							<div class="col-md-12 post-contain">
								<div class="col-md-8 ">
									<h1><?php echo $me->post_title; ?></h1>
									
									<div class="content">
										<?php echo $me->post_content; ?>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="col-md-12 no-space news-list">
										<h2 class="title">ALL TIME <span>HITS</span></h2>
										<ul>	
											<li><a href="#">Lorem ipsum dolor sit amet conse ctetur adipisicing elit</a></li>
											<li><a href="#">Lorem ipsum dolor sit amet conse ctetur adipisicing elit</a></li>
											<li><a href="#">Lorem ipsum dolor sit amet conse ctetur adipisicing elit</a></li>
											<li><a href="#">Lorem ipsum dolor sit amet conse ctetur adipisicing elit</a></li>
											<li><a href="#">Lorem ipsum dolor sit amet conse ctetur adipisicing elit</a></li>
										</ul>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		
	
		
		</div>