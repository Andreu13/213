<?php 
header_remove(); 
$status_order = Wpshop_Orders::getStatus_order($_POST["WMI_PAYMENT_NO"]);
if (isset($_POST['WMI_ORDER_STATE'])&&$status_order[0]->order_status==0&&isset($_POST["SESSION_USER"]))
{ 
	function print_answer($result, $description,$res)
	{
		print "WMI_RESULT=" . strtoupper($result) . "&";
		print "WMI_DESCRIPTION=" .urlencode($description);
        if ($res){
            global $wpdb;
            $wpdb->query("DELETE FROM {$wpdb->prefix}wpshop_selected_items WHERE selected_items_session_id='".$_POST["SESSION_USER"]."'");
            Wpshop_Orders::setStatus($_POST["WMI_PAYMENT_NO"],1);
        }
		exit();
	}

	if (strtoupper($_POST["WMI_ORDER_STATE"]) == "ACCEPTED")
	{
						// TODO: Пометить заказ, как «Оплаченный» в системе учета магазина

						print_answer("Ok", "Заказ #" . $_POST["WMI_PAYMENT_NO"] . " оплачен!",true);
            
	} else	{
		// Случилось что-то странное, пришло неизвестное состояние заказа

		print_answer("Retry", "Неверное состояние ". $_POST["WMI_ORDER_STATE"],false);
	}
} 
