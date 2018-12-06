<?
/**
 * class card payment 
 */
class payment_card
{
   
   private $payment_url = 'https://www.baokim.vn/the-cao/restFul/send';
   //Mã MerchantID dang kí trên Bảo Kim
   private $merchant_id = '30537';
   //Api username 
   private $api_username = '1vieccom';
   //Api Pwd d
   private $api_password = '2ZwPD3jeTQFYgCxBG4Dx';
   
   //mat khau di kem ma website dang kí trên B?o Kim
   private $secure_code = '081b9521d93079ee'; 
   
   private $card_provider = array('MOBI','VIETEL','GATE','VTC','VINA');
   
   /**
    * $data = array(
    *    
    * ) 
    */
   function paycard($data = array()){
      
      global $myuser;
      
      $arrayResult   = array('status' => 0, 'error' => '');
      
      $card_name = isset($data['card_name'])? $data['card_name'] : '';
      $card_pin  = isset($data['card_pin'])? $data['card_pin'] : '';
      $card_seri = isset($data['card_seri'])? $data['card_seri'] : '';
      $buyvip = isset($data['buyvip'])? $data['buyvip'] : 0;
      
      
      
      if(!in_array($card_name, $this->card_provider)){
         return $arrayResult['error'] = 'Không tồn tại nhà mạng';
      }
      
      $dataCard   = array(
         'merchant_id'=> $this->merchant_id,
      	'api_username'=> $this->api_username,
      	'api_password'=> $this->api_password,
      	'card_id'=> $card_name,
      	'pin_field'=>$card_pin,
      	'seri_field'=>$card_seri,
      	'algo_mode'=>'hmac',
         'transaction_id' => time()
      );
      
      // ghi log
      file_put_contents('../logss/payment/card.v', date('d/m/Y - H:i:s', time()) . "\n" . json_encode($dataCard) . "\n\n", FILE_APPEND);
      
      ksort($dataCard);
      $data_sign = hash_hmac('SHA1',implode('',$dataCard),$this->secure_code);
      $dataCard['data_sign'] = $data_sign;
      
      $curl = curl_init($this->payment_url);
      
      curl_setopt_array($curl, array(
      	CURLOPT_POST=>true,
      	CURLOPT_HEADER=>false,
      	CURLINFO_HEADER_OUT=>true,
      	CURLOPT_TIMEOUT=>30,
      	CURLOPT_RETURNTRANSFER=>true,
      	CURLOPT_SSL_VERIFYPEER => false,
      	CURLOPT_POSTFIELDS=>http_build_query($dataCard)
      ));
      
      $data = curl_exec($curl);
      
      $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      $result = json_decode($data,true);
      
      if($myuser->u_id == 11){
         //$result['status'] = 200;
//         $result['amount'] = 10000;
//         $status  = 200;
      }
      if($status==200){
         $amount = $result['amount'];
         
         $su = intval($amount / 1000);
         
         $total_money   = intval($myuser->useField['use_money']) + $su;
         if($buyvip == 0){
            $db_user = new db_execute("UPDATE user SET use_money = " . $total_money . " WHERE use_id = " . $myuser->u_id);
            unset($db_user);
         }else{
            
            $vipday  = time();
            $db_user = new db_query("SELECT * FROM user WHERE use_id = " . $myuser->u_id . " LIMIT 1");
            if($rvip = $db_user->fetch()){
               $vipday = ($rvip['use_buy_vip'] > 0 && $rvip['use_buy_vip'] > time())? $rvip['use_buy_vip'] : time();
            }
            
            $vipday += (($su * 1.5) * 86400);
            $db_user = new db_execute("UPDATE user SET use_buy_vip = " . $vipday . ",use_is_verify_phone = 1 WHERE use_id = " . $myuser->u_id);
            unset($db_user);
         }
         
         // ghi log
         file_put_contents('../logss/payment/card_success.v', date('d/m/Y - H:i:s', time()) . "\n" . json_encode($result) . "\n\n", FILE_APPEND);
         
      }else{
         $arrayResult['error'] = $result['errorMessage'];
         // ghi log
         file_put_contents('../logss/payment/card_error.v', date('d/m/Y - H:i:s', time()) . "\n" . json_encode($result) . "\n\n", FILE_APPEND);
         
      }
      
      $arrayResult['status'] = $status;
      
      return $arrayResult;
      
   }
}