<?




function get_vieclameva($url){
   
   $arrJobtype_vieclameva = array(
   'Nhân viên chính thức' => 1,
   'Nhân viên thời vụ' => 4,
   'Bán thời gian' => 3,
   'Làm thêm ngoài giờ' => 2,
   'Thực tập và dự án' => 5
);

$arrGender_vieclameva = array(
   'Nữ' => 2
);

$arrExper_vieclameva = array(
   'Chưa có kinh nghiệm' => 11,
   'Dưới 1 năm' => 11,
   '1 năm' => 10,
   '2 năm' => 9,
   '3 năm' => 8,
   '4 năm' => 7,
   '5 năm' => 6,
   'Trên 5 năm' => 5
);

$arrSalary_vieclameva = array(
   '7-10 triệu' => 6,
   '10-12 triệu' => 5,
   '12-15 triệu' => 5,
   '15-20 triệu' => 4,
   '20-25 triệu' => 3,
   '25-30 triệu' => 2,
   'Trên 30 triệu' => 1
);

$arrLevel_vieclameva = array(
   'Đại học' => 2,
   'Cao đẳng' => 3,
   'Trung cấp' => 4,
   'Cao học' => 1,
   'Trung học' => 6,
   'Chứng chỉ' => 4,
   'Lao động phổ thông' => 6,
   'Không yêu cầu' => 7
);
   
   #return
   $result  = array(
      'title' => '',
      'short_des' => '',
      'policy' => '',
      'require' => '',
      'profile' => '',
      'number' => '',
      'level' => '',
      'job_type' => '',
      'salary' => '',
      'exper' => '',
      'gender' => '',
      'expires' => '',
      'email' => '',
      'name' => '',
      'phone' => '',
      'add' => ''
   );
   $html = file_get_html($url);
   $title   = trim($html->find('h1', 0)->plaintext);
   $short_des  = trim($html->find('.block-content table tr td', 1)->plaintext);
   $policy  = trim($html->find('.block-content table tr td', 5)->plaintext);
   $require  = trim($html->find('.block-content table tr td', 3)->plaintext);
   
   
   $number  = '';
   $level  = '';
   $job_type  = '';
   $salary  = '';
   $exper  = '';
   $gender  = 3;
   $expires = trim($html->find('.block-content table tr td', 9)->plaintext);
   
   foreach($html->find('.no-style li') as $vl){
      $vl = $vl->plaintext;
      if(strpos($vl, 'Số lượng tuyển dụng') !== false){
         $number = intval(str_replace('- Số lượng tuyển dụng:', '', $vl));
      }
      
      if(strpos($vl, 'Giới tính:') !== false){
         $gen = (str_replace('- Giới tính:', '', $vl));
         if(isset($arrGender_vieclameva[$gen])) $gender = $arrGender_vieclameva[$gen];
         
      }
      
      if(strpos($vl, 'Hình thức làm việc') !== false){
         $job = (str_replace('- Hình thức làm việc:', '', $vl));
         if(isset($arrJobtype_vieclameva[$job])) $job_type = $arrJobtype_vieclameva[$job];
         
      }
      
      if(strpos($vl, 'Mức lương:') !== false){
         $sal  = trim(str_replace('- Mức lương:', '', $vl));
         if(isset($arrSalary_vieclameva[$sal])) $salary = $arrSalary_vieclameva[$sal];
      }
      
      if(strpos($vl, 'Kinh nghiệm') !== false){
         $ex = (str_replace('- Kinh nghiệm:', '', $vl));
         if(isset($arrExper_vieclameva[$ex])) $exper = $arrExper_vieclameva[$ex];
      }
      
      if(strpos($vl, 'Trình độ:') !== false){         
         $lev = intval(str_replace('- Trình độ:', '', $vl));
         if(isset($arrLevel_vieclameva[$lev])) $level = $arrLevel_vieclameva[$lev];
      }
   }
   
   $result  = array(
      'title' => $title,
      'short_des' => $short_des,
      'policy' => $policy,
      'require' => $require,
      'number' => $number,
      'level' => $level,
      'job_type' => $job_type,
      'salary' => $salary,
      'exper' => $exper,
      'gender' => $gender,
      'expires' => str_replace('-', '/', $expires)
   );
   return $result;
}


