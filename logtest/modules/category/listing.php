<?
require_once("inc_security.php");

$list = new fsDataGird($id_field,$name_field,("Listing"));

$cat_type 			= getValue("cat_type","str","GET","");
$iCat		 			= getValue("iCat");
if($cat_type=="") $cat_type=getValue("cat_type","str","POST","");
$sql="1";
if($cat_type!="")  $sql="cat_type = '" . $cat_type . "'";
$menu = new menu();

$menu->show_count = 1; //tính count sản phẩm
$listAll = array();
$db_cat  = new db_query("SELECT * FROM category WHERE " . $sql);
while($rcat = mysqli_fetch_assoc($db_cat->result)){
   $listAll[$rcat['cat_id']] = $rcat;
}
unset($db_cat);


$arrayCat = array(0=>("Categories"));
$db_cateogry = new db_query("SELECT cat_name,cat_id
										FROM category
										WHERE " . $sql);
while($row = mysqli_fetch_assoc($db_cateogry->result)){
	$arrayCat[$row["cat_id"]] = $row["cat_name"];
}

$list->addSearch(("select_type_category"),"cat_type","array",$arrayCategoryType,$cat_type);
//$list->addSearch(("Categories"),"iCat","array",$arrayCat,$iCat);

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
			<td class="bold bg" ><?=("name")?></td>
			<td class="bold bg" align="center"><?=("order")?></td>
			<td class="bold bg" align="center" width="5"><?=("Active")?></td>
			<td class="bold bg" align="center" width="5"><?=("Hot")?></td>
			<td class="bold bg" align="center" width="5"><img src="<?=$fs_imagepath?>copy.gif" border="0" ></td>
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
				<input type="checkbox" name="record_id[]" id="record_<?=$row["cat_id"]?>_<?=$i?>" value="<?=$row["cat_id"]?>">
			 </td>
			<td width="2%" nowrap="nowrap" align="center"><img src="<?=$fs_imagepath?>save.png" border="0" style="cursor:pointer" onClick="document.form_listing.submit()" alt="Save"></td>

			<td nowrap="nowrap">
				<span style="display: inline-block; width: 60px">Danh mục:</span> <input type="text" style="width: 500px"  name="cat_name<?=$row["cat_id"];?>" id="cat_name<?=$row["cat_id"];?>" onKeyUp="check_edit('record_<?=$row["cat_id"]?>_<?=$i?>')" value="<?=$row["cat_name"];?>" class="form"  /><br /><br />
            <span style="display: inline-block; width: 60px">Tiêu đề:</span> <input type="text" style="width: 500px"  name="cat_title<?=$row["cat_id"];?>" id="cat_title<?=$row["cat_id"];?>" onKeyUp="check_edit('record_<?=$row["cat_id"]?>_<?=$i?>')" value="<?=$row["cat_title"];?>" class="form"  /><br /><br />            
            <span style="display: inline-block; width: 60px">Description:</span> <textarea style="width: 800px; height: 40px" name="cat_des<?=$row["cat_id"];?>" id="cat_des<?=$row["cat_id"];?>" onKeyUp="check_edit('record_<?=$row["cat_id"]?>_<?=$i?>')" class="form"  ><?=$row["cat_des"];?></textarea>
			</td>

			<td align="center"><input type="text" size="2" class="form" value="<?=$row["cat_order"]?>" onKeyUp="check_edit('record_<?=$row["cat_id"]?>_<?=$i?>')" id="cat_order<?=$row["cat_id"]?>" name="cat_order<?=$row["cat_id"]?>" /></td>

			<td align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["cat_id"]?>&type=cat_active&value=<?=abs($row["cat_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["cat_active"];?>.gif" title="Active!" /></a></td>
			<td align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["cat_id"]?>&type=cat_hot&value=<?=abs($row["cat_hot"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["cat_hot"];?>.gif" title="Hot!" /></a></td>
			<td align="center" width="16"><img src="<?=$fs_imagepath?>copy.gif" title="<?=("Are you want duplicate record")?>" border="0" onClick="if (confirm('<?=("Are you want duplicate record")?>?')){ window.location.href='copy.php?record_id=<?=$row["cat_id"]?>&returnurl=<?=base64_encode(getURL())?>'}" style="cursor:pointer" /></td>

			<td align="center" width="16"><a class="text" href="edit.php?record_id=<?=$row["cat_id"]?>&returnurl=<?=base64_encode(getURL())?>"><img src="<?=$fs_imagepath?>edit.png" alt="EDIT" border="0" /></a></td>

			<td align="center"><img src="<?=$fs_imagepath?>delete.gif" alt="DELETE" border="0" onClick="if (confirm('Are you sure to delete?')){ window.location.href='delete.php?record_id=<?=$row["cat_id"]?>&returnurl=<?=base64_encode(getURL())?>'}" style="cursor:pointer" /></td>

		</tr>
		<? } ?>
		</form>
		</table>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>
