<div class="wrap">
<h2><?php  echo _e("Delivery", 'wp-shop'); /*Доставка*/?></h2>
	<form action="<?php  echo $_SERVER['REQUEST_URI'];?>" method="post">
	<input type="hidden" name="update" value="1"/>
	<div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('Country mail', 'wp-shop'); /*Почта по стране*/; ?></h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'><?php  _e('Price', 'wp-shop'); /*Стоимость*/; ?></td>
					<td><input type="text" name="wpshop_delivery[postByCountry][cost]" value="<?php if(isset($this->delivery['postByCountry']['cost'])){ echo $this->delivery['postByCountry']['cost'];}?>"/></td>
				</tr>
				<tr>
					<td style='width:400px;'><?php  _e('Free shipping for orders of', 'wp-shop'); /*Стоимость*/; ?></td>
					<td><input type="text" name="wpshop_delivery[postByCountry][free_delivery]" value="<?php if(isset($this->delivery['postByCountry']['free_delivery'])){ echo $this->delivery['postByCountry']['free_delivery'];} ?>"/></td>
				</tr>
			</table>
			</div>
		</div>
	</div>

	<div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('International mail', 'wp-shop'); /*Международная почта*/; ?></h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'><?php  _e('Price', 'wp-shop'); /*Стоимость*/; ?></td>
					<td><input type="text" name="wpshop_delivery[postByWorld][cost]" value="<?php if(isset($this->delivery['postByWorld']['cost'])){ echo $this->delivery['postByWorld']['cost'];}?>"/></td>
				</tr>
				<tr>
					<td style='width:400px;'><?php  _e('Free shipping for orders of', 'wp-shop'); /*Стоимость*/; ?></td>
					<td><input type="text" name="wpshop_delivery[postByWorld][free_delivery]" value="<?php if(isset($this->delivery['postByWorld']['free_delivery'])){ echo $this->delivery['postByWorld']['free_delivery'];} ?>"/></td>
				</tr>
			</table>
			</div>
		</div>
	</div>

	<div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('Express delivery', 'wp-shop'); /*Курьерская доставка*/; ?></h3>
			<div class="wpshop_wpshop_inside_block_block">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'><?php  _e('Price', 'wp-shop'); /*Стоимость*/; ?></td>
					<td><input type="text" name="wpshop_delivery[courier][cost]" value="<?php if(isset($this->delivery['courier']['cost'])){echo $this->delivery['courier']['cost'];} ?>"/></td>
				</tr>
				<tr>
					<td style='width:400px;'><?php  _e('Free shipping for orders of', 'wp-shop'); /*Стоимость*/; ?></td>
					<td><input type="text" name="wpshop_delivery[courier][free_delivery]" value="<?php if(isset($this->delivery['courier']['free_delivery'])){ echo $this->delivery['courier']['free_delivery'];} ?>"/></td>
				</tr>
			</table>
			</div>
		</div>

		<div class="postbox">
			<h3><?php  _e('A visit to the office', 'wp-shop'); /*Визит в офис*/; ?></h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'><?php  _e('Price', 'wp-shop'); /*Стоимость*/; ?></td>
					<td><input type="text" name="wpshop_delivery[vizit][cost]" value="<?php if(isset($this->delivery['vizit']['cost'])){ echo $this->delivery['vizit']['cost'];} ?>"/></td>
				</tr>
				<tr>
					<td style='width:400px;'><?php  _e('Free shipping for orders of', 'wp-shop'); /*Стоимость*/; ?></td>
					<td><input type="text" name="wpshop_delivery[vizit][free_delivery]" value="<?php if(isset($this->delivery['vizit']['free_delivery'])){ echo $this->delivery['vizit']['free_delivery'];} ?>"/></td>
				</tr>
			</table>
			</div>
		</div>
				
	</div>
  
  <div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('Yandex_delivery', 'wp-shop'); /*Yandex доставка*/; ?></h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" width="100%">
				<tr>
					<td style='width:400px;padding-bottom:20px;'><?php  _e('Enable support for Yandex delivery', 'wp-shop'); /*Включить поддержку Yandex доставки*/ ?></td>
					<?php if(isset($this->yandex_delivery['activate'])&&$this->yandex_delivery['activate']){
						$ya_del_activate =" checked";
					}else {
						$ya_del_activate ="";
					} ?>
					<td style='padding-bottom:20px;'><input type="checkbox" name="wpshop_yandex_delivery[activate]"<?php  echo $ya_del_activate;?>/></td>
				</tr>
				<tr>
					<td style='width:400px;padding-bottom:10px;'><?php  _e('Yandex cart widget code', 'wp-shop'); /*Код скрипта Yandex доставки*/ ?></td>
					<td style='padding-bottom:10px;'><textarea style='width:100%;height:100px;' name="wpshop_yandex_delivery[cart_code]"><?php if(isset($this->yandex_delivery['cart_code'])){echo $this->yandex_delivery['cart_code'];}?></textarea></td>
				</tr>
				<tr>
					<td style='width:400px;padding-bottom:10px;'><?php  _e('Base lenght', 'wp-shop'); /*Базовая длина*/; ?></td>
					<td style='padding-bottom:10px;'><input type="text" style='width:100%;' name="wpshop_yandex_delivery[base_lenght]" value="<?php if(isset($this->yandex_delivery['base_lenght'])){echo $this->yandex_delivery['base_lenght'];} ?>"/><br><?php  _e('specify in centimeters, use `.` as decimal separator', 'wp-shop'); /*указывать в сантиметрах*/; ?></td>
				</tr>
				<tr>
					<td style='width:400px;padding-bottom:10px;'><?php  _e('Base width', 'wp-shop'); /*Базовая ширина*/; ?></td>
					<td style='padding-bottom:10px;'><input style='width:100%;' type="text" name="wpshop_yandex_delivery[base_width]" value="<?php if(isset($this->yandex_delivery['base_width'])){echo $this->yandex_delivery['base_width'];} ?>"/><br><?php  _e('specify in centimeters, use `.` as decimal separator', 'wp-shop'); /*указывать в сантиметрах*/; ?></td>
				</tr>
				<tr>
					<td style='width:400px;padding-bottom:10px;'><?php  _e('Base height', 'wp-shop'); /*Базовая высота*/; ?></td>
					<td style='padding-bottom:10px;'><input style='width:100%;' type="text" name="wpshop_yandex_delivery[base_height]" value="<?php if(isset($this->yandex_delivery['base_height'])){echo $this->yandex_delivery['base_height'];} ?>"/><br><?php  _e('specify in centimeters, use `.` as decimal separator', 'wp-shop'); /*указывать в сантиметрах*/; ?></td>
				</tr>
				<tr>
					<td style='width:400px;padding-bottom:10px;'><?php  _e('Base weight', 'wp-shop'); /*Базовый вес*/; ?></td>
					<td style='padding-bottom:10px;'><input style='width:100%;' type="text" name="wpshop_yandex_delivery[base_weight]" value="<?php if(isset($this->yandex_delivery['base_weight'])){echo $this->yandex_delivery['base_weight'];} ?>"/><br><?php  _e('specify in kilograms, use `.` as decimal separator', 'wp-shop'); /*указывать в сантиметрах*/; ?></td>
				</tr>
			</table>
			</div>
		</div>
	</div>
  
  <?php do_action('wpshop_custom_delivery_settings_view');?>
  
	<input type="submit" value="<?php  _e('Save', 'wp-shop'); /*Сохранить*/; ?>" class="button">
	</form>
</div>