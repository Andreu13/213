<?php 
header_remove(); 
$opts = get_option("wpshop.payments.interkassa");
include_once WPSHOP_DIR .'/classes/interkassa/interkassa.php';
	Interkassa::register();
	$shop_id = $opts['shopId'];
	$secret_key = $opts['secret'];
	$shop = Interkassa_Shop::factory(array(
		'id' => $shop_id,
		'secret_key' => $secret_key
	));

	if (count($_POST)&&$_POST['ik_co_id'])
	{
			try {
				$status = $shop->receiveStatus($_POST); // POST is used by default
			} catch (Interkassa_Exception $e) {
				// The signature was incorrect, send a 400 error to interkassa
				// They should resend payment status request until they receive a 200 status
				header('HTTP/1.0 400 Bad Request');
				exit;
			}
			
			$ver = $status->getVerified();
			if ($ver) {
				$order_num = $_POST['ik_pm_no'];
				$st = $status->getState();
				if ($st=='success') {
				  Wpshop_Orders::setStatus($order_num,1);
				}else if($st=='fail'){
				  Wpshop_Orders::setStatus($order_num,2);
				}else if($st=='waitAccept'){
				  Wpshop_Orders::setStatus($order_num,3);
				}				
			}
			
			$payment = $status->getPayment();
	}
?>