<?
session_start();
//error_reporting(0);
require_once("../../../class/vl_database.php");
require_once("../../../class/vl_class.php");
require_once("../../../fn/vl_function.php");
require_once("../../../fn/vl_template.php");
require_once("../../../fn/vl_translate.php");
require_once("../../../fn/vl_rewrite.php");
require_once("../../../class/generate_form.php");
require_once("../../../class/form.php");
require_once("../../../class/upload.php");
require_once("../../../class/menu.php");
require_once("../../../class/html_cleanup.php");
require_once("../../../cf_avarible.php");
require_once("grid.php");
require_once("../../resource/wysiwyg_editor/fckeditor.php");
require_once("functions.php");
require_once("template.php");
$admin_id 				= getValue("user_id","int","SESSION");
$lang_id	 				= getValue("lang_id","int","SESSION");

$vl_class   = new vl_class();

//phan khai bao bien dung trong admin
$fs_stype_css			= "../css/css.css";
$fs_template_css		= "../css/template.css";
$fs_border 				= "#f9f9f9";
$fs_bgtitle 			= "#DBE3F8";
$fs_imagepath 			= "../../resource/images/";
$fs_scriptpath 		= "../../resource/js/";
$wys_path				= "../../resource/wysiwyg_editor/";
$fs_denypath			= "../../error.php";
$wys_cssadd				= array();
$wys_cssadd				= "/css/all.css";
$sqlcategory 			= "";
$fs_category			= checkAccessCategory();
$fs_is_in_adm			= 1;
//phan include file css

$load_header 			= '<link href="../../resource/css/css.css" rel="stylesheet" type="text/css">';
$load_header 			.= '<link href="../../resource/css/template.css" rel="stylesheet" type="text/css">';
$load_header 			.= '<link href="../../resource/css/grid.css" rel="stylesheet" type="text/css">';
$load_header 			.= '<link href="../../resource/css/thickbox.css" rel="stylesheet" type="text/css">';
$load_header 			.= '<link href="../../resource/css/calendar.css" rel="stylesheet" type="text/css">';
$load_header 			.= '<link href="../../resource/js/jwysiwyg/jquery.wysiwyg.css" rel="stylesheet" type="text/css">';
$load_header 			.= '<link href="../../resource/css/jquery.datepick.css" rel="stylesheet" type="text/css">';

//phan include file script
$load_header 			.= '<script language="javascript" src="../../resource/js/jquery-1.3.2.min.js"></script>';
$load_header 			.= '<script language="javascript" src="../../resource/js/library.js"></script>';
$load_header 			.= '<script language="javascript" src="../../resource/js/thickbox.js"></script>';
$load_header 			.= '<script language="javascript" src="../../resource/js/calendar.js"></script>';
$load_header 			.= '<script language="javascript" src="../../resource/js/tooltip.jquery.js"></script>';
$load_header 			.= '<script language="javascript" src="../../resource/js/jquery.jeditable.mini.js"></script>';
$load_header 			.= '<script language="javascript" src="../../resource/js/swfObject.js"></script>';
$load_header 			.= '<script language="javascript" src="../../resource/js/jwysiwyg/jquery.wysiwyg.js"></script>';
$load_header 			.= '<script language="javascript" src="../../resource/js/jquery.datepick.js"></script>';
$load_header 			.= '<script language="javascript" src="../../resource/js/jquery.datepick-vi.js"></script>';

$fs_change_bg			= 'onMouseOver="this.style.background=\'#DDF8CC\'" onMouseOut="this.style.background=\'#FEFEFE\'"';
//phan ngon ngu admin

$db_con = new db_query("SELECT * from configuration");
if ($row=mysqli_fetch_assoc($db_con->result)){
	while (list($data_field, $data_value) = each($row)) {
		if (!is_int($data_field)){
			//tao ra cac bien config
			$$data_field = $data_value;
			//echo $data_field . "= $data_value <br>";
		}
	}
}
$db_con->close();
unset($db_con);

?>