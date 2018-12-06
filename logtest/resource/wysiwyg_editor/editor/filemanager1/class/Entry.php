<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

include_once('Tools.php');

/**
 * This class manages directory listing entries.
 *
 * @package FileManager
 * @subpackage class
 * @author Gerd Tentler
 */
class Entry {

/* PUBLIC PROPERTIES *************************************************************************** */

	/**
	 * file name
	 *
	 * @var string
	 */
	var $name;

	/**
	 * file owner
	 *
	 * @var string
	 */
	var $owner;

	/**
	 * file group
	 *
	 * @var string
	 */
	var $group;

	/**
	 * file size
	 *
	 * @var string
	 */
	var $size;

	/**
	 * last modified
	 *
	 * @var string
	 */
	var $changed;

	/**
	 * file permissions
	 *
	 * @var string
	 */
	var $permissions;

	/**
	 * file icon
	 *
	 * @var string
	 */
	var $icon;

	/**
	 * file path
	 *
	 * @var string
	 */
	var $path;

	/**
	 * thumbnail hash
	 *
	 * @var string
	 */
	var $thumbHash;

	/**
	 * thumbnail width
	 *
	 * @var integer
	 */
	var $thumbWidth;

	/**
	 * thumbnail height
	 *
	 * @var integer
	 */
	var $thumbHeight;

	/**
	 * stores entry ID
	 *
	 * @var integer
	 */
	var $id;

	/**
	 * symbolic link target - works only on local file system
	 *
	 * @var string
	 */
	var $target;

/* PROTECTED PROPERTIES ************************************************************************ */

	/**
	 * holds FileManager object
	 *
	 * @var FileManager
	 */
	var $FileManager;

	/**
	 * holds listing object
	 *
	 * @var Listing
	 */
	var $Listing;

/* PUBLIC METHODS ****************************************************************************** */

	/**
	 * constructor
	 *
	 * @param Listing $Listing
	 * @return Entry
	 */
	function Entry(&$Listing) {
		$this->Listing =& $Listing;
		$this->FileManager =& $this->Listing->FileManager;
	}

	/**
	 * check if entry is a directory
	 *
	 * @return boolean
	 */
	function isDir() {
		return ($this->icon == 'dir');
	}

	/**
	 * rename file or directory
	 *
	 * @param string $dst		new file/directory path
	 * @return boolean
	 */
	function rename($dst) {
		return $this->Listing->FileSystem->rename($this->path, $dst);
	}

	/**
	 * delete file
	 *
	 * @return boolean
	 */
	function deleteFile() {
		return $this->Listing->FileSystem->deleteFile($this->path);
	}

	/**
	 * save file data
	 *
	 * @param string $data		file data
	 * @return boolean
	 */
	function saveFile(&$data) {
		return $this->Listing->FileSystem->writeFile($this->path, $data);
	}

	/**
	 * change file permissions
	 *
	 * @param integer $mode		new mode
	 * @return boolean
	 */
	function changePerms($mode) {
		return $this->Listing->FileSystem->changePerms($this->path, $mode);
	}

	/**
	 * get file permissions
	 *
	 * @return string			permissions
	 */
	function getPerms() {
		if($this->FileManager->ftpHost) {
			return $this->permissions;
		}
		$file = $this->path;
		if(is_dir($file)) {
			$perms = 'd';
			$rwx = substr(decoct(@fileperms($file)), 2);
		}
		else {
			$perms = '-';
			$rwx = substr(decoct(@fileperms($file)), 3);
		}
		for($i = 0; $i < strlen($rwx); $i++) {
			switch($rwx[$i]) {
				case 1: $perms .= '--x'; break;
				case 2: $perms .= '-w-'; break;
				case 3: $perms .= '-wx'; break;
				case 4: $perms .= 'r--'; break;
				case 5: $perms .= 'r-x'; break;
				case 6: $perms .= 'rw-'; break;
				case 7: $perms .= 'rwx'; break;
				default: $perms .= '---';
			}
		}
		return $perms;
	}

