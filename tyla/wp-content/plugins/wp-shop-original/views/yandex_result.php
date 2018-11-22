<?php 
header_remove(); 
if ($_POST['action'] == 'checkOrder'){ 
			$yandex_set = get_option("wpshop.payments.yandex_kassa");
			$hash= md5($_POST['action'].';'.$_POST['orderSumAmount'].';'.$_POST['orderSumCurrencyPaycash'].';'.$_POST['orderSumBankPaycash'].';'.$_POST['shopId'].';'.$_POST['invoiceId'].';'.$_POST['customerNumber'].';'.$yandex_set['shopPassword']);
			if (strtolower($hash) != strtolower($_POST['md5'])) {
					$code = 1;
				} else {
					global $wpdb;
					$order = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wpshop_orders WHERE order_id = '.(int)$_POST['orderNumber']);
					if (!$order) {
						$code = 200;
					} else {
						$code = 0;
					}
			} 
			header_remove(); 	
			include WPSHOP_DIR ."/views/response_xml.php";
			exit;
		}
		
		if ($_POST['action'] == 'paymentAviso'){ 
			$yandex_set = get_option("wpshop.payments.yandex_kassa");
			$hash= md5($_POST['action'].';'.$_POST['orderSumAmount'].';'.$_POST['orderSumCurrencyPaycash'].';'.$_POST['orderSumBankPaycash'].';'.$_POST['shopId'].';'.$_POST['invoiceId'].';'.$_POST['customerNumber'].';'.$yandex_set['shopPassword']);
			if (strtolower($hash) != strtolower($_POST['md5'])) {
					$code = 1;
			} else {
					global $wpdb;
					$order = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wpshop_orders WHERE order_id = '.(int)$_POST['orderNumber']);
					if (!$order) {
						$code = 200;
					} else {
						$code = 0;
					}
			} 
			if ($code == 0){
				global $wpdb;
				$wpdb->query("DELETE FROM {$wpdb->prefix}wpshop_selected_items WHERE selected_items_session_id='".$_POST["custom"]."'");
				Wpshop_Orders::setStatus($_POST["orderNumber"],1);
			}
			header_remove(); 	
			include WPSHOP_DIR ."/views/aviso_response_xml.php";
			exit;
		}
?>