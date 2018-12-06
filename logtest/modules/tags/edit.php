<?
require_once("inc_security.php");
//check quy?n them sua xoa
//checkAddEdit("add");

	//Khai bao Bien
	$fs_redirect 		= base64_decode(getValue("url","str","GET",base64_encode("listing.php")));
	$record_id 			= getValue("record_id");
	$sql					= "1";
	$menu 				= new menu();
	
   $listAll 								= array();
   $db_cat  = new db_query("SELECT * FROM category WHERE cat_type='vl'");
   while($rcat = mysqli_fetch_assoc($db_cat->result)){
      $listAll[$rcat['cat_id']] = $rcat['cat_name'];
   }
   unset($db_cat);
   
	//Call Class generate_form();

   $cat_title	= getValue('cat_title', 'str', 'POST', '');
	$cat_des	= getValue('cat_des', 'str', 'POST', '');
	$cat_content		= getValue('cat_content', 'str', 'POST', '');

	$myform 									= new generate_form();
	$myform->removeHTML(0);
	$myform->add("tag_name","tag_name",0,0,'',1,"Vui lòng chọn tên!",0,"");
   $myform->add("tag_title","tag_title",0,0,"",0,"Vui lòng nhập tite", 0,"");
   $myform->add("tag_keyword","tag_keyword",0,0,"",0,"Vui lòng nhập keyword",0,"");
   $myform->add("tag_search","tag_search",0,0,"",0,"Vui lòng nhập từ khóa search",0,"");
   $myform->add("tag_des","tag_des",0,0,"",0,"Vui lòng nhập mô tả",0,"");
   $myform->add("tag_cat_id","tag_cat_id",1,0,0,0,"Chưa chọn danh mục cho tags",0,"");
	$myform->addTable($fs_table);

	$action	= getValue("action", "str", "POST", "");
	if($action == "execute"){

		$fs_errorMsg .= $myform->checkdata();
		if($fs_errorMsg == ""){
			$db_ex 	= new db_execute_return();
			$last_id = $db_ex->db_execute($myform->generate_update_SQL($id_field, $record_id));

         
         $db_des  = new db_query("SELECT * FROM category_des WHERE cat_id = " . $record_id . " LIMIT 1");
         if($rdes = mysqli_fetch_assoc($db_des->result)){
            $db_ex	= new db_execute("UPDATE category_des SET cat_title ='". replaceMQ($cat_title) ."', 
                                       cat_des = '". replaceMQ($cat_des) ."',
                                       cat_content = '". replaceMQ($cat_content) ."'
   												WHERE cat_id =". $record_id, __FILE__ ." Line: " . __LINE__);
            unset($db_ex);
         }else{
            $db_in	= new db_execute("INSERT INTO category_des (cat_id, cat_title,cat_des,cat_content)
													VALUES(". $record_id .",'". replaceMQ($cat_title) ."','". replaceMQ($cat_des) ."','". replaceMQ($cat_content) ."')");
				unset($db_in);
         }
         unset($db_des);
         
			
			redirect($fs_redirect);
			exit();
		}
	}
	//add form for javacheck
	$myform->addFormname("add_new");
	$myform->evaluate();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<?
$myform->checkjavascript();
$fs_errorMsg .= $myform->strErrorField;

//lay du lieu cua record can sua doi
$db_data 	= new db_query("SELECT * FROM " . $fs_table . "
                            WHERE " . $id_field . " = " . $record_id);
if($row 		= mysqli_fetch_assoc($db_data->result)){
	foreach($row as $key=>$value){
		if($key!='lang_id' && $key!='admin_id') $$key = $value;
	}
}else{
		exit();
}
?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top(translate_text("Edit_category"))?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
	<p align="center" style="padding-left:10px;">
	<?
	$form = new form();
	$form->create_form("add", $_SERVER["REQUEST_URI"], "post", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>
	<?=$form->text_note('Những ô dấu (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($fs_errorMsg)?>
	<?=$form->text("Tags", "tag_name", "tag_name", $tag_name, "Tên tags", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Title", "tag_title", "tag_title", $tag_title, "Title", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Keyword", "tag_keyword", "tag_keyword", $tag_keyword, "keyword", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Từ khóa search", "tag_search", "tag_search", $tag_search, "Từ khóa search", 1, 250, "", 255, "", "", "")?>
   <?=$form->select('Danh mục', 'tag_cat_id', 'tag_cat_id', $listAll, $tag_cat_id, 'Danh mục', 1, 200)?>
	<?=$form->textarea("Mô tả", "tag_des", "tag_des", $tag_des, "Mô tả", 0, 400, 100, "", "", "")?>
	<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", 'style="background:url(' . $fs_imagepath . 'button_1.gif) no-repeat"' . $form->ec . 'style="background:url(' . $fs_imagepath . 'button_2.gif)"', "");?>
	<?=$form->hidden("action", "action", "execute", "");?>
	<?=$form->close_table();?>
	<?
	$form->close_form();
	unset($form);
	?>
	</p>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>

<script>
	function countstr(obj){
		var strlen		= $(obj).val().length || 0;
		$('#countstr').text(strlen + ' ký tự');
	}
</script>
</body>
</html>