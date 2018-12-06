<?php
#Get filemanager setting from request
#End get config
//$path = "D:/xampp/htdocs/dulieu2010/az24/images";
//echo base64_encode($path);
#Initialize system
include_once('class/FileManager.php');
$FileManager = new FileManager($path);
$FileManager->allowFileTypes = $ext;

$parts = parse_url($FileManager->fmWebPath);
if(!$parts) die('ERROR: invalid web path!');

session_set_cookie_params(0, $parts['path'], $parts['host']);
session_start();
#End

#Authentication
if( !isset($_SESSION['user_id']) || (int)$_SESSION['user_id'] <= 0 ){
   exit();
}
#End

header("Content-type: text/html; charset=$FileManager->encoding");
header('Cache-control: private, no-cache, must-revalidate');
header('Expires: 0');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>File Manager</title>
</head>
<body style="background-color:#F0F0F0">
<table border="0" width="100%" height="90%"><tr>
<td align="center">
<?php
print $FileManager->create();
?>
</td>
</tr></table>

</body>
</html>