function get_vieclam24h($url){
   
   $arrJobtype_vieclameva = array(
      'Toàn thời gian cố định' => 1,
      'Toàn thời gian tạm thời' => 4,
      'Bán thời gian cố định' =>  3,
      'Bán thời gian tạm thời' => 4,
      'Theo hợp đồng tư vấn' => 4,
      'Thực tập' => 5,
      'Khác' =>  6
   );
   
   $arrGender_vieclameva = array(
      'Nữ' => 2,
      'Nam' => 1,
      'Không yêu cầu' => 3
   );
   
   $arrExper_vieclameva = array(
      'Dưới 1 năm' => 11,
      '1 năm' => 10,
      '2 năm' => 9,
      '3 năm' => 8,
      '4 năm' => 7,
      '5 năm' => 6,
      'Hơn 5 năm' => 5
   );
   
   $arrSalary_vieclameva = array(
      '1 - 3 triệu' => 12,
      '3 - 5 triệu' => 11,
      '5 - 7 triệu' => 8,
      '7 – 10 triệu' => 6,
      '10 - 15 triệu' => 5,
      '15 - 20 triệu' => 4,
      '20 - 30 triệu' => 2,
      'Trên 30 triệu' => 1
   );
   
   $arrLevel_vieclameva = array(
      'Đại học' => 2,
      'Cử nhân' => 2,
      'Cao đẳng' => 3,
      'Trung cấp' => 4,
      'Sau đại học' => 1,
      'Thạc sĩ' => 1,
      'Tiến sĩ' => 1,
      'Trung học' => 6,
      'Chứng chỉ' => 4,
      'Lao động phổ thông' => 6,
      'Không yêu cầu bằng cấp' => 7
   );
   
   $arrayJobRank	= array(
      'Quản lý cấp cao' => 6,
      'Quản lý cấp trung' => 5,
      'Quản lý nhóm - giám sát' => 4,
      'Chuyên gia' => 3,
      'Chuyên viên - Nhân viên' => 2,
      'Cộng tác viên' => 1
   );
   
   $result  = array(
      'title' => '',
      'short_des' => '',
      'policy' => '',
      'require' => '',
      'profile' => '',
      'number' => '',
      'level' => '',
      'job_type' => '',
      'salary' => '',
      'exper' => '',
      'gender' => '',
      'expires' => '',
      'email' => '',
      'name' => '',
      'phone' => '',
      'add' => ''
   );
   $html = file_get_html($url);
   
   $title   = trim($html->find('h1', 0)->plaintext);
   
   $short_des  = trim($html->find('.col-md-9', 0)->plaintext);
   
   $policy  = trim($html->find('.col-md-9', 1)->plaintext);
   
   $require  = trim($html->find('.col-md-9', 2)->plaintext);
   
   $profile = trim($html->find('.col-md-9', 3)->plaintext);
   $profile = str_replace('Vieclam24h.vn', '1viec.com', $profile);
   
   
   
   $number  = 1;
   $level   = '';   
   $job_type  = '';
   $salary  = '';
   $exper  = '';
   $gender = '';
   $expires = '';
   $expires = trim($html->find('.line-icon .text_pink', 0)->plaintext);
   
   
   foreach($html->find('.col-xs-6 .pl_28') as $vl){
      $vl = $vl->plaintext;
            
      if(strpos($vl, 'Số lượng cần tuyển:') !== false){
         $number = intval(str_replace('Số lượng cần tuyển:', '', $vl));
      }
      
      if(strpos($vl, 'Yêu cầu bằng cấp:') !== false){
         $lev = trim(str_replace('Yêu cầu bằng cấp:', '', $vl));
         //return $lev;
         if(isset($arrLevel_vieclameva[$lev])) $level = $arrLevel_vieclameva[$lev];
      }
      
      if(strpos($vl, 'Yêu cầu giới tính:') !== false){
         $gen = trim(str_replace('Yêu cầu giới tính:', '', $vl));
         if(isset($arrGender_vieclameva[$gen])) $gender = $arrGender_vieclameva[$gen];
         
      }
      
      if(strpos($vl, 'Hình thức làm việc:') !== false){
         $job = trim(str_replace('Hình thức làm việc:', '', $vl));
         if(isset($arrJobtype_vieclameva[$job])) $job_type = $arrJobtype_vieclameva[$job];
         
      }
      
      if(strpos($vl, 'Mức lương:') !== false){
         $sal  = trim(str_replace('Mức lương:', '', $vl));         
         if(isset($arrSalary_vieclameva[$sal])) $salary = $arrSalary_vieclameva[$sal];
      }
      
      if(strpos($vl, 'Kinh nghiệm:') !== false){
         $ex = trim(str_replace('Kinh nghiệm:', '', $vl));
         if(isset($arrExper_vieclameva[$ex])) $exper = $arrExper_vieclameva[$ex];
      }
      
   }
   $name = '';
   $email = '';
   $phone = '';
   $add  = '';
   
   foreach($html->find('.item') as $vl){
      $vl = $vl->plaintext;
            
      if(strpos($vl, 'Người liên hệ') !== false){
         $name = trim(str_replace('Người liên hệ', '', $vl));
      }
      
      if(strpos($vl, 'Email liên hệ') !== false){
         $email = trim(str_replace('Email liên hệ', '', $vl));
      }
      
      if(strpos($vl, 'Điện thoại liên hệ') !== false){
         $phone = trim(str_replace('Điện thoại liên hệ', '', $vl));
      }
      
      if(strpos($vl, 'Địa chỉ liên hệ') !== false){
         $phone = trim(str_replace('Địa chỉ liên hệ', '', $vl));
      }
            
   }
   
   
   
   
   $result  = array(
      'title' => $title,
      'short_des' => $short_des,
      'policy' => $policy,
      'require' => $require,
      'require' => $require,
      'profile' => $profile,
      'number' => $number,
      'level' => $level,
      'job_type' => $job_type,
      'salary' => $salary,
      'exper' => $exper,
      'gender' => $gender,
      'expires' => str_replace('-', '/', $expires),
      'email' => $email,
      'phone' => $phone,
      'name' => $name,
      'add' => $add
   );
   return $result;
}


