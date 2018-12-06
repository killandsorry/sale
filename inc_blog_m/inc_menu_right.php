<div id="right_menu" class="hide">
   <h2>
      <p id="right_humberger" onclick="hideRightMenu(this)" class="right_humberger"><i class="line_icon"></i>Danh mục</p>
   </h2>
   <ul class="mb_menu_right">
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
   </ul>
</div>
<span class="hide ovlay" onclick="trigger_hidemenu()"></span>