	/**
	 * send file for download
	 *
	 * @return boolean		false on failure
	 */
	function sendFile() {
		$file = $this->getFile();
		if(is_file($file)) {
			$filename = $this->name;
			if($this->FileManager->replSpacesDownload) {
				$filename = str_replace(' ', '_', $filename);
			}
			if($this->FileManager->lowerCaseDownload) {
				$filename = strtolower($filename);
			}
			if($this->FileManager->mailOnDownload) {
				$date = date('Y-m-d H:i:s');
				$ip = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : 'n/a';
				$body = "The following file has been downloaded on $date by IP address $ip:\n\n";
				$body .= $this->path . ' ' . $this->size . " B\n";
				@mail($this->FileManager->mailOnDownload, 'FileManager Download Info', $body);
			}
			header("Content-Type: application/octet-stream");
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Cache-Control: private, no-cache, must-revalidate');
			header('Expires: 0');
			readfile($file);
			exit;
		}
		return false;
	}

	/**
	 * get image path
	 *
	 * @return string		local path
	 */
	function getImagePath() {
		if($this->FileManager->ftpHost) {
			$cachePath = $this->FileManager->cacheDir . '/'. $this->name;
			if(!is_file($cachePath)) {
				return $this->getFile($this->FileManager->cacheDir);
			}
			return $cachePath;
		}
		return $this->path;
	}

	/**
	 * get thumbnail size and type
	 *
	 * @param integer $maxWidth			optional: max. width
	 * @param integer $maxHeight		optional: max. height
	 * @return array					thumbnail size and type
	 */
	function getThumbSize($maxWidth = 0, $maxHeight = 0) {
		$file = $this->getImagePath();
		list($width, $height, $type) = @getimagesize($file);

		if($type == 1 || $type == 2 || $type == 3) {
			if($maxWidth && $width > $maxWidth) {
				$perc = $maxWidth / $width;
				$width = round($width * $perc);
				$height = round($height * $perc);
			}
			if($maxHeight && $height > $maxHeight) {
				$perc = $maxHeight / $height;
				$width = round($width * $perc);
				$height = round($height * $perc);
			}
		}
		return array($width, $height, $type);
	}

	/**
	 * get file path; loads file from FTP server if necessary
	 *
	 * @param string $dstDir	optional: destination directory
	 * @return string			file path
	 */
	function getFile($dstDir = '') {
		if($dstDir == '') $dstDir = $this->FileManager->tmpDir;
		$dstPath = $dstDir . '/' . $this->name;
		return $this->Listing->FileSystem->getFile($this->path, $dstPath);
	}

	/**
	 * set properties from local file path or FTP listing row
	 *
	 * @param string $file		local file path or FTP listing row
	 * @param string $dir		directory path
	 * @return boolean
	 */
	function setProperties($file, $dir) {
		if($this->FileManager->ftpHost) {
			$sysType = preg_match('/winnt|windows/i', $this->Listing->sysType) ? 'Windows' : 'UNIX';
		}
		else $sysType = 'PHP';

		switch($sysType) {

			case 'UNIX':
				if(preg_match('/^([drwxst\-]{10}) +\d+ +([^ ]+) +([^ ]+) +(\d+) +(\w{3} +\d+ +(\d{2,4} )?[\d\:]{4,5}) +(.+)$/i', $file, $m)) {
					if($m[7] == '..' || $m[7] == '.') return false;
					$changed = $m[6] ? $m[5] : preg_replace('/([\d\:]{4,5})$/', date('Y') . " $1", $m[5]);
					$tstamp = strtotime($changed);
					$this->permissions = $m[1];
					$this->owner = $m[2];
					$this->group = $m[3];
					$this->size = $m[4];
					$this->changed = ($tstamp !== false && $tstamp != -1) ? date('Y-m-d H:i', $tstamp) : $m[5];
					$this->name = $m[7];
					$this->icon = ($this->permissions[0] == 'd') ? 'dir' : '';
					$this->path = $dir . '/' . $this->name;
					return true;
				}
				break;

			case 'Windows':
				if(preg_match('/^([\d\.\-]+) +([\d\:]{5}[PA]?M?) +(<DIR>|[\d\.]+) +(.+)$/i', $file, $m)) {
					if($m[4] == '..' || $m[4] == '.') return false;
					$isDir = (strtoupper($m[3]) == '<DIR>');
					$t = explode(':', $m[2]);
					if(preg_match('/[AP]M$/', strtoupper($t[1]), $m2)) {
						$t[1] = (int) $t[1];
						if($m2[0] == 'PM') $t[0] += 12;
					}
					if(strstr($m[1], '-')) {
						$d = explode('-', $m[1]);
						$tstamp = mktime($t[0], $t[1], 0, $d[0], $d[1], $d[2]);
					}
					else {
						$d = explode('.', $m[1]);
						$tstamp = mktime($t[0], $t[1], 0, $d[1], $d[0], $d[2]);
					}
					$this->changed = $tstamp ? date('Y-m-d H:i', $tstamp) : $m[1] . ' ' . $m[2];
					$this->permissions = '';
					$this->size = $isDir ? 0 : str_replace('.', '', $m[3]);
					$this->name = $m[4];
					$this->icon = $isDir ? 'dir' : '';
					$this->path = $dir . '/' . $this->name;
					return true;
				}
				break;

			case 'PHP':
				$filename = Tools::basename($file, $this->FileManager->encoding);
				if($filename == '.' || $filename == '..') return false;
				$this->owner = @fileowner($file);
				$this->group = @filegroup($file);
				$this->size = @filesize($file);
				$this->changed = date('Y-m-d H:i', @filemtime($file));
				$this->name = $filename;
				$this->icon = is_dir($file) ? 'dir' : '';
				$this->path = $dir . '/' . $this->name;
				$this->permissions = $this->getPerms();
				if(is_link($file)) $this->target = @realpath($file);
				return true;
		}
		return false;
	}

/* PROTECTED METHODS *************************************************************************** */