function get_timviecnhanh($url){
   
   $arrJobtype_vieclameva = array(
   'Nhân viên chính thức' => 1,
   'Nhân viên thời vụ' => 4,
   'Bán thời gian' => 3,
   'Làm thêm ngoài giờ' => 2,
   'Thực tập và dự án' => 5
);

$arrGender_vieclameva = array(
   'Nữ' => 2
);

$arrExper_vieclameva = array(
   'Chưa có kinh nghiệm' => 11,
   'Dưới 1 năm' => 11,
   '1 năm' => 10,
   '2 năm' => 9,
   '3 năm' => 8,
   '4 năm' => 7,
   '5 năm' => 6,
   'Trên 5 năm' => 5
);

$arrSalary_vieclameva = array(
   '7-10 triệu' => 6,
   '10-12 triệu' => 5,
   '12-15 triệu' => 5,
   '15-20 triệu' => 4,
   '20-25 triệu' => 3,
   '25-30 triệu' => 2,
   'Trên 30 triệu' => 1
);

$arrLevel_vieclameva = array(
   'Đại học' => 2,
   'Cao đẳng' => 3,
   'Trung cấp' => 4,
   'Cao học' => 1,
   'Trung học' => 6,
   'Chứng chỉ' => 4,
   'Lao động phổ thông' => 6,
   'Không yêu cầu' => 7
);
   
   #return
   $result  = array(
      'title' => '',
      'short_des' => '',
      'policy' => '',
      'require' => '',
      'profile' => '',
      'number' => '',
      'level' => '',
      'job_type' => '',
      'salary' => '',
      'exper' => '',
      'gender' => '',
      'expires' => '',
      'email' => '',
      'name' => '',
      'phone' => '',
      'add' => ''
   );
   $html = file_get_html($url);
   $title   = trim($html->find('h1', 0)->plaintext);
   $short_des  = trim($html->find('.block-content table tr td', 1)->plaintext);
   $policy  = trim($html->find('.block-content table tr td', 5)->plaintext);
   $require  = trim($html->find('.block-content table tr td', 3)->plaintext);
   
   
   $number  = '';
   $level  = '';
   $job_type  = '';
   $salary  = '';
   $exper  = '';
   $gender  = 3;
   $expires = trim($html->find('.block-content table tr td', 9)->plaintext);
   
   foreach($html->find('.no-style li') as $vl){
      $vl = $vl->plaintext;
      if(strpos($vl, 'Số lượng tuyển dụng') !== false){
         $number = intval(str_replace('- Số lượng tuyển dụng:', '', $vl));
      }
      
      if(strpos($vl, 'Giới tính:') !== false){
         $gen = (str_replace('- Giới tính:', '', $vl));
         if(isset($arrGender_vieclameva[$gen])) $gender = $arrGender_vieclameva[$gen];
         
      }
      
      if(strpos($vl, 'Hình thức làm việc') !== false){
         $job = (str_replace('- Hình thức làm việc:', '', $vl));
         if(isset($arrJobtype_vieclameva[$job])) $job_type = $arrJobtype_vieclameva[$job];
         
      }
      
      if(strpos($vl, 'Mức lương:') !== false){
         $sal  = trim(str_replace('- Mức lương:', '', $vl));
         if(isset($arrSalary_vieclameva[$sal])) $salary = $arrSalary_vieclameva[$sal];
      }
      
      if(strpos($vl, 'Kinh nghiệm') !== false){
         $ex = (str_replace('- Kinh nghiệm:', '', $vl));
         if(isset($arrExper_vieclameva[$ex])) $exper = $arrExper_vieclameva[$ex];
      }
      
      if(strpos($vl, 'Trình độ:') !== false){         
         $lev = intval(str_replace('- Trình độ:', '', $vl));
         if(isset($arrLevel_vieclameva[$lev])) $level = $arrLevel_vieclameva[$lev];
      }
   }
   
   $result  = array(
      'title' => $title,
      'short_des' => $short_des,
      'policy' => $policy,
      'require' => $require,
      'number' => $number,
      'level' => $level,
      'job_type' => $job_type,
      'salary' => $salary,
      'exper' => $exper,
      'gender' => $gender,
      'expires' => str_replace('-', '/', $expires)
   );
   return $result;
}




