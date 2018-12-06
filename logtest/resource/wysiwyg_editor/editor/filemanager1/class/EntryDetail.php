<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

include_once('Entry.php');

/**
 * This class manages file entries (detailed view).
 *
 * @package FileManager
 * @subpackage class
 * @author Gerd Tentler
 */
class EntryDetail extends Entry {

/* PUBLIC METHODS ****************************************************************************** */

	/**
	 * constructor
	 *
	 * @param Listing $Listing
	 * @return EntryDetail
	 */
	function EntryDetail(&$Listing) {
		parent::Entry($Listing);
	}

	/**
	 * view entry
	 */
	function view() {
		$this->viewHeader();
		$this->viewIcon();
		$this->viewName();
		$this->viewSize();
		$this->viewModified();
		$this->viewPermissions();
		$this->viewSelectFile(); # Add by ALD
		$this->viewCheckbox();
		$this->viewFooter();
	}

	/**
	 * view header
	 */
	function viewHeader() {
		$class = ($this->Listing->searchString != '') ? 'fmSearchResult' : 'fmTD1';
		print "<tr class=\"$class\" align=\"center\" ";
		print "onMouseOver=\"this.className='fmTD2'\" onMouseOut=\"this.className='$class'\">\n";
	}

	/**
	 * view footer
	 */
	function viewFooter() {
		print "</tr>\n";
	}

	/**
	 * view icon
	 */
	function viewIcon() {
		$icon = $this->FileManager->fmWebPath . '/icons/' . $this->icon . '.gif';
		list($action, $caption) = $this->getAction('menu');
		print "<td class=\"fmContent\" style=\"cursor:pointer\" onClick=\"$action\">";
     	Tools::printIcon($icon, 12, 10);
    	print "</td>\n";
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
		list($action, $caption) = $this->getAction('menu');
		print "<td class=\"fmContent\" style=\"cursor:pointer\" align=\"left\" onClick=\"$action\">$shortName</td>\n";
	}

	/**
	 * view file size
	 */
	function viewSize() {
		if($this->icon == 'cdup') {
			$size = '';
		}
		else if($this->size < 1000) {
			$size = $this->size . ' B';
		}
		else {
			$size = $this->size / 1024;
			if($size > 999) $size = number_format($size / 1024, 1) . ' MB';
			else $size = number_format($size, 1) . ' KB';
		}
		list($action, $caption) = $this->getAction('menu');
		print "<td class=\"fmContent\" style=\"cursor:pointer\" align=\"right\" onClick=\"$action\">$size</td>\n";
	}

	/**
	 * view last modification date
	 */
	function viewModified() {
		list($action, $caption) = $this->getAction('menu');
		print "<td class=\"fmContent\" style=\"cursor:pointer\" onClick=\"$action\">$this->changed</td>\n";
	}

	/**
	 * view permissions
	 */
	function viewPermissions() {
		list($action, $caption) = $this->getAction('menu');
		print "<td class=\"fmContent\" style=\"cursor:pointer\" onClick=\"$action\">$this->permissions</td>\n";
	}
	
	/**
	 * view select file
	 * Add  by ALD
	 */
	function subPath($path){
		$path = str_replace($_SERVER['DOCUMENT_ROOT'],"",$path);
		return $path;
	}
	function viewSelectFile() {
		if( $this->icon != 'dir' && $this->icon != 'cdup' ){
			$icon = '<img src="'.$this->FileManager->fmWebPath.'/icons/select-16.png" />';
			print "<td class=\"fmContent\" style=\"cursor:pointer\" onClick=\"fmSelect('" . $this->subPath($this->path) . "')\">".$icon."</td>\n";
		}
		elseif($this->icon == 'dir'){
			$icon = '<img src="'.$this->FileManager->fmWebPath.'/icons/open-16.png" />';
			$action = "fmCall('".$this->FileManager->fmWebPath."/action.php?fmContainer=".$this->FileManager->container."&fmMode=open&fmObject=$this->id')";
			print "<td class=\"fmContent\" style=\"cursor:pointer\" align=\"center\" onClick=\"$action\">".$icon."</td>\n";
		}else{
			print "<td class=\"fmContent\">&nbsp;</td>\n";
		}
	}

	/**
	 * view checkbox
	 */
	function viewCheckbox() {
		print "<td class=\"fmContent\" onClick=\"fmFadeOut()\">";
		print "<input type=\"checkbox\" value=\"$this->id\"";
		if($this->icon == 'cdup') print ' disabled="disabled"';
		print "/></td>\n";
	}
}

?>