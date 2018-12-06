<?

/**
 * Type = 1: 300x250
 * Type = 2: 728x90
 * Type = 3: 300x600
 * Type = 4: 320x100
 * Type = 5: 160x600
 * Type = 6: 468x60
 * Type = 7: 250x250
 * Type = 8: free size
 * Type = 9: banner link
 * Type = 10: Ads content article
 * Type = 11: ads for myweb (trang chi tiết viec lam)
 * Type = 12 : ads myweb right (trang timviecnhanh bên phải)
 * Type = 13: link resize
 */
function ads_show($type = 1){
   return;
   global $show_adsense;
   if($show_adsense==0)return;
   $ads = '';
   switch($type){
      case 1:
         $ads = '
<!-- banner_300_250 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="8803391915"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
         break;
      case 2:
         $ads = '
<!-- banner_728 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="7326658710"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
         break;
      case 3:
         $ads = '
<!-- banner_300_600 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:600px"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="3681861510"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
         break;     
      case 4:
         $ads = '
<!-- banner_mobile -->
<ins class="adsbygoogle"
     style="display:inline-block;width:320px;height:100px"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="9449193514"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>' ;
         break;  
      case 5:
         $ads = '
<!-- banner_160_600 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:160px;height:600px"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="6216525511"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
         break;   
      case 6:
         $ads = '
<!-- banner_468_60 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:468px;height:60px"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="9169991919"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
         break;  
      case 7:
         $ads = '
<!-- banner_250_250 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:250px;height:250px"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="7545154719"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
         break;
         
      case 8:
         $ads = '
<!-- banner_free -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="1498621111"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
         break;
      case 9:
         $ads = '
<!-- banner_link -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:15px"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="9763591112"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
      break;
      case 10:
         $ads  = '
<ins class="adsbygoogle"
     style="display:block; text-align:center;"
     data-ad-format="fluid"
     data-ad-layout="in-article"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="6304996711"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>';
         break;
      case 11:
         $ads = '
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="3085675110"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>         
         ';
         break;
      case 12:
         $ads = '
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:600px"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="5759939918"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>';
         break;
      case 13:
         $ads = '
<!-- link_resize -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2155024450843541"
     data-ad-slot="2006022295"
     data-ad-format="link"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
         break;
   }
   
   return $ads;
}

/**
 * Ham tach lay cau tieu de
 */
function breakSentence($string,$cityname = ''){
	$arrayReturn = array();
	$charBreak 	= "____";
	$string 		= str_replace(array(chr(9),chr(10)),$charBreak,$string);
	$arraySplit = array(";",".",";",chr(13)," - ","\n",":","*"," - ");
	$string 		= str_replace($arraySplit,$charBreak,$string);
	//echo $string . '<hr>';
	$arraySentence = explode($charBreak,$string);
	foreach($arraySentence as $key => $line){
		$line = str_replace("  "," ",$line);
		$line = str_replace("  "," ",$line);
		$line = str_replace("  "," ",$line);
		$line = str_replace("  "," ",$line);
		$word = count(explode(" ",trim($line)));
		if($word > 25 || $word < 5){
			unset($arraySentence[$key]);
		}
		
		
	}
	reset($arraySentence);
	foreach($arraySentence as $line){
		$line_lower = mb_strtolower($line,"UTF-8");
		if(strpos($line_lower,"tuyển") !== false || strpos($line_lower,"cần tìm") !== false){
			$arrayReturn[] = $line;
		}
	}
	$string = str_replace(array(chr(9),chr(10),chr(13)),"",$string);
	$string = isset($arrayReturn[0]) ? trim($arrayReturn[0]) : '';
	$len =	mb_strlen($string, "UTF-8");
	if($len < 35 && $len > 10 && $cityname != ""){
		$string = $string . " tại " . $cityname;
	}
	return $string;
	
}

/**
 * [getDiffText description]
 * @param  [type] $from text from
 * @param  [type] $to   text to
 * @param  [type] $type  0 - paragraph | 1 - sentence | 2 - word | 3 - character
 * @return [type]       [description]
 */
