<?php

 get_header(); ?> 
<section id="content">
<div class="container_12">
<article class="grid_8 indent-top">
<div class="bg-1 rel margin-bot3">
<?php

?>
  <div class="indent4 bg-5">
    <div class="wrapper">
      <h4 class=" color7 fleft">SOLVING PUZZLES</h4>
    </div>
  </div>
  <div class="indent5">
  <?php
      
  $temp = listAllCategory("game_genre"); 
  //var_dump($temp);
  foreach($temp as $k=> $obj) {?>
    <div class="wrapper border-2"> 
<figure class="img_border img img-indent1">
    <a></a>
</figure>
<div class="extra-wrap">
<a href="<?php echo $obj->slug; ?>" class="link-2"> <?php echo $obj->name; ?>  </a>
<div><?php echo $obj->description; ?></div>
</div>
</div>
 <?php
}
?>

 <div id="paginations" style='width:270px;;' >
 
  </div>

</article>
<?php include(TEMPLATEPATH.'/rightcontent.php'); ?>
 <?php get_footer(); ?>  
</div>
</center>
</body>
</html>
