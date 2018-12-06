<?
$module_id 			= 9;
$module_name		= "Quản lý danh mục";

$fs_table			= "blog_cat";
$id_field			= "bc_id";
$name_field			= "bc_name";
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

?>