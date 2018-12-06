<?

function checkDkim(){
	return '';
}
function checkLogin($username, $password){
	$username	= replaceMQ($username);
	$password	= replaceMQ($password);
	$adm_id		= 0;
	$db_check	= new db_query("SELECT adm_id
										 FROM admin_user
										 WHERE adm_loginname = '" . $username . "' AND adm_password = '" . md5($password) . "' AND adm_active = 1 AND adm_delete = 0");
	if(mysqli_num_rows($db_check->result) > 0){
		$check	= mysqli_fetch_assoc($db_check->result);
		$adm_id	= $check["adm_id"];
		$db_check->close();
		unset($db_check);
		return $adm_id;
	}
	else{
		$db_check->close();
		unset($db_check);
		return 0;
	}
}

function get_curent_language(){
	$db_current_language = new db_query("SELECT lang_id
										 FROM admin_user
										 WHERE adm_loginname='" . $_SESSION["userlogin"] . "' AND adm_password='" . $_SESSION["password"] . "' AND adm_active=1 AND adm_delete = 0");
	if ($row=mysqli_fetch_assoc($db_current_language->result)){
		$db_current_language->close();
		unset($db_current_language);
		return $row["lang_id"];
	}
	else{
		return "";
	}
}
function get_curent_path(){
	$db_current_path = new db_query("SELECT lang_path
										 FROM languages
										 WHERE lang_id=" . intval(get_curent_language()) . "");
	if ($row=mysqli_fetch_assoc($db_current_path->result)){
		$db_current_path->close();
		unset($db_current_path);
		return $row["lang_path"];
	}
	else{
		return "";
	}
}
function checkaccessmodule($module_id){
	checkloged();
	$userlogin	= getValue("userlogin", "str", "SESSION", "", 1);
	$password	= getValue("password", "str", "SESSION", "", 1);
	$lang_id		= getValue("lang_id", "int", "SESSION", 1);
	$db_getright = new db_query("SELECT *
								 FROM admin_user
								 WHERE adm_loginname='" . $userlogin . "' AND adm_password='" . $password . "' AND adm_active=1 AND adm_delete = 0");
	//Check xem user co ton tai hay khong
	if ($row = mysqli_fetch_assoc($db_getright->result)){
		//Neu column adm_isadmin = 1 thi cho access
		if ($row['adm_isadmin'] == 1) {
			$db_getright->close();
			unset($db_getright);
			return 1;
		}
	}
	//Ko co thi` fail luon
	else{
		$db_getright->close();
		unset($db_getright);
		return 0;
	}
	$db_getright->close();
	unset($db_getright);

	//check user
	$db_getright = new db_query("SELECT *
								 FROM admin_user, admin_user_right, modules
								 WHERE adm_id = adu_admin_id AND mod_id = adu_admin_module_id AND
								 adm_loginname='" . $userlogin . "' AND adm_password='" . $password . "' AND adm_active=1 AND adm_delete = 0
								 AND mod_id = " . $module_id);

	if ($row=mysqli_fetch_assoc($db_getright->result)){
		$db_getright->close();
		unset($db_getright);
		return 1;
	}
	else{
		$db_getright->close();
		unset($db_getright);
		return 0;
	}
}
function checkloged(){
	return true;
}
function str_debase($encodedStr="",$type=0)
{
  $returnStr = "";
  $encodedStr = str_replace(" ","+",$encodedStr);
	if(!empty($encodedStr)) {
		 $dec = str_rot13($encodedStr);
		 $dec = base64_decode($dec);
		$returnStr = $dec;
	}
  return $returnStr;
}

?>