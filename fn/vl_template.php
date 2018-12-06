<?
// template list job
function templateJob($row = array()){
   global $arrayCategory, $arrayCity, $arrayJobType, $arraySalary, $arrayLevel, $arrayRank,$myuser;
   $forcat  = isset($row['forcat'])? intval($row['forcat']) : 0;
   $htmlTem = '';
   $style      = isset($row['style'])? $row['style'] : '';
   $linkJob		= createLinkJob($row);
	$job_name	= clean_title($row['job_name']);
	$job_cat_id	= (isset($row['job_cat_id']) && $row['job_cat_id'] > 0)? $row['job_cat_id'] : 0;
	$linkcat		= '#';
	$cat_name	= '';
	if($job_cat_id > 0 && isset($arrayCategory[$job_cat_id])){
		$linkcat		= vl_url_category($arrayCategory[$job_cat_id]);
		$cat_name	= $arrayCategory[$job_cat_id]['cat_name'];
	}
   
   $arrLinkCit    = array();
   for($i = 1; $i < 4; $i++){
      if($row['job_cit_'. $i] > 0){
         if(isset($arrayCity[$row['job_cit_'. $i]])){
      		$cityname	= $arrayCity[$row['job_cit_'. $i]]['cit_name'];
      		$linkcity	= createLinkCit($arrayCity[$row['job_cit_'. $i]]);
            $arrLinkCit[] = '<a href="'. $linkcity .'" title="Tìm việc làm tại '. $cityname .'">'. $cityname .'</a>';
      	}
      }
   }
	

	$company_name	= isset($row['job_company_name'])? $row['job_company_name'] : '';
	$linkcompany	= '#';
	if($company_name != ''){
		$data_company	= array('id'=>$row['job_company_id'], 'name' => $company_name);
		$linkcompany	= createLinkCompany($data_company);
	}
   
   $salary_name     = '';
   if($row['job_salary'] > 0 &&  isset($arraySalary[$row['job_salary']])){
      $salary_name = $arraySalary[$row['job_salary']];
   }
   
   /*
   $level_name  = '';
   if($row['job_level_id'] > 0 &&  isset($arrayLevel[$row['job_level_id']])){
      $level_name = $arrayLevel[$row['job_level_id']];
   }
   */
   
   $rank    = '';
   if($row['job_rank'] > 0 && isset($arrayRank[$row['job_rank']])){
      $rank = ' | ' . $arrayRank[$row['job_rank']];
   }
   
   $skill   = '';
   if($row['job_skill'] != ''){
      $arrskill   = explode(',', $row['job_skill']);
      $skill = '<p class="skill">';
      foreach($arrskill as $sk){
         $skill .= '<span>' . trim($sk) . '</span>';
      }
      $skill .= '</p>';
   }else{
      $skill = '<p class="skill">';
         $skill .= '<span>Số lượng: ' . $row['job_number'] . '</span>';
      $skill .= '</p>';
   }
   
   
   
   $is_hot  = (($row['job_packet_id'] > 0 && $row['job_packet_end'] > time()))? 1 : 0;
   $is_supper_hot = ($row['job_packet_id'] == VL_PAYMENT_PACKET_SUPPER_HOT && $row['job_packet_end'] > time())? '<span class="list_hot">HOT</span>' : '';
   
   $htmlTem .= '<div class="job_list '. $style .'">
                  <div class="fr job_more_info">                     
                     '. ( ($salary_name != '')? '<p><span class="btn label">' . $salary_name . '</span></p>' : '' ) .'
                     <p><a class="clgray" href="'. $linkcompany .'" title="Tuyển dụng việc làm tại '. $company_name .'">'. $company_name .'</a></p>
                  </div>
                  <div class="job_info">
                     <p class="pjob"><a target="_blank" class="'. (($is_hot == 1)? 'job_hot' : '') .'" href="'. $linkJob .'" title="'. $job_name .'">'. $job_name .'</a>'. $is_supper_hot .'</p>
                     <p class="pcit">'. implode(', ', $arrLinkCit) . $rank . '</p>  
                     <div class="job_info_item">
                        <div>
                           <p><i class="icon_g flaticon-add182"></i> Đăng tuyển: '. countTime($row['job_date_create']) .'</p>
                           <p><i class="icon_g flaticon-connector3"></i> Hạn nộp: '. date('d/m/Y', $row['job_date_expires']) .'</p>
                        </div>
                        <div>'. $skill .'</div>
                     </div>
                  </div>
         		</div>';
               
   if(isset($myuser) && $myuser->u_id == 11){
      $htmlTem .= '
      <p class="myaction">
         <span onclick="common.set_job_type(this)" data-id="'. $row['job_id'] .'" data-type="supper_hot">Supper_hot</span>
         <span onclick="common.set_job_type(this)" data-id="'. $row['job_id'] .'" data-type="hot">Hot</span>
         <span onclick="common.set_job_type(this)" data-id="'. $row['job_id'] .'" data-type="focus">Tiêu điểm</span>
         <span onclick="common.set_job_type(this)" data-id="'. $row['job_id'] .'" data-type="inter">Hấp dẫn</span>
         <a target="_blank" href="/logtest/modules/jobs/edit.php?record_id='. $row['job_id'] .'">Sửa</a>
         <span>Time: '. date("H:i - d/m/Y", $row['job_date_create']) .'</span>
      </p>';
   }       
   return $htmlTem;
   
}

