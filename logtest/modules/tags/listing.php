<?
require_once("inc_security.php");

$list = new fsDataGird($id_field,$name_field,("Listing"));

//$cal_cat_id = getValue("cal_cat_id","int","GET",0);
$iCat		 	= getValue("iCat");
$sql        = "";
//if($cal_cat_id>0)  $sql=" AND cal_cat_id = " . $cal_cat_id;
$menu = new menu();
$menu->show_count = 1; //tính count sản phẩm
$listAll = array();
$db_cat  = new db_query("SELECT * FROM tags WHERE 1 " . $sql . " ORDER BY tag_active ASC");
while($rcat = mysqli_fetch_assoc($db_cat->result)){
   $listAll[$rcat['tag_id']] = $rcat;
}
unset($db_cat);

/*
$arrayCat = array(0=>("Categories"));
$db_cateogry = new db_query("SELECT cat_name,cat_id
										FROM category
										WHERE 1 ");
while($row = mysqli_fetch_assoc($db_cateogry->result)){
	$arrayCat[$row["cat_id"]] = $row["cat_name"];
}
*/

//$list->addSearch(("select_category"),"cal_cat_id","array",$arrayCat,$cal_cat_id);
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
			<td class="bold bg" >Tags</td>
         <td class="bold bg" >Active</td>
         <td class="bold bg" >Hot</td>
         <td class="bold bg" >Supper hot</td>
         <td class="bold bg" >Sửa</td>         
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
				<input type="checkbox" name="record_id[]" id="record_<?=$row["tag_id"]?>_<?=$i?>" value="<?=$row["tag_id"]?>">
			 </td>
			<td width="2%" nowrap="nowrap" align="center"><img src="<?=$fs_imagepath?>save.png" border="0" style="cursor:pointer" onClick="document.form_listing.submit()" alt="Save"></td>

			<td nowrap="nowrap">
            <p>
              Tags:<br />
				  <input type="text"  name="tag_name<?=$row["tag_id"];?>" id="tag_name<?=$row["tag_id"];?>" onKeyUp="check_edit('record_<?=$row["tag_id"]?>_<?=$i?>')" value="<?=$row["tag_name"];?>" class="form" size="100" /><br />
              <?/*
              Title:<br />
              <input type="text"  name="tag_title<?=$row["tag_id"];?>" id="tag_title<?=$row["tag_id"];?>" onKeyUp="check_edit('record_<?=$row["tag_id"]?>_<?=$i?>')" value="<?=$row["tag_title"];?>" class="form" size="100" /><br />
              Keyword:<br />
              <input type="text"  name="tag_keyword<?=$row["tag_id"];?>" id="tag_keyword<?=$row["tag_id"];?>" onKeyUp="check_edit('record_<?=$row["tag_id"]?>_<?=$i?>')" value="<?=$row["tag_keyword"];?>" class="form" size="100" /><br />
              Description:<br />
              <textarea name="tag_des<?=$row["tag_id"];?>" id="tag_des<?=$row["tag_id"];?>" onKeyUp="check_edit('record_<?=$row["tag_id"]?>_<?=$i?>')" style="width: 90%; height: 30px" class="form"><?=$row['tag_des']?></textarea>
              */?>
            </p>
			</td>
         <td align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["tag_id"]?>&type=tag_active&value=<?=abs($row["tag_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["tag_active"];?>.gif" title="Active!" /></a></td>
         <td align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["tag_id"]?>&type=tag_hot&value=<?=abs($row["tag_hot"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["tag_hot"];?>.gif" title="Active!" /></a></td>
         <td align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["tag_id"]?>&type=tag_supper_hot&value=<?=abs($row["tag_supper_hot"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["tag_supper_hot"];?>.gif" title="Active!" /></a></td>
			<?=$list->showEdit($row['tag_id'])?>
         <td align="center"><img src="<?=$fs_imagepath?>delete.gif" alt="DELETE" border="0" onClick="if (confirm('Are you sure to delete?')){ window.location.href='delete.php?record_id=<?=$row["tag_id"]?>&returnurl=<?=base64_encode(getURL())?>'}" style="cursor:pointer" /></td>
         
		</tr>
		<? } ?>
		</form>
		</table>
<?=template_bottom() ?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
</body>
</html>
