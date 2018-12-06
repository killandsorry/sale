<?
include("inc_security.php");
checkAddEdit("add");
//Khai báo biến khi thêm mới
$fs_redirect		= base64_url_decode(getValue("url", "str", "GET", base64_url_encode("listing.php")));
$fs_title			= "Sửa sản phẩm"; //tên tiêu đề bân trái
$fs_action			= getURL();//reload lại
$fs_errorMsg		= "";//lỗi trả về nếu thêm mới không đc
$pro_date			= time();//lấy ngày hiện hành
$errorMsg	= "";

$myform	=	new generate_form();//tạo form mới
$myform->removeHTML(0);
$myform->add("pro_name", "pro_name", 0, 0, "", 1, "Vui lòng nhập tiêu đề sản phẩm", 1, "Trùng tiêu đề sản phẩm"); //tiêu đề tin
$myform->add("pro_cat_id", "pro_cat_id", 1, 0, 0, 1, "Vui lòng chọn danh mục đăng tin", 0, ""); //danh mục cấp cha
$myform->add("pro_date", "pro_date", 1, 1, 0, 0, "", 0, ""); //Ngay dang
$myform->add("pro_price", "pro_price", 3, 0, 0, 0, "", 0, "");
$myform->add("pro_teaser", "pro_teaser", 0, 0, "", 1, "Nhập vào công dụng của thuốc", 0, "");
$myform->add("pro_content", "pro_content", 0, 0, "", 0, "Nhập vào thông tin chi tiết thuốc", 0, "");
$myform->add("pro_quantity", "pro_quantity", 1, 0, "", 0, "", 0, "");
$myform->addTable($fs_table);


$content		= getValue("pro_content", "str", "POST", "");
$action		= getValue("action", "str", "POST", ""); //kiểm tra xem form có đc submit đi không
if($action == "execute"){
	//upload ảnh lên thư mục
	$upload_pic = new upload("pro_image", $img_path, $extension_list, $limit_size);
	$picture = $upload_pic->file_name;
	if($picture != ""){
		resize_image($img_path, $picture , 90, 90, 75);
		$myform->add("pro_image", "picture", 0, 1, "", 0, "", 0, "");
	}

	/**
	 * Thêm 2 ảnh detail nếu có
	 */

 	for($i = 1; $i < 3; $i++){
 		if(isset($_FILES['pro_image_' . $i]['name']) && $_FILES['pro_image_' . $i]['name'] != ''){
			$upload_img	= new upload('pro_image_' . $i, $img_path, $extension_list, $limit_size);
			if($upload_img->show_warning_error() == ''){
				$name		= 'file_name_' . $i;
				$$name					= $upload_img->file_name;
				$myform->add('pro_image_' . $i, 'file_name_' . $i, 0, 1, '', 1, 'Bạn chưa nhập ảnh chi tiết cho sản phẩm', 0, '');
			}else{
				$fs_errorMsg	.= $upload_img->show_warning_error() . '<br>';
			}
			unset($upload_img);
 		}
 	}


 	/**
 	 * Thêm 1 ảnh hướng dẫn sử dụng nếu có
 	 */
	if(isset($_FILES['pro_image_using']['name']) && $_FILES['pro_image_using']['name'] != ''){
		$upload_img	= new upload('pro_image_using', $img_path, $extension_list, $limit_size);
		if($upload_img->show_warning_error() == ''){
			$name		= 'file_name_' . $j;
			$$name					= $upload_img->file_name;
			$myform->add('pro_image_using', 'file_name_' . $j, 0, 1, '', 0, '', 0, '');
		}else{
			$fs_errorMsg	.= $upload_img->show_warning_error() . '<br>';
		}
		unset($upload_img);
	}
	//$html		=	new html_cleanup($content);
	//$html->clean();
	//$content	=	$html->output_html;
	//$content	=	str_replace("http://%5C%22", "", $content); //$cla_des là nội dung của tin đăng khi đã loại bỏ các ký tự trước hình ảnh
	//$content	=	str_replace("%5C%22", "", $content);
	//$myform->add("pro_content", "content", 0, 1, "", 0, "", 0, ""); //nội dung bài viết

	//check kiểm tra có lỗi hay không
	$fs_errorMsg .= $upload_pic->show_warning_error();
	$fs_errorMsg .= $myform->checkdata();

	//thực hiện thêm mới nếu không có lỗi
	if($fs_errorMsg == ""){
		//không remove ký tự html
		$myform->removeHTML(0);
		//echo $myform->generate_insert_SQL();
		$db_ex 	= new db_execute($myform->generate_update_SQL("pro_id", $record_id));
		$myform->evaluate();
		generateTagsProduct($last_id, $pro_name . ","
											 . $pro_teaser . ","
											 . $pro_content . ","
											 );
		//die($myform->generate_insert_SQL());
		//kiểm tra nếu thêm mới thành công thì thông báo
		if($record_id > 0){
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
<style>
#pic_detail{
	height: 100px; overflow: hidden;
}
#pic_detail img{
	width: 100px; margin-right: 20px;
}
#pic_detail .img{
	position: relative;display: inline-block;
}
#pic_detail .delimg{
	background-color: #fff;  color: #f00;   cursor: pointer;   font-style: normal;   padding: 0 3px 3px 5px;   position: absolute;   right: 21px;   top: 1px;
}
</style>
<?
//add form for javacheck
$myform->addFormname("add");//(tên form name)
$myform->checkjavascript();

