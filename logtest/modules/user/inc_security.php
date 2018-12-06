<?
require_once("../../resource/security/security.php");
$module_id		= 15;
$module_name	= "User";
$msgError		= "";
checkLogged();
//Declare prameter when insert data
$fs_table		= "user";
$id_field		= "use_id";
$name_field		= "use_fullname";

?>