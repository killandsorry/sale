<?
$users = array('admin' => 'muahe');
$realm	= "Thong tin tai khoan";
function check_authen($recheck = 0){
	global $realm;
	if (empty($_SERVER['PHP_AUTH_DIGEST']) || $recheck == 1) {
	    header('HTTP/1.1 401 Unauthorized');
	    header('WWW-Authenticate: Digest realm="'.$realm.
	           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');
	    die('Truy cap bi tu choi');
	}
}
// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}

check_authen();

// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
    !isset($users[$data['username']])){
    	check_authen(1);
		die("Xin loi ban ko co quyen");
    }
// generate the valid response
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response){
		check_authen(1);
		die("Xin loi ban ko co quyen");
}

if($_SERVER["SERVER_NAME"] != 'localhost' && $_SERVER["SERVER_NAME"] != '1viec.com'){
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://1viec.com" . $_SERVER['REQUEST_URI']);
   exit();
}
/*
if($_SERVER["SERVER_NAME"] != 'localhost'){
   if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
       $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
       header('HTTP/1.1 301 Moved Permanently');
       header('Location: ' . $redirect);
       exit();
   }
}
*/

// requer class, function
require_once ('class/vl_database.php');
require_once ('class/vl_user.php');
require_once ('class/vl_class.php');
require_once ('class/mobile.php');
require_once ('class/form.php');
require_once ('class/generate_form.php');
require_once ('class/suggestionKeyword.php');
require_once ('class/sphinxQL.php');
require_once ('fn/vl_function.php');
require_once ('fn/vl_profile.php');
require_once ('fn/vl_rewrite.php');
require_once ('fn/vl_template.php');
require_once ('fn/vl_cv.php');
require_once ('config_profile.php');

$protocol   = 'https:';

// call class user
$myuser			= new user();
$vl_class      = new vl_class();
$mobile        = new Mobile_Detect();

$webname			= '1viec.com'; // tên website
$slogan			= 'Tìm việc làm, Tuyển dụng nhân sự việt, Tìm kiếm tài năng việt';
$path_root		= '/'; // đường dẫn root
$path_themes	= '/themes/'; // đường dẫn themes
$path_img_cv   = '/upload/'; // đường dẫn avatar
$path_avatar	= '/pictures/ava/'; // avatar company
$path_fillter	= '/tim-kiem-viec-lam';
$path_ntd      = '/nha-tuyen-dung-viec-lam-hang-dau';
$path_24h      = '/tim-viec-lam-24h';
$path_tvl      = $path_root;
$path_uv       = '/nguoi-tim-viec';
$path_ct       = '/tim-viec-lam-theo-tinh-thanh';
$path_luongcao = '/tim-viec-lam-luong-cao';
$path_dangtin  = '/dang-tin-tuyen-dung-tim-viec-lam';
$path_employer = '/employer';
$path_edit_cv  = '/cv/tao-cv-online';
$path_cv_tem   = '/cv/cv-mau';
$path_cv_download   = '/cv/download_cv';
$path_cv_view   = '/cv/view_cv';
$path_profile  = $path_root . 'profile/';
$path_timviecnhanh = '/tim-viec-nhanh';
$thumb_fb      = $protocol . '//' . $webname . $path_themes . 'image/bg_fb_2.jpg';
$current_id    = 0;
$process       = isset($myuser->useField['use_process'])? $myuser->useField['use_process'] : 0; // tiến trình hoàn thành hồ sơ
$is_file_cv    = (isset($myuser->useField['use_cv_name']) && $myuser->useField['use_cv_name'] != '')? 1 : 0; // tiến trình hoàn thành hồ sơ


// cookie name
// cat_near_here
$cat_near_here = 'cat_near_here';

// version themes
$version	= 116;

// mảng css
$arrCss	= array(
	'common.css',
	//'ntv.css',
	'flaticon.css'
);
// mảng js
$arrJs	= array(
	'jquery.js',
	'js_common.js'
);

// mảng css
$arrCss_mobile	= array(
	'mobile.css'
);
// mảng js
$arrJs_mobile	= array(
	'jquery.js',
	'js_mobile.js'
);

