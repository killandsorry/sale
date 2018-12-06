<?
require_once("inc_security.php");

$list = new fsDataGird($id_field, $name_field, "Company listing");
$list->add("com_id","ID","str",0,1); //tên
$list->add("com_name","Name","str",0,0); //tên
$list->add("com_address","Address","str",0,0); //tên
$list->add("com_home_phone","Phone","str",0,0); // ngày tạo - hết hạn
$list->add("com_name_contact","Name contact","str",0,1); // số lượng
$list->add("","Edit","edit");
$list->add("","Delete","delete");
// add search
$list->addSearch("ID","rec_id", "text","");
$list->ajaxedit($fs_table);
$list->page_size = 100;

$rec_id			= getValue("rec_id", "int", "GET", 0);


if($rec_id > 0){
	$sql_where	.= " AND com_id = " . $rec_id;
}

//Count record
$total			= new db_count("SELECT 	count(*) AS count
										 FROM " . $fs_table . "
										 WHERE 1 " . $sql_where . $list->sqlSearch());


$db_listing 	= new db_query("SELECT *, IF(com_email <> '',1,0) AS email
										 FROM ". $fs_table ."
										 WHERE 1 ". $sql_where ."
										 ORDER BY email ASC, com_id DESC
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
				<?=$list->start_tr($i, $row['com_id'])?>
				<td style="text-align: center;"><b><?=$row['com_id']?></b></td>
				<td>
					<p><b><?=$row['com_name']?></b></p>
					<p style="color: #999;"><?=$row['com_name_accent']?></p>
				</td>
				<td>
					<?=$row['com_address']?>
				</td>
				<td>
					<p>Hphone: <?=$row['com_home_phone']?></p>
					<p>Phone: <b><?=$row['com_mobile_phone']?></b></p>
				</td>
				<td width="110">
					<b><?=$row['com_name_contact']?></b>
					<p><?=$row['com_email']?></p>
				</td>
				<?=$list->showEdit($row['com_id'])?>
				<?=$list->showDelete($row['com_id'])?>
				<?=$list->end_tr()?>
			<?
	  	}
	?>
	<?=$list->showFooter($total_row)?>
</div>
<!--</form>-->
</body>
</html>