<?
	include("inc_security.php");

	//Khai báo biến khi thêm mới
	$fs_title				= "Cấu hình Website";
	$fs_action				= getURL();
	$fs_redirect			= getURL();
	$fs_errorMsg			= "";

	//Get data edit
	$record_id				= $lang_id;
	$db_edit					= new db_query("SELECT * FROM " . $fs_table . " WHERE " . $id_field . " = " . $record_id);
	if(mysqli_num_rows($db_edit->result) == 0){
		//Redirect if can not find data
		redirect($fs_error);
	}
	$edit						= mysqli_fetch_assoc($db_edit->result);
	unset($db_edit);

	$con_comment_face		= getValue('con_comment_face', 'int', 'POST', 0);
	$con_cache_craw		= getValue('con_cache_craw', 'int', 'POST', 0);
	//print_r($_POST);
	$myform = new generate_form();
	$myform->add("con_admin_email", "con_admin_email", 0, 0, $edit["con_admin_email"], 0, "", 0, "");
	$myform->add("con_site_title", "con_site_title", 0, 0, $edit["con_site_title"], 1, "Bạn chưa nhập tiêu đề cho website", 0, "");	
	$myform->add("con_meta_description", "con_meta_description", 0, 0, $edit["con_meta_description"], 0, "", 0, "");
	$myform->add("con_content", "con_content", 0, 0, $edit["con_content"], 0, "", 0, "");
   $myform->add("con_hotline", "con_hotline", 0, 0, $edit["con_hotline"], 0, "", 0, "");
   $myform->add("con_address", "con_address", 0, 0, $edit["con_address"], 0, "", 0, "");
   $myform->add("con_email", "con_email", 0, 0, $edit["con_email"], 0, "", 0, "");
   $myform->add("con_owner", "con_owner", 0, 0, $edit["con_owner"], 0, "", 0, "");
	//Add table insert data (add sau khi add het các trường để check lỗi)
	$myform->addTable($fs_table);
	$action					= getValue("action", "str", "POST", "");
	//Check $action for insert new data
	if($action == "execute"){
		//Check form data
		$fs_errorMsg .= $myform->checkdata();
		if($fs_errorMsg == "") {
			//Insert to database
			$myform->removeHTML(0);
			$db_update = new db_execute($myform->generate_update_SQL($id_field, $record_id));
			unset($db_update);
			redirect($fs_redirect);
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<?
$myform->checkjavascript();
//chuyển các trường thành biến để lấy giá trị thay cho dùng kiểu getValue
$myform->evaluate();
$fs_errorMsg .= $myform->strErrorField;
?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top($fs_title)?>
	<p align="center" style="padding-left:10px;">
	<?
	$form = new form();
	$form->create_form("edit", $fs_action, "post", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>

	<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($fs_errorMsg)?>
	<?=$form->text("Admin email", "con_admin_email", "con_admin_email", $con_admin_email, "Admin email", 1, 200, "", 255, "", "", "")?>
	<?=$form->text("Tiêu đề Website", "con_site_title", "con_site_title", $con_site_title, "Tiêu đề Website", 1, 350, "", 255, "", "", "")?>	
	<?=$form->textarea("Meta Description", "con_meta_description", "con_meta_description", $con_meta_description, "Meta Description", 0, 350, 100, "", "", "")?>
	<?=$form->textarea("Content", "con_content", "con_content", $con_content, "Content", 0, 350, 100, "", "", "")?>
   <?=$form->text("Hotline", "con_hotline", "con_hotline", $con_hotline, "Hotline", 1, 350, "", 255, "", "", "")?>
   <?=$form->text("Địa chỉ", "con_address", "con_address", $con_address, "Địa chỉ", 1, 350, "", 255, "", "", "")?>
   <?=$form->text("Email", "con_email", "con_email", $con_email, "Email", 1, 350, "", 255, "", "", "")?>
   <?=$form->text("Tên doanh nghiệp", "con_owner", "con_owner", $con_owner, "Owner", 1, 350, "", 255, "", "", "")?>
	<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", $form->ec, "");?>
	<?=$form->hidden("action", "action", "execute", "");?>
	<?
	$form->close_table();
	$form->close_form();
	unset($form);
	?>
	</p>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>