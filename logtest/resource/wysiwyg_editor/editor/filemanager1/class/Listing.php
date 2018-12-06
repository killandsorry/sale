<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

include_once('Entry.php');
include_once('FileSystem.php');
include_once('Tools.php');
include_once('Explorer.php');

/**
 * This class manages directory listings.
 *
 * @package FileManager
 * @subpackage class
 * @author Gerd Tentler
 */
class Listing {

/* PUBLIC PROPERTIES *************************************************************************** */

	/**
	 * current directory path
	 *
	 * @var string
	 */
	var $curDir;

	/**
	 * previous directory path
	 *
	 * @var string
	 */
	var $prevDir;

	/**
	 * holds current search string
	 *
	 * @var string
	 */
	var $searchString;

	/**
	 * current sort field
	 *
	 * @var string
	 */
	var $sortField = 'isDir';

	/**
	 * current sort order ('asc' or 'desc')
	 *
	 * @var string
	 */
	var $sortOrder = 'asc';

	/**
	 * max. file name length in entry list
	 *
	 * @var integer
	 */
	var $nameMaxLen;

	/**
	 * listing width in pixels
	 *
	 * @var integer
	 */
	var $listWidth;

	/**
	 * listing height in pixels
	 *
	 * @var integer
	 */
	var $listHeight;

	/**
	 * holds FileSystem object
	 *
	 * @var FileSystem
	 */
	var $FileSystem;

	/**
	 * holds FileManager object
	 *
	 * @var FileManager
	 */
	var $FileManager;

	/**
	 * holds Explorer object
	 *
	 * @var Explorer
	 */
	var $Explorer;

/* PROTECTED PROPERTIES ************************************************************************ */

	/**
	 * holds OS type
	 *
	 * @var string
	 */
	var $sysType;

	/**
	 * holds current listing (entry objects)
	 *
	 * @var array
	 */
	var $entries;

	/**
	 * file extensions
	 *
	 * @var array
	 */
	var $extensions = array(
		'text'		=> '(txt)|([sp]?html?)|(css)|(jse?)|(php\d*)|(pr?l)|(pm)|(cgi)|(inc)|(csv)|(py)|(asp)|(ini)|(sql)',
		'image'		=> '(gif)|(jpe?g)|(png)|(w?bmp)|(tiff?)|(pict?)|(ico)',
		'archive'	=> '(zip)|([rtj]ar)|(t?gz)|(t?bz2?)|(arj)|(ace)|(lzh)|(lha)|(xxe)|(uue?)|(iso)|(cab)|(r\d+)',
		'program'	=> '(exe)|(com)|(pif)|(bat)|(scr)|(app)',
		'acrobat'	=> '(pd[fx])',
		'word'		=> '(do[ct])|(do[ct]html)',
		'excel'		=> '(xl[stwv])|(xl[st]html)|(slk)'
	);

/* PUBLIC METHODS ****************************************************************************** */

	/**
	 * constructor
	 *
	 * @param FileManager $FileManager		file manager object
	 * @param string $dir					optional: directory path
	 * @param string $search				optional: search string
	 * @return Listing
	 */
	function Listing(&$FileManager, $dir = '', $search = '') {
		$this->FileManager =& $FileManager;
		$this->FileSystem =& new FileSystem($FileManager);
		$this->curDir = ($dir != '') ? $dir : $this->FileManager->startDir;
		$this->searchString = $search;
		$this->sysType = $this->FileSystem->getSystemType();
		$this->listWidth = $this->FileManager->fmWidth - $this->FileManager->explorerWidth;
		$this->listHeight = $this->FileManager->fmHeight - 22;
		if($this->FileManager->logHeight > 0) $this->listHeight -= $this->FileManager->logHeight + 9;
		if($this->FileManager->debugInfo) $this->listHeight -= 151;
	}

