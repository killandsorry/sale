<?
require_once("inc_security.php");

if(!isset($_GET['fdate'])) $_GET['fdate'] = date('d/m/Y', time());
if(!isset($_GET['ldate'])) $_GET['ldate'] = date('d/m/Y', time() + 86400 );


$job_id			 = getValue("job_id", "int", "GET", 0);
$job_hot			 = getValue("job_hot", "int", "GET", 0);
$job_ip			 = getValue("job_ip", "int", "GET", 0);
$fdate			 = getValue("fdate","str","GET",date('d/m/Y', (time())));
$ldate			 = getValue("ldate","str","GET",date('d/m/Y', time()));
$arrfDate		 = explode('/', $fdate);
$arrlDate		 = explode('/', $ldate);
$fdate			 = mktime(0,0,0, $arrfDate[1], $arrfDate[0], $arrfDate[2]);
$ldate			 = mktime(0,0,0, $arrlDate[1], $arrlDate[0], $arrlDate[2]);

$list = new fsDataGird($id_field, $name_field, "Jobs listing");
$list->add("job_active","Act","int",0,0);
$list->add("job_id","ID","str",0,1); //tên
$list->add("job_name","Name","str",0,1); //tên
$list->add("job_hit","Hit","str",0,0); //tên
$list->add("job_cat_id","Info","str",0,1); //tên
$list->add("job_date_create","Date","int",0,0); // ngày tạo - hết hạn
$list->add("job_number","Num","int",0,0); // số lượng

$list->add("job_hot","Hot","int",0,0);
$list->add("job_supper_hot","Supper Hot","int",0,1);
$list->add("","Edit","edit");
$list->add("","Delete","delete");
// add search
$list->addSearch("ID","job_id", "text","");
$list->addSearch("From","fdate", "date","");
$list->addSearch("To","ldate","date","");
$list->addSearch('Hot', 'job_hot', 'array', $arrayFillHot, $job_hot);
$list->addSearch('Auto', 'job_ip', 'array', $arrayFillIp, $job_ip);
$list->ajaxedit($fs_table);



$sql_where		= '';
if($fdate > 0 && $ldate > 0){
	$sql_where	= " AND job_date_create BETWEEN ". $fdate . " AND " . $ldate;
}


if($job_id > 0){
	$sql_where .= " AND job_id = ". $job_id;
}


if($job_hot == 1){
	$sql_where .= " AND job_hot = 1";
}else if($job_hot == 2){
   $sql_where .= " AND job_supper_hot = 1";
}

if($job_ip == 1){
	$sql_where .= " AND job_ip = 0";
}else if($job_ip == 2){
   $sql_where .= " AND job_ip > 0";
}


$total_row  = 0;

