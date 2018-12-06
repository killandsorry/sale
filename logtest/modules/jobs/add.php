<?
require_once("inc_security.php");
//check quyền them sua xoa
//checkAddEdit("add");

//Khai bao Bien
$fs_redirect		= "add.php";
$after_save_data	= getValue("after_save_data", "str", "POST", "add.php");

$use_phone  = getValue('use_phone', 'str', 'POST', '');
$use_email  = getValue('use_email', 'str', 'POST', '');
$use_name  = getValue('use_name', 'str', 'POST', '');

$job_date_create  = time(); 
$job_active       = 1;  
$use_id           = 0; 
$company_id       = 0;  
$web_id           = getValue('web_id', 'int', 'POST', '');
$crawl_id         = getValue('crawl_id', 'int', 'POST', '');



$myform 				= new generate_form();
$mydes            = new generate_form();
$myfilter         = new generate_form();
$mycompany        = new generate_form();
$mycompanydes     = new generate_form();
$myactive         = new generate_form();

$myform->add("job_name","job_name",0,0,'',1,"Chưa nhập tiêu đề việc làm",0,"");
$myform->add("job_name_accent","job_name_accent",0,1,'',0,"",0,"");
$myform->add("job_skill","job_skill",0,0,'',0,"",0,"");
$myform->add("job_rank","job_rank",1,0,0,1,"Bạn chưa chọn cấp bậc",0,"");
$myform->add("job_company_name","com_name",0,0,'', 1,'Chưa nhập tên công ty',0,"",0,"");
$myform->add("job_date_create","job_date_create",1,1,0,0,"",0,"");
$myform->add("job_date_expires","job_date_expires",1,1,0,0,"",0,"");
$myform->add("job_user_id","use_id",1,1,0,0,"",0,"");
$myform->add("job_cat_id","job_cat_id",1,1,0,1,"Chưa nhập danh mục việc làm",0,"");
$myform->add("job_cit_id","job_cit_id",1,1,0,1,"Chưa chọn tỉnh thành",0,"");
$myform->add("job_number","job_number",1,0,1,1,"Chưa số người tuyển dụng",0,"");
$myform->add("job_company_id","company_id",1,1,0,1,"Chưa nhập thông tin công ty",0,"");
$myform->add("job_salary","job_salary",1,0,0,1,"Chưa chọn mức lương",0,"");
$myform->add("job_type","job_type",1,0,0,1,"Chưa chọn loại hình công việc",0,"");
$myform->add("job_active","job_active",1,1,0,0,"",0,"");
$myform->add("job_cat_1","job_cat_1",1,1,0,0,"",0,"");
$myform->add("job_cat_2","job_cat_2",1,1,0,0,"",0,"");
$myform->add("job_cit_1","job_cit_1",1,1,0,0,"",0,"");
$myform->add("job_cit_2","job_cit_2",1,1,0,0,"",0,"");
$myform->add("job_cit_3","job_cit_3",1,1,0,0,"",0,"");
$myform->add("job_contact_name","job_contact_name",0,0,'',1,"Chưa nhập tên người liên hệ",0,"");
$myform->add("job_contact_phone","job_contact_phone",0,0,'',0,"Chưa nhập Số điện thoại liên hệ",0,"");
$myform->add("job_contact_email","job_contact_email",0,0,'',1,"Chưa nhập email người liên hệ",0,"");
$myform->addTable('job');


$myactive->add("job_id","job_id",1,1,'',0,"",0,"");
$myactive->add("job_date_create","job_date_create",1,1,'',0,"",0,"");
$myactive->add("job_date_expires","job_date_expires",1,1,'',0,"",0,"");
$myactive->add("job_user_id","use_id",1,1,'',0,"",0,"");
$myactive->add("job_active","job_active",1,1,'',0,"",0,"");
$myactive->add("job_name","job_name",0,0,'',1,"Chưa nhập tiêu đề việc làm!",0,"");
$myactive->addTable('job_active');


$mydes->add("jobd_id","job_id",1,1,'',0,"",0,"");
$mydes->add("jobd_short_description","jobd_short_description",0,0,'',1,"Chưa nhập mô tả ngắn",0,"");
$mydes->add("jobd_requirement","jobd_requirement",0,0,'',1,"Chưa nhập yêu cầu khác",0,"");
$mydes->add("jobd_policy","jobd_policy",0,0,'',1,"Chưa nhập quyền lợi",0,"");
$mydes->add("jobd_profile","jobd_profile",0,0,'',0,"",0,"");
$mydes->addTable('jobs_description');


