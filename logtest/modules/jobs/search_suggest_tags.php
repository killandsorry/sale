<?
include 'inc_security.php';

$tags = getValue('tags', 'str', 'GET', '');

$array_return   = array(
   'status' => 0,
   'error' => '',
   'result' => array()
);

if($tags == ''){
   $array_return['error'] = 'Không có tags';
   echo json_encode($array_return);
   exit();
}
$new_arr = array();
$db_tags = new db_query("SELECT * FROM tags WHERE tag_name LIKE '%". replaceMQ($tags) ."%' LIMIT 10");
while($rtag = mysqli_fetch_assoc($db_tags->result)){
   $adata['id']      = $rtag['tag_id'];
   $adata['name']   = $rtag['tag_name'];
   $adata['info']    = '';
   $new_arr[] = $adata;
}
unset($db_tags);

if(!empty($new_arr)){
   $array_return['status'] = 1;
   $array_return['result'] = $new_arr;
}

echo json_encode($array_return);
exit();