
<?php
		$list_php=array();
		$slideshow = new Media_model(); 
		$data = $slideshow->byNamePost('gallery');
		if(is_array($data)){
		unlinkRecursive($root);								
		foreach($data as $i=> $thumb){											  
		$file = pathinfo(str_replace(site_url(),"",$thumb->guid));
		$list_php[]= $file['basename'].";";
		$path = ABSPATH.$file["dirname"];
			

$root = get_template_directory().'/images/gallery';



//full
copy($path."/".$file['filename'].'.'.$file['extension'],$root."/full/".$file['filename'].'.'.$file['extension']);
copy($path."/".$file['filename'].'.'.$file['extension'],$root."/".$file['filename'].'.'.$file['extension']);
//copy($path."/".$file['filename'].'-670x370.'.$file['extension'],$root."/".$file['filename'].'.'.$file['extension']); 
}
}

$file_list_php = get_template_directory().'/images/file_list.php';

//$current =implode(";",$list_php);
$current =implode($list_php);

file_put_contents($file_list_php, $current);

function unlinkRecursive($dir, $deleteRootToo=false) 
{ 
    if(!$dh = @opendir($dir)) 
    { 
        return; 
    } 
    while (false !== ($obj = readdir($dh))) 
    { 
        if($obj == '.' || $obj == '..') 
        { 
            continue; 
        } 

        if (!@unlink($dir . '/' . $obj)) 
        { 
            unlinkRecursive($dir.'/'.$obj, $deleteRootToo); 
        } 
    } 

    closedir($dh); 
    
    if ($deleteRootToo) 
    { 
        @rmdir($dir); 
    } 
    
    return; 
} 

?>




<!--========================================================
                  CONTENT
=========================================================-->
<div class="galleryContainer">
    <!-- spinner -->
    <div class="imgSpinner"></div>
    <!-- close button -->
    <a href="" class="close-icon"><i class="fa fa-times"></i></a>

    <!-- Navigation -->
    <!-- previous button -->
    <a href="#" class="prevButton">
        <i class="fa fa-angle-left"></i>
    </a>
    <!-- next button -->
    <a href="#" class="nextButton">
        <i class="fa fa-angle-right"></i>
    </a>

    <!-- main gallery and image holder -->
    <div class="galleryHolder">
        <div class="imageHolder">
            <img src="<?php bloginfo('template_directory'); ?>/images/topmenu.jpg" alt>
        </div>
    </div>

    <div class="carousel-holder">
        <div class="carousel inner">
            <!-- holder for pagination -->
        </div>
        <a href="#" class="prev"><i class="fa fa-angle-left"></i></a>
        <a href="#" class="next"><i class="fa fa-angle-right"></i></a>
    </div>

</div>


<?php 

global $lang;
$upload_dir = wp_upload_dir();

$page = get_page_by_title( 'gallery' );

echo $page->post_content;
 ?>



<script type="text/javascript">
    $(function(){
        pathThumb = '<?php echo site_url(); ?>/wp-content/themes/dartist/images/gallery/';
        pathFull = '<?php echo site_url(); ?>/wp-content/themes/dartist/images/gallery/full/';
    })
</script>