// editable css
$arrayEditable_css = array(
	'address.css','jquery-editable.css','select2.css','tip-yellowsimple.css'
);
$arrayEditable_js	= array(
	'jquery.poshytip.min.js','jquery-editable-poshytip.min.js','moment.min.js','select2.min.js','address.js','typeahead_min.js'
);

$editable_css = '';
$editable_js = '';
foreach($arrayEditable_css as $css_name){
	$editable_css	.=	'<link rel="stylesheet" href="'. $path_themes .'editable/css/'. $css_name .'?ver='. $version .'" type="text/css" />';
}

foreach($arrayEditable_js as $js_name){
	$editable_js		.= '<script src="'. $path_themes .'editable/js/'. $js_name .'?ver='. $version .'" type="text/javascript"></script>';
}


$link_css	= '';
$link_css_after   = '';
$link_js		= '';

$link_css_m	= '';
$link_css_m_after   = '';
$link_js_m		= '';

$source_ads = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
$source_google_ads = '<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-2155024450843541",
    enable_page_level_ads: true
  });
</script>';
//$link_css	.=	'<link rel="manifest" href="//1viec.com/sw/manifest.php" />';
//$link_css_m	.=	'<link rel="manifest" href="//1viec.com/sw/manifest.php" />';
foreach($arrCss as $css_name){
	$link_css	.=	'<link rel="stylesheet" href="'. $path_themes .'css/'. $css_name .'?ver='. $version .'" type="text/css" />';
}
$link_css         .= '<link href="'. $path_themes .'css/css_after.css?ver='. $version .'" rel="stylesheet" type="text/css">';

$link_css_after   .= '<link rel="stylesheet" href="'. $path_themes .'css/flaticon.css?ver='. $version .'" type="text/css" />';
$link_css_after   .= '<link href="'. $path_themes .'css/css_after.css?ver='. $version .'" rel="stylesheet" type="text/css">';

foreach($arrJs as $js_name){
	$link_js		.= '<script src="'. $path_themes .'js/'. $js_name .'?ver='. $version .'" type="text/javascript"></script>';
}

foreach($arrCss_mobile as $css_name){
	$link_css_m	.=	'<link rel="stylesheet" href="'. $path_themes .'css/'. $css_name .'?ver='. $version .'" type="text/css" />';
}

$link_css_m_after   .= '<link rel="stylesheet" href="'. $path_themes .'css/flaticon.css?ver='. $version .'" type="text/css" />';
$link_css_m_after   .= '<link href="'. $path_themes .'css/css_after.css?ver='. $version .'" rel="stylesheet" type="text/css">';

foreach($arrJs_mobile as $js_name){
	$link_js_m		.= '<script src="'. $path_themes .'js/'. $js_name .'?ver='. $version .'" type="text/javascript"></script>';
}

$temp_file_cv = isset($myuser->useField['use_cv_template'])? intval($myuser->useField['use_cv_template']) : 0; 

$js_ga		= ''; /* "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-82953708-1', 'auto');
  ga('send', 'pageview');
   
</script>
";
*/
$js_ga .= include("inc_profile/inc_firebase.php");

$fb_pixel   = "";


$js_ga .= $fb_pixel;

$js_head_tagmanage = "";
                
$link_js .= "
<script>
   var _config = {
      uid : ". $myuser->u_id .",
      process : ".$process.",
      logged : ". $myuser->logged .",
      is_file_cv : ". $is_file_cv .",
      temp_file : ". $temp_file_cv ."
   };  
</script>";                  
if($_SERVER['SERVER_NAME'] == 'localhost') $js_ga = '';

// get $_GET
$question	= isset($_GET['q'])? $_GET['q'] : '';
if(!is_array($question)){
   $arr  = explode(',', $question);
   if(!empty($arr)){
      $question = reset($arr);
   }
}else{
   $question = '';
}

$vl_cat_id	= getValue('cat_id');
$vl_key_id	= getValue('key_id');
$vl_cit_id  = getValue('cit_id');
$tag_id     = getValue('tag_id');
$fb_group_id   = getValue('fbg_id');
$fb_job_id     = getValue('fbj_id');
$fb_user_id     = getValue('fbid','str','GET', '');
$fb_cit_id     = getValue('cit_id');
$fb_cal_id     = getValue('cal_id');
$myCat_id   = (isset($myuser->useField['use_cat_id']) && $myuser->useField['use_cat_id'] > 0)? $myuser->useField['use_cat_id'] : 0;

