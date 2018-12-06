<div class="breadcrum">
   <ul itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
         <a href="<?=$path_root?>" itemprop="item" title="Blog chia sẻ kinh nghiệm tìm việc làm, tuyển dụng">
            <span itemprop="name">Blog tìm việc làm</span>
         </a>
      </li>
      <?   
         if(isset($cat_id) && $cat_id > 0){
            if(isset($arrayCategory[$cat_id])){
               $name = $arrayCategory[$cat_id]['bc_name'];
               $link = createLinkBlogCategory($arrayCategory[$cat_id]);
               ?>
               <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                  &rsaquo;
                  <a href="<?=$link?>" itemprop="item" title="<?=$name?>">
                     <span itemprop="name"><?=$name?></span>
                  </a>
               </li>
               <?
            }
         }
      ?>
      <?
         if(isset($blo_id) && $blo_id > 0){
            $name = isset($title)? $title : '';
            $link = isset($url)? $url : '';
            ?>
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
               &rsaquo;
               <a href="<?=$link?>" itemprop="item" title="<?=$name?>">
                  <span itemprop="name"><?=$name?></span>
               </a>
            </li>
            <?
         }
      ?>
   </ul>
   
   <a style="position: absolute;right: 10px;top: 5px;" rel="nofollow" href="<?='/login?ref=' . base64_url_encode($_SERVER['REQUEST_URI']);?>" title="Đăng nhập tài khoản">Đăng nhập</a>
   <a style="position: absolute;right: 100px;top: 5px;" href="/cv/cv-mau" title="mau cv xin viec">Mẫu cv xin việc</a>
</div>