<?
require_once("inc_security.php");
$list = new fsDataGird($id_field,$name_field,translate_text("Classifields listing"));
//mặc định ngày bắt đầu và ngày kết thúc
$start_dat		= date("d/m/Y");
$end_dat			= date("d/m/Y",str_totime(date("d/m/Y")) + (1 * 24 * 60 * 60)); 

//get ngày cho xuống 2 ô từ ngày và đến ngày
$_GET['start_date']	=	isset($_GET['start_date']) ? $_GET['start_date'] : $start_dat;
$_GET['end_date']		=	isset($_GET['end_date']) ? $_GET['end_date'] : $end_dat;

/*
1: Ten truong trong bang
2: Tieu de header
3: kieu du lieu
4: co sap xep hay khong, co thi de la 1, khong thi de la 0
5: co tim kiem hay khong, co thi de la 1, khong thi de la 0
*/

$list->add("new_id", "ID", "int", 1, 0);
$list->add("new_title", "Tiêu đề", "str", 0, 0);
$list->add("new_cat_id", "Danh mục", "str", 0, 0);
$list->add("new_date_create", "Ngày tạo", "int", 1, 0);
$list->add("new_last_update","Sửa lần cuối", "int", 0, 0);
$list->add("new_hits", "Lượt xem", "int", 0, 0);
$list->add("new_author", "Nguồn bài viết", "str", 0, 0);
$list->add("new_active", "Kích hoat", "int", 0, 0);

//add 2 ô tìm kiếm theo ngày
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
$sql_date				=	"new_date_create >= " . $timestart_con  . " AND  new_date_create <= " . $timeend_con . " ";
//Lấy dữ liệu
$total			= new db_count("SELECT 	count(*) AS count 
										 FROM news_post
										 WHERE " . $sql_date .	$list->sqlSearch()); // AND

$db_listing 	= new db_query("SELECT new_id, new_title, new_picture, new_cat_id, new_date_create, new_last_update, new_hits, new_author, new_active
										 FROM news_post
										 WHERE  ". $sql_date  ."
										 ORDER BY new_last_update DESC
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
			
			<?=$list->start_tr($i, $row['new_id'])?>
			<td><?=$row['new_id']?></td>
			<?			
			$link		=	rewrite_url_news($arr_cat[$row['new_cat_id']], $row);
			?>
			<td width="250"><a href="<?=$link?>" target="_blank"><?=$row['new_title']?></a></td>			
			<td width="100" align="left"> <?=$arr_cat[$row['new_cat_id']]?> </td>
			<td width="90" align="center"> <?=date("d/m/Y",$row['new_date_create'])?> </td>
			<td width="90" align="center"> <?=date("d/m/Y",$row['new_last_update'])?> </td>
			<td width="90" align="center"> <?=$row['new_hits']?> </td>
			<td width="150" align="left"> <?=$row['new_author']?> </td>			
			<td width="60" align="center"><a onClick="loadactive(this); return false;" href="active.php?record_id=<?=$row["new_id"]?>&type=new_active&value=<?=abs($row["new_active"]-1)?>&url=<?=base64_encode(getURL())?>"><img border="0" src="<?=$fs_imagepath?>check_<?=$row["new_active"];?>.gif" title="Active!" /></a></td>			
			<?=$list->showEdit($row['new_id'])?>
			<td width="10" align="center"><a onclick="if (confirm('Bạn muốn xóa bản ghi?')){ deletefile(<?=$row['new_id']?>,'<?=$row['new_picture']?>'); }" href="#" class="delete"><img border="0" src="../../resource/images/grid/delete.gif"></a></td>
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