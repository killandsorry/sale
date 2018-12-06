<?
include('config.php');

if($blo_id > 9000000) $blo_id = 0;
if($blo_id <= 0){
   header_301($path_root);
   exit();
}

$blo_cat_id = 0;
$db_detail  = new db_query("SELECT * FROM blog AS b
                            INNER JOIN blog_content AS c ON b.blo_id = c.blo_id
                            WHERE b.blo_id = " . intval($blo_id) . " LIMIT 1");
if($row     = mysqli_fetch_assoc($db_detail->result)){
   $blo_cat_id = $row['blo_cat_id'];
   $cat_id     = $blo_cat_id;
}else{
   header_301($path_root);
   exit(); 
}
unset($db_detail);

$title	= $row['blo_title'];
$des		= $row['blo_teaser'];

$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')? 'https:' : 'http:';
$url		= $http .'//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// cập nhật visit
$db_visit   = new db_execute("UPDATE blog SET blo_hit = blo_hit + 1 WHERE blo_id = " . intval($blo_id));
unset($db_visit);

$fb_image  = '//1viec.com/upload/blog/' . $row['blo_image'];
?>
<!DOCTYPE HTML>
<html>
<head>
<title><?=$title?></title>
<meta http-equiv="content-type" content="text/html, charset=utf-8" />
<meta http-equiv="content-language" itemprop="inLanguage" content="vi"/>
<meta name="robots" content="index,follow,noodp" />
<meta name="description" itemprop="description" content="<?=$des?>" />
<meta name="viewport" content="initial-scale=1, maximum-scale=1.0, minimum-scale=1.0">
<meta property="og:site_name" content="Blog tuyển dụng, chia sẻ kinh nghiệm" />
<meta property="og:type" content="Website" />
<meta property="og:locale" content="vi_VN" />
<meta property="og:title" itemprop="name" content="<?=$title?>" />
<meta property="og:url" itemprop="url" content="<?=$url?>" />
<meta property="og:description" content="<?=$des?>" />
<meta property="og:image" itemprop="thumbnailUrl" content="<?=$fb_image?>"  />
<meta property="fb:page_id" content="1164460176956424" />
<meta property="fb:app_id" content="1649548195356808" />
<meta name="language" content="vietnamese" />
<meta name="copyright" content="Copyright © <?=date('Y')?> by <?=$webname?>" />
<meta name="abstract" content="<?=$webname . ' ' . $slogan?>" />
<meta name="distribution" content="Global" />
<meta name="author" itemprop="author" content="<?=$webname?>" />
<meta http-equiv="refresh" content="1800" />
<meta name="REVISIT-AFTER" content="1 DAYS" />
<link rel="canonical" href="<?=$url?>"/>
<link rel='shortcut icon' type='image/png' href='/favicon.png' />
<?=$source_ads?>
<?
if($mobile->isMobile()){
   echo $link_css_m;
}else{
   echo $link_css;
}
?>
<?=$js_head_tagmanage?>

</head>
<body class="">
   <?
   if($mobile->isMobile()){
      include '../blog_m/detail.php';      
      exit();
   }else{
   ?>
      <div id="wrapper">   	
         <?include('../inc_blog/inc_breadcrum.php')?>
         <?include('../inc_blog/inc_header.php')?>
      	<div id="home_center">
            <div id="left"><?include('../inc_blog/inc_detail_left.php')?></div>
            <div id="right"><?include('../inc_blog/inc_right.php')?></div>
         </div>
      	<?include('../inc_blog/inc_footer.php')?>
      </div>
    <?}?>
   
   
</body>
<?=$link_js?>
<?=$js_ga?>
</html>