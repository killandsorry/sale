<?
/**
 * export cv to pdf
 */

/**
 * 
 * Function generate html pdf
 * 
 * param:
 * array(
 *    temp  => 1 - 100
 *    id => id user
 * )
 * 
 * return html
 */
 
function generatehtml_cv($data = array()){
   
   global $webname, $path_img_cv;
   
   $file_temp  = isset($data['temp'])? intval($data['temp']) : 1;
   $uid        = isset($data['id'])? intval($data['id']) : 0;
   $array_title   = isset($data['title'])? ($data['title']) : array();
   $arrayField_active   = isset($data['field_active'])? ($data['field_active']) : array();
   $font          = isset($data['font'])? ($data['font']) : 'FreeSans';
   $mycv_id       = isset($data['mycv_id'])? ($data['mycv_id']) : '';
   $protect       = isset($data['protect'])? ($data['protect']) : 0;
   $lang          = isset($data['lang'])? $data['lang'] : 'vi';
   
   
   
   $html = '';
   if($uid <= 0 || $uid > 10000000 || $mycv_id == '') return $html;
   if(!file_exists('../cv_template/cv_tem_' . $file_temp . '.html')) return $html;
   
   
   // lấy thông tin cơ bản cần thiết
   $array_school = array();
   $array_company = array();
   $array_project = array();
   $array_certificate = array();
   $array_target = '';
   $array_more_info  = '';
   $array_gusto = '';
   $array_price = array();
   $array_skill = array();
   $array_user  = array();
   
   $html_school   = '';
   $html_company   = '';
   $html_certificate   = '';
   $html_price   = '';
   $html_target   = '';
   $html_skill   = '';
   $html_gusto   = '';
   
   $width_avatar = 150;
   switch($file_temp){
      case 17: $width_avatar = 120;
      break;
      case 16: $width_avatar = 120;
      break;
      case 14: $width_avatar = 120;
      break;
      case 12: $width_avatar = 120;
      break;
      case 22: $width_avatar = 120;
      break;
      case 23: $width_avatar = 100;
      break;
      case 24: $width_avatar = 160;
      break;
      case 30: $width_avatar = 130;
      break;
      case 31: $width_avatar = 120;
      break;
      case 33: $width_avatar = 120;
      break;
      case 34: $width_avatar = 120;
      break;
      case 36: $width_avatar = 120;
      break;
      case 37: $width_avatar = 120;
      break;
      case 38: $width_avatar = 120;
      break;
      case 42: $width_avatar = 120;
      break;
   }
   
   $db_user = new db_query("SELECT * FROM user WHERE use_id = " . intval($uid) . " LIMIT 1");
   if($ruser   = mysqli_fetch_assoc($db_user->result)){
      
      
      
      if($ruser['use_birthday'] > 0) $ruser['use_birthday'] = date('d/m/Y', $ruser['use_birthday']);
      switch($lang){
         case 'vi':
            $ruser['use_gender'] = ($ruser['use_gender'] == 0)? "Nữ" : 'Nam';
            break;
         case 'en':
            $ruser['use_gender'] = ($ruser['use_gender'] == 0)? "Female" : 'Male';
            break;
         case 'jp':
            $ruser['use_gender'] = ($ruser['use_gender'] == 0)? "Female" : 'Male';
            break;
      }
      
      if($protect == 1){
         $ruser['use_phone'] = '[Protect]';
         $ruser['use_email_notifi'] = '[Protect]';
         $ruser['use_email'] = '[Protect]';
      }
      $ruser['avatar'] = ($ruser['use_avatar_cv'] != '')? '<img style="width:'. $width_avatar .'px" src="http://1viec.com/upload/use/' . $ruser['use_avatar_cv'] .'" />' : '';
      //$ruser['avatar'] = ($ruser['use_avatar_cv'] != '')? '<img style="width: 160px;" src="https://lh3.googleusercontent.com/-EXDRd7RmL58/AAAAAAAAAAI/AAAAAAAAAAA/yZszBUOu60o/photo.jpg" />' : '';
      $ruser['use_email'] = ($ruser['use_email_notifi'] != '')? $ruser['use_email_notifi'] : $ruser['use_email']; 
      $array_user = $ruser;
   }else{
      return $html;
   }
   unset($db_user);
   
   
   // School
   $db_school = new db_query("SELECT * FROM user_cv_school WHERE ucs_use_id = " . $uid . " AND ucs_key = '". replaceMQ($mycv_id) ."' ORDER BY ucs_order ASC");
   while($rschool = mysqli_fetch_assoc($db_school->result)){
      $array_school[$rschool['ucs_order']] = $rschool;
   }
   unset($db_school);
   
   
   // Certificate
   $db_certificate = new db_query("SELECT * FROM user_cv_certificate WHERE ucc_use_id = " . $uid . " AND ucc_key = '". replaceMQ($mycv_id) ."' ORDER BY ucc_order ASC");
   while($rcer = mysqli_fetch_assoc($db_certificate->result)){
      $array_certificate[$rcer['ucc_order']] = $rcer;
   }
   unset($db_certificate);
   
   
   // Company
   $db_company = new db_query("SELECT * FROM user_cv_company WHERE ucc_use_id = " . $uid . " AND ucc_key = '". replaceMQ($mycv_id) ."' ORDER BY ucc_order ASC");
   while($rcom = mysqli_fetch_assoc($db_company->result)){
      $array_company[$rcom['ucc_order']] = $rcom;
   }
   unset($db_company);
   
   // Project
   $db_project = new db_query("SELECT * FROM user_cv_project WHERE ucp_use_id = " . $uid . " AND ucp_key = '". replaceMQ($mycv_id) ."' ORDER BY ucp_order ASC");
   while($rpro = mysqli_fetch_assoc($db_project->result)){
      $array_project[$rpro['ucp_order']] = $rpro;
   }
   unset($db_project);
   
   
   // Gusto
   $db_gusto = new db_query("SELECT * FROM user_cv_gusto WHERE ucg_use_id = " . $uid . " AND ucg_key = '". replaceMQ($mycv_id) ."'");
   if($rgus = mysqli_fetch_assoc($db_gusto->result)){
      $array_gusto = $rgus['ucg_des'];
   }
   unset($db_gusto);
   
   
   // Price
   $db_price = new db_query("SELECT * FROM user_cv_price WHERE ucp_use_id = " . $uid . " AND ucp_key = '". replaceMQ($mycv_id) ."' ORDER BY ucp_order ASC");
   while($rprice = mysqli_fetch_assoc($db_price->result)){
      $array_price[$rprice['ucp_order']] = $rprice;
   }
   unset($db_price);
   
   
   // Skill
   $db_skill = new db_query("SELECT * FROM user_cv_skill WHERE ucs_use_id = " . $uid . " AND ucs_key = '". replaceMQ($mycv_id) ."'");
   if($rskill = mysqli_fetch_assoc($db_skill->result)){      
      $skill_star = explode('|', $rskill['ucs_star']);
      $rskill['ucs_name'] = str_replace(',', '|', $rskill['ucs_name']);
      $skill_name = explode('|', $rskill['ucs_name']);
      foreach($skill_name as $k => $vl){
         $array_skill[] = array(
            'name' => $vl,
            'star' => isset($skill_star[$k])? intval($skill_star[$k]) : 0
         );
      }
   }
   unset($db_skill);
   
   
   // Target
   $db_target = new db_query("SELECT * FROM user_cv_target WHERE uct_use_id = " . $uid . " AND uct_key = '". replaceMQ($mycv_id) ."'");
   if($rtar = mysqli_fetch_assoc($db_target->result)){
      $array_target = $rtar['uct_des'];
   }
   unset($db_target);
   
   // more info
   $db_more_info = new db_query("SELECT * FROM user_cv_more_info WHERE umi_use_id = " . $uid . " AND umi_key = '". replaceMQ($mycv_id) ."'");
   if($rmore = mysqli_fetch_assoc($db_more_info->result)){
      $array_more_info = $rmore['umi_text'];
   }
   unset($db_more_info);
   
   // Get html
   $html = file_get_contents('../cv_template/cv_tem_'. $file_temp .'.html');
   foreach($array_user as $uk => $uvalue){
      $html = str_replace('{'. $uk .'}', $uvalue, $html);
   }
   
   if(!empty($arrayField_active)){
      foreach($arrayField_active as $kac => $vl_ac){
         $vlac = ($vl_ac == 1)? 'block' : 'none';
         $html = str_replace('{'. $kac .'}', $vlac, $html);
      }
   }
   
   // replate title
   if(!empty($array_title)){
      foreach($array_title as $kt => $vlt){
         $html = str_replace('{'. $kt .'}', $vlt, $html);
      }
   }
   
   // replace font
    $html = str_replace('{font}', $font, $html);
   
   // Get sub item 
   // sub school
   if(!empty($array_school)){
      foreach($array_school as $sid => $school){         
         $h = file_get_contents('../cv_template/temp_sub_'. $file_temp .'/school.html');
         foreach($school as $sk => $svalue){
            $h = str_replace('{'. $sk .'}', nl2br($svalue), $h);
         }
         $html_school .= $h;
      }
      $html = str_replace('{use_school}', $html_school, $html);
      
   }else{
      $html = str_replace('id="school"', 'id="school" style="display: none;"', $html);
   }
   
   
   // sub company
   if(!empty($array_company)){
      foreach($array_company as $sid => $school){         
         $h = file_get_contents('../cv_template/temp_sub_'. $file_temp .'/company.html');
         foreach($school as $sk => $svalue){
            $h = str_replace('{'. $sk .'}', nl2br($svalue), $h);
         }
         $html_company .= $h;
      }
      
      $html = str_replace('{use_company}', $html_company, $html);
   }else{
      $html = str_replace('id="company"', 'id="company" style="display: none;"', $html);
   }
   
   // sub project
   if(!empty($array_project)){
      foreach($array_project as $sid => $school){         
         $h = file_get_contents('../cv_template/temp_sub_'. $file_temp .'/project.html');
         foreach($school as $sk => $svalue){
            $h = str_replace('{'. $sk .'}', nl2br($svalue), $h);
         }
         $html_project .= $h;
      }
      
      $html = str_replace('{use_project}', $html_project, $html);
   }else{
      $html = str_replace('id="project"', 'id="project" style="display: none;"', $html);
   }
   
   // sub certificate
   if(!empty($array_certificate)){
      foreach($array_certificate as $sid => $school){         
         $h = file_get_contents('../cv_template/temp_sub_'. $file_temp .'/certificate.html');
         foreach($school as $sk => $svalue){
            $h = str_replace('{'. $sk .'}', $svalue, $h);
         }
         $html_certificate .= $h;
      }    
      
      $html = str_replace('{use_certificate}', $html_certificate, $html);
   }else{
      $html = str_replace('id="certificate"', 'id="certificate" style="display: none;"', $html);
   }
   
   
   // sub price
   if(!empty($array_price)){
      foreach($array_price as $sid => $school){         
         $h = file_get_contents('../cv_template/temp_sub_'. $file_temp .'/price.html');
         foreach($school as $sk => $svalue){
            $h = str_replace('{'. $sk .'}', $svalue, $h);
         }
         $html_price .= $h;
      }   
      
      $html = str_replace('{use_price}', $html_price, $html);
   }else{
      $html = str_replace('id="price"', 'id="price" style="display: none;"', $html);
   }
   
   // sub target
   if($array_target != ''){
      $h = file_get_contents('../cv_template/temp_sub_'. $file_temp .'/target.html');
      $h = str_replace('{uct_des}', $array_target, $h);  
      $html_target .= $h;   
      
      $html = str_replace('{use_target}', nl2br($html_target), $html);
   }else{
      $html = str_replace('id="target"', 'id="target" style="display: none;"', $html);
   }
   
   // more info
   if($array_more_info != ''){
      $h = file_get_contents('../cv_template/temp_sub_'. $file_temp .'/more_info.html');
      $h = str_replace('{umi_text}', $array_more_info, $h);  
      $html_more_info .= $h;   
      
      $html = str_replace('{use_more_info}', nl2br($html_more_info), $html);
   }else{
      $html = str_replace('id="target"', 'id="target" style="display: none;"', $html);
   }
   
   // sub gusto
   if($array_gusto != ''){
      $h = file_get_contents('../cv_template/temp_sub_'. $file_temp .'/gusto.html');
      $h = str_replace('{ucg_des}', nl2br($array_gusto), $h);
      $html_gusto .= $h; 
      
      $html = str_replace('{use_gusto}', $html_gusto, $html);
   }else{
      $html = str_replace('id="gusto"', 'id="gusto" style="display: none;"', $html);
   }
   
   
   // sub skill
   if(!empty($array_skill)){
      foreach($array_skill as $sid => $skill){  
         $h = file_get_contents('../cv_template/temp_sub_'. $file_temp .'/skill.html');         
         $h = str_replace('{skill_name}', $skill['name'], $h);
         // star
         $h_star  = '';
         for($ski = 1; $ski < 6; $ski++){
            $class = '<img class="star" src="http://1viec.com/themes/img_cv/star_g.png" />';
            if($ski <= $skill['star']) $class = '<img class="star" src="http://1viec.com/themes/img_cv/star_y.png" />';
            $h_star .= $class;
         }
         $h = str_replace('{skill_star}', $h_star, $h);
         
         $html_skill .= $h;
      }    
      
      $html = str_replace('{use_skill}', $html_skill, $html);
   }else{
      $html = str_replace('id="skill"', 'id="skill" style="display: none;"', $html);
   }
   
         
   
   
   return $html;
   
   
}