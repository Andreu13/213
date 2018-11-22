<?php 
$last = Wpshop_RecycleBin::getInstance()->getLastOrder();
$order = new Wpshop_Order($this->order['id']);

if ($this->order['info']['payment'] == "wm") {

?>
<form action="https://merchant.webmoney.ru/lmi/payment.asp" method="POST">
	<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?php  echo $order->getTotalSum();?>"/>
	<input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="<?php  echo base64_encode(__('Order', 'wp-shop')." #{$this->order['id']} ".__('from site', 'wp-shop')." {$_SERVER['HTTP_HOST']}");?>"/>
	<input type="hidden" name="LMI_PAYEE_PURSE" value="<?php  echo $this->wm['wmCheck'];?>"/>
	<input type="hidden" name="LMI_SUCCESS_URL" value="<?php  echo $this->wm['successUrl'];?>"/>
	<input type="hidden" name="LMI_FAIL_URL" value="<?php  echo $this->wm['failedUrl'];?>"/>
	<input type="hidden" name="LMI_RESULT_URL" value="<?php  echo bloginfo('wpurl')."/?wmResult=1&order_id={$this->order['id']}";?>"/>
	<input type="submit" class=\"wpshop-button\" value="<?php  echo __('Pay WM', 'wp-shop'); // Оплатить WM ?>"/>
</form>
<?php 
}elseif ($this->order['info']['payment'] == "interkassa") {
$shop_id = $this->interkassa['shopId'];
$secret_key = $this->interkassa['secret'];
if (isset($shop_id)&&$shop_id!=''&&$secret_key!=''&&isset($secret_key)){
	include_once WPSHOP_DIR .'/classes/interkassa/interkassa.php';
	Interkassa::register();

	// Create a shop
	$shop = Interkassa_Shop::factory(array(
		'id' => $shop_id,
		'secret_key' => $secret_key
	));

	// Create a payment
	$payment_id = (string)$this->order['id']; // Your payment id
	$payment_amount = (float)$order->getTotalSum(); // The amount to charge your shop's user
	$payment_desc = __('Order', 'wp-shop')." #{$this->order['id']} ".__('from site', 'wp-shop')." {$_SERVER['HTTP_HOST']}"; // Payment description

	$payment = $shop->createPayment(array(
		'id' => $payment_id,
		'amount' => $payment_amount,
		'description' => $payment_desc,
		'status_url' => get_bloginfo("url").'/wpshop/interkassa_result',
		'currency' => $this->interkassa['currency']	
	));
	?>
	<form action="<?= $payment->getFormAction(); ?>" method="post">
		<?php foreach ($payment->getFormValues() as $field => $value): ?>
		<input type="hidden" name="<?= $field; ?>" value="<?= $value; ?>" />
		<?php endforeach; ?>
		<?php if (isset($this->order['info']['email']) && !is_null($this->order['info']['email']) && !empty($this->order['info']['email'])) : ?>
		<input type="hidden" name="ik_cli" value="<?php echo $this->order['info']['email']; ?>">
		<?php endif; ?>
		<button type="submit" class="wpshop-button" ><?php echo __('Pay Interkassa', 'wp-shop');?></button>
	</form>
	<?php 
	}
}elseif ($this->order['info']['payment'] == "cripto") {
	include WPSHOP_DIR .'/classes/cripto/MerchantAPI.php';
	$merchant_id = $this->cripto['merchant_id'];
	$secret = $this->cripto['secret'];

	if ($merchant_id!=''&&$secret!=''){
 
	   if($this->cripto['successUrl']!=''){
		$sucess = $this->cripto['successUrl'];
	  }else {
		$sucess = get_bloginfo("url") . '/?wpshopcarts=cripto_success';
	  }
	  
	  if($this->cripto['failedUrl']!=''){
		$fail = $this->cripto['failedUrl'];
	  }else {
		$fail = get_bloginfo("url") . '/?wpshopcarts=cripto_failed';
	  }	  
	  
	  if($this->cripto['lang_cripto']!=''){
		$lang = $this->cripto['lang_cripto'];
	  }else {
		$lang = 'ru';
	  }
	  
	  if($this->cripto['currency_cripto']!=''){
		$curr = $this->cripto['currency_cripto'];
	  }else {
		$curr = 'rur';
	  }
	  
	  $cryptonator = new MerchantAPI($merchant_id, $secret);
	  $options = array(
	   'item_name'               => __('Order', 'wp-shop')." #{$this->order['id']} ".__('from site', 'wp-shop')." {$_SERVER['HTTP_HOST']}",
	   'order_id'              => (string)$this->order['id'],
	   'invoice_amount'          => $order->getTotalSum(),
	   'invoice_currency'        => $curr,
	   'success_url'           => $sucess,
	   'failed_url'            => $fail,
	   'language'              => $lang
	  );
	  try{
	  $url = $cryptonator->startPayment($options);
	  }catch (ServerError $e) {
			// code to handle the Exception
			echo 'Error :' . $e->getMessage() . '<br />';
			echo 'Code :' . $e->getCode() . '<br />';
			echo 'File :' . $e->getFile() . '<br />';
			echo 'Line :' . $e->getLine() . '<br />';
			exit();
	  }
	  
	  $cryptonator->safeRedirect($url);
	} else {
		echo __('You have not configured a payment through `Cryptonator`, contact the site administrator', 'wp-shop');
	}
}elseif ($this->order['info']['payment'] == "tinkoff") {
    include WPSHOP_DIR .'/classes/TinkoffMerchantAPI.php';

    $data = array(
      'OrderId'  => $this->order['id'],
      'Amount'   => $order->getTotalSum() * 100,
      'DATA' => array(
        'Email' => $this->order['info']['email'],
        'Connection_type' => 'wordpress_wp_shop'
      )
    );

    $Tinkoff = new TinkoffMerchantAPI( $this->tinkoff['terminal'], $this->tinkoff['secret_key'], $this->tinkoff['gateway'] );
    $Tinkoff->init($data);?>
    <!--<p><span class="highlight">Response</span>: <?php// echo $Tinkoff->response ?></p>-->
    <?php if ($Tinkoff->error) : ?>
      <span class="error"><?php echo $Tinkoff->error ?></span>
    <?php else: ?>
      <!--<p><span class="highlight">Status</span>: <?php //echo $Tinkoff->status ?></p>-->
      <form action="<?php echo $Tinkoff->paymentUrl;?>" method="POST">
        <input type="submit" class="wpshop-button" value="<?php echo __('Pay Tinkoff Bank', 'wp-shop');?>"/>
      </form>
    <?php endif;
}elseif ($this->order['info']['payment'] == "primearea") {
  if (isset($this->primearea['secure'])&&$this->primearea['secure']!=''&&isset($this->primearea['shopid'])&&$this->primearea['shopid']!=''):
  $secret = $this->primearea['secure'];
  $data = array(
    'shopid' => (int)$this->primearea['shopid'],
    'payno' => (int)$this->order['id'],
    'amount' => (float)$order->getTotalSum(),
    'description' => __('Order', 'wp-shop')." #{$this->order['id']} ".__('from site', 'wp-shop')." {$_SERVER['HTTP_HOST']}"
  );
  ksort($data,SORT_STRING);
  $sign = hash('sha256',implode(':',$data).':'.$secret);
  echo '
    <form method="POST" action=https://primearea.biz/merchant/pay/>
    <input type="hidden" name="shopid" value="'.$data['shopid'].'">
    <input type="hidden" name="payno" value="'.$data['payno'].'">
    <input type="hidden" name="amount" value="'.$data['amount'].'">
    <input type="hidden" name="description" value="'.$data['description'].'">
    <input type="hidden" name="sign" value="'.$sign.'"><br>
    <button>Оплатить</button>
    </form>
  ';
  endif;
}elseif ($this->order['info']['payment'] == "ym") {?>
<?php 
	if ($this->ym['p_name']){$p_name = '&fio=on';}else {$p_name ='';}
	if ($this->ym['p_email']){$p_email = '&mail=on';}else {$p_email ='';}
	if ($this->ym['p_cell']){$p_cell = '&phone=on';}else {$p_cell ='';}
	if ($this->ym['p_adr']){$p_adr = '&address=on';}else {$p_adr ='';}
	if ($this->ym['p_visa']){$p_visa = '&payment-type-choice=on';}else {$p_visa ='';}
	if ($this->ym['p_mobile']){$p_mobile = '&mobile-payment-type-choice=on';}else {$p_mobile ='';}
?>
<iframe frameborder="0" allowtransparency="true" scrolling="no" style="display: block; margin: 0 auto;" src="https://money.yandex.ru/embed/shop.xml?account=<?php  echo urlencode($this->ym['ymAccount']);?>&quickpay=shop<?php  echo $p_visa.$p_mobile;?>&writer=seller&targets=<?php  echo urlencode (__('Order', 'wp-shop')." #{$this->order['id']} ".__('from site', 'wp-shop')." {$_SERVER['HTTP_HOST']}");?>&targets-hint=&default-sum=<?php  echo $order->getTotalSum();?>&button-text=01<?php  echo $p_name.$p_email.$p_cell.$p_adr;?>&successURL=<?php echo $this->ym['successUrl'];?>" width="450" height="198"></iframe>
<?php 
} elseif ($this->order['info']['payment'] == "sber") {
	if ($this->sber['test']){
		$action_adr = 'https://3dsec.sberbank.ru/payment/rest/';
	}else{
		$action_adr = 'https://securepayments.sberbank.ru/payment/rest/';
	}

	if ($this->sber['stage'] == 'two') {
		$action_adr .= 'registerPreAuth.do';
	} else if ($this->sber['stage'] == 'one') {
		$action_adr .= 'register.do';
	} 
  
	$info_order_sber = $order->getOrderItemsFull($this->order['id']);
	$info_order_sber_arr = array();
	$order_del = $order->getDelivery(); 
	$order_del_name = (string)$order_del->ID; 
	$info_order_del_cost = (float)$order->getDeliveryCost();  
	$info_order_del_disk = (float)($order->getDiscount()/100);
  
    $info_order_sber_arr['customerDetails']['email']=$order->getOrderEmail();
	
	$count = 0;
	$positionId = 0;
	$sber_total = 0;
	foreach ($info_order_sber as $key=>$item){
		$info_order_sber_arr['cartItems']['items'][$key]['positionId'] = (int)($item['ordered_id']);
		$info_order_sber_arr['cartItems']['items'][$key]['name'] = (string)$item['name'];
		$info_order_sber_arr['cartItems']['items'][$key]['quantity']['value'] = (string)$item['count'];
		$info_order_sber_arr['cartItems']['items'][$key]['quantity']['measure'] = (string)$this->sber['measure'];
		$info_order_sber_arr['cartItems']['items'][$key]['itemAmount'] = (int)(round((float)$item['cost']-(float)$item['cost']*$info_order_del_disk,2)*(float)$item['count']*100);
		$sber_total +=$info_order_sber_arr['cartItems']['items'][$key]['itemAmount'];
		$info_order_sber_arr['cartItems']['items'][$key]['itemCode'] = (string)$key.'_'.$item['post_id'];
        $info_order_sber_arr['cartItems']['items'][$key]['itemPrice'] =(int)(round((float)$item['cost']-(float)$item['cost']*$info_order_del_disk,2)*100);
		$count++;
		if ((int)($item['ordered_id'])>(int)$positionId){
			$positionId = $item['ordered_id'];
		}
	}
	
    if ($order_del_name!=''&&$info_order_del_cost>0) {
		$positionId = $positionId+1;
		$info_order_sber_arr['cartItems']['items'][$count]['positionId'] = (int)$positionId;
		$info_order_sber_arr['cartItems']['items'][$count]['name'] = (string)$order_del_name;
		$info_order_sber_arr['cartItems']['items'][$count]['quantity']['value'] = '1';
		$info_order_sber_arr['cartItems']['items'][$count]['quantity']['measure'] = 'шт.';
		$info_order_sber_arr['cartItems']['items'][$count]['itemAmount'] = (int)((float)$info_order_del_cost*100);
		$sber_total +=$info_order_sber_arr['cartItems']['items'][$count]['itemAmount'];
		$info_order_sber_arr['cartItems']['items'][$count]['itemCode'] = 'Доставка';
        $info_order_sber_arr['cartItems']['items'][$count]['itemPrice'] =(int)((float)$info_order_del_cost*100);
	} 
	
	$info_order_sber_json = json_encode($info_order_sber_arr);
    //echo '<script>console.log('.$info_order_sber_json.');</script>';
	
	$args = array(
		'userName' => $this->sber['login'],
		'password' => $this->sber['pass'],
		'orderNumber' => $this->order['id'],
		'amount' => (float)$sber_total,
		'returnUrl' =>$this->sber['successUrl'],
		'currency' =>$this->sber['currency_sber'],
		'failUrl'=>$this->sber['failedUrl'],
    'orderBundle'=>$info_order_sber_json,
    'taxSystem'=>(int)$this->sber['tax']
	);
	
	$rbsCurl = curl_init();
	curl_setopt_array($rbsCurl, array(
		CURLOPT_URL => $action_adr,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => http_build_query($args)
	));
	$response = curl_exec($rbsCurl);
	curl_close($rbsCurl);

	$response = json_decode($response, true);
	
	$errorCode = $response['errorCode'];
	$return_link_sber =get_option("wpshop.cartpage");

	if ($errorCode == 0) {
		echo '<h3>'.__('Thank you for your order, please click the button `Pay` below to pay.', 'wp-shop').'</h3><br><br>'.
		'<a class="wpshop-button" href="'.$response['formUrl'].'">'.__('Pay', 'wp-shop').'</a>'.
		'<a class="wpshop-button" href="'.$return_link_sber.'">'.__('Refuse to pay and go to shopping cart', 'wp-shop').'</a><br><br>';
	}
	else {
		echo '<h3>'.__('Error #'.$errorCode.': '.$response['errorMessage'], 'wp-shop').'</h3><br><br>'.
		'<a class="wpshop-button" href="'.$return_link_sber.'">'.__('Refuse to pay and go to shopping cart', 'wp-shop').'</a><br><br>';
	}

/*Artpay begin*/
} elseif ($this->order['info']['payment'] == "ap") { ?>

<form method="POST" action="<?=$this->ap['test'] ? 'https://gateway-sandbox-artpay.dev-3c.by/create/' : 'https://engine.artpay.by/create/'?>">
<?php

	$data =
		array(
			'ap_storeid' => $this->ap['id'],
			'ap_order_num' => $this->order['id'],
			'ap_amount' => round($order->getTotalSum(), 2),
			'ap_currency' => 'BYN',
			'ap_client_dt' => date('c'),
			'ap_invoice_desc' => 'Оплата заказа #' . $this->order['id'],
			'ap_test' => $this->ap['test']
		);
		
		
	$string = null;
	uksort($data, 'strnatcmp');
		
	foreach ($data as $param => $value)
		$string .= $value . ';';
		
	$string .= $this->ap['pass1'];
	$key =  hash('sha512', $string);
		
	$data['ap_signature'] =  hash('sha512', $string);

	foreach ($data as $name => $value) {
?>
	<input type="hidden" name="<?=$name?>" value="<?=$value?>"/>
<?php
	}
?>
	<input type="submit" class="wpshop-button" value="Оплатить через ArtPay"/>
	</form>
	
<?php
	/*Artpay end*/
 } elseif ($this->order['info']['payment'] == "icredit") {

    $ICREDIT_PAYMENT_GATEWAY_URL_TEST = 'https://testicredit.rivhit.co.il/API/PaymentPageRequest.svc/GetUrl';
    $ICREDIT_PAYMENT_GATEWAY_URL_REAL = 'https://icredit.rivhit.co.il/API/PaymentPageRequest.svc/GetUrl';
		
	$full_name = $this->order['info']['username'];
	$full_name = rtrim ($full_name);
	if(isset($full_name)&&$full_name!=''){
		$full_name_arr = explode(" ", $full_name);
	}
	
	$icredit_data = array();
	$icredit_data['GroupPrivateToken'] = $this->icredit['token'];
	$icredit_data['Order'] = $this->order['id'];
	$icredit_data['IPNURL']= get_bloginfo("url");
	$icredit_data['RedirectURL']= $this->icredit['success'];
	$icredit_data['Currency']=$this->icredit['currency']; 
	$icredit_data['EmailAddress']= $this->order['info']['email'];
	$icredit_data['Custom1'] = session_id();
	
	$icredit_items = array();
	$whole_price = 0;
	foreach ($this->order['offers'] as $key=>$item) {
		$icredit_items[$key]['Quantity'] = $item['partnumber'];
		$icredit_items[$key]['UnitPrice'] = $item['price'];
		$icredit_items[$key]['Description'] = $item['name'];
		$whole_price = $whole_price + (float) $item['price'] * (float)$item['partnumber'];
	}
	
	if (isset($icredit_items)&&is_array($icredit_items)) {
		$icredit_data['Items'] = $icredit_items;
	}
	
	if (is_array($full_name_arr)&&$full_name_arr[0]!='') {
		$icredit_data['CustomerFirstName'] = $full_name_arr[0];
	}
	
	$discont = $this->order['info']['discount'];
	if (isset($discont)&&$discont > 0&&isset($whole_price)&&$whole_price>0) {
		$price_with_disc= ($whole_price/100) * (float) $discont;
		$icredit_data['Discount'] = $price_with_disc;
	}
	
	if (is_array($full_name_arr)&&$full_name_arr[1]!='') {
		$icredit_data['CustomerLastName'] =  $full_name_arr[1];
	}
	
	if ($this->icredit['test']){
		$icredit_payment_gateway_url = $ICREDIT_PAYMENT_GATEWAY_URL_TEST;
	}else{
		$icredit_payment_gateway_url = $ICREDIT_PAYMENT_GATEWAY_URL_REAL;
	}
	
	$jsonData = json_encode($icredit_data);
		

	$rbsCurl = curl_init();
	curl_setopt_array($rbsCurl, array(
		CURLOPT_URL => $icredit_payment_gateway_url,
		CURLOPT_HTTPHEADER => array("Content-type: application/json"),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $jsonData
	));
	$response = curl_exec($rbsCurl);
	
	curl_close($rbsCurl);
	
	
	$j_response = json_decode($response);
	
	$errorCode = $j_response->Status;
	$return_link_icredit =get_option("wpshop.cartpage");

	if ($errorCode == 0) {
		echo '<h3>'.__('Thank you for your order, please click the button below to pay.', 'wp-shop').'</h3><br><br>'.
		'<a class="wpshop-button" href="'.$j_response->URL.'">'.__('Pay ICredit', 'wp-shop').'</a>'.
		'<a class="wpshop-button" href="'.$return_link_icredit.'">'.__('Refuse to pay and go to shopping cart', 'wp-shop').'</a><br><br>';
	}
	else {
		echo '<h3>'.__('Error #'.$errorCode, 'wp-shop').'</h3><br><br>'.
		'<a class="wpshop-button" href="'.$return_link_icredit.'">'.__('Refuse to pay and go to shopping cart', 'wp-shop').'</a><br><br>';
	}

} elseif ($this->order['info']['payment'] == "yandex_kassa") {
?>
<form action="https://<?php if($this->yandex_kassa['test']==true){echo 'demo';}?>money.yandex.ru/eshop.xml" method="POST" id="payment_form">
	<?php  
		$info_order_yandex = $order->getOrderItemsFull($this->order['id']); 
		$info_order_yandex_arr = array();
		$info_order_yandex_arr['customerContact'] = $order->getOrderEmail();
		foreach ($info_order_yandex as $key=>$item){
			$info_order_yandex_arr['items'][$key]['quantity']=$item['count'];
			$info_order_yandex_arr['items'][$key]['price']['amount']=$item['cost'];
			$info_order_yandex_arr['items'][$key]['text']=$item['name'];
      $info_order_yandex_arr['items'][$key]['tax']=$this->yandex_kassa['tax'];
		}
		$info_order_yandex_json = json_encode($info_order_yandex_arr);
	?>
	<input type="hidden" name="shopId" value="<?php  echo $this->yandex_kassa['shopId'];?>" />
	<input type="hidden" name="scid" value="<?php  echo $this->yandex_kassa['scid'];?>" />
	<input type="hidden" name="sum" value="<?php  echo $order->getTotalSum();?>" />
	<input type="hidden" name="customerNumber" value="<?php echo $this->order['id'];?>" />
	<input type="hidden" name="orderNumber" value="<?php echo $this->order['id'];?>" />
	<input type="hidden" name="custom" value="<?php echo session_id();?>" />
	<input type="hidden" name="cps_email" value="<?php echo $order->getOrderEmail();?>" />
	<input type="hidden" name="shopSuccessURL" value="<?php echo $this->yandex_kassa['successUrl'];?>" />
	<input type="hidden" name="shopFailURL" value="<?php echo $this->yandex_kassa['failedUrl'];?>" />
	<input type="hidden" name="paymentType" value="<?php echo $_GET['paymentType'];?>" />
	<input type="hidden" name="ym_merchant_receipt" value='<?php echo $info_order_yandex_json;?>' />
	<input type="hidden" name="cms_name" value="wordpress_wp-shop-original" />
	<input type="submit" class="wpshop-button" value="<?php  echo __('Pay Yandex kassa', 'wp-shop'); // Оплатить Yandex касса ?>"/>
</form>
<?php 
}elseif($this->order['info']['payment'] == "robokassa"){

// регистрационная информация (логин, пароль #1)
// registration info (login, password #1)
$mrh_login = $this->robokassa['login'];
$mrh_pass1 = $this->robokassa['pass1'];

// номер заказа
// number of order
$inv_id = $this->order['id'];

// описание заказа
// order description
$inv_desc = urlencode(__('Order', 'wp-shop')." #{$this->order['id']} ".__('from site', 'wp-shop')." {$_SERVER['HTTP_HOST']}.");

// сумма заказа
// sum of order
$out_summ = $order->getTotalSum();

// тип товара
// code of goods
$shp_item = 1;

// предлагаемая валюта платежа
// default payment e-currency
$in_curr = "PCR";

// язык
// language
$culture = "ru";

// кодировка
// encoding
$encoding = "utf-8";

// формирование подписи
// generate signature
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");

// HTML-страница с кассой
// ROBOKASSA HTML-page
print "<script language=JavaScript ".
      "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormMS.js?".
//      "src='https://test.robokassa.ru/Handler/MrchSumPreview.ashx?".
      "MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&IncCurrLabel=$in_curr".
      "&Desc=$inv_desc&SignatureValue=$crc&Shp_item=$shp_item".
      "&Culture=$culture&Encoding=$encoding'></script>";

?>
<?php 
}elseif($this->order['info']['payment'] == "ek"){
$info_order_ek = $order->getOrderItemsFull($this->order['id']); 
$info_order_ek_arr = array();

foreach ($info_order_ek as $key=>$item){
  $info_order_ek_arr[$key]['Title']=(string)strip_tags($item['name']);
  $info_order_ek_arr[$key]['Quantity']=number_format((float)$item['count'],3,'.', '');
  $info_order_ek_arr[$key]['UnitPrice']=number_format((float)$item['cost'],2,'.', '');
  $info_order_ek_arr[$key]['SubTotal']=number_format((float)$item['cost']*(float)$item['count'],2,'.', '');
  if($this->ek['tax']!='') {
    $info_order_ek_arr[$key]['TaxType']=(string)$this->ek['tax'];
  }else {
    $info_order_ek_arr[$key]['TaxType']='tax_ru_1';
  }
  $item_tax = 0;
  if($this->ek['tax']=='tax_ru_1'||$this->ek['tax']==''||$this->ek['tax']=='tax_ru_2') {
    $item_tax = 0;
  }else if($this->ek['tax']=='tax_ru_3') {
    $item_tax = ((float)$item['cost']*(float)$item['count'])*0.1;
  }else if($this->ek['tax']=='tax_ru_4') {
    $item_tax = ((float)$item['cost']*(float)$item['count'])*0.18;
  }else if($this->ek['tax']=='tax_ru_5') {
    $item_tax = ((float)$item['cost']*(float)$item['count'])*10/110;
  }else if($this->ek['tax']=='tax_ru_6') {
    $item_tax = ((float)$item['cost']*(float)$item['count'])*18/118;
  }
  $info_order_ek_arr[$key]['Tax']=number_format((float)$item_tax, 2,'.', '');
}

$info_order_ek_json = json_encode($info_order_ek_arr);

$fields = array(); 

// Добавление полей формы в ассоциативный массив
$fields["WMI_MERCHANT_ID"]    = $this->ek['wmCheck'];
$fields["WMI_PAYMENT_AMOUNT"] = $order->getTotalSum();
$fields["WMI_CURRENCY_ID"]    = $this->ek['currency_ek'];
$fields["WMI_PAYMENT_NO"]     = $this->order['id'];
$fields["WMI_DESCRIPTION"]    = __('Order', 'wp-shop')." #{$this->order['id']} ".__('from site', 'wp-shop')." {$_SERVER['HTTP_HOST']}.";
$fields["WMI_SUCCESS_URL"]    = $this->ek['successUrl'];
$fields["WMI_FAIL_URL"]       = $this->ek['failedUrl'];
$fields["WMI_CUSTOMER_EMAIL"] = $order->getOrderEmail();
$fields["WMI_ORDER_ITEMS"] = $info_order_ek_json;
$fields["SESSION_USER"]       = session_id();

//Если требуется задать только определенные способы оплаты, раскоментируйте данную строку и перечислите требуемые способы оплаты.
// if (isset($_GET['rk'])){$fields["WMI_PTENABLED"] = $_GET['rk'];} 

// Формирование HTML-кода платежной формы

print "<form action=\"https://merchant.w1.ru/checkout/default.aspx\" method=\"POST\">";

foreach($fields as $key => $val)
{
    if (is_array($val))
       foreach($val as $value)
       {
     print "<input type=\"hidden\" name=\"$key\" value=\"$value\"/>";
       }
    else	    
       print "<input type=\"hidden\" name=\"$key\" value=\"$val\"/>";
}
$button_name = __('Pay EK', 'wp-shop');// Оплатить в Единой кассе
print "<input type=\"submit\" class=\"wpshop-button\" value=\"".$button_name."\"/></form>";
?>

<?php 
}elseif($this->order['info']['payment'] == "simplepay"){

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function makeSigStr ( $strScriptName, array $arrParams, $strSecretKey ) {
  unset($arrParams['sp_sig']);
  
  ksort($arrParams);

  array_unshift($arrParams, $strScriptName);
  array_push   ($arrParams, $strSecretKey);

  return join(';', $arrParams);
}

$fields = array(); 

// Добавление полей формы в ассоциативный массив
$fields["sp_outlet_id"]    = $this->simplepay['outlet_id'];
$fields["sp_order_id"] = $this->order['id'];
$fields["sp_partner_id"]    = '18';
$fields["sp_amount"]     = $order->getTotalSum();
$fields["sp_description"]    = __('Order', 'wp-shop')." #{$this->order['id']} ".__('from site', 'wp-shop')." {$_SERVER['HTTP_HOST']}.";
$fields["sp_user_params"]    =  session_id();
$fields["sp_currency"]       = $this->simplepay['currency_simplepay'];
$fields["sp_salt"]       = rand(21,43433);
$fields["sp_user_ip"] = get_client_ip();
$fields["sp_user_name"] = $this->order['info']['username'];
$fields["sp_user_contact_email"] = $this->order['info']['email'];

$sp_sig_before  = makeSigStr('payment',$fields,$this->simplepay['secure']);
$fields["sp_sig"] =  md5($sp_sig_before);
?>

<form action="https://api.simplepay.pro/sp/payment" method="POST"> 
   
    <?php foreach($fields as $key => $val)
    {
    if (is_array($val))
       foreach($val as $value)
       {
     print "<input type=\"hidden\" name=\"$key\" value=\"$value\"/>";
       }
    else	    
       print "<input type=\"hidden\" name=\"$key\" value=\"$val\"/>";
    }?>
    <input type="submit" class=\"wpshop-button\" value="<?php  echo __('Pay Simplepay', 'wp-shop'); // Оплатить через Simplepay ?>"/>
  </form>

<?php 
} elseif($this->order['info']['payment'] == "paypal"){

$fields = array(); 

// Добавление полей формы в ассоциативный массив

$fields["cmd"]         = '_cart';
$fields["upload"] 	   = 1;
$fields["business"]    = $this->paypal['email'];
$fields["amount_1"]      = $order->getTotalSum();
$text = __('Order', 'wp-shop')." #{$this->order['id']} ".__('from site', 'wp-shop')." {$_SERVER['HTTP_HOST']}.";
$convertedText = mb_convert_encoding($text, 'utf-8', mb_detect_encoding($text));
$fields["item_name_1"]      = $convertedText;
$fields["currency_code"] 	   = $this->paypal['currency_paypal'];
$fields["no_shipping"] 	   = 1;
$fields["invoice"] 	   = $this->order['id'];
$fields["custom"] 	   = session_id();
$fields["return"] 	   = $this->paypal['success'];
$fields["notify_url"] 	   = 'http://'.$_SERVER['HTTP_HOST'];
// Формирование HTML-кода платежной формы
if($this->paypal['test']==true){print "<form action=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" method=\"post\" accept-charset=\"UTF-8\">";}
else{ print "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" accept-charset=\"UTF-8\">";}

foreach($fields as $key => $val)
{
    if (is_array($val))
       foreach($val as $value)
       {
     print "<input type=\"hidden\" name=\"$key\" value=\"$value\"/>";
       }
    else	    
       print "<input type=\"hidden\" name=\"$key\" value=\"$val\"/>";
}

print "<input type=\"image\" value=\"PayPal\" src=\"https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif\" alt=\"Submit button\" align=\"left\" style=\"margin-right:7px;\" /></form>";

}elseif($this->order['info']['payment'] == "sofort"){
	require_once(WPSHOP_CLASSES_DIR.'/sofort_lib/payment/sofortLibSofortueberweisung.inc.php');
	
	if(isset($this->sofort['conf_key'])&&$this->sofort['conf_key']!=''){
	  $configkey = $this->sofort['conf_key'];
	  $Sofortueberweisung = new Sofortueberweisung($configkey);

	  $Sofortueberweisung->setAmount($order->getTotalSum());
	  $Sofortueberweisung->setCurrencyCode('EUR');
	  $Sofortueberweisung->setReason($this->order['id'],$_SERVER['HTTP_HOST']);
	  $Sofortueberweisung->setUserVariable(session_id());

	  $Sofortueberweisung->setSuccessUrl($this->sofort['successUrl'], true);
	  $Sofortueberweisung->setAbortUrl($this->sofort['failedUrl']);
	  $Sofortueberweisung->setNotificationUrl($this->sofort['resultUrl']);
	  if (isset($this->sofort['notifEmail'])&&$this->sofort['notifEmail']!=''){
		$Sofortueberweisung->setNotificationEmail($this->sofort['notifEmail']);
	  }
	  
	  // $Sofortueberweisung->setSenderSepaAccount('SFRTDE20XXX', 'DE06000000000023456789', 'Max Mustermann');
	  // $Sofortueberweisung->setSenderCountryCode('DE');
	  // $Sofortueberweisung->setNotificationUrl('http://www.google.de', 'loss,pending');
	  // $Sofortueberweisung->setNotificationUrl('http://www.yahoo.com', 'loss');
	  // $Sofortueberweisung->setNotificationUrl('http://www.bing.com', 'pending');
	  // $Sofortueberweisung->setNotificationUrl('http://www.sofort.com', 'received');
	  // $Sofortueberweisung->setNotificationUrl('http://www.youtube.com', 'refunded');
	  // $Sofortueberweisung->setNotificationUrl('http://www.youtube.com', 'untraceable');
		if (isset($this->sofort['trust'])&&$this->sofort['trust']){
			$Sofortueberweisung->setCustomerprotection(true);
		}
	  $Sofortueberweisung->sendRequest();

	  if($Sofortueberweisung->isError()) {
		//SOFORT-API didn't accept the data
		echo $Sofortueberweisung->getError();
	  } else {
		//buyer must be redirected to $paymentUrl else payment cannot be successfully completed!
		$paymentUrl = $Sofortueberweisung->getPaymentUrl();
		$Sofortueberweisung->safeRedirect($paymentUrl);
	  }
	}
} elseif($this->order['info']['payment'] == "chronopay"){
if($this->chronopay['order']==true){
$sign = md5($this->chronopay['product_id'].'-'.$order->getTotalSum().'-'.$this->order['id'].'-'.$this->chronopay['sharedsec']);}else{
$sign = md5($this->chronopay['product_id'].'-'.$order->getTotalSum().'-'.$this->chronopay['sharedsec']);
}
?>
  <form action="https://payments.chronopay.com/" method="POST"> 
    <input type="hidden" name="product_id" value="<?php  echo $this->chronopay['product_id'];?>" /> 
    <input type="hidden" name="product_price" value="<?php  echo $order->getTotalSum();?>" />
    <input type="hidden" name="order_id" value="<?php echo $this->order['id'];?>" />     
    <input type="hidden" name="cs1" value="<?php echo session_id();?>" /> 
    <input type="hidden" name="cb_type" value="P" />
    <input type="hidden" name="cb_url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>" /> 
    <input type="hidden" name="success_url" value="<?php  echo $this->chronopay['success'];?>" /> 
    <input type="hidden" name="decline_url" value="<?php  echo $this->chronopay['failed'];?>" /> 
    <input type="hidden" name="sign" value="<?php echo $sign; ?>" /> 
    <input type="submit" class=\"wpshop-button\" value="<?php  echo __('Pay Chronopay', 'wp-shop'); // Оплатить через ChronoPay ?>"/>
  </form>
<?php  } else {?>
<script type="text/javascript">
jQuery(document).ready(function()
{
	window.__cart.reset();
});
</script>
<?php } ?>
<?php if(isset($_GET['ya_dostavka'])&&$_GET['ya_dostavka']==1){
  $yandex_delivery_opts = $this->yandex_delivery;
  if (isset($yandex_delivery_opts['cart_code'])&&isset($yandex_delivery_opts['activate'])&&$yandex_delivery_opts['cart_code']!='') {
  echo $yandex_delivery_opts['cart_code'];?>

  <script type="text/javascript">
    ydwidget.ready(function(){
      ydwidget.initCartWidget({
        // Завершение загрузки корзинного виджета.
        'onLoad': function () {
          // Подтверждаем заказ и передаем любые данные со страницы успешного оформления, если 
          // необходимо. В данном случае, номер заказа (чтобы номер заказа в CMS и в Яндекс.Доставке
          // совпадал)
          ydwidget.cartWidget.order.confirmOrder({'order_num': '<?php echo $this->order['id'];?>'})
          .done(function (data) {
             if (data.status == 'ok') {
               console.log('Заказ создан успешно.', data)
              } else {
                // При правильной интеграции, на этом этапе ошибки быть не должно, так как вся 
                // валидация происходит на этапе вызова createOrder, и здесь в cookie уже валидные
                // данные
                console.log('При создании заказа были ошибки.', data)
              }
          });
        }
      })
    })
  </script>
<?php  }}?>