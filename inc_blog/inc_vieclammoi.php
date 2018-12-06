<div style="text-align: center;margin: 20px 0; text-align:  center;clear: both;">
   <?/*<a href="/cv/" target="_blank" title="Công cụ viết cv online số 1 việt nam"><img src="<?=$path_themes . 'image_temp/banner_cv.png'?>" /></a>*/?>
   <?
   echo ads_show(10);
   ?>
</div>
<div class="category_focus">
   <h2 class="pnr_item_title"><a href="<?=$path_24h?>" title="Tìm việc làm 24h qua" target="_blank">Tuyển dụng việc làm mới đăng 24h qua</a></h2>
   <ul class="job_home" id="list_job_focus">
      <?
      $arrayJob	= array();
		$arrayJob	= getCacheJob(15, 'new');
		if(count($arrayJob) > 0){
         $c = 1;		 
			foreach($arrayJob as $job_id => $job_info){
			   if($c > 10) break;
			   echo templateJob_home($job_info);
            $c++;
			}
		}      
      ?>
   </ul>
   
</div>