function templateJob_formobile($row = array()){
   global $arrayCategory, $arrayCity, $arrayJobType, $arraySalary, $arrayLevel, $arrayRank;
   $forcat  = isset($row['forcat'])? intval($row['forcat']) : 0;
   $htmlTem = '';
   $style      = isset($row['style'])? $row['style'] : '';
   $linkJob		= createLinkJob($row);
	$job_name	= clean_title($row['job_name']);
	$job_cat_id	= (isset($row['job_cat_id']) && $row['job_cat_id'] > 0)? $row['job_cat_id'] : 0;
	$linkcat		= '#';
	$cat_name	= '';
	if($job_cat_id > 0 && isset($arrayCategory[$job_cat_id])){
		$linkcat		= vl_url_category($arrayCategory[$job_cat_id]);
		$cat_name	= $arrayCategory[$job_cat_id]['cat_name'];
	}
   
   $arrLinkCit    = array();
   for($i = 1; $i < 4; $i++){
      if($row['job_cit_'. $i] > 0){
         if(isset($arrayCity[$row['job_cit_'. $i]])){
      		$cityname	= $arrayCity[$row['job_cit_'. $i]]['cit_name'];
            $arrLinkCit[] = '<span>'. $cityname .'</span>';
      	}
      }
   }
	

	$company_name	= isset($row['job_company_name'])? $row['job_company_name'] : '';
   
   $salary_name     = '';
   if($row['job_salary'] > 0 &&  isset($arraySalary[$row['job_salary']])){
      $salary_name = $arraySalary[$row['job_salary']];
   }
   
   /*
   $level_name  = '';
   if($row['job_level_id'] > 0 &&  isset($arrayLevel[$row['job_level_id']])){
      $level_name = $arrayLevel[$row['job_level_id']];
   }
   */
   
   $is_hot  = (($row['job_packet_id'] > 0 && $row['job_packet_end'] > time()))? 1 : 0;
   //$is_supper_hot = ($row['job_packet_id'] == VL_PAYMENT_PACKET_SUPPER_HOT && $row['job_packet_end'] > time())? '<span class="list_hot">HOT</span>' : '';
   
   $htmlTem .= '<li class="job_list '. $style .'">
                  <a class="job_info" href="'. $linkJob .'" title="'. $job_name .'">
                     <p class="pjob '. (($is_hot == 1)? 'job_hot' : '') .'">'. $job_name .'</p>
                     <p class="pcom">'. $company_name .'</p>
                     <p class="pcit">'. implode(', ', $arrLinkCit).  ( ($salary_name != '')? '<span class="psal">' . $salary_name . '</span>' : '' ) .'</p>
                  </a>
         		</li>';
   return $htmlTem;
   
}


function templateJob_home($row = array()){
   //global $arraySalary;
   $htmlTem = '';
   $linkJob		= createLinkJob($row);
	$job_name	= clean_title($row['job_name']);
   
   $company_name	= isset($row['job_company_name'])? $row['job_company_name'] : '';
	$linkcompany	= '#';
	if($company_name != ''){
		$data_company	= array('id'=>$row['job_company_id'], 'name' => $company_name);
		$linkcompany	= createLinkCompany($data_company);
	}
   
   /*
   $salary_name     = '';
   if($row['job_salary'] > 0 &&  isset($arraySalary[$row['job_salary']])){
      $salary_name = $arraySalary[$row['job_salary']];
   }
   */
   
   $icon_hot  = (isset($row['job_packet_id']) && $row['job_packet_id'] == VL_PAYMENT_PACKET_SUPPER_HOT)? '<span class="icon_hot">Hot</span>' : '';
   
   $htmlTem .= '<li>
                  <p class="pjob">'. $icon_hot .'<a target="_blank" class="" href="'. $linkJob .'" title="'. $job_name .'">'. $job_name .'</a></p>
                  <p class="pcom"><a class="fsmall" href="'. $linkcompany .'" title="Tuyển dụng tại '. $company_name .'">'. $company_name .'</a></p>
               </li>';
   return $htmlTem;
   
}

