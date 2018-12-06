<?
require_once("inc_security.php");

$list = new fsDataGird($id_field, $name_field, "Company listing");
$list->add("use_id","ID","str",0,1); //tên
$list->add("use_fullname","Name","str",0,0); //tên
$list->add("use_phone","Phone","str",0,0); //tên
$list->add("use_email_notifi","Email","str",0,0); //tên
$list->add("umc_key","Cv key","str",0,0); //tên
$list->add("umc_cv_name","Cv name","str",0,0); //tên
$list->add("umc_date_create","Date create","str",0,1); // số lượng
$list->add("umc_lang","Lang","str",0,1); // số lượng
$list->add("umc_temp_cv","Cv temp","str",0,1); // số lượng
$list->add("",translate_text("Delete"),"delete");
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
$db_count			= new db_query("SELECT 	count(*) AS count,SUM(IF(umc_date_create > ". $time_today .",1,0)) AS use_new
										 FROM user_my_cv
										 WHERE 1 " . $sql_where . $list->sqlSearch());
if($rcount  = mysqli_fetch_assoc($db_count->result)){
   $total_row = $rcount['count'];
   $total_new  = $rcount['use_new'];
}
unset($db_count);


$db_listing 	= new db_query("SELECT *
										 FROM user_my_cv
                               INNER JOIN user ON umc_use_id = use_id
										 WHERE 1 AND use_type = 0  ". $sql_where ."
										 ORDER BY umc_id DESC
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
   Tổng: <b><?=format_number($total_row)?></b> - New user: <b><?=$total_new?></b>
</div>
<div id="listing">
  <?=$list->showHeader($total_row)?>
  <?
	  	$i = 0;
		while($row	=	mysqli_fetch_assoc($db_listing->result)){
	  		$i++;
         $profile = createLinkViewProfile($row['use_id']);
			?>
				<?=$list->start_tr($i, $row['umc_id'])?>
				<td style="text-align: center;"><b><?=$row['use_id']?></b></td>
				<td>
					<img style="width: 40px; height: 40px; float: left; margin-right: 10px;;" src="<?=$row['use_avatar']?>" />
               <a href="<?=$profile?>" target="_blank"><b><?=$row['use_fullname']?></b></a>
				</td>
            <td><?=$row['use_phone']?></td>
            <td><?=$row['use_email_notifi']?></td>
				<td><a href="https://1viec.com/cv/myviewcv.php?mycv=<?=$row['umc_key']?>" target="_blank"><?=$row['umc_key']?></a></td>
            <td><?=$row['umc_cv_name']?></td>
				<td style="text-align: center;"><?=date('d/m/Y', $row['umc_date_create'])?></td>
				<td style="text-align: center;"><?=$row['umc_lang']?></td>
            <td style="text-align: center;"><?=$row['umc_temp_cv']?></td>
            <td width="10" align="center"><a class="delete" href="#" onclick="if (confirm('Bạn muốn xóa bản ghi?')){ deletetwo(<?=$row["umc_id"]?>); }"><img src="../../resource/images/grid/delete.gif" border="0"></a></td>
            
				<?=$list->end_tr()?>
			<?
	  	}
	?>
	<?=$list->showFooter($total_row)?>
</div>
<!--</form>-->
</body>

<script>
    function deletetwo(id){
    	$("#tr_"+id).remove();
        	$.ajax({
        		type: "POST",
        		url: "delete_cv.php",
        		data: "record_id="+id,
        		success: function(msg){
        		  if(msg!=''){
        		  	alert( msg );
      		      }
       		}
    	 });
     }
</script>
</html>