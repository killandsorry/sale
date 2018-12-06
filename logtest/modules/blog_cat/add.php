<?
require_once("inc_security.php");
//check quyền them sua xoa
//checkAddEdit("add");

//Khai bao Bien
$fs_redirect							= "add.php";
$after_save_data						= getValue("after_save_data", "str", "POST", "add.php");

$myform 									= new generate_form();
$myform->removeHTML(0);


$myform->add("bc_name","bc_name",0,0,"",1,"Vui lòng nhập tên danh mục",0,"");
$myform->add("bc_title","bc_title",0,0,"",1,"Vui lòng nhập tiêu đề danh mục",0,"");
$myform->add("bc_keyword","bc_keyword",0,0,"",1,"Vui lòng nhập từ khóa danh mục",0,"");
$myform->add("bc_description","bc_description",0,0,"",1,"Vui lòng nhập description",0,"");
$myform->addTable($fs_table);

$action	= getValue("action", "str", "POST", "");
if($action == "execute"){

	$fs_errorMsg .= $myform->checkdata();
	if($fs_errorMsg == ""){

		$db_ex 	= new db_execute_return();
		$last_id = $db_ex->db_execute($myform->generate_insert_SQL());
		// Redirect to add new
		$fs_redirect = "add.php?save=1";
		//Redirect to:
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
<? $myform->checkjavascript();?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top(("Add_new_category"))?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
	<p align="center" style="padding-left:10px;">
	<?
	$form = new form();
	$form->create_form("add", $_SERVER["REQUEST_URI"], "POST", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>
	<?=$form->text_note('Những ô dấu (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($fs_errorMsg)?>
	
	<?=$form->text("Tên danh mục", "bc_name", "bc_name", $bc_name, "Tên danh mục", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Tiêu đề", "bc_title", "bc_title", $bc_title, "tiêu đề", 1, 250, "", 255, "", "", "")?>
   <?=$form->textarea('Keyword', 'bc_keyword', 'bc_keyword', $bc_keyword, 'Keyword', 1, 500, 100)?>
   <?=$form->textarea('Description', 'bc_description', 'bc_description', $bc_description, 'Description', 1, 500, 100)?>	
	<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", 'style="background:url(' . $fs_imagepath . 'button_1.gif) no-repeat"' . $form->ec . 'style="background:url(' . $fs_imagepath . 'button_2.gif)"', "");?>
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