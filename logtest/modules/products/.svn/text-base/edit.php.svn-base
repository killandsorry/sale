<?
include("inc_security.php");
checkAddEdit("add");
//Khai báo biến khi thêm mới
$fs_redirect		=	base64_url_decode(getValue("url", "str", "GET", base64_url_encode("listing.php")));
$fs_title			= "Tin đăng"; //tên tiêu đề bân trái
$fs_action			= getURL();//reload lại
$fs_errorMsg		= "";//lỗi trả về nếu thêm mới không đc
$pro_date			= time();//lấy ngày hiện hành
$pro_rewrite 		= getValue("pro_rewrite","str","POST","");
$record_id			= getValue("record_id");
$pro_rewrite 		= createTitle($pro_rewrite);
$pro_sale			= 1;
$new_active			=	0;

$myform	=	new generate_form();//tạo form mới
$myform->removeHTML(0);
//thêm các trường vào form
//$myform->add("pro_rewrite","pro_rewrite",0,1,"",1,translate_text("Vui lòng nhập Url Rewrite"),0,translate_text("Trùng Url Rewrite sản phẩm"));
$myform->add("pro_name", "pro_name", 0, 0, "", 1, "Vui lòng nhập tiêu đề tin", 0, "Trùng tiêu đề tin"); //tiêu đề tin
$myform->add("pro_cat_id", "pro_cat_id", 1, 0, 0, 1, "Vui lòng chọn danh mục đăng tin", 0, ""); //danh mục cấp cha
//$myform->add("pro_group_type", "pro_group_type", 1, 0, 0, 1, "Vui lòng chọn nhóm thuốc", 0, "");
$myform->add("pro_date", "pro_date", 1, 1, 0, 0, "", 0, ""); //Ngay dang
$myform->add("pro_price", "pro_price", 3, 0, 0, 0, "", 0, "");
$myform->add("pro_sale", "pro_sale", 1, 1, 0, 0, "", 0, "");
//$myform->add("pro_price_market", "pro_price_market", 3, 0, 0, 0, "", 0, "");
//$myform->add("pro_sdk", "pro_sdk", 0, 0, "", 0, "", 0, "");
//$myform->add("pro_code", "pro_code", 0, 0, "", 0, "", 0, "");
//$myform->add("pro_type", "pro_type", 1, 0, 0, 0, "", 0, "");
//$myform->add("pro_cou_id", "pro_cou_id", 1, 0, 0, 0, "", 0, "");
//$myform->add("pro_handung", "pro_handung", 0, 0, "", 0, "", 0, "");
$myform->add("pro_formula", "pro_formula", 0, 0, "", 0, "", 0, "");
$myform->add("pro_deportment", "pro_deportment", 0, 0, "", 0, "", 0, "");
$myform->add("pro_assign", "pro_assign", 0, 0, "", 0, "", 0, "");
//$myform->add("pro_using", "pro_using", 0, 0, "", 0, "", 0, "");
$myform->add("pro_dosage", "pro_dosage", 0, 0, "", 0, "", 0, "");
$myform->add("pro_contraindications", "pro_contraindications", 0, 0, "", 0, "", 0, "");
$myform->add("pro_note", "pro_note", 0, 0, "", 0, "", 0, "");
$myform->add("pro_preservation", "pro_preservation", 0, 0, "", 0, "", 0, "");
$myform->add("pro_production", "pro_production", 0, 0, "", 0, "", 0, "");
$myform->add("pro_description", "pro_description", 0, 0, "", 0, "", 0, "");
//Add table insert data
$myform->addTable($fs_table);
$errorMsg	= "";

