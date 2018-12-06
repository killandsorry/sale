<?php

/**
 * This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 * Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 * redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
 */

// needed to get web path for session and error reporting, time limit settings and encoding
include_once('class/FileManager.php');
$FileManager = new FileManager();
$parts = parse_url($FileManager->fmWebPath);
if(!$parts) die('ERROR: invalid web path!');

session_set_cookie_params(0, $parts['path'], $parts['host']);
session_start();

if($_REQUEST['fmMode'] != 'getFile' && $_REQUEST['fmMode'] != 'getThumbnail') {
	if($FileManager->locale) @setlocale(LC_ALL, $FileManager->locale);
	header("Content-type: text/html; charset=$FileManager->encoding");
	header('Cache-Control: private, no-cache, must-revalidate');
	header('Expires: 0');
}

$container = $_REQUEST['fmContainer'];

if($container && isset($_SESSION[$container])) {
	$FileManager = unserialize($_SESSION[$container]);

	if(function_exists('iconv_set_encoding')) {
		iconv_set_encoding('internal_encoding', $FileManager->encoding);
		iconv_set_encoding('output_encoding', $FileManager->encoding);
	}
	ob_start('ob_iconv_handler');
	$FileManager->action();
	ob_flush();
}
else die('ERROR: session not found!');

?>