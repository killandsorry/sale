<?
require_once("inc_security.php");
//check quyền them sua xoa
//checkAddEdit("add");

//Khai bao Bien
$fs_redirect							= "add.php";
$after_save_data						= getValue("after_save_data", "str", "POST", "add.php");

$cat_type								= getValue("cat_type","str","GET","");
if($cat_type == "") $cat_type 	= 'product';

$sql										= "1";
if($cat_type != "")  $sql			= " cat_type = '" . $cat_type . "'";
$menu 									= new menu();
$listAll 								= $menu->getAllChild("category","cat_id","cat_parent_id","0",$sql . "","cat_id,cat_name,cat_order","cat_order ASC, cat_name ASC","");

$myform 									= new generate_form();
$myform->removeHTML(0);

$myform->add("cat_type","cat_type",0,0,$cat_type,1,"Vui lòng chọn loại danh mục!",0,"");
$myform->add("cat_name","cat_name",0,0,"",1,"Vui lòng nhập tên danh mục",0,"");
$myform->add("cat_order","cat_order",1,0,0,0,"",0,"");
$myform->add("cat_parent_id","cat_parent_id",1,0,0,0,"",0,"");
$myform->add("cat_title","cat_title",0,0,"",0,"",0,"");
$myform->add("cat_des","cat_des",0,0,"",0,"",0,"");
$myform->add("cat_active","active",1,1,1,0,"",0,"");
$myform->addTable($fs_table);

$action	= getValue("action", "str", "POST", "");
if($action == "execute"){

	$fs_errorMsg .= $myform->checkdata();
	if($fs_errorMsg == ""){

		$db_ex 	= new db_execute_return();
		$last_id = $db_ex->db_execute($myform->generate_insert_SQL());

		// Redirect to add new
		$fs_redirect = "add.php?save=1&cat_type=" . getValue("cat_type","str","POST") . "&cat_order=" . getValue("cat_order","int","POST");
		//Redirect to:
		redirect($fs_redirect);
		exit();
	}
}
//add form for javacheck
$myform->addFormname("add_new");
$myform->evaluate();
if($cat_parent_id	== 0)
{
	$cat_parent_id	= 	getValue("iParent");
}
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
	<tr>
		<td align="right" nowrap class="form_name" width="200"><font class="form_asterisk">* </font> <?=("Loại danh mục")?> :</td>
		<td>
			<select name="cat_type" id="cat_type"  class="form_control" onChange="window.location.href='add.php?cat_type='+this.value">
				<?foreach($arrayCategoryType as $key => $value){?>
				<option value="<?=$key?>" <? if($key == $cat_type) echo "selected='selected'";?>><?=$value?></option>
				<?}?>
			</select>
		</td>
	</tr>
	<?=$form->text("Tên danh mục", "cat_name", "cat_name", $cat_name, "Tên danh mục", 1, 250, "", 255, "", "", "")?>
	<?=$form->text("Thứ tự", "cat_order", "cat_order", $cat_order, "Thứ tự", 1, 30, "", 30, "", "", "")?>
	<?=$form->select_db_multi("Danh mục cha", "cat_parent_id", "cat_parent_id", $listAll, "cat_id", "cat_name", $cat_parent_id, "Chọn cấp cha", 1, "", 1, 0, "", "")?>
   <?=$form->textarea("Tiêu đề", "cat_title", "cat_title", $cat_title, "tiêu đề", 0, 255, 50, "", "", "")?>
	<?=$form->textarea("Mô tả", "cat_des", "cat_des", $cat_des, "Mô tả", 0, 255, 50, "", "", "")?>
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