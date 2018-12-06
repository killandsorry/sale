<?
require_once("inc_security.php");

$list = new fsDataGird($id_field, $name_field, "Company listing");
$list->add("use_id","ID","str",0,1); //tên
$list->add("use_fullname","Name","str",0,0); //tên
$list->add("use_email","Email","str",0,0); //tên
$list->add("use_social_type","Type","str",0,0); //tên
$list->add("use_phone","Phone","str",0,0); // ngày tạo - hết hạn
$list->add("use_date_join","Date create","str",0,1); // số lượng
$list->add("use_process","Process","str",0,1); // số lượng
$list->add("use_cv_name","CV","str",0,1); // số lượng
$list->add("use_active","Act","int",0,0);
// add search
$list->addSearch("Email","use_email", "text","");
$list->ajaxedit($fs_table);


$use_email			= getValue("use_email", "str", "GET", '');
$sql_where  = '';
if($use_email != ''){
	$sql_where	.= " AND use_email = '". replaceMQ($use_email) ."'";
}

$time_today = strtotime('Today');

//Count record
$total_row        = 0;
$total_active     = 0;
$total_new        = 0;
$db_count			= new db_query("SELECT 	count(*) AS count, SUM(IF(use_process > 40, 1, 0)) AS active, SUM(IF(use_date_join > ". $time_today .",1,0)) AS use_new
										 FROM " . $fs_table . "
										 WHERE 1 AND use_type = 0 " . $sql_where . $list->sqlSearch());
if($rcount  = mysqli_fetch_assoc($db_count->result)){
   $total_row = $rcount['count'];
   $total_active  = $rcount['active'];
   $total_new  = $rcount['use_new'];
}
unset($db_count);


$db_listing 	= new db_query("SELECT *
										 FROM ". $fs_table ."
										 WHERE 1 AND use_type = 0  ". $sql_where ."
										 ORDER BY use_date_join DESC
										 " . $list->limit($total_row));

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
<div>
   Tổng: <b><?=format_number($total_row)?></b> - Active: <b><?=format_number($total_active)?></b> - New user: <b><?=$total_new?></b>
</div>
<div id="listing">
  <?=$list->showHeader($total_row)?>
  <?
	  	$i = 0;
		while($row	=	mysqli_fetch_assoc($db_listing->result)){
	  		$i++;
         $profile = createLinkViewProfile($row['use_id']);
			?>
				<?=$list->start_tr($i, $row['use_id'])?>
				<td style="text-align: center;"><b><?=$row['use_id']?></b></td>
				<td>
					<img style="width: 40px; height: 40px; float: left; margin-right: 10px;;" src="<?=$row['use_avatar']?>" />
               <a href="<?=$profile?>" target="_blank"><b><?=$row['use_fullname']?></b></a>
				</td>
				<td><?=$row['use_email']?></td>
            <td><a target="_blank" href="<?=$row['use_profile_social']?>"><?=$row['use_social_type']?></a></td>
				<td style="text-align: center;"><?=$row['use_phone']?></td>
				<td style="text-align: center;"><?=($row['use_date_join'] > 0)? date('d/m/Y', $row['use_date_join']) : ''?></td>
            <td style="text-align: center;"><?=$row['use_process']?></td>
            <td style="text-align: center;"><?=$row['use_cv_name']?></td>
            <td align="center" width="20">
               <a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["use_id"]?>&type=use_active&value=<?=abs($row["use_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["use_active"];?>.gif" title="Hot!" /></a>
            </td>
				<?=$list->end_tr()?>
			<?
	  	}
	?>
	<?=$list->showFooter($total_row)?>
</div>
<!--</form>-->
</body>
</html>