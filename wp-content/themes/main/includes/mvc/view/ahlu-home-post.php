<?php if(isset($WP_enable) && $WP_enable) get_header();

global $lang;
$upload_dir = wp_upload_dir();

echo $post->post_content;

 if(isset($WP_enable) && $WP_enable) get_footer(); 
 ?>