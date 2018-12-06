<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

@error_reporting(E_WARNING);
@set_time_limit(600);

include_once('ListingDetail.php');
include_once('ListingIcon.php');
include_once('Event.php');
include_once('Tools.php');

/**
 * This is the main class.
 */
class FileManager {

/* PUBLIC PROPERTIES *************************************************************************** */

	/* configuration variables; will be filled with content from config file */

	var $ftpHost;
	var $ftpUser;
	var $ftpPassword;
	var $ftpPort;
	var $ftpPassiveMode;
	var $language;
	var $encoding;
	var $locale;
	var $startDir;
	var $startSubDirs;
	var $startSearch;
	var $fmWebPath;
	var $fmWidth;
	var $fmHeight;
	var $fmMargin;
	var $fmView;
	var $logHeight;
	var $explorerWidth;
	var $thumbMaxWidth;
	var $thumbMaxHeight;
	var $defaultFilePermissions;
	var $defaultDirPermissions;
	var $allowFileTypes;
	var $hideFileTypes;
	var $hideSystemFiles;
	var $hideSystemType;
	var $hideFilePath;
	var $hideLinkTarget;
	var $hideDisabledIcons;
	var $enableUpload;
	var $enableDownload;
	var $enableEdit;
	var $enableDelete;
	var $enableRename;
	var $enablePermissions;
	var $enableNewDir;
	var $replSpacesUpload;
	var $replSpacesDownload;
	var $lowerCaseUpload;
	var $lowerCaseDownload;
	var $createBackups;
	var $loginPassword;
	var $mailOnUpload;
	var $mailOnDownload;
	var $debugInfo;

	/**
	 * path to temporary directory
	 *
	 * @var string
	 */
	var $tmpDir;

	/**
	 * path to cache directory
	 *
	 * @var string
	 */
	var $cacheDir;

	/**
	 * HTML container name
	 *
	 * @var string
	 */
	var $container;

/* PRIVATE PROPERTIES ************************************************************************** */

	/**
	 * file manager directory path (for includes)
	 *
	 * @var string
	 */
	var $incPath;

	/**
	 * holds listing object
	 *
	 * @var Listing
	 */
	var $Listing;

	/**
	 * HTML container name
	 *
	 * @var string
	 */
	var $listCont;

	/**
	 * HTML container name
	 *
	 * @var string
	 */
	var $logCont;

	/**
	 * HTML container name
	 *
	 * @var string
	 */
	var $infoCont;

	/**
	 * user access
	 *
	 * @var boolean
	 */
	var $access;

/* PUBLIC METHODS ****************************************************************************** */

	/**
	 * constructor
	 *
	 * @param string $startDir		optional: directory path
	 * @return FileManager
	 */
	function FileManager($startDir = '') {
		$this->incPath = str_replace('\\', '/', realpath(dirname(__FILE__) . '/..'));
		$this->tmpDir = $this->incPath . '/tmp';
		$this->cacheDir = $this->incPath . '/cache';

		$this->initFromConfig();
		if($startDir != '') $this->startDir = $startDir;

		if($this->fmWebPath == '') {
			$this->fmWebPath = ereg_replace('^' . $_SERVER['DOCUMENT_ROOT'], '', $this->incPath);
			if($this->fmWebPath == $this->incPath) {
				$ld = Tools::basename($_SERVER['DOCUMENT_ROOT'], $this->encoding);
				$this->fmWebPath = substr($this->incPath, strpos($this->incPath, $ld) + strlen($ld));
			}
		}
	}

