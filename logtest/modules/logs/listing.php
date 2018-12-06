<?
require_once("inc_security.php");

$list = new fsDataGird($id_field, $name_field, "Company listing");
$list->add("log_id","ID","str",0,1); //tên
$list->add("log_file_name","Name","str",0,0); //tên
$list->add("log_title","Address","str",0,0); //tên
$list->add("log_error","Phone","str",0,0); // ngày tạo - hết hạn
$list->add("log_date","Date","str",0,1); // số lượng
$list->add("","Delete","delete");
// add search
$list->ajaxedit($fs_table);

//Count record
$total			= new db_count("SELECT 	count(*) AS count
										 FROM " . $fs_table . "
										 WHERE 1 " . $list->sqlSearch());


$db_listing 	= new db_query("SELECT *
										 FROM ". $fs_table ."
										 WHERE 1 
										 ORDER BY log_id DESC
										 " . $list->limit($total->total));
$total_row		= 	$total->total;
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
			?>
				<?=$list->start_tr($i, $row['log_id'])?>
				<td style="text-align: center;"><b><?=$row['log_id']?></b></td>
				<td>
					<p><b><?=$row['log_file_name']?></b></p>
				</td>
				<td>
					<?=$row['log_title']?>
				</td>
				<td>
					<p><?=$row['log_error']?></p>
				</td>
				<td width="110">
					<?
               echo date('d/m/Y H:i', $row['log_date']);
               ?>
				</td>
				<?=$list->showDelete($row['log_id'])?>
				<?=$list->end_tr()?>
			<?
	  	}
	?>
	<?=$list->showFooter($total_row)?>
</div>
<!--</form>-->
</body>
</html>