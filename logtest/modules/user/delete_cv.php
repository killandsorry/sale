<?
	include("inc_security.php");
	//check quy?n them sua xoa
	checkAddEdit("delete");
	$returnurl 		= base64_decode(getValue("returnurl","str","GET",base64_encode("listing.php")));
	$record_id		= getValue("record_id","str","POST","0");
	$arr_record 	= explode(",", $record_id);
	$total 			= 0;
	foreach($arr_record as $i=>$record_id){
		$record_id = intval($record_id);
		//Delete data with ID
		
        $db_cv  = new db_query("SELECT * FROM user_my_cv WHERE umc_id = " . intval($record_id) . " LIMIT 1");
        if($row = $db_cv->fetch()){
            $db_del_cv		= new db_execute("DELETE FROM user_my_cv WHERE umc_id IN (" . $record_id .")");
            unset($db_del_cv);
            
            $db_del_cv		= new db_execute("DELETE FROM user_cv_certificate WHERE ucc_key = '" . $row['umc_key'] ."'");
            unset($db_del_cv);
            
            $db_del_cv		= new db_execute("DELETE FROM user_cv_company WHERE ucc_key = '" . $row['umc_key'] ."'");
            unset($db_del_cv);
            
            $db_del_cv		= new db_execute("DELETE FROM user_cv_field WHERE ucf_key = '" . $row['umc_key'] ."'");
            unset($db_del_cv);
            
            $db_del_cv		= new db_execute("DELETE FROM user_cv_gusto WHERE ucg_key = '" . $row['umc_key'] ."'");
            unset($db_del_cv);
            
            $db_del_cv		= new db_execute("DELETE FROM user_cv_more_info WHERE umi_key = '" . $row['umc_key'] ."'");
            unset($db_del_cv);
            
            $db_del_cv		= new db_execute("DELETE FROM user_cv_price WHERE ucp_key = '" . $row['umc_key'] ."'");
            unset($db_del_cv);
            
            $db_del_cv		= new db_execute("DELETE FROM user_cv_project WHERE ucp_key = '" . $row['umc_key'] ."'");
            unset($db_del_cv);
            
            $db_del_cv		= new db_execute("DELETE FROM user_cv_school WHERE ucs_key = '" . $row['umc_key'] ."'");
            unset($db_del_cv);
            
            $db_del_cv		= new db_execute("DELETE FROM user_cv_skill WHERE ucs_key = '" . $row['umc_key'] ."'");
            unset($db_del_cv);
            
            $db_del_cv		= new db_execute("DELETE FROM user_cv_target WHERE uct_key = '" . $row['umc_key'] ."'");
            unset($db_del_cv);
            
            
            
        }
        unset($db_cv);
	}
	echo "Có " . $total . " b?n ghi dã du?c xóa !";
?>