<?
require_once("inc_security.php");
$list = new fsDataGird($id_field,$name_field,translate_text("Product listing"));
//mặc định ngày bắt đầu và ngày kết thúc
$start_dat		= date("d/m/Y");
$end_dat			= date("d/m/Y",str_totime(date("d/m/Y")) + (1 * 24 * 60 * 60));

//get ngày cho xuống 2 ô từ ngày và đến ngày
$_GET['start_date']	=	isset($_GET['start_date']) ? $_GET['start_date'] : $start_dat;
$_GET['end_date']		=	isset($_GET['end_date']) ? $_GET['end_date'] : $end_dat;
$keyword					= getValue("pro_name","str", "GET", "");
$pro_cat_id				= getValue("pro_cat_id","int", "GET", 0);

/*
1: Ten truong trong bang
2: Tieu de header
3: kieu du lieu
4: co sap xep hay khong, co thi de la 1, khong thi de la 0
5: co tim kiem hay khong, co thi de la 1, khong thi de la 0
*/

$list->add("pro_image", "pro_image", "int", 1, 0);
$list->add("pro_name", "Tên sản phẩm", "str", 0, 0);
$list->add("pro_cat_id", "Danh mục", "str", 0, 0);
$list->add("pro_date", "Ngày tạo", "int", 1, 0);
$list->add("pro_active", "Kích hoat", "int", 0, 0);
$list->add("pro_hot", "Hot", "int", 0, 0);

//add 2 ô tìm kiếm theo ngày
$list->addSearch("Từ khóa","pro_name", "text",$keyword,translate_text("Enter keyword"));
$list->addSearch("Loại","pro_cat_id", "array",$arr_cat, $pro_cat_id);
$list->addSearch("Từ ngày","start_date", "date","");
$list->addSearch("Đến ngày","end_date","date","");


//$list->add("",translate_text("Copy"),"copy");
$list->add("",translate_text("Edit"),"edit");
$list->add("",translate_text("Delete"),"delete");
$list->ajaxedit($fs_table);

//lấy giá trị của control cla_date_create và cla_date_espries
$time_start			=	getValue("start_date","str","GET",$start_dat);
$end_time			=	getValue("end_date","str","GET",$end_dat);
//chuyển cả 2 giá trị về kiểu int
$timestart_con			=	str_totime($time_start);
$timeend_con			=	str_totime($end_time);
//câu sql where ngày tạo hiển thị lớn hơn từ ngày và nhở hơn đến ngày
$sql_date				=	"1 ";
$sql 						= "";
if($keyword != "" && $keyword != translate_text("Enter keyword")){
	$sql .= " AND (
						pro_name LIKE '%" . $keyword . "%'
						OR pro_name LIKE '%" . $keyword . "%'
						OR pro_teaser LIKE '%" . $keyword . "%'
						OR pro_content LIKE '%" . $keyword . "%'
						)";
}
if($pro_cat_id > 0){
	$sql .= " AND pro_cat_id = " . $pro_cat_id;
}
//Lấy dữ liệu
$total			= new db_count("SELECT 	count(*) AS count
										 FROM " . $fs_table . "
										 WHERE " . $sql_date .	$sql); // AND

$db_listing 	= new db_query("SELECT *
										 FROM " . $fs_table . "
										 WHERE  ". $sql_date . $sql  ."
										 ORDER BY pro_id DESC
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
<input type="hidden" class="iCat" id="iCat" name="iCat" value="0" />
<script>
/*==================================================================================*/
$("#pro_name").autoSuggest('/ajax/autocomplete.php', {
   minChars : 1,
   selectionLimit : 1,
   selectedItemProp : 'p_text',
   searchObjProps : 'p_text',
   selectedItemiCat: 'iCat',
   startText: '',
   retrieveLimit : 15,
   formatList : function(data, el) {
      var html = formatResults(data);
      el.html(html);
      $('.as-list').append(el);
   },
   retrieveComplete: function(data) {
      return data.result;
   }
});
</script>
  <?
  	$i = 0;
	while($row	=	mysql_fetch_assoc($db_listing->result)){
  		$i++;
		?>

			<?=$list->start_tr($i, $row['pro_id'])?>
			<td width="100">
				<img src="<?=$img_path . "small_" . $row["pro_image"]?>" onerror="this.src='<?=$fs_nophoto?>'" width="90" height="90" />
			</td>
			<?
			$link		=	'#';
			?>
			<td valign="top">
				<div><a href="<?=$link?>" style="font-weight: bold; text-decoration: none; color: green;" target="_blank"><?=$row['pro_name']?></a></div>
				<div style="padding-top: 5px;">Giá bán: <b style="color: red;"><?= format_number($row["pro_price"])?></b>đ</div>
			</td>
			<td width="100" align="left"> <?=$arr_cat[$row['pro_cat_id']]['cat_name']?> </td>
			<td width="90" align="center"> <?=date("d/m/Y",$row['pro_date'])?> </td>
			<td width="60" align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["pro_id"]?>&type=pro_active&value=<?=abs($row["pro_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["pro_active"];?>.gif" title="Active!" /></a></td>
			<td width="60" align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["pro_id"]?>&type=pro_hot&value=<?=abs($row["pro_hot"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["pro_hot"];?>.gif" title="Hot!" /></a></td>
			<?=$list->showEdit($row['pro_id'])?>
			<td width="10" align="center"><a onclick="if (confirm('Bạn muốn xóa bản ghi?')){ deletefile(<?=$row['pro_id']?>,'<?=$row['pro_image']?>'); }" href="#" class="delete"><img border="0" src="../../resource/images/grid/delete.gif"></a></td>
			<?=$list->end_tr()?>
		<?
  	}
	  unset($db_listing);
  ?>
  <?=$list->showFooter($total_row)?>
</div>
<!--</form>-->
<? /*---------Body------------*/ ?>
<script>
	function deletefile(id,file){
	$("#tr_"+id).remove();
	$.ajax({
		type: "POST",
		url: "delete.php",
		data: {record_id:id,
		file:	file},
		success: function(msg){
		  if(msg!=''){
		  	alert( msg );
		  }
		}
	 });
	}
</script>
</body>
</html>
