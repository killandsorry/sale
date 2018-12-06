<div class="blog_content">
   <h1 class="blog_title"><?=$title?></h1>
   <p class="sapo">
      <?=$row['blo_teaser']?>
   </p>
   <?=ads_show(8)?>
   <div class="fb_box">      
      <p style="margin-bottom: 10px;">Chia sẻ với bạn bè của bạn</p>
      <div class="fb_like" style="clear: both;display: inline-block; float: left; margin-right: 5px;">
         <div id="fb-root"></div>
         <script>(function(d, s, id) {
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) return;
           js = d.createElement(s); js.id = id;
           js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.7&appId=1649548195356808";
           fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));</script>
         <div class="fb-send" data-href="<?=$url?>"></div>
         <div class="fb-like" data-href="<?=$url?>" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>
         <div class="fb-save" data-uri="<?=$url?>" data-size="small"></div>
      </div>
      <div style="display: inline-block;">
         <!-- Đặt thẻ này vào phần đầu hoặc ngay trước thẻ đóng phần nội dung của bạn. -->
         <script src="https://apis.google.com/js/platform.js" async defer>
           {lang: 'vi'}
         </script>
         
         <!-- Đặt thẻ này vào nơi bạn muốn Nút +1 kết xuất. -->
         <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="300" data-href="http://1viec.com/"></div>
      </div>
   </div>
   <p class="blog_time">Ngày đăng: <?=date('d/m/Y - H:i', $row['blo_date'])?></p>
   <div class="content">
      <?
      if(isset($row['blo_cat_vl_id']) && $row['blo_cat_vl_id'] > 0){
         $vl_cid  = intval($row['blo_cat_vl_id']);
         
         if(isset($arrayCate_cat[$vl_cid])){
            $link_c  = vl_url_category($arrayCate_cat[$vl_cid]);
            echo '<a class="keyword" href="'. $link_c .'" title="Tìm việc làm '. $arrayCate_cat[$vl_cid]['cat_name'] .'">Tìm việc làm '. $arrayCate_cat[$vl_cid]['cat_name'] .'</a>';
         }
      }
      $content = ($row['blo_content']);
      $content = str_replace('<br /><br />', '<br>', $content);
      $content = str_replace('<br /><br />', '<br>', $content);
      $content = str_replace('<br /><br />', '<br>', $content);
      $content = str_replace('<br /><br />', '<br>', $content);
      echo ($content)?>
      <div style="padding: 10px 0;">
         <?=ads_show(10)?>
      </div>
      <p class="blog_source">Nguồn: <?=$row['blo_source']?></p>
   </div>
   <div class="fb_box">      
      <p style="margin-bottom: 10px;">Chia sẻ nếu bạn thấy có ý nghĩa</p>
      <div class="fb_like" style="clear: both;display: inline-block; float: left; margin-right: 5px;">
         <div class="fb-send" data-href="<?=$url?>"></div>
         <div class="fb-like" data-href="<?=$url?>" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>
         <div class="fb-save" data-uri="<?=$url?>" data-size="small"></div>
      </div>
      <div style="display: inline-block;">
         <!-- Đặt thẻ này vào nơi bạn muốn Nút +1 kết xuất. -->
         <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="300" data-href="http://1viec.com/"></div>
      </div>
   </div>
   
   <?
   if(isset($row['blo_cat_vl_id']) && $row['blo_cat_vl_id'] > 0){
      ?>
      <div class="vl_quantam">
         <h3><a href="<?=$path_ntv?>" title="tìm việc là">Việc làm</a> đáng quan tâm</h3>
         <ul class="job_home">
         <?
         $sql_where  = " AND job_active = 1 AND job_date_expires > ". time();
         $sql_order  = " ORDER BY job_id DESC ";
         
         if($row['blo_cat_vl_id'] > 0){
            $sql_where .= " AND (job_cat_1 = " . intval($row['blo_cat_vl_id']) . " OR job_cat_2 = " . intval($row['blo_cat_vl_id']) . ") ";
         }
         
         
         $db_job	= new db_query("SELECT * FROM job      
   										WHERE 1 ". $sql_where . $sql_order ."
   										LIMIT 5 "
   										,__FILE__ . "Line: " . __LINE__);
      	while($rjob	= mysqli_fetch_assoc($db_job->result)){
      		echo templateJob_home($rjob);
      	}
      	unset($db_job);
         ?>
         </ul>
      </div>
      <?
   }
   ?>
</div>

<div class="example">
   <h2 class="example_title">Bài viết cùng chuyên mục</h2>
   <div class="home_list">
      <?
      $db_example = new db_query("SELECT * FROM blog 
                                  WHERE blo_cat_id = " . intval($blo_cat_id) . "
                                  AND blo_active = 1 AND blo_id <> ". $blo_id . " 
                                  ORDER BY blo_id DESC LIMIT 6");
      $i = 2;
      while($row_example   = mysqli_fetch_assoc($db_example->result)){
         
         $title   = $row_example['blo_title'];
         $link    = createLinkBlog($row_example);
         
         $cat_info   = isset($arrayCategory[$row_example['blo_cat_id']])? $arrayCategory[$row_example['blo_cat_id']] : array();
         $linkCat = createLinkBlogCategory($cat_info);
         
         $cat_name   = isset($cat_info['bc_name'])? $cat_info['bc_name'] : '';
         $class_center  = '';
         
         if($i % 3 == 0) $class_center = 'item_center';
         ?>
         <div class="item <?=$class_center?>">
            <p class="bname"><a href="<?=$link?>" title="<?=$title?>"><?=$title?></a></p>
            <div class="img">
               <a href="<?=$link?>"><img src="/upload/blog/small_<?=$row_example['blo_image']?>" /></a>
            </div>
            <p class="incat">In: <a href="<?=$linkCat?>" title="<?=$cat_name?>"><?=$cat_name?></a></p>
         </div>
         <?
         $i++;
      }
      unset($db_example);
      ?>
   </div>
</div>