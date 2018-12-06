<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

include_once('Entry.php');

/**
 * This class manages file entries (icon view).
 *
 * @package FileManager
 * @subpackage class
 * @author Gerd Tentler
 */
class EntryIcon extends Entry {

/* PUBLIC METHODS ****************************************************************************** */

	/**
	 * constructor
	 *
	 * @param Listing $Listing
	 * @return EntryIcon
	 */
	function EntryIcon(&$Listing) {
		parent::Entry($Listing);
	}

	/**
	 * view entry
	 */
	function view() {
		$this->viewHeader();
		$this->viewIcon();
		$this->viewName();
		$this->viewFooter();
	}

	/**
	 * view header
	 */
	function viewHeader() {
		list($action, $caption) = $this->getAction('menu');
		$class = ($this->Listing->searchString != '') ? 'fmSearchResult' : 'fmTD1';

		if($this->Listing->cellCnt >= $this->Listing->cellsPerRow) {
			$this->Listing->cellCnt = 0;
			print "</tr>\n<tr align=\"center\" valign=\"top\">\n";
		}
	    print "<td class=\"$class\" style=\"cursor:pointer\" onClick=\"$action\" ";
	    print "onMouseOver=\"this.className='fmTD2'\" onMouseOut=\"this.className='$class'\">\n";
	    $this->Listing->cellCnt++;
	}

	/**
	 * view footer
	 */
	function viewFooter() {
		print "</td>\n";
	}

	/**
	 * view icon
	 */
	function viewIcon() {
		$style = $this->thumbHash ? 'border:1px solid #E0E0E0' : '';

    	print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:100%; height:60px\">\n";
    	print "<tr>\n";
    	print "<td align=\"center\" style=\"$style\">\n";

	    if($this->thumbHash) {
			$cont = $this->FileManager->container;
			$url = $this->FileManager->fmWebPath . "/action.php?fmContainer=$cont";
			$width = round($this->Listing->listWidth * $this->Listing->cellWidth / 100) - 16;
      		list($width, $height) = $this->getThumbSize($width, 54);
      		$thumbnail = "$url&fmMode=getThumbnail&fmObject={$this->id}&width=$width&height=$height&{$this->thumbHash}";
      		Tools::printIcon($thumbnail, $width, $height, '', $tooltip);
    	}
    	else {
			$icon = $this->FileManager->fmWebPath . '/icons/' . $this->icon . '_big.gif';
			Tools::printIcon($icon, 32, 32, '', $tooltip);
    	}
    	print "</td>\n";
    	print "</tr>\n";
    	print "</table>\n";
	}

/* PROTECTED METHODS *************************************************************************** */

	/**
	 * view file name
	 */
	function viewName() {
		$max = $this->Listing->nameMaxLen;
		if(Tools::containsBigChars($this->name)) $max = round($max / 1.5);
		$enc = $this->FileManager->encoding;
		$shortName = (Tools::strlen($this->name, $enc) > $max) ? Tools::substr($this->name, 0, $max, $enc) . '...' : $this->name;
		print $shortName . "<br/><br/>\n";
	}
}

?>