function get_tuyendungcomvn($url){
   
   $arrJobtype_vieclameva = array(
      'Toàn thời gian cố định' => 1,
      'Bán thời gian tạm thời' => 4,
      'Theo hợp đồng/tư vấn' => 4,
      'Bán thời gian cố định' => 3,
      'Khác' => 6,
      'Thực tập' => 5
   );

   
   $arrGender_vieclameva = array(
      'Nữ' => 2,
      'Nam' => 1,
      'Tất cả' => 3
   );
   
   $arrExper_vieclameva = array(
      'Chưa có kinh nghiệm' => 11,
      'Dưới 1 năm' => 11,
      '1 năm' => 10,
      '2 năm' => 9,
      '3 năm' => 8,
      '4 năm' => 7,
      '5 năm' => 6,
      'Trên 5 năm' => 5
   );
   
   $arrSalary_vieclameva = array(
      '7-10 triệu' => 6,
      '10-12 triệu' => 5,
      '12-15 triệu' => 5,
      '15-20 triệu' => 4,
      '20-25 triệu' => 3,
      '25-30 triệu' => 2,
      'Trên 30 triệu' => 1
   );
   
   $arrLevel_vieclameva = array(
      'Đại học' => 2,
      'Cử nhân' => 2,
      'Cao đẳng' => 3,
      'Trung cấp' => 4,
      'Sau đại học' => 1,
      'Thạc sĩ' => 1,
      'Tiến sĩ' => 1,
      'Trung học' => 6,
      'Chứng chỉ' => 4,
      'Lao động phổ thông' => 6,
      'Không yêu cầu' => 7
   );
      
   #return
   $result  = array(
      'title' => '',
      'short_des' => '',
      'policy' => '',
      'require' => '',
      'profile' => '',
      'number' => '',
      'level' => '',
      'job_type' => '',
      'salary' => '',
      'exper' => '',
      'gender' => '',
      'expires' => '',
      'email' => '',
      'name' => '',
      'phone' => '',
      'add' => ''
   );
   $html = file_get_html($url);
   $title   = trim($html->find('#ctl00_ContentPlaceHolder1_lblJobTitle', 0)->plaintext);
   $short_des  = trim($html->find('#ctl00_ContentPlaceHolder1_lblJobDescription', 0)->plaintext);
   $policy  = trim($html->find('#ctl00_ContentPlaceHolder1_lblBenefits', 0)->plaintext);
   $require  = trim($html->find('#ctl00_ContentPlaceHolder1_lblJobSkills', 0)->plaintext);
   $profile = trim($html->find('#ctl00_ContentPlaceHolder1_lblProfileConsist', 0)->plaintext);
   
   $number  = trim($html->find('#ctl00_ContentPlaceHolder1_lblReqNumber', 0)->plaintext);
   $level  = trim($html->find('#ctl00_ContentPlaceHolder1_lblMinEdutcation', 0)->plaintext);
   $job_type  = trim($html->find('#ctl00_ContentPlaceHolder1_lblWorkingType', 0)->plaintext);
   $salary  = '';
   $exper  = '';
   $gen  = trim($html->find('#ctl00_ContentPlaceHolder1_lblGender', 0)->plaintext);
   if(isset($arrGender_vieclameva[$gen])) $gender = $arrGender_vieclameva[$gen];
   $expires = trim($html->find('#ctl00_ContentPlaceHolder1_lblClosedDate', 0)->plaintext);
   
   
   $result  = array(
      'title' => $title,
      'short_des' => $short_des,
      'policy' => $policy,
      'require' => $require,
      'profile' => $profile,
      'number' => $number,
      'level' => $level,
      'job_type' => $job_type,
      'salary' => $salary,
      'exper' => $exper,
      'gender' => $gender,
      'expires' => str_replace('-', '/', $expires)
   );
   return $result;
}


