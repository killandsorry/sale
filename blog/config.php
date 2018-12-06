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

/*
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}
*/

require_once ('../class/vl_database.php');
require_once ('../class/vl_class.php');
require_once('../class/form_html.php');
require_once('../class/upload.php');
require_once('../class/vl_user.php');
require_once ('../class/mobile.php');
require_once ('../fn/vl_function.php');
require_once ('../fn/vl_profile.php');
require_once ('../fn/vl_rewrite.php');
require_once ('../fn/vl_template.php');

$webname = '1viec.com';
$vl_class   = new vl_class();
$mobile        = new Mobile_Detect();
$myuser     = new user();

$module  = '';

$path_themes	= '/themes/'; // đường dẫn themes
$path_root     = '/blog';
$path_employer = '/employer/boad.php';
$path_ntv      = '//' . $webname;
$path_luongcao = '/tim-viec-lam-luong-cao';
$path_24h      = '/tim-viec-lam-24h';

$slogan			= 'Tìm việc làm, Tuyển dụng nhân sự việt, Tìm kiếm tài năng việt';

// version themes
$version	= 3;
// mảng css
$arrCss	= array(
	'css_blog.css'
);

// mảng css
$arrCss_mobile	= array(
	'css_blog_m.css'
);
// mảng js
$arrJs	= array(
	'jquery.js',
	'js_blog.js'
);

$link_css	= '';
$link_css_m = '';
$link_js		= '';
$source_ads = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';

foreach($arrCss as $css_name){
	$link_css	.=	'<link rel="stylesheet" href="'. $path_themes .'css/'. $css_name .'?ver='. $version .'" type="text/css" />';
}
//$link_css   .= '<link href="//fonts.googleapis.com/css?family=Roboto:400,300,500,700&subset=latin,vietnamese" rel="stylesheet" type="text/css">';

foreach($arrCss_mobile as $css_name){
	$link_css_m	.=	'<link rel="stylesheet" href="'. $path_themes .'css/'. $css_name .'?ver='. $version .'" type="text/css" />';
}
//$link_css_m   .= '<link href="//fonts.googleapis.com/css?family=Roboto:400,300,500,700&subset=latin,vietnamese" rel="stylesheet" type="text/css">';

foreach($arrJs as $js_name){
	$link_js		.= '<script src="'. $path_themes .'js/'. $js_name .'?ver='. $version .'" type="text/javascript"></script>';
}

$js_ga		= "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-82953708-1', 'auto');
  ga('send', 'pageview');

</script>
";

$source_ads = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';

$js_head_tagmanage = "";

$js_ga .= include ('../inc_profile/inc_firebase.php');

$arrayCategory = array();
$db_cat  = new db_query("SELECT * FROM blog_cat WHERE bc_active = 1");
while($rcat = mysqli_fetch_assoc($db_cat->result)){
   $arrayCategory[$rcat['bc_id']] = $rcat;
}
unset($db_cat);

// setting
$array_setting = array();
$db_setting = new db_query("SELECT * FROM setting");
while($rs = $db_setting->fetch()){
   $array_setting[$rs['st_key']] = $rs['st_vl'];
}
unset($db_setting);

$show_adsense = isset($array_setting['vl_ads'])? intval($array_setting['vl_ads']) : 1;

$cat_id  = getValue('cat');
$blo_id  = getValue('bid');

$arrayCate_cat = getCache('cat');