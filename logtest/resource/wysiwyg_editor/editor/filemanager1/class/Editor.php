<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

include_once('Tools.php');

/**
 * This class creates a text editor.
 *
 * @package FileManager
 * @subpackage class
 * @author Gerd Tentler
 */
class Editor {

/* PRIVATE PROPERTIES ************************************************************************** */

	/**
	 * holds FileManager object
	 *
	 * @var FileManager
	 */
	var $FileManager;

/* PUBLIC METHODS ****************************************************************************** */

	/**
	 * constructor
	 *
	 * @param FileManager $FileManager
	 * @return Editor
	 */
	function Editor(&$FileManager) {
		$this->FileManager =& $FileManager;
	}

	/**
	 * view text editor
	 *
	 * @param Entry $Entry		file entry object
	 */
	function view(&$Entry) {
		$this->viewHeader($Entry);
		$this->viewContent($Entry);
		$this->viewFooter();
	}

/* PRIVATE METHODS ***************************************************************************** */

	/**
	 * view header
	 *
	 * @param Entry $Entry		file entry object
	 */
	function viewHeader(&$Entry) {
		global $msg;

		$webPath = $this->FileManager->fmWebPath;
		$url = $webPath . '/action.php?fmContainer=' . $this->FileManager->container;

		print "<form name=\"frmEdit\" class=\"fmForm\" action=\"javascript:fmCall('$url', 'frmEdit')\" method=\"post\">\n";
    	print "<input type=\"hidden\" name=\"fmMode\" value=\"edit\">\n";
    	print "<input type=\"hidden\" name=\"fmObject\" value=\"$Entry->id\">\n";
		print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n";
		print "<tr>\n";
		print "<td class=\"fmTH1\" align=\"left\" style=\"padding:4px\">" . $msg['cmdEdit'] . ": $Entry->name</td>\n";
		print "<td class=\"fmTH1\" align=\"right\" style=\"padding:4px\" nowrap=\"nowrap\">\n";
		Tools::printIcon("$webPath/icons/list.gif", 14, 14, "fmCall('$url')", $msg['cmdViewList'], 'cursor:pointer');
		Tools::printIcon("$webPath/icons/reset.gif", 14, 14, "fmCall('$url&fmMode=edit&fmObject=$Entry->id')", $msg['cmdReset'], 'cursor:pointer');
		Tools::printIcon("$webPath/icons/save.gif", 14, 14, "fmCallOK('{$msg['msgSaveFile']}', '', 'frmEdit')", $msg['cmdSave'], 'cursor:pointer');
		print "</td>\n";
		print "</tr>\n";
		print "<tr>\n";
		print "<td class=\"fmTH2\" colspan=\"2\" align=\"center\">\n";
	}

	/**
	 * view footer
	 */
	function viewFooter() {
		print "</td>\n";
		print "</tr>\n";
		print "</table>\n";
		print "</form>\n";
	}

	/**
	 * view file content
	 *
	 * @param Entry $Entry		file entry object
	 */
	function viewContent(&$Entry) {
		$file = $Entry->getFile();
		$content = htmlspecialchars(Tools::readLocalFile($file));
		$width = $this->FileManager->fmWidth - 6;
		$height = $this->FileManager->fmHeight - 32;
		if($this->FileManager->logHeight > 0) $height -= $this->FileManager->logHeight + 9;
		if($this->FileManager->debugInfo) $height -= 151;
		$ext = strtolower(end(explode('.', $Entry->name)));

		switch($ext) {
			case 'js':   $language = 'javascript'; break;
			case 'php':  $language = 'php'; break;
			case 'html': $language = 'html'; break;
			case 'css':  $language = 'css'; break;
			default:     $language = '';
		}
		print "<textarea name=\"fmText\" style=\"width:{$width}px; height:{$height}px\" ";
		print "class=\"codeedit $language lineNumbers focus\" wrap=\"off\">$content</textarea>\n";
	}
}

?>