function get_mywork($url){
   
   $arrJobtype_vieclameva = array(
      'Toàn thời gian cố định' => 1,
      'Bán thời gian tạm thời' => 4,
      'Theo hợp đồng / tư vấn' => 4,
      'Bán thời gian cố định' => 3,
      'Khác' => 6,
      'Thực tập' => 5
   );

   
   $arrGender_vieclameva = array(
      'Nữ' => 2,
      'Nam' => 1,
      'Tất cả' => 3
   );
   
   $arrExper_vieclameva = array(
      'Chưa có kinh nghiệm' => 11,
      'Dưới 1 năm' => 11,
      '1 năm' => 10,
      '2 năm' => 9,
      '3 năm' => 8,
      '4 năm' => 7,
      '5 năm' => 6,
      'Trên 5 năm' => 5
   );
   
   $arrSalary_vieclameva = array(
      '1-3 triệu' => 12,
      '3-5 triệu' => 11,
      '5-7 triệu' => 8,
      '7-10 triệu' => 6,
      '12-15 triệu' => 5,
      '15-20 triệu' => 4,
      '20-25 triệu' => 3,
      '25-30 triệu' => 2,
      '30 triệu trở lên' => 1
   );
   
   $arrLevel_vieclameva = array(
      'Đại học' => 2,
      'Cử nhân' => 2,
      'Cao đẳng' => 3,
      'Trung cấp' => 4,
      'Sau đại học' => 1,
      'Thạc sĩ' => 1,
      'Tiến sĩ' => 1,
      'Trung học' => 6,
      'Chứng chỉ' => 4,
      'Lao động phổ thông' => 6,
      'Không yêu cầu' => 7
   );
   
   #return
   $result  = array(
      'title' => '',
      'short_des' => '',
      'policy' => '',
      'require' => '',
      'profile' => '',
      'number' => '',
      'level' => '',
      'job_type' => '',
      'salary' => '',
      'exper' => '',
      'gender' => '',
      'expires' => '',
      'email' => '',
      'name' => '',
      'phone' => '',
      'add' => ''
   );
   $html = file_get_html($url);
   
   $title   = trim($html->find('.title-job-info', 0)->plaintext);
   $short_des  = trim($html->find('.desjob-company', 4)->plaintext);
   $short_des  = trim(str_replace('Mô tả công việc', '', $short_des));
   $short_des  = removeLink($short_des);
   
   $policy  = trim($html->find('.desjob-company', 5)->plaintext);
   $policy  = trim(str_replace('Quyền lợi được hưởng', '', $policy));
   $policy  = removeLink($policy);
   
   $require  = trim($html->find('.desjob-company', 6)->plaintext);
   $require  = trim(str_replace('Yêu cầu công việc', '', $require));
   $require  = removeLink($require);
   
   $profile = trim($html->find('.desjob-company', 7)->plaintext);
   $profile  = trim(str_replace('Yêu cầu hồ sơ', '', $profile));
   $profile  = removeLink($profile);
   
   
   $number  = 1;
   $level   = '';
   $lev  = trim($html->find('.desjob-company', 4)->plaintext);; //desjob-company
   $lev  = trim(str_replace('Trình độ học vấn', '', $lev));
   if(isset($arrLevel_vieclameva[$lev])) $level = $arrLevel_vieclameva[$lev];
   
   $job_type  = '';
   $salary  = '';
   $exper  = '';
   $gender = '';
   $expires = trim($html->find('.desjob-company', 8)->plaintext);
   $expires  = trim(str_replace('Hạn nộp hồ sơ', '', $expires));;
   
   
   foreach($html->find('.mw-ti') as $vl){
      $vl = $vl->plaintext;
      if(strpos($vl, 'Số lượng:') !== false){
         $number = intval(str_replace('Số lượng:', '', $vl));
      }
      
      
      if(strpos($vl, 'Loại hình công việc:') !== false){
         $job = (str_replace('Loại hình công việc:', '', $vl));
         if(isset($arrJobtype_vieclameva[$job])) $job_type = $arrJobtype_vieclameva[$job];
         
      }
      
   }
   
   
   $result  = array(
      'title' => $title,
      'short_des' => $short_des,
      'policy' => $policy,
      'require' => $require,
      'require' => $require,
      'profile' => $profile,
      'number' => $number,
      'level' => $level,
      'job_type' => $job_type,
      'salary' => $salary,
      'exper' => $exper,
      'gender' => $gender,
      'expires' => str_replace('-', '/', $expires)
   );
   return $result;
}