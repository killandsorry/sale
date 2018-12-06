<?
require_once("inc_security.php");
//check quyền them sua xoa
checkAddEdit("add");
	//Khai bao Bien
	//$fs_redirect							= "add.php";
	$cit_parent_id							= getValue("cit_parent_id","int","GET",1);
	$after_save_data						= getValue("after_save_data", "str", "POST", "add.php");
	$cit_alias								= getValue("cit_alias","str","GET","");
	if($cit_alias == "") $cit_alias 	= getValue("cit_alias","str","POST","");
	$sql										= "1";
	if($cit_alias != "")  $sql			= " cit_alias = '" . $cit_alias . "'";
	$menu 									= new menu();
	$listAll 								= $menu->getAllChild("city","cit_id","cit_parent_id","0",$sql . "","cit_id,cit_name,cit_order,cit_parent_id,cit_active","cit_order ASC, cit_name ASC","");
	//Call Class generate_form();
	$myform 									= new generate_form();
	//Loại bỏ chuc nang thay the Tag Html
	$myform->removeHTML(0);
	$myform->add("cit_name","cit_name",0,0,"",1,("Vui lòng nhập vào tên tỉnh thành!"),0,"");
	$myform->add("cit_order","cit_order",1,0,0,0,"",0,"");
	$myform->add("cit_alias","cit_alias",0,0,"",1,"Bạn vui lòng nhập vào tên viết tắt!",1,"Tên viết tắt không được trùng với tên viết tắt khác");
	$myform->add("cit_parent_id", "cit_parent_id", 1, 0, 0, 0, "", 0, "");
	$myform->addTable($fs_table);

	$action	= getValue("action", "str", "POST", "");
	if($action == "execute"){


		$fs_errorMsg .= $myform->checkdata();
		if($fs_errorMsg == ""){
			$db_ex 	= new db_execute_return();
			$last_id = $db_ex->db_execute($myform->generate_insert_SQL());

			$iParent = getValue("cit_parent_id","int","POST",0);
			if($iParent > 0){
				$db_ex = new db_execute("UPDATE city SET cit_has_child = 1 WHERE cit_id = " . $iParent);
			}

			//redirect("add.php?cit_parent_id=".$cit_parent_id);
			//exit();
		}
	}
	//add form for javacheck
	$myform->addFormname("add_new");
	$myform->evaluate();
	if($cit_parent_id	== 0)
	{
		$cit_parent_id	= 	getValue("cit_parent_id","int","GET");
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
<?=template_top(translate_text("Add_new_city"))?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
	<p align="center" style="padding-left:10px;">
	<?
	$form = new form();
	$form->create_form("add", $_SERVER["REQUEST_URI"], "post", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>
	<?=$form->text_note('Những ô dấu (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($fs_errorMsg)?>
	<!--<tr>
		<td align="right" nowrap class="form_name" width="200"><font class="form_asterisk">* </font> <?=translate_text("Loại danh mục")?> :</td>
	</tr>	-->
	<?=$form->text("Tên tỉnh thành", "cit_name", "cit_name", $cit_name, "Tên tỉnh thành", 1, 240, "", 255, "", "", "")?>
	<?=$form->text("Thứ tự", "cit_order", "cit_order", $cit_order, "Thứ tự", 1, 50, "", 11, "", "", "")?>
	<?=$form->text("Tên viết tắt", "cit_alias", "cit_alias", $cit_alias, "Tên viết tắt", 1, 150, "", 20, "", "", "")?>
	<tr>
		<td align="right" nowrap class="form_name" width="200"><font class="form_asterisk">* </font> <?=translate_text("Chọn cấp cha")?> :</td>
		<td>
			<select name="cit_parent_id" id="cit_parent_id" class="form_control" onchange="window.location.href='add.php?cit_parent_id='+this.value" >
				<option value="0">-Chọn cấp cha-</option>
				<?
				$sql			=	"SELECT * FROM city WHERE cit_parent_id=0";
				$sql_query	=	mysql_query($sql);
				while($vl	=	mysqli_fetch_assoc($sql_query))
				{
					?>
					<option value="<?=$vl['cit_id']?>" <?=$cit_parent_id ==	$vl['cit_id']? "selected='selected'":""?> ><?=$vl['cit_name']?></option>
					<?
				}
				?>

			</select>
		</td>
	</tr>

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