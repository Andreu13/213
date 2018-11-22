<?php 
header_remove();

set_error_handler('exceptions_error_handler', E_ALL);

function exceptions_error_handler($severity) {
  if (error_reporting() == 0) {
      return;
  }
  if (error_reporting() & $severity) {
    die('NOTOK1');
  }
}

try{
  $request = (array) json_decode(file_get_contents('php://input'));
  header("HTTP/1.1 200 ok");
  $settings = get_option("wpshop.payments.tinkoff");
  $request['Password'] = $settings['secret_key'];
  ksort($request);
  $request_str = json_encode($request);
  $original_token = $request['Token'];
  
  unset($request['Token']);

  $request['Success'] = $request['Success'] === true ? 'true' : 'false';
  
  $values = '';
  foreach ($request as $key => $val) {
    $values .= $val;
  }  
  $token = hash('sha256', $values);
  
  //log
  //$log = $_POST;
  //$log['token'] = $token;
  //$log['original_token'] = $original_token;
  //error_log($token.','.$original_token,0);
  
  if($token == $original_token){
    global $wpdb;
		$order = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}wpshop_orders` WHERE `order_id` = '%d'", array((int) $request['OrderId'])));
    
    //check orderId
    if (!$order->order_id) {
      die('NOTOK');
    }
    
    //set new status to order
		$status = $request['Status'];
		$newStatus = 0;
 
    if ($status == 'AUTHORIZED' && (int) $order->order_status == 2) {
      die('OK');
    }

    if ($status == 'AUTHORIZED') {
      $newStatus = 3;
    } elseif ($status == 'CONFIRMED') {
      $newStatus = 1;
    } elseif ($status == 'CANCELED' || $status == 'REJECTED' || $status == 'REVERSED'|| $status == 'REFUNDED') {
      $newStatus = 2;
    }

    Wpshop_Orders::setStatus($order->order_id, $newStatus);

    die('OK');
  } else {
    die('NOTOK');
  }
}catch(Exception $e){
  die('NOTOK');
}