	/**
	 * get action
	 *
	 * @param string $type			'get', 'info' or 'menu'
	 * @param boolean $addSlashes	optional: add slashes to action and caption
	 * @return array				JavaScript action, caption
	 */
	function getAction($type, $addSlashes = false) {
		global $msg;

		$cont = $this->FileManager->container;
		$url = $this->FileManager->fmWebPath . "/action.php?fmContainer=$cont";
		$action = $caption = '';

		switch(strtolower($type)) {

			case 'get':
				switch($this->icon) {

					case 'cdup':
						if($this->Listing->searchString != '') {
							$action = "fmCall('$url&fmMode=search')";
							$caption = $msg['cmdGoBack'];
						}
						else {
							$action = "fmCall('$url&fmMode=parent&fmObject=$this->id')";
							$caption = $msg['cmdParentDir'];
						}
						break;

					case 'dir':
						$action = "fmCall('$url&fmMode=open&fmObject=$this->id')";
						$caption = $msg['cmdChangeDir'];
						break;

					default:
						if($this->FileManager->enableDownload) {
							$action = "fmGetFile('$url&fmMode=getFile&fmObject=$this->id')";
							$caption = $msg['cmdGetFile'];
						}
				}
				break;

			case 'info':
				if($this->name != '..') {
					if($this->FileManager->hideFilePath) {
						$name = addslashes($this->name);
					}
					else {
						$path = substr($this->path, strlen($this->FileManager->startDir), strlen($this->path));
						$name = addslashes($path);
					}

					if(!$this->FileManager->hideLinkTarget && $this->target != '') {
						$name .= addslashes(' => ' . $this->target);
					}
					$thumb = $this->thumbHash ? $this->thumbHash : $this->icon . '_big.gif';
					$action = "fmFileInfo('$cont', '$this->id', '$name', '$this->permissions', '$this->owner', '$this->group', ";
					$action .= "'$this->changed', '$this->size', '$thumb', '$this->thumbWidth', '$this->thumbHeight')";
					$caption = $msg['cmdFileInfo'];
				}
				break;

			case 'menu':
				if($this->icon == 'cdup') {
					list($action, $caption) = $this->getAction('get');
				}
				else {
					$name = $this->name;
					$max = (Tools::containsBigChars($this->name)) ? 15 : 25;

					if(Tools::strlen($name, $this->FileManager->encoding) > $max) {
						$name = Tools::substr($name, 0, $max, $this->FileManager->encoding) . '...';
					}
					$name = addslashes($name);
					$items = $this->getMenuItems();
					$action = "fmMenu('$name', $items)";
				}
				break;

		}

		if($addSlashes) {
			$action = addslashes($action);
			$caption = addslashes($caption);
		}
		return array($action, $caption);
	}

