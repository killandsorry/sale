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
			$cit_name			= getValue("cit_name" . $record_id[$i],"str","POST","");
			$cit_alias			= getValue("cit_alias" . $record_id[$i],"str","POST","");
			$cit_order			= getValue("cit_order" . $record_id[$i],"int","POST",0);
			//Insert to database
			$myform->add("cit_name","cit_name_" . $record_id[$i],0,0,"",0,"",0,"");
			$myform->add("cit_alias","cit_alias_" . $record_id[$i],0,0,"",0,"",0,"");
			$myform->add("cit_order","cit_order_" . $record_id[$i],1,0,0,0,"",0,"");
			//$myform->add("cat_group","cat_group" . $record_id[$i],1,0,0,0,"",0,"");
			//Add table
			$myform->addTable($fs_table);

			$errorMsg .= $myform->checkdata();
			if($errorMsg == ""){
				$db_ex = new db_execute($myform->generate_update_SQL($id_field,$record_id[$i]));
				//echo $myform->generate_update_SQL($id_field,$record_id[$i]);
				//echo $errorMsg;
			}
		}
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
	echo "Đang cập nhật dữ liệu !";
	redirect($returnurl);

}
?>