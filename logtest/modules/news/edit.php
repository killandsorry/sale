<?
require_once("inc_security.php");
//check quyền them sua xoa
checkAddEdit("add");
$fs_redirect 							= base64_decode(getValue("url","str","GET",base64_encode("listing.php")));
$new_id			=	getValue("record_id");

$fs_title			=	"Tin đăng";
$fs_action			= getURL();//reload lại
$fs_errorMsg		= "";//lỗi trả về nếu thêm mới không đc
$new_date_create	= time();//lấy ngày hiện hành
$teaser				=	getValue("new_teaser", "str", "POST", "");
$new_teaser			=	cut_string($teaser, 300);
$myform	=	new generate_form();//tạo form mới
$myform->removeHTML(0);
//thêm các trường vào form
$myform->add("new_title", "new_title", 0, 0, "", 1, "Vui lòng nhập tiêu đề tin", 0, ""); //tiêu đề tin
$myform->add("new_cat_id", "new_cat_id", 1, 0, 0, 1, "Vui lòng chọn danh mục đăng tin", 0, ""); //danh mục cấp cha
$myform->add("new_last_update", "new_date_create", 1, 1, 0, 0, "", 0, ""); //Ngay sua cuoi cung
$myform->add("new_author", "new_author", 0, 0, "", 0, "", 0, ""); //Nguon bai viet
$myform->add("new_content", "new_content", 0, 0, "", 0, "", 0, ""); //Nguon bai viet
$myform->add("new_teaser", "new_teaser", 0, 1, "", 0, "", 0, ""); //Nguon bai viet
//Add table insert data
$myform->addTable($fs_table);
$content		=	getValue("new_content", "str", "POST", "");

//Get action variable for add new data
$action				= getValue("action", "str", "POST", ""); //kiểm tra xem form có đc submit đi không
//Check $action for insert new data
if($action == "execute"){
	//upload ảnh lên thư mục
	$upload_pic = new upload("new_picture", $img_path, $extension_list, $limit_size);
	if ($upload_pic->file_name != ""){
		$picture = $upload_pic->file_name;
		resize_image($img_path, $picture , 100, 100, 75);
		$myform->add("new_picture", "picture", 0, 1, "", 0, "", 0, "");
	}
	$html		=	new html_cleanup($content);
	$html->clean();
	$content	=	$html->output_html;
	$content	=	str_replace("http://%5C%22", "", $content); //$cla_des là nội dung của tin đăng khi đã loại bỏ các ký tự trước hình ảnh
	$content	=	str_replace("%5C%22", "", $content);
	
	$myform->add("new_content", "content", 0, 1, "", 0, "", 0, ""); //nội dung bài viết
	//check kiểm tra có lỗi hay không
	$fs_errorMsg .= $upload_pic->show_warning_error();
	$fs_errorMsg .= $myform->checkdata();
	//thực hiện thêm mới nếu không có lỗi
	if($fs_errorMsg == ""){
		//không remove ký tự html
		$myform->removeHTML(0);
		$db_ex 	= new db_execute($myform->generate_update_SQL($id_field, $new_id));
		//echo $db_ex->total;
		//echo $myform->generate_update_SQL($id_field, $new_id);
		//kiểm tra nếu thêm mới thành công thì thông báo
		//echo $last_id;
		if($db_ex->total > 0){
			echo "<script>alert('Cập nhật thành công')</script>";
		} else {
			echo "<script>alert('Cập nhật không thành công')</script>";
		}
		redirect($fs_redirect);
		exit();		
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
$myform->addFormname("add");//(tên form name)
$myform->checkjavascript();


$fs_errorMsg .= $myform->strErrorField;
$fs_errorMsg .= $myform->strErrorField;
?>

</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top($fs_title)?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
	<p align="center" style="padding-left:10px;">
	<?
	$db_news		=	new db_query("SELECT * FROM news_post WHERE new_id=" . $new_id);
	if($row		=	mysqli_fetch_assoc($db_news->result)){		
	}
	unset($db_news);
	$form = new form();
	$form->create_form("add", $_SERVER["REQUEST_URI"], "POST", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>
		<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
		<?=$form->errorMsg($fs_errorMsg)?>
		<?=$form->text("Tiêu đề tin", "new_title", "new_title", $row['new_title'], "Tiêu đề của tin đăng", 1, 400, "", 255, "", "", "")?>
		<?=$form->select("Danh mục tin", "new_cat_id", "new_cat_id", $arr_cat, $row['new_cat_id'], "Chọn danh mục đăng tin", 1, 200, "" )?>
		<?=$form->text("Nguồn tin", "new_author", "new_author", $row['new_author'], "Nguồn gốc của tin đăng", 0, 400, "", 255, "", "", "")?>
		<?=$form->getFile("Ảnh tiêu đề", "new_picture", "new_picture", "hình ảnh đại diện của tin đăng",  0, 30, "", "") ?>
		<?=$form->textarea("Tóm tắt", "new_teaser", "new_teaser", $row['new_teaser'], "Tóm tắt nội dung", 1, 400, 100, "", "<span style='padding-left: 10px;'>Tóm tắt tối đa 300 ký tự</span>")?>
		<!--nội dung tin-->
	<?=$form->close_table();?>
	<?=$form->create_table();?>	
	<?=$form->wysiwyg("Nội dung tin", "new_content", $row['new_content'], "../../resource/wysiwyg_editor/","80%",450)?>
	<?=$form->close_table();?>
	<?=$form->create_table();?>
	
	<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", 'style="background:url(' . $fs_imagepath . 'button_1.gif) no-repeat"' . $form->ec . 'style="background:url(' . $fs_imagepath . 'button_2.gif)"', "");?>
	<?=$form->hidden("action", "action", "execute", "");?>
	<?
	$form->close_table();
	$form->close_form();
	unset($form);
	?>
	</p>
<? /*------------------------------------------------------------------------------------------------*/ ?><?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>