$mybrc1 = uniqid();
$browser_cookie = getValue('brc1', 'str', 'COOKIE', '');
if($browser_cookie == ''){
   setcookie('brc1', $mybrc1, time() + 2*365*86400);
}


$array_key_serch  = array(); // mang tu khoa serach
if($vl_key_id > 0){
   if($vl_key_id > 9000000) $vl_key_id = 9000000;
   if($vl_key_id < 0) $vl_key_id = 1;
   
   $db_keysearch  = new db_query("SELECT * FROM category_link WHERE cal_id = " . intval($vl_key_id) . " LIMIT 1");
   if($rkey       = $db_keysearch->fetch()){
      $array_key_serch = $rkey;
   }
   unset($db_keysearch);
}

// setting
$array_setting = array();
$db_setting = new db_query("SELECT * FROM setting");
while($rs = $db_setting->fetch()){
   $array_setting[$rs['st_key']] = $rs['st_vl'];
}
unset($db_setting);

$show_adsense = isset($array_setting['vl_ads'])? intval($array_setting['vl_ads']) : 1;


// file name cache
$arrCacheName	= array(
	'new' => "cache_job_new.cache",
	'hot' => "cache_job_hot.cache"
);

// percent succent profile
$array_success    = array();

if($myuser->logged == 1){
   if($myuser->useField['use_email_notifi'] != ''
      && $myuser->useField['use_phone'] != ''
      && $myuser->useField['use_birthday'] > 0
      && $myuser->useField['use_birthday_year'] > 0
      && $myuser->useField['use_gender'] > 0
      && $myuser->useField['use_address'] != ''
      && $myuser->useField['use_married'] > 0
   ){
      $array_success['vt1'] = 1;
   }
   
   if($myuser->useField['use_branch'] != ''
      && $myuser->useField['use_level'] > 0
      && $myuser->useField['use_school_id'] > 0
   ){
      $array_success['vt2'] = 1;
   }
   
   if($myuser->useField['use_cat_id'] > 0
      && $myuser->useField['use_experience_id'] > 0
      && $myuser->useField['use_city_id'] > 0
      && $myuser->useField['use_relocate'] > 0
   ){
      $array_success['vt3'] = 1;
   }
}

$arrayCity			= getCache('cit');
$arrayCategory		= getCache('cat');
$arrayTagkeyword	= getCache('tag');
$arrayJobType		= generate_job_type();
$arraySalary		= generate_job_salary();
$arrayGender		= generate_job_gender();
$arrayLevel			= generate_job_level();
$arrayExperience	= generate_job_experience();
$arrayRank     	= generate_job_rank();

