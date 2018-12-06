<?
require_once("inc_security.php");

//Khai báo biến khi thêm mới
$fs_redirect		= "listing.php";
$after_save_data	= getValue("after_save_data", "str", "POST", "add.php");
$record_id				= getValue('record_id');
$fs_title			= "Edit Jobs";
$fs_action			= getURL();
$table_job  = $vl_class->create_table_job($record_id);
$job_last_update  = time(); 
$use_id           = 0;
$company_id       = 0;
$arrayCat_select  = array();
$arrayCit_select  = array();
$job_tags         = '';


$myform 				= new generate_form();
$myfilter         = new generate_form();
$mycompany        = new generate_form();
$mycompanydes     = new generate_form();
$myactive         = new generate_form();
$myjob            = new generate_form();

$myform->add("job_name","job_name",0,0,'',1,"Chưa nhập tiêu đề việc làm",0,"");
$myform->add("job_name_accent","job_name_accent",0,1,'',0,"",0,"");
$myform->add("job_skill","job_skill",0,0,'',0,"",0,"");
$myform->add("job_rank","job_rank",1,0,0,1,"Bạn chưa chọn cấp bậc",0,"");
$myform->add("job_company_name","com_name",0,0,1,'Chưa nhập tên công ty',0,"",0,"");
$myform->add("job_last_update","job_last_update",1,1,0,0,"",0,"");
$myform->add("job_date_expires","job_date_expires",1,1,0,0,"",0,"");
$myform->add("job_cat_id","job_cat_id",1,1,0,1,"Chưa nhập danh mục việc làm",0,"");
$myform->add("job_cit_id","job_cit_id",1,1,0,1,"Chưa chọn tỉnh thành",0,"");
$myform->add("job_number","job_number",1,0,1,1,"Chưa số người tuyển dụng",0,"");
$myform->add("job_company_id","company_id",1,1,0,1,"Chưa nhập thông tin công ty",0,"");
$myform->add("job_salary","job_salary",1,0,0,1,"Chưa chọn mức lương",0,"");
$myform->add("job_hot","job_hot",1,0,0,0,"",0,"");
$myform->add("job_supper_hot","job_supper_hot",1,0,0,0,"",0,"");
$myform->add("job_hot_time","job_hot_time",1,1,0,0,"",0,"");
$myform->add("job_supper_hot_time","job_supper_hot_time",1,1,0,0,"",0,"");
$myform->add("job_cat_1","job_cat_1",1,1,0,0,"",0,"");
$myform->add("job_cat_2","job_cat_2",1,1,0,0,"",0,"");
$myform->add("job_cit_1","job_cit_1",1,1,0,0,"",0,"");
$myform->add("job_cit_2","job_cit_2",1,1,0,0,"",0,"");
$myform->add("job_cit_3","job_cit_3",1,1,0,0,"",0,"");
$myform->add("job_contact_name","job_contact_name",0,0,'',1,"Chưa nhập tên người liên hệ",0,"");
$myform->add("job_contact_phone","job_contact_phone",0,0,'',0,"Chưa nhập Số điện thoại liên hệ",0,"");
$myform->add("job_contact_email","job_contact_email",0,0,'',0,"Chưa nhập email người liên hệ",0,"");
$myform->add("job_packet_id","job_packet_id",1,0,0,0,"",0,"");
$myform->add("job_packet_end","job_packet_end",1,1,0,0,"",0,"");
$myform->addTable('job');