	/**
	 * view current listing
	 */
	function view() {
		$startDir = $this->FileManager->startDir;
		$subdir = (
			strlen($this->curDir) > strlen($startDir) &&
			strncmp($this->curDir, $startDir, strlen($startDir)) == 0
		);
		if($subdir || $this->searchString != '') $this->viewDirUp();

		if(is_array($this->entries)) foreach($this->entries as $Entry) {
			$Entry->view();
		}
	}

	/**
	 * refresh listing
	 *
	 * @return boolean
	 */
	function refresh() {
		$this->entries = array();
		$ok = $this->readDir($this->curDir);
		$this->view();
		return $ok;
	}

	/**
	 * sort entries
	 */
	function sortList() {
		$arr = $this->entries;
		$cnt = count($arr);
		$prop = $this->sortField;
		$swap = true;

		while($cnt && $swap) {
			$swap = false;
			for($i = 0; $i < $cnt; $i++) {
				for($j = $i; $j < $cnt - 1; $j++) {
					if($prop == 'isDir') {
						$noDir = ($arr[$j]->icon != 'dir') ? 1 : 0;
						$str1 = strtolower($noDir . $arr[$j]->name);
						$noDir = ($arr[$j + 1]->icon != 'dir') ? 1 : 0;
						$str2 = strtolower($noDir . $arr[$j + 1]->name);
					}
					else {
						$str1 = strtolower($arr[$j]->$prop);
						$str2 = strtolower($arr[$j + 1]->$prop);
					}

					if(($this->sortOrder == 'asc' && $str1 > $str2) ||
						($this->sortOrder == 'desc' && $str1 < $str2)) {
						$temp = $arr[$j];
						$arr[$j] = $arr[$j+1];
						$arr[$j+1] = $temp;
						$swap = true;
					}
				}
			}
			$cnt--;
		}
		$this->entries = $arr;
	}

	/**
	 * get entry by ID
	 *
	 * @param integer $id		entry ID
	 * @return mixed			entry object or false on failure
	 */
	function &getEntry($id) {
		if(is_array($this->entries)) foreach(array_keys($this->entries) as $ind) {
			$Entry =& $this->entries[$ind];
			if($Entry->id == $id) return $Entry;
		}
		return false;
	}

	/**
	 * get entry by file/directory name
	 *
	 * @param string $name		file/directory name
	 * @return mixed			entry object or false on failure
	 */
	function &getEntryByName($name) {
		if(is_array($this->entries)) foreach(array_keys($this->entries) as $ind) {
			$Entry =& $this->entries[$ind];
			if($Entry->name == $name) return $Entry;
		}
		$file = $this->curDir . '/' . $name;
		if(file_exists($file)) return $this->addEntry($file);
		else return false;
	}

	/**
	 * move uploaded file to current directory
	 *
	 * @param string $src		source file path
	 * @param string $newName	new file name
	 * @return boolean
	 */
	function upload($src, $newName) {
		if($this->FileManager->hideSystemFiles && $newName[0] == '.') {
			return false;
		}

		/* check if file extension is allowed */
		$ext = strtolower(end(explode('.', $newName)));
		if($ext != '') {
			$hidden = $this->FileManager->hideFileTypes;
			$allowed = $this->FileManager->allowFileTypes;

			if(in_array($ext, $hidden) || ($allowed && !in_array($ext, $allowed))) {
				return false;
			}
		}

		if($this->FileManager->createBackups) {
			$this->createBackup($newName);
		}
		$dst = $this->curDir . '/' . $newName;
		return $this->FileSystem->putFile($src, $dst);
	}

	/**
	 * remove directory
	 *
	 * @param string $dir		directory path
	 * @return boolean
	 */
	function remDir($dir) {
		return $this->FileSystem->removeDir($dir);
	}

	/**
	 * create directory
	 *
	 * @param string $dir		directory path
	 * @return boolean
	 */
	function mkDir($dir) {
		return $this->FileSystem->makeDir($dir);
	}

