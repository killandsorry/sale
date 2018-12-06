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
			$tag_name			= getValue("tag_name" . $record_id[$i],"str","POST","");
			$tag_keyword				= getValue("tag_keyword" . $record_id[$i],"str","POST",0);
			$tag_title			= getValue("tag_title" . $record_id[$i],"str","POST",0);
         $tag_des			= getValue("tag_des" . $record_id[$i],"str","POST",0);
			//Insert to database
			$myform->add("tag_name","tag_name" . $record_id[$i],0,0,"",0,"",0,"");
			$myform->add("tag_keyword","tag_keyword" . $record_id[$i],0,0,0,0,"",0,"");
         $myform->add("tag_title","tag_title" . $record_id[$i],0,0,0,0,"",0,"");
         $myform->add("tag_des","tag_des" . $record_id[$i],0,0,0,0,"",0,"");
			//Add table
			$myform->addTable($fs_table);
			$errorMsg .= $myform->checkdata();
			if($errorMsg == ""){
				$db_ex = new db_execute($myform->generate_update_SQL($id_field,$record_id[$i]));
				//echo $myform->generate_update_SQL("cat_id",$record_id[$i]);
				//echo $errorMsg;
			}
		}
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
	echo "Đang cập nhật dữ liệu !";
	redirect($returnurl);

}
?>