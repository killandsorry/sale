<div class="item_right">
   <h2 class="title_right">Chuyên mục kinh nghiệm</h2>
   <ul class="category">
      <?
      foreach($arrayCategory as $cid => $cinfo){
         $linkCat = createLinkBlogCategory($cinfo);
         $cat_name   = $cinfo['bc_name'];
         ?>
         <li>
            <a href="<?=$linkCat?>" title="<?=$cat_name?>"><?=$cat_name?></a>
         </li>
         <?
      }
      ?>
      <li>
         <a href="<?=$path_employer?>" target="_blank" title="Dành cho nhà tuyển dụng">Dành cho nhà tuyển dụng</a>
      </li>
      <li>
         <a href="<?=$path_ntv?>" target="_blank" title="Dành cho người tìm việc">Dành cho người tìm việc</a>
      </li>
      <li>
         <a href="<?=$path_luongcao?>" target="_blank" title="Việc làm lương cao">Việc làm lương cao</a>
      </li>
      <li>
         <a href="<?=$path_24h?>" target="_blank" title="Việc làm 24h">Việc làm 24h</a>
      </li>
      <li>
         <a href="/cv/" target="_blank" title="Cách viết cv online lọt top cv nhà tuyển dụng">Cách viết CV xin việc online</a>
      </li>
   </ul>
</div>
<div class="item_right">
   <h2 class="title_right">Tin nhiều người xem</h2>
      <?
      $db_hot  = new db_query("SELECT * FROM blog WHERE blo_active = 1 ORDER BY blo_hit DESC LIMIT 5");
      while($rhot = mysqli_fetch_assoc($db_hot->result)){
         $title   = $rhot['blo_title'];
         $teaser  = $rhot['blo_teaser'];
         $link    = createLinkBlog($rhot);
         
         $cat_info   = isset($arrayCategory[$rhot['blo_cat_id']])? $arrayCategory[$rhot['blo_cat_id']] : array();
         $linkCat = createLinkBlogCategory($cat_info);
         
         $cat_name   = isset($cat_info['bc_name'])? $cat_info['bc_name'] : '';
         ?>
         <div class="item_blog_right">
            <div class="body">
               <p class="bname"><a href="<?=$link?>" title="<?=$title?>"><?=$title?></a></p>
               <div class="img">
                  <a href="<?=$link?>"><img src="/upload/blog/small_<?=$rhot['blo_image']?>" /></a>
               </div>
            </div>
            <p class="incat">In: <a href="<?=$linkCat?>" title="<?=$cat_name?>"><?=$cat_name?></a></p>
         </div>
         <?
      }
      unset($db_hot);
      ?>
</div>
<div class="item_right">
   <div class="fb-page" data-href="https://www.facebook.com/tuyendung.1viec/" data-tabs="timeline" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/vieclam.works24h/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/vieclam.works24h/">Tìm việc làm - Tuyển nhân sự</a></blockquote></div>
</div>