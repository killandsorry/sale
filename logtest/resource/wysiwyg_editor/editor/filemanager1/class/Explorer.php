<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

include_once('Entry.php');

/**
 * This class creates a directory explorer.
 *
 * @package FileManager
 * @subpackage class
 * @author Gerd Tentler
 */
class Explorer {

/* PUBLIC PROPERTIES *************************************************************************** */

	/**
	 * stores folder information
	 *
	 * @var array
	 */
	var $folders;

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
	 * @param Listing $Listing
	 * @return Explorer
	 */
	function Explorer(&$Listing) {
		$this->Listing =& $Listing;
		$this->FileManager =& $this->Listing->FileManager;
	}

	/**
	 * view directory explorer
	 */
	function view() {
		$this->viewHeader();
		$this->viewContent();
		$this->viewFooter();
	}

/* PRIVATE METHODS ***************************************************************************** */

	/**
	 * view header
	 */
	function viewHeader() {
		print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n";
	}

	/**
	 * view content
	 */
	function viewContent() {
		$cont = $this->FileManager->container;
		$url = $this->FileManager->fmWebPath . '/action.php?fmContainer=' . $cont;
		$webPath = $this->FileManager->fmWebPath;

		if(!$this->folders) {
			$this->folders = $this->getFolders($this->FileManager->startDir);
			$paths = array();
			foreach($this->folders as $key => $row) $paths[$key] = strtolower($row[1]);
			array_multisort($paths, SORT_ASC, SORT_REGULAR, $this->folders);
		}

		if(is_array($this->folders)) foreach($this->folders as $id => $dir) {
			$path = urlencode($dir[1]);
			print '<tr style="cursor:pointer"';
			print " onMouseOver=\"this.className='fmTH2'\"";
			print " onMouseOut=\"this.className='fmTD2'\"";
			print " onClick=\"fmCall('$url&fmMode=expOpen&fmObject=$id')\">\n";
			print "<td align=\"left\">\n";
			print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n";
			print "<tr valign=\"top\">\n";

			for($i = 1; $i < $dir[0]; $i++) {
				print "<td><img src=\"$webPath/icons/blank.gif\" width=\"4\" height=\"1\"/></td>";
			}

			if($this->folders[$id + 1] && $this->folders[$id + 1][0] > $dir[0]) {
				$icon = 'dir_open.gif';
			}
			else $icon = 'dir.gif';

			print "<td><img src=\"$webPath/icons/$icon\"/></td>\n";
			print '<td class="fmContent" align="left">' . Tools::basename($dir[1], $this->FileManager->encoding) . "</td>\n";
			print "</tr>\n";
			print "</table>\n";
			print "</td>\n";
			print "</tr>\n";
		}
	}

	/**
	 * view footer
	 */
	function viewFooter() {
		print "</table>\n";
	}

	/**
	 * get all sub-folders
	 *
	 * @param string $dir		directory path
	 * @param integer $level	optional: directory level
	 * @return array			folders (level, path)
	 */
	function getFolders($dir, $level = 0) {
		$dirs = array();

		if($list = $this->Listing->FileSystem->readDir($dir, true)) {
			if(is_array($list)) foreach($list as $row) {
				$Entry = new Entry($this->Listing);

				if($Entry->setProperties($row, $dir)) {
					if($Entry->isDir()) {
						if(!$this->Listing->isAllowedDir($Entry->path)) continue;
						$dirs[] = array($level + 1, $Entry->path);
						$dirs = array_merge($dirs, $this->getFolders($Entry->path, $level + 1));
					}
				}
			}
		}
		return $dirs;
	}
}

?>