	/**
	 * perform search
	 *
	 * @param string $text		search string
	 */
	function performSearch($text) {
		$this->searchString = $text;
		$this->refresh();
	}

	/**
	 * check if directory access is allowed
	 *
	 * @param string $dir		directory path
	 * @return boolean
	 */
	function isAllowedDir($dir) {
		$enc = $this->FileManager->encoding;
		if(Tools::dirname($dir, $enc) == $this->FileManager->startDir) {
			$allowedDirs = $this->FileManager->startSubDirs;
			if($allowedDirs && is_array($allowedDirs)) {
				if(!in_array(Tools::basename($dir, $enc), $allowedDirs)) {
					return false;
				}
			}
		}
		return true;
	}

/* PROTECTED METHODS *************************************************************************** */

	/**
	 * view header
	 */
	function viewHeader() {
		global $msg;

		$webPath = $this->FileManager->fmWebPath;
		$url = $webPath . '/action.php?fmContainer=' . $this->FileManager->container;
		$colspan = ($this->FileManager->explorerWidth > 0) ? ' colspan="2"' : '';

		print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"{$this->FileManager->fmWidth}\">\n";
		print "<tr>\n";
		print "<td class=\"fmTH1\" align=\"left\" style=\"padding:4px\"$colspan>\n";
		print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n";
		print "<tr>\n";
		print "<td class=\"fmTH1\">";
		$this->viewTitle();
		print "</td>\n";
		print "<td class=\"fmTH1\" width=\"18\" align=\"right\">";
		$action = "fmCall('$url&fmMode=refresh')";
		Tools::printIcon("$webPath/icons/refresh.gif", 11, 14, $action, $msg['cmdRefresh'], 'cursor:pointer');
		print "</td>\n";

		if(strtolower($this->FileManager->fmView) == 'icons') {
			print "<td class=\"fmTH1\" width=\"18\" align=\"right\">";
			$action = "fmCall('$url&fmMode=switchView')";
			Tools::printIcon("$webPath/icons/list_details.gif", 11, 14, $action, $msg['cmdDetails'], 'cursor:pointer');
			print "</td>\n";
		}
		else if(strtolower($this->FileManager->fmView) == 'details') {
			print "<td class=\"fmTH1\" width=\"18\" align=\"right\">";
			$action = "fmCall('$url&fmMode=switchView')";
			Tools::printIcon("$webPath/icons/list_icons.gif", 11, 14, $action, $msg['cmdIcons'], 'cursor:pointer');
			print "</td>\n";
		}

		print "<td class=\"fmTH1\" width=\"20\" align=\"right\">";
		$action = "fmOpenDialog('$url', 'fmSearch', '" . addslashes($msg['cmdSearch']) . "')";
		Tools::printIcon("$webPath/icons/search.gif", 13, 14, $action, $msg['cmdSearch'], 'cursor:pointer');
		print "</td>\n";

		if($this->FileManager->enableNewDir && $this->searchString == '') {
			print "<td class=\"fmTH1\" width=\"22\" align=\"right\">";
	 		$action = "fmOpenDialog('$url', 'fmNewDir', '" . addslashes($msg['cmdNewDir']) . "')";
			Tools::printIcon("$webPath/icons/newDir.gif", 15, 14, $action, $msg['cmdNewDir'], 'cursor:pointer');
			print "</td>\n";
		}
		else if(!$this->FileManager->hideDisabledIcons) {
			$error = addslashes($msg['cmdNewDir'] . ': ' . $msg['errDisabled']);
			print "<td class=\"fmTH1\" width=\"22\" align=\"right\">";
			$action = "fmOpenDialog('', 'fmError', '$error')";
			Tools::printIcon("$webPath/icons/newDir_x.gif", 15, 14, $action);
		}

		if($this->FileManager->enableUpload && $this->searchString == '') {
			print "<td class=\"fmTH1\" width=\"18\" align=\"right\">";
			$action = "fmOpenDialog('$url', 'fmNewFile', '" . addslashes($msg['cmdUploadFile']) . "')";
			Tools::printIcon("$webPath/icons/new.gif", 11, 14, $action, $msg['cmdUploadFile'], 'cursor:pointer');
			print "</td>\n";
		}
		else if(!$this->FileManager->hideDisabledIcons) {
			$error = addslashes($msg['cmdUploadFile'] . ': ' . $msg['errDisabled']);
			print "<td class=\"fmTH1\" width=\"18\" align=\"right\">";
			$action = "fmOpenDialog('', 'fmError', '$error')";
			Tools::printIcon("$webPath/icons/new_x.gif", 11, 14, $action);
		}

		print "</tr></table>\n";
		print "</td>\n";
		print "</tr>\n";
		print "<tr>\n";
		print "<td class=\"fmTD1\">\n";
		print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n";
		print "<tr valign=\"top\">\n";

		if($this->FileManager->explorerWidth > 0) {
			print "<td><div class=\"fmTD2\" style=\"width:{$this->FileManager->explorerWidth}px; ";
			print "height:{$this->listHeight}px; overflow:auto\">\n";
			if(!$this->Explorer) $this->Explorer = new Explorer($this);
			$this->Explorer->view();
			print "</div></td>\n";
		}
		print "<td><div style=\"width:{$this->listWidth}px; height:{$this->listHeight}px; overflow:auto\">\n";
	}

