<?
//file inc_security trong module nào cũng phải có.
//includes file kiểm tra bảo mật và include các file cần thiết như class,function.
require_once("../../resource/security/security.php");
//$module_id	là id của module ở trong bảng module
$module_id	= 2;
//$module_name		là tiêu đề của module
$module_name= "Tỉnh/Thành phố";
//Check user login... (gọi ra hàm kiểm tra bảo mật đăng nhập rồi hay chưa)
checkLogged();
//Declare prameter when insert data
$fs_table		= "city"; //tên bảng
$id_field		= "cit_id";//khóa chính trong bảng
$name_field		= "cit_name";//trường đại diện
$break_page		= "{---break---}";
$fs_errorMsg	= '';


?>