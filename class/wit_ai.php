<?
/**
 * class wit.ai
 * 
 * create tags
 * get tags from string
 */

define ('WIT_TOCKENT', 'QM6RRJZS35EEFE6ESWHGYQJVWBGNLX5H');
define ('WIT_URL', 'https://api.wit.ai/');

class witai{
   
   public function curl_post($url, $data){
      $ini = curl_init($url);
      $userAgent  = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13";
      $header  = array(
         'Content-Type:application/json',
         'Authorization:Bearer QM6RRJZS35EEFE6ESWHGYQJVWBGNLX5H'
      );
      
      curl_setopt($ini, CURLOPT_USERAGENT, $userAgent);
      curl_setopt($ini, CURLOPT_HTTPHEADER , $header);
      curl_setopt($ini, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ini, CURLOPT_POSTFIELDS, json_encode($data));
      $result  = curl_exec($ini);
      unset($ini);
      return $result;
   }
   
   
   public function curl_get($url){
      $ini = curl_init($url);
      $userAgent  = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13";
      $header  = array(
         'Authorization:Bearer QM6RRJZS35EEFE6ESWHGYQJVWBGNLX5H'
      );
      curl_setopt($ini, CURLOPT_CUSTOMREQUEST, 'GET');
      curl_setopt($ini, CURLOPT_USERAGENT, $userAgent);
      curl_setopt($ini, CURLOPT_HTTPHEADER , $header);
      curl_setopt($ini, CURLOPT_RETURNTRANSFER, true);
      $result  = curl_exec($ini);
      unset($ini);
      return $result;
   }
   
   /**
    * data = array(
    *    doc => 'description',
    *    id => tags name
    * )
    */
   public function post_tags($data = array()){
      $api = 'entities?v=20170307';
      $fullapi = WIT_URL . $api;
      
      $result = '';
      if(!empty($data)){
         $result = $this->curl_post($fullapi, $data);
      }
      
      return $result;
   }
   
   
   /**
    * posst entries with keyword
    * data = array(
    *    keyTags => 'country',
    *    keyword => array(
    *       'value' => 'country',
    *       'expressions' => [country],
    *       'matadata' => 'city_3'
    *    )
    * )
    */
   public function post_tags_with_keyword($data = array()){
      $tags = isset($data['keyTags'])? $data['keyTags'] : '';
      $keyword = isset($data['keyword'])? $data['keyword'] : array();
      $api = 'entities/'. $tags .'/values?v=20170307';
      $fullapi = WIT_URL . $api;
      
      $result = '';
      if(!empty($keyword)){
         $result = $this->curl_post($fullapi, $keyword);
      }
      
      return $result;
   }
   
   
   /**
    * Function get tags
    * 
    */
   public function get_tags($mes = ''){
      $api = 'message?v=20170307&q=' . urlencode($mes);
      $fullapi = WIT_URL . $api;
      
      $result = '';
      $result = $this->curl_get($fullapi);
      return $result;
   }
}