
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <title>EASE</title>
    
    <link href="<?php bloginfo('template_directory');?>/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php bloginfo('template_directory');?>/css/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/jquery-1.8.3.js"></script>
	 <script src="<?php bloginfo('template_directory');?>/js/jquery-2.0.3.min.js" type="text/javascript"></script>
	 <script src="<?php bloginfo('template_directory');?>/js/jquery-migrate.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/js/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/js/jquery.metadata.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_directory');?>/js/bootstrap-table.min.js" type="text/javascript"></script>
	
	<link rel="stylesheet" href="<?php bloginfo('template_directory');?>/css/back-to-top.css">
    <script src="<?php bloginfo('template_directory');?>/js/back-to-top.js" type="text/javascript"></script>

	<link rel="stylesheet" href="https://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css">

	
	<link rel="stylesheet" href="<?php bloginfo('template_directory');?>/ahlu.css">
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".open-menu").click(function(e){
				var $this = jQuery(this);
				var group = jQuery(".group");
				
				if($this.hasClass("opened")){
					group.slideUp(300,function(){
						$this.removeClass("opened");
						$this.closest(".menu-horizontal-container").removeClass("menu-horizontal-open").removeClass("menu-horizontal-opened");
						jQuery(".menu-horizontal").removeClass("menu-horizontal-opened");
					});
				}else{
					group.slideDown();
					$this.addClass("opened");
					$this.closest(".menu-horizontal-container").addClass("menu-horizontal-open").addClass("menu-horizontal-opened");
					jQuery(".menu-horizontal").addClass("menu-horizontal-opened");
				}
				
			});
		});
	</script>
</head>	
<body class="<?php echo isset($cls)?$cls:""; ?>">
<a href="#" id="back-to-top" title="Back to top"><img src="<?php bloginfo('template_directory');?>/images/icons_03.png" /><br />Back to top</a>
	<div class="wrapper">
		<div class="main_header">
			<div class="container-inner center-no-center">
				<?php
					echo $header;
				?>
			</div>
		</div>
		<div class="main_content">
			<div class="container-inner center-no-center">
				<?php
					echo $content;
				?>
			</div>
		</div>
		<div class="main_footer">
			<div class="wrapper container-inner center-no-center">
				<?php
					echo $footer;
				?>
			</div>
		</div>
	</div>
</body>
</html>