function getDiffText($from, $to, $type = 2){
    $time     = microtime(true);
    $memory   = memory_get_usage();
    $from_len = mb_strlen($from, 'UTF-8');
    $to_len   = mb_strlen($to, 'UTF-8');

    $granularityStacks = array(
        FineDiff::$paragraphGranularity,
        FineDiff::$sentenceGranularity,
        FineDiff::$wordGranularity,
        FineDiff::$characterGranularity
    );

    $diff           = new FineDiff($from, $to, $granularityStacks[$type]);
    $edits          = $diff->getOps();

    // var_dump($edits);die;//
    // $exec_time      = sprintf('%.3f sec', gettimeofday(true) - $start_time);
    // $rendered_diff  = $diff->renderDiffToHTML();
    // $rendering_time = sprintf('%.3f sec', gettimeofday(true) - $start_time);

    // $arrText = array();
    $opcodes     = array();
    $copy_len    = 0;
    $delete_len  = 0;
    $insert_len  = 0;
    $replace_len = 0;
    $copy        = 0;
    $delete      = 0;
    $insert      = 0;
    $replace     = 0;

    if ( $edits !== false ) {

        $offset = 0;
        foreach ( $edits as $edit ) {
            $n      = $edit->getFromLen();
            // $text   = mb_substr($from, $offset, $n, 'UTF-8');
            // $length = mb_strlen($text, 'UTF-8');

            if ( $edit instanceof FineDiffCopyOp ) {
                $state    = 'copy';
                $text     = mb_substr($from, $offset, $n, 'UTF-8');
                $copy_len += mb_strlen($text, 'UTF-8');

            }else if ( $edit instanceof FineDiffDeleteOp ) {
                $state = 'delete';
                $text     = mb_substr($from, $offset, $n, 'UTF-8');
                if ( strcspn($text, " \n\r") === 0 ) {
                    $text = str_replace(array("\n","\r"), array('\n','\r'), $text);
                }
                $delete_len += mb_strlen($text, 'UTF-8');

            }else if ( $edit instanceof FineDiffInsertOp ) {
                $state      = 'insert';
                $text       = mb_substr($edit->getText(), 0, $edit->getToLen(), 'UTF-8');
                $insert_len += mb_strlen($text, 'UTF-8');

            }else /* if ( $edit instanceof FineDiffReplaceOp ) */ {
                $state       = 'replace';

                // delete
                $text     = mb_substr($from, $offset, $n, 'UTF-8');
                if ( strcspn($text, " \n\r") === 0 ) {
                    $text = str_replace(array("\n","\r"), array('\n','\r'), $text);
                }
                $delete_len  += mb_strlen($text, 'UTF-8');
                $replace_len += $delete_len;

                // insert
                $text        = mb_substr($from, $offset, $n, 'UTF-8');
                $insert_len  += mb_strlen($text, 'UTF-8');
            }

            $opcodes[] = array('state' => $state, 'value' => $text);
            $offset += $n;
        }

        // $opcodes = implode("", $opcodes);
        // $opcodes_len = sprintf('%d bytes (%.1f %% of &quot;To&quot;)', $opcodes_len, $to_len ? $opcodes_len * 100 / $to_len : 0);
        $copy    = $copy_len * 100 / $from_len;
        $delete  = $delete_len * 100 / $from_len;
        $insert  = $insert_len * 100 / $from_len;
        $replace = $replace_len * 100 / $from_len;
    }

    return array(
            'meta'    => array(
                'time'   => microtime(true) - $time,
                'memory' => memory_get_usage() - $memory,
                ),
            'opcode'  => $opcodes,
            'copy'    => $copy,
            'delete'  => $delete,
            'insert'  => $insert,
            'replace' => $replace
        );
}
function removeEmoji($text) {

    $clean_text = "";

    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);

    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);

    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);

    return $clean_text;
}
function getUserCreateIfNotExif($name,$email,$phone,$fbid){
	if(trim($email) == "" && trim($phone) == "") return 0;
	
	$sql = "use_fbid = '" . replaceMQ($fbid) . "'";
	if(trim($email) != "") $sql .= " OR use_email = '" . replaceMQ($email) . "'";
	if(trim($phone) != "") $sql .= " OR use_phone = '" . replaceMQ($phone) . "'";

	$db_select = new db_query("SELECT use_id FROM user WHERE $sql  LIMIT 1");
	if($row = mysqli_fetch_assoc($db_select->result)){
		return $row["use_id"];
	}else{
		$password = md5(rand(111111111,999999999));
		$password = md5($password);
		$db_ex = new db_execute_return();
		$loginname = ($email == "") ? $phone : $email;
		$use_id = $db_ex->db_execute("INSERT INTO user(use_login_name,use_fullname,use_password,use_email,use_phone,use_fbid,use_active) 
												VALUES('" . replaceMQ($loginname) . "','" . replaceMQ($name) . "','" . replaceMQ($password) . "','" . replaceMQ($email) . "','" . replaceMQ($phone) . "','" . replaceMQ($fbid) . "',1)");
		return $use_id;
	}
}
function splitPrice($abc){
  $abc = mb_strtolower($abc,"UTF-8");
  $pattern = '/([0-9]+)tr([0-9]+)|([0-9]+)tr/';
  $ac =  '/([0-9]+)(\.|\,)([0-9]+)tr/';
  $ac1 =  '/([0-9]+)(\.|\,)([0-9]+) triệu/';
  $tt = '/([0-9]+)(\.|\,)([0-9]+)t/';
  $k =  '/([0-9]+)k/';
  $t = '/([0-9]+)t([0-9]+)|([0-9]+)t/';
  $cb = '/([0-9]+)(\.|\,)([0-9]+)(\.|\,)([0-9]+)/';
  $cd = '/([0-9]+)(\.|\,)([0-9]+)/';
  $d = '/₫([0-9]+)(\.|\,)([0-9]+)/';
  $bcd = 0;
  if (preg_match($ac, $abc,$matches)){
   $rp = str_replace("tr","",$matches[0]);
       $bcd = (str_replace(",",".",$rp)*1000000);
  }
  else if (preg_match($ac1, $abc,$matches)){
      $bcd = (str_replace("tr",".",$matches[0])*1000000);
  }
  else if (preg_match($pattern, $abc,$matches)){
      $bcd = (str_replace("tr",".",$matches[0])*1000000);
  }
  else if(preg_match($tt, $abc,$matches)){
      $bcd = (str_replace("t","",$matches[0])*1000000);
  }
  else if (preg_match($k, $abc,$matches)){
      $bcd = (str_replace("k","",$matches[0])*1000);
  }
  else if(preg_match($cb, $abc,$matches)){
      $bcd = (str_replace(array(",","."),"",$matches[0]));
  }
  else if(preg_match($cd, $abc,$matches)){
       $bcd = (str_replace(array(",","."),"",$matches[0]));
  }
  else if(preg_match($t, $abc,$matches)){
      $bcd = (str_replace("t",".",$matches[0])*1000000);
  }
  else if(preg_match($d, $abc,$matches)){
      $bcd = (str_replace("₫","",$matches[0]));
  }
  $bcd = str_replace(array(",","."),"",$bcd);
  if(intval($bcd) < 10000 || intval($bcd) > 90000000) return 0;
    return $bcd;
}
function removeCharPhoneNumber($string){

 $length  = mb_strlen($string, "UTF-8");

 $start_char = 0;
 //Remove các ký tự ko phải số ở đầu
 for($i=0; $i<$length; $i++){
  $char = mb_substr($string, $i, 1, "UTF-8");
  if(($char == "(") || (is_numeric($char))) break;
  $start_char = $i+1;
 }

 $end_char = $length;
 //Remove các ký tự ko phải số ở cuối
 for($i=$length; $i>=0; $i--){
  $char = mb_substr($string, $i-1, 1, "UTF-8");
  if(is_numeric($char)) break;
  $end_char = $i-1;
 }
 //Cắt chuỗi
 $string  = mb_substr($string, $start_char, ($end_char - $start_char), "UTF-8");

 return $string;

}

function splitPhoneNumber($string){

	/*
		Số điện thoại là các số bắt đầu == 01 -> 09 và có 10 hoặc 11 ký tự
		09: sẽ có 10 ký tự
		01 -> 08 là số máy bàn hoặc đầu số mới cũng sẽ có 10 hoặc 11 ký tự
 	*/
 	$arrDauSo	= array(
	 	1 => '01',
	 	2 => '02',
	 	3 => '03',
	 	4 => '04',
	 	5 => '05',
	 	6 => '06',
	 	7 => '07',
	 	8 => '08',
	 	9 => '09'
	);

	$str_tmp = str_replace(array(" - ", " . "), " / ", $string);
	$str_tmp = preg_replace('/\s/', '', $str_tmp);

	$pattern = '/(\d{6,}(?!\d)|(?<!\d)\d{6,}|(\(|\d|\.|-|,|\)){6,})/';

	preg_match_all($pattern, $str_tmp, $match);
	//print_r($match[0]);

	$result = array();// Mang luu lai ket qua tra ve
	foreach($match[0] as $key => $value){
		// số chuẩn khi đã replace hết các ký tự string
		$phoneNumber				= preg_replace('/\D/', '', $value);
		foreach($arrDauSo as $k => $dauso){
			if(strpos($phoneNumber, $dauso) === 0){
				if($dauso == '09'){
					if(strlen($phoneNumber) == 10){
						$result[$key]["socu"] = removeCharPhoneNumber($value);
						$result[$key]["somoi"] = $phoneNumber;
					}
				}else{
					if(strlen($phoneNumber) >= 9 && strlen($phoneNumber) <= 11){
						$result[$key]["socu"] = removeCharPhoneNumber($value);
						$result[$key]["somoi"] = $phoneNumber;
					}
				}
			}
		}
	}

	return $result;

}

function getEmailFromText($string){
	//$string = "bla bla pickachu@domain.com MIME-Version: balbasur@domain.com bla bla bla";
	$matches = array();
	$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
	preg_match_all($pattern,$string,$matches);
	if(isset($matches[0])) $matches = $matches[0];
	return $matches;
}

function extract_email_address ($string) {
    $emails = '';  
    foreach(preg_split('/\s/', $string) as $token) {
        $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
        if ($email !== false) {
            $emails[] = $email;
        }
    }
    return $emails;
}

//kiem tra xem tu khoa co bi tu choi ko
function isRejectKeyword($text){
	$arrayReject = array("sex","độc thân","ly hôn","viêm nhiễm","cho vay","thế chấp", "chuyện ấy","quan hệ","chăn rau","chăn chuối","chia sẽ chuối","chia sẻ rau","bán dâm","mại dâm","tình dục","khoái cảm","khỏa thân","lồn","cặc","hiếp dâm","nhân quyền","âm đạo","dương vật","mông phụ nữ","quan tham","tham nhũng","phá thai","đẻ non","tránh thai","làm bằng","bằng đại học","lam bang dai hoc","mây mưa","đảng cộng sản","chính phủ","lừa đảo","thầm kín","thả thính","tâm sự");
	$text = mb_strtolower($text, "UTF-8");
	foreach($arrayReject as $key){
		if(strpos($text,$key) !== false){
			return $key;
		}
	}
	return false;
}

function isExifKeyword($text){
	$arrayReject = array("việc","làm","tuyển");
	$text = mb_strtolower($text, "UTF-8");
	foreach($arrayReject as $key){
		if(strpos($text,$key) !== false){
			return $key;
		}
	}
	return false;
}

function clean_string($str, $rmhtml = 1){
	$newString	= $str;
	$newString	= trim($newString);
	$newString	= removeLink($newString);
	if($rmhtml == 0) return $newString;
	$newString	= removeHTML($newString);

	return $newString;
}

function replaceSphinxMQ($str){
	$array_bad_word = array("?", "^", ",", ";", "*", "(", ")","\\","/");
	$str	= str_replace($array_bad_word, " ", $str);
	$str	= str_replace(array("\\", "'", '"'), array("", "\\'", ""), $str);
	return $str;
}

/**
 * Ham lam sach title
 * trim
 * mb_strtolower
 * ucfirt
 */
function clean_title($name){
	$title	= $name;
	$title	= trim($title);
	$title	= mb_strtolower($title, 'UTF-8');
	$title	= ucfirst($title);
	return $title;
}
function base64_url_encode($input){
	return strtr(base64_encode($input), '+/=', '_,-');
}
function base64_url_decode($input) {
	return base64_decode(strtr($input, '_,-', '+/='));
}
function callback($buffer){
	$str		= array(chr(9), chr(10));
	$buffer	= str_replace($str, "", $buffer);
	return $buffer;
}

function check_email_address($email) {
	//First, we check that there's one @ symbol, and that the lengths are right
	if(!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)){
		//Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	//Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for($i = 0; $i < sizeof($local_array); $i++){
		if(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])){
			return false;
		}
	}
	if(!ereg("^\[?[0-9\.]+\]?$", $email_array[1])){
	//Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if(sizeof($domain_array) < 2){
			return false; // Not enough parts to domain
		}
		for($i = 0; $i < sizeof($domain_array); $i++){
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])){
				return false;
			}
		}
	}
	return true;
}

