<?
require_once("inc_security.php");

checkAddEdit("add");
//Khai báo biến khi thêm mới
$fs_redirect		= "add.php";
$after_save_data	= getValue("after_save_data", "str", "POST", "add.php");
$rec_id				= getValue('record_id');
$fs_title			= "Jobs";
$fs_action			= getURL();

$rec_name	= clean_string(getValue('com_name', 'str', 'POST', ''));
$rec_name_accent	= strtolower(removeAccent($rec_name));
$rec_name_md5		= md5($rec_name_accent);

$mycompany	= new generate_form();
$mycompany->add('com_name', 'com_name', 0, 1, '', 1, 'Ten cong ty khong co', 0, '');
$mycompany->add('com_name_accent', 'com_name_accent', 0, 1, '', 0, '', 0, '');
$mycompany->add('com_address', 'com_address', 0, 0, '', 0, '', 0, '');
$mycompany->add('com_home_phone', 'com_home_phone', 0, 0, '', 0, '', 0, '');
$mycompany->add('com_mobile_phone', 'com_mobile_phone', 0, 0, '', 0, '', 0, '');
$mycompany->add('com_name_contact', 'com_name_contact', 0, 0, '', 0, '', 0, '');
$mycompany->add('com_email', 'com_email', 0, 0, '', 0, '', 0, '');
$mycompany->addTable($fs_table);

$action	=	getValue("action", "str", "POST", "");
if($action == 'execute'){
	$msgError	= $mycompany->checkdata();

	if($msgError == ''){
		$db_ex	= new db_execute($mycompany->generate_update_SQL('com_id', $rec_id));
		if($db_ex->total < 0) $msgError .= 'Error update jobs <br>';
		unset($db_ex);

		if($msgError == ''){
			redirect('listing.php');
		}
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<?
//add form for javacheck
$mycompany->addFormname("form_name");//(tên form name)
$mycompany->evaluate();
$mycompany->strJavascript .= ';';
$mycompany->checkjavascript();

$msgError .= $mycompany->strErrorField;

//lay du lieu cua record can sua doi
$db_data 	= new db_query("SELECT * FROM ". $fs_table ."
                            INNER JOIN company_description ON com_id = cd_com_id
								    WHERE com_id = " . $rec_id, __FILE__ . " Line: " . __LINE__);
if($row 		= mysqli_fetch_assoc($db_data->result)){
	foreach($row as $key=>$value){
		$$key = $value;
	}
}else{
		exit();
}
?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top(translate_text("Edit company"))?>
<?
$form = new form();
$form->create_form("form_name", $fs_action, "post", "multipart/form-data",'onsubmit="validateForm(); return false;" id="form_name"');
?>
<?=$form->create_table('1')?>
<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
<?=$form->errorMsg($msgError)?>
<?=$form->text('Rec name', 'com_name', 'com_name', $com_name, '', 1, 500, '', 255)?>
<?=$form->textarea('Address', 'com_address', 'com_address', $com_address, '', 0, 500, '')?>
<?=$form->text('H Phone', 'com_mobile_phone', 'com_mobile_phone', $com_mobile_phone, '', 0, 150, '')?>
<?=$form->text('Phone', 'com_mobile_phone', 'com_mobile_phone', $com_mobile_phone, '', 0, 150, '')?>
<?=$form->text('Name contact', 'com_name_contact', 'com_name_contact', $com_name_contact, '', 0, 300, '')?>
<?=$form->text('Email', 'com_email', 'com_email', $com_email, '', 0, 300, '')?>
<?=$form->textarea('Description', 'cd_description', 'cd_description', str_replace('<br>', "\n", $cd_description), '', 0, 500, 300)?>
<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", 'style="background:url(' . $fs_imagepath . 'button_1.gif) no-repeat"' . $form->ec . 'style="background:url(' . $fs_imagepath . 'button_2.gif)"', "");?>
<?=$form->hidden("action", "action", "execute", "");?>
<?=$form->close_table()?>
<?
$form->close_form();
unset($form);
?>
<?=template_bottom() ?>
<?=template_bottom() ?>
<script src="/js/jquery.datepick.js" language="javascript"></script>
<script src="/js/jquery.datepick-vi.js" language="javascript"></script>
<script language="javascript">
	$(document).ready(function() {
		$('#cla_expires').datepick();
	});
	function checkformupload(){
		var file_name	=	$("#emp_file_name").val();
		if(file_name == ""){
			alert("Chọn file thông tin việc làm.");
			$("#emp_file_name").focus();
			return false;
		}
		return true;
	}
	function loadChildScript(value, type){
		$.ajax({
		  url: "/ajaxs/script_form.php",
		  dataType: "script",
		  data: {
		  	value : value,
		  	type	: type
		  },
		  success: function(){

		  }
		});
	}
</script>
</body>
</html>

