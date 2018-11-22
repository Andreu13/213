<?php 
$order_id = $this->order_id;
$order = new Wpshop_Order($order_id);

$client_name = $order->getOrderClientName();
$client_email = $order->getOrderEmail();

$total = $order->getTotalSum();

$ordered = $order->getOrderItemsFull($order_id);

$site_url =  esc_url( home_url( '/' ) );

$message = '';

if ($ordered) {
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

wp_reset_query();
wp_reset_postdata();
  $wp_query_mail = new WP_Query(
		array(
      'post_type' => 'wpshop_client_mail',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'tax_query' => array(
				array(
					'taxonomy' => 'mail_type',
					'field' => 'slug',
					'terms' => 'confirm'
				)
			),
			'caller_get_posts'=> 1
		) 
	);	
while ($wp_query_mail->have_posts()) : $wp_query_mail->the_post(); 
	$content = get_the_content();
  $content = str_replace('[wpshop_order_date_confirm]', date('d.m.Y') , $content );
	$content = str_replace( '[wpshop_email]', $client_email , $content );
	$content = str_replace( '[wpshop_username]', $client_name , $content );
	$content = str_replace( '[wpshop_order_id]', $order_id , $content );
  $content = str_replace( '[wpshop_orders]', $message , $content );
  $content = str_replace( '[wpshop_total]', $total, $content );
	echo $content;
endwhile;  
wp_reset_postdata();