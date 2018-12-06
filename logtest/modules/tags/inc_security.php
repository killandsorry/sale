<?
$module_id 			= 13;
$module_name		= "Quản lý danh mục";

$fs_table			= "tags";
$id_field			= "tag_id";
$name_field			= "tag_name";
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
   'vl' => 'Việc làm',
   'new' => 'Tin tức'
);
?>