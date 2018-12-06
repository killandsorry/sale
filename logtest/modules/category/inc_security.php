<?
$module_id 			= 1;
$module_name		= "Quản lý danh mục";

$fs_table			= "category";
$id_field			= "cat_id";
$name_field			= "cat_name";
$fs_errorMsg		= "";
$fs_filepath		= "../../../pictures/category/";
$limit_size			= 750;
$extension_list	= "jpg,gif,png";
$add					= "add.php";
$listing				= "listing.php";
//check security...
require_once("../../resource/security/security.php");
//Check user login...
checkLogged();

$arrayCategoryType	= array(
   'product' => 'Sản phẩm',
   'new' => 'Tin tức'
);
?>