$myjob->add("job_name","job_name",0,0,'',1,"Chưa nhập tiêu đề việc làm",0,"");
$myjob->add("job_name_accent","job_name_accent",0,1,'',0,"",0,"");
$myjob->add("job_skill","job_skill",0,0,'',0,"",0,"");
$myjob->add("job_rank","job_rank",1,0,0,1,"Bạn chưa chọn cấp bậc",0,"");
$myjob->add("job_company_name","com_name",0,0,1,1,'Chưa nhập tên công ty',0,"",0,"");
$myjob->add("job_company_id","company_id",1,1,0,0,"",0,"");
$myjob->add("job_last_update","job_last_update",1,1,0,0,"",0,"");
$myjob->add("job_date_expires","job_date_expires",1,1,0,0,"",0,"");
$myjob->add("job_cat_id","job_cat_id",1,1,0,1,"Chưa nhập danh mục việc làm",0,"");
$myjob->add("job_cit_id","job_cit_id",1,1,0,1,"Chưa chọn tỉnh thành",0,"");
$myjob->add("job_number","job_number",1,0,1,1,"Chưa số người tuyển dụng",0,"");
$myjob->add("job_hot","job_hot",1,0,0,0,"",0,"");
$myjob->add("job_supper_hot","job_supper_hot",1,0,0,0,"",0,"");
$myjob->add("job_hot_time","job_hot_time",1,1,0,0,"",0,"");
$myjob->add("job_supper_hot_time","job_supper_hot_time",1,1,0,0,"",0,"");
$myjob->add("job_salary","job_salary",1,0,0,1,"Chưa chọn mức lương",0,"");
$myjob->add("job_short_description","job_short_description",0,0,'',1,"Chưa nhập mô tả ngắn",0,"");
$myjob->add("job_requirement","job_requirement",0,0,'',1,"Chưa nhập yêu cầu khác",0,"");
$myjob->add("job_policy","job_policy",0,0,'',1,"Chưa nhập quyền lợi",0,"");
$myjob->add("job_profile","job_profile",0,0,'',0,"Chưa nhập hồ sơ",0,"");
$myjob->add("job_type","job_type",1,0,0,1,"Chưa chon loại công việc",0,"");
$myjob->add("job_experience","job_experience",1,0,0,1,"Chưa chọn kinh nghiệm",0,"");
$myjob->add("job_level","job_level",1,0,0,1,"Chưa chọn trình độ",0,"");
$myjob->add("job_gender","job_gender",1,0,0,1,"Chưa chọn giới tính",0,"");
$myjob->add("job_cat_all","job_cat_all",0,1,'',1,"Chưa nhập danh mục việc làm",0,"");
$myjob->add("job_cit_all","job_cit_all",0,1,'',1,"Chưa chọn tỉnh thành",0,"");
$myjob->add("job_contact_name","job_contact_name",0,0,'',1,"Chưa nhập tên người liên hệ",0,"");
$myjob->add("job_contact_phone","job_contact_phone",0,0,'',0,"Chưa nhập Số điện thoại liên hệ",0,"");
$myjob->add("job_contact_email","job_contact_email",0,0,'',0,"Chưa nhập email người liên hệ",0,"");
$myjob->add("job_packet_id","job_packet_id",1,0,0,0,"",0,"");
$myjob->add("job_packet_end","job_packet_end",1,1,0,0,"",0,"");
$myjob->add("job_tags","new_job_tags",0,1,'',0,"",0,"");
$myjob->addTable($table_job);



$myactive->add("job_date_expires","job_date_expires",1,1,'',0,"",0,"");
$myactive->add("job_name","job_name",0,0,'',1,"Chưa nhập tiêu đề việc làm!",0,"");
$myactive->addTable('job_active');

$myfilter->add("job_cit_id","job_cit_id",1,0,0,0,"",0,"");
$myfilter->add("job_cat_id","job_cat_id",1,0,0,0,"",0,"");
$myfilter->add("job_level","job_level",1,0,0,1,"Chưa chọn trình độ",0,"");
$myfilter->add("job_type","job_type",1,0,0,1,"Chưa chon loại công việc",0,"");
$myfilter->add("job_salary","job_salary",1,0,0,0,"",0,"");
$myfilter->add("job_experience","job_experience",1,0,0,1,"Chưa chọn kinh nghiệm",0,"");
$myfilter->add("job_gender","job_gender",1,0,0,1,"Chưa chọn giới tính",0,"");
$myfilter->add("job_date_expires","job_date_expires",1,1,0,0,"",0,"");
$myfilter->add("job_cat_1","job_cat_1",1,1,0,0,"",0,"");
$myfilter->add("job_cat_2","job_cat_2",1,1,0,0,"",0,"");
$myfilter->add("job_cit_1","job_cit_1",1,1,0,0,"",0,"");
$myfilter->add("job_cit_2","job_cit_2",1,1,0,0,"",0,"");
$myfilter->add("job_cit_3","job_cit_3",1,1,0,0,"",0,"");
$myfilter->addTable('job_filter');

