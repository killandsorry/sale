<?
require_once("inc_security.php");

if(!isset($_GET['fdate'])) $_GET['fdate'] = date('d/m/Y', time());
if(!isset($_GET['ldate'])) $_GET['ldate'] = date('d/m/Y', time() + 86400 );


$job_id			 = getValue("jobf_id", "int", "GET", 0);
$fdate			 = getValue("fdate","str","GET",date('d/m/Y', (time())));
$ldate			 = getValue("ldate","str","GET",date('d/m/Y', time()));
$arrfDate		 = explode('/', $fdate);
$arrlDate		 = explode('/', $ldate);
$fdate			 = mktime(0,0,0, $arrfDate[1], $arrfDate[0], $arrfDate[2]);
$ldate			 = mktime(0,0,0, $arrlDate[1], $arrlDate[0], $arrlDate[2]);

$list = new fsDataGird($id_field, $name_field, "Jobs listing");
$list->add("jobf_active","Act","int",0,0);
$list->add("jobf_id","ID","str",0,1); //tên
$list->add("jobf_title","Name","str",0,1); //tên
$list->add("","Edit","edit");
$list->add("","Delete","delete");
// add search
$list->addSearch("ID","jobf_id", "text","");
$list->addSearch("From","fdate", "date","");
$list->addSearch("To","ldate","date","");
$list->ajaxedit($fs_table);



$sql_where		= '';
if($fdate > 0 && $ldate > 0){
	$sql_where	= " AND jobf_date BETWEEN ". $fdate . " AND " . $ldate;
}


if($job_id > 0){
	$sql_where .= " AND jobf_id = ". $job_id;
}



$total_row  = 0;

if($job_id <= 0){
   //Count record
   $total			= new db_count("SELECT 	count(*) AS count
   										 FROM job_fb
   										 WHERE 1 " . $sql_where . $list->sqlSearch());
   $total_row		= 	$total->total;
   
   $db_listing 	= new db_query("SELECT *
   										 FROM job_fb
   										 WHERE 1 ". $sql_where ."
   										 ORDER BY jobf_active ASC
   										 " . $list->limit($total->total));
}else{
   $table_job  = $vl_class->create_table_job($job_id);
   $db_listing 	= new db_query("SELECT *
   										 FROM job_fb
   										 WHERE 1 AND jobf_id = ". intval($job_id));
   
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
         $link = create_link_job_fb($row);
         ?>
			<?=$list->start_tr($i, $row['jobf_id'])?>
         <td align="center" width="20"><a onClick="$('#record_<?=$i?>').attr('checked', false); loadactive(this); return false;" href="active.php?record_id=<?=$row["jobf_id"]?>&type=job_active&value=<?=abs($row["jobf_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["jobf_active"];?>.gif" title="Active!" /></a></td>
			<td style="text-align: center;">
            <a href="<?=$link?>" target="_blank">
               <b><?=$row['jobf_id']?></b>
            </a>
         </td>
			<td>
				<p><a href="<?=$link?>" target="_blank"><b><?=$row['jobf_title']?></b></a></p>
			</td>
			<?=$list->showEdit($row['jobf_id'])?>
			<td>
            <a class="delete" href="#" onclick="if (confirm('Bạn muốn xóa bản ghi?')){ deletetwo(<?=$row['jobf_id']?>); }"><img src="../../resource/images/grid/delete.gif" border="0"></a>
         </td>
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