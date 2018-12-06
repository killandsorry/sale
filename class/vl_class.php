<?
define('VL_TABLE_FRIEND', 200); // default 200 bảng friend user
define('VL_TABLE_PROFILE', 100); // tổng số bảng user profile
define('VL_TABLE_JOB', 1000); // tổng số tin tuyển dụng trong 1 bảng
define('VL_TABLE_APPLY_CV', 5); // tổng số bảng (user apply cv)
define('VL_TABLE_RECORD', 5000);
define('VL_TABLE_APPLY_FOR_JOB', 5); // tổng số bảng (jobs user apply cv)
define('VL_TABLE_RECORD_JOB_FB', 20000); // tổng số bản ghi của bảng job_fb (jobs user apply cv)



// tin trả phí thuộc gói nào
define('VL_PAYMENT_PACKET_INTERESTING', 1); // gói hấp dẫn
define('VL_PAYMENT_PACKET_FOCUS', 2); // gói tiêu điểm ngành
define('VL_PAYMENT_PACKET_HOT', 3); // gói hot
define('VL_PAYMENT_PACKET_SUPPER_HOT', 4); // gói siêu hot

class vl_class{
   
   /**
    * Table job
    */
   function create_table_job($id = 0){
      if($id <= 0) return 'job';
      
      return 'job_' . intval($id / VL_TABLE_RECORD);
   }
   
   /**
    * Table job cv
    */
   function create_table_apply_job($jid = 0){
      if($jid <= 0) return '';
      if($jid > 0) return 'apply_job_' . intval($jid % VL_TABLE_APPLY_FOR_JOB);
   }
   
   /**
    * Table cv Job
    */
   function create_table_apply_cv($uid = 0){
      if($uid <= 0) return '';
      if($uid > 0) return 'apply_cv_' . intval($uid  % VL_TABLE_APPLY_CV);
   }
   
   
   /**
    * Table job_fb
    */
   function create_table_job_fb($id = 0){
      if($id <= 0) return 'job_fb';
      
      return 'job_fb_' . intval($id / VL_TABLE_RECORD_JOB_FB);
   }
   
   
   /**
    * Clean keyword
    * 
    */
   function clean_keyword($str = ''){
      $str  = trim($str);
      if($str == '') return '';
      
      $keyword  = removeAccent($str);
      $keyword  = mb_strtolower($keyword, 'UTF-8');
      $keyword  = str_replace(array('*',',','"',"'",'%','&','#','@','!', '$', '-', '_','(', ')', '<', '>', '?', ':', '.', "\\", '/'), ' ', $keyword);
      $keyword  = str_replace('  ', ' ', $keyword);
      $keyword  = str_replace('  ', ' ', $keyword);
      
      return replaceMQ($keyword);
   }
      