	/**
	 * initialization from config file
	 */
	function initFromConfig() {
		include($this->incPath . '/config.inc.php');
		$this->ftpHost = $ftpHost;
		$this->ftpUser = $ftpUser;
		$this->ftpPassword = $ftpPassword;
		$this->ftpPort = $ftpPort ? $ftpPort : 21;
		$this->ftpPassiveMode = $ftpPassiveMode;
		$this->language = $language;
		$this->encoding = $encoding;
		$this->locale = $locale;
		$this->startDir = $startDir;
		$this->startSubDirs = $startSubDirs;
		$this->startSearch = $startSearch;
		$this->fmWebPath = $fmWebPath;
		$this->fmWidth = $fmWidth;
		$this->fmHeight = $fmHeight;
		$this->fmMargin = $fmMargin;
		$this->fmView = $fmView;
		$this->logHeight = $logHeight;
		$this->explorerWidth = $explorerWidth;
		$this->thumbMaxWidth = $thumbMaxWidth;
		$this->thumbMaxHeight = $thumbMaxHeight;
		$this->defaultFilePermissions = $defaultFilePermissions;
		$this->defaultDirPermissions = $defaultDirPermissions;
		$this->allowFileTypes = $allowFileTypes;
		$this->hideFileTypes = $hideFileTypes;
		$this->hideSystemFiles = $hideSystemFiles;
		$this->hideSystemType = $hideSystemType;
		$this->hideFilePath = $hideFilePath;
		$this->hideLinkTarget = $hideLinkTarget;
		$this->hideDisabledIcons = $hideDisabledIcons;
		$this->enableUpload = $enableUpload;
		$this->enableDownload = $enableDownload;
		$this->enableEdit = $enableEdit;
		$this->enableDelete = $enableDelete;
		$this->enableRename = $enableRename;
		$this->enablePermissions = $enablePermissions;
		$this->enableNewDir = $enableNewDir;
		$this->replSpacesUpload = $replSpacesUpload;
		$this->replSpacesDownload = $replSpacesDownload;
		$this->lowerCaseUpload = $lowerCaseUpload;
		$this->lowerCaseDownload = $lowerCaseDownload;
		$this->createBackups = $createBackups;
		$this->loginPassword = $loginPassword;
		$this->mailOnUpload = $mailOnUpload;
		$this->mailOnDownload = $mailOnDownload;
		$this->debugInfo = $debugInfo;
	}

