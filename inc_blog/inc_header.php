<?=$source_ads?>
<div id="header">
   <a class="logo" href="<?=$path_root?>" target="_blank" title="tìm việc làm"><img src="<?=$path_themes . 'image/logo.png'?>" /></a>
   <div class="top_banner"><?=ads_show(2)?></div>
   <?/*<a class="top_banner bncv" href="/cv/?utm_campaign=banner_topblog&utm_medium=referral&utm_source=banner_topblog" title="CV xin việc, viết cv online, cv mẫu"><img src="<?=$path_themes . 'image_temp/banner_cv_728_1.png'?>" alt="CV xin việc, viết cv online, cv mẫu" /></a>*/?>
   <?/*
   <ul class="navigator">
      <li><a target="_blank" href="/tim-viec-lam" title="Tìm việc làm">Tìm việc làm</a></li>
      <li><a target="_blank" href="/employer/boad.php" title="Nhà tuyển dụng">Nhà tuyển dụng</a></li>
      <?
      foreach($arrayCategory as $id => $cat){
         $name    = $cat['bc_name'];
         $linkCat = createLinkBlogCategory($cat);
         ?>
         <li><a href="<?=$linkCat?>" title="<?=$name?>"><?=$name?></a></li>
         <?
      }
      ?>
   </ul>
   */?>
</div>
