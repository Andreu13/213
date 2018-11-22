<table>
<!--thead>
<tr style="background-color:#CCCCCC">
	<th>Поле</th>
	<th>Значение</th>
</tr>
</thead-->
<?php 
foreach($this->order['cforms'] as $value)
{
	echo "<tr><td>{$value['name']}</td><td>{$value['value']}</td></tr>";
}
?>
</table>

<br/><br/>
<table style="width:100%">
<tr style="background-color:#CCCCCC">
	<th><?php  _e('Name', 'wp-shop'); /*Наименование*/; ?></th>
	<th>&nbsp;</th>
	<th><?php  _e('Price', 'wp-shop'); /*Цена*/; ?></th>
	<th><?php  _e('Qty', 'wp-shop'); /*Кол-во*/; ?></th>
	<th><?php  _e('Sum', 'wp-shop'); /*Сумма*/; ?></th>
</tr>
<?php 
$key = 0;
$price = 0;
foreach($this->order['offers'] as $offer)
{

$price = round($offer['partnumber'] * $offer['price'],2);
	$itogo += $price;
	if ($key++ % 2) $color = "white";
	else $color = "#DDDDDD";
	echo "<tr style='background-color:{$color};'>
		<td><a href='".get_permalink($offer['post_id'])."'>{$offer['name']}</a></td>
		<td style='text-align:center'>{$offer['key']}</td>
		<td style='text-align:center'>{$offer['price']}</td>
		<td style='text-align:center'>{$offer['partnumber']}</td>
		<td style='text-align:center'>{$price}</td>
	</tr>";
}
?>
<tr><td colspan='3'><?php  _e('Total:', 'wp-shop'); /*Итого:*/; ?></td><td><?php  echo $itogo;?></td></tr>
<?php 

if ($this->order['info']['discount'])
{
	$itogo = round($itogo - $itogo / 100 * $this->order['info']['discount'],2);
	echo "<tr><td colspan='3'>".__('Discount price', 'wp-shop') . " ({$this->order['info']['discount']}%)</td><td>{$itogo}</td></tr>";
}

if ($this->order['info']['promo'])
{
	echo "<tr><td colspan='3'>".__('Promocode: ', 'wp-shop').$this->order['info']['promo']."</td></tr>";
}

$custom_del_cost = (float) $this->order['info']['custom_delivery_cost'];
$del_name = $this->order['info']['delivery'];
$custom_del_adress = $this->order['info']['custom_delivery_adress'];
$delivery = Wpshop_Delivery::getInstance()->getDelivery($del_name);

if (isset($custom_del_cost)&&$custom_del_cost > 0){
	if ($delivery) {
		if ($itogo >= $delivery->free_delivery&&$delivery->free_delivery > 0){
      echo "<tr><td colspan='3'>" . __("Delivery", "wp-shop") . " ({$del_name})</td><td>".__('Free', 'wp-shop')."</td></tr>";
		}else {
			$itogo += $custom_del_cost;
      echo "<tr><td colspan='3'>" . __("Delivery", "wp-shop") . " ({$del_name})</td><td>{$custom_del_cost}</td></tr>";
		}
	}
}else{
	if ($delivery) {
		if ($itogo >= $delivery->free_delivery&&$delivery->free_delivery > 0){
      echo "<tr><td colspan='3'>" . __("Delivery", "wp-shop") . " ({$delivery->name})</td><td>".__('Free', 'wp-shop')."</td></tr>";
		}else {
			$itogo += $delivery->cost;
      echo "<tr><td colspan='3'>" . __("Delivery", "wp-shop") . " ({$delivery->name})</td><td>{$delivery->cost}</td></tr>";
		}
	}
}

if (isset($custom_del_adress)&&$custom_del_adress !='') {
  echo "<tr><td colspan='4'>" . __("Delivery address: ", "wp-shop") . " {$custom_del_adress}</td></tr>";
}

?>
<tr>
	<td colspan='3'><?php  _e('In all', 'wp-shop'); /*Всего*/; ?></td>
	<td><?php  echo $itogo;?></td>
</tr>
</table>