	/**
	 * create file manager
	 *
	 * @return string	HTML code
	 */
	function create() {
		global $fmCnt, $msg;

		ob_start();

		if(!$fmCnt) $fmCnt = 1;
		if($fmCnt == 1) {
			$this->getLanguageFile();
			$fmWebPath = $this->fmWebPath;
			$fmEncoding = $this->encoding;
			include_once($this->incPath . '/template.inc.php');
		}

		if($this->startDir != '') {
			if(!$this->ftpHost) $this->startDir = realpath($this->startDir);
			$this->startDir = str_replace('\\', '/', $this->startDir);

			if($this->ftpHost) {
				$this->startDir = preg_replace('%/*\.\.%', '', $this->startDir);
				$this->startDir = preg_replace('%^/+%', '', $this->startDir);
			}
		}

		if($this->ftpHost && $this->startDir == '') $this->startDir = '.';
		if($this->loginPassword == '') $this->access = true;

		$this->container = 'fmCont' . $fmCnt;
		$this->listCont = 'fmList' . $fmCnt;
		$this->logCont = 'fmLog' . $fmCnt;
		$this->infoCont = 'fmInfo' . $fmCnt;
		$this->save();

		$this->viewHeader();
		$this->viewFooter();

		$url = $this->fmWebPath . '/action.php?fmContainer=' . $this->container;
		$mode = ($this->startSearch != '') ? "search&fmName=$this->startSearch" : 'refresh';
		print "<script type=\"text/javascript\">\n";
		print "setTimeout(\"fmCall('$url&fmMode=$mode')\", 250);\n";
		print "</script>\n";
		$fmCnt++;

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 * return listing object
	 *
	 * @return Listing
	 */
	function &getListing() {
		if(!$this->Listing) switch($this->fmView) {
			case 'details':	$this->Listing =& new ListingDetail($this); break;
			case 'icons':	$this->Listing =& new ListingIcon($this); break;
			default:		die('Invalid view type "' . $this->fmView . '"');
		}
		return $this->Listing;
	}

	/**
	 * switch listing view
	 *
	 * @return Listing
	 */
	function &switchListingView() {
		$this->Listing =& $this->Listing->switchView();
		return $this->Listing;
	}

	/**
	 * perform requested action
	 */
	function action() {
		global $msg;

		$fmMode = Tools::utf8Decode($_REQUEST['fmMode'], $this->encoding);
		$fmName = Tools::utf8Decode($_REQUEST['fmName'], $this->encoding);

		$this->getLanguageFile();

		if(!$this->ftpHost && $this->startDir == '') {
			die("SECURITY ALERT:<br/>Please set a start directory or an FTP server!");
		}
		else if($fmMode == 'login' && $fmName == $this->loginPassword) {
			$this->access = true;
			$fmMode = 'refresh';
		}
		else if($this->loginPassword != '' && !$this->access) {
			$this->viewLogin();
		}

		if($this->access) {
			$this->getListing();
			$Event = new Event($this);
			$error = $Event->handle($fmMode, $_REQUEST['fmObject'], $fmName);

			if($error != '') {
				print '{{fmERROR}}' . $error . '{{/fmERROR}}';
				$this->error = '';
			}
			if($this->ftpHost) $this->Listing->FileSystem->ftpClose();
			Tools::cleanDir($this->tmpDir);
		}

		if($this->debugInfo) {
			print '{{fmINFO}}' . $this->getDebugInfo() . '{{/fmINFO}}';
		}
		print '{{fmLOG}}' . $this->Listing->FileSystem->getMessages() . '{{/fmLOG}}';
		$this->save();
	}

/* PRIVATE METHODS ***************************************************************************** */

	/**
	 * view header
	 */
	function viewHeader() {
		$listHeight = $this->fmHeight;
		if($this->logHeight > 0) $listHeight -= $this->logHeight + 9;
		if($this->debugInfo) $listHeight -= 151;

		print "<div id=\"$this->container\" class=\"fmTH1\" style=\"position:relative; padding:1px; ";
		print "width:{$this->fmWidth}px; margin:{$this->fmMargin}px\">\n";
		print "<div id=\"$this->listCont\" class=\"fmTH1\">\n";
		print "<div class=\"fmTH2\" style=\"width:{$this->fmWidth}px; height:{$listHeight}px\">&nbsp;</div>\n";
		print "</div>\n";
	}

	/**
	 * view footer
	 */
	function viewFooter() {
		if($this->logHeight > 0) {
			$width = $this->fmWidth - 8;
			print "<div id=\"$this->logCont\" class=\"fmTH2\" ";
			print "style=\"width:{$width}px; height:{$this->logHeight}px; ";
			print "margin-top:1px; padding:4px; text-align:left; overflow:auto\">\n";
			print "</div>\n";
		}

		if($this->debugInfo) {
			print "<div id=\"$this->infoCont\" class=\"fmTH2\" ";
			print "style=\"width:{$this->fmWidth}px; height:150px; ";
			print "margin-top:1px; text-align:left; overflow:auto\">\n";
			print "</div>\n";
		}
		print "</div>\n";
	}

	/**
	 * view login form
	 */
	function viewLogin() {
		global $msg;

		$url = $this->fmWebPath . '/action.php?fmContainer=' . $this->container;
		$action = "javascript:fmCall('$url', '{$this->container}Login')";
		$listHeight = $this->fmHeight - 22;
		if($this->logHeight > 0) $listHeight -= $this->logHeight + 9;
		if($this->debugInfo) $listHeight -= 151;

		print "<form name=\"{$this->container}Login\" action=\"$action\" class=\"fmForm\" method=\"post\">\n";
		print "<input type=\"hidden\" name=\"fmMode\" value=\"login\"/>\n";
		print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\"><tr>\n";
		print "<td class=\"fmTH1\" style=\"padding:4px\" align=\"left\" nowrap=\"nowrap\">{$msg['cmdLogin']}</td>\n";
		print "</tr><tr>\n";
		print "<td class=\"fmTH3\" align=\"center\" style=\"height:{$listHeight}px; padding:4px\">\n";
		print "<input type=\"password\" name=\"fmName\" size=\"20\" maxlength=\"60\" class=\"fmField\"/><br/>\n";
		print "<input type=\"submit\" class=\"fmButton\" value=\"{$msg['cmdLogin']}\"/>\n";
		print "</td>\n";
		print "</tr></table>\n";
		print "</form>\n";
	}

	/**
	 * get language file
	 */
	function getLanguageFile() {
		global $msg;

		if(!isset($this->language)) $this->language = 'en';
		$file = $this->incPath . '/languages/lang_' . $this->language . '.inc';
		$data = Tools::readLocalFile($file, $this->encoding);

		if(preg_match_all('/(\w+)\s*=\s*(.+)/', $data, $m)) {
			for($i = 0; $i < count($m[0]); $i++) {
				$key = trim($m[1][$i]);
				$val = trim($m[2][$i]);
				$msg[$key] = $val;
			}
		}
	}

	/**
	 * get debug info
	 */
	function getDebugInfo() {
		$html =  '<div class="fmTH1" style="padding:4px; text-align:left">DEBUG INFO</div>';
		$html .= '<div class="fmTD2" style="padding:2px; text-align:left">';
		$html .= '<table border="0" cellspacing="0" cellpadding="2">';
		$html .= '<tr valign="top"><td class="fmTD2">Cookie info:</td>';
		$html .= '<td class="fmTD2">' . nl2br(str_replace(' ', '&nbsp;', print_r(session_get_cookie_params(), true))) . '</td>';
		$html .= '</tr><tr valign="top">';
		$html .= '<td class="fmTD2">FileManager::$language:</td><td class="fmTD2">' . $this->language . '</td>';
		$html .= '</tr><tr valign="top">';
		$html .= '<td class="fmTD2">FileManager::$encoding:</td><td class="fmTD2">' . $this->encoding . '</td>';
		$html .= '</tr><tr valign="top">';
		$html .= '<td class="fmTD2">FileManager::$locale:</td><td class="fmTD2">' . $this->locale . ' (system: ' . @setlocale(LC_ALL, '0') . ')</td>';
		$html .= '</tr><tr valign="top">';
		$html .= '<td class="fmTD2">FileManager::$fmWebPath:</td><td class="fmTD2">' . $this->fmWebPath . '</td>';
		$html .= '</tr><tr valign="top">';
		$html .= '<td class="fmTD2">FileManager::$startDir:</td><td class="fmTD2">' . $this->startDir . '</td>';
		$html .= '</tr><tr valign="top">';
		$html .= '<td class="fmTD2">Listing::$curDir:</td><td class="fmTD2">' . $this->Listing->curDir . '</td>';
		$html .= '</tr><tr valign="top">';
		$html .= '<td class="fmTD2">Listing::$prevDir:</td><td class="fmTD2">' . $this->Listing->prevDir . '</td>';
		$html .= '</tr><tr valign="top">';
		$html .= '<td class="fmTD2">Listing::$searchString:</td><td class="fmTD2">' . $this->Listing->searchString . '</td>';
		$html .= '</tr><tr valign="top">';
		$html .= '<td class="fmTD2">Cache directory:</td><td class="fmTD2">' . Tools::getFileCount($this->cacheDir) . ' files</td>';
		$html .= '</tr></table>';
		$html .= "</div>\n";
		return $html;
	}

	/**
	 * save FileManager object
	 */
	function save() {
		$_SESSION[$this->container] = serialize($this);
	}
}

?>