<?
class user{
	var $logged = 0;
	var $login_name;
	var $use_name;
	var $password;
	var $u_id = -1;
	var $use_security;
	var $use_admin = 0;
	var $use_check_friend = 0;
	var $useField = array();
	var $use_avatar = '';
	var $use_birthday = 0;
	var $use_address = '';
	var $use_email = '';
   var $use_phone = '';
	var $use_com_id = 0;
	var $key = "fjldsjrsdfewj323@@4343";
	/*
	init class
	login_name : ten truy cap
	password  : password (no hash)
	level: nhom user; 0: Normal; 1: Admin (default level = 0)
	*/
	function user($login_name="",$password=""){
		$checkcookie=0;
		$this->logged = 0;
		if ($login_name==""){
			if (isset($_COOKIE["lglocal"])) $login_name = $_COOKIE["lglocal"];
		}
		if ($password==""){
			if (isset($_COOKIE["wvl_rand"])) $password = $_COOKIE["wvl_rand"];
			$checkcookie=1;
		}
		else{
			//remove \' if gpc_magic_quote = on
			$password = str_replace("\'","'",$password);
		}

		if ($login_name=="" && $password=="") return;
		
		$field_where = "use_login_name";
		//nếu là email thì query ở email
		if (filter_var($login_name, FILTER_VALIDATE_EMAIL)) {
		    $field_where = "use_email";
		}
		
		if (is_numeric(trim($login_name))) {
		    $field_where = "use_phone";
		}

		$db_user = new db_query("SELECT *
										 FROM user
										 WHERE $field_where = '" . $this->removequote($login_name) . "' LIMIT 1");

		if ($row=mysqli_fetch_assoc($db_user->result)){
			//kiem tra password va use_active
			if($checkcookie == 0)	$password = md5($password . $row["use_secrest"]);
			if (($password == $row["use_password"] && $row["use_active"] == 1)) {
				$this->logged				= 1;
				$this->login_name			= trim($row["use_login_name"]);
				$this->use_name			= trim($row["use_fullname"]);
				$this->password			= $row["use_password"];
				$this->u_id					= intval($row["use_id"]);
				$this->use_fullname		= trim($row["use_fullname"]);
				$this->useField			= $row;
				$this->use_avatar			= $row['use_avatar'];
				$this->use_login			= trim($row['use_login_name']);
				$this->use_email			= trim($row['use_email']);
            $this->use_phone			= trim($row['use_phone']);
				$this->use_check_friend			= $row['use_check_friend'];
				$this->use_birthday		= $row['use_birthday'];
				$this->use_address		= $row['use_address'];
				$this->use_com_id		= $row['use_com_id'];
			}
		}
		unset($db_user);


	}
	/*
	Ham lay truong thong tin ra
	*/
	function row($field){
		if(isset($this->useField[$field])){
			return $this->useField[$field];
		}else{
			return '';
		}
	}
	/*
	save to cookie
	time : thoi gian save cookie, neu = 0 thi` save o cua so hien ha`nh
	*/
	function savecookie($time=0){
		if ($this->logged!=1) return false;

		if ($time > 0){
			setcookie("lglocal",$this->login_name,time()+$time,"/");
			setcookie("username",substr($this->login_name, 0, strpos($this->login_name, '@')),time()+$time,"/");
			setcookie("wvl_rand",$this->password,time()+$time,"/");
			setcookie("password",md5(rand(111111, 999999)),time()+$time,"/");
		}else{
			setcookie("lglocal",$this->login_name,null,"/");
			setcookie("username",substr($this->login_name, 0, strpos($this->login_name, '@')),null,"/");
			setcookie("wvl_rand",$this->password,null,"/");
			setcookie("password",md5(rand(111111, 999999)),null,"/");
		}
	}
	
	function setCookieFcm($fcm_id){
		$time = 86400*364;
		$array_data = array("fcm_id" => $fcm_id,"use_id" => $this->u_id);
		ksort($array_data);
		$data = json_encode($array_data);
		$checksum = md5($this->key . "|" . $data);
		$array_data = array("dt" => $data,"cs" => $checksum);
		$data = base64_encode(json_encode($array_data));
		setcookie("SESSTON",$data,time()+$time,"/");
	}
	
	function getFcmId(){
		$cookie = isset($_COOKIE["SESSTON"]) ? $_COOKIE["SESSTON"] : '';
		if($cookie == "") return 0;
		$cookie = json_decode(base64_decode($cookie),true);
		if(!isset($cookie["dt"]) && !isset($cookie["cs"])) return 0;
		$data 	 = $cookie["dt"];
		$checksum = $cookie["cs"];
		$new_checksum = md5($this->key . "|" . $data);
		if($checksum != $new_checksum) return 0;
		$data = json_decode($data,true);
		if(is_array($data)){
			return $data;	
		}else{
			return 0;
		}
	}

	/*
		login with facebook
		kiểm tra user có tồn tại không
		-nếu tồn tại thì kiểm tra đã active chưa
			+active rồi thì face đăng nhập luôn
			+chưa active thì update lại active = 1
		-nếu chưa tồn tại thì thêm user mới và active luôn
	*/
	function login_facebook($data = array()){
		$email 			= isset($data['email'])? $data['email'] : '';
		$name 			= isset($data['name'])? $data['name'] : '';
		$gender 			= isset($data['gender'])? $data['gender'] : '';
		$social_id 		= isset($data['social_id'])? $data['social_id'] : '';
		$access_token 	= isset($data['access_token'])? $data['access_token'] : '';
		$social_type 	= isset($data['social_type'])? $data['social_type'] : '';
		$avatar			= isset($data['avatar'])? $data['avatar'] : '';
		$social_profile	= isset($data['social_profile'])? $data['social_profile'] : '';
      $type          = isset($data['type'])? $data['type'] : 0;
      if($type != 1) $type = 0;

		if($email == '' || $social_id == '') return 0;

		$db_checkuser	= new db_query("	SELECT * FROM user
													WHERE use_login_name ='". replaceMQ($email) ."' lIMIT 1",
													__FILE__ . " Line: " . __LINE__);
		if($row	= mysqli_fetch_assoc($db_checkuser->result)){
			// đã có user
			if($row['use_active'] == 0){
				// chưa actieve
				$db_upactive	= new db_execute("	UPDATE user SET use_active = 1,use_type = ". $type ."
																WHERE use_id =". $row['use_id'],
																__FILE__ . " Line: " . __LINE__);
				unset($db_upactive);
			}

			$this->logged				= 1;
			$this->login_name			= $row["use_login_name"];
			$this->use_name			= $row["use_fullname"];
			$this->password			= $row["use_password"];
			$this->u_id					= intval($row["use_id"]);
			$this->use_fullname		= $row["use_fullname"];
			$this->useField			= $row;
			$this->use_avatar			= $row['use_avatar'];
			$this->use_login			= $row['use_login_name'];
			$this->use_email			= $row['use_login_name'];
			$this->use_check_friend			= $row['use_check_friend'];
			setcookie("lglocal", $row["use_login_name"], (time() + (86400 * 30)), "/");
			setcookie("username", substr($row['use_login_name'], 0, strpos($row['use_login_name'], '@')), (time() + (86400 * 30)), "/");
			setcookie("wvl_rand", $row["use_password"], (time() + (86400 * 30)), "/");
			setcookie("password", md5(rand(111111, 999999)), (time() + (86400 * 30)), "/");
         
         /*
			if($access_token != ''){
				$db_ex=new db_execute("UPDATE user_social SET usc_access_token = '". replaceMQ($access_token) ."' WHERE usc_id = " . $row["use_id"]);
				unset($db_ex);
			}
         */
			return 1;

		}else{
			// chưa có user
			$screst		= rand(11111,99999);
			$password 	= md5(rand(11111,99999) . $screst);
			$use_avatar	= '';
			if($social_type == 'facebook'){
				$use_avatar	= 'http://graph.facebook.com/v2.4/'. $social_id .'/picture?width=100&height=100';
			}
			if($social_type == 'google'){
				$use_avatar	= $avatar . '?sz=100';
			}
			$db_ex		= new db_execute_return();

			$use_id		= $db_ex->db_execute("INSERT INTO user(use_login_name,use_email,use_password,use_secrest,use_fullname,use_date_join,use_active,use_gender,use_fbid,use_check_friend,use_avatar,use_profile_social,use_social_type,use_type)
											 			 VALUES('" . replaceMQ($email) . "','". replaceMQ($email) ."','" . replaceMQ($password) . "','". replaceMQ($screst) ."','" . replaceMQ($name) . "',". time() .",1,". $gender .",'". replaceMQ($social_id)  ."',0,'". replaceMQ($use_avatar) ."','". replaceMQ($social_profile) ."','". $social_type ."',". $type .")");

		 	unset($db_ex);
         
         $db_insert  = new db_execute("INSERT IGNORE INTO user_cv_field (ucf_use_id)
                                       VALUES(". $use_id .")");
         unset($db_insert);

		 	setcookie("lglocal", $email, (time() + (86400 * 30)), "/");
		 	setcookie("username", substr($email, 0, strpos($email, '@')), (time() + (86400 * 30)), "/");

			setcookie("wvl_rand", $password, (time() + (86400 * 30)), "/");
			setcookie("password", md5(rand(111111, 999999)), (time() + (86400 * 30)), "/");
			$this->logged				= 1;
			$this->login_name			= $email;
			$this->use_name			= $name;
			$this->password			= $password;
			$this->u_id					= intval($use_id);
			$this->use_fullname		= $name;
			$this->use_avatar			= $use_avatar;
			$this->use_login			= $email;
			$this->use_email			= $email;
			$this->use_check_friend			= 0;
         
         /*
			if($access_token != ''){
				$db_ex=new db_execute("INSERT INTO user_social(usc_id,usc_access_token,usc_time,usc_fbid)
												VALUES(". $use_id .",'". $access_token ."',". time() .",'". $social_id ."')");
				unset($db_ex);
			}
         

			$table	= 'user_profile_' . intval($use_id%VL_TABLE_PROFILE);
			$db_ex	= new db_execute("INSERT INTO " . $table . " (usp_id) VALUES (". $use_id .")");
			unset($db_ex);
         

			$db_ex	= new db_execute("INSERT INTO user_filter (usf_id) VALUES (". $use_id .")");
			unset($db_ex);
         */
			return 1;

		}
		unset($db_checkuser);

	}

	/*
	Logout account
	*/
	function logout(){
		setcookie("lglocal"," ",null,"/");
		setcookie("wvl_rand","",null,"/");
		$_COOKIE["lglocal"] = "";
		$_COOKIE["wvl_rand"] = "";
		$this->logged=0;
      
	}


	/*
	Remove quote
	*/
	function removequote($str){
		$temp = str_replace("\'","'",$str);
		$temp = str_replace("'","''",$temp);
		return $temp;
	}


}