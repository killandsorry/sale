<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

/**
 * This class provides static methods for general use.
 */
class Tools {

/* PUBLIC STATIC METHODS *********************************************************************** */

	/**
	 * delete files from local directory
	 *
	 * @param string $dir	directory
	 */
	function cleanDir($dir) {
		if($dp = @opendir($dir)) {
			while(($file = @readdir($dp)) !== false) {
				if($file != '.' && $file != '..') @unlink("$dir/$file");
			}
			@closedir($dp);
		}
	}

	/**
	 * get number of files in local directory
	 *
	 * @param string $dir	directory
	 * @return integer
	 */
	function getFileCount($dir) {
		$cnt = 0;
		if($dp = @opendir($dir)) {
			while(($file = @readdir($dp)) !== false) {
				if($file != '.' && $file != '..') $cnt++;
			}
			@closedir($dp);
		}
		return $cnt;
	}

	/**
	 * read local file
	 *
	 * @param string $file		file path
	 * @param string $encoding	optional: character set, example: ISO-8859-1
	 * @return string $data		file data
	 */
	function readLocalFile($file, $encoding = '') {
		$data = '';
		if($fp = @fopen($file, 'r')) {
			$data = @fread($fp, filesize($file));
			@fclose($fp);
		}
		return ($encoding != '') ? Tools::utf8Decode($data, $encoding) : $data;
	}

	/**
	 * save local file
	 *
	 * @param string $path		file path
	 * @param string $data		file data
	 * @param string $encoding	optional: character set, example: ISO-8859-1
	 * @return boolean
	 */
	function saveLocalFile($path, &$data, $encoding = '') {
		$ok = false;
		if($encoding != '') $data = Tools::utf8Decode($data, $encoding);

		if($fp = @fopen($path, 'w')) {
			$ok = @fwrite($fp, $data);
			@fclose($fp);
		}
		return $ok;
	}

	/**
	 * check if string contains 3 or 4 byte characters (Chinese etc.);
	 * usually these characters have a bigger width than the characters
	 * of the Latin alphabet
	 *
	 * @param string $str		the string
	 * @return boolean
	 */
	function containsBigChars($str) {
		return preg_match('/[\xE0-\xF4][\x80-\xBF]{2,3}/', $str);
	}

