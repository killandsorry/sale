<?php
require_once("config_security.php");
/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

// FTP access; leave empty to use local file system instead
$ftpHost = "";           // FTP server name, example: www.yourdomain.com
$ftpUser = "";           // FTP user name
$ftpPassword = "";       // FTP password
$ftpPort = 21;           // FTP port number (default is 21)
$ftpPassiveMode = false;  // use passive mode (true = yes, false = no)

// language: bg, cs, de, en, es, et, fi, fr, he, it, lv, nl, pt, pt-BR, ro, ru, sv, tr, zh-Hans
$language = "en";

// character set, example: ISO-8859-1
// NOTE: will also be used for file/directory names and edited text files!
$encoding = "UTF-8";

// locale setting, example: en_US - leave empty to use your server's default setting
$locale = "";

// start directory (file path, example: /home/users/gerry/htdocs/tools)
// NOTE: if not in FTP mode, PHP must have at least read permission for this directory!
$startDir = $_SERVER['DOCUMENT_ROOT'] . "/upload_images/";

// only view these directories; example: array("www", "uploads") - leave empty to view all directories
// NOTE: this will only work within the start directory
$startSubDirs = array();

// view files/directories containing this string when starting FileManager
$startSearch = "";

// FileManager WEB path (example: [http://domain]/tools/filemanager)
// NOTE: only set this if FileManager doesn't view properly!
$fmWebPath = "";

// FileManager width (pixels)
$fmWidth = 690;

// FileManager height (pixels)
$fmHeight = 600;

// FileManager margin (pixels)
$fmMargin = 0;

// FileManager default view ("details" or "icons")
$fmView = "details";

// log window height (pixels; 0 = don't view log)
$logHeight = 100;

// directory tree width (pixels; 0 = don't view directory tree)
$explorerWidth = 170;

// max. width of preview thumbnails (pixels)
$thumbMaxWidth = 220;

// max. height of preview thumbnails (pixels)
$thumbMaxHeight = 220;

// default permissions for uploaded files (octal number, example: 0755)
// NOTE: does not work correctly on Windows systems
$defaultFilePermissions = 0;

// default permissions for new directories (octal number, example: 0755)
// NOTE: does not work correctly on Windows systems
$defaultDirPermissions = 0;

// allow files with certain extensions, example: array("mp3", "txt", "jpg"); leave empty to allow all types
// NOTE: only use lowercase extensions; they will also work with uppercase files!
$allowFileTypes = array("jpg","jpeg","bmp","gif","swf");

// hide files with certain extensions, example: array("mp3", "txt", "jpg")
// NOTE: only use lowercase extensions; they will also work with uppercase files!
$hideFileTypes = array("php");

// hide system files with leading dot, example: .htaccess (true = yes, false = no)
$hideSystemFiles = false;

// hide system type (true = yes, false = no)
$hideSystemType = false;

// hide file path in file details (true = yes, false = no)
$hideFilePath = true;

// hide symbolic link target (true = yes, false = no)
$hideLinkTarget = false;

// hide disabled icons (true = yes, false = no)
$hideDisabledIcons = false;

// enable file upload (true = yes, false = no)
$enableUpload = true;

// enable file download (true = yes, false = no)
$enableDownload = true;

// enable file editing (true = yes, false = no)
$enableEdit = true;

// enable file / directory deleting (true = yes, false = no)
$enableDelete = true;

// enable file / directory renaming (true = yes, false = no)
$enableRename = true;

// enable file / directory permissions changing (true = yes, false = no)
$enablePermissions = true;

// enable directory creation (true = yes, false = no)
$enableNewDir = true;

// upload: replace spaces in filenames with underscores (true = yes, false = no)
$replSpacesUpload = false;

// download: replace spaces in filenames with underscores (true = yes, false = no)
$replSpacesDownload = false;

// upload: convert filenames to lowercase (true = yes, false = no)
$lowerCaseUpload = false;

// download: convert filenames to lowercase (true = yes, false = no)
$lowerCaseDownload = false;

// upload: backup files, i.e. don't overwrite (true = yes, false = no)
$createBackups = true;

// password protection; leave empty if you don't need it
$loginPassword = "";

// send an e-mail to this address after each upload, example: "john.doe@isp.com"
$mailOnUpload = "";

// send an e-mail to this address after each download, example: "john.doe@isp.com"
$mailOnDownload = "";

// only for development: view debug info messages (true = yes, false = no)
$debugInfo = false;

?>