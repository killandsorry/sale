<?
require_once("inc_security.php");
//check quyền them sua xoa
//checkAddEdit("add");

//Khai bao Bien
$fs_redirect							= "add.php";
$after_save_data						= getValue("after_save_data", "str", "POST", "add.php");
$cat_id                          = getValue("cat", "str", "GET", 0);
$cal_cat_id                      = getValue("cal_cat_id", "str", "POST", 0);
$cat_type								= getValue("cat_type","str","GET","");
if($cat_type == "") $cat_type 	= 'job';

$sql										= "1";
if($cat_type != "")  $sql			= " cat_type = '" . $cat_type . "'";
$menu 									= new menu();
$listAll 								= array();
$db_cat  = new db_query("SELECT * FROM category WHERE cat_type='vl'");
while($rcat = mysqli_fetch_assoc($db_cat->result)){
   $listAll[$rcat['cat_id']] = $rcat['cat_name'];
}
unset($db_cat);

$myform 									= new generate_form();
$myform->removeHTML(0);

$myform->add("tag_name","tag_name",0,0,'',1,"Vui lòng chọn tên!",0,"");
$myform->add("tag_title","tag_title",0,0,"",1,"Vui lòng nhập tite",1,"Tags đã được nhập");
$myform->add("tag_keyword","tag_keyword",0,0,"",1,"Vui lòng nhập keyword",0,"");
$myform->add("tag_search","tag_search",0,0,"",1,"Vui lòng nhập từ khóa search",0,"");
$myform->add("tag_des","tag_des",0,0,"",1,"Vui lòng nhập mô tả",0,"");
$myform->add("tag_cat_id","tag_cat_id",1,0,0,1,"Chưa chọn danh mục cho tags",0,"");
$myform->addTable('tags');

$action	= getValue("action", "str", "POST", "");
if($action == "execute"){

	$fs_errorMsg .= $myform->checkdata();
	if($fs_errorMsg == ""){

		$db_ex 	= new db_execute_return();
		$last_id = $db_ex->db_execute($myform->generate_insert_SQL());

		//Redirect to:
		redirect('add.php?cat=' . $cal_cat_id);
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
	<?=$form->text("Tags", "tag_name", "tag_name", $tag_name, "Tên tags", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Title", "tag_title", "tag_title", $tag_title, "Title", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Keyword", "tag_keyword", "tag_keyword", $tag_keyword, "keyword", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Từ khóa search", "tag_search", "tag_search", $tag_search, "Từ khóa search", 1, 250, "", 255, "", "", "")?>
   <?=$form->select('Danh mục', 'tag_cat_id', 'tag_cat_id', $listAll, $tag_cat_id, 'Danh mục', 1, 200)?>
	<?=$form->textarea("Mô tả", "tag_des", "tag_des", $tag_des, "Mô tả", 0, 400, 100, "", "", "")?>
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
<script>
   $(function(){
      $('#cal_name').focus();
   })
</script>
</body>
</html>