<?
require_once("inc_security.php");
//check quyền them sua xoa
checkAddEdit("delete");
$fs_redirect	= base64_decode(getValue("returnurl","str","GET",base64_encode("listing.php")));
$record_id		= getValue("record_id","int","GET");
//kiểm tra quyền sửa xóa của user xem có được quyền ko
//checkRowUser($fs_table,$field_id,$record_id,$fs_redirect);
//kiểm tra xóa hết cấp con chưa mới có thể xóa cấp cha
$db_select = new db_query("SELECT tag_id FROM " . $fs_table . " WHERE tag_id =" . $record_id);

if($row=mysqli_fetch_assoc($db_select->result)){
	$db_del = new db_execute("DELETE FROM ". $fs_table ." WHERE tag_id =" . $record_id);
   unset($db_del);
}

//Delete data with ID


redirect($fs_redirect);

?>