<div class="banner">
   <a class="top_banner" href="/blog/lam-the-nao-de-cv-xin-viec-lot-vao-mat-xanh-cua-nha-tuyen-dung-blog34" title="Làm thế nào để CV xin việc lọt vào mắt xanh của nhà tuyển dụng"><img src="<?=$path_themes . 'image/7_bi_quyet_viet_cv.jpg'?>" alt="7 bí quyết viết cv hấp dẫn" /></a>
</div>
<?
$db_blog = new db_query("SELECT * FROM blog
                         WHERE blo_active = 1 ORDER BY blo_id DESC LIMIT 25");
$i = 1;
while($row  = mysqli_fetch_assoc($db_blog->result)){
   $class   = 'item';
   
   if($i == 1) $class = ' item_first';
   $title   = $row['blo_title'];
   $teaser  = $row['blo_teaser'];
   $link    = createLinkBlog($row);
   
   $cat_info   = isset($arrayCategory[$row['blo_cat_id']])? $arrayCategory[$row['blo_cat_id']] : array();
   $linkCat = createLinkBlogCategory($cat_info);
   
   $cat_name   = isset($cat_info['bc_name'])? $cat_info['bc_name'] : '';
   
   
   if($i == 1){
      ?>
      <div class="<?=$class?>">
         <p class="bname"><a href="<?=$link?>" title="<?=$title?>"><?=$title?></a></p>
         <div class="img">
            <a class="show" href="<?=$link?>"><img src="/upload/blog/small_<?=$row['blo_image']?>" alt="<?=$title?>" /></a>
         </div>
         <div class="teaser">
            <?=$row['blo_teaser']?>
            <p class="incat">In: <a href="<?=$linkCat?>" title="<?=$cat_name?>"><?=$cat_name?></a></p>
         </div>
      </div>
      <div>
         <?=ads_show(8)?>
      </div>
      <?
   }else{
      
      if($i == 8){
         include ('inc_vieclammoi.php');
      }
      
      if($i == 2) echo '<div class="home_list">';
      $class_center  = '';
      if($i % 3 == 0) $class_center = 'item_center';
      ?>
      <div class="<?=$class . ' ' . $class_center?>">
         <p class="bname"><a href="<?=$link?>" title="<?=$title?>"><?=$title?></a></p>
         <div class="img">
            <a href="<?=$link?>"><img src="/upload/blog/small_<?=$row['blo_image']?>" /></a>
         </div>
         <p class="incat">In: <a href="<?=$linkCat?>" title="<?=$cat_name?>"><?=$cat_name?></a></p>
      </div>
      <?
   }
   $i++;
}
echo '</div>';
unset($db_blog);