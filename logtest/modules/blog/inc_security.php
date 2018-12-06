<?
$module_id	= 10;
$module_name= "Blogs";

//Declare prameter when insert data
$fs_table		= "blog";
$field_id		= "blo_id";
$field_name		= "blo_title";
$break_page		= "{---break---}";

require_once("../../resource/security/security.php");

//Path save image
$fs_pathfile		= '../../../upload/blog/';
$extension_list	= 'jpg,png,gif';
//Check user login...
checkLogged();
//Check access module...
if(checkAccessModule($module_id) != 1) redirect($fs_denypath);

$arrayCat		= array(0 => 'Chọn danh mục');
$db_category	= new db_query("SELECT * FROM blog_cat
										 WHERE bc_active = 1");
while($rcategory	= mysqli_fetch_assoc($db_category->result)){
	$arrayCat[$rcategory['bc_id']]	= $rcategory['bc_name'];
}
unset($db_category);

// danh mục việc làm
$arrayCat_vl   = array(
   0 => 'Chọn danh mục'
);
$db_cat_vl  = new db_query("SELECT * FROM category WHERE cat_active = 1");
while($rc   = mysqli_fetch_assoc($db_cat_vl->result)){
   $arrayCat_vl[$rc['cat_id']] = $rc['cat_name'];
}
unset($db_cat_vl);
?>