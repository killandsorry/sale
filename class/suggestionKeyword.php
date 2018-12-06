<?
class suggestionKeyword{
	
	var $text				= "";
	var $array_text		= array();
	var $array_replace	= array("! ", ". ", ", ");
	var $text_max_length	= 5000;
	
	/**
	Function khởi tạo
	*/
	function clean($text){
		
		$break_text			= "{#BREAK#}";
		$this->text			= $this->replace_double_space(mb_strtolower($text, "UTF-8"));
		$this->text			= cut_string($this->text, $this->text_max_length);
		$this->text			= str_replace($this->array_replace, $break_text, $this->text);
		$this->array_text	= explode($break_text, $this->text);
		
	}
	
	/**
	Thay thế 2 dấu cách trở lên bằng 1 dấu cách
	*/
	function replace_double_space($string, $char=" "){
		$i		= 0;
		$max	= 10;
		if($char == "") return $string;
		while(mb_strpos($string, $char.$char, 0, "UTF-8") !== false){
			$i++;
			$string	= str_replace($char.$char, $char, $string);
			if($i >= $max) break;
		}
		return trim($string);
	}
	
	function searchPhraseInContent($keyword,$content){
		$keyword = $this->cleanSearch($keyword);
		$content = $this->cleanSearch($content);
		$arrayReturn = array();
		$arrayReturn["total"] = 0;
		$arrayReturn["found"] = 0;
		$arrayReturn["percent"] = 0;
		if($keyword == "" || $content == "") return $arrayReturn;
		$keyword = explode(" ",$keyword);
		$total   = count($keyword);
		$arrayReturn = array();
		$arrayReturn["total"] = $total;
		$arrayReturn["found"] = 0;
		foreach($keyword as $word){
			if(strpos($content," " . $word . " ") !== false){
				$arrayReturn["found"]++;
			}
		}
		$arrayReturn["percent"] = $arrayReturn["found"] / $arrayReturn["total"] *100;
		return $arrayReturn;
	}
	
	/**
	Function get tag vehicle 
	*/
	function searchKeywordFromIndex($title = "",$text = "",$index,$minWord = 1,$field_check = ""){
		$this->clean($text);
		$title		= mb_strtolower($title,"UTF-8");
		$arrReturn	= array();
		$db_keyword = new dbQuerySphinxMysqli();
		$db_keyword->query($this->generate_sql($index,$minWord), SPHINX_DATABASE_SERVER_KEYWORD);
		$this->array_text[] = $title;
		$finish		= false;
		while(!$finish && !$db_keyword->error){
			while($row = mysqli_fetch_assoc($db_keyword->result)){
				$arrReturn[$row["id"]] = $row;
			}
			if($db_keyword->more_results()) $db_keyword->next_result();
			else $finish	= true;
		}
		unset($db_keyword);
		
		$arrReturn1 = array();
		foreach($arrReturn as $row){
			if($field_check != ""){
			$result = $this->searchPhraseInContent($field_check,$title . " " . $text);
				if($result["total"] > 3 && $result["percent"] < 75){
					continue;
				}
				$row = array_merge($row,$result);
			}			
			$arrReturn1[$row["id"]] = $row;
		}
		
		// Trả về array keyword
		return $arrReturn1;
		
	}
	
	function checkDuplicateJob($text,$maxCheck = 80,$sqlWhereCheck = ""){
		$arrReturn	= 0;
		$db_keyword = new dbQuerySphinx();
		//echo "SELECT jobf_message FROM rt_job_fb WHERE " . $sqlWhereCheck . " ORDER BY jobf_date DESC LIMIT 10";
		$db_keyword->query("SELECT jobf_message FROM rt_job_fb WHERE " . $sqlWhereCheck . " ORDER BY jobf_date DESC LIMIT 50", SPHINX_DATABASE_SERVER_KEYWORD);
		//$db_keyword->query("SELECT jobf_message,WEIGHT() AS point_a FROM rt_job_fb WHERE MATCH('\"" . replaceSphinxMQ($text)  . "\"/1') " . $sqlWhereCheck . " LIMIT 10 OPTION ranker = matchany", SPHINX_DATABASE_SERVER_KEYWORD);
		$finish		= false;
		while($row = mysql_fetch_assoc($db_keyword->result)){
				$result = getDiffText($text, $row["jobf_message"]);
				//echo $row["jobf_message"] . '<hr>';
				//print_r($result); echo '<hr>';
				if(intval($result["copy"]) > 30) return intval($result["copy"]);
		}
		unset($db_keyword);
		
		// Trả về array keyword
		return 0;
	}
	
	function checkDuplicateJobMysql($jobf_message,$maxCheck = 80,$use_id = 0){
		
		$db_select = new db_query("SELECT * FROM job_fb WHERE `jobf_use_id` =$use_id LIMIT 15");
		while($row = $db_select->fetch()){
			$result = getDiffText($this->cleanSearch($jobf_message), $this->cleanSearch($row["jobf_message"]));
			if(intval($result["copy"]) > $maxCheck) return intval($result["copy"]);
		}
		// Trả về array keyword
		return 0;
	}
	
	function cleanSearch($string){
		$string = removeHTML($string);
		$arrayChar = array(chr(9),chr(10),chr(13),"&gt;","  ","  ","  ","  ","  ","  ","  ","  ","  ");
		for($i = 32; $i < 48; $i++){
			$arrayChar[] = chr($i);
		}
		for($i = 58; $i < 65; $i++){
			$arrayChar[] = chr($i);
		}
		$string	= str_replace($arrayChar," ",$string);
		$string	= trim(mb_strtolower($string,"UTF-8"));
		return trim($string);
	}
	
	/**
	Function get tag vehicle 
	*/
	function searchJobRelate($text = "",$arrayCity = array()){
		$this->clean($text);
		$text = implode(" ", $this->array_text);
		$arrReturn	= array();
		$sql = '';
		if(!empty($arrayCity)) $sql .= " AND job_cit_id IN(" . implode(",",$arrayCity) . ")";
		$db_keyword = new dbQuerySphinxMysqli();
		$db_keyword->query("SELECT *,WEIGHT() FROM job WHERE MATCH('\"" . replaceSphinxMQ($text) . "\"/3') $sql LIMIT 5 OPTION field_weights=(cit_name = 150,cit_alias = 100);", SPHINX_DATABASE_SERVER_KEYWORD);
		$finish		= false;
		while(!$finish && !$db_keyword->error){
			while($row = mysqli_fetch_assoc($db_keyword->result)){
				$arrReturn[$row["id"]] = $row;
			}
			if($db_keyword->more_results()) $db_keyword->next_result();
			else $finish	= true;
		}
		unset($db_keyword);
		
		// Trả về array keyword
		return $arrReturn;
		
	}
	

	/**
	Function tạo query để search từ khóa
	*/
	function generate_sql($index,$minWord = 2){
		
		$strReturn	= "";
		foreach($this->array_text as $key => $value){
			$value	= trim($value);
			if($value == "") continue;
			$length	= mb_strlen($value, "UTF-8");
			//$value = mysql_escape_string($value);
			for($i=2; $i<4; $i++){
				if($length < 10 && $i == 2) continue;
				if($length < 15 && $i == 3) continue;
				$strReturn	.= "SELECT * FROM $index WHERE MATCH('\"" . replaceSphinxMQ($value) . "\"/" . $i . "') LIMIT 3;";
			}
		}
		//echo $strReturn;
		return $strReturn;
		
	}
	
}
?>