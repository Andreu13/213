<?php 



class Wpshop_Order
{
	private $order;
	private $ordered;
	public function __construct($id)
	{
		global $wpdb;
		$param_order = array($id);
		$this->order =  $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}wpshop_orders` WHERE `order_id` = '%d'",$param_order)); 
		$this->ordered = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}wpshop_ordered` WHERE `ordered_pid` = '%d'",$param_order));
	}
	
	public function getDiscount()
	{
		return $this->order->order_discount;
	}
	
	public function getOrderEmail()
	{
		return $this->order->client_email;
	}
  
  public function getOrderClientName()
	{
		return $this->order->client_name;
	}
  
  public function getClient()
	{
		return $this->order->client_id;
	}
	
	public function getDelivery()
	{
		if ($this->order->order_delivery)
		{
			return Wpshop_Delivery::getInstance()->getDelivery($this->order->order_delivery);
		}
		return null;
	}
	
	public function getTotalSumItems()
	{
		foreach($this->ordered as $order)
		{
			if ($this->getDiscount())
			{
				$total += round($order->ordered_cost - ($order->ordered_cost)/100 * $this->getDiscount(),2)*$order->ordered_count ;
			}else {
				$total += round($order->ordered_count * $order->ordered_cost,2);
			}
		}
    
		return round($total,2);
	}
	
	public function getDeliveryCost()
	{
		$total = $this->getTotalSumItems();
		$delivery_cost = 0;
		$delivery = $this->getDelivery();
		if ($delivery){
			if ($total >= $delivery->free_delivery&&$delivery->free_delivery > 0){
			}else{
				$custom_del = (float) $this->order->order_delivery_cost;
				if (isset($custom_del)&&$custom_del>0){
					$delivery_cost = $custom_del;
				}else {
					$delivery_cost = $delivery->cost;
				}
			}
		}
		return round($delivery_cost,2);
	}
	
	public function getTotalSum()
	{
		foreach($this->ordered as $order)
		{
			if ($this->getDiscount())
			{
				$total += round($order->ordered_cost - ($order->ordered_cost)/100 * $this->getDiscount(),2)*$order->ordered_count ;
			}else {
				$total += round($order->ordered_count * $order->ordered_cost,2);
			}
		}
		
		$delivery = $this->getDelivery();
		if ($delivery){
			if ($total >= $delivery->free_delivery&&$delivery->free_delivery > 0){
			}else{
				$custom_del = (float) $this->order->order_delivery_cost;
				if (isset($custom_del)&&$custom_del>0){
					$total += $custom_del;
				}else {
					$total += $delivery->cost;
				}
			}
		}
		return round($total,2);
	}

	public function getOrderItems($order_id) {
		foreach($this->ordered as $key=>$item){
			$product[$key]['ip']=$item->ordered_cost;
			$product[$key]['in']=$item->ordered_name;
			$product[$key]['iq']=$item->ordered_count;
			$product[$key]['ic']=$key.'_'.$order_id;
		}
		return $product;
	}
	
	public function getOrderItemsFull($order_id) {
		foreach($this->ordered as $key=>$item){
      $product_order[$key]['ordered_id']=$item->ordered_id; 
			$product_order[$key]['cost']=$item->ordered_cost;
			$product_order[$key]['name']=$item->ordered_name;
			$product_order[$key]['count']=$item->ordered_count;
			$product_order[$key]['post_id']=$item->ordered_page_id;
			$product_order[$key]['caption']=$item->ordered_key;
		}
		return $product_order;
	}
}
