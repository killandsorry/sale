<?
require_once("inc_security.php");
$list = new fsDataGird($id_field,$name_field,translate_text("Countries Listing"));
$list->page_size = 100;
/*
1: Ten truong trong bang
2: Tieu de header
3: kieu du lieu
4: co sap xep hay khong, co thi de la 1, khong thi de la 0
5: co tim kiem hay khong, co thi de la 1, khong thi de la 0
*/
$list->add("cit_name","Tên tỉnh thành","string",0,1);
$list->add("cit_order","Thứ tự","int",0,0);
$list->add("",translate_text("Copy"),"copy");
$list->add("",translate_text("Edit"),"edit");
$list->add("",translate_text("Delete"),"delete");
$list->ajaxedit($fs_table);

//Lấy dữ liệu
$total			= new db_count("SELECT COUNT(*) AS count
										 FROM city
										 WHERE cit_parent_id=0 "	.	$list->sqlSearch() . "
										 ORDER BY cit_order ASC "); // AND

$db_listing 	= new db_query("SELECT *
										 FROM city
										 WHERE cit_parent_id=0 "	.	$list->sqlSearch() . "
										 ORDER BY cit_order ASC
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
  	$i = 1;
	while($row	=	mysqli_fetch_assoc($db_listing->result))
	{
		?>
		<tr>
			<td colspan="11" style="background-color: #F9F7C0; font-weight: bold; color: #EF3207; text-align: center;">
				<?=$row['cit_name']?>
			</td>
		</tr>
		<?=$list->start_tr($i, $row['cit_id'])?>
		<td width="200"><input style="width: 400px;" type="text" name="cit_name_<?=$row['cit_id']?>" value="<?=$row['cit_name']?>" onkeyup="check_edit('record_<?=$i?>')" /></td>
		<td width="50"><input style="width: 30px;" type="text" name="cit_order_<?=$row['cit_id']?>" value="<?=$row['cit_order']?>" onkeyup="check_edit('record_<?=$i?>')" /></td>
		<?=$list->showCopy($row['cit_id'])?>
		<?=$list->showEdit($row['cit_id'])?>
		<?=$list->showDelete($row['cit_id'])?>
		<?=$list->end_tr()?>

		<?
			$sql_child				=	"SELECT * FROM city WHERE cit_parent_id=". $row['cit_id'];
			$sql_child_query		=	mysql_query($sql_child);
			while($row_child		=	mysqli_fetch_assoc($sql_child_query))
			{
				$i	+=1;
				?>
				<?=$list->start_tr($i, $row_child['cit_id'])?>
				<td width="200">--<input style="width: 400px;" type="text" name="cit_name_<?=$row_child['cit_id']?>" value="<?=$row_child['cit_name']?>" onkeyup="check_edit('record_<?=$i?>')" /></td>
				<td width="50" align="center"><input style="width: 100px;" type="text" name="cit_alias_<?=$row_child['cit_id']?>" value="<?=$row_child['cit_alias']?>"    onkeyup="check_edit('record_<?=$i?>')" /></td>
				<td width="30" align="center"><input style="width: 100px;" type="text" name="cit_importan_<?=$row_child['cit_id']?>" value="<?=$row_child['cit_importan']?>"    onkeyup="check_edit('record_<?=$i?>')" /></td>
				<td width="50"><input style="width: 30px;" type="text" name="cit_order_<?=$row_child['cit_id']?>" value="<?=$row_child['cit_order']?>" onkeyup="check_edit('record_<?=$i?>')" /></td>
				<?=$list->showCopy($row_child['cit_id'])?>
				<?=$list->showEdit($row_child['cit_id'])?>
				<?=$list->showDelete($row_child['cit_id'])?>
				<?=$list->end_tr()?>
				<?
			}
		?>
		<?
		$i	+=1;
	}
	?>

  <?=$list->showFooter($total_row)?>
</div>
<!--</form>-->
<? /*---------Body------------*/ ?>
</body>
</html>