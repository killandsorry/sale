<meta charset="utf-8" />
<?

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

require_once '../../../class/simple_html_dom.php';
function get_vieclameva($url){
   
   global $arrSalary_vieclameva, $arrLevel_vieclameva,$arrExper_vieclameva,$arrGender_vieclameva,$arrJobtype_vieclameva,$arrayJobRank;
   
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
      'phone' => ''
   );
   $html = file_get_html($url);
   
   $title   = trim($html->find('h1', 0)->plaintext);
   
   $short_des  = trim($html->find('.col-md-9', 0)->plaintext);
   
   $policy  = trim($html->find('.col-md-9', 1)->plaintext);
   
   $require  = trim($html->find('.col-md-9', 2)->plaintext);
   
   $profile = trim($html->find('.col-md-9', 3)->plaintext);
   
   
   
   
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
      'name' => $name
   );
   return $result;
}


$url  = 'https://vieclam24h.vn/dich-vu/nhan-vien-ban-parttime-or-fulltime-lam-viec-tai-chuoi-nhat-ban-c7p0id2233165.html';

$html = get_vieclameva($url);
echo '<pre>';
print_r($html);