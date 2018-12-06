<?
require_once("inc_security.php");
//check quy?n them sua xoa
checkAddEdit("add");

	//Khai bao Bien
	$fs_redirect 							= base64_decode(getValue("url","str","GET",base64_encode("listing.php")));
	$record_id 								= getValue("record_id");
	$cit_parent_id	=	"";
	$sql_cit									=	new db_query("SELECT * FROM city WHERE cit_id=".$record_id);
	while($vl_cit							=	mysqli_fetch_assoc($sql_cit->result))
	{
		$cit_parent_id								=	$vl_cit['cit_parent_id'];
	}

	$sql										= "1";
//	$menu 									= new menu();
//	$listAll 								= $menu->getAllChild("city","cit_id","cit_parent_id","0",$sql . "","cit_id,cit_name,cit_order,cit_alias,cit_parent_id,cit_active");
	//Call Class generate_form();
	$myform 									= new generate_form();
	//Lo?i b? chuc nang thay the Tag Html
	$myform->removeHTML(0);

	$myform->add("cit_name","cit_name",0,0,"",1,translate_text("Vui lòng nhập vào tên tỉnh thành!"),0,"");
	$myform->add("cit_order","cit_order",1,0,0,0,"",0,"");
	$myform->add("cit_alias","cit_alias",0,0,"",1,"Bạn vui lòng nhập vào tên viết tắt!",0,"");
	$myform->add("cit_parent_id","cit_parent_id",1,0,0,0,"",0,"");
	//Add table
	$myform->addTable($fs_table);

	$action	= getValue("action", "str", "POST", "");
	if($action == "execute"){


		$fs_errorMsg .= $myform->checkdata();
		if($fs_errorMsg == ""){
			$db_ex 	= new db_execute_return();
			$last_id = $db_ex->db_execute($myform->generate_update_SQL($id_field, $record_id));

			redirect("listing.php");
		//	exit();
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
$db_data 	= new db_query("SELECT * FROM " . $fs_table . " WHERE " . $id_field . " = " . $record_id);
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
<?=template_top(translate_text("Edit_city"))?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
	<p align="center" style="padding-left:10px;">
	<?
	$form = new form();
	$form->create_form("add", $_SERVER["REQUEST_URI"], "post", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>

	<?=$form->text("Tên tỉnh thành", "cit_name", "cit_name", $cit_name, "Tên tỉnh thành", 1, 240, "", 255, "", "", "")?>
	<?=$form->text("Thứ tự", "cit_order", "cit_order", $cit_order, "Thứ tự", 1, 50, "", 11, "", "", "")?>
	<?=$form->text("Tên viết tắt", "cit_alias", "cit_alias", $cit_alias, "Tên viết tắt", 1, 150, "", 20, "", "", "")?>
	<tr>
		<td align="right" nowrap class="form_name" width="200"><font class="form_asterisk">* </font> <?=translate_text("Chọn cấp cha")?> :</td>
		<td>
			<select name="cit_parent_id" id="cit_parent_id" class="form_control" >
				<option value="0">-Chọn cấp cha-</option>
				<?
				$sql			=	"SELECT * FROM city WHERE cit_parent_id=0";
				$sql_query	=	mysql_query($sql);
				while($vl	=	mysqli_fetch_assoc($sql_query))
				{
					?>
					<option value="<?=$vl['cit_id']?>" <?=$vl['cit_id'] ==	$cit_parent_id? "selected='selected'":""?> ><?=$vl['cit_name']?></option>
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