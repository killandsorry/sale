<?
include('inc_security.php');

// Message
$message		= '';

$actionName	= getValue('action_name', 'str', 'POST', '');
if($actionName == 'category'){
   
   // cache category
   $data_cat   = array();
   $db_cat = new db_query("SELECT * FROM category WHERE cat_type = 'vl'");
   
   $sql_sum = array();
   while($row = mysqli_fetch_assoc($db_cat->result)){
      $data_cat[$row['cat_id']] = $row;
      $sql_sum[] = "SUM(IF(job_cat_id = ". $row['cat_id'] ." ,1, 0)) AS count_" . $row['cat_id'];
   }
   unset($db_cat);
   
   
   // cache số tin
   //echo "SELECT " . implode(',', $sql_sum) . " FROM job_filter WHERE job_date_expires > " . time() . " AND job_active = 1";
   $db_sum   = new db_query("SELECT " . implode(',', $sql_sum) . " FROM job_filter WHERE job_date_expires > " . time() . " AND job_active = 1");
   while($rsum = mysqli_fetch_assoc($db_sum->result)){
      foreach($rsum as $k => $vl){
         $id = intval(str_replace('count_', '', $k));
         if(isset($data_cat[$id])){
            $data_cat[$id]['cat_count'] = $vl;
         }
      }
   }
   unset($db_sum);
   
   
   
	

	$success	= createCache($data_cat, 'cat');
 	if($success == 1){
 		$message	= 'Create cache category success';
 	}else{
 		$message = 'Error when create cache';
 	}
}

if($actionName	== 'city'){
	$db_city	= new db_query("SELECT * FROM city WHERE cit_active = 1 ORDER BY cit_order ASC", __FILE__ . " Line: " . __LINE__);
	$arrCity	= array();
	while($rcit	= mysqli_fetch_assoc($db_city->result)){
		$arrCity[$rcit['cit_id']]	= $rcit;
	}
	unset($db_city);

	$success	= createCache($arrCity, 'cit');
 	if($success == 1){
 		$message	= 'Create cache city success';
 	}else{
 		$message = 'Error when create cache';
 	}


}

if($actionName	== 'keyword'){
	$db_keyword	= new db_query("SELECT * FROM keyword WHERE key_active = 1 AND key_position = 1", __FILE__ . " Line: " . __LINE__);
	$arrKeyword	= array();
	while($rkey	= mysqli_fetch_assoc($db_keyword->result)){
		$arrKeyword[$rkey['key_id']]	= $rkey;
	}
	unset($db_keyword);

	$success	= createCache($arrKeyword, 'keyword');
 	if($success == 1){
 		$message	= 'Create cache Keyword success';
 	}else{
 		$message = 'Error when create cache';
 	}


}



?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<title>Create cache</title>
</head>

<body>

<table style="width: 500px; margin:  0 auto;">
	<tr>
		<td colspan="2"><b style="color: #f00;;"><?=$message?></b></td>
	</tr>
	<tr>
		<td>
			<form method="post" action="" name="fcache">
				<input type="submit" value="Create cache Category" name="sbcreate" />
				<input type="hidden" value="category" name="action_name" />
			</form>
		</td>
		<td>
			<form method="post" action="" name="fcache">
				<input type="submit" value="Create cache City" name="sbcreate" />
				<input type="hidden" value="city" name="action_name" />
			</form>
		</td>
		<td>
			<form method="post" action="" name="fcache">
				<input type="submit" value="Create cache Keyword" name="sbcreate" />
				<input type="hidden" value="keyword" name="action_name" />
			</form>
		</td>
	</tr>
</table>


</body>
</html>