	/**
	 * get menu items
	 *
	 * @return string $items		menu items
	 */
	 function subPath1($path){
		$path = str_replace($_SERVER['DOCUMENT_ROOT'],"",$path);
		return $path;
	}
	function getMenuItems() {
		global $msg;

		$cont = $this->FileManager->container;
		$url = $this->FileManager->fmWebPath . '/action.php?fmContainer=' . $cont;
		$items = array();
                
                #Add by ALD
                if( $this->icon != 'dir' && $this->icon != 'cdup' )
                {
                    $icon    = $this->FileManager->fmWebPath . '/icons/select-16.png';
                    $caption = $msg['cmdSelectFile'];
                    $action  = "fmSelect(\'".$this->subPath1($this->path)."\')";
                    $items[] = "{icon: '$icon', caption: '$caption', action: '$action'}";   
                }
                #End add by ALD

		$icon = $this->FileManager->fmWebPath . '/icons/' . $this->icon . '.gif';
		list($action, $caption) = $this->getAction('get', true);
		$items[] = "{icon: '$icon', width: 12, caption: '$caption', action: '$action'}";

		$icon = $this->FileManager->fmWebPath . '/icons/info.gif';
		list($action, $caption) = $this->getAction('info', true);
		$items[] = "{icon: '$icon', caption: '$caption', action: '$action'}";

		if($this->FileManager->enableRename) {
			$icon    = $this->FileManager->fmWebPath . '/icons/rename.gif';
			$caption = $msg['cmdRename'];
			$name    = addslashes($this->name);
			$title   = addslashes($msg['cmdRename']) . ': ' . $name;
			$action  = addslashes("fmOpenDialog('$url', 'fmRename', '$title', '$this->id', '$name')");
			$items[] = "{icon: '$icon', caption: '$caption', action: '$action'}";
		}
		else if(!$this->FileManager->hideDisabledIcons) {
			$icon    = $this->FileManager->fmWebPath . '/icons/rename_x.gif';
			$caption = $msg['cmdRename'];
			$items[] = "{icon: '$icon', caption: '$caption', action: ''}";
		}

		if($this->FileManager->enablePermissions) {
			$icon    = $this->FileManager->fmWebPath . '/icons/permissions.gif';
			$caption = $msg['cmdChangePerm'];
			$name    = addslashes($this->name);
			$title   = addslashes($msg['cmdChangePerm']) . ': ' . $name;
			$action  = addslashes("fmOpenDialog('$url', 'fmPerm', '$title', '$this->id', '$name', '$this->permissions')");
			$items[] = "{icon: '$icon', caption: '$caption', action: '$action'}";
		}
		else if(!$this->FileManager->hideDisabledIcons) {
			$icon    = $this->FileManager->fmWebPath . '/icons/permissions_x.gif';
			$caption = $msg['cmdChangePerm'];
			$items[] = "{icon: '$icon', caption: '$caption', action: ''}";
		}

		if($this->FileManager->enableDelete) {
			if($this->isDir()) $confirm = addslashes($msg['msgRemoveDir']);
			else $confirm = addslashes($msg['msgDeleteFile']);
			$icon = $this->FileManager->fmWebPath . '/icons/delete.gif';
			$caption = $msg['cmdDelete'];
			$name = addslashes($this->name);
			$title = addslashes($msg['cmdDelete']) . ': ' . $name;
			$action = addslashes("fmOpenDialog('$url', 'fmDelete', ['$title', '$confirm'], '$this->id')");
			$items[] = "{icon: '$icon', caption: '$caption', action: '$action'}";
		}
		else if(!$this->FileManager->hideDisabledIcons) {
			$icon = $this->FileManager->fmWebPath . '/icons/delete_x.gif';
			$caption = $msg['cmdDelete'];
			$items[] = "{icon: '$icon', caption: '$caption', action: ''}";
		}

		if($this->icon == 'text') {
			if($this->FileManager->enableEdit) {
				$icon = $this->FileManager->fmWebPath . '/icons/edit.gif';
				$caption = $msg['cmdEdit'];
				$action = addslashes("fmCall('$url&fmMode=edit&fmObject=$this->id')");
				$items[] = "{icon: '$icon', caption: '$caption', action: '$action'}";
			}
			else if(!$this->FileManager->hideDisabledIcons) {
				$icon = $this->FileManager->fmWebPath . '/icons/edit_x.gif';
				$caption = $msg['cmdEdit'];
				$items[] = "{icon: '$icon', caption: '$caption', action: ''}";
			}
		}
                $items = count($items) ? implode(', ', $items) : '';
                
		return "[$items]";
	}
}

?>