function templateJob_share($row = array()){
   //global $arraySalary;
   $htmlTem = '';
   $linkJob		= $row['jos_link'];
	$job_name	= clean_title($row['jos_name']);
   
   $company_name	= isset($row['jos_company'])? $row['jos_company'] : '';
	
   
   /*
   $salary_name     = '';
   if($row['job_salary'] > 0 &&  isset($arraySalary[$row['job_salary']])){
      $salary_name = $arraySalary[$row['job_salary']];
   }
   */
   
   $icon_hot  = (isset($row['job_packet_id']) && $row['job_packet_id'] == VL_PAYMENT_PACKET_SUPPER_HOT)? '<span class="icon_hot">Hot</span>' : '';
   
   $htmlTem .= '<li>
                  <p class="pjob">'. $icon_hot .'<a  rel="nofollow" target="_blank" class="" href="'. $linkJob .'" title="'. $job_name .'">'. $job_name .'</a></p>
                  <p class="pcom"><span class="fsmall" title="Tuyển dụng tại '. $company_name .'">'. $company_name .'</span></p>
               </li>';
   return $htmlTem;
   
}

function templateUser($ruser = array()){
   global $arrayCategory, $arrayCity;
   $htmlTem    = '';
   $linkUser   = createLinkUser($ruser);
   $user_name  = $ruser['use_fullname'];         
   
	$cityname		= '';
	$linkcity		= '#';
   if($ruser['use_city_id'] > 0 && isset($arrayCity[$ruser['use_city_id']])){
      $cityname   = $arrayCity[$ruser['use_city_id']]['cit_name'];
      $linkcity = '/nguoi-tim-viec/?ci='. $ruser['use_city_id'];
   } 

	$company_name	= '';
	$linkcompany	= '';
	if($ruser['use_com_id'] > 0){
	   $db_company  = new db_query("SELECT * FROM company WHERE com_id = " . intval($ruser['use_com_id']) . " LIMIT 1", __FILE__ . " Line: " . __LINE__);
      if($rcom = mysqli_fetch_assoc($db_company->result)){
         $company_name   = $rcom['com_name'];
         $data_company	= array('id'=>$rcom['com_id'], 'name' => $company_name);
         $linkcompany	= createLinkCompany($data_company);
      }
      unset($db_company);
	}
   
   $linkSpeciallized = '';
   $specialized   = '';
   if($ruser['use_cat_id'] > 0 && isset($arrayCategory[$ruser['use_cat_id']])){
      $specialized   = $arrayCategory[$ruser['use_cat_id']]['cat_name'];
      $linkSpeciallized = createLink_catuser($arrayCategory[$ruser['use_cat_id']]);            
   }
   
   
   $linkSchool    = '';
   $school_name   = '';
   if($ruser['use_school_id'] > 0){
      $db_school  = new db_query("SELECT * FROM school WHERE sch_id = " . intval($ruser['use_school_id']) . " LIMIT 1", __FILE__ . " Line: " . __LINE__);
      if($rschool = mysqli_fetch_assoc($db_school->result)){
         $school_name   = $rschool['sch_name'];
         $linkSchool    = createLinkSchool($rschool);
      }
      unset($db_school);
   }
   
   $htmlTem .= '<div class="user_list">
      				<div class="user_info">
                     <p class="user_avatar"><a href="'. $linkUser .'" title="Nhân sự '. $user_name .'" target="_blank"><img src="'. $ruser['use_avatar'] .'" alt="Nhân sự '. $user_name .'" /></a></p>
      					<h2><a class="ovtext" href="'. $linkUser .'" title="Hồ sơ nhân sự '. $user_name .'">'. $user_name .'</a></h2>
      					<p class="user_branch ovtext">'. (($ruser['use_branch'] != '')? $ruser['use_branch'] .' - ' : '') .'<span class="join_time">'. date('d/m/Y', $ruser['use_date_join']) .'</span></p>
                     <p class="user_branch ovtext">'. (($linkSpeciallized != '')? '<a href="'. $linkSpeciallized .'" title="'. $specialized .'">'. $specialized .'</a>' : '') .'</p>
      				</div>
                  <div class="user_commom">
                     '. (($linkSchool != '')? '<p><a href="'. $linkSchool .'" title="Trường '. $school_name .'">Học tại: '. $school_name .'</a></p>' : '') 
                    . (($linkcompany != '')? '<p><a href="'. $linkcompany .'" title="Tuyển dụng tại '. $company_name .'">Đang làm: '. $company_name .'</a></p>' : '') .'
                  </div>
      				<div class="user_city">
      					'. (($cityname != '')? '<a class="ovtext" href="'. $linkcity .'" title="Tìm ứng viên, Người tìm việc tại '. $cityname .'">'. $cityname .'</a>' : '') .'                     
      				</div>
      			</div>';
   return $htmlTem;
}