if($job_id <= 0){
   //Count record
   $total			= new db_count("SELECT 	count(*) AS count
   										 FROM " . $fs_table . "
   										 WHERE 1 " . $sql_where . $list->sqlSearch());
   $total_row		= 	$total->total;
   
   $db_listing 	= new db_query("SELECT *
   										 FROM ". $fs_table ."
   										 WHERE 1 ". $sql_where ."
   										 ORDER BY job_active ASC, job_company_id ASC
   										 " . $list->limit($total->total));
}else{
   $table_job  = $vl_class->create_table_job($job_id);
   $db_listing 	= new db_query("SELECT *
   										 FROM ". $table_job ."
   										 WHERE 1 AND job_id = ". intval($job_id));
   
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<?=$list->headerScript()?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*---------Body------------*/ ?>
<!--<form action="quickedit.php?returnurl=<?=base64_encode(getURL())?>" method="post" name="form_listing" id="form_listing" enctype="multipart/form-data">
<input type="hidden" name="iQuick" value="update" />-->

<div id="listing">
  <?=$list->showHeader($total_row)?>
  <?
	  	$i = 0;
		while($row	=	mysqli_fetch_assoc($db_listing->result)){
         $i++;		    
         $hit        = 0;
         $db_job     = new db_query("SELECT * FROM job_hit 
                                     WHERE joh_id = " . intval($row['job_id']) . " LIMIT 1");
         if($rhit    = mysqli_fetch_assoc($db_job->result)){
            $hit     = $rhit['joh_hit'];
         }
         unset($db_job);
         $link    = createLinkJob($row);
         ?>
			<?=$list->start_tr($i, $row['job_id'])?>
         <td align="center" width="20"><a onClick="$('#record_<?=$i?>').attr('checked', false); loadactive(this); return false;" href="active.php?record_id=<?=$row["job_id"]?>&type=job_active&value=<?=abs($row["job_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["job_active"];?>.gif" title="Active!" /></a></td>
			<td style="text-align: center;"><b><?=$row['job_id']?></b></td>
			<td>
				<p><a href="<?=$link?>" target="_blank"><b><?=$row['job_name']?></b></a></p>
            <?
            if($row['job_craw_link'] != ''){
               ?>
               <p><a href="<?=$row['job_craw_link']?>" target="_blank"><i><?=$row['job_craw_link']?></i></a></p>
               <?
            }
            ?>
            <p><?='<b style="color: #f00;">' . $row['job_company_id'] . '</b> - ' . $row['job_company_name']?></p>
			</td>
			<td width="40" style=" text-align: center;">
            <b style="color: #f00;"><?=$hit?></b>
            <p><?=($row['job_ip'] > 0)? '<b style="color: #27C02C;">Post</b>' : '<b style="color: #f00;">Auto</b>'?></p>
         </td>
			<td>
            
				<p>
               <?
               if($job_id <= 0){
                  for($c = 1; $c < 3;$c++){
                     if($row['job_cat_'. $c] > 0 && isset($arrayCategory[$row['job_cat_'. $c]])){
                        echo '<span>'. $arrayCategory[$row['job_cat_'. $c]] .'</span> | ';
                     }
                  }
               }else{
                  $arr_cat = explode('|', $row['job_cat_all']);
                  foreach($arr_cat as $cat_id){
                     if($cat_id > 0 && isset($arrayCategory[$cat_id])){
                        echo '<span>'. $arrayCategory[$cat_id] .'</span> | ';
                     }
                  }
               }
               ?>
            </p>
				<p>
               <?
               if($job_id <= 0){
                  for($t = 1; $t < 4;$t++){
                     if($row['job_cit_'. $t] > 0 && isset($arrayCity[$row['job_cit_'. $t]])){
                        echo '<span>'. $arrayCity[$row['job_cit_'. $t]] .'</span> | ';
                     }
                  }
               }else{
                  $arr_cit = explode('|', $row['job_cit_all']);
                  foreach($arr_cit as $cit_id){
                     if($cit_id > 0 && isset($arrayCity[$cit_id])){
                        echo '<span>'. $arrayCity[$cit_id] .'</span> | ';
                     }
                  }
               }
               ?>
            </p>
            <p><b class="price"><?=isset($arraySalary[$row['job_salary']])? $arraySalary[$row['job_salary']]: ''?></b></p>
			</td>
			<td width="110">
				<p>Create: <span><?=date('d/m/Y', $row['job_date_create'])?></span></p>
				<p>Expires: <span class="price"><?=date('d/m/Y', $row['job_date_expires'])?></span></p>
			</td>
			<td width="30" style="text-align: center;"><b><?=intval($row['job_number'])?></b></td>
			
			<td align="center" width="20">
            <a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["job_id"]?>&type=job_hot&value=<?=abs($row["job_hot"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["job_hot"];?>.gif" title="Hot!" /></a>
            <p><?=($row['job_hot_time'] > 0)? date('d/m/Y', $row['job_hot_time']) : ''?></p>
         </td>
         <td align="center" width="20">
            <a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["job_id"]?>&type=job_supper_hot&value=<?=abs($row["job_supper_hot"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["job_supper_hot"];?>.gif" title="Hot!" /></a>
            <p><?=($row['job_supper_hot_time'] > 0)? date('d/m/Y', $row['job_supper_hot_time']) : ''?></p>
         </td>
			<?=$list->showEdit($row['job_id'])?>
			<?=$list->showDelete($row['job_id'])?>
			<?=$list->end_tr()?>
			<?
         unset($db_job);
       
	  	}
	?>
	<?=$list->showFooter(30)?>
   <div>
      <a href="1viec.com/create_sitemap.php" target="_blank">Create cache</a>
   </div>
</div>
<!--</form>-->
</body>
</html>