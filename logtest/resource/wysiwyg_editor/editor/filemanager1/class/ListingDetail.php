<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

include_once('Listing.php');
include_once('EntryDetail.php');

/**
 * This class manages directory listings (detailed view).
 *
 * @package FileManager
 * @subpackage class
 * @author Gerd Tentler
 */
class ListingDetail extends Listing {

/* PUBLIC METHODS ****************************************************************************** */

	/**
	 * constructor
	 *
	 * @param FileManager $FileManager		file manager object
	 * @param string $dir					optional: directory path
	 * @param string $search				optional: search string
	 * @return ListingDetail
	 */
	function ListingDetail(&$FileManager, $dir = '', $search = '') {
		parent::Listing($FileManager, $dir, $search);
		$this->nameMaxLen = round($this->listWidth / 20);
	}

	/**
	 * view listing
	 */
	function view() {
		$this->sortList();
		$this->viewHeader();
		$this->viewCaptions();
		parent::view();
		$this->viewFooter();
	}

	/**
	 * switch view
	 *
	 * @return ListingIcon
	 */
	function &switchView() {
		$this->FileManager->fmView = 'icons';
		return new ListingIcon($this->FileManager, $this->curDir, $this->searchString);
	}

/* PROTECTED METHODS *************************************************************************** */

	/**
	 * create new entry
	 */
	function &newEntry() {
		return new EntryDetail($this);
	}

	/**
	 * view header
	 */
	function viewHeader() {
		parent::viewHeader();
		print "<table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" width=\"100%\" class=\"fmTH2\">\n";
	}

	/**
	 * view footer
	 */
	function viewFooter() {
		print "</table>\n";
		parent::viewFooter();
	}

	/**
	 * view directory up icon
	 */
	function viewDirUp() {
		$Entry = $this->newEntry();
		$Entry->icon = 'cdup';
		$Entry->name = ($this->searchString == '') ? '..' : '&nbsp;';
		$Entry->view();
	}

/* PRIVATE METHODS ***************************************************************************** */

	/**
	 * view captions of columns
	 */
	function viewCaptions() {
		global $msg;

		print "<tr align=\"center\">\n";
		$this->viewCaption('isDir', '');
		$this->viewCaption('name', $msg['name']);
		$this->viewCaption('size', $msg['size']);
		$this->viewCaption('changed', $msg['lastChange']);
		$this->viewCaption('permissions', $msg['permissions']);
		$this->viewCaption('select-file', $msg['selectfile']); # Add by ALD

		if($this->FileManager->enableDelete) {
			$cont = $this->FileManager->container;
			$url = $this->FileManager->fmWebPath . '/action.php?fmContainer=' . $cont;
			$confirm = $msg['msgDelItems'];
			$icon = $this->FileManager->fmWebPath . '/icons/delete.gif';
			$tooltip = $msg['cmdDelete'];
			$style = 'cursor:pointer';
			$onClick = "fmDeleteCheckedFiles('$cont', '$url', ['$tooltip', '$confirm'])";
		}
		else {
			$icon = $this->FileManager->fmWebPath . '/icons/delete_x.gif';
			$tooltip = $style = '';
			$error = addslashes($msg['cmdDelete'] . ': ' . $msg['errDisabled']);
			$onClick = "fmOpenDialog('', 'fmError', '$error')";
		}
		print "<td width=\"20\">";
		Tools::printIcon($icon, 10, 10, $onClick, $tooltip, $style);
		print "</td>\n";
		print "</tr>\n";
	}

	/**
	 * view caption
	 *
	 * @param string $name			column name
	 * @param string $title			column title
	 */
	function viewCaption($name, $title) {
		global $msg;

		if($this->sortField == $name) {
			$imgSort = $this->FileManager->fmWebPath . '/icons/sort_' . $this->sortOrder . '.gif';
			$order = ($this->sortOrder == 'asc') ? 'desc' : 'asc';
		}
		else {
			$imgSort = $this->FileManager->fmWebPath . '/icons/blank.gif';
			$order = 'asc';
		}
		$cont = $this->FileManager->container;
		$link = $this->FileManager->fmWebPath . "/action.php?fmContainer=$cont&fmMode=sort&fmName=$name,$order";
		$tooltip = ($order == 'asc') ? $msg['cmdSortAsc'] : $msg['cmdSortDesc'];

		print "<td class=\"fmTH3\" title=\"$tooltip\" nowrap=\"nowrap\"";
 		print " onMouseOver=\"this.className='fmTH4'\"";
 		print " onMouseOut=\"this.className='fmTH3'\"";
 		print " onMouseDown=\"this.className='fmTH5'\"";
 		print " onMouseUp=\"this.className='fmTH4'\"";
		print " onClick=\"fmCall('$link')\">\n";
		if($title != '') print "&nbsp;$title&nbsp\n";
		Tools::printIcon($imgSort, 8, 7);
		print "</td>\n";
	}
}

?>