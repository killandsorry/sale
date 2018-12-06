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
		$db_del 			= new db_execute("DELETE FROM ". $fs_table ." WHERE " . $id_field . " = " . $record_id);
		$db_del_des		= new db_execute("DELETE FROM jobs_description WHERE jobd_id =" . $record_id);
		$db_del_fill	= new db_execute("DELETE FROM jobs_fillter WHERE jobf_id =" . $record_id);
		$db_del_hit		= new db_execute("DELETE FROM jobs_hit WHERE jobh_id =" . $record_id);
      $table_job     = $vl_class->create_table_job($record_id);
      $db_del 			= new db_execute("DELETE FROM ". $table_job ." WHERE " . $id_field  . " = " . $record_id);

		if($db_del->total>0){
			$total +=  $db_del->total;
		}
		unset($db_del,$db_del_des,$db_del_fill,$db_del_hit);
	}
	echo "Có " . $total . " bản ghi đã được xóa !";
?>