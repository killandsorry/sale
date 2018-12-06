<?
// Khai báo các hằng số sử dụng trong class
define("SPHINX_DATABASE_ACTIVE", true);
define('SPHINX_DATABASE_SERVER_KEYWORD',1);


class dbInitSphinx{
	
	var $array_server	= array ();
	var $server			= "127.0.0.1";
	var $port			= "9306";
	var $username		= "";
	var $password		= "";
	var $database		= "";
	
	function dbInitSphinx($server_number=0){
		
		$this->server 	= "127.0.0.1";
		
	}
	
	static function dump_log($string){
		file_put_contents(str_replace("class","logss",dirname(__FILE__)) . "/sphinx.log",$string . "\n");
	}
	
}

class dbQuerySphinx{
	
	var $link	= false;
	var $result	= false;
	var $error	= true;
	
	function query($query, $server_number=0){
		
		$query	= trim($query);
		if(!SPHINX_DATABASE_ACTIVE || $query == "") return false;
		
		$db_init	= new dbInitSphinx($server_number);
		if($this->link = @mysql_connect($db_init->server . ":" . $db_init->port, $db_init->username, $db_init->password)){
			@mysql_select_db($db_init->database);
			@mysql_query("SET NAMES 'utf8'");
			$this->result	= @mysql_query($query);
			if(!$this->result){
				dbInitSphinx::dump_log("Error in query string (" . $db_init->server . ":" . $db_init->port . "): " . mysql_error($this->link) . "\n" . @$_SERVER["SERVER_NAME"] . @$_SERVER["REQUEST_URI"] . "\n" . $query, "SPHINX_LOG");
				@mysql_close($this->link);
			}
			else $this->error	= false;
		}
		else dbInitSphinx::dump_log("Cannot connect sphinx realtime database (" . $db_init->server . ":" . $db_init->port . ")", "SPHINX_LOG");
		unset($db_init);
		
	}
	
	function getInfoQuery(){
		
		if(!$this->link) return false;
		
		$metainfo	= @mysql_query("SHOW META", $this->link);
		$srchmeta	= array();
		while($meta = @mysql_fetch_assoc($metainfo)) $srchmeta[$meta["Variable_name"]]	= $meta["Value"];
		
		return $srchmeta;
		
	}
	
	function close(){
		
		@mysql_free_result($this->result);
		if($this->link) @mysql_close($this->link);
		
	}
	
}

class dbQuerySphinxMysqli{
	
	var $link	= false;
	var $db_init= false;
	var $mysqli	= false;
	var $result	= false;
	var $query	= "";
	var $error	= true;
	var $i		= 0;
	
	function query($query, $server_number=0){
		
		$query	= trim($query);
		if(!SPHINX_DATABASE_ACTIVE || $query == "") return false;
		
		$db_init			= new dbInitSphinx($server_number);
		$this->db_init	= $db_init;
		$this->mysqli	= @new mysqli($db_init->server, $db_init->username, $db_init->password, $db_init->database, $db_init->port);
		if($this->mysqli->connect_errno){
			dbInitSphinx::dump_log("Cannot connect sphinx realtime database (" . $db_init->server . ":" . $db_init->port . ")", "SPHINX_LOG");
			return;
		}
		
		$this->i++;
		$this->query	= $query;
		$this->mysqli->query("SET NAMES 'utf8'");
		if($this->mysqli->multi_query($this->query)){
			$this->result	= $this->mysqli->store_result();
			$this->error	= false;
		}
		else{
			dbInitSphinx::dump_log("Error in query string result " . $this->i . " (" . $db_init->server . ":" . $db_init->port . "): " . $this->mysqli->error . "\n" . @$_SERVER["SERVER_NAME"] . @$_SERVER["REQUEST_URI"] . "\n" . $this->query, "SPHINX_LOG");
			@$this->mysqli->close();
		}
		
	}
	
	function more_results(){
		
		return ($this->mysqli->more_results() ? true : false);
		
	}
	
	function next_result(){
		
		$this->i++;
		if($this->mysqli->next_result()) $this->result = $this->mysqli->store_result();
		else dbInitSphinx::dump_log("Error in query string result " . $this->i . " (" . $this->db_init->server . ":" . $this->db_init->port . ")\n" . @$_SERVER["SERVER_NAME"] . @$_SERVER["REQUEST_URI"] . "\n" . $this->query, "SPHINX_LOG");
		
	}
	
	function close(){
		
		@$this->mysqli->free();
		@$this->mysqli->close();
		
	}
	
}

class db_execute_sphinx{
	
	var $link	= false;
	var $result	= false;
	var $error	= true;

	function db_execute_sphinx($query, $server_number=0){
		
		if(!SPHINX_DATABASE_ACTIVE) return false;
		
		$db_init	= new dbInitSphinx($server_number);
		if($this->link = @mysql_connect($db_init->server . ":" . $db_init->port, $db_init->username, $db_init->password)){
			@mysql_select_db($db_init->database);
			@mysql_query("SET NAMES 'utf8'");
			$this->result	= @mysql_query($query);
			if(!$this->result) dbInitSphinx::dump_log("Error in query string (" . $db_init->server . ":" . $db_init->port . "): " . mysql_error($this->link) . "\n" . @$_SERVER["SERVER_NAME"] . @$_SERVER["REQUEST_URI"] . "\n" . $query, "SPHINX_LOG");
			else $this->error	= false;
			@mysql_close($this->link);
		}
		else dbInitSphinx::dump_log("Cannot connect sphinx realtime database (" . $db_init->server . ":" . $db_init->port . ")", "SPHINX_LOG");
		unset($db_init);
		
	}
	
}
?>