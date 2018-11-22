<?php //error_log('primearea try',0);
  header_remove(); 
  $opts = get_option("wpshop.payments.primearea");
  $order = new Wpshop_Order((int)$_POST["payno"]);
  $amount = number_format((float) $order->getTotalSum(), 2,'.','');
  $secret = $opts['secure'];

  if(!in_array($_SERVER["REMOTE_ADDR"], ['109.120.152.109', '145.239.84.249'], true)){
    error_log('primearea addr',0);
    exit();
  }
  
  if($_POST["amount"] !== $amount){
    error_log('primearea amount'.$_POST["amount"].' '.$amount,0);
    exit();
  }
  
  $sign = $_POST['sign'];
  unset($_POST['sign']);
  ksort($_POST,SORT_STRING);
  $signi = hash('sha256',implode(':',$_POST).':'.$secret);
  if($signi !== $sign):
    header('HTTP/1.0 400 Bad Request');
    error_log('primearea hash_error',0);
    exit();
  else:
    Wpshop_Orders::setStatus($_POST["payno"],1);
    header("HTTP/1.1 200 ok");
  endif;
  exit();