function cut_string($str, $length, $char=" ..."){
	//Nếu chuỗi cần cắt nhỏ hơn $length thì return luôn
	$strlen	= mb_strlen($str, "UTF-8");
	if($strlen <= $length) return $str;

	//Cắt chiều dài chuỗi $str tới đoạn cần lấy
	$substr	= mb_substr($str, 0, $length, "UTF-8");
	if(mb_substr($str, $length, 1, "UTF-8") == " ") return $substr . $char;

	//Xác định dấu " " cuối cùng trong chuỗi $substr vừa cắt
	$strPoint= mb_strrpos($substr, " ", "UTF-8");

	//Return string
	if($strPoint < $length - 20) return $substr . $char;
	else return mb_substr($substr, 0, $strPoint, "UTF-8") . $char;
}

function format_number($number, $edit=0){
	if($edit == 0){
		$return	= number_format($number, 2, ".", ",");
		if(intval(substr($return, -2, 2)) == 0) $return = number_format($number, 0, ".", ",");
		elseif(intval(substr($return, -1, 1)) == 0) $return = number_format($number, 1, ".", ",");
		return $return;
	}
	else{
		$return	= number_format($number, 2, ".", "");
		if(intval(substr($return, -2, 2)) == 0) $return = number_format($number, 0, ".", "");
		return $return;
	}
}

function format_currency($value = ""){
	$str		=	$value;
	if($value != ""){
		$str		=	number_format(round($value/1000)*1000,0,"",",");
	}
	return $str;
}

function generate_array_variable($variable){
	$list			= tdt($variable);
	$arrTemp		= explode("{-break-}", $list);
	$arrReturn	= array();
	for($i=0; $i<count($arrTemp); $i++) $arrReturn[$i] = trim($arrTemp[$i]);
	return $arrReturn;
}

function getURL($serverName=0, $scriptName=0, $fileName=1, $queryString=1, $varDenied=''){
	$url	 = '';
	$slash = '/';
	if($scriptName != 0)$slash	= "";
	if($serverName != 0){
		if(isset($_SERVER['SERVER_NAME'])){
			$url .= 'http://' . $_SERVER['SERVER_NAME'];
			if(isset($_SERVER['SERVER_PORT'])) $url .= ":" . $_SERVER['SERVER_PORT'];
			$url .= $slash;
		}
	}
	if($scriptName != 0){
		if(isset($_SERVER['SCRIPT_NAME']))	$url .= substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/') + 1);
	}
	if($fileName	!= 0){
		if(isset($_SERVER['SCRIPT_NAME']))	$url .= substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/') + 1);
	}
	if($queryString!= 0){
		$url .= '?';
		reset($_GET);
		$i = 0;
		if($varDenied != ''){
			$arrVarDenied = explode('|', $varDenied);
			while(list($k, $v) = each($_GET)){
				if(array_search($k, $arrVarDenied) === false){
					$i++;
					if($i > 1) $url .= '&' . $k . '=' . @urlencode($v);
					else $url .= $k . '=' . @urlencode($v);
				}
			}
		}
		else{
			while(list($k, $v) = each($_GET)){
				$i++;
				if($i > 1) $url .= '&' . $k . '=' . @urlencode($v);
				else $url .= $k . '=' . @urlencode($v);
			}
		}
	}
	$url = str_replace('"', '&quot;', strval($url));
	return $url;
}

function getValue($value_name, $data_type = "int", $method = "GET", $default_value = 0, $advance = 0, $wysiwyg = 0){
	$value = $default_value;
	$arrMethod	= explode("{#}", $method);
	foreach($arrMethod as $m_value){
		switch($m_value){
			case "GET": if(isset($_GET[$value_name])) $value = $_GET[$value_name]; break;
			case "POST": if(isset($_POST[$value_name])) $value = $_POST[$value_name]; break;
			case "COOKIE": if(isset($_COOKIE[$value_name])) $value = $_COOKIE[$value_name]; break;
			case "SESSION": if(isset($_SESSION[$value_name])) $value = $_SESSION[$value_name]; break;
			default: if(isset($_GET[$value_name])) $value = $_GET[$value_name]; break;
		}
	}

	//Sủa lại nếu $value là mảng thì ko được dùng strval. Fix cho PHP bản > 5.2
	if(!is_array($value))
	{
		$string_value = ($wysiwyg == 1) ? $value : trim(str_replace('"', '&quot;', strval($value)));
	}else{
		$string_value = $value;
	}

	//$string_value = ($wysiwyg == 1) ? $value : trim(str_replace('"','&quot;',strval($value)));
	$valueArray	= array("int" => intval($value), "str" => $string_value, "flo" => floatval($value), "dbl" => doubleval($value), "arr" => $value);
	foreach($valueArray as $key => $returnValue){
		if($data_type == $key){
		    if($key == 'str'){
		       if(is_array($returnValue)) $returnValue = '';
		    }
			if($advance != 0){
				if($advance == 1){
					$returnValue = str_replace("\'", "'", $returnValue);
					$returnValue = str_replace("'", "''", $returnValue);
					$returnValue = $returnValue;
				}
				if($advance == 2){
					$returnValue = htmlspecialbo($returnValue);
				}
				//Bỏ các ký tự magic quote và htmlspecialbo
				if($advance == 3){
					$returnValue = htmlspecialbo($returnValue);
					$returnValue = str_replace("\'", "'", $returnValue);
					$returnValue = str_replace("\&quot;", "&quot;", $returnValue);
					$returnValue = str_replace("\\\\", "\\", $returnValue);
				}
				//Bỏ các ký tự magic quote nhưng vẫn giữ nguyên tag
				if($advance == 4){
					$returnValue = stripslashes($returnValue);
				}
			}
			//Do số quá lớn nên phải kiểm tra trước khi trả về giá trị
			if(!is_array($returnValue))
			{
				if((strval($returnValue) == "INF") && ($data_type != "str")) return 0;
			}

			return $returnValue;
			break;
		}
	}
	return (intval($value));
}

