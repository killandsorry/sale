<?
include ("inc_security.php");
//check quyền them sua xoa
checkAddEdit("delete");
$record_id=getValue("record_id");
$sql="";
$type    = getValue("type","str","GET","",1);
$value   = getValue("value");
$filed   = $type;

$ajax		= getValue("ajax");
if($ajax==1){
	$db_select = new db_query("SELECT " . $filed . " FROM " . $fs_table . " WHERE ". $field_id ."=" . $record_id);
	if($row=mysqli_fetch_assoc($db_select->result)){
		$value = abs($row[$filed]-1);
	}
}

$url=base64_decode(getValue("url","str","GET",base64_encode("listing.php")));

$fs_redirect	= base64_decode(getValue("returnurl","str","GET",base64_encode("listing.php")));
$record_id		= getValue("record_id","int","GET");
//kiểm tra quyền sửa xóa của user xem có được quyền ko
//checkRowUser($fs_table,$field_id,$record_id,$fs_redirect);

$db_category	= new db_execute("UPDATE " . $fs_table . " SET " . $filed . " = " . $value . " WHERE ". $field_id ." =" . $record_id);
unset($db_category);
redirect($url);
?>