	/**
	 * view footer
	 */
	function viewFooter() {
		print "</div></td>\n";
		print "</tr>\n";
		print "</table>\n";
		print "</td>\n";
		print "</tr>\n";
		print "</table>\n";
	}

	/**
	 * view title
	 */
	function viewTitle() {
		global $msg;

		if($this->searchString != '') {
			$path = $msg['searchResult'] . ': ' . $this->searchString;
		}
		else $path = substr($this->curDir, strlen($this->FileManager->startDir));
		if($path == '') $path = '/';

		if(!$this->FileManager->hideSystemType) {
			if(Tools::strlen($this->sysType, $this->FileManager->encoding) > 15) {
				$sysType = Tools::substr($this->sysType, 0, 15, $this->FileManager->encoding) . '...';
			}
			else $sysType = $this->sysType;

			print "[$sysType] $path";
		}
		else print $path;
	}

	/**
	 * create new entry; this method should be overwritten
	 */
	function &newEntry() {
		return new Entry($this);
	}

	/**
	 * read directory entries
	 *
	 * @param string $dir		directory path
	 * @return boolean
	 */
	function readDir($dir) {
		$startDir = $this->FileManager->startDir;
		if(strncmp($dir, $startDir, strlen($startDir)) != 0) {
			$dir = $this->curDir = $startDir;
		}
		$list = $this->FileSystem->readDir($dir);

		if(!is_array($list)) {
			if($this->curDir != $startDir) {
				$this->curDir = $startDir;
				$this->readDir($startDir);
			}
			return false;
		}

		foreach($list as $row) {
			$Entry = $this->addEntry($row, $dir);
			if(is_object($Entry)) {
				if($this->searchString != '' && $Entry->isDir()) {
					$this->readDir($Entry->path);
				}
			}
			else if(is_string($Entry)) $this->readDir("$dir/$Entry");
		}
		return true;
	}

