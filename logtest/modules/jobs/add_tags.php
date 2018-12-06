<?
include 'inc_security.php';

$tag = getValue('tag_name', 'str', 'POST', '');

$array_return  = array(
   'tag_id' => 0,
   'status' => 0,
   'error' => '',
   'tag_name' => ''
);

if($tag != ''){
   $db_ex   = new db_execute_return();
   $tag_id  = $db_ex->db_execute("INSERT INTO tags (tag_name) VALUES ('". replaceMQ($tag) ."')");
   if($tag_id > 0){
      $array_return['tag_id'] = $tag_id;
      $array_return['status'] = 1;
      $array_return['tag_name'] = $tag;
   }else{
      $array_return['error'] = 'Thêm tags không thành công';
   }
}else{
   $array_return['error'] = 'Không có tags';
}

echo json_encode($array_return);
exit();