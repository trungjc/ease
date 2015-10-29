<?php if(isset($WP_enable) && $WP_enable) get_header();
$me = $post->getMe();

?>
<div id="content">
          <div class="row-container">
            <div class="container-fluid">
              <div class="content-inner row-fluid">   
                        
                <div id="component" class="span12">
					<main role="main">
                          <?php echo $me->post_content; ?>
                    
					</main>
                </div>        
                              </div>
            </div>
          </div>
        </div>
<div id="push"></div>		
<?php
 if(isset($WP_enable) && $WP_enable) get_footer(); 
 ?>