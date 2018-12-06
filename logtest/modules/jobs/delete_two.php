<?
	include("inc_security.php");
	//check quyền them sua xoa
	checkAddEdit("delete");
	$returnurl 		= base64_decode(getValue("returnurl","str","GET",base64_encode("listing.php")));
	$record_id		= getValue("record_id","str","POST","0");
	$arr_record 	= explode(",", $record_id);
	$total 			= 0;
  
	foreach($arr_record as $i=>$record_id){
		$record_id = intval($record_id);
		//Delete data with ID
		$db_del 			= new db_execute("DELETE FROM job_fb WHERE jobf_id = " . $record_id);
      unset($db_del);
      $table_job     = $vl_class->create_table_job_fb($record_id);
      $db_del 			= new db_execute("DELETE FROM ". $table_job ." WHERE jobf_id = " . $record_id);

		if($db_del->total>0){
			$total +=  $db_del->total;
		}
		unset($db_del);
	}
	echo "Có " . $total . " bản ghi đã được xóa !";
?>