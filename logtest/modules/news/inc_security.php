<?

//file inc_security trong module nào cũng phải có.
//includes file kiểm tra bảo mật và include các file cần thiết như class,function.
require_once("../../resource/security/security.php");
//$module_id	là id của module ở trong bảng module
$module_id	= 42;
//$module_name		là tiêu đề của module
$module_name= "Tin ";
//$fs_errorMsg		= "";
$img_path			= "../../../pictures/news/";
$limit_size			= 750;
$extension_list	= "jpg,gif,png";
//Check user login... (gọi ra hàm kiểm tra bảo mật đăng nhập rồi hay chưa)
checkLogged();
//Check access module...(kiểm tra quyền xem có được phép truy cập module này hay không)
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

//Declare prameter when insert data
$fs_table		= "news_post"; //tên bảng
$id_field		= "new_id";//khóa chính trong bảng
$name_field		= "new_title";//trường đại diện
$break_page		= "{---break---}";
//$array_config		= array("image"=>1,"upper"=>1,"order"=>1,"description"=>1);
//Mảng array categories cha
	$db_select_cat		=	new db_query("SELECT * 
                                      FROM categories_multi
												  WHERE (cat_parent_id > 0) AND (cat_active=1) AND (cat_type='news') ORDER BY cat_order ASC");
	$arr_cat					=	array();
	while($cat_vl			=	mysqli_fetch_assoc($db_select_cat->result)){
		$arr_cat[$cat_vl['cat_id']] = $cat_vl['cat_name'];
	}
 unset($db_select_cat); 
?>