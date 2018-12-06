<?
require_once("../security/security.php");
require_once("../../functions/functions.php");
$file 	= getValue("file","str","GET","");
$fileUrl = getValue("fileUrl","str","GET","");
$urlreturn = getValue("urlreturn","str","GET","/editor/filemanager/browser/default/frmresourceslist.html");
if(strpos("finalstyle" . $fileUrl,"finalstyle/upload_images/")!==false && strpos($fileUrl . "/finalstyle",$file . "/finalstyle")!==false){
	//echo $fileUrl;
	@unlink($_SERVER['DOCUMENT_ROOT'] . $fileUrl);
}
?>
<script language="javascript">history.back(-1)</script>