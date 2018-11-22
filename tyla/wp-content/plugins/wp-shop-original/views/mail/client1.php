<?php 

$message .="
<table style='width:100%'>
<tr style='background-color:#CCCCCC'>
<th>". __('Name', 'wp-shop')."</th><th>&nbsp;</th><th>" . __('Price', 'wp-shop') . "</th><th>" . __('Qty', 'wp-shop')."</th><th>" . __('Sum', 'wp-shop') . "</th></tr>";?>
<?php
$key = 0;
$price = 0;
foreach($this->order['offers'] as $offer)
{
	$price = round($offer['partnumber'] * $offer['price'],2);
	$itogo += $price;
	if ($key++ % 2) $color = "white";
	else $color = "#DDDDDD";
	$message .= "<tr style='background-color:{$color};'>
		<td><a href='".get_permalink($offer['post_id'])."'>{$offer['name']}</a></td>
		<td>{$offer['key']}</td>
		<td style='text-align:center'>{$offer['price']}</td>
		<td style='text-align:center'>{$offer['partnumber']}</td>
		<td style='text-align:center'>{$price}</td>
	</tr>";
}
?>
<?php $message .="<tr><td colspan='3'>" . __('Total:', 'wp-shop') . "</td><td>{$itogo}</td></tr>";?>
<?php 

if ($this->order['info']['discount'])
{
	$itogo = round($itogo - $itogo / 100 * $this->order['info']['discount'],2);
	$message .= "<tr><td colspan='3'>".__('Price with discount', 'wp-shop') . " ({$this->order['info']['discount']}%)</td><td>{$itogo}</td></tr>";
}

if ($this->order['info']['promo'])
{
	$message .= "<tr><td colspan='3'>".__('Promocode: ', 'wp-shop').$this->order['info']['promo']."</td></tr>";
}

$custom_del_cost = (float) $this->order['info']['custom_delivery_cost'];
$del_name = $this->order['info']['delivery'];
$custom_del_adress = $this->order['info']['custom_delivery_adress'];
$delivery = Wpshop_Delivery::getInstance()->getDelivery($del_name);

if (isset($custom_del_cost)&&$custom_del_cost > 0){
	if ($delivery) {
		if ($itogo >= $delivery->free_delivery&&$delivery->free_delivery > 0){
      $message .= "<tr><td colspan='3'>" . __("Delivery", "wp-shop") . " ({$del_name})</td><td>".__('Free', 'wp-shop')."</td></tr>";
		}else {
			$itogo += $custom_del_cost;
      $message .= "<tr><td colspan='3'>" . __("Delivery", "wp-shop") . " ({$del_name})</td><td>{$custom_del_cost}</td></tr>";
		}
	}
}else{
	if ($delivery) {
		if ($itogo >= $delivery->free_delivery&&$delivery->free_delivery > 0){
      $message .= "<tr><td colspan='3'>" . __("Delivery", "wp-shop") . " ({$delivery->name})</td><td>".__('Free', 'wp-shop')."</td></tr>";
		}else {
			$itogo += $delivery->cost;
      $message .= "<tr><td colspan='3'>" . __("Delivery", "wp-shop") . " ({$delivery->name})</td><td>{$delivery->cost}</td></tr>";
		}
	}
}

if (isset($custom_del_adress)&&$custom_del_adress !='') {
  $message .= "<tr><td colspan='4'>" . __("Delivery address: ", "wp-shop") . " {$custom_del_adress}</td></tr>";
}

?>
<?php $message .= "<tr><td colspan='3'>" . __('In all', 'wp-shop') . ":</td><td>{$itogo}</td></tr></table>";?>


<?php wp_reset_query();?>
<?php
		wp_reset_postdata();
    if ($this->order['info']['payment']){
		$wp_query_mail = new WP_Query(
		array(
				'post_type' => 'wpshop_client_mail',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'mail_type',
						'field' => 'slug',
						'terms' => $this->order['info']['payment']
					)
				),
				'caller_get_posts'=> 1
				
			) 
		);	
    }else {
      $wp_query_mail = new WP_Query(
      array(
          'post_type' => 'wpshop_client_mail',
          'post_status' => 'publish',
          'posts_per_page' => 1,
          'tax_query' => array(
            array(
              'taxonomy' => 'mail_type',
              'field' => 'slug',
              'terms' => 'without_payment'
            )
          ),
          'caller_get_posts'=> 1
          
        ) 
      );	
    }
		while ($wp_query_mail->have_posts()) : $wp_query_mail->the_post(); 

			$content = get_the_content();
      $content = str_replace('[wpshop_order_comment]', $this->order['info']['comment'] , $content );
      $content = str_replace('[wpshop_order_date]', date('d.m.Y') , $content );
			$content = str_replace( '[wpshop_email]', $this->order['info']['email'] , $content );
			$content = str_replace( '[wpshop_username]', $this->order['info']['username'] , $content );
			$content = str_replace( '[wpshop_order_id]', $this->id , $content );
			$content = str_replace( '[wpshop_orders]', $message , $content );
      $content = str_replace( '[wpshop_total]', $itogo, $content );
			$content = str_replace( '[wpshop_delivery_adress]', $this->order['info']['custom_delivery_adress'] , $content );
			echo $content;
		endwhile;  
		wp_reset_postdata();

?>		
