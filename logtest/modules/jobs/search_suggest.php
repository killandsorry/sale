<?
include 'inc_security.php';

$uemail  = getValue('uemail', 'str', 'GET', '');
$cname  = getValue('cname', 'str', 'GET', '');
$q       = ($uemail != '')? trim($uemail) : trim($cname);

$arrayReturn = array(
   'result' => array(),
   'status' => 0,
   'error' => ''
);

if($q == ''){
   echo json_encode($arrayReturn);
   exit();
}else{
   $q    = $vl_class->clean_keyword($q);
   $array_search  = explode(' ', trim($q));
   
   $sql_query     = '';
   if(!empty($array_search)){
      $arr  = array();
      $count   = count($array_search);
      $count_s = 1;
      switch($count){
         case 1:
         case 2:
            $count_s = 1;
            break;
         case 3:
         case 4:
            $count_s = 2;
            break;
         case 5:
         case 6:
            $count_s = 3;
            break;
         case 7:
         case 8:
            $count_s = 4;
            break;
         case 9:
         case 10:
            $count_s = 5;
            break;
            
      }
      
      
      if($uemail != ''){
         
         $sql_query = "SELECT * FROM user_employer 
                       WHERE use_email LIKE '%". $q ."%' LIMIT 10";
         $new_arr    = array();
         $db_search  = new db_query($sql_query);
         while($row  = mysqli_fetch_assoc($db_search->result)){
            $adata['id']      = $row['use_id'];
            $adata['name']   = $row['use_email'];
            $adata['info'] = $row['use_name'];
            $arrayReturn['result'][] = $adata;
         }
         unset($db_search);
         
         $arrayReturn['status'] = 1;
         echo json_encode($arrayReturn);
         exit();
      }
      
      if($cname != ''){
         foreach($array_search as $kid => $ks){
            $arr[] = "(com_name_accent LIKE '%". $ks ."%')";
         }
         
         $sql_query = "SELECT *," . implode(' + ', $arr) . " rank 
                       FROM ( SELECT * FROM company a ) q
                       WHERE (" . implode(" OR ", $arr) . ")
                       HAVING rank > " . $count_s . "
                       ORDER BY rank DESC LIMIT 10";
         
         $new_arr    = array();
         $db_search  = new db_query($sql_query);
         while($row  = mysqli_fetch_assoc($db_search->result)){
            $adata['id']      = $row['com_id'];
            $adata['name']   = $row['com_name'];
            $adata['info']    = $row['com_address'];
            $new_arr[] = $adata;
         }
         unset($db_search);
         
         $arrayReturn['status'] = 1;
         $arrayReturn['result'] = $new_arr;
         echo json_encode($arrayReturn);
         exit();
      }
            
   }
}