   /**
    * apply cv
    * array(
    *    'u_id' =>
    *    'job_id' => 
    * )
    */
   function apply_cv($data = array()){
      $u_id  = isset($data['u_id'])? intval($data['u_id']) : 0;
      $job_id  = isset($data['job_id'])? intval($data['job_id']) : 0;
      $juse_id = isset($data['juse_id'])? intval($data['juse_id']) : 0;
      $job_name   = isset($data['job_name'])? ($data['job_name']) : '';
      
      if($u_id <= 0 || $job_id <= 0) return 0;
      
      // table apply_cv B1
      $table_apply_cv = $this->create_table_apply_cv($u_id); //'apply_cv_' . intval($u_id  % VL_TABLE_APPLY_CV);
      
      // table job user apply B2
      $table_job_apply  = $this->create_table_apply_job($job_id); //'apply_job_' . intval($job_id % VL_TABLE_APPLY_FOR_JOB);
      
      // kiểm tra đã ứng tuyển chưa ứng tuyển rồi thì thôi
      $db_check    = new db_query("SELECT * FROM " . $table_apply_cv . " WHERE
                                   apc_use_id = " . intval($u_id) . " AND apc_job_id = " . intval($job_id) . " LIMIT 1");
      if($rchec     = mysqli_fetch_assoc($db_check->result)){
         return 2;
      }
      unset($db_check);
      
      // cập nhật b1
      $db_ex   = new db_execute("INSERT IGNORE INTO " . $table_apply_cv . "(apc_use_id, apc_job_id, apc_date)
                                 VALUES(". intval($u_id) .",". intval($job_id) .",". time() .")", __FILE__ . " LINE: " . __LINE__);
      unset($db_ex);
      
      // cập nhật b2
      //@file_put_contents('../logss/a.txt', "INSERT IGNORE INTO " . $table_job_apply . "(apj_use_id, apj_job_id, apj_date)
      //                           VALUES(". intval($u_id) .",". intval($job_id) .",". time() .")");
      $db_ex   = new db_execute("INSERT IGNORE INTO " . $table_job_apply . "(apj_use_id, apj_job_id, apj_date)
                                 VALUES(". intval($u_id) .",". intval($job_id) .",". time() .")", __FILE__ . " LINE: " . __LINE__);
      unset($db_ex);
      
      // cập nhật ứng viên cho từng user đăng tin để ng đăng tin quản lý ứng viên
      if($juse_id > 0 && $job_name != ''){
         $db_ex   = new db_execute("INSERT IGNORE INTO cv_of_user (cou_use_id,cou_job_id,cou_date,cou_uv_id,cou_job_name)
                                    VALUES(". intval($juse_id) .",". intval($job_id) .",". time() .",". intval($u_id) .",'". replaceMQ($job_name) ."')");
         unset($db_ex);
      }
      
      return 1;
   }
   
   /**
    * Get total record user apply job
    * param:
    *    job_id : 
    * return : total user
    */
   function get_count_user_apply_job($job_id = 0){
      $total   = 0;
      
      if($job_id <= 0) return $total;
      
      $table   = $this->create_table_apply_job($job_id);
      $db_count   = new db_count("SELECT COUNT(*) AS count FROM " . $table . " WHERE apj_job_id = ". intval($job_id));
      $total      = $db_count->total;
      unset($db_count);
      return $total;
   }
   
   
   /**
    * Convert data job to table job optimize
    * param
    *    array :
    *       jid:
    *       cat_all:
    *       cit_all
    *    
    */
    
   function convert_to_table_optimize($data = array()){
      $jid = isset($data['jid'])? intval($data['jid']) : 0;
      $jcat_all   = isset($data['cat_all'])? $data['cat_all'] : '';
      $jcit_all   = isset($data['cit_all'])? $data['cit_all'] : '';
      $link_craw   = isset($data['link_craw'])? $data['link_craw'] : '';
      
      if($jid <= 0) return 0;
      $table_job  = $this->create_table_job($jid);
      $db_in   = new db_execute("INSERT INTO ". $table_job ."
      (job_id,job_number,job_name,job_skill,job_rank,job_date_create,job_date_expires,job_name_accent,job_last_update,job_user_id,job_cat_id,job_cit_id,job_active,job_hot,job_supper_hot,job_hot_time,job_supper_hot_time,job_company_id,job_company_name,job_ip,job_salary,job_short_description,job_requirement,job_policy,job_profile,job_type,job_experience,job_level,job_gender,job_cat_all,job_cit_all,job_contact_name,job_contact_phone,job_contact_email,job_craw_link)
      SELECT 
      a.job_id,a.job_number,a.job_name,a.job_skill,a.job_rank,a.job_date_create,a.job_date_expires,a.job_name_accent,a.job_last_update,a.job_user_id,a.job_cat_id,a.job_cit_id,a.job_active,job_hot,job_supper_hot,job_hot_time,job_supper_hot_time,job_company_id,job_company_name,job_ip,a.job_salary,jobd_short_description,jobd_requirement,jobd_policy,jobd_profile,a.job_type,job_experience,job_level,job_gender,'". $jcat_all ."','". $jcit_all ."',job_contact_name,job_contact_phone,job_contact_email,'". $link_craw ."'
      FROM job a
      INNER JOIN job_filter f ON f.job_id = a.job_id
      INNER JOIN jobs_description d ON d.jobd_id = a.job_id
      WHERE a.job_id = " . $jid);
      unset($db_in);
      
      return 1;
      
   }
   
   /**
    * Function set Active, Unactive job
    * param:
    *    array:
    *       jid:
    * return 0 || 1
    */
   function set_active_unactive_job($data = array()){
      $jid  = isset($data['jid'])? intval($data['jid']) : 0;
      
      if($jid <= 0) return 0;
      
      $status = 0;
      $current_vl = 0;
      $db_select = new db_query("SELECT job_active FROM job_active WHERE job_id = " . intval($jid) . " LIMIT 1");
   	if($row = mysqli_fetch_assoc($db_select->result)){
   		$current_vl 	= abs($row['job_active']-1);
   	}
   	unset($db_select);
      
      $db_job	= new db_execute("UPDATE job SET job_active = " . $current_vl . " WHERE job_id = " . intval($jid));
      unset($db_job);
      
      // table job
      $table_job     = $this->create_table_job($jid);
      $db_job_o        = new db_execute("UPDATE " . $table_job . " SET job_active = " . $current_vl . " WHERE job_id = " . intval($jid));
      unset($db_job_o);
      
      // job_active
      $db_active     = new db_execute("UPDATE job_active SET job_active = " . $current_vl . " WHERE job_id = " . intval($jid));
      unset($db_active);
      
      return 1;
      
   }
   
   /**
    * FUnction check user apply cv
    * param:
    * use_id :
    * job_id
    * 
    * return 0 || 1
    *  
    */
   function check_user_apply_cv($data = array()){
      $use_id  = isset($data['uid'])? intval($data['uid']) : 0;
      $job_id  = isset($data['jid'])? intval($data['jid']) : 0;
      
      if($use_id <= 0 || $job_id <= 0) return 0;
      
      
      $status = 0;
      $table_apply_cv = 'apply_cv_' . intval($use_id % VL_TABLE_APPLY_CV);
      $db_app  = new db_query("SELECT * FROM " . $table_apply_cv . " WHERE apc_use_id = " . intval($use_id) . " AND apc_job_id = ". intval($job_id) ." LIMIT 1", __FILE__ . " LINE: " . __LINE__);
      if($rapp = mysqli_fetch_assoc($db_app->result)){
         $status = 1;
      }
      
      unset($db_app);
      
      return $status;
   }
    
   
   /**
    * Function cập nhật filed user
    */
   function update_field($key = ''){
      if($key != ''){
         $field   = 'usf_' . $key;
         //file_put_contents('../logss/a.txt', "SELECT * FROM user_field WHERE usf_id = " . intval(UID) . " LIMIT 1");
         $db_field   = new db_query("SELECT * FROM user_field WHERE usf_id = " . intval(UID) . " LIMIT 1");
         if($row     = mysqli_fetch_assoc($db_field->result)){
            //file_put_contents('../logss/b.txt', "UDPATE user_field SET " . $field . " = 1 WHERE usf_id = " . intval(UID));
            $db_update  = new db_execute("UPDATE user_field SET " . $field . " = 1 WHERE usf_id = " . intval(UID));
            unset($db_update);
         }else{
            $db_new  = new db_execute("INSERT INTO user_field(usf_id,".$field .") VALUES (". UID .",1)");
            unset($db_new);
         }
         unset($db_field);
      }
   }
   
   /**
    * Hàm thêm profile
    * data = array(
    *    data => $_POST,
    *    key => 
    * )
    */
   function add_profile($row = array()){
      $data = isset($row['data'])? $row['data'] : array();
      $key  = isset($row['key'])? $row['key'] : '';
      
      if(empty($data) || $key == '') return 0;
      
      $status  = 0;
      switch($key){
         case 'school':
            $status = $this->add_school($data);
            break;
         case 'language':
            $status = $this->add_language($data);
            break;
         case 'achievements':
            $status = $this->add_achievements($data);
            break;
         case 'cetificate':
            $status = $this->add_cetificate($data);
            break;
         case 'detail':
            $status = $this->add_detail($data);
            break;
         case 'gusto':
            $status = $this->add_gusto($data);
            break;
         case 'history':
            $status = $this->add_history($data);
            break;
         case 'idea':
            $status = $this->add_idea($data);
            break;
         case 'works':
            $status = $this->add_works($data);
            break;
      }
      
      return $status;
   }
   
   /** 
    * Thêm profile trường học
    */
   function add_school($data = array()){
      $status  = 0;
      $active = isset($data['active_all_user'])? intval($data['active_all_user']) : 0;
      $name = isset($data['name'])? replaceMQ(removeHTML($data['name'])) : '';
      $school_id = isset($data['school_id'])? intval($data['school_id']) : 0;
      $from_date = isset($data['from_date'])? intval($data['from_date']) : 0;
      $to_date = isset($data['to_date'])? intval($data['to_date']) : 0;
      $field_study = isset($data['field_study'])? replaceMQ(removeHTML($data['field_study'])) : '';
      $achievements = isset($data['achievements'])? replaceMQ(removeHTML($data['achievements'])) : '';
      $position = isset($data['position'])? replaceMQ(removeHTML($data['position'])) : '';
      $activity = isset($data['activity'])? replaceMQ(removeHTML($data['activity'])) : '';
      
      $action  = isset($data['action'])? $data['action'] : 'add';
      $usp_id  = isset($data['id'])? intval($data['id']) : 0;
      if($action == 'add'){
         
         $sql  = "INSERT INTO users_profile_school
         (usp_use_id,usp_name,usp_school_id,usp_from_date,usp_to_date,usp_field_study,usp_achievements,usp_position,usp_activity,usp_date_create,usp_last_update,usp_active) 
         VALUES
         (".UID.",'".$name."',".$school_id.",".$from_date.",".$to_date.",'".$field_study."','".$achievements."','".$position."','".$activity."',".time().",0,".$active.")";
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
            $this->update_field('school');
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }else{
         $sql  = "UPDATE users_profile_school SET
         usp_name = '". $name . "',
         usp_from_date = ". $from_date .",
         usp_to_date = ". $to_date .",
         usp_field_study = '". $field_study ."',
         usp_achievements = '". $achievements ."',
         usp_position = '". $position ."', 
         usp_activity = '". $activity ."',
         usp_last_update = ". time() .",
         usp_active = ". $active ."
         WHERE usp_id = " . $usp_id;
         
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }
      
   }
   
   function add_language($data = array()){
      $status  = 0;
      $active = isset($data['active_all_user'])? intval($data['active_all_user']) : 0;
      $language_name = isset($data['language_name'])? replaceMQ(removeHTML($data['language_name'])) : '';
      $level = isset($data['level'])? intval($data['level']) : 0;
      
      $action  = isset($data['action'])? $data['action'] : 'add';
      $usp_id  = isset($data['id'])? intval($data['id']) : 0;
      
      if($action == 'add'){
         $sql  = "INSERT INTO users_profile_language
         (usp_use_id,usp_language_name,usp_level,usp_date_create,usp_last_update,usp_active) 
         VALUES
         (".UID.",'".$language_name."',".$level.",".time().",0,".$active.")";
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
            $this->update_field('language');
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }else{
         $sql  = "UPDATE users_profile_language SET
         usp_language_name = '". $language_name ."',
         usp_level = ". $level .",
         usp_last_update = ". time() .",
         usp_active = ". $active ."
         WHERE usp_id = ". $usp_id;
         
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }
      
         
      
   }
   
   function add_achievements($data = array()){
      $status  = 0;
      $active = isset($data['active_all_user'])? intval($data['active_all_user']) : 0;
      $achievements_name = isset($data['achievements_name'])? replaceMQ(removeHTML($data['achievements_name'])) : '';
      $fields = isset($data['fields'])? replaceMQ(removeHTML($data['fields'])) : '';
      $detail = isset($data['detail'])? replaceMQ(removeHTML($data['detail'])) : '';
      $date = isset($data['date'])? $data['date'] : 0;
      
      $action  = isset($data['action'])? $data['action'] : 'add';
      $usp_id  = isset($data['id'])? intval($data['id']) : 0;
      
      if($action == 'add'){
         $sql  = "INSERT INTO users_profile_achievements
         (usp_use_id,usp_achievements_name,usp_date,usp_fields,usp_detail,usp_date_create,usp_last_update,usp_active) 
         VALUES
         (".UID.",'".$achievements_name."',".$date.",'".$fields ."','". $detail ."',". time() .",0,".$active.")";
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
            $this->update_field('achievements');
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }else{
         $sql  = "UPDATE users_profile_achievements SET
         usp_achievements_name = '". $achievements_name ."',
         usp_date = ". $date .",
         usp_fields = '". $fields ."',
         usp_detail = '". $detail ."',
         usp_last_update = ". time() .",
         usp_active = ". $active;
         
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }
      
         
      
   }
   
   function add_cetificate($data = array()){
      $status  = 0;
      $active = isset($data['active_all_user'])? intval($data['active_all_user']) : 0;
      $cetificate_name = isset($data['cetificate_name'])? replaceMQ(removeHTML($data['cetificate_name'])) : '';
      $confer = isset($data['confer'])? replaceMQ(removeHTML($data['confer'])) : '';
      $date_confer = isset($data['date_confer'])? convertDateTime($data['date_confer']) : 0;
      $date_expires = isset($data['date_expires'])? convertDateTime($data['date_expires']) : 0;
      $no_expires = isset($data['no_expires'])? intval($data['no_expires']) : 0;
      $number_cetificate = isset($data['number_cetificate'])? replaceMQ(removeHTML($data['number_cetificate'])) : '';
      $url_confer = isset($data['url_confer'])? replaceMQ(removeHTML($data['url_confer'])) : '';
            
      $action  = isset($data['action'])? $data['action'] : 'add';
      $usp_id  = isset($data['id'])? intval($data['id']) : 0;
      
      if($action == 'add'){
         $sql  = "INSERT INTO users_profile_cetificate
         (usp_use_id,usp_cetificate_name,usp_confer,usp_date_confer,usp_date_expires,usp_no_expires,usp_number_cetificate,usp_url_confer,usp_active,usp_date_create,usp_last_update) 
         VALUES
         (".UID.",'".$cetificate_name."','".$confer."',".$date_confer.",". $date_expires .",".$no_expires.",'". $number_cetificate ."','". $url_confer ."',". $active .",". time() .",0)";
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
            $this->update_field('cetificate');
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }else{
         $sql  = "UPDATE users_profile_cetificate SET
         usp_cetificate_name = '". $cetificate_name ."',
         usp_confer = '". $confer ."',
         usp_date_confer = ". $date_confer .",
         usp_date_expires = ". $date_expires .",
         usp_no_expires = ". $no_expires .",
         usp_number_cetificate = ". $number_cetificate .",
         usp_url_confer = '". $url_confer ."',
         usp_active = " . $active . ",
         usp_last_update = ". time();
         
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }
      
         
      
   }
   
   function add_detail($data = array()){
      $status  = 0;
      $active = isset($data['active_all_user'])? intval($data['active_all_user']) : 0;
      $country = isset($data['country'])? replaceMQ(removeHTML($data['country'])) : '';
      $family = isset($data['family'])? intval($data['family']) : 0;
      $child = isset($data['child'])? intval($data['child']) : 0;
      $phone = isset($data['phone'])? replaceMQ(removeHTML($data['phone'])) : '';
      $email = isset($data['email'])? replaceMQ(removeHTML($data['email'])) : '';
      
      $action  = isset($data['action'])? $data['action'] : 'add';
      $usp_id  = isset($data['id'])? intval($data['id']) : 0;
      
      if($action == 'add'){
         $sql  = "INSERT INTO users_profile_detail
         (usp_use_id,usp_country,usp_family,usp_child,usp_phone,usp_email,usp_date_create,usp_last_update,usp_active) 
         VALUES
         (".UID.",'".$country."',".$family.",". $child .",'". $phone ."','". $email ."',".time().",0,".$active.")";
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
            $this->update_field('detail');
            
            // update phone user
            $str_update = '';
            if($phone != ''){
               $str_update .= "use_phone = '". $phone ."'";
            }
            if($email != ''){
               if($str_update != ''){
                  $str_update .= ",use_email = '". $email ."'";
               }else{
                  $str_update .= "use_email = '". $email ."'";
               }
            }
            
            if($str_update != ''){
               $db_update  = new db_execute("UPDATE user SET ". $str_update ." WHERE use_id = " . intval(UID));
               unset($db_update);
            }
               
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }else{
         $sql  = "UPDATE users_profile_detail SET
         usp_country = '". $country ."',
         usp_family = ". $family .",
         usp_child = ". $child .",
         usp_phone = '". $phone ."',
         usp_email = '". $email ."',
         usp_last_update = ". time() .",
         usp_active = ". $active; 
         
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
            // update phone user
            //file_put_contents('../logss/a.txt', "UPDATE user SET use_phone = '". $phone ."' WHERE use_id = " . intval(UID));
            // update phone user
            $str_update = '';
            if($phone != ''){
               $str_update .= "use_phone = '". $phone ."'";
            }
            if($email != ''){
               if($str_update != ''){
                  $str_update .= ",use_email = '". $email ."'";
               }else{
                  $str_update .= "use_email = '". $email ."'";
               }
            }
            
            if($str_update != ''){
               $db_update  = new db_execute("UPDATE user SET ". $str_update ." WHERE use_id = " . intval(UID));
               unset($db_update);
            }
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }
      
         
      
   }
   
   function add_gusto($data = array()){
      $status  = 0;
      $active = isset($data['active_all_user'])? intval($data['active_all_user']) : 0;
      $gusto = isset($data['gusto'])? replaceMQ(removeHTML($data['gusto'])) : '';
      
      $action  = isset($data['action'])? $data['action'] : 'add';
      $usp_id  = isset($data['id'])? intval($data['id']) : 0;
      
      if($action == 'add'){
         $sql  = "INSERT INTO users_profile_gusto
         (usp_use_id,usp_gusto,usp_date_create,usp_last_update,usp_active) 
         VALUES
         (".UID.",'".$gusto."',".time().",0,".$active.")";
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
            $this->update_field('gusto');
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }else{
         $sql  = "UPDATE users_profile_gusto SET
         usp_gusto = '". $gusto ."',
         usp_last_update = ". time() .",
         usp_active = ". $active;
         
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }
      
         
      
   }
   
   function add_history($data = array()){
      $status  = 0;
      $active = isset($data['active_all_user'])? intval($data['active_all_user']) : 0;
      $history = isset($data['history'])? replaceMQ(removeHTML($data['history'])) : '';
      
      $action  = isset($data['action'])? $data['action'] : 'add';
      $usp_id  = isset($data['id'])? intval($data['id']) : 0;
      
      if($action == 'add'){
         $sql  = "INSERT INTO users_profile_history
         (usp_use_id,usp_history,usp_date_create,usp_last_update,usp_active) 
         VALUES
         (".UID.",'".$history."',".time().",0,".$active.")";
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
            $this->update_field('history');
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }else{
         $sql  = "UPDATE users_profile_history SET
         usp_history = '". $history ."',
         usp_last_update = ". time() .",
         usp_active = " . $active;
         
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }
      
         
      
   }
   
   function add_idea($data = array()){
      $status  = 0;
      $active = isset($data['active_all_user'])? intval($data['active_all_user']) : 0;
      $idea_name = isset($data['idea_name'])? replaceMQ(removeHTML($data['idea_name'])) : '';
      $date = isset($data['date'])? convertDateTime($data['date']) : 0;
      $fields = isset($data['fields'])? replaceMQ(removeHTML($data['fields'])) : '';
      $content = isset($data['content'])? replaceMQ(removeHTML($data['content'])) : '';
      
      
      $action  = isset($data['action'])? $data['action'] : 'add';
      $usp_id  = isset($data['id'])? intval($data['id']) : 0;
      
      if($action == 'add'){
         $sql  = "INSERT INTO users_profile_idea
         (usp_use_id,usp_idea_name,usp_date,usp_fields,usp_content,usp_date_create,usp_last_update,usp_active) 
         VALUES
         (".UID.",'".$idea_name."',".$date.",'". $fields ."','". $content ."',".time().",0,".$active.")";
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
            $this->update_field('idea');
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }else{
         $sql  = "UPDATE users_profile_idea SET
         usp_idea_name = '". $idea_name ."',
         usp_date = ". $date .",
         usp_fields = '". $fields ."',
         usp_content = '".  $content . "',
         usp_last_update = ". time() .",
         usp_active = ". $active; 
         
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }
      
         
      
   }
   
   function add_works($data = array()){
      $status  = 0;
      $active = isset($data['active_all_user'])? intval($data['active_all_user']) : 0;
      $com_name = isset($data['com_name'])? replaceMQ(removeHTML($data['com_name'])) : '';
      $from_date = isset($data['from_date'])? convertDateTime($data['from_date']) : 0;
      $to_date = isset($data['to_date'])? convertDateTime($data['to_date']) : 0;
      $no_date = isset($data['no_date'])? intval($data['no_date']) : 0;
      $category = isset($data['category'])? replaceMQ(removeHTML($data['category'])) : '';
      $position = isset($data['position'])? replaceMQ(removeHTML($data['position'])) : '';
      
      
      $action  = isset($data['action'])? $data['action'] : 'add';
      $usp_id  = isset($data['id'])? intval($data['id']) : 0;
      
      if($action == 'add'){
         $sql  = "INSERT INTO users_profile_works
         (usp_use_id,usp_com_name,usp_from_date,usp_to_date,usp_no_date,usp_category,usp_position,usp_date_create,usp_last_update,usp_active) 
         VALUES
         (".UID.",'".$com_name."',".$from_date.",". $to_date .",". $no_date .",'". $category ."','". $position ."',".time().",0,".$active.")";
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
            $this->update_field('works');
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }else{
         $sql  = "UPDATE users_profile_works SET
         usp_com_name = '". $com_name ."',
         usp_from_date = ". $from_date .",
         usp_to_date = ". $to_date .",
         usp_no_date = ". $no_date .",
         usp_category = ". $category .",
         usp_position = '". $position ."',
         usp_last_update = ". time() .",
         usp_active = ". $active; 
         
         $db_ex   = new db_execute($sql);
         if($db_ex->total > 0){
            $status =  1;
         }else{
            $status = 0;
         }
         unset($db_ex);
         return $status;
      }
         
      
   }
   
   
   function view_profile($key = '', $data = array(), $edit = 0){
      $html = '';
      
      switch($key){
         case 'school':
            $html = $this->view_school($data, $edit);
            break;
         case 'language':
            $html = $this->view_language($data, $edit);
            break;
         case 'achievements':
            $html = $this->view_achievements($data, $edit);
            break;
         case 'cetificate':
            $html = $this->view_cetificate($data, $edit);
            break;
         case 'detail':
            $html = $this->view_detail($data, $edit);
            break;
         case 'gusto':
            $html = $this->view_gusto($data, $edit);
            break;
         case 'history':
            $html = $this->view_history($data, $edit);
            break;
         case 'idea':
            $html = $this->view_idea($data, $edit);
            break;
         case 'works':
            $html = $this->view_works($data, $edit);
            break;
      }
      
      return $html;
   }
   
   function view_school($data = array(), $edit = 0){
      $html = '';
      if(!empty($data)){
         $html_action   = '';
         if($edit == 1){
            $html_action = '<div class="action_profile">
                              <span class="action_repair" data-id="'. $data['usp_id'] .'" data-key="school" onclick="edit_profile(this)">Sửa</span>
                              <span class="action_delete" data-id="'. $data['usp_id'] .'" data-key="school" onclick="delete_profile(this)">Xóa</span>
                           </div>';
         }
         $html .= '<div class="miniitem">
                     '. $html_action .'
                     <p title="Trường học">Học: <b>'. $data['usp_name'] .'</b></p> 
                     <p title="Từ thời gian nào">Từ: '. $data['usp_from_date'] . (($data['usp_to_date'] > 0)? ' - '. $data['usp_to_date'] .'</b>' : '') .'</p>
                     '. ( ($data['usp_field_study'] != '')? '<p title="lĩnh vực nghiên cứu">'. $data['usp_field_study'] .'</p>' : '' ) .'
                     '. ( ($data['usp_achievements'] != '')? '<p title="thành tích học tập">Thành tích: '. $data['usp_achievements'] .'</p>' : '' ) .'
                     '. ( ($data['usp_position'] != '')? '<p title="vị trí đảm nhiệm">Vị trí: '. $data['usp_position'] .'</p>' : '' ) .'
                     '. ( ($data['usp_activity'] != '')? '<p title="Công tác hoạt động tại nơi học">'. nl2br($data['usp_activity']) .'</p>' : '' ) .'
                   </div>';
      }
      
      return $html;
   }
   
   function view_language($data = array(), $edit = 0){
      global $arraylanguage_level;
      $html = '';
      if(!empty($data)){
         $html_action   = '';
         if($edit == 1){
            $html_action = '<div class="action_profile">
                              <span class="action_repair" data-id="'. $data['usp_id'] .'" data-key="language" onclick="edit_profile(this)">Sửa</span>
                              <span class="action_delete" data-id="'. $data['usp_id'] .'" data-key="language" onclick="delete_profile(this)">Xóa</span>
                           </div>';
         }
         $html .= '<div class="miniitem w50">
                     '. $html_action .'
                     <p><b title="Tên ngoại ngữ">'. $data['usp_language_name'] .'</b></p>  
                     '. ( ($data['usp_level'] > 0)? '<p title="trình độ ngoại ngữ">'. $arraylanguage_level[$data['usp_level']] .'</p>' : '' ) .'
                   </div>';
      }
      
      return $html;
   }
   
   function view_achievements($data = array(), $edit = 0){
      $html = '';
      if(!empty($data)){
         $html_action   = '';
         if($edit == 1){
            $html_action = '<div class="action_profile">
                              <span class="action_repair" data-id="'. $data['usp_id'] .'" data-key="achievements" onclick="edit_profile(this)">Sửa</span>
                              <span class="action_delete" data-id="'. $data['usp_id'] .'" data-key="achievements" onclick="delete_profile(this)">Xóa</span>
                           </div>';
         }
         $html .= '<div class="miniitem">
                     '. $html_action .'
                     <p><b>'. $data['usp_achievements_name'] .'</b> <i>(năm: '. $data['usp_date'] .')</i></p>  
                     '. ( ($data['usp_fields'] != '')? '<p>Lĩnh vực: '. $data['usp_fields'] .'</p>' : '' ) .'
                     '. ( ($data['usp_detail'] != '')? '<p>'. nl2br($data['usp_detail']) .'</p>' : '' ) .'                     
                   </div>';
      }
      
      return $html;
   }
   
   function view_cetificate($data = array(), $edit = 0){
      $html = '';
      if(!empty($data)){
         $html_action   = '';
         if($edit == 1){
            $html_action = '<div class="action_profile">
                              <span class="action_repair" data-id="'. $data['usp_id'] .'" data-key="cetificate" onclick="edit_profile(this)">Sửa</span>
                              <span class="action_delete" data-id="'. $data['usp_id'] .'" data-key="cetificate" onclick="delete_profile(this)">Xóa</span>
                           </div>';
         }
         $html .= '<div class="miniitem">
                     '. $html_action .'
                     <p><b>'. $data['usp_cetificate_name'] .'</b> <i>(số: '. $data['usp_number_cetificate'] .')</i></p>  
                     '. ( ($data['usp_date_confer'] > 0)? '<p>Cấp ngày: '. date('d/m/Y', $data['usp_date_confer']) .'</p>' : '' ) .'
                     '. ( ($data['usp_date_expires'] > 0)? '<p>Hết hạn: <b class="price">'. date('d/m/Y', $data['usp_date_expires']) .'</b></p>' : '' ) .'
                     '. ( ($data['usp_no_expires'] > 0)? '<p><b class="clcm">Chứng chỉ vô thời hạn</b></p>' : '' ) .'
                     '. ( ($data['usp_confer'] != '')? '<p>Nơi cấp: '. $data['usp_confer'] . ( ($data['usp_url_confer'] != '')? '<i > ('. $data['usp_url_confer'] .')</i>' : '' ) .'</p>' : '' ) .'                     
                   </div>';
      }
      
      return $html;
   }
   
   function view_detail($data = array(), $edit = 0){
      global $array_marrie, $array_myson;
      $html = '';
      if(!empty($data)){
         $html_action   = '';
         if($edit == 1){
            $html_action = '<div class="action_profile">
                              <span class="action_repair" data-id="'. $data['usp_id'] .'" data-key="detail" onclick="edit_profile(this)">Sửa</span>
                              <span class="action_delete" data-id="'. $data['usp_id'] .'" data-key="detail" onclick="delete_profile(this)">Xóa</span>
                           </div>';
         }
         $html .= '<div class="miniitem">
                     '. $html_action .'
                     <p>Quê quán: <b>'. $data['usp_country'] .'</b></p>  
                     '. ( ($data['usp_family'] > 0)? '<p>Hôn nhân: '. $array_marrie[$data['usp_family']] .'</p>' : '' ) .'
                     '. ( ($data['usp_child'] > 0)? '<p>'. $array_myson[$data['usp_child']] .'</p>' : '' ) .'
                     '. ( ($data['usp_phone'] != '')? '<p>Số ĐT: <b>'. $data['usp_phone'] .'</b></p>' : '' ) .'
                     '. ( ($data['usp_email'] != '')? '<p>Email: <b>'. $data['usp_email'] .'</b></p>' : '' ) .'                     
                   </div>';
      }
      
      return $html;
   }
   
   function view_gusto($data = array(), $edit = 0){
      $html = '';
      if(!empty($data)){
         $html_action   = '';
         if($edit == 1){
            $html_action = '<div class="action_profile">
                              <span class="action_repair" data-id="'. $data['usp_id'] .'" data-key="gusto" onclick="edit_profile(this)">Sửa</span>
                              <span class="action_delete" data-id="'. $data['usp_id'] .'" data-key="gusto" onclick="delete_profile(this)">Xóa</span>
                           </div>';
         }
         $html .= '<div class="miniitem">
                     '. $html_action .'
                     <p>'. nl2br($data['usp_gusto']) .'</p>                     
                   </div>';
      }
      
      return $html;
   }
   
   function view_history($data = array(), $edit = 0){
      $html = '';
      if(!empty($data)){
         $html_action   = '';
         if($edit == 1){
            $html_action = '<div class="action_profile">
                              <span class="action_repair" data-id="'. $data['usp_id'] .'" data-key="history" onclick="edit_profile(this)">Sửa</span>
                              <span class="action_delete" data-id="'. $data['usp_id'] .'" data-key="history" onclick="delete_profile(this)">Xóa</span>
                           </div>';
         }
         $html .= '<div class="miniitem">
                     '. $html_action .'
                     <p>'. nl2br($data['usp_history']) .'</p>                     
                   </div>';
      }
      
      return $html;
   }
   
   function view_idea($data = array(), $edit = 0){
      $html = '';
      if(!empty($data)){
         $html_action   = '';
         if($edit == 1){
            $html_action = '<div class="action_profile">
                              <span class="action_repair" data-id="'. $data['usp_id'] .'" data-key="idea" onclick="edit_profile(this)">Sửa</span>
                              <span class="action_delete" data-id="'. $data['usp_id'] .'" data-key="idea" onclick="delete_profile(this)">Xóa</span>
                           </div>';
         }
         $html .= '<div class="miniitem">
                     '. $html_action .'
                     <p><b>'. $data['usp_idea_name'] .'</b> <i>(Năm: '. date('m/Y', $data['usp_date']) .')</i></p>  
                     '. ( ($data['usp_fields'] != '')? '<p>Lĩnh vực: '. $data['usp_fields'] .'</p>' : '' ) .'
                     '. ( ($data['usp_content'] != '')? '<p>'. nl2br($data['usp_content']) .'</p>' : '' ) .'                     
                   </div>';
      }
      
      return $html;
   }
   
   function view_works($data = array(), $edit = 0){
      $html = '';
      if(!empty($data)){
         $html_action   = '';
         if($edit == 1){
            $html_action = '<div class="action_profile">
                              <span class="action_repair" data-id="'. $data['usp_id'] .'" data-key="works" onclick="edit_profile(this)">Sửa</span>
                              <span class="action_delete" data-id="'. $data['usp_id'] .'" data-key="works" onclick="delete_profile(this)">Xóa</span>
                           </div>';
         }
         $html .= '<div class="miniitem">
                     '. $html_action .'
                     <p><b>'. $data['usp_com_name'] .'</b></p>  
                     <p title="Từ thời gian nào">Từ: '. date('m/Y', $data['usp_from_date']) . (($data['usp_to_date'] > 0)? ' - '. date('m/Y', $data['usp_to_date']) .'</b>' : '') .'</p>                     
                     '. ( ($data['usp_no_date'] > 0)? '<p><b class="clcm">Đến hiện tại</b></p>' : '' ) .'
                     '. ( ($data['usp_category'] != '')? '<p>Bộ phận: '. $data['usp_category'] . ( ($data['usp_position'] != '')? '<i > ('. $data['usp_position'] .')</i>' : '' ) .'</p>' : '' ) .'                     
                   </div>';
      }
      
      return $html;
   }
   
   /**
    * Keyword search
    */
   function addKeywordSearch($keyword = '', $cat_id = 0){
      $key = replaceMQ($keyword);
      $key_accent = $this->clean_keyword($key);
      
      if($key_accent != ''){
         $db_key    = new db_query("SELECT * FROM keyword_search WHERE key_name_accent = '". $key_accent ."' LIMIT 1");
         if($row    = mysqli_fetch_assoc($db_key->result)){
            $db_update  = new db_execute("UPDATE keyword_search SET key_count = key_count + 1 WHERE key_id = " . $row['key_id']);
            unset($db_update);
         }else{
            $db_insert  = new db_execute("INSERT INTO keyword_search (key_name, key_name_accent, key_cat_id, key_count) 
                                          VALUES('". $key ."','". $key_accent ."',". intval($cat_id) .",1)");
            unset($db_insert);
            unset($db_update);
         }
         unset($db_key);
      }
   }
   
}  