/**
 * Hiển thị việc làm fb
 */
function template_job_fb($data   = array(), $option = array()){
   
   global $arrayCity;
   
   $show_group = isset($option['show_group'])? intval($option['show_group']) : 1;
   $keyword    = isset($option['key'])? ($option['key']) : '';
   
   $uname   = isset($data['jobf_use_name'])? $data['jobf_use_name'] : '';
   $uavatar = generate_avatar($data['jobf_use_fbid']);
   $gname   = isset($data['jobf_gro_name'])? $data['jobf_gro_name'] : '';
   $linkuser   = create_link_job_user_fb($data);
   
   $data['jobf_message'] = str_replace(' : ', ':', $data['jobf_message']);
   $data['jobf_message'] = strip_tags($data['jobf_message']);
   
   $arraygr = array(
      'id' => $data['jobf_gro_id'],
      'name' => $gname
   );
   $linkGroup  = create_link_group_fb($arraygr);
   $link_jobfb = create_link_job_fb($data);
   
   $title_job  = mb_substr($data['jobf_message'], 0, 80, 'UTF-8');
   
   $message = mb_substr($data['jobf_message'], 0, 300, 'UTF-8');
   if($keyword != ''){
      if(strpos($message, $keyword) !== false){
         $message = str_replace($keyword, '<b style="background-color: yellow;">'. $keyword .'</b>', $message);
      }else{
         $arrkey = explode(' ', $keyword);
         if(!empty($arrkey)){
            foreach($arrkey as $k){
               $message = str_replace($k, '<b style="background-color: yellow;">'. $k .'</b>', $message);
            }
         }
      }
   }
   
  $message .= '... <a class="fb_view_detail" title="xem thêm" href="'. $link_jobfb .'">Xem thêm</a>';
   
   $arr_city   = array();
   for($i = 1;$i <= 3;$i++){
      if(isset($data['jobf_cit_' . $i]) && $data['jobf_cit_' . $i] > 0){
         if(isset($arrayCity[$data['jobf_cit_' . $i]])){
            $arr_city[] = $arrayCity[$data['jobf_cit_' . $i]]['cit_name'];
         }
      }
   }
   
   $citname = '';
   if(!empty($arr_city)) $citname = ' - Tại: ' . implode(', ', $arr_city);
   
   $salary  = (isset($data['jobf_salary']) && $data['jobf_salary'] > 0)? '<p class="fb_salary"><b>'. format_number($data['jobf_salary']) .' </b>₫</p>' : '';
   
   $html_group = '';
   if($show_group == 1){
      $html_group = '<span class="fb_via"></span><a  href="'. $linkGroup .'" title="'. $gname .'">'. $gname .'</a>';
   }
   
   //<p class="fb_avatar"><a  href="'. $linkuser .'" title="'. $uname .'"><img src="'. $uavatar .'" alt="" /></a></p>
   
   $html = '';
   $html .= '<div class="fb_item">';
      $html .= '<div class="fb_head">                  
                  <div class="fb_name">
                     <a  href="'. $linkuser . '" title="">'. $uname .'</a>
                     '. $html_group .'
                     
                     <p class="fb_info">
                        <span class="fb_time">'. countTime($data['jobf_date']) .'</span>
                        <span class="fb_time">'. $citname .'</span>
                        <a class="fb_link_detail" href="'. $link_jobfb .'" title="'. $title_job .'">Chi tiết</a>
                     </p>
                  </div>
                </div>';
      $html .= '<div class="fb_body">
                  '. $salary . nl2br($message) .'
                </div>';
   $html .= '</div>';
   return $html;
}