<?php 

class Wpshop_RecycleBin
{
	private $view;
	static private $instance = null;
	private $orderDataTmp = null;

	public function getLastOrder()
	{
		if ($this->orderDataTmp == null)
		{
			echo "no";
			//throw Exception("no saving order");
		}
		return $this->orderDataTmp;
	}

	static public function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new Wpshop_RecycleBin();
		}
		return self::$instance;
	}

  private function __construct()
	{
		$this->view = new Wpshop_View();
		add_filter('the_content', array(&$this,"recycleBinAction"),102);
	}

	/**
	 * Функция сохраняет заказ!!!
	 *
	 * @param array $orders заказы переданные в массиве
	 * @return boolean
	 */
	public function saveOrder(Array $orders) {
		global $wpdb;
		/**
		 * @todo Действие очищает корзину в обычном режиме
		 */
     
      
		if (!get_option("wpshop.payments.activate"))
		{
			$this->view->render('js.inc.clearCart.php');
		}
    
		$currentUser = wp_get_current_user();

		$status = 0;
		$wpdb->insert( "{$wpdb->prefix}wpshop_orders", array( 
			'order_date' => time(),
			'order_discount' => $orders['info']['discount'],
			'order_payment' => $orders['info']['payment'],
			'client_name' => $orders['info']['username'],
			'client_email' => $orders['info']['email'],
			'client_ip' => $orders['info']['ip'],
			'client_id' => $currentUser->ID,
			'order_status' => $status,
			'order_delivery' => $orders['info']['delivery'],
			'order_comment' => $orders['info']['comment'],
			'order_promo' => $orders['info']['promo'],
			'order_delivery_cost' => $orders['info']['custom_delivery_cost'],
			'order_delivery_adress' => $orders['info']['custom_delivery_adress']
		),
		array('%d','%f','%s','%s','%s','%s','%d','%d','%s','%s','%s','%f','%s') );

		$pid = $wpdb->insert_id;
         
		$this->orderDataTmp = $orders;
		$this->orderDataTmp['id'] = $pid;

		foreach($orders['orders'] as $order) {
			$digitCount = get_post_meta($order->selected_items_item_id,"digital_count",true);		
			if (empty($digitCount)) {
				$digitCount = -1;
			}
			$digitLive = get_post_meta($order->selected_items_item_id,"digital_live",true);
			if (empty($digitLive)) {
				$digitLive = -1;
			}			
			$wpdb->insert("{$wpdb->prefix}wpshop_ordered" , array( 'ordered_pid' => $pid, 'ordered_name' => $order->selected_items_name, 'ordered_cost' => $order->selected_items_cost,'ordered_count' => $order->selected_items_num,'ordered_key' => $order->selected_items_key,'ordered_page_id'=>$order->selected_items_item_id,'ordered_digit_count'=>$digitCount,'ordered_digit_live'=>$digitLive) , array( "%d" , "%s", "%f", "%d", "%s","%d","%d","%d"));
		}
		
		$ya_metrika = get_option("wpshop.yandex_metrika");
		if (isset($ya_metrika)&&$ya_metrika!=''){
			$code = '';
			$code .= "window.dataLayer = window.dataLayer || [];";
			$code .= "dataLayer.push({
			'ecommerce': {
				'purchase': {
					'actionField': {
						'id' : '{$pid}'
					},
					'products': [";
	
			foreach($orders['orders'] as $order) {
				$code .= '{';		
				$code .= "'id': '{$order->selected_items_item_id}',";
				$code .= "'name': '{$order->selected_items_name}',";
				$code .= "'price': {$order->selected_items_cost},";
				$code .= "'quantity': {$order->selected_items_num}";
				$code .= '},';
			}
			$code .= "]}}});";
			$this->view->code = $code;
			$this->view->render('yandex_metrika_res.php');
		}
	
		$order = new Wpshop_Order($pid);
		$ordered_products = $order->getOrderItemsFull($pid);
		if ($ordered_products){
			foreach($ordered_products as $product) {
				$product['count'];
				$meta = get_post_custom($product['post_id']);
				foreach ($meta as $key => $val){
					if ( preg_match('/^cost_(\d+)/i', $key, $m) ){
						$costs[$m[1]] = $val[0];
					}
				}
			
				if (count($costs) > 0){
				
					foreach ($costs as $key => $val){
						$val_r = round($val,2);
						$key_order='';
						if ($product['cost']==$val_r){
							$key_order=$key;
							$name='sklad_'.$key_order;
							$old = get_post_meta( $product['post_id'],$name, true );
							if($old){
								$new = (int)$old - $product['count'];
								update_post_meta($product['post_id'],$name,$new); 
							}
						}
					}
				}
			}
		}
	
				if (get_option("wpshop.partner_param")&&get_option("wpshop.partner_pass")){
			$partner_id = get_option("wpshop.partner_param");
			$partner_pass = get_option("wpshop.partner_pass");
			$partner_project_id = get_option("wpshop.partner_project_id");
     
			$order_items = array();
			foreach($orders['orders'] as $key =>$order) {
				$prod_id = get_post_meta($order->selected_items_item_id,'prod_id',true);
				if (isset($partner_project_id)&&$partner_project_id!='') {
					$prod_project_id = get_post_meta($order->selected_items_item_id,'project_id',true);
					$prod_project_id_ar = explode(",", $prod_project_id);
					if (isset($prod_project_id)&&in_array($prod_project_id, $prod_project_id_ar)) {
						if (isset($prod_id)&&$prod_id!='') {
							$order_items[$key]['prod_id']=$prod_id; 
							$order_items[$key]['quantity']=$order->selected_items_num; 
						} 
					}
				}else{
					if (isset($prod_id)&&$prod_id!='') {
						$order_items[$key]['prod_id']=$prod_id; 
						$order_items[$key]['quantity']=$order->selected_items_num; 
					}       
				}				
			}
        
			$data = array(
				'partner'=>array(
				  'aid'=>$partner_id,
				  'password'=>$partner_pass
				),
				'customer'=>array(
				  'client_name'=>$orders['info']['username'],
				  'client_email'=>$orders['info']['email'],
				  'client_lastname'=>$orders['info']['userfamily'],
				  'client_phone'=>$orders['info']['usercell']
				),
				'items'=>$order_items
			);
          
			$data_string = json_encode($data); 
			$ch = curl_init();  
			curl_setopt($ch, CURLOPT_URL, "https://www.top-shop.ru/partner-api/add_order/");
			
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('cache-control: no-cache','Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.$data_string);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			  
			$response = curl_exec($ch);
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);  
		   
			echo "<script>console.log( 'status $status, response $response, curl_error " . curl_error($ch) . ", curl_errno " . curl_errno($ch)."');</script>";  
			//echo "<script>console.log( 'data:$data_string');</script>";  

    
			/* $partner_id = get_option("wpshop.partner_param");
			$ref= get_bloginfo('url');
			$ch = curl_init();  
			curl_setopt($ch, CURLOPT_URL, "http://partner.mbgenerator.ru/affiliate/goto_offer/{$partner_id}");
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, $ref);
				
			$response = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);  
			$headers = array();

			$header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

			foreach (explode("\r\n", $header_text) as $i => $line){
				if ($i === 0){
				   $headers['http_code'] = $line;
				} else {
				  list ($key, $value) = explode(': ', $line);
				  $headers[$key] = $value;
				}
			}
			  
			$location = explode("?h=", $headers["Location"]);
			$hesh = $location[1];
			 
			$itogo = 0;
			foreach($orders['offers'] as $offer) {
				$price = round($offer['partnumber'] * $offer['price'],2);
				$itogo += $price;
			}
			if ($orders['info']['discount'])
			{
				$itogo = round($itogo - $itogo / 100 * $orders['info']['discount'],2);
			}
			$delivery = Wpshop_Delivery::getInstance()->getDelivery($orders['info']['delivery']);
			if ($delivery) {
				$itogo += $delivery->cost;
			} 
			 
			$url = "http://partner.mbgenerator.ru/affiliate/track_by_hash/{$hesh}/{$pid}/{$itogo}/"; 
			$ch = curl_init();  
			curl_setopt($ch, CURLOPT_URL,$url); // set url to post to  
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);  
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable  
			curl_setopt($ch, CURLOPT_TIMEOUT, 3); // times out after 4s  
			curl_setopt($ch, CURLOPT_REFERER, $ref);
			$result = curl_exec($ch); // run the whole process 
			curl_close($ch); */
		}
		
		do_action('wpshop_custom_delivery_Order_save',$pid,$orders);
		
		ob_start();
		$this->view->order = $orders;
		$this->view->id = $pid;
		$this->view->render("mail/admin.php");
		// отправка почты администратору
		$email = get_option("wpshop.email");
		$user_name = get_option("wpshop.email_name");
		if($user_name) {
			$email_result=$user_name.' <'.$email.'>';
		}else {
			$email_result=$email;
		}
		$siteurl = get_bloginfo('wpurl');
		wp_mail($email, __('New Order','wp-shop')." #{$pid} ".__('from site','wp-shop')." {$siteurl}", ob_get_clean(),
    array("Content-type: text/html; charset=UTF-8","Reply-To: {$email_result}","From:{$email_result}"));

		ob_start();
		$this->view->order = $orders;
		
		if (!get_option("wpshop.mail_activate")){
			$this->view->render("mail/client.php");
		}else{
			$this->view->render("mail/client1.php");
		}
		
		wp_mail($orders['info']['email'], "Re: ".__('Your order','wp-shop')."  #{$pid} ".__('from site','wp-shop')." {$siteurl}", ob_get_clean(),
    array("Content-type: text/html; charset=UTF-8","Reply-To: {$email_result}","From:{$email_result}"));

		if ($payment){
			$this->paymentAction($payment);
		}
		return true;
	}

	public function recycleBinAction($content)
	{
		global $post;
		global $wpdb;
		$ses = session_id();
		
		if ($post->post_excerpt == "tinkoff_success") {
            ob_start();
            $this->view->render("js.inc.clearCart.php");
            $content = $content . ob_get_clean();
        }
		
		if ($post->post_excerpt == "cripto_success") {
            ob_start();
            $this->view->render("js.inc.clearCart.php");
            $content = $content . ob_get_clean();
        }
		
		if ($post->post_excerpt == "wm_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
		}
		
		if ($post->post_excerpt == "sber_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
		}
		
		if ($post->post_excerpt == "icredit_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
		}
		
		if ($post->post_excerpt == "yandex_kassa_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
		}

		if ($post->post_excerpt == "robokassa_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
		}
    
    if ($post->post_excerpt == "primearea_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
		}
		
		if ($post->post_excerpt == "ek_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
		}
		
		if ($post->post_excerpt == "ym_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
			global $wpdb;
			$wpdb->query("DELETE FROM {$wpdb->prefix}wpshop_selected_items WHERE selected_items_session_id='{$ses}'");
		}
		
		if ($post->post_excerpt == "intercassa_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
			global $wpdb;
			$wpdb->query("DELETE FROM {$wpdb->prefix}wpshop_selected_items WHERE selected_items_session_id='{$ses}'");
		}
    
    if ($post->post_excerpt == "simplepay_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
		}
		
		if ($post->post_excerpt == "chronopay_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
			global $wpdb;
			$wpdb->query("DELETE FROM {$wpdb->prefix}wpshop_selected_items WHERE selected_items_session_id='{$ses}'");
		}
		$this->view->dataSend = Wpshop_Forms::isDataSend();

		$this->view->cartCols = array(
			'name' => get_post_meta($post->ID,'cart_col_name',true),
			'price' => get_post_meta($post->ID,'cart_col_price',true),
			'count' => get_post_meta($post->ID,'cart_col_count',true),
			'sum' => get_post_meta($post->ID,'cart_col_sum',true),
			'type' => get_post_meta($post->ID,'cart_col_type',true)
		);
		
		if (get_option('wpshop.payments.activate')){
			$count = 0;
			$this->view->payments = Wpshop_Payment::getInstance()->getPayments();
			foreach($this->view->payments as $key => $value){
				$this->view->payments[$key]->data = get_option("wpshop.payments.{$value->paymentID}");
				$paymentID = $this->view->payments[$key]->paymentID;
				$rows = array();
				$rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts WHERE post_type='wpshopcarts' AND post_excerpt='%s'",$paymentID));
				if(isset($rows)&&is_array($rows)) {
					foreach($rows as $cart) {
						$this->view->payments[$key]->data['cart_url'] = get_permalink($cart->ID);
						break;
					}
				}
			}
		}

		$this->view->minzakaz = get_option('wpshop.cart.minzakaz');
		$this->view->discount = get_option('wpshop.cart.discount');
		$this->view->minzakaz_info = get_option('wpshop.cart.minzakaz_info');
		$this->view->cform = get_option('wp-shop_cform');
		$this->view->delivery = Wpshop_Delivery::getInstance()->getDeliveries();

		ob_start();
		if (Wpshop_Forms::isDataSend()){
			/** Получаем сделанный заказ */
			$this->view->order = Wpshop_RecycleBin::getInstance()->getLastOrder();

			/** Проверяем оплачено ли через Web-Money,EK, Robokassa и если да, то устанавливаем нужные переменные */
			if ($this->view->order['info']['payment'] == "tinkoff") {
        $this->view->tinkoff = get_option("wpshop.payments.tinkoff");
      }
			if ($this->view->order['info']['payment'] == "cripto"){
				$this->view->cripto = get_option("wpshop.payments.cripto");
			}
			
			if ($this->view->order['info']['payment'] == "wm"){
				$this->view->wm = get_option("wpshop.payments.wm");
			}
			if ($this->view->order['info']['payment'] == "ym"){
				$this->view->ym = get_option("wpshop.payments.ym");
			}
			if ($this->view->order['info']['payment'] == "yandex_kassa"){
				$this->view->yandex_kassa = get_option("wpshop.payments.yandex_kassa");
			}
			if ($this->view->order['info']['payment'] == "robokassa"){
				$this->view->robokassa = get_option("wpshop.payments.robokassa");
			}
			if ($this->view->order['info']['payment'] == "ek"){
				$this->view->ek = get_option("wpshop.payments.ek");
			}
			if ($this->view->order['info']['payment'] == "paypal"){
				$this->view->paypal = get_option("wpshop.payments.paypal");
			}
      if ($this->view->order['info']['payment'] == "primearea"){
				$this->view->primearea = get_option("wpshop.payments.primearea");
			}
			if ($this->view->order['info']['payment'] == "sber"){
				$this->view->sber = get_option("wpshop.payments.sber");
			}
			if ($this->view->order['info']['payment'] == "interkassa"){
				$this->view->interkassa = get_option("wpshop.payments.interkassa");
			}
			if ($this->view->order['info']['payment'] == "icredit"){
				$this->view->icredit = get_option("wpshop.payments.icredit");
			}
			if ($this->view->order['info']['payment'] == "sofort"){
				$this->view->sofort = get_option("wpshop.payments.sofort");
			}
			if ($this->view->order['info']['payment'] == "simplepay"){
				$this->view->simplepay = get_option("wpshop.payments.simplepay");
			}
			if ($this->view->order['info']['payment'] == "chronopay"){
				$this->view->chronopay = get_option("wpshop.payments.chronopay");
			}
		  if ($this->view->order['info']['payment'] == "ap")
			{
				$this->view->ap = get_option("wpshop.payments.ap");
			}
			$this->view->yandex_delivery = get_option("wpshop_yandex_delivery");
			$this->view->render("RecycleBinAfterSend.php");
		}else{
			if(isset($cform_name)){
				$form = Wpshop_Forms::getInstance()->getFormByName($cform_name);  
			}
			$this->view->yandex_delivery = get_option("wpshop_yandex_delivery");
			$this->view->render("RecycleBin.php");
		}
		return str_replace(CART_TAG, ob_get_clean(), $content);
	}

	public function paymentAction($payment)
	{
		$totalSum = 0;
		foreach($this->orderDataTmp['offers'] as $good)	{
			$totalSum += $good['price'];
		}

		if ($payment == "wm"){
			$this->view->payment_no = $this->orderDataTmp['id'];
			$this->view->amount = $totalSum;
			$this->view->render("wm.redirect.php");
		}
	}

	public static function getCformsName($POSTData) {
		if (isset($POSTData['payment']) && !empty($POSTData['payment'])) {
			$cform_name = "wpshop-" . $POSTData['payment'];
		} else {	
			$cform_name = get_option("wp-shop_cform");
		}
		return $cform_name;
	}

	public static function actionOrder($POSTData) {		
    
		$cform_name = self::getCformsName($POSTData);
		$orders = Wpshop_Orders::getCartOrders();
		
		/*@TODO проверить хранится ли скидка еще здесь*/		
		$discount = $_COOKIE['wpshop_discount'];
		
		$allInfo = array();
		$sum = 0;
		$final =0;
		$promo = 0;
		$promo_id = 0;
		foreach($orders as $key => $order) {
			$offers = &$allInfo['offers'][];
			$offers['name'] = $order->selected_items_name;
			$offers['price'] = $order->selected_items_cost;
			
			if ($order->selected_items_promo != 0){
				$promo_id = (int) $order->selected_items_promo;
			}
			
			$sum += $order->selected_items_cost * $order->selected_items_num;	
			
			$offers['partnumber'] = $order->selected_items_num;
			$offers['key'] = $order->selected_items_key;
			$offers['post_id'] = $order->selected_items_item_id;
			//$offers['color'] = '';
			//$offers['size'] = '';
		}
		
		if (!empty($discount)) {
			$final = round((100-$discount)*$sum/100,2);
		}else {
			$final = $sum;
		}

		// Отсюда начинаем работу с данными формы
		$allInfo['info'] = array();
		$allInfo['info']['payment'] = $POSTData['payment'];
		if($promo_id != 0){
			$allInfo['info']['promo'] = get_the_title($promo_id);
		}else {
			$allInfo['info']['promo'] = 0;
		}
		$allInfo['info']['ip'] = $_SERVER['REMOTE_ADDR'];
		$allInfo['info']['discount'] = $_COOKIE['wpshop_discount'];
		$allInfo['info']['delivery'] = $POSTData['delivery'];
		$allInfo['info']['custom_delivery_cost'] = $POSTData['custom_delivery_cost'];
		$allInfo['info']['custom_delivery_adress'] = $POSTData['custom_delivery_adress'];
		if(isset($POSTData['custom_delivery_order_id'])) {
			$allInfo['info']['custom_delivery_order_id'] = $POSTData['custom_delivery_order_id'];
		}
		$allInfo['info']['total'] = $final;
		$allInfo['orders'] = $orders;
		
		$form = Wpshop_Forms::getInstance()->getFormByName($cform_name);
		
		
		if(isset($POSTData['file_name'])&&$POSTData['file_name']!='') {
			$decodedText = stripcslashes($POSTData['file_name']);
      
			$json = json_decode($decodedText,true);
			$upload_dir = wp_upload_dir();
			
			if(is_array($json)){
				foreach($json as $key=>$file){
					$mainComment .= __('File','wp-shop').$key.': noid-'.$file."\n";
				}
			
			} 
		} else {
		  $mainComment = "";
		} 

		foreach($form['fields'] as $field) {
			if ($POSTData[$field['postName']]!=''){
				$mainComment .= "{$field['name']} - {$POSTData[$field['postName']]}\n";
			}
			// Определяем E-mail
			if ($field['email']) {
				$allInfo['info']['email'] = $POSTData[$field['postName']];
			}

			/* 	if ($field['order']) {
				$POSTData[$field['postName']] = $final;
			} */

			if ($field['type'] == "Name") {
				$allInfo['info']['username'] = $POSTData[$field['postName']];
			}
			
			if ($field['type'] == "Family") {
				$allInfo['info']['userfamily'] = $POSTData[$field['postName']];
			}
			
			if ($field['type'] == "Phone") {
				$allInfo['info']['usercell'] = $POSTData[$field['postName']];
			}
			
			// Комментарий к заказу
			$allInfo['info']['comment'] = "";
			if ($field['type'] == '$textarea') {
				$allInfo['info']['comment'] = $POSTData[$field['postName']];
			}
			/**
			 * @todo отменить отправку кода с картинки и ненужные скрыте поля
			 */
			if ($field['name'] != Wpshop_Forms::getInstance()->getRightField() && $field['type'] != '$fieldsetstart' && $field['type'] != '$captcha') {
				$row = &$allInfo['cforms'][];
				$row['name'] = $field['name'];
				$row['value'] = $POSTData[$field['postName']];
			}
		}

		$allInfo['info']['comment'] = $mainComment;
		//проверяем что корзина не пуста
		if (get_option("wpshop.partner_param")&&get_option("wpshop.partner_param")!=''){
			if ($allInfo['info']['total']){
				self::getInstance()->saveOrder($allInfo);
				return $POSTdata;
			} else {
				exit();
			}
		} else {
			self::getInstance()->saveOrder($allInfo);
			return $POSTdata;
		}
	}
}