$myfilter->add("job_id","job_id",1,1,0,0,"",0,"");
$myfilter->add("job_cit_id","job_cit_id",1,0,0,0,"",0,"");
$myfilter->add("job_cat_id","job_cat_id",1,0,0,0,"",0,"");
$myfilter->add("job_level","job_level",1,0,0,1,"Chưa chọn trình độ",0,"");
$myfilter->add("job_type","job_type",1,0,0,1,"Chưa chon loại công việc",0,"");
$myfilter->add("job_salary","job_salary",1,0,0,0,"",0,"");
$myfilter->add("job_experience","job_experience",1,0,0,1,"Chưa chọn kinh nghiệm",0,"");
$myfilter->add("job_gender","job_gender",1,0,0,1,"Chưa chọn giới tính",0,"");
$myfilter->add("job_active","job_active",1,1,0,0,"",0,"");
$myfilter->add("job_date_create","job_date_create",1,1,0,0,"",0,"");
$myfilter->add("job_date_expires","job_date_expires",1,1,0,0,"",0,"");
$myfilter->add("job_cat_1","job_cat_1",1,1,0,0,"",0,"");
$myfilter->add("job_cat_2","job_cat_2",1,1,0,0,"",0,"");
$myfilter->add("job_cit_1","job_cit_1",1,1,0,0,"",0,"");
$myfilter->add("job_cit_2","job_cit_2",1,1,0,0,"",0,"");
$myfilter->add("job_cit_3","job_cit_3",1,1,0,0,"",0,"");
$myfilter->addTable('job_filter');

$mycompany->add("com_name","com_name",0,0,"",1,"Chưa nhập tên công ty",0,"");
$mycompany->add("com_name_accent","com_name_accent",0,1,"",0,"",0,"");
$mycompany->add("com_address","com_address",0,0,"",0,"Chưa nhập địa chỉ công ty",0,"");
$mycompany->add("com_home_phone","com_home_phone",0,0,"",0,"",0,"");
$mycompany->add("com_mobile_phone","com_mobile_phone",0,0,"",0,"Chưa nhập điện thoại di động",0,"");
$mycompany->add("com_name_contact","com_name_contact",0,0,"",0,"Chưa nhập tên người liên hệ của công ty",0,"");
$mycompany->add("com_email","com_email",2,0,"",0,"Chưa nhập email người liên hệ của công ty",0,"");
$mycompany->addTable('company');

$mycompanydes->add("cd_com_id","cd_com_id",1,1,0,0,"",0,"");
$mycompanydes->add("cd_description","cd_description",0,0,'',1,"Chưa nhập thông tin chi tiết công ty",0,"");
$mycompanydes->addTable('company_description');


$arrayCat_select  = array();
$arrayCit_select  = array();