	/**
	 * add listing entry
	 *
	 * @param string $file			file path or entry in FTP listing
	 * @param string $dir			optional: directory path
	 * @return mixed				entry object, directory name or false
	 */
	function &addEntry($file, $dir = '') {
		if($dir == '') $dir = $this->curDir;

		/* if search is performed, $Entry will just contain the directory name */
		$Entry = $this->createEntry($file, $dir);

		if(is_object($Entry)) {
			if($Entry->isDir()) {
				/* check if directory access is allowed */
				if(!$this->isAllowedDir($Entry->path)) return false;
			}
			else {
				/* check if file extension is allowed */
				$ext = strtolower(end(explode('.', $Entry->name)));
				if($ext != '') {
					$hidden = $this->FileManager->hideFileTypes;
					$allowed = $this->FileManager->allowFileTypes;

					if(in_array($ext, $hidden) || ($allowed && !in_array($ext, $allowed))) {
						return false;
					}
				}
			}
			$Entry->thumbHash = '';
			$Entry->thumbWidth = $Entry->thumbHeight = 0;

			if(!$Entry->icon) {
				if($this->isType($ext, $this->extensions['text'])) $Entry->icon = 'text';
				else if($this->isType($ext, $this->extensions['image'])) $Entry->icon = 'image';
				else if($this->isType($ext, $this->extensions['archive'])) $Entry->icon = 'archive';
				else if($this->isType($ext, $this->extensions['program'])) $Entry->icon = 'exe';
				else if($this->isType($ext, $this->extensions['acrobat'])) $Entry->icon = 'acrobat';
				else if($this->isType($ext, $this->extensions['word'])) $Entry->icon = 'word';
				else if($this->isType($ext, $this->extensions['excel'])) $Entry->icon = 'excel';
				else $Entry->icon = 'file';

				if(in_array(strtolower($ext), array('jpeg', 'jpg', 'gif', 'png'))) {
					list($width, $height, $type) = $Entry->getThumbSize(
						$this->FileManager->thumbMaxWidth,
						$this->FileManager->thumbMaxHeight
					);
					if($type == 1 || $type == 2 || $type == 3) {
						$Entry->thumbHash = md5($Entry->path);
						$Entry->thumbWidth = $width;
						$Entry->thumbHeight = $height;
					}
				}
			}
			$Entry->id = count($this->entries);
			$this->entries[] =& $Entry;
		}
		else if(is_string($Entry)) {
			/* check if directory access is allowed */
			if(!$this->isAllowedDir($Entry)) return false;
		}
		return $Entry;
	}

	/**
	 * create entry, but don't add it to the current listing
	 *
	 * @param string $file			local file path or FTP listing row
	 * @param string $dir			directory path
	 * @return mixed				entry object, directory name or false
	 */
	function &createEntry($file, $dir) {
		$Entry =& $this->newEntry();
		if($Entry->setProperties($file, $dir)) {
			if($this->searchString != '') {
				if(stristr($Entry->name, $this->searchString)) {
					return $Entry;
				}
				else if($Entry->isDir()) return $Entry->name;
			}
			else if(!$this->FileManager->hideSystemFiles || $Entry->name[0] != '.') {
				return $Entry;
			}
		}
		return false;
	}

	/**
	 * check file type
	 *
	 * @param string $ext		file extension
	 * @param string $types		list of file types
	 * @return boolean
	 */
	function isType($ext, $types) {
		return preg_match('/^(' . $types . ')$/i', $ext);
	}

	/**
	 * create backup by renaming original file
	 *
	 * @param string $fileName		file name
	 */
	function createBackup($fileName) {
		$parts = explode('.', $fileName);
		if(count($parts) > 1) {
			$ext = '.' . end($parts);
			$enc = $this->FileManager->encoding;
			$name = Tools::substr($fileName, 0, Tools::strlen($fileName, $enc) - Tools::strlen($ext, $enc));
		}
		else {
			$ext = '';
			$name = $fileName;
		}
		$backupName = $fileName;
		$cnt = 0;

		while($this->getEntryByName($backupName)) {
			$cnt++;
			$backupName = $name . "($cnt)$ext";
		}

		if($cnt > 0) {
			$this->FileSystem->rename($this->curDir . '/' . $fileName, $this->curDir . '/' . $backupName);
		}
	}
}

?>