$arraycv_name = array(
   1 => array('name' => 'Standard 1 (1)', 'score' => 0)
   ,2 => array('name' => 'Impressive 1 (2)', 'score' => 0)
   ,3 => array('name' => 'Impressive 2 (3)', 'score' => 0)
   ,4 => array('name' => 'Professional 1 (4)', 'score' => 0)
   ,5 => array('name' => 'Modern 1 (5)', 'score' => 0)
   ,6 => array('name' => 'Standard 2 (6)', 'score' => 0)
   ,7 => array('name' => 'Professional 2 (7)', 'score' => 0)
   ,8 => array('name' => 'VIP 1 (8)', 'score' => 0)
   ,9 => array('name' => 'Impressive 3 (9)', 'score' => 0)
   ,10 => array('name' => 'Standard 3 (10)', 'score' => 0)
   ,100 => 1
   ,11 => array('name' => 'Professional 3 (11)', 'score' => 0)
   ,12 => array('name' => 'VIP 2 (12)', 'score' => 0)
   ,13 => array('name' => 'Standard 4 (13)', 'score' => 0)
   ,14 => array('name' => 'Elegant 1 (14)', 'score' => 0)
   ,15 => array('name' => 'Elegant 2 (15)', 'score' => 0)
   ,16 => array('name' => 'Impressive 4 (16)', 'score' => 0)
   ,17 => array('name' => 'Student 1 (17)', 'score' => 0)
   ,18 => array('name' => 'Standard 5 (17)', 'score' => 0)
   ,19 => array('name' => 'Elegant 3 (19)', 'score' => 0)
   ,20 => array('name' => 'Professional 4 (20)', 'score' => 0)
   ,21 => array('name' => 'Impressive 5 (21)', 'score' => 0)
   ,22 => array('name' => 'Special (22)', 'score' => 0)
   ,101 => 2
   ,23 => array('name' => 'Professional 5 (23)', 'score' => 0)
   ,24 => array('name' => 'Impressive 6 (24)', 'score' => 0)
   ,25 => array('name' => 'Impressive 7 (25)', 'score' => 0)
   ,26 => array('name' => 'Professional 6 (26)', 'score' => 0)
   ,27 => array('name' => 'Modern 2 (27)', 'score' => 0)
   ,28 => array('name' => 'Standard 6 (28)', 'score' => 0)
   ,29 => array('name' => 'Professional 7 (29)', 'score' => 0)
   ,30 => array('name' => 'VIP 3 (30)', 'score' => 0)
   ,31 => array('name' => 'Impressive 8 (31)', 'score' => 0)
   ,32 => array('name' => 'Standard 7 (32)', 'score' => 0)
   ,33 => array('name' => 'Professional 8 (33)', 'score' => 0)
   ,34 => array('name' => 'VIP 4 (34)', 'score' => 0)
   ,35 => array('name' => 'Standard 8 (35)', 'score' => 0)
   ,36 => array('name' => 'Elegant 4 (36)', 'score' => 0)
   ,37 => array('name' => 'Elegant 5 (37)', 'score' => 0)
   ,38 => array('name' => 'Impressive 9 (38)', 'score' => 0)
   ,39 => array('name' => 'Student 2 (39)', 'score' => 0)
   ,40 => array('name' => 'Standard 9 (40)', 'score' => 0)
   ,41 => array('name' => 'Elegant 6 (41)', 'score' => 0)
   ,42 => array('name' => 'Professional 9 (42)', 'score' => 0)
);
$arraycv_category = array(
    1 => array
        (
            "name" => 'Báo chí'
            ,'cv' => array(4,6,7,8,100,12,14,19,21,101,26,27,36,39)
        )

    ,2 => array
        (
            "name" => 'Hành chính văn phòng'
            ,'cv' => array(2,4,5,100,7,11,20,101,24)
        )

    ,3 => array
        (
            "name" => 'Nhân sự'
            ,'cv' => array(40,41,100,39,34,26,101,14,12)
        )

    ,5 => array
        (
            "name" => 'Hoá chất Sinh học'
            ,'cv' => array(5,100,2,11,12,14,21,101,27,31)
        )

    ,7 => array
        (
            "name" => 'Bưu chính Viễn thông'
            ,'cv' => array(1,3,100,8,10,13,15,17,101,19,27,36)
        )

    ,8 => array
        (
            "name" => 'Kế toán Kiểm toán'
            ,'cv' => array(42,39,34,100,32,28,101,29,30,24)
        )

    ,10 => array
        (
            "name" => 'IT lập trình'
            ,'cv' => array(5,7,8,100,14,32,101,38,39)
        )

    ,12 => array
        (
            "name" => 'Quản lý chất lượng'
            ,'cv' => array(3,6,9,100,25,26,27,31,101,34,36,39)
        )

    ,13 => array
        (
            "name" => 'Cơ khí Chế tạo máy'
            ,'cv' => array(38,34,100,26,27,14,15,101,12,10,1)
        )

    ,14 => array
        (
            "name" => 'Kiến trúc'
            ,'cv' => array(4,7,12,100,14,22,32,34,101,39,42)
        )

    ,15 => array
        (
            "name" => 'Quảng cáo'
            ,'cv' => array(37,100,39,35,28,29,30,101,25,2)
        )

    ,16 => array
        (
            "name" => 'Dầu khí Khoáng sản'
            ,'cv' => array(5,8,100,12,14,15,19,21,101,27,31)
        )

    ,17 => array
        (
            "name" => 'Kinh doanh Bán hàng'
            ,'cv' => array(34,100,35,39,40,41,25,101,21,16)
        )

    ,18 => array
        (
            "name" => 'Quản trị kinh doanh'
            ,'cv' => array(8,12,100,14,19,21,26,27,34,101,36)
        )

    ,19 => array
        (
            "name" => 'Dệt may'
            ,'cv' => array(2,4,5,6,100,7,8,12,42,23,101,16,19)
        )

    ,22 => array
        (
            "name" => 'Dịch vụ'
            ,'cv' => array(35,40,41,100,30,28,29,23,101,24,22)
        )

    ,25 => array
        (
            "name" => 'Du lịch Khách sạn'
            ,'cv' => array(28,29,100,30,34,35,40,41,101,42)
        )

    ,26 => array
        (
            "name" => 'Luật Pháp lý'
            ,'cv' => array(39,37,100,36,34,31,26,27,21,101,19,17)
        )

    ,28 => array
        (
            "name" => 'Điện công nghiệp'
            ,'cv' => array(1,2,4,6,100,8,12,16,17,34,101,29,32)
        )

    ,29 => array
        (
            "name" => 'Marketing Tư vấn'
            ,'cv' => array(28,100,32,41,2,7,3,29,101,30)
        )

    ,30 => array
        (
            "name" => 'Trang trí nội thất'
            ,'cv' => array(28,32,100,41,2,7,3,29,30,101,21,22)
        )

    ,31 => array
        (
            "name" => 'Điện tử'
            ,'cv' => array(1,6,8,100,10,15,17,3,2,38,101,41,42)
        )

    ,32 => array
        (
            "name" => 'Môi trường'
            ,'cv' => array(1,6,9,100,11,15,7,19,101,18,13)
        )

    ,33 => array
        (
            "name" => 'Xuất bản In ấn'
            ,'cv' => array(1,34,2,100,22,5,23,16,101,19,7,38)
        )

    ,36 => array
        (
            "name" => 'Xây dựng'
            ,'cv' => array(3,4,7,100,9,21,26,23,101,22,24)
        )

    ,39 => array
        (
            "name" => 'Y tế Dược phẩm'
            ,'cv' => array(28,25,100,29,30,32,42,41,2,101,6,8,9,23)
        )

    ,40 => array
        (
            "name" => 'Vận chuyển Giao nhận'
            ,'cv' => array(1,2,3,100,4,5,6,7,12,13,16,17,101,18,23)
        )

    ,41 => array
        (
            "name" => 'Ngân hàng Tài chính'
            ,'cv' => array(30,31,100,32,33,34,35,36,37,38,101,39,40)
        )

    ,42 => array
        (
            "name" => 'Ngành nghề khác'
            ,'cv' => array(1,3,100,5,32,25,26,7,26,27,101,22)
        )

    ,43 => array
        (
            "name" => 'Giao thông vận tải'
            ,'cv' => array(40,23,100,38,39,15,13,11,10,101,27,33)
        )

    ,45 => array
        (
            "name" => 'Thực phẩm Đồ uống'
            ,'cv' => array(23,100,24,25,26,27,35,36,101,37,38)
        )

    ,46 => array
        (
            "name" => 'Giáo dục Đào tạo'
            ,'cv' => array(12,14,100,16,18,24,27,28,34,1,101,37,39,41)
        )

    ,47 => array
        (
            "name" => 'Biên phiên dịch'
            ,'cv' => array(1,3,100,5,7,9,11,13,15,17,19,101,26,18)
        )

    ,48 => array
        (
            "name" => 'Quản lý điều hành'
            ,'cv' => array(2,4,100,6,8,10,16,18,20,22,101,24)
        )

    ,53 => array
        (
            "name" => 'Bất động sản'
            ,'cv' => array(3,6,9,12,100,6,19,26,18,29,101,36,39)
        )

);

// danh sách các cv đã mua
$arrayBuy   = array();
if(isset($myuser->useField['use_buy_template'])){
   $arrayBuy  = explode('|', $myuser->useField['use_buy_template']);
}