$action	= getValue("action", "str", "POST", "");
if($action == "execute"){
   
   $date_ex = getValue('job_date_expires', 'str', 'POST', date('d/m/Y', time() + (30 * 86400)));
   $job_date_expires = convertDateTime($date_ex);
   $use_id  = getValue('use_id', 'int', 'POST', 0);
   $company_id = getValue('company_id', 'int', 'POST', 0);
   $job_name   = getValue('job_name', 'str', 'POST', '');
   $job_name_accent = $vl_class->clean_keyword($job_name);
   
   
   if($use_id <= 0){
      $use_name   = trim(getValue('job_contact_name', 'str', 'POST', ''));
      $use_phone   = trim(getValue('job_contact_phone', 'str', 'POST', ''));
      $use_email   = trim(getValue('job_contact_email', 'str', 'POST', ''));
      $com_name   = trim(getValue('com_name', 'str', 'POST', ''));
      
      if($use_name == '' || $use_phone == "" || $use_email == ''){
         $msgError .= '* Bạn chưa nhập thông tin người liên hệ <br>';
      }else{
         $screst		= rand(11111,99999);
			$password 	= md5(rand(11111,99999) . $screst);
         $db_ex   = new db_execute_return();
         /*echo "INSERT IGNORE INTO user_employer(use_name,use_email,use_password,use_security,use_active,use_phone)
											 			 VALUES('" . replaceMQ($use_name) . "','". replaceMQ($use_email) ."','" . replaceMQ($password) . "','". replaceMQ($screst) ."',0,'". replaceMQ($use_phone) ."')";
         
         */
         
         // kiểm tra email có chưa, có rooif thìtrar ra iD
         $db_check_user = new db_query("SELECT * FROM user WHERE use_email = '". replaceMQ($use_email) ."' LIMIT 1");
         if($rcheck  = mysqli_fetch_assoc($db_check_user->result)){
            $use_id  = $rcheck['use_id'];
         }else{
            $use_id		= $db_ex->db_execute("INSERT IGNORE INTO user(use_name,use_email,use_password,use_secrest,use_active,use_phone,use_type)
   											 			 VALUES('" . replaceMQ($use_name) . "','". replaceMQ($use_email) ."','" . replaceMQ($password) . "','". replaceMQ($screst) ."',0,'". replaceMQ($use_phone) ."',1)");
            unset($db_ex);
         }
         unset($db_check_user);
            
         
         
         //if($use_id <= 0) $msgError .= '* Không tạo được thông tin User <br>';
      }
   }
   
   $cat  = getValue('cat', 'arr', 'POST', array());
   $cit  = getValue('cit', 'arr', 'POST', array());
   
   if(empty($cat)) $msgError .= "Bạn chưa chọn danh mục";
   if(empty($cit)) $msgError .= "Bạn chưa chọn tỉnh thành";
   
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
            
            $com_name_accent  = $vl_class->clean_keyword($com_name);
            $db_com  = new db_execute_return();
            $company_id = $db_com->db_execute($mycompany->generate_insert_SQL());            
            unset($db_com);
            
            $cd_com_id  = $company_id;
            $db_comdes  = new db_execute($mycompanydes->generate_insert_SQL());
            unset($db_comdes);
         }
      }
      
      // nếu qua bước tạo thông tin công ty
      if($msgError == '' && $company_id > 0){
         // check thông tin bảng job
         $msgError .= $myform->checkdata();
         $msgError .= $myfilter->checkdata();
         $msgError .= $mydes->checkdata();
         
         if($msgError == ''){
            $db_job  = new db_execute_return();
            $job_id  = $db_job->db_execute($myform->generate_insert_SQL());
            if($job_id  > 0){
               $db_filter  = new db_execute($myfilter->generate_insert_SQL());
               unset($db_filter);
               
               $db_des  = new db_execute($mydes->generate_insert_SQL());
               unset($db_des);
               
               $db_active  = new db_execute($myactive->generate_insert_SQL());
               unset($db_active);
               
               // convert job to table optimize
               $data = array(
                  'jid' => $job_id,
                  'cat_all' => $job_cat_all,
                  'cit_all' => $job_cit_all
               );               
               $vl_class->convert_to_table_optimize($data);
               
               
               // nếu thêm thành công và có biến check crawl thì thêm
               if($web_id > 0 && $crawl_id > 0){
                  $db_ex   = new db_execute("INSERT IGNORE INTO check_crawl (ccr_web_id, ccr_crawl_id)
                                             VALUES(". intval($web_id) .",". intval($crawl_id) .")");
                  unset($db_ex);
               }
               
               redirect('listing.php');
		         exit();
            }
         }
      }      
   }

}
//add form for javacheck
$myform->addFormname("add_new");
$myform->evaluate();
$mydes->evaluate();
$myfilter->evaluate();
$mycompany->evaluate();
$mycompanydes->evaluate();

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
.error{
   font-weight: bold;
   color: #f00;
}
</style>
<link rel="stylesheet" href="../../../themes/css/autoSuggest.css" />
<script src="../../../themes/js/jquery.autoSuggest.js"></script>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*------------------------------------------------------------------------------------------------*/ ?>
<?=template_top(("Add_new_category"))?>
<? /*------------------------------------------------------------------------------------------------*/ ?>
	<p align="center" style="padding-left:10px;">
	<?
	$form = new form();
	$form->create_form("add", $_SERVER["REQUEST_URI"], "POST", "multipart/form-data",'onsubmit="validateForm(); return false;"');
	$form->create_table();
	?>
   <tr>
      <td class="form_name">Kiểm tra Url lấy tin</td>
      <td>
         <p class="error" id="error_check"></p>
         <h3 class="" style="color:#27C02C" id="success_check"></h3>
         <input style="width: 500px" type="text" value="" id="url_crawler" /> <input type="button" value="Kiểm tra" onclick="check_url_crawler(this)" />
         <input type="hidden" id="web_id" name="web_id" value="0"/>
         <input type="hidden" id="crawl_id" name="crawl_id" value="0"/>
      </td>
   </tr>
	<?=$form->text_note('Những ô dấu (<font class="form_asterisk">*</font>) là bắt buộc phải nhập.')?>
	<?=$form->errorMsg($msgError)?>	
   <tr><td colspan="1000"><b>Việc làm</b><hr /></td></tr>
	<?=$form->text("Tiêu đề", "job_name", "job_name", $job_name, "Tiêu đề", 1, 500, "", 255, "", "", "")?>
   <?=$form->textarea("Mô tả công việc", "jobd_short_description", "jobd_short_description", $jobd_short_description, "Mô tả công việc", 1, 800, 100, "", "", "")?>
   <?=$form->textarea("Quyền lợi", "jobd_policy", "jobd_policy", $jobd_policy, "Quyền lợi", 1, 800, 100, "", "", "")?>
   <?=$form->textarea("Yêu cầu khác", "jobd_requirement", "jobd_requirement", $jobd_requirement, "Yêu cầu khác", 1, 800, 100, "", "", "")?>
   <?=$form->textarea("Hồ sơ", "jobd_profile", "jobd_profile", $jobd_profile, "Hồ sơ cần có", 0, 800, 100, "", "", "")?>   
   <?=$form->text('Kỹ năng', 'job_skill', 'job_skill', $job_skill, 'Kỹ năng', 0, 800, '', 255, '', 'placeholder="VD: teamwork,nhanh nhẹn,vui tính,hòa đồng (cách nhau bằng dấu ,)"')?>
   <tr><td colspan="1000"><b>Thông tin thêm</b><hr /></td></tr>
   <?=$form->text("Số lượng", "job_number", "job_number", $job_number, "Số lượng", 1, 100, "", 3, "", "", "")?>
   <?=$form->select('Cấp bậc', 'job_rank', 'job_rank', $arrayRank, $job_rank, 'Cấp bậc', 1, 200, '',0)?>
   <tr>
      <td class="form_name"><font class="form_asterisk">* </font>Tỉnh thành:</td>
      <td class="form_text">
         <ul class="item">
            <?
            //print_r($arrayCit_select);
            foreach($arrayCity as $cit_id => $cit_name){
               if($cit_id == 0) continue;
               $sl   = isset($arrayCit_select[$cit_id])? 'checked="checked"' : '';
               ?>
               <li>
                  <input class="arr_cit" <?=$sl?> type="checkbox" value="<?=$cit_id?>" id="cit_<?=$cit_id?>" name="cit[]" />
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
            //print_r($arrayCat_select);
            foreach($arrayCategory as $cat_id => $cat_name){
               if($cat_id == 0) continue;
               $sl   = isset($arrayCat_select[$cat_id])? 'checked="checked"' : '';
               ?>
               <li>
                  <input class="arr_cat" <?=$sl?> type="checkbox" value="<?=$cat_id?>" id="cat_<?=$cat_id?>" name="cat[]" />
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
         <input type="text" value="<?=($job_date_expires > 0)? date('d/m/Y', $job_date_expires) : date('d/m/Y', (time() + 30*86400))?>" onblur="if(this.value=='') this.value='Enter date'" onfocus="if(this.value=='Enter date') this.value=''" onclick="displayDatePicker('job_date_expires', this);" class="form_control" onkeypress="displayDatePicker('job_date_expires', this);" style="width:200px;" id="job_date_expires" name="job_date_expires" class="textbox">
      </td>
   </tr>
   
   <tr><td colspan="1000"><b>Liện hệ</b><hr /></td></tr>
   <tr>
      <td class="form_name"><font class="form_asterisk">* </font>Email:</td>
      <td class="form_text">         
         <div style="position: relative;">
            <input type="text" onclick="setAuto_suggest('job_contact_email', '<?=$job_contact_email?>','uemail', 'use_id');" class="form_control" style="width:500px" value="<?=$job_contact_email?>" name="job_contact_email" id="job_contact_email" />
         </div>
         <input type="hidden" value="<?=$use_id?>" name="use_id" id="use_id" />
      </td>
   </tr>
   <?=$form->text("Người liên hệ", "job_contact_name", "job_contact_name", $job_contact_name, "Người liên hệ", 1, 250, "", 255, "", "", "")?>
   <?=$form->text("Số ĐT", "job_contact_phone", "job_contact_phone", $job_contact_phone, "Số đt", 1, 250, "", 255, "", "", "")?>
   
   <?//=$form->text("Email", "job_contact_email", "job_contact_email", $job_contact_email, "Email liên hệ", 1, 250, "", 255, "", "", "")?>
   
   <tr><td colspan="1000"><b>Nhà tuyển dụng</b><hr /></td></tr>
   <tr>
      <td class="form_name"><font class="form_asterisk">* </font>Tên công ty:</td>
      <td class="form_text">         
         <div style="position: relative;">
            <input type="text" onclick="setAuto_suggest('com_name', '<?=$com_name?>','cname', 'company_id');" class="form_control" style="width:500px" value="<?=$com_name?>" name="com_name" id="com_name" />
         </div>
      </td>
   </tr>
   <tr>
      <td class="form_name"><font class="form_asterisk">* </font>Avatar:</td>
      <td class="form_text"><input type="file" value="" name="file_avatar" /></td>
   </tr>
   <?=$form->text("Địa chỉ", "com_address", "com_address", $com_address, "Địa chỉ", 1, 500, "", 255, "", "", "")?>
   <?=$form->text("Người liên hệ", "com_name_contact", "com_name_contact", $com_name_contact, "Người liên hệ", 1, 500, "", 255, "", "", "")?>
   <?=$form->text("Số ĐT", "com_mobile_phone", "com_mobile_phone", $com_mobile_phone, "Số đt", 1, 500, "", 30, "", "", "")?>
   <?=$form->text("Máy bàn", "com_home_phone", "com_home_phone", $com_home_phone, "Số đt", 0, 500, "", 30, "", "", "")?>
   <?=$form->text("Email", "com_email", "com_email", $com_email, "Email", 1, 250, "", 255, "", "", "")?>
   <?=$form->textarea("Chi tiế công ty", "cd_description", "cd_description", $cd_description, "Mô tả", 0, 500, 100, "", "", "")?>
   <tr>
      <td colspan="2"><input type="hidden" value="<?=$company_id?>" name="company_id" id="company_id" /></td>
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
<? /*------------------------------------------------------------------------------------------------*/ ?>
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
   
   $(function(){
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
   })
   
   function check_url_crawler(obj){
      var lik  = $('#url_crawler').val() || '';
      if(lik.trim() == ''){
         $('#error_check').text('Bạn chưa nhập lịnk crawler');
         return false;
      }else{
         
         $(obj).val('Đang kiểm tra');
         $.post(
            'check_url_crawler.php',
            {
               url : lik
            },
            function(res){
               if(res.status == 0){
                  $('#error_check').text(res.error);
                  alert('Tin này đã có trong hệ thống');
                  $('#success_check').text('')
                  return false;
               }else{
                  $('#error_check').text('');
                  $('#success_check').text('Tin này được phép lấy');
                  $('#web_id').val(res.web_id);
                  $('#crawl_id').val(res.crawl_id);
                  
                  $('#job_name').val(res.data['title']);
                  $('#jobd_short_description').val(res.data['short_des']);
                  $('#jobd_policy').val(res.data['policy']);
                  $('#jobd_requirement').val(res.data['require']);
                  $('#jobd_profile').val(res.data['profile']);
                  $('#job_number').val(res.data['number']);
                  $('#job_level').val(res.data['level']);
                  $('#job_type').val(res.data['job_type']);
                  $('#job_salary').val(res.data['salary']);
                  $('#job_experience').val(res.data['exper']);
                  $('#job_gender').val(res.data['gender']);
                  $('#job_date_expires').val(res.data['expires']);
                  
                  $('#job_contact_email').val(res.data['email']);
                  $('#job_contact_name').val(res.data['name']);
                  $('#job_contact_phone').val(res.data['phone']);
                  
                  $('#com_email').val(res.data['email']);
                  $('#com_name_contact').val(res.data['name']);
                  $('#com_mobile_phone').val(res.data['phone']);
                  $('#com_address').val(res.data['add']);
                  
               }
               
               $(obj).val('Kiểm tra');
            },
            'json'
         );
      }
   }
</script>
</body>
</html>