<?
require_once("../../resource/security/security.php");

$module_id = 5;
$fs_fieldupload      = "con_background_img";
$fs_fieldupload2		= "con_background_homepage";

$fs_filepath			= "../../../pictures/background/";
$fs_extension			= "gif,jpg,jpe,jpeg,png";
$fs_filesize			= 400;
//Check user login...
checkLogged();

//Declare prameter when insert data
$fs_table				= "configuration";
$id_field				= "con_id";

?>