function htmlspecialbo($str){
	$arrDenied	= array('<', '>', '\"', '"', "\'", "'");
	$arrReplace	= array('&lt;', '&gt;', '&quot;', '&quot;', '&apos;', '&apos;');
	$str = str_replace($arrDenied, $arrReplace, $str);
	return $str;
}

function javascript_writer($str){
	$mytextencode = "";
	for ($i=0;$i<strlen($str);$i++){
		$mytextencode .= ord(substr($str,$i,1)) . ",";
	}
	if ($mytextencode!="") $mytextencode .= "32";
	return "<script language='javascript'>document.write(String.fromCharCode(" . $mytextencode . "));</script>";
}


function microtime_float(){
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

/**
 * redirec header 301
 */
function header_301($url = ''){
	if($url != ''){
		Header( "HTTP/1.1 301 Moved Permanently" );
		Header( "Location: " . $url );
		exit();
	}
}

/**
 * redirect header
 */
function redirect_header($url = ''){
	echo '<script>window.location.href="'. $url .'"</script>';
	exit();
}

/**
 * redirect header
 */
function redirect($url = ''){
	echo '<script>window.location.href="'. $url .'"</script>';
	exit();
}

function removeAccent($mystring){
	$marTViet=array(
		// Chữ thường
		"à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ","ặ","ẳ","ẵ",
		"è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ",
		"ì","í","ị","ỉ","ĩ",
		"ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ","ờ","ớ","ợ","ở","ỡ",
		"ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
		"ỳ","ý","ỵ","ỷ","ỹ",
		"đ","Đ","'",
		// Chữ hoa
		"À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă","Ằ","Ắ","Ặ","Ẳ","Ẵ",
		"È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
		"Ì","Í","Ị","Ỉ","Ĩ",
		"Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ",
		"Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
		"Ỳ","Ý","Ỵ","Ỷ","Ỹ",
		"Đ","Đ","'"
		);
	$marKoDau=array(
		/// Chữ thường
		"a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a",
		"e","e","e","e","e","e","e","e","e","e","e",
		"i","i","i","i","i",
		"o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o",
		"u","u","u","u","u","u","u","u","u","u","u",
		"y","y","y","y","y",
		"d","D","",
		//Chữ hoa
		"A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A",
		"E","E","E","E","E","E","E","E","E","E","E",
		"I","I","I","I","I",
		"O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O",
		"U","U","U","U","U","U","U","U","U","U","U",
		"Y","Y","Y","Y","Y",
		"D","D","",
		);
	return str_replace($marTViet, $marKoDau, $mystring);
}

function removeHTML($string){
	$string = preg_replace ('/<script.*?\>.*?<\/script>/si', ' ', $string);
	$string = preg_replace ('/<style.*?\>.*?<\/style>/si', ' ', $string);
	$string = preg_replace ('/<.*?\>/si', ' ', $string);
	$string = str_replace ('&nbsp;', ' ', $string);
	$string = mb_convert_encoding($string, "UTF-8", "UTF-8");
	$string = str_replace (array(chr(9),chr(10),chr(13)), ' ', $string);
	for($i = 0; $i <= 5; $i++) $string = str_replace ('  ', ' ', $string);
	return $string;
}

function removeLink($string){
	$string = preg_replace ('/<a.*?\>/si', '', $string);
	$string = preg_replace ('/<\/a>/si', '', $string);
	return $string;
}

function replaceFCK($string, $type=0){
	$array_fck	= array ("&Agrave;", "&Aacute;", "&Acirc;", "&Atilde;", "&Egrave;", "&Eacute;", "&Ecirc;", "&Igrave;", "&Iacute;", "&Icirc;",
								"&Iuml;", "&ETH;", "&Ograve;", "&Oacute;", "&Ocirc;", "&Otilde;", "&Ugrave;", "&Uacute;", "&Yacute;", "&agrave;",
								"&aacute;", "&acirc;", "&atilde;", "&egrave;", "&eacute;", "&ecirc;", "&igrave;", "&iacute;", "&ograve;", "&oacute;",
								"&ocirc;", "&otilde;", "&ugrave;", "&uacute;", "&ucirc;", "&yacute;",
								);
	$array_text	= array ("À", "Á", "Â", "Ã", "È", "É", "Ê", "Ì", "Í", "Î",
								"Ï", "Ð", "Ò", "Ó", "Ô", "Õ", "Ù", "Ú", "Ý", "à",
								"á", "â", "ã", "è", "é", "ê", "ì", "í", "ò", "ó",
								"ô", "õ", "ù", "ú", "û", "ý",
								);
	if($type == 1) $string = str_replace($array_fck, $array_text, $string);
	else $string = str_replace($array_text, $array_fck, $string);
	return $string;
}

function replaceJS($text){
	$arr_str = array("\'", "'", '"', "&#39", "&#39;", chr(10), chr(13), "\n");
	$arr_rep = array(" ", " ", '&quot;', " ", " ", " ", " ");
	$text		= str_replace($arr_str, $arr_rep, $text);
	$text		= str_replace("    ", " ", $text);
	$text		= str_replace("   ", " ", $text);
	$text		= str_replace("  ", " ", $text);
	return $text;
}

function replace_keyword_search($keyword, $lower=1){
	if($lower == 1) $keyword	= mb_strtolower($keyword, "UTF-8");
	$keyword	= replaceMQ($keyword);
	$arrRep	= array("'", '"', "-", "+", "=", "*", "?", "/", "!", "~", "#", "@", "%", "$", "^", "&", "(", ")", ";", ":", "\\", ".", ",", "[", "]", "{", "}", "‘", "’", '“', '”');
	$keyword	= str_replace($arrRep, " ", $keyword);
	$keyword	= str_replace("  ", " ", $keyword);
	$keyword	= str_replace("  ", " ", $keyword);
	return $keyword;
}

function replaceMQ($text){
	$text	= str_replace("\'", "'", $text);
	$text	= str_replace("'", "''", $text);
   $text = str_replace('\\', '', $text);
	return $text;
}

function remove_magic_quote($str){
	$str = str_replace("\'", "'", $str);
	$str = str_replace("\&quot;", "&quot;", $str);
	$str = str_replace("\\\\", "\\", $str);
	return $str;
}

function my_intval($vl){
   if($vl > 200000000) return 0;
   return $vl;
}

/**
 * Ham lay ten file hien tai
 * @return [file_name] [description]
 */
function get_current_file_name() {
	$part_of_file = explode(".", getURL());
	// Ten file hien tai
	$current_file = $part_of_file[0];
	return $current_file;
}

/**
 * Ham tinh so ngay da dang cua 1 tin
 * vi du: truyen vao 1 ngay tinh de ngay gio hien tai xem dc bao nhieu roi
 */
function countTime($timein = 0){
	$timeCount		= time() - $timein;
	// nếu time > 30 ngày thì trả về số chính xác luôn
	if($timeCount > (30 * 86400)) return date('d/m/Y', $timein);

	$timeFday		= mktime(0, 0, 0, date('m', time()), date('d', time()), date('Y', time()));
	// neeus time < time đầu ngày thì hiển thị theo ngày

	// nếu time là trong ngày thì tính theo h hoặc phút
	if($timein > $timeFday){
		if($timeCount / 60 > 59){
			return round($timeCount / 3600) . ' giờ trước';
		}else{
			$phut	= round($timeCount / 60);
			if($phut < 5) return 'Vừa đăng';
			return $phut . ' phút trước';
		}
	}else{
		if($timein < $timeFday && $timein >= ($timeFday - 86400)){
			return 'Hôm qua';
		}else{
			return round($timeCount / 86400) . ' ngày trước';
		}
	}

}

/**
 * Function show content job, add br and replace title
 */
function addBrInJobDescription($str = ''){
	$arrReplace	= array(
		'MÔ TẢ CV:', 'Mô tả', 'Kỹ năng', 'Chế độ khác', 'Yêu cầu hồ sơ', '<br>',
	);

	$arrAddBr	= array('- ', '-	', '+ ', '* ','•	','• ', '● ','_ ', '1. ', '2. ', '3. ', '4. ', '5. ', '6. ', '7. ', '8. ', '9. ', '10. ','11. ','12. ','13. ','14. ','15. ','16. ','1:','2:','3:','4:','5:','6:','1)','2)', '3)','4)','5)');

	$newstring	= trim($str);
	$newstring	= str_replace($arrReplace, '', $newstring);
	foreach($arrAddBr as $br){
		$newstring	= str_replace($br, "\n" . $br, $newstring);
	}
	$newstring	= trim($newstring);
	//if(strpos($newstring, '<br>') === 0) $newstring	= mb_substr($newstring, 5, mb_strlen($newstring, 'UTF-8'), 'UTF-8');
	$newstring	= str_replace('&lt;br&gt;', "\n", $newstring);
   $newstring	= str_replace('<br />', "", $newstring);
   $newstring	= str_replace('<br/>', "", $newstring);
	return $newstring;
}
function convertDateTime($strDate = "", $strTime = ""){
	//Break string and create array date time
	$array_replace	= array("/", ":");
	$strDate			= str_replace($array_replace, "-", $strDate);
	$strTime			= str_replace($array_replace, "-", $strTime);
	$strDateArray	= explode("-", $strDate);
	$strTimeArray	= explode("-", $strTime);
	$countDateArr	= count($strDateArray);
	$countTimeArr	= count($strTimeArray);

	//Get Current date time
	$today			= getdate();
	$day				= $today["mday"];
	$mon				= $today["mon"];
	$year				= $today["year"];
	$hour				= $today["hours"];
	$min				= $today["minutes"];
	$sec				= $today["seconds"];
	//Get date array
	switch($countDateArr){
		case 2:
			$day		= intval($strDateArray[0]);
			$mon		= intval($strDateArray[1]);
			break;
		case $countDateArr >= 3:
			$day		= intval($strDateArray[0]);
			$mon		= intval($strDateArray[1]);
			$year 	= intval($strDateArray[2]);
			break;
	}
	//Get time array
	switch($countTimeArr){
		case 2:
			$hour		= intval($strTimeArray[0]);
			$min		= intval($strTimeArray[1]);
			break;
		case $countTimeArr >= 3:
			$hour		= intval($strTimeArray[0]);
			$min		= intval($strTimeArray[1]);
			$sec		= intval($strTimeArray[2]);
			break;
	}
	//Return date time integer
	if(@mktime($hour, $min, $sec, $mon, $day, $year) == -1) return $today[0];
	else return mktime($hour, $min, $sec, $mon, $day, $year);
}

function removeTitle($string,$keyReplace = "/"){
	 $string = removeAccent($string);
	 $string  =  trim(preg_replace("/[^A-Za-z0-9]/i"," ",$string)); // khong dau
	 $string  =  str_replace(" ","-",$string);
	 $string = str_replace("--","-",$string);
	 $string = str_replace("--","-",$string);
	 $string = str_replace("--","-",$string);
	 $string = str_replace("--","-",$string);
	 $string = str_replace("--","-",$string);
	 $string = str_replace("--","-",$string);
	 $string = str_replace("--","-",$string);
	 $string = str_replace($keyReplace,"-",$string);
	 return strtolower($string);
}


/**
 * 18/09/2015
 * killlove
 * Create cache file category, city
 * @input: array()
 */
function createCache($data = array(), $file_name = 'cat'){
	if(is_array($data) && count($data) > 0){
		$dataCache	= json_encode($data);
		if($file_name == 'cat') $pathFile	= '../../../caches/cache_category.cl';
		if($file_name == 'cit') $pathFile	= '../../../caches/cache_city.cl';
      if($file_name == 'tag') $pathFile	= '../../../caches/cache_tag.cl';

		if(@file_put_contents($pathFile, $dataCache)){
	 		return 1;
	 	}else{
	 		return 0;
	 	}

	}
	return 0;
}

/**
 * 18/9/2015
 * killlove
 * Get cache category, city
 * @input: cat or cit
 * default cat
 */
function getCache( $obj = 'cat'){
	$fileCache	= '';
	$data			= '';
	$arrayFile	= array();

	if($obj == 'cit') $fileCache = 'cache_city.cl';
	if($obj == 'cat') $fileCache = 'cache_category.cl';
   if($obj == 'tag') $fileCache = 'cache_tag.cl';
	$pathFile	= '../caches/' . $fileCache;

	$data			= @file_get_contents($pathFile);
	//$data			= stripcslashes($data);
	$arrayFile	= json_decode($data, 1);
	return $arrayFile;
}

/**
 * 18/9/2015
 * killlove
 * Function get cache of job new most
 * 10 minute set cache again
 * @input: @number job
 * @input: $file : new or hot
 */
 function getCacheJob($number = 20, $file, $cat_id = 0){
 	//global $arrCacheName;
 	$filecache	= '';
 	$fileTime	= 0;
 	(intval($number) > 0)? $number	: 10;
 	$filecache	= '../caches/cache_job_' . $file . (($cat_id > 0)? '_' . $cat_id : '') . '.cache';
	if(@file_exists($filecache)) $fileTime	= filemtime($filecache);

	$time_select	= 0;
	if($_SERVER['SERVER_NAME'] != 'localhost'){
		$time_select = time() - 20 * 86400;
	}

	if( (time() - $fileTime > (20 * 60)) || !file_exists($filecache) || ($fileTime == 0) ){
		$arrDataCache	= array();
      $sql_where  = " AND job_active = 1 AND job_date_expires > ". time();
      $sql_order  = " ORDER BY job_id DESC ";
      
      if($cat_id > 0){
         $sql_where .= " AND (job_cat_1 = " . intval($cat_id) . " OR job_cat_2 = " . intval($cat_id) . ") ";
      }
      $limit      = $number;
      switch($file){
         case 'new':
            $sql_where  .= " AND job_date_create > ". $time_select;
            break;
         case 'interesting':
            $sql_where  .= " AND job_packet_id = ". VL_PAYMENT_PACKET_INTERESTING . " AND job_packet_end > " . time();
            break;
         case 'focus':
            $sql_where  .= " AND job_packet_id = ". VL_PAYMENT_PACKET_FOCUS . " AND job_packet_end > " . time();
            break;
         case 'hot':
            $sql_where  .= " AND job_packet_id = ". VL_PAYMENT_PACKET_HOT . " AND job_packet_end > " . time();
            break;
         case 'supperhot':
            $sql_where  .= " AND job_packet_id = ". VL_PAYMENT_PACKET_SUPPER_HOT . " AND job_packet_end > " . time();
            break;
         case 'home_hot':
            $sql_where  .= " AND job_packet_id > ". VL_PAYMENT_PACKET_FOCUS . " AND job_packet_end > " . time();
            $sql_order  = " ORDER BY job_packet_id DESC ";
            break;
         case 'list_focus':
            $sql_where  .= " AND job_packet_id > ". VL_PAYMENT_PACKET_INTERESTING . " AND job_packet_end > " . time();
            $sql_order  = " ORDER BY job_packet_id DESC ";
            break;
         case 'verified' :
            $sql_where  .= " AND job_ip > 0 ";
            break;
      }
      
      $db_job	= new db_query("SELECT * FROM job      
										WHERE 1 ". $sql_where . $sql_order ."
										LIMIT " . $limit
										,__FILE__ . "Line: " . __LINE__);
   	while($rjob	= mysqli_fetch_assoc($db_job->result)){
   		$arrDataCache[$rjob['job_id']]	= $rjob;
   	}
   	unset($db_job);
      

		// nếu có lấy được dữ liệu thì tống vào file cace
		if(count($arrDataCache) > 0){
			@file_put_contents($filecache, json_encode($arrDataCache));
			return $arrDataCache;
		}
	}else{
		$arrDataCache	= array();
		$data			= @file_get_contents($filecache);
		//$data			= stripcslashes($data);
		$arrDataCache	= json_decode($data, 1);
		return $arrDataCache;
	}

 }// end funtion
 
 

function generatePageBar($page_prefix, $current_page, $page_size, $total_record, $url, $normal_class, $selected_class, $previous='<', $next='>', $first='<<', $last='>>', $break_type=1, $page_rewrite=0, $page_space=3, $obj_response='', $page_name="page"){

	$page_query_string	= "&" . $page_name . "=";
	// Nếu dùng ModRewrite thì dùng dấu , để phân trang
	if($page_rewrite == 1) $page_query_string = ",";

	if($total_record % $page_size == 0) $num_of_page = $total_record / $page_size;
	else $num_of_page = (int)($total_record / $page_size) + 1;

	if($page_space > 4) $page_space = 3;
	if($page_space < 1) $page_space = 3;

	$start_page = $current_page - $page_space;
	if($start_page <= 0) $start_page = 1;

	$end_page = $current_page + $page_space;
	if($end_page > $num_of_page) $end_page = $num_of_page;

	// Remove XSS
	$url = str_replace('\"', '"', $url);
	$url = str_replace('"', '', $url);

	if($break_type < 1) $break_type = 1;
	if($break_type > 4) $break_type = 4;

	// Pagebreak bar
	$page_bar = "";

	// Write prefix on screen
	if($page_prefix != "") $page_bar .= '<span class="vl_page' . $normal_class . '">' . $page_prefix . '</span> ';

	// Write frist page
	if($break_type == 1){
		if(($start_page != 1) && ($num_of_page > 1)){
			if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . '1' . '\',\'' . $obj_response . '\')';
			else $href = $url . $page_query_string . '1';
			$page_bar .=  '<a href="' . $href . '" class="' . $normal_class . ' firstpage" title="First page">' . $first . '</a> ';
		}
	}

	// Write previous page
	if($break_type == 1 || $break_type == 2 || $break_type == 4){
		if(($current_page > 1) && ($num_of_page > 1)){
			if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . ($current_page - 1) . '\',\'' . $obj_response . '\')';
			else $href = $url . $page_query_string . ($current_page - 1);
			$page_bar .= ' <a href="' . $href . '" class="' . $normal_class . ' prevpage" title="Prev page">' . $previous . '</a> ';
			if(($start_page > 1) && ($break_type == 1 || $break_type == 2)){
				$page_dot_before = $start_page - 1;
				if($page_dot_before < 1) $page_dot_before = 1;
				if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . $page_dot_before . '\',\'' . $obj_response . '\')';
				else $href = $url . $page_query_string . $page_dot_before;
				$page_bar .= '<a href="' . $href . '" class="' . $normal_class . ' notUndeline">...</a> ';
			}
		}
	}

	// Write page numbers
	if($break_type == 1 || $break_type == 2 || $break_type == 3){
		$start_loop = $start_page;
		if($break_type == 3) $start_loop = 1;
		$end_loop	= $end_page;
		if($break_type == 3) $end_loop = $num_of_page;
		for($i=$start_loop; $i<=$end_loop; $i++){
			if($i != $current_page){
				if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . $i . '\',\'' . $obj_response . '\')';
				else $href = $url . $page_query_string . $i;
				$page_bar .= ' <a href="' . $href . '" class="' . $normal_class . '">' . $i . '</a> ';
			}
			else{
				$page_bar .= ' <span class="vl_page ' . $normal_class . ' ' . $selected_class . '">' . $i . '</span> ';
			}
		}
	}

	// Write next page
	if($break_type == 1 || $break_type == 2 || $break_type == 4){
		if(($current_page < $num_of_page) && ($num_of_page > 1)){
			if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . ($current_page + 1) . '\',\'' . $obj_response . '\')';
			else $href = $url . $page_query_string . ($current_page + 1);
			if(($end_page < $num_of_page) && ($break_type == 1 || $break_type == 2)){
				$page_dot_after = $end_page + 1;
				if($page_dot_after > $num_of_page) $page_dot_after = $num_of_page;
				if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . $page_dot_after . '\',\'' . $obj_response . '\')';
				else $href = $url . $page_query_string . $page_dot_after;
				$page_bar .= '<a href="' . $href . '" class="' . $normal_class . ' notUndeline">...</a> ';
			}
			$page_bar .= ' <a href="' . $href . '" class="' . $normal_class . ' nextpage" title="Next page">' . $next . '</a> ';
		}
	}

	// Write last page
	if($break_type == 1){
		if(($end_page < $num_of_page) && ($num_of_page > 1)){
			if($obj_response != '') $href = 'javascript:load_data(\'' . $url . $page_query_string . $num_of_page . '\',\'' . $obj_response . '\')';
			else $href = $url . $page_query_string . $num_of_page;
			$page_bar .= ' <a href="' . $href . '" class="' . $normal_class . ' lastpage" title="Last page">' . $last . '</a>';
		}
	}

	// Return pagebreak bar
	return $page_bar;

}
// loại hình công việc
function generate_job_type(){
	$arrayJobType	= array(
      '0' => 'Chọn loại hình công việc',
		'1' => "Giờ hành chính",
		'2' => "Làm theo ca",
		'3' => "Bán thời gian",
		'4' => "Nhân viên thời vụ",
		'5' => "Thực tập dự án",
		'6' => "Khác"
	);

	return $arrayJobType;
}

// job level
function generate_job_level(){
	$arrayJobLevel	= array(
      '0' => 'Chọn trình độ',
		'1' => "Giáo sư, Tiến sĩ, Thạc sĩ",
		'2' => "Cử nhân đại học",
		'3' => "Cao đẳng",
		'4' => "Trung cấp",
		'5' => "Cấp 3 (THPT)",
		'6' => "Cấp 2 (THCS)",
		'7' => "Không yêu cầu"
	);

	return $arrayJobLevel;
}

// job salary
function generate_job_salary(){
	$arrayJobSalary	= array(
      '0' => 'Chọn mức lương',
		'1' => "> 40 triệu",
		'2' => "> 30 triệu",
		'3' => "> 20 triệu",
		'4' => "> 15 triệu",
		'5' => "10 => 15 triệu",
		'6' => "8 => 10 triệu",
		'7' => "6 => 8 triệu",
		'8' => "5 => 7 triệu",
		'9' => "4 => 6 triệu",
		'10' => "2,5 => 4 triệu",
		'11' => "3 => 5 triệu",
		'12' => "1 => 3 triệu",
		'13' => "Thỏa thuận"
	);

	return $arrayJobSalary;
}

// job experience
function generate_job_experience(){
	$arrayJobExperience	= array(
      '0' => 'Chọn kinh nghiệm',
		'1' => "> 10 năm kinh nghiệm",
		'2' => "> 9 năm kinh nghiệm",
		'3' => "> 8 năm kinh nghiệm",
		'4' => "> 7 năm kinh nghiệm",
		'5' => "> 6 năm kinh nghiệm",
		'6' => "> 5 năm kinh nghiệm",
		'7' => "> 4 năm kinh nghiệm",
		'8' => "> 3 năm kinh nghiệm",
		'9' => "> 2 năm kinh nghiệm",
		'10' => "> 1 năm kinh nghiệm",
		'11' => "Không cần kinh nghiệm"
	);

	return $arrayJobExperience;
}

// job gender
function generate_job_gender(){
	$arrayJobGender	= array(
      '0' => 'Chọn giới tính',
		'1' => "Nam",
		'2' => "Nữ",
		'3' => "Không phân biệt"
	);

	return $arrayJobGender;
}

// job rank
function generate_job_rank(){
	$arrayJobRank	= array(
      '0' => 'Chọn cấp bậc',
		'1' => "Thực tập",
		'2' => "Nhân viên",
		'3' => "Team leader",
      '4' => "Quản lý dự án",
      '5' => "Trưởng phòng",
      '6' => "Giám đốc và cao hơn"
	);

	return $arrayJobRank;
}

// register type user
function generate_type_user(){
	$arrayTypeUser	= array(
		0 => 'Ứng viên',
		1 => 'Nhà tuyển dụng'
	);
	return $arrayTypeUser;
}

//hàm tính số ngày còn hạn
function show_date_expires($date = 0){
   $time = $date - time();
   $text = '';
   if($time <= 0){
      return '<span class="price bg_time">Hết hạn</span>';
   }
   
   $day  = floor($time/86400);   
   return '<span class="cl9 bg_time">Còn '. $day .' ngày</span>';
}

/**
 * Cập nhật trạng thái active un-active
 * array(
 *    uid
 *    admin
 *    job_id
 * )
 */
function set_Active_Un_active($data = array()){
   $user_id = isset($data['uid'])? intval($data['uid']) : 0;
   $admin_id   = isset($data['admin'])? intval($data['admin']) : 0;
   $job_id  = isset($data['job_id'])? intval($data['job_id']) : 0;
   
   
   
   $db_job  = new db_query("SELECT * FROM jobs WHERE job_id = " . intval($job_id) . " LIMIT 1", __FILE__ . " LINE: " . __LINE__);
   if($row  = mysqli_fetch_assoc($db_job->result)){
      if($row['job_user_id'] != $user_id || $admin_id == 0){
         return 0;
      }else{
         
         $value   = abs(1-$row['job_active']);
         $db_update  = new db_execute("UPDATE jobs SET job_active = " . intval($value) . " 
                                       WHERE job_id = " . intval($job_id), __FILE__ . " LINE: " . __LINE__);
         unset($db_update);
         
         $db_update  = new db_execute("UPDATE jobs_fillter SET jobf_active = " . intval($value) . "
                                       WHERE jobf_id = " . intval($job_id), __FILE__ . " LINE: " . __LINE__);
         unset($db_update);
         
         $table_job  = 'jobs_' . intval($job_id/2000);
         $db_jup  = new db_execute("UPDATE " . $table_job . " SET job_active = " . intval($value) . "
                                    AND jobf_active = " . intval($value) . " WHERE job_id = " . intval($job_id), __FILE__ . " LINE: " . __LINE__);
         unset($db_jup);
         
         return 1;
            
      }
   }else{
      return 0;
   }
   unset($db_job);
}

/**
 * Function check phone
 */

function check_phone($phone = ''){
   $leng = strlen($phone);
   if($leng < 10 || $leng > 12){
      return 0;
   }
   
   $f = substr($phone, 0, 2);
   if($f == '09' && $leng != 10) return 0;
   if($f!='09' && $leng!=11) return 0;
   
   return 1;
}

/**
 * Function check email
 */
function isValidEmail($email){ 
   return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Function save log error php
 * 
 * array(
 *    'file_name' => 
 *    'title' =>
 *    'error' => 
 *    'use_id' =>
 * )
 */
 
function savelog_error($row = array()){
   $file_name  = isset($row['file_name'])? $row['file_name'] : '';
   $title  = isset($row['title'])? $row['title'] : '';
   $error  = isset($row['error'])? $row['error'] : '';
   $use_id  = isset($row['use_id'])? $row['use_id'] : '';
   
   $db_ex   = new db_execute("INSERT INTO logs_php (log_file_name,log_title,log_use_id,log_error,log_date)
                              VALUES('". $file_name ."','". $title ."',". $use_id .",'". $error ."',". time() .")", __FILE__ . " Line: " . __LINE__);
   unset($db_ex);
}

/**
 * Send email to employer
 *  
 */
function send_employer_email($row = array()){
   $content = '';
   $mail = new PHPMailer();
   $mail->setMail('smtp');
   if(!is_array($row)) return;
   $path = '../temp_mail/temp_apply.html';
   if(file_exists($path)){
      $content = file_get_contents($path);
   }
   
   if($content == '') return;
   
   
   /*
   'employer_name' => $employer_name, // ten nguoi nhan
   'employer_email' => $employer_email, // email nguoi nhan
   'post_title' => $jname, // ten cong viec
   'ungvien_name' => $ungvien_name, // ten ung vien
   'post_url'  => ($url != '')? 'http://' . $webname . $url : '', // url viec lam
   'company_name' => $company_name // ten cong ty
   */
   // gắn biến config
   foreach($row as $k => $vl){
      $$k = $vl;
      $content = str_replace('{'.$k.'}', $vl, $content);
   }
   
   if(!isset($employer_email) || !isset($employer_name) || !isset($ungvien_name)) return;
   
   
   // lấy emal gửi có thời gian bé nhất để gửi
   $send_email = '';
   $send_pass  = '';
   $send_id    = 0;
   $reply_email   = 'lienhe.1viec@gmail.com';
   $db_mail    = new db_query("SELECT * FROM send ORDER BY sen_date ASC LIMIT 1");
   if($rmail   = mysqli_fetch_assoc($db_mail->result)){
      $send_email = $rmail['sen_email'];
      $send_pass  = $rmail['sen_pass'];
      $send_id    = $rmail['sen_id'];
   }
   unset($db_mail);
   
   $mail->Host = "smtp.gmail.com"; // 
   $mail->Port = 465;
   $mail->SMTPAuth = true;
   $mail->SMTPSecure = 'ssl'; // ssl hoac tls, neu su dung tls thi $mail->Port la: 587
   $mail->Username = $send_email; // tai khoan dang nhap de gui email 
   $mail->Password = $send_pass;            // mat khau gui email
   
   $mail->From = $send_email; // email se duoc thay the email trong smtp
   $mail->AddReplyTo($reply_email);  // email cho phep nguoi dung reply lai
   $mail->FromName = "Mạng Tuyển Dụng"; // ho ten nguoi gui email
   
   $mail->WordWrap = 50; 
   $mail->IsHTML('text/html');     //text/html | text/plain, default:text/html 
   
   $mail->AltBody = "Website tuyển dụng"; //Text Body
   $mail->SMTPDebug = 2;
   
   $mail->Body = $content; //HTML Body
   $mail->Subject = $ungvien_name . ' ứng tuyển: ' . $post_title;
   $mail->AddAddress($employer_email); // email nguoi nhan
   
   $mail->Send();
   $mail->ClearAddresses();
   
   // cap nhat so tin dc gui di
   $db_update   = new db_execute("UPDATE send SET sen_count = sen_count + 1, sen_date = " . time() . " WHERE sen_id = " . $send_id);
   unset($db_update);
   
   return;// $mail;
   
}



function get_sapo($id){
   global $vl_class,$arrayCity;
   $array_return  = array(
      'sapo' => '',
      'city' => ''
   );
   $str = '';
   $cit = '';
   
   if($id <= 0) return $array_return;
   $table_job  = $vl_class->create_table_job($id);
   
   
   $db_detail	= new db_query("SELECT job_short_description,job_cit_all
										FROM ". $table_job . "
										WHERE 1  AND job_id =". $id, __FILE__ . " Line:" . __LINE__);
   if($row  = mysqli_fetch_assoc($db_detail->result)){
      $arr  = explode(' ', $row['job_short_description']);
      $new_arr = array_slice($arr,0,50);
      $str  = implode(' ', $new_arr);
      $str  = str_replace(array('<br>', '- ','&lt;br&gt;', '+'), '', $str) .' ...';
      
      if(isset($row['job_cit_all']) && $row['job_cit_all'] != ''){
         $arrCitAll  = explode('|', $row['job_cit_all']);
         $arrLinkCit = array();
         foreach($arrCitAll as $cit){
            if(isset($arrayCity[$cit])){
               $cit_name   = isset($arrayCity[$cit]['cit_name'])? $arrayCity[$cit]['cit_name'] : 'Toàn quốc';
               $arrLinkCit[] = $cit_name;
            }
         }
         $cit = implode(', ', $arrLinkCit);
         
   	}
   }
   unset($db_detail);
   
   $array_return  = array(
      'sapo' => $str,
      'city' => $cit
   );
   
   return $array_return;
}

// hàm tính % hoàn thành hồ sơ của ứng viên
function caculator_cv($uid = 0){
   $id   = intval($uid);
   if($id <= 0) return 0;
   
   $percentSuccess	= 0;   
   $total_field      = 0;
   $field_success    = 0;
   
   $db_field   = new db_query("SELECT * FROM user_cv_field WHERE ucf_use_id = " . intval($id) . " LIMIT 1");
   if($rfield  = $db_field->fetch()){
      $total_field = count($rfield) - 2;
      foreach($rfield as $k => $vl){
         if($k == 'ucf_id' || $k == 'ucf_use_id'){
            continue;
         }else{
            $field_success++;
         }
      }
   }   
   unset($db_field);
   
   if($field_success > 0){
   	$percentSuccess = ceil( ($field_success * 100)/$total_field );
   }
   
   return $percentSuccess;
}

/**
 * Generate avatar user
 */
function generate_avatar($id = '', $type = 'small'){
   if($id != ''){
      return '//1viec.com/avatar/'. $type .'/'. $id .'.jpg';
   }
}

/**
 * Hàm return key search việc làm liên quan
 */
function return_keysearch($str = ''){
   $keys = '';
   if($str != ''){
      $str  = mb_strtolower($str, 'UTF-8');
      $str  = trim($str);
      $str = preg_replace ('/' . preg_quote('[') . '.*?' . preg_quote(']') . '/', '', $str);      
      $str = preg_replace ('/' . preg_quote('{') . '.*?' . preg_quote('}') . '/', '', $str);
      $str = preg_replace ('/' . preg_quote('<') . '.*?' . preg_quote('>') . '/', '', $str);
      $str = preg_replace ('/' . preg_quote('(') . '.*?' . preg_quote(')') . '/', '', $str);
      $str  = trim($str);
      $str  = str_replace(array(',',':','(', ')','*','-','_','{', '}','<','>','?','&','%','$','#','@','!','~','+','[',']',"\/", "\\",'.'), ' ', $str);
      
      $arrstr = explode('không', $str);
      if(is_array($arrstr)){
         $str  = $arrstr[0];
      }
      $arrstr = explode('lương', $str);
      if(is_array($arrstr)){
         $str  = $arrstr[0];
      }
      $arrstr = explode('làm việc', $str);
      if(is_array($arrstr)){
         $str  = $arrstr[0];
      }
      $arrstr = explode('thu nhập', $str);
      if(is_array($arrstr)){
         $str  = $arrstr[0];
      }
      $arrstr = explode('chi nhánh', $str);
      if(is_array($arrstr)){
         $str  = $arrstr[0];
      }
      $arrstr = explode('quận', $str);
      if(is_array($arrstr)){
         $str  = $arrstr[0];
      }
      $arrstr = explode('làm việc', $str);
      if(is_array($arrstr)){
         $str  = $arrstr[0];
      }
      $arrstr = explode('khu vực', $str);
      if(is_array($arrstr)){
         $str  = $arrstr[0];
      }
      $arrstr = explode('bán thời', $str);
      if(is_array($arrstr)){
         $str  = $arrstr[0];
      }
      
      $keys = str_replace(array('tuyển gấp','cần tuyển','tuyển','tìm','tp hcm','hcm','hn'), '', $str);
      $keys = str_replace('  ', ' ', $keys);
      $keys = str_replace('  ', ' ', $keys);
      $keys = trim($keys);
      $arr  = explode(' ', $keys);
      if(count($arr) > 6){
         $new_arr = array_slice($arr, 0, 6, true);
         $keys = implode(' ', $new_arr);
      }
   }
   
   return $keys;
}

// bóc tác từ khóa từ google
function fix_latinutf8($str){
   return preg_replace_callback('#[\\xA1-\\xFF](?![\\x80-\\xBF]{2,})#', 'utf8_encode_callback', $str);
}

function utf8_encode_callback($m){
   return utf8_encode($m[0]);
}

function getSuggestions($key = ''){
   $link = 'http://suggestqueries.google.com/complete/search?output=toolbar&hl=en&q=' . urlencode($key);
   
   $xml  = file_get_contents($link);
   $xml  = fix_latinutf8($xml);
   
   $arrReturn  = array();
   $google_xml = new SimpleXMLElement($xml);
   foreach($google_xml->CompleteSuggestion as $complate_suggest)
   {
      $arrReturn[] =  (string)$complate_suggest->suggestion->attributes()->data;
   }
   
   return $arrReturn;
}


// functun save mining
function save_mining($data = array()){
   
   return;
   $cat_id = isset($data['cat_id'])? intval($data['cat_id']) : 0;
   $cit_id = isset($data['cit_id'])? intval($data['cit_id']) : 0;
   $use_id = isset($data['use_id'])? intval($data['use_id']) : 0;
   $cookie = isset($data['cookie'])? ($data['cookie']) : '';
   $title = isset($data['title'])? ($data['title']) : ''; 
   
   if($use_id <= 0 && $cookie == ''){
      return;
   }
   
   $db_ex   = new db_execute("INSERT IGNORE INTO source_mining (som_use_id, som_cat_id, som_cit_id, som_cookie, som_date, som_title) VALUES 
                              (". intval($use_id) .",". intval($cat_id) .", ". intval($cit_id) .",'". replaceMQ($cookie) ."',". time() .",'". replaceMQ($title) ."')", __FILE__, DB_NOTIFICATION);
   unset($db_ex);
   
   
}


/**
 * Ham làm đẹp search
 */
function cleanCvSeach($str){
   $new_string = '';
   if($str != ''){
      $new_string = removeAccent($str);
      $new_string = strtolower($new_string);
      $arrayReplace = array(
         'dai hoc','cao dang','trung cap','cong ty','tnhh','co phan'
      );
      $new_string = str_replace($arrayReplace,'', $new_string);
      $new_string = str_replace('  ', ' ', $new_string);
      $new_string = str_replace('| ', '|', $new_string);
      $new_string = trim($new_string);
   }
   
   return $new_string;
}

function wit_cut_message($str){
   
   $str = trim(preg_replace('/\s\s+/', ' ', $str));
   $str = str_replace(array('&', "\n"), ' ', $str);
   $array_message = array();
   
   
   if(mb_strlen($str, 'UTF-8') < 255){
      $array_message[] = $str;
   }else{
      
      $arraykey   = array(
         'tuyển','nhân viên'
      );
      
      
      foreach($arraykey as $k){
         $start = 0;
         if(strpos($str, $k) !== false){
            $start  = strpos($str, $k);
            $array_message[] = substr($str, $start, 250);
         }
         
      }
      
      $strlen= mb_strlen($str, 'UTF-8');
      $count   = intval($strlen/250);
      if($count >= 1){
         for($i = 0; $i < $count; $i++){
            $array_message[] = substr($str, $i*250, 250);
         }
      }
      
   }
   
   return $array_message;
}

?>