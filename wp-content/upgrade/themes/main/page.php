<?php 
global $post;

$postData = new Post_model();
$postData->load($post->ID);

$template = Ahlu::Library("Template");
$template->assign("cls","{$post->post_name} page-{$post->ID}");
$template->assign("header",$template->load->view( $template->getPath()."/compoment/header.php",null,true));

//$template->assign("content",$content);
$template->assign("content",$template->load->view("ahlu-page-post",array("post"=>$postData),true));

$template->assign("footer",$template->load->view( $template->getPath()."/compoment/footer.php",null,true));

//output html
$template->render(FALSE);
		   


?>
