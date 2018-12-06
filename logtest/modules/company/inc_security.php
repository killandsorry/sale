<?
require_once("../../resource/security/security.php");
$module_id		= 4;
$module_name	= "Company";
$msgError		= "";
checkLogged();
//Declare prameter when insert data
$fs_table		= "company";
$id_field		= "com_id";
$name_field		= "com_name";

?>