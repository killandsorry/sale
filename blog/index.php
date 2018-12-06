<?
include('config.php');

$title	= 'Blog Tư vấn, chia sẻ kinh nghiệm tuyển dụng, tìm việc làm';
$keyword	= 'tư vấn hướng nghiệp, kinh nghiệm tuyển dụng, kỹ năng làm việc,câu chuyện thành công,khởi nghiệp';
$des		= 'Blog chia sẻ kinh nghiệm tuyển dụng, tư vấn hướng nghiệp, kinh nghiệm làm việc được cập nhật liên tục tại đây, là trang thông tin tư vấn tin cậy cho người tìm việc';
$url		= '//' . $_SERVER['HTTP_HOST'] . '/blog/';


?>
<!DOCTYPE HTML>
<html>
<head>
<title><?=$title?></title>
<meta http-equiv="content-type" content="text/html, charset=utf-8" />
<meta http-equiv="content-language" itemprop="inLanguage" content="vi"/>
<meta name="robots" content="index,follow,noodp" />
<meta name="keywords" itemprop="keywords" content="<?=$keyword?>" />
<meta name="description" itemprop="description" content="<?=$des?>" />
<meta name="viewport" content="initial-scale=1, maximum-scale=1.0, minimum-scale=1.0">
<meta property="og:site_name" content="Blog tuyển dụng, chia sẻ kinh nghiệm" />
<meta property="og:type" content="Website" />
<meta property="og:locale" content="vi_VN" />
<meta property="og:title" itemprop="name" content="<?=$title?>" />
<meta property="og:url" itemprop="url" content="<?=$url?>" />
<meta property="og:description" content="<?=$des?>" />
<meta property="og:image" itemprop="thumbnailUrl" content=""  />
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
         include '../blog_m/index.php';      
         exit();
      }else{
      ?>
         <div id="wrapper">
         	<?include('../inc_blog/inc_breadcrum.php')?>
            <?include('../inc_blog/inc_header.php')?>
            
         	<div id="home_center">
               <div id="left"><?include('../inc_blog/inc_home_left.php')?></div>
               <div id="right"><?include('../inc_blog/inc_right.php')?></div>
            </div>
         	<?include('../inc_blog/inc_footer.php')?>
         </div>
       <?}?>   
</body>
<?=$link_js?>
<?=$js_ga?>
</html>