	/**
	 * check if string contains UTF-8 characters
	 *
	 * @param string $str	the string
	 * @return boolean
	 */
	function isUtf8($str) {
		return preg_match('%(?:
			[\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
			|\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
			|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
			|\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
			|\xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
			|[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
			|\xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
			)%x', $str);
	}

	/**
	 * decode string if it contains UTF-8 characters
	 *
	 * @param string $str		the string
	 * @param string $encoding	optional: character set, example: ISO-8859-1
	 * @return string			decoded string
	 */
	function utf8Decode($str, $encoding = '') {
		if(Tools::isUtf8($str) && $encoding != 'UTF-8') {
			if(function_exists('iconv')) {
				if($encoding == '') $encoding = 'ISO-8859-1';
				$str = iconv('UTF-8', "$encoding//TRANSLIT", $str);
			}
			else $str = utf8_decode($str);
		}
		return $str;
	}

	/**
	 * print icon
	 *
	 * @param string $path			image path
	 * @param integer $width		image width (pixels)
	 * @param integer $height		image height (pixels)
	 * @param string $action		optional: JavaScript action
	 * @param string $tooltip		optional: tooltip
	 * @param string $style			optional: CSS styles
	 */
	function printIcon($path, $width, $height, $action = '', $tooltip = '', $style = '') {
		print "<img src=\"$path\" border=\"0\" width=\"$width\" height=\"$height\"";
		if($action) print " onClick=\"$action\"";
		if($tooltip) print " alt=\"$tooltip\" title=\"$tooltip\"";
		if($style) print " style=\"$style\"";
		print "/>\n";
	}

	/**
	 * strlen() that works also with UTF-8 strings
	 *
	 * @param string $str			the string
	 * @param string $encoding		optional: encoding
	 * @return integer				number of characters
	 */
	function strlen($str, $encoding = '') {
		if($encoding == 'UTF-8') {
			if(function_exists('mb_strlen')) {
				return mb_strlen($str, $encoding);
			}
			return preg_match_all('/.{1}/us', $str, $m);
		}
		return strlen($str);
	}

	/**
	 * substr() that works also with UTF-8 strings
	 *
	 * @param string $str			the string
	 * @param integer $start		start position
	 * @param integer $length		optional: number of characters
	 * @param string $encoding		optional: encoding
	 * @return string				the substring
	 */
	function substr($str, $start, $length = null, $encoding = '') {
		if($encoding == 'UTF-8') {
			if(function_exists('mb_substr')) {
				return mb_substr($str, $start, $length, $encoding);
			}
			if(!$length) $length = Tools::strlen($str) - $start;
			preg_match('/.{' . $start . '}(.{' . $length . '})/us', $str, $m);
			return $m[1];
		}
		return substr($str, $start, $length);
	}

	/**
	 * basename() that works also with UTF-8 strings
	 *
	 * @param string $path			file path
	 * @param string $encoding		optional: encoding
	 * @return string
	 */
	function basename($path, $encoding = '') {
		if($encoding == 'UTF-8') {
			return end(explode('/', $path));
		}
		return basename($path);
	}

	/**
	 * dirname() that works also with UTF-8 strings
	 *
	 * @param string $path			file path
	 * @param string $encoding		optional: encoding
	 * @return string
	 */
	function dirname($path, $encoding = '') {
		if($encoding == 'UTF-8') {
			$parts = explode('/', $path);
			array_pop($parts);
			return join('/', $parts);
		}
		return dirname($path);
	}
	
	/**
	* Make a string safely
	* Add by ALD
	*/
	function safe_string($string, $separator='_')
	{
	    //Remove any $separator from the string they will be used as concatonater
	    $str = str_replace($separator, ' ', $string);
	    
	    $utf8characters = 'à|a, ả|a, ã|a, á|a, ạ|a, ă|a, ằ|a, ẳ|a, ẵ|a, ắ|a, ặ|a, â|a, ầ|a, ẩ|a, ẫ|a, ấ|a, ậ|a, đ|d, è|e, ẻ|e, ẽ|e, é|e, ẹ|e, ê|e, ề|e, ể|e, ễ|e, ế|e, ệ|e, ì|i, ỉ|i, ĩ|i, í|i, ị|i, ò|o, ỏ|o, õ|o, ó|o, ọ|o, ô|o, ồ|o, ổ|o, ỗ|o, ố|o, ộ|o, ơ|o, ờ|o, ở|o, ỡ|o, ớ|o, ợ|o, ù|u, ủ|u, ũ|u, ú|u, ụ|u, ư|u, ừ|u, ử|u, ữ|u, ứ|u, ự|u, ỳ|y, ỷ|y, ỹ|y, ý|y, ỵ|y, À|A, Ả|A, Ã|A, Á|A, Ạ|A, Ă|A, Ằ|A, Ẳ|A, Ẵ|A, Ắ|A, Ặ|A, Â|A, Ầ|A, Ẩ|A, Ẫ|A, Ấ|A, Ậ|A, Đ|D, È|E, Ẻ|E, Ẽ|E, É|E, Ẹ|E, Ê|E, Ề|E, Ể|E, Ễ|E, Ế|E, Ệ|E, Ì|I, Ỉ|I, Ĩ|I, Í|I, Ị|I, Ò|O, Ỏ|O, Õ|O, Ó|O, Ọ|O, Ô|O, Ồ|O, Ổ|O, Ỗ|O, Ố|O, Ộ|O, Ơ|O, Ờ|O, Ở|O, Ỡ|O, Ớ|O, Ợ|O, Ù|U, Ủ|U, Ũ|U, Ú|U, Ụ|U, Ư|U, Ừ|U, Ử|U, Ữ|U, Ứ|U, Ự|U, Ỳ|Y, Ỷ|Y, Ỹ|Y, Ý|Y, Ỵ|Y, "|, &|';
	    $replacements   = array();
	    $items          = explode(',', $utf8characters);
	    foreach ($items as $item) {
		@list($src, $dst) = explode('|', trim($item));
		$replacements[trim($src)] = trim($dst);
	    }
	    $str = strtr($str, $replacements);
    
	    // remove any duplicate whitespace, and ensure all characters are alphanumeric
	    $str = preg_replace(array("/\s+/","/[^A-Za-z0-9\{$$separator}]/"), array($separator,''), $str);
    
	    // lowercase and trim
	    $str = trim(strtolower($str));
	    
	    return $str;
	}
}

?>