$mycompany->add("com_name","com_name",0,0,"",1,"Chưa nhập tên công ty",0,"");
$mycompany->add("com_name_accent","com_name_accent",0,1,"",0,"",0,"");
$mycompany->add("com_address","com_address",0,0,"",1,"Chưa nhập địa chỉ công ty",0,"");
$mycompany->add("com_home_phone","com_home_phone",0,0,"",0,"",0,"");
$mycompany->add("com_mobile_phone","com_mobile_phone",0,0,"",1,"Chưa nhập điện thoại di động",0,"");
$mycompany->add("com_name_contact","com_name_contact",0,0,"",1,"Chưa nhập tên người liên hệ của công ty",0,"");
$mycompany->add("com_email","com_email",0,2,"",1,"Chưa nhập email người liên hệ của công ty",0,"");
$mycompany->addTable('company');

$mycompanydes->add("cd_com_id","cd_com_id",1,1,0,1,"Chưa nhập thông tin công ty",0,"");
$mycompanydes->add("cd_description","cd_description",0,0,'',1,"Chưa nhập thông tin chi tiết công ty",0,"");
$mycompanydes->addTable('company_description');


$action	= getValue("action", "str", "POST", "");
if($action == "execute"){
   
   $date_ex = getValue('job_date_expires', 'str', 'POST', date('d/m/Y', time() + (30 * 86400)));
   $packet_end = getValue('job_packet_end', 'str', 'POST', 0);
   
   $job_date_expires = convertDateTime($date_ex);
   $job_packet_end = convertDateTime($packet_end);
   //$use_id  = getValue('use_id', 'int', 'POST', 0);
   $company_id = getValue('company_id', 'int', 'POST', 0);
   $job_name   = getValue('job_name', 'str', 'POST', '');
   $job_name_accent = $vl_class->clean_keyword($job_name);
   $hot_time   = getValue('job_hot_time', 'str', 'POST', date('d/m/Y', time()+(10*86400)));
   $job_hot_time  = convertDateTime($hot_time);
   
   $supper_hot_time   = getValue('job_hot_time', 'str', 'POST', date('d/m/Y', time()+(10*86400)));
   $job_supper_hot_time  = convertDateTime($supper_hot_time);
   
   $cat  = getValue('cat', 'arr', 'POST', array());
   $cit  = getValue('cit', 'arr', 'POST', array());
   
   if(empty($cat)) $msgError .= "Bạn chưa chọn danh mục";
   if(empty($cit)) $msgError .= "Bạn chưa chọn tỉnh thành";
   
   $new_job_tags   = ''; 
   $tags = getValue('tag', 'arr', 'POST', array());
   $new_job_tags   = (!empty($tags))? implode(',', $tags) : '';
   
   $old_tags  = getValue('old_tags', 'str', 'POST', '');
   // nếu không có lỗi và qua bước thông tin người liên hệ
   if($msgError == ''){
      
      $job_cat_1  = isset($cat[0])? intval($cat[0]) : 0;
      $job_cat_2  = isset($cat[1])? intval($cat[1]) : 0;
      $job_cat_id = $job_cat_1;
      $arrayCat_select  = array_flip($cat);
      
      $job_cit_1  = isset($cit[0])? intval($cit[0]) : 0;
      $job_cit_2  = isset($cit[1])? intval($cit[1]) : 0;
      $job_cit_3  = isset($cit[2])? intval($cit[2]) : 0;
      $job_cit_id = $job_cit_1;
      $arrayCit_select  = array_flip($cit);
      
      $job_cat_all   = implode('|', $cat);
      $job_cit_all   = implode('|', $cit);
      
      if($company_id <= 0){
         $msgError .= $mycompany->checkdata();
         if($msgError == ''){
            
            // kiểm tra avatar
            if(isset($_FILES['file_avatar'])){
               $upload  = new upload('file_avatar', $file_path, 'jpg,png', 200, 1);
               $com_avatar  = $upload->file_name;
               if($upload->warning_error == ''){
                  $mycompany->add("com_avatar","com_avatar",0,1,"",1,"Chưa chọn ảnh đại diện công ty",0,"");
               }
            }
            
            $db_com  = new db_execute_return();
            $company_id = $db_com->db_execute($mycompany->generate_insert_SQL());
            unset($db_com);
            
            $cd_com_id  = $company_id;
            $db_comdes  = new db_execute($mycompanydes->generate_insert_SQL());
            unset($db_comdes);
         }
      }else{
         
         // kiểm tra avatar
         if(isset($_FILES['file_avatar'])){
            $upload  = new upload('file_avatar', $file_path, 'jpg,png', 200, 1);
            $com_avatar  = $upload->file_name;
            if($com_avatar!= ''){
               $mycompany->add("com_avatar","com_avatar",0,1,"",1,"Chưa chọn ảnh đại diện công ty",0,"");
            }
         }
         
         $db_com   = new db_execute($mycompany->generate_update_SQL('com_id', $company_id));
         unset($db_com);
         
         $cd_com_id  = $company_id;
         $db_comdes  = new db_execute($mycompanydes->generate_update_SQL('cd_com_id', $cd_com_id));
         unset($db_comdes);
      }
      
      // nếu qua bước tạo thông tin công ty
      if($msgError == '' && $company_id > 0){
         // check thông tin bảng job
         $msgError .= $myform->checkdata();
         $msgError .= $myfilter->checkdata();
         $msgError .= $myjob->checkdata();
         
         if($msgError == ''){
            
            if($old_tags != ''){
               $db_update_count  = new db_execute("UPDATE tags SET tag_count = tag_count - 1 WHERE tag_id IN (". $old_tags .")");
               unset($db_update_count);
               
                $db_del_tags   = new db_execute("DELETE FROM tags_detail WHERE tad_job_id = " . $record_id);
                unset($db_del_tags);   
            }
            
            // thêm tags
            if(!empty($tags)){
               
               foreach($tags as $k => $tag_id){
                  $db_tags = new db_execute("INSERT IGNORE INTO tags_detail (tad_id,tad_job_id)
                                             VALUES(". $tag_id .",". $record_id .")");
                  unset($db_tags);
                  
                  $db_tags_count = new db_execute("UPDATE tags SET tag_count = tag_count + 1 WHERE tag_id = " . $tag_id);
                  unset($db_tags_count);
               }
            }
            
            $db_job  = new db_execute($myform->generate_update_SQL('job_id', $record_id));
            unset($db_job);
            
            $db_myjob   = new db_execute($myjob->generate_update_SQL('job_id', $record_id));
            unset($db_myjob);
            
            $db_filter  = new db_execute($myfilter->generate_update_SQL('job_id', $record_id));
            unset($db_filter);
            
            $db_active  = new db_execute($myactive->generate_update_SQL('job_id', $record_id));
            unset($db_active);
            
            redirect('listing.php');
            exit();
         }
      }      
   }

}


$myform->evaluate();
$myfilter->evaluate();
$mycompany->evaluate();
$mycompanydes->evaluate();
$myjob->evaluate();


$mycat   = array();
$mycit   = array();
//SELECT thông tin của tin cần sửa
$arrayTag_default = array();

$db_job	=	new db_query("SELECT * FROM ". $table_job . "
								  WHERE job_id = " . $record_id . " LIMIT 1", __FILE__ . " Line: " . __LINE__);
if($row 		= mysqli_fetch_assoc($db_job->result)){
	foreach($row as $key=>$value){
		$$key = $value;
	}
   
   $mycat   = explode('|', $job_cat_all);
   $mycat   = array_flip($mycat);
   $arrayCat_select = $mycat;
   
   $mycit   = explode('|', $job_cit_all);
   $mycit   = array_flip($mycit);
   $arrayCit_select  = $mycit;
   
   $db_company = new db_query("SELECT * FROM company
                               INNER JOIN company_description ON cd_com_id = com_id
                               WHERE com_id = " . $row['job_company_id'] . " LIMIT 1");
   if($rcom = mysqli_fetch_assoc($db_company->result)){
      foreach($rcom as $key=>$value){
   		$$key = $value;
   	}
   }
   unset($db_company);
   
   if($job_tags != ''){
      $db_tags = new db_query("SELECT * FROM  tags WHERE tag_id IN (". $job_tags .")");
      while($rtag = mysqli_fetch_assoc($db_tags->result)){
         $arrayTag_default[$rtag['tag_id']] = $rtag['tag_name'];
      }
      unset($db_tags);
   }
}else{
   exit();
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$load_header?>
<style>
.item{
   background-color: #f9f9f9;
   list-style: outside none none;
   max-height: 200px;
   max-width: 800px;
   overflow-x: hidden;
   overflow-y: auto;
   padding: 10px;
}
.item li{
   float: left;
   width: 20%;
}
.hide{
   display: none;
}
ul.as-list {
   top: 10px;
   left: 5px !important;
}
#suggest_tags{
   position: relative;
}
#ovl_tags{
   position: fixed;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
   background-color: #000;
   opacity: 0.4;   
   z-index: 99;
}
#add_tags{
   position: fixed;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%; 
   z-index: 100;
   
}
#add_tags .content_tags{
   width: 300px;
   padding: 20px;
   border: 3px solid #999;
   background-color: #fff;
   margin: 200px auto;
}
#tags{
   
}
#tags p{
   display: inline-block;
   position: relative;
   margin-right: 10px;
   background-color: #eee;
   padding: 2px 5px;
   border-radius: 3px;
   color: #333;
}
#tags p span{
   position: absolute;
   right: 0;
   top: -12px;
   color: #999;
   background-color: #ccc;
   padding: 0 3px;
   border-radius: 3px 3px 0 0;
   cursor: pointer;
}
</style>
<link rel="stylesheet" href="../../../themes/css/autoSuggest.css" />
<script src="../../../themes/js/jquery.autoSuggest.js"></script>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top(translate_text("Edit jobs"))?>
   <p align="center" style="padding-left:10px;">
	<?
	$form = new form();
	$form->create_form("add", $_SERVER["REQUEST_URI"], "POST", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>
	<?=$form->text_note('Những ô dấu (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($msgError)?>	
   <tr><td colspan="1000"><b>Việc làm</b><hr /></td></tr>
	<?=$form->text("Tiêu đề", "job_name", "job_name", $job_name, "Tiêu đề", 1, 500, "", 255, "", "", "")?>
   
   <tr>
      <td class="form_name">Tags</td>
      <td class="form-control">
         <div id="suggest_tags">
            <input type="text" class="form_control" style="float: left;width: 500px" value="" placeholder="Tags bài viết" id="tags_search" />
            <input type="button" onclick="quick_tags()" value="Thêm tags" />
         </div>
         <div class="hide" id="ovl_tags"></div>
         <div id="add_tags" class="hide">
            <div class="content_tags">
               Tên tags:<br />
               <input type="text" value="" style="width: 300px" id="tag_name" /><br /><br />
               <input type="button" value="Thêm tags" onclick="add_tags()" />
               <input type="button" value="Thoát" onclick="close_tags()" />
            </div>
         </div>
      </td>
   </tr>
   <tr>
      <td class="formname"></td>
      <td class="form-control">
         <div id="tags">
            <?
            if(!empty($arrayTag_default)){
               foreach($arrayTag_default as $k => $tn){
                  ?>
                  <p class="item_tags"><?=$tn?> <span onclick="clear_tags(this)">x</span><input type="hidden" value="<?=$k?>" name="tag[]"/></p>
                  <?
               }
            }
            ?>
            <input type="hidden" value="<?=$job_tags?>" name="old_tags" />
         </div>
      </td>
   </tr>
   <?=$form->textarea("Mô tả công việc", "job_short_description", "job_short_description", $job_short_description, "Mô tả công việc", 1, 800, 100, "", "", "")?>
   <?=$form->textarea("Quyền lợi", "job_policy", "job_policy", $job_policy, "Quyền lợi", 1, 800, 100, "", "", "")?>
   <?=$form->textarea("Yêu cầu khác", "job_requirement", "job_requirement", $job_requirement, "Yêu cầu khác", 1, 800, 100, "", "", "")?>
   <?=$form->textarea("Hồ sơ", "job_profile", "job_profile", $job_profile, "Hồ sơ cần có", 0, 800, 100, "", "", "")?>
   <?=$form->text('Kỹ năng', 'job_skill', 'job_skill', $job_skill, 'Kỹ năng', 0, 800, '', 255, '', 'placeholder="VD: teamwork,nhanh nhẹn,vui tính,hòa đồng (cách nhau bằng dấu ,)"')?>
   <tr><td colspan="1000"><b>Thông tin thêm</b><hr /></td></tr>
   <tr>
      <td class="form_name">Gói tin:</td>
      <td>
         <select id="job_packet_id" name="job_packet_id">
            <option value="0">- Chọn gói tin - </option>
            <?
            foreach($array_packet as $k => $pc){
               $sl   = ($job_packet_id == $k)? 'selected' : '';
               ?>
               <option <?=$sl?> value="<?=$k?>"><?=$pc['name']?></option>
               <?
            }
            ?>
         </select> ->đến ngày 
         <input type="text" value="<?=($job_packet_end > 0)?  date('d/m/Y', $job_packet_end) : (($job_date_expires > 0)? date('d/m/Y', $job_date_expires) : '')?>" onblur="if(this.value=='') this.value='Enter date'" onfocus="if(this.value=='Enter date') this.value=''" onclick="displayDatePicker('job_packet_end', this);" class="form_control" onkeypress="displayDatePicker('job_packet_end', this);" style="width:200px;" id="job_packet_end" name="job_packet_end" class="textbox">
      </td>
   </tr>
   <?=$form->text("Số lượng", "job_number", "job_number", $job_number, "Số lượng", 1, 100, "", 3, "", "", "")?>
   <?=$form->select('Cấp bậc', 'job_rank', 'job_rank', $arrayRank, $job_rank, 'Cấp bậc', 1, 200, '',0)?>
   <tr>
      <td class="form_name"><font class="form_asterisk">* </font>Tỉnh thành:</td>
      <td class="form_text">
         <ul class="item">
            <?
            
            foreach($arrayCity as $cit_id => $cit_name){
               if($cit_id == 0) continue;
               $ch   = isset($mycit[$cit_id])? 'checked' : '';
               $sl   = isset($arrayCit_select[$cit_id])? 'checked="checked"' : '';
               ?>
               <li>
                  <input class="arr_cit" type="checkbox" <?=$sl?> value="<?=$cit_id?>" id="cit_<?=$cit_id?>" name="cit[]" />
                  <label for="cit_<?=$cit_id?>"><?=$cit_name?></label>
               </li>
               <?
            }
            ?>
         </ul>
      </td>
   </tr>
   <tr>
      <td class="form_name"><font class="form_asterisk">* </font>Danh mục:</td>
      <td class="form_text">
         <ul class="item">
            <?
            foreach($arrayCategory as $cat_id => $cat_name){
               if($cat_id == 0) continue;
               $ch   = isset($mycat[$cat_id])? 'checked' : '';
               $sl   = isset($arrayCat_select[$cat_id])? 'checked="checked"' : '';
               ?>
               <li>
                  <input class="arr_cat" type="checkbox" <?=$sl?> value="<?=$cat_id?>" id="cat_<?=$cat_id?>" name="cat[]" />
                  <label for="cat_<?=$cat_id?>"><?=$cat_name?></label>
               </li>
               <?
            }
            ?>
         </ul>
      </td>
   </tr>
   <?=$form->select('Trình độ', 'job_level', 'job_level', $arrayLevel, $job_level, 'Trình độ', 1, 200)?>
   <?=$form->select('Loại công việc', 'job_type', 'job_type', $arrayJobType, $job_type, 'Loại công việc', 1, 200)?>
   <?=$form->select('Mức lương', 'job_salary', 'job_salary', $arraySalary, $job_salary, 'Mức lương', 1, 200)?>
   <?=$form->select('Kinh nghiệm', 'job_experience', 'job_experience', $arrayExperience, $job_experience, 'Kinh nghiệm', 1, 200)?>
   <?=$form->select('Giới tính', 'job_gender', 'job_gender', $arrayGender, $job_gender, 'Giới tính', 1, 200)?>
   <tr>
      <td class="form_name">Ngày hết hạn:</td>
      <td class="form_text">
         <input type="text" value="<?=($job_date_expires > 0)? date('d/m/Y', $job_date_expires) : ''?>" onblur="if(this.value=='') this.value='Enter date'" onfocus="if(this.value=='Enter date') this.value=''" onclick="displayDatePicker('job_date_expires', this);" class="form_control" onkeypress="displayDatePicker('job_date_expires', this);" style="width:200px;" id="job_date_expires" name="job_date_expires" class="textbox">
      </td>
   </tr>
   <tr><td colspan="1000"><b>Liện hệ</b><hr /></td></tr>
   <?=$form->text("Người liên hệ", "job_contact_name", "job_contact_name", $job_contact_name, "Người liên hệ", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Số ĐT", "job_contact_phone", "job_contact_phone", $job_contact_phone, "Số đt", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Email", "job_contact_email", "job_contact_email", $job_contact_email, "Email liên hệ", 1, 250, "", 255, "", "", "")?>
   
   <tr><td colspan="1000"><b>Nhà tuyển dụng</b><hr /></td></tr>
   <tr>
      <td class="form_name"><font class="form_asterisk">* </font>Tên công ty:</td>
      <td class="form_text">
         <div style="position: relative;">
            <input type="text" class="form_control" style="width:500px" value="<?=$com_name?>" name="com_name" id="com_name" />
         </div>
      </td>
   </tr>
   <tr>
      <td class="form_name">Ảnh đại diện</td>
      <td>
         <img src="<?=$file_path . $com_avatar?>" /><br />
         <input type="file" value="" name="file_avatar" />
      </td>
   </tr>
   <?=$form->text("Địa chỉ", "com_address", "com_address", $com_address, "Địa chỉ", 1, 500, "", 255, "", "", "")?>
   <?=$form->text("Người liên hệ", "com_name_contact", "com_name_contact", $com_name_contact, "Người liên hệ", 1, 500, "", 255, "", "", "")?>
   <?=$form->text("Số ĐT", "com_mobile_phone", "com_mobile_phone", $com_mobile_phone, "Số đt", 1, 500, "", 30, "", "", "")?>
   <?=$form->text("Máy bàn", "com_home_phone", "com_home_phone", $com_home_phone, "Số đt", 0, 500, "", 30, "", "", "")?>
   <?=$form->text("Email", "com_email", "com_email", $com_email, "Email", 1, 250, "", 255, "", "", "")?>
   <?=$form->textarea("Chi tiế công ty", "cd_description", "cd_description", $cd_description, "Mô tả", 0, 500, 100, "", "", "")?>
   <tr>
      <td colspan="2"><input type="hidden" value="<?=($company_id > 0)? $company_id : $com_id?>" name="company_id" id="company_id" /></td>
   </tr>
   
	<?=$form->button("submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "submit" . $form->ec . "reset", "Cập nhật" . $form->ec . "Làm lại", "Cập nhật" . $form->ec . "Làm lại", 'style="background:url(' . $fs_imagepath . 'button_1.gif) no-repeat"' . $form->ec . 'style="background:url(' . $fs_imagepath . 'button_2.gif)"', "");?>
	<?=$form->hidden("action", "action", "execute", "");?>
	<?
	$form->close_table();
	$form->close_form();
	unset($form);
	?>
	</p>
 <?=template_bottom() ?>
 <script>
   function setAuto_suggest(id, default_text,param_q, name_get){
      $("#"+id).autoSuggest('search_suggest.php', {
     	   minChars : 1,
      	selectionLimit : 1,
      	selectedItemProp : 'name',
      	searchObjProps : 'name',
      	startText: default_text,
      	retrieveLimit : 15,
      	defaultData:'',
         queryParam : param_q,
         neverSubmit : false,
      	formatList : function(data, el) {
      		var html = formatResults(data);
      		el.html(html);
      		$('.as-list').append(el);
      	},
      	retrieveComplete: function(data) {
      		return data.result;
      	},
      	dataSelected: function(data){
            $('#'+name_get).val(data.id);
            $('.as-results').html('');
      	}
      });
   }
   
   function setAuto_suggest_tags(id, default_text,param_q, name_get){
      $("#"+id).autoSuggest('search_suggest_tags.php', {
     	   minChars : 1,
      	selectionLimit : 1,
      	selectedItemProp : 'name',
      	searchObjProps : 'name',
      	startText: default_text,
      	retrieveLimit : 15,
      	defaultData:'',
         queryParam : param_q,
         neverSubmit : false,
      	formatList : function(data, el) {
      		var html = formatResults(data);
      		el.html(html);
      		$('.as-list').append(el);
      	},
      	retrieveComplete: function(data) {
      		return data.result;
      	},
      	dataSelected: function(data){
            var htm = '<p class="item_tags">'+data.name+' <span onclick="clear_tags(this)">x</span><input type="hidden" value="'+data.id+'" name="tag[]"/></p>';
            $('#tags').append(htm);
            $('.as-results').html('');
            $('#suggest_tags').find('input[id^=as-input]').val('');
      	}
      });
   }
   
   $(function(){
      setAuto_suggest('com_name', '<?=$com_name?>','cname', 'company_id');
      setAuto_suggest_tags('tags_search', '','tags', 'tags_id');
      
      $('.arr_cat').click(function(){
         var tt = 0;
         $('.arr_cat').each(function(){
            if($(this).is(':checked')) tt++;
         })
         
         if(tt > 2){
            alert('Vui lòng chọn tối đa 2 danh mục');
            $(this).attr('checked', false);
         }
      })
      
      $('.arr_cit').click(function(){
         var tt = 0;
         $('.arr_cit').each(function(){
            if($(this).is(':checked')) tt++;
         })
         
         if(tt > 3){
            alert('Vui lòng chọn tối đa 3 tỉnh thành');
            $(this).attr('checked', false);
         }
      })
      
      
      $('#tag_name').keypress(function(e){
         var code = e.keyCode || e.which;
         if(code == 13){
            add_tags();
            return false;
         } 
      })
   });
   
   function quick_tags(){
      $('#ovl_tags').removeClass('hide');
      $('#add_tags').removeClass('hide');
      $('#tag_name').focus().val('');
   }
   
   function close_tags(){
      $('#ovl_tags').addClass('hide');
      $('#add_tags').addClass('hide');
   }
   
   function add_tags(){
      var tag_name = $('#tag_name').val() || '';
      if(tag_name != ''){
         $.post(
            'add_tags.php',
            {tag_name : tag_name},
            function (res){
               var htm = '<p class="item_tags">'+res.tag_name+' <span onclick="clear_tags(this)">x</span><input type="hidden" value="'+res.tag_id+'" name="tag[]"/></p>';
               $('#tags').append(htm);
               $('#tag_name').val('');
            },
            'json'
         );
      }else{
         alert('Bạn chưa nhập tags');
         return false;
      }
   }
   
   function clear_tags(obj){
      $(obj).parents('.item_tags').remove();
   }
   
   
</script>
</body>
</html>

