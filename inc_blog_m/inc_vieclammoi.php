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