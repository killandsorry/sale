<?
require_once("../../resource/security/security.php");
$module_id		= 11;
$module_name	= "Logs";
$msgError		= "";
checkLogged();
//Declare prameter when insert data
$fs_table		= "logs_php";
$id_field		= "log_id";
$name_field		= "log_title";

?>