<?
require_once("inc_security.php");

#+
#+ Kiem tra quyen them sua xoa
checkAddEdit("edit");

#+
#+ Khai bao bien
$add				= "add.php";
$listing			= "listing.php";
$edit				= "edit.php";
$after_save_data	= getValue("after_save_data", "str", "POST", $listing);

$errorMsg 			= "";		//Warning Error!
$action				= getValue("action", "str", "POST", "");
$fs_action			= getURL();
$record_id			= getValue("record_id");
$blo_last_update	= time();

#+
#+ Goi class generate form
$myform = new generate_form();	//Call Class generate_form();
#+ Khai bao thong tin cac truong
$myform->add("blo_cat_id", "blo_cat_id", 1, 0, 0, 1, "Bạn chưa chọn danh mục.", 0, "");
$myform->add("blo_cat_vl_id", "blo_cat_vl_id", 1, 0, 0, 0, "", 0, "");
$myform->add("blo_title", "blo_title", 0, 0, "", 1, "Bạn chưa nhập tiêu đề.", 0, "");
$myform->add("blo_teaser", "blo_teaser", 0, 0, '', 1, "Tóm tắt.", 0, "");
$myform->add("blo_source", "blo_source", 0, 0, '', 1, "Tóm tắt.", 0, "");
$myform->addTable($fs_table);


$blo_content	= getValue('blo_content', 'str', 'POST', '');
$html_clean			= new html_cleanup($blo_content);
$html_clean->clean();
$blo_content	= $html_clean->output_html;

$blo_content	= str_replace('http://%5C%22', '', $blo_content);
$blo_content	= str_replace('%5C%22', '', $blo_content);
$blo_content	= str_replace('%22', '', $blo_content);
$blo_content	= str_replace('http://http://', 'http://', $blo_content);
$blo_content	= str_replace('http://https://', 'https://', $blo_content);


$mydes	= new generate_form();
$mydes->add('blo_content', 'blo_content', 6, 1, '', 1, 'Chưa nhập nội dung chi tiết', 0, '');
$mydes->addTable('blog_content');

#+
#+ đổi tên trường thành biến và giá trị
$myform->evaluate();
$mydes->evaluate();

#+
#+ Neu nhu co submit form
if($action == "submitForm"){
	$upload_pic = new upload("blo_image", $fs_pathfile, $extension_list, 500);
	if ($upload_pic->file_name != ""){
		$picture = $upload_pic->file_name;
		$upload_pic->resize_image($fs_pathfile,$upload_pic->file_name,460,1000,75, 'small_');
		$myform->add("blo_image","picture",0,1,"",0,"",0,"");
	}
	//Check Error!
	$errorMsg .= $upload_pic->show_warning_error();
	#+
	#+ Kiểm tra lỗi
   $errorMsg .= $myform->checkdata();
	# $errorMsg .= $myform->strErrorField ;	//Check Error!
	if($errorMsg == ""){
		#+
		#+ Thuc hien query
		$db_ex	 		= new db_execute_return();
		$query			= $myform->generate_update_SQL('blo_id', $record_id);
		$last_id 		= $db_ex->db_execute($query);
		$errorMsg	= $mydes->checkdata();
		if($errorMsg == ''){

			$db_ex	= new db_execute($mydes->generate_update_SQL('blo_id', $record_id));
			unset($db_ex);
			redirect($listing);
		}
	}
}

#+
#+ Khai bao ten form
$myform->addFormname("submitForm"); //add  tên form để javacheck
#+
#+ Xử lý javascript
$myform->addjavasrciptcode('');
$myform->checkjavascript();

#+
#+ lay du lieu cua record can sua doi
$query = "SELECT * FROM " . $fs_table . " AS a INNER JOIN blog_content AS b ON a.blo_id = b.blo_id WHERE a." . $field_id . " = " . $record_id;
$db_data 	= new db_query($query);

if($row 	= mysqli_fetch_assoc($db_data->result))
{
	foreach($row as $key=>$value)
	{
		if($key!='lang_id' && $key!='admin_id') $$key = $value;
	}
}else
{
	exit();
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top(translate_text("Records Add"))?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?
$form = new form();
$form->create_form("form_name",$fs_action,"post","multipart/form-data",'onsubmit="validateForm();return false;"  id="form_name" ');
$form->create_table();
?>
<?=$form->text_note('Những ô dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
<?=$form->errorMsg($errorMsg)?>

<?=$form->select('Danh mục việc làm', 'blo_cat_vl_id', 'blo_cat_vl_id', $arrayCat_vl, $blo_cat_vl_id, 'Category', 0, 200)?>
<?=$form->select('Danh mục', 'blo_cat_id', 'blo_cat_id', $arrayCat, $blo_cat_id, 'Category', 1, 200)?>
<?=$form->text("Tiêu đề", "blo_title", "blo_title", $blo_title, "Tiêu đề", 1, 500, "", 255, "", "", "")?>
<?=$form->textarea('Tóm tắt', 'blo_teaser', 'blo_teaser', $blo_teaser, 'Tóm tắt', 1, 500, 100)?>
<?=$form->textarea('Source', 'blo_source', 'blo_source', $blo_source, 'Tags', 1, 500, 40, '', '')?>
<?=$form->getFile("Ảnh tiêu đề", 'blo_image', 'blo_image', 'ảnh tiêu đề', 1)?>
<?=$form->close_table();?>
<?=$form->wysiwyg("Mô tả chi tiết", "blo_content", $blo_content, "../../resource/wysiwyg_editor/", "95%", 500)?>
<?=$form->create_table();?>

<?=$form->radio("Sau khi lưu dữ liệu", "add_new" . $form->ec . "return_listing" . $form->ec . "return_edit", "after_save_data", $add . $form->ec . $listing . $form->ec . $edit, $after_save_data, "Thêm mới" . $form->ec . "Quay về danh sách" . $form->ec . "Sửa bản ghi", 0, "" . $form->ec . "" . $form->ec . "");?>
<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", 'style="background:url(' . $fs_imagepath . 'button_1.gif) no-repeat; border:none;"' . $form->ec . 'style="background:url(' . $fs_imagepath . 'button_2.gif) no-repeat; border:none;"', "");?><br />
<?=$form->hidden("action", "action", "submitForm", "");?>

<?
$form->close_table();
$form->close_form();
unset($form);
?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>