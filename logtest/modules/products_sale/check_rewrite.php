<? 
include ("inc_security.php"); 
//check quyền them sua xoa
checkAddEdit("edit");
$title = getValue("title", "str", "POST", "");
$record_id	= getValue("record_id", "int", "POST");
$check = checkRewriteUrl($title, $record_id);
if($check > 0){
	?><span style="color: red;">Url Rewrite đã tồn tại</span><?
}