//chuyển các trường thành biến để lấy giá trị thay cho dùng kiểu getValue
$myform->evaluate();

$fs_errorMsg .= $myform->strErrorField;
$fs_errorMsg .= $myform->strErrorField;
//lay du lieu cua record can sua doi
$db_data 	= new db_query("SELECT * FROM " . $fs_table . " WHERE " . $id_field . " = " . $record_id);
if($row 		= mysql_fetch_assoc($db_data->result)){
	foreach($row as $key=>$value){
		if($key!='lang_id' && $key!='admin_id') $$key = $value;
		if(isset($_POST[$key])) $$key = $_POST[$key];
	}
}else{
		exit();
}
?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top($fs_title)?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
	<p align="center" style="padding-left:10px;">
	<?
	$form = new form();
	$form->create_form("add", $_SERVER["REQUEST_URI"], "POST", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table(3,3,'width="100%"');
	?>
		<?=$form->text_note('Những ô có dấu sao (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
		<?=$form->errorMsg($fs_errorMsg)?>
		<tr>
			<td class="form_name"><font class="form_asterisk">* </font>Danh mục thuốc</td>
			<td class="form_text">
				<select class="pro_cat_id" id="pro_cat_id" name="pro_cat_id">
					<option>-- Chọn danh mục thuốc --</option>
					<?
					foreach($arrayCateoty as $k => $value){
						echo '<optgroup label="'. $value['name'] .'">';
						foreach($value['list'] as $catid => $catchild){
							echo '<option value="'. $catchild['cat_id'] .'">'. $catchild['cat_name'] .'</option>';
						}
						echo '</optgroup>';
					}
					?>
				</select>
			</td>
		</tr>
		<?=$form->text("Tên sản phẩm", "pro_name", "pro_name", $pro_name, "Tiêu đề của sản phẩm", 1, 400, "", 255, "", 'onblur="$(\'#pro_rewrite\').val($(this).val())"', "")?>
		<?=$form->text("Giá bán", "pro_price", "pro_price", $pro_price, "", 1, 100, "", 255, "", "", "")?>
		<?=$form->getFile("Ảnh sản phẩm", "pro_image", "pro_image", "",  1, 30, "", "") ?>
		<?=$form->getFile("Ảnh sản phẩm 1", "pro_image_1", "pro_image_1", "",  0, 30, "", "") ?>
		<?=$form->getFile("Ảnh sản phẩm 2", "pro_image_2", "pro_image_2", "",  0, 30, "", "") ?>
		<?=$form->getFile("Ảnh hướng dẫn sử dụng", "pro_image_using", "pro_image_using", "",  0, 30, "", "") ?>
		<?=$form->textarea('Công dụng', 'pro_teaser', 'pro_teaser', $pro_teaser, 'Công dụng sản phẩm', 1, 500, 100,'công dụng của thuốc không quá 255 ký tự')?>

		<tr>
			<td colspan="2">
				<?=$form->wysiwyg("Chi tiết sản phẩm","pro_content", $pro_content,"../../resource/wysiwyg_editor/","100%","600");?>
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
<? /*------------------------------------------------------------------------------------------------*/ ?><?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>