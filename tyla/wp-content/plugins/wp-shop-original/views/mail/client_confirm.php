<?php 
$order_id = $this->order_id;
$order = new Wpshop_Order($order_id);

$client_name = $order->getOrderClientName();
$client_email = $order->getOrderEmail();

$ordered = $order->getOrderItemsFull($order_id);

$site_url =  esc_url( home_url( '/' ) );

$message = '';

$message .='<p>'.__('Hi','wp-shop').','.$client_name.'!</p>';

$message .='<p>'.__('Order #','wp-shop').$order_id.__('has paid','wp-shop').'</p>';

if ($ordered) {
  $message .='<p>'.__('You ordered','wp-shop').':</p>';
  $message .='<table style="width:100%">';
  $message .='<tr style="background-color:#CCCCCC">
	<th>'.__('Name', 'wp-shop').'</th>
	<th>'.__('Qty', 'wp-shop').'</th>
  <th>'.__('Price', 'wp-shop').'</th>
	</tr>';
  foreach($ordered as $item) {
    
    $url = Wpshop_Digital::getDigitalLink($item['post_id']);
    if ($url&&$url!=''){
      $ext = Wpshop_Digital::checkExternalLink($item['post_id']);
      if ($ext){
        $digital_link = $url;
      }else {
        $digital_link = get_option('home') . "?wpdownload=" . $item['post_id'] . "&order_id={$order_id}";
      }		
    }
    $message .='<tr><td>';
    
    $message .= $item['name'];
    if ($url&&$url!=''){
      $message .= '<br><a href="'.$digital_link.'">'.__('Download','wp-shop').'</a>';
    }
    $message .='</td>';
    
    $message .= '<td>'.$item['count'].'</td>';
    
    $message .= '<td>'.$item['cost'].'</td>';
    
    $message .='</tr>';
  }
  $message .='</table>';
}

echo $message;