<?
require_once("inc_security.php");
//check quyền them sua xoa
checkAddEdit("edit");
$returnurl = base64_decode(getValue("returnurl","str","GET",base64_encode("listing.php")));

//Khai bao Bien
$errorMsg = "";
$iQuick = getValue("iQuick","str","POST","");
if ($iQuick == 'update'){
	$record_id = getValue("record_id", "arr", "POST", "");
	if($record_id != ""){
		for($i=0; $i<count($record_id); $i++){
			$errorMsg="";
			//Call Class generate_form();
			$myform = new generate_form();
			//Loại bỏ chuc nang thay the Tag Html
			$myform->removeHTML(0);
			$cat_name			= getValue("cat_name" . $record_id[$i],"str","POST","");
			$cat_cha				= getValue("cat_cha" . $record_id[$i],"int","POST",0);
			$cat_order			= getValue("cat_order" . $record_id[$i],"int","POST",0);
         
         $cat_title        = getValue("cat_title" . $record_id[$i],"str","POST","");
         $cat_keyword        = getValue("cat_keyword" . $record_id[$i],"str","POST","");
         $cat_des        = getValue("cat_des" . $record_id[$i],"str","POST","");
			//Insert to database
			$myform->add("cat_name","cat_name" . $record_id[$i],0,0,"",0,"",0,"");
			$myform->add("cat_order","cat_order" . $record_id[$i],1,0,0,0,"",0,"");
			//$myform->add("cat_group","cat_group" . $record_id[$i],1,0,0,0,"",0,"");
			//Add table
			$myform->addTable($fs_table);
         
         $mydes   = new generate_form();
         if($cat_title != ''){
            $mydes->add("cat_title","cat_title" . $record_id[$i],0,0,"",0,"",0,"");
         }
         
         if($cat_keyword != ''){
            $mydes->add("cat_keyword","cat_keyword" . $record_id[$i],0,0,"",0,"",0,"");
         }
         
         if($cat_des != ''){
            $mydes->add("cat_des","cat_des" . $record_id[$i],0,0,"",0,"",0,"");
         }
         
         $mydes->addTable('category_des');
         
			$errorMsg .= $myform->checkdata();
			if($errorMsg == ""){
				$db_ex = new db_execute($myform->generate_update_SQL($id_field,$record_id[$i]));
				//echo $myform->generate_update_SQL("cat_id",$record_id[$i]);
				//echo $errorMsg;
            
            unset($db_ex);
            $db_ex   = new db_execute($mydes->generate_update_SQL($id_field, $record_id[$i]));
            unset($db_ex);
            
            
			}
		}
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
	echo "Đang cập nhật dữ liệu !";
	redirect($returnurl);

}
?>