<?
require_once("../../resource/security/security.php");
$module_id		= 3;
$module_name	= "Jobs";
$msgError		= "";
checkLogged();
//Declare prameter when insert data
$fs_table		= "job";
$id_field		= "job_id";
$name_field		= "job_name";
$file_path     = '../../../upload/avatar/';

// array packet
$array_packet  = array(
   1 => array(
      'name' => 'hấp dẫn',
      'price' => '20000',
      'default' => 0,
      'right' => array(
         'home_hightlight' => 0,
         'home_new' => 0,
         'home_interesting' => 1,
         'job_red' => 1,
         'job_icon' => 0,
         'job_cat_focus' => 0,
         'job_cat_intersting' => 1,
         'job_detail_care' => 0
      )
   ),
   2 => array(
      'name' => 'tiêu điểm ngành',
      'price' => '30000',
      'default' => 0,
      'right' => array(
         'home_hightlight' => 0,
         'home_new' => 0,
         'home_interesting' => 0,
         'job_red' => 1,
         'job_icon' => 0,
         'job_cat_focus' => 1,
         'job_cat_intersting' => 0,
         'job_detail_care' => 0
      )
   ),
   3 => array(
      'name' => 'hot',
      'price' => '50000',
      'default' => 1,
      'right' => array(
         'home_hightlight' => 1,
         'home_new' => 0,
         'home_interesting' => 0,
         'job_red' => 1,
         'job_icon' => 0,
         'job_cat_focus' => 1,
         'job_cat_intersting' => 0,
         'job_detail_care' => 0
      )
   ),
   4 => array(
      'name' => 'siêu hot',
      'price' => '100000',
      'default' => 0,
      'right' => array(
         'home_hightlight' => 1,
         'home_new' => 1,
         'home_interesting' => 0,
         'job_red' => 1,
         'job_icon' => 1,
         'job_cat_focus' => 1,
         'job_cat_intersting' => 0,
         'job_detail_care' => 1
      )
   )
);

$array_web_id  = array(
   'tuyendung.com.vn' => 1,
   'vieclameva.com' => 2,
   'timviecnhanh.com' => 3,
   'mywork.com.vn' => 4,
   'vieclam24h.vn' => 5
);

$arrayCategory	= array(0 => 'Chọn danh mục');
$arrayCity		= array(0 => 'Chọn tỉnh thành');

$db_cat	= new db_query("SELECT * FROM category WHERE cat_active = 1 AND cat_type = 'vl'", __FILE__ . " Line: " . __LINE__);
while($rcat	= mysqli_fetch_assoc($db_cat->result)){
	$arrayCategory[$rcat['cat_id']]	= $rcat['cat_name'];
}
unset($db_cat);

$db_cit	= new db_query("SELECT * FROM city WHERE cit_active = 1", __FILE__ . " Line: " . __LINE__);
while($rcit	= mysqli_fetch_assoc($db_cit->result)){
	$arrayCity[$rcit['cit_id']]	= $rcit['cit_name'];
}
unset($db_cit);

$arrayLevel			= generate_job_level();
$arrayJobType		= generate_job_type();
$arrayExperience	= generate_job_experience();
$arraySalary		= generate_job_salary();
$arrayGender		= generate_job_gender();
$arrayRank        = generate_job_rank();

$arrayFillIp		= array(
	0 => 'Tất cả',
	1 => 'Tin tự động',
	2 => 'Tin đăng tay'
);
$arrayFillHot		= array(
	-1 => 'Tất cả',
	1 => 'Hot',
   2 => 'Supper hot'
);
$arrayTypeJob  = array(
   -1 => 'Tất cả',
   0 => 'Tin tự động',
   1 => 'Người dùng đăng'
);
?>