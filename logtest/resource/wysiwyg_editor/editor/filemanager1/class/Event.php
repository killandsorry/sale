<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

include_once('Image.php');
include_once('Editor.php');
include_once('Tools.php');

/**
 * This class handles user events.
 *
 * @package FileManager
 * @subpackage class
 * @author Gerd Tentler
 */
class Event {

/* PRIVATE PROPERTIES ************************************************************************** */

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
	 * @param FileManager $FileManager
	 * @return Event
	 */
	function Event(&$FileManager) {
		$this->FileManager =& $FileManager;
		$this->Listing =& $FileManager->getListing();
	}

	/**
	 * handle event
	 *
	 * @param string $type		event type
	 * @param string $id		optional: object ID(s)
	 * @param string $param		optional: additional parameter(s)
	 * @return string
	 */
	function handle($type, $id = '', $param = '') {
		switch($type) {

			case 'sort':
				list($column, $order) = explode(',', $param);
				return $this->sortListing($column, $order);

			case 'open':
				return $this->openDir($id);

			case 'expOpen':
				return $this->openExpDir($id);

			case 'getFile':
				return $this->getFile($id);

			case 'getThumbnail':
				return $this->getThumbnail($id);

			case 'parent':
				return $this->parentDir();

			case 'rename':
				return $this->rename($id, $param);

			case 'delete':
				return $this->delete($id);

			case 'newDir':
				return $this->newDir($param);

			case 'newFile':
				return $this->newFile();

			case 'refresh':
				return $this->refresh();

			case 'permissions':
				return $this->changePermissions($id, $_REQUEST['fmPerms']);

			case 'edit':
				return $this->editFile($id);

			case 'search':
				return $this->search($param);

			case 'switchView':
				return $this->switchView();
		}
		$this->Listing->view();
		return '';
	}

/* PRIVATE METHODS ***************************************************************************** */

	/**
	 * sort listing by column
	 *
	 * @param string $column
	 * @param sring $order
	 */
	function sortListing($column, $order) {
		$this->Listing->sortField = $column;
		$this->Listing->sortOrder = $order;
		$this->Listing->view();
	}

	/**
	 * open directory
	 *
	 * @param integer $id
	 * @return string
	 */
	function openDir($id) {
		global $msg;

		if($Entry =& $this->Listing->getEntry($id)) {
			if($Entry->isDir()) {
				$this->Listing->prevDir = $this->Listing->curDir;
				$this->Listing->curDir = $Entry->path;
				$this->Listing->searchString = '';
				Tools::cleanDir($this->FileManager->cacheDir);

				if(!$this->Listing->refresh()) {
					return $msg['errOpen'] . ": $Entry->name";
				}
			}
		}
		return '';
	}

	/**
	 * open explorer directory
	 *
	 * @param integer $id
	 * @return string
	 */
	function openExpDir($id) {
		global $msg;

		if($entry = $this->Listing->Explorer->folders[$id]) {
			$this->Listing->prevDir = $this->Listing->curDir;
			$this->Listing->curDir = $entry[1];
			$this->Listing->searchString = '';
			Tools::cleanDir($this->FileManager->cacheDir);

			if(!$this->Listing->refresh()) {
				return $msg['errOpen'] . ': ' . Tools::basename($entry[1], $this->FileManager->encoding);
			}
		}
		return '';
	}

	/**
	 * get file
	 *
	 * @param integer $id
	 * @return string
	 */
	function getFile($id) {
		global $msg;

		if($this->FileManager->enableDownload && $id != '') {
			if($Entry =& $this->Listing->getEntry($id)) {
				if(!$Entry->sendFile()) {
					$this->Listing->view();
					return $msg['errOpen'] . ": $Entry->name";
				}
			}
		}
		return '';
	}

	/**
	 * get thumbnail
	 *
	 * @param integer $id
	 */
	function getThumbnail($id) {
		global $msg;

		if($Entry =& $this->Listing->getEntry($id)) {
			$Image = new Image($Entry->getImagePath(), $_REQUEST['width'], $_REQUEST['height']);
			$Image->view();
		}
	}

