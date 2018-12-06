<?
$module_id = 2;
//check security...
require_once("../../../../../classes/database.php");
require_once("../../../security/functions.php");
require_once("../../../../../functions/functions.php");
@session_start();
checkLogged();
$username = getValue("userlogin","str","SESSION","");
$password = getValue("password","str","SESSION","");
$db_getright = new db_query("SELECT * 
							 FROM admin_user
							 WHERE adm_loginname='" . $username . "' AND adm_password='" . $password . "' AND adm_active=1 AND adm_delete = 0");
//Check xem user co ton tai hay khong
if (mysql_num_rows($db_getright->result) == 0){
	redirect("../deny.htm");
}
?>