$content		=	getValue("pro_content", "str", "POST", "");
//Get action variable for add new data
$action				= getValue("action", "str", "POST", ""); //kiểm tra xem form có đc submit đi không
//Check $action for insert new data
if($action == "execute"){

	//upload ảnh lên thư mục
	$upload_pic = new upload("pro_picture", $img_path, $extension_list, $limit_size);
	if ($upload_pic->file_name != ""){
		$picture = $upload_pic->file_name;
		resize_image($img_path, $picture , 90, 90, 75);
		$myform->add("pro_picture", "picture", 0, 1, "", 0, "", 0, "");
	}

	/**
	 * Thêm 3 ảnh detail nếu có
	 */

 	for($i = 1; $i < 4; $i++){
 		if(isset($_FILES['pro_pic_' . $i]['name']) && $_FILES['pro_pic_' . $i]['name'] != ''){
			$upload_img	= new upload('pro_pic_' . $i, $img_path, $extension_list, $limit_size);
			if($upload_img->show_warning_error() == ''){
				$name		= 'file_name_' . $i;
				$$name					= $upload_img->file_name;
				$myform->add('pro_pic_' . $i, 'file_name_' . $i, 0, 1, '', 1, 'Bạn chưa nhập ảnh chi tiết cho sản phẩm', 0, '');
			}else{
				$fs_errorMsg	.= $upload_img->show_warning_error() . '<br>';
			}
			unset($upload_img);
 		}
 	}

 	/**
 	 * Thêm 2 ảnh hướng dẫn sử dụng nếu có
 	 */
	for($j = 1; $j < 3; $j++){
 		if(isset($_FILES['pro_pic_intro_' . $j]['name']) && $_FILES['pro_pic_intro_' . $j]['name'] != ''){
			$upload_img	= new upload('pro_pic_intro_' . $j, $img_path, $extension_list, $limit_size);
			if($upload_img->show_warning_error() == ''){
				$name		= 'file_name_' . $j;
				$$name					= $upload_img->file_name;
				$myform->add('pro_pic_intro_' . $j, 'file_name_' . $j, 0, 1, '', 0, '', 0, '');
			}else{
				$fs_errorMsg	.= $upload_img->show_warning_error() . '<br>';
			}
			unset($upload_img);
 		}
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
	if($fs_errorMsg == ""){
		//if(checkRewriteUrl($pro_rewrite, $record_id) > 0) $fs_errorMsg .= "Url Rewrite đã được nhập";
	}
	//thực hiện thêm mới nếu không có lỗi
	if($fs_errorMsg == ""){
		//không remove ký tự html
		$myform->removeHTML(0);
		//echo $myform->generate_insert_SQL();
		$db_ex 	= new db_execute($myform->generate_update_SQL("pro_id", $record_id));
		$pro_cat_id		=	getValue("pro_cat_id", "int", "POST");
		$rew_name 							= getValue("rew_name","str","POST","");
		deleteRewrite($pro_rewrite, $rew_name);
		if($record_id > 0)	createRewriteUrl($pro_rewrite, array("iPro" => $record_id, "module" => 'thuoc',"iCat" => $pro_cat_id), $record_id);
		$myform->evaluate();
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
							$sl	= ($pro_cat_id == $catchild['cat_id'])? 'selected' : '';
							echo '<option '. $sl .' value="'. $catchild['cat_id'] .'">'. $catchild['cat_name'] .'</option>';
						}
						echo '</optgroup>';
					}
					?>
				</select>
			</td>
		</tr>
		<?//=$form->select("Danh mục tin", "pro_cat_id", "pro_cat_id", $arr_cat, $pro_cat_id, "Chọn danh mục đăng tin", 1, 200, "" )?>
		<?=$form->text("Tên sản phẩm", "pro_name", "pro_name", $pro_name, "Tiêu đề của tin đăng", 1, 400, "", 255, "", '', "")?>
		<?//=$form->select("Nhóm sản phẩm", "pro_group_type", "pro_group_type", get_group_type(), $pro_group_type, "", 1, 200, "" )?>
		<?//=$form->text("Url Rewrite", "pro_rewrite", "pro_rewrite", $pro_rewrite, "", 1, 400, "", 255, "", 'onblur="ajaxCheckRewrite(\'#pro_rewrite_check\',{title:$(this).val(),record_id:0})" onkeypress="ajaxCheckRewrite(\'#pro_rewrite_check\',{title:$(this).val(),record_id:0})"', ' <span id="pro_rewrite_check"></span>')?>
		<?=$form->text("Giá bán", "pro_price", "pro_price", $pro_price, "", 1, 100, "", 255, "", "", "")?>

		<?//=$form->text("Giá thị trường", "pro_price_market", "pro_price_market", $pro_price_market, "", 1, 100, "", 255, "", "", "")?>
		<?//=$form->text("Số đăng ký", "pro_sdk", "pro_sdk", $pro_sdk, "", 1, 100, "", 255, "", "", "")?>
		<?//=$form->text("Mã vạch", "pro_code", "pro_code", $pro_code, "", 1, 100, "", 255, "", "", "")?>
		<?//=$form->select("Dạng bào chế", "pro_type", "pro_type", get_type_product(), $pro_type, "", 1, 200, "" )?>
		<?//=$form->select("Nước sản xuất", "pro_cou_id", "pro_cou_id", get_coutries(), $pro_cou_id, "", 1, 200, "" )?>
		<tr>
			<td class="form_name">Ảnh tiêu đề cũ:</td>
			<td class="form_text"><img src="<?=$img_path . "small_" . $pro_picture?>" /></td>
		</tr>
		<?=$form->getFile("Ảnh sản phẩm 1", "pro_picture", "pro_picture", "hình ảnh đại diện của tin đăng",  0, 30, "", "") ?>
		<tr>
			<td class="form_name">Ảnh miêu tả cũ:</td>
			<td class="form_text">
				<div id="pic_detail">
				<?
				for($i = 1; $i < 4; $i++){
					$name	= 'pro_pic_' . $i;
					if($$name != ''){
						echo '<span class="img"><img src="'. $img_path . "" . $$name .'" /><i class="delimg">ảnh '.$i.'</i></span>';
					}
				}
				?>
				</div>
			</td>
		</tr>
		<?=$form->getFile("Ảnh miêu tả 1", "pro_pic_1", "pro_pic_1", "",  0, 30, "", "") ?>
		<?=$form->getFile("Ảnh miêu tả 2", "pro_pic_2", "pro_pic_2", "",  0, 30, "", "") ?>
		<?=$form->getFile("Ảnh miêu tả 3", "pro_pic_3", "pro_pic_3", "",  0, 30, "", "") ?>
		<tr>
			<td class="form_name">Ảnh hướng dẫn sử dụng cũ:</td>
			<td class="form_text">
				<div id="pic_detail">
				<?
				for($i = 1; $i < 3; $i++){
					$name	= 'pro_pic_intro_' . $i;
					if($$name != ''){
						echo '<span class="img"><img src="'. $img_path . "" . $$name .'" /><i class="delimg">ảnh '.$i.'</i></span>';
					}
				}
				?>
				</div>
			</td>
		</tr>
		<?=$form->getFile("Ảnh hướng dẫn sử dụng 1", "pro_pic_intro_1", "pro_pic_intro_1", "",  0, 30, "", "") ?>
		<?=$form->getFile("Ảnh hướng dẫn sử dụng 2", "pro_pic_intro_2", "pro_pic_intro_2", "",  0, 30, "", "") ?>
		<?//=$form->text("Hạn dùng", "pro_handung", "pro_handung", $pro_handung, "", 1, 200, "", 255, "", "", "")?>
		<?//=$form->textarea("<b>Cách dùng</b>", "pro_using", "pro_using", $pro_using, "", 1, 400, 100, '', "",'','')?>
		<?=$form->textarea("Công dụng", "pro_assign", "pro_assign", $pro_assign, "", 1, 400, 100, '', "",'','')?>
		<? /* ?>
		<?=$form->textarea("<b>Công thức</b>", "pro_formula", "pro_formula", $pro_formula, "", 1, 400, 100, '', "",'','')?>
		<?=$form->textarea("<b>Tác dụng</b>", "pro_deportment", "pro_deportment", $pro_deportment, "", 1, 400, 100, '', "",'','')?>
		<?=$form->textarea("<b>Liều dùng</b>", "pro_dosage", "pro_dosage", $pro_dosage, "", 1, 400, 100, '', "",'','')?>
		<?=$form->textarea("<b>Chống chỉ định</b>", "pro_contraindications", "pro_contraindications", $pro_contraindications, "", 1, 400, 100, '', "",'','')?>
		<?=$form->textarea("<b>Lưu ý</b>", "pro_note", "pro_note", $pro_note, "", 1, 400, 100, '', "",'','')?>
		<?=$form->textarea("<b>Bảo quản</b>", "pro_preservation", "pro_preservation", $pro_preservation, "", 1, 400, 100, '', "",'','')?>
		<?=$form->textarea("<b>Sản xuất</b>", "pro_production", "pro_production", $pro_production, "", 1, 400, 100, '', "",'','')?>
		<? //*/?>
		<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", 'style="background:url(' . $fs_imagepath . 'button_1.gif) no-repeat"' . $form->ec . 'style="background:url(' . $fs_imagepath . 'button_2.gif)"', "");?>
		<?=$form->hidden("action", "action", "execute", "");?>
		<tr>
			<td colspan="2">
				<?=$form->wysiwyg("Thông tin","pro_description", $pro_description,"../../resource/wysiwyg_editor/","100%","600");?>
			</td>
		</tr>

		<?=$form->hidden("rew_name", "rew_name", $pro_rewrite, "");?>
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