	/**
	 * return to parent directory
	 */
	function parentDir() {
		$this->Listing->prevDir = $this->Listing->curDir;
		$this->Listing->curDir = preg_replace('%/[^/]+$%', '', $this->Listing->curDir);
		$this->Listing->searchString = '';
		Tools::cleanDir($this->FileManager->cacheDir);
		$this->Listing->refresh();
	}

	/**
	 * rename file / directory
	 *
	 * @param integer $id
	 * @param string $name
	 * @return string
	 */
	function rename($id, $name) {
		global $msg;

		if($this->FileManager->enableRename && $name != '' && $id != '') {
			if($Entry =& $this->Listing->getEntry($id)) {
				$path = Tools::dirname($Entry->path, $this->FileManager->encoding);
				if(get_magic_quotes_gpc()) $name = stripslashes($name);
				$name = Tools::basename($name, $this->FileManager->encoding);

				if(!$Entry->rename("$path/$name")) {
					$this->Listing->view();
					return $msg['errRename'] . ": $Entry->name &raquo; $name";
				}
				if($Entry->isDir()) $this->Listing->Explorer = null;
			}
		}
		$this->Listing->refresh();
		return '';
	}

	/**
	 * delete files / directories
	 *
	 * @param string $ids
	 * @return string
	 */
	function delete($ids) {
		global $msg;

		$errors = array();
		if($this->FileManager->enableDelete && $ids != '') {
			foreach(explode(',', $ids) as $id) {
				if($Entry =& $this->Listing->getEntry($id)) {
					if($Entry->isDir()) {
						if(!$this->Listing->remDir($Entry->path)) {
							$errors[] = $msg['errDelete'] . ": $Entry->name";
						}
						else $this->Listing->Explorer = null;
					}
					else if(!$Entry->deleteFile()) {
						$errors[] = $msg['errDelete'] . ": $Entry->name";
					}
				}
			}
		}
		$this->Listing->refresh();
		return join('<br/>', $errors);
	}

	/**
	 * create new directory
	 *
	 * @param string $name
	 * @return string
	 */
	function newDir($name) {
		global $msg;

		if($this->FileManager->enableNewDir) {
			if($name != '') {
				if(get_magic_quotes_gpc()) $name = stripslashes($name);
				$name = str_replace('\\', '/', $name);
				$dirs = explode('/', $name);
				$dir = '';

				for($i = 0; $i < count($dirs); $i++) {
					if($dirs[$i] != '') {
						if($dir != '') $dir .= '/';
						$dir .= $dirs[$i];
						$curDir = $this->Listing->curDir;

						if(!$this->Listing->mkDir("$curDir/$dir")) {
							$this->Listing->refresh();
							return $msg['errDirNew'] . ": $dir";
						}
						else {
							$this->Listing->Explorer = null;
							if($this->FileManager->defaultDirPermissions) {
								if(!$this->Listing->FileSystem->changePerms("$curDir/$dir", $this->FileManager->defaultDirPermissions)) {
									$this->Listing->refresh();
									return $msg['errPermChange'] . ": $dir";
								}
							}
						}
					}
				}
			}
		}
		$this->Listing->refresh();
		return '';
	}

	/**
	 * upload file(s)
	 *
	 * @return string
	 */
	function newFile() {
		global $msg;

		$errors = array();
		if($this->FileManager->enableUpload) {
			$fmFile = $_FILES['fmFile'];
			$fmReplSpaces = $_REQUEST['fmReplSpaces'];
			$fmLowerCase = $_REQUEST['fmLowerCase'];
			$uploaded = array();

			if(is_array($fmFile)) {
				for($i = 0; $i < count($fmFile['size']); $i++) {
					$newFile = $fmFile['name'][$i];
					
					if($fmFile['size'][$i]){
						
					#Modify by ALD
					$pathInfo = pathinfo($newFile);
					$piName   = Tools::safe_string( $pathInfo['filename'] );
					$piExt    = Tools::safe_string( $pathInfo['extension'] );
					$newFile  = $piName.'.'.$piExt;
					#End MBA
						
						if($this->FileManager->hideSystemFiles && $newFile[0] == '.') {
							$errors[] = $msg['errAccess'] . ": $newFile";
						}
						else {
							if($this->FileManager->replSpacesUpload || $fmReplSpaces) {
								$newFile = str_replace(' ', '_', $newFile);
							}

							if($this->FileManager->lowerCaseUpload || $fmLowerCase) {
								$newFile = strtolower($newFile);
							}

							if(!$this->Listing->upload($fmFile['tmp_name'][$i], $newFile)) {
								$errors[] = $msg['errSave'] . ": $newFile";
							}
							else {
								$uploaded[] = array(
									'path' => $this->Listing->curDir . '/' . $newFile,
									'size' => $fmFile['size'][$i]
								);
								if($this->FileManager->defaultFilePermissions) {
									$path = $this->Listing->curDir . '/' . $newFile;
									if(!$this->Listing->FileSystem->changePerms($path, $this->FileManager->defaultFilePermissions)) {
										$errors[] = $msg['errPermChange'] . ": $newFile";
									}
								}
							}
						}
					}
					else if($newFile != '') {
						$errors[] = $msg['error'] . ": $newFile = 0 B";
						$maxFileSize = ini_get('upload_max_filesize');
						$postMaxSize = ini_get('post_max_size');
						$info = "PHP settings: upload_max_filesize = $maxFileSize, ";
						$info .= "post_max_size = $postMaxSize";
						$error = "Could not upload $newFile ($info)";
						$this->Listing->FileSystem->addMsg($error, 'error');
					}
				}
			}
			if($this->FileManager->mailOnUpload && $uploaded) {
				$date = date('Y-m-d H:i:s');
				$ip = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : 'n/a';
				$body = "The following files have been uploaded on $date by IP address $ip:\n\n";
				foreach($uploaded as $file) $body .= $file['path'] . ' ' . $file['size'] . " B\n";
				@mail($this->FileManager->mailOnUpload, 'FileManager Upload Info', $body);
			}
		}
		$this->Listing->refresh();
		return join('<br/>', $errors);
	}

	/**
	 * refresh listing
	 */
	function refresh() {
		$this->Listing->Explorer = null;
		$this->Listing->refresh();
	}

	/**
	 * change file / directory permissions
	 *
	 * @param integer $id
	 * @param array $perms
	 * @return string
	 */
	function changePermissions($id, $perms) {
		global $msg;

		if($this->FileManager->enablePermissions && is_array($perms) && $id != '') {
			if($Entry =& $this->Listing->getEntry($id)) {
				$mode = '';
				for($i = 0; $i < 9; $i++) {
					$mode .= $perms[$i] ? 1 : 0;
				}
				if(!$Entry->changePerms(bindec($mode))) {
					$this->Listing->view();
					return $msg['errPermChange'] . ": $Entry->name";
				}
			}
		}
		$this->Listing->refresh();
		return '';
	}

	/**
	 * edit file
	 *
	 * @param integer $id
	 * @return string
	 */
	function editFile($id) {
		global $msg;

		if($this->FileManager->enableEdit && $id != '') {
			if($Entry =& $this->Listing->getEntry($id)) {
				$fmText = $_POST['fmText'];
				if($fmText != '') {
					if(!$Entry->saveFile($fmText)) {
						$this->Listing->view();
						return $msg['errSave'] . ": $Entry->name";
					}
					$this->Listing->refresh();
				}
				else {
					$Editor = new Editor($this->FileManager);
					$Editor->view($Entry);
				}
			}
		}
		return '';
	}

	/**
	 * perform search
	 *
	 * @param string $value
	 */
	function search($value) {
		$this->Listing->performSearch($value);
	}

	/**
	 * switch listing view
	 */
	function switchView() {
		$this->Listing =& $this->FileManager->switchListingView();
		$this->Listing->refresh();
	}
}

?>