<?
require_once("inc_security.php");

$list = new fsDataGird($id_field,$name_field,("Listing"));


$sql="1";
$menu = new menu();
$menu->show_count = 1; //tính count sản phẩm
$listAll = array();
$db_cat  = new db_query("SELECT * FROM " . $fs_table);
while($rcat = mysqli_fetch_assoc($db_cat->result)){
   $listAll[$rcat['bc_id']] = $rcat;
}
unset($db_cat);


$arrayCat = array(0=>("Categories"));
$db_cateogry = new db_query("SELECT bc_name,bc_id
										FROM ". $fs_table . "
										WHERE 1 ");
while($row = mysqli_fetch_assoc($db_cateogry->result)){
	$arrayCat[$row["bc_id"]] = $row["bc_name"];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top(("Category listing"),$list->urlsearch())?>
	<?
	if(!is_array($listAll)) $listAll = array();
	?>
	<table border="1" cellpadding="3" cellspacing="0" class="table" width="100%" bordercolor="<?=$fs_border?>">
		<tr>
			<td class="bold bg" width="5"><input type="checkbox" id="check_all" onClick="check('1','<?=count($listAll)+1?>')"></td>
			<td class="bold bg" width="2%" nowrap="nowrap" align="center"><img src="<?=$fs_imagepath?>save.png" border="0"></td>
			<td class="bold bg" ><?=("Name")?></td>
         <td class="bold bg" ><?=("Tiêu đề")?></td>
			<td class="bold bg" align="center" width="5"><?=("Active")?></td>
			<td class="bold bg" align="center" width="16"><img src="<?=$fs_imagepath?>edit.png" border="0" width="16"></td>
			<td class="bold bg" align="center" width="16"><img src="<?=$fs_imagepath?>delete.gif" border="0"></td>
		</tr>
		<form action="quickedit.php?returnurl=<?=base64_encode(getURL())?>" method="post" name="form_listing" id="form_listing" enctype="multipart/form-data">
		<input type="hidden" name="iQuick" value="update" />
		<?

		$i=0;
		$cat_type = '';
		foreach($listAll as $key=>$row){ $i++;
		?>
		<tr <? if($i%2==0) echo ' bgcolor="#FAFAFA"';?>>
			<td>
				<input type="checkbox" name="record_id[]" id="record_<?=$row["bc_id"]?>_<?=$i?>" value="<?=$row["bc_id"]?>">
			 </td>
			<td width="2%" nowrap="nowrap" align="center"><img src="<?=$fs_imagepath?>save.png" border="0" style="cursor:pointer" onClick="document.form_listing.submit()" alt="Save"></td>

			<td nowrap="nowrap">
				<input type="text"  name="bc_name<?=$row["bc_id"];?>" id="bc_name<?=$row["bc_id"];?>" onKeyUp="check_edit('record_<?=$row["bc_id"]?>_<?=$i?>')" value="<?=$row["bc_name"];?>" class="form" size="50" />
			</td>
         <td nowrap="nowrap">
				<input type="text"  name="bc_title<?=$row["bc_id"];?>" id="bc_title<?=$row["bc_id"];?>" onKeyUp="check_edit('record_<?=$row["bc_id"]?>_<?=$i?>')" value="<?=$row["bc_title"];?>" class="form" size="50" />
			</td>
			<td align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["bc_id"]?>&type=bc_active&value=<?=abs($row["bc_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["bc_active"];?>.gif" title="Active!" /></a></td>
			<td align="center" width="16"><a class="text" href="edit.php?record_id=<?=$row["bc_id"]?>&returnurl=<?=base64_encode(getURL())?>"><img src="<?=$fs_imagepath?>edit.png" alt="EDIT" border="0" /></a></td>
         <td align="center"><img src="<?=$fs_imagepath?>delete.gif" alt="DELETE" border="0" onClick="if (confirm('Are you sure to delete?')){ window.location.href='delete.php?record_id=<?=$row["bc_id"]?>&returnurl=<?=base64_encode(getURL())?>'}" style="cursor:pointer" /></td>

		</tr>
		<? } ?>
		</form>
		</table>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>
