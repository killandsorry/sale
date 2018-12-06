<?
include 'inc_security.php';
require_once '../../../class/simple_html_dom.php';
include 'function_get.php';

$url  = getValue('url', 'str', 'POST', 'https://vieclameva.com/tuyen-chuyen-vien-tu-va-n-the-ho-i-vien-junior-sales-consultant-716731.html');

$array_return  = array(
   'status' => 0,
   'error' => '',
   'web_id' => 0,
   'crawl_id' => 0,
   'data' => array()
);

$arr_url  = parse_url($url);
$host       = isset($arr_url['host'])? trim(str_replace('www.', '', $arr_url['host'])) : '';
$path       = isset($arr_url['path'])? trim($arr_url['path']) : '';

if(isset($array_web_id[$host])){
   $web_id  = $array_web_id[$host];
   $crawl_id   = 0;
   
   switch($host){
      case 'timviecnhanh.com':
         $arr  = explode('-', $path);
         $crawl_id   = intval(end($arr));
         break;
      case 'tuyendung.com.vn':
         $arr  = explode('/', $path);
         $crawl_id   = intval($arr[2]);
         break;
      case 'vieclameva.com':
         $arr  = explode('-', $path);
         $crawl_id   = intval(end($arr));
         break;
      case 'mywork.com.vn':
         $arr  = explode('/', $path);
         $crawl_id   = intval($arr[3]);
         break;
      case 'vieclam24h.vn':
         $arr  = explode('id', $path);
         $crawl_id   = intval(end($arr));
         break;
   }
   
   
   $db_check   = new db_query("SELECT * FROM check_crawl
                               WHERE ccr_web_id = " . $web_id . " AND ccr_crawl_id = " . intval($crawl_id) . " LIMIT 1");
   if($rcheck  = mysqli_fetch_assoc($db_check->result)){
      $array_return['error'] = 'Link crawler đã được lấy tin về hệ thống';
   }else{
      $array_return['status'] = 1;
      $array_return['web_id'] = $web_id;
      $array_return['crawl_id'] = intval($crawl_id);
      //echo $url;
      //*
      switch($host){
         case 'timviecnhanh.com':
            $array_return['data']  = get_timviecnhanh($url);
            break;
         case 'tuyendung.com.vn':
            $array_return['data']  = get_tuyendungcomvn($url);
            break;
         case 'vieclameva.com':
            $array_return['data']  = get_vieclameva($url);
            break;
         case 'mywork.com.vn':
            $array_return['data']  = get_mywork($url);
            break;
         case 'vieclam24h.vn':
            $array_return['data']  = get_vieclam24h($url);
            break;
      }
      //*/
            
   }
   unset($db_check);   
   
   
}else{
   $array_return['error'] = 'Website bạn lấy tin không có trong hệ thống';
}
//print_r($array_return);
echo json_encode($array_return);
exit();