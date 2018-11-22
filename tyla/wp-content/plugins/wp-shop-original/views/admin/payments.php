<style type='text/css'>
.postbox h3
{
	padding: 0 12px;
	cursor: none;
}

#wpshop_tabs_metabox {
	margin-bottom: 20px;
}

#wpshop_tabs_metabox .cripto_button {
	color: #fff !important;
    background-color: #136ebd;
    display: inline-block;
    padding: 15px 40px;
    border-radius: 21px;
    text-transform: uppercase;
    text-decoration: none;
    font-size: 15px;
    font-weight: bold;
}

#wpshop_tabs_metabox .cripto_button_text {
	padding-top: 20px;
	margin-bottom: 30px;
	font-size: 15px;
	color: #000;
}
</style>
<script>
	jQuery( document ).ready(function( $ ) {
		var tabs = $( "#wpshop_tabs_metabox" ).tabs();
		var active_cripto = <?php echo get_option("wpshop_cripto_active"); ?>;
		
		if(typeof active_cripto === 'undefined'||active_cripto!=1) {
			$("input.cripto_m").attr('disabled','disabled');
			$("input.cripto_s").attr('disabled','disabled');
		}
		
		$(".cripto_button").on('click',function() {
			$("input.cripto_m").removeAttr('disabled');
			$("input.cripto_s").removeAttr('disabled');
			<?php update_option("wpshop_cripto_active",1); ?>
		});

	});
</script>	
<div class="wrap">
<h2><?php  _e('Payment methods', 'wp-shop'); /*Способы оплаты*/ ?></h2>
<form method="POST" class="payments">
<input type="hidden" name="update_payments" value="1"/>
<div id="wpshop_tabs_metabox">
<ul>
        <li class="wpshop_tab-1"><a href="#wpshop_tabs_metabox-1"><?php  echo __('Offline payments', 'wp-shop');?></a></li>
        <li class="wpshop_tab-2"><a href="#wpshop_tabs_metabox-2"><?php  echo __('Russian payment systems', 'wp-shop');?></a></li>	
		<li class="wpshop_tab-3"><a href="#wpshop_tabs_metabox-3"><?php  echo __('International payment systems', 'wp-shop');?></a></li>	
		<li class="wpshop_tab-4"><a href="#wpshop_tabs_metabox-4"><?php  echo __('Crypto currency', 'wp-shop');?></a></li>
</ul>
<div id="wpshop_tabs_metabox-1">
		<div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('Self-delivery', 'wp-shop'); /*Самовывоз*/ ?></h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td><?php  _e('Enable support for self-delivery from a store / office.', 'wp-shop'); /*Включить поддержку самовывоза из магазина/офиса.*/ ?></td>
          <?php if(isset($this->vizit['activate'])&&$this->vizit['activate']){
            $vizit_activate =" checked";
          }else {
            $vizit_activate ="";
          } ?>
					<td><input type="checkbox" name="wpshop_payments_vizit[activate]"<?php  echo $vizit_activate;?>/></td>
				</tr>

				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->vizit['delivery'])&&$this->vizit['delivery']){
                if (in_array($delivery->ID,$this->vizit['delivery']))
                {
                  $checked = " checked";
                }
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.vizit",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_vizit[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
			</table>
			<div style="width: 50%;float: left;text-align: right;min-width: 500px;">
				<ins data-revive-zoneid="8" data-revive-id="03af71d0efe35b0d7d888949e681431d"></ins><script async src="https://wp-shop.ru/adv/www/delivery/asyncjs.php"></script>
			</div>
			</div>
		</div>
	</div>

	<div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('Cash to courier', 'wp-shop'); /*Включить поддержку оплаты курьеру*/ ?></h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td><?php  _e('Enable support for payment to courier', 'wp-shop'); /*Включить поддержку оплаты курьеру*/ ?></td>
					<?php if(isset($this->cash['activate'])&&$this->cash['activate']){
            $cash_activate =" checked";
          }else {
            $cash_activate ="";
          } ?>
					<td><input type="checkbox" name="wpshop_payments_cash[activate]"<?php  echo $cash_activate;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->cash['delivery'])&&$this->cash['delivery']){
							if (in_array($delivery->ID,$this->cash['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.cash",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_cash[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
			</table>
			<div style="width: 50%;float: left;text-align: right;min-width: 500px;">
				<ins data-revive-zoneid="9" data-revive-id="03af71d0efe35b0d7d888949e681431d"></ins><script async src="https://wp-shop.ru/adv/www/delivery/asyncjs.php"></script>
			</div>
			</div>
		</div>
	</div>
	<div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('Cash on delivery (COD)', 'wp-shop'); /*Наложенный платеж*/ ?></h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td><?php  _e('Enable support for COD', 'wp-shop'); /*Включить поддержку наложного платежа*/ ?></td>
				  <?php if(isset($this->post['activate'])&&$this->post['activate']){
            $post_activate =" checked";
          }else {
            $post_activate ="";
          } ?>
					<td><input type="checkbox" name="wpshop_payments_post[activate]"<?php  echo $post_activate;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->post['delivery'])&&$this->post['delivery']){
							if (in_array($delivery->ID,$this->post['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.post",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_post[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
			</table>
			<div style="width: 50%;float: left;text-align: right;min-width: 500px;">
				<ins data-revive-zoneid="10" data-revive-id="03af71d0efe35b0d7d888949e681431d"></ins><script async src="https://wp-shop.ru/adv/www/delivery/asyncjs.php"></script>
			</div>
			</div>
		</div>
	</div>
  
	<div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('Your bank account details', 'wp-shop'); /*Ваши банковские реквизиты*/ ?></h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td ><?php  _e('Enable support of a payment through the bank', 'wp-shop'); /*Включить поддержку оплаты через банк*/ ?></td>
          <?php if(isset($this->bank['activate'])&&$this->bank['activate']){
            $bank_activate =" checked";
          }else {
            $bank_activate ="";
          } ?>
					<td><input type="checkbox" name="wpshop_payments_bank[activate]"<?php  echo $bank_activate;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->bank['delivery'])&&$this->bank['delivery']){
							if (is_array($this->bank['delivery']) && in_array($delivery->ID,$this->bank['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.bank",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_bank[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
			</table>
			<div style="width: 50%;float: left;text-align: right;min-width: 500px;">
				<ins data-revive-zoneid="11" data-revive-id="03af71d0efe35b0d7d888949e681431d"></ins><script async src="https://wp-shop.ru/adv/www/delivery/asyncjs.php"></script>
			</div>
			</div>
		</div>
	</div>
</div>
<div id="wpshop_tabs_metabox-2">
	<div id="poststuff">
        <div class="postbox">
            <h3><?php _e('Tinkoff Bank', 'wp-shop'); ?></h3>
            <div class="wpshop_inside_block">
                <table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
                    <tr>
                        <td><?php _e('Enable Tinkoff Bank', 'wp-shop'); ?></td>
                            <?php if (isset($this->tinkoff['activate']) && $this->tinkoff['activate']) {
                                $tinkoff_activate = " checked";
                            } else {
                                $tinkoff_activate = "";
                            } ?>
                        <td><input type="checkbox" name="wpshop_payments_tinkoff[activate]"<?php echo $tinkoff_activate; ?>/></td>
                    </tr>

                    <tr>
                        <td><?php _e('Delivery', 'wp-shop'); ?></td>
                        <td>
                            <?php
                            $i = 0;
                            foreach ($this->deliveries as $delivery) {
                                $checked = "";
                                if (isset($this->tinkoff['delivery']) && $this->tinkoff['delivery']) {
                                    if (in_array($delivery->ID, $this->tinkoff['delivery'])) {
                                        $checked = " checked";
                                    }
                                } elseif ($i == 3) {
                                    $checked = " checked";
                                    update_option("wpshop.payments.tinkoff", array('delivery' => array(2 => 'vizit')));
                                }
                                echo "<input type='checkbox' name='wpshop_payments_tinkoff[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
                                if (++$i == 5) break;
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Gateway', 'wp-shop'); ?></td>
                        <td style="min-width:300px;"><input style="width:100%;" type="text"
                            name="wpshop_payments_tinkoff[gateway]"
                            value="<?php if (isset($this->tinkoff['gateway'])) { echo $this->tinkoff['gateway'];} ?>"/>
						</td>
                    </tr>
                    <tr>
                        <td><?php _e('Terminal', 'wp-shop'); ?></td>
                        <td style="min-width:300px;"><input style="width:100%;" type="text"
                            name="wpshop_payments_tinkoff[terminal]"
                            value="<?php if (isset($this->tinkoff['terminal'])) {echo $this->tinkoff['terminal'];} ?>"/>
						</td>
                    </tr>
                    <tr>
                        <td><?php _e('Secret key', 'wp-shop'); ?></td>
                        <td style="min-width:300px;"><input style="width:100%;" type="text"
                            name="wpshop_payments_tinkoff[secret_key]"
                            value="<?php if (isset($this->tinkoff['secret_key'])) {echo $this->tinkoff['secret_key'];} ?>"/>
						</td>
                    </tr>

                    <tr>
                      <td><?php  _e('Success URL', 'wp-shop'); ?></td>
                      <td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_tinkoff[successUrl]" value="<?php  if(isset($this->tinkoff['successUrl'])){echo $this->tinkoff['successUrl'];}?>"/></td>
                    </tr>
                    <tr>
                      <td><?php  _e('Failed URL', 'wp-shop'); ?></td>
                      <td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_tinkoff[failedUrl]" value="<?php if(isset($this->tinkoff['failedUrl'])){echo $this->tinkoff['failedUrl'];}?>"/></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
	
	<div id="poststuff">
		<div class="postbox">
			<h3>Web-money</h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td><?php  _e('Enable support of a payment using Web-Money', 'wp-shop'); /*Включить поддержку оплаты по Web-Money*/ ?></td>
					  <?php if(isset($this->wm['activate'])&&$this->wm['activate']){
						$wm_activate =" checked";
					  }else {
						$wm_activate ="";
					  } ?>
					<td><input type="checkbox" name="wpshop_payments_wm[activate]"<?php  echo $wm_activate;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->wm['delivery'])&&$this->wm['delivery']){
							if (in_array($delivery->ID,$this->wm['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.wm",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_wm[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>

				<tr>
					<td><?php  _e('Your WM-purse', 'wp-shop'); /*Ваш WM-Кошелек*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_wm[wmCheck]" value="<?php if(isset($this->wm['wmCheck'])){echo $this->wm['wmCheck'];}?>"/></td>
				</tr>
				<tr>
					<td><?php  _e('Success URL', 'wp-shop'); ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_wm[successUrl]" value="<?php  if(isset($this->wm['successUrl'])){echo $this->wm['successUrl'];}?>"/></td>
				</tr>
				<tr>
					<td><?php  _e('Failed URL', 'wp-shop'); ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_wm[failedUrl]" value="<?php if(isset($this->wm['failedUrl'])){echo $this->wm['failedUrl'];}?>"/></td>
				</tr>
			</table>
			<div style="width: 50%;float: left;text-align: right;min-width: 500px;">
				<ins data-revive-zoneid="12" data-revive-id="03af71d0efe35b0d7d888949e681431d"></ins><script async src="https://wp-shop.ru/adv/www/delivery/asyncjs.php"></script>
			</div>
			</div>
		</div>
	</div>
  
  <div id="poststuff">
		<div class="postbox">
			<h3>Yandex money</h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td><?php  _e('Enable support of a payment using Yandex Money', 'wp-shop'); /*Включить поддержку оплаты по Yandex Money*/ ?></td>
					  <?php if(isset($this->ym['activate'])&&$this->ym['activate']){
						$ym_activate =" checked";
					  }else {
						$ym_activate ="";
					  } ?>
					<td><input type="checkbox" name="wpshop_payments_ym[activate]"<?php  echo $ym_activate;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->ym['delivery'])&&$this->ym['delivery']){
							if (in_array($delivery->ID,$this->ym['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.ym",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_ym[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>

				<tr>
					<td><?php  _e('Your Yandex money account ', 'wp-shop'); /*Ваш Yandex Money кошелек*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_ym[ymAccount]" value="<?php if(isset($this->ym['ymAccount'])){echo $this->ym['ymAccount'];}?>"/></td>
				</tr>
				<tr>
					<td><?php  _e('Success URL', 'wp-shop'); ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_ym[successUrl]" value="<?php  if(isset($this->ym['successUrl'])){echo $this->ym['successUrl'];}?>"/></td>
				</tr>
				<tr>
					<td><?php  _e('Add payments by Visa and MasterCard', 'wp-shop'); ?></td>
						<?php if(isset($this->ym['p_visa'])&&$this->ym['p_visa']){
							$ym_p_visa =" checked";
						}else {
							$ym_p_visa ="";
						} ?>
					<td><input type="checkbox" name="wpshop_payments_ym[p_visa]"<?php  echo $ym_p_visa;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Add payments from cellphones', 'wp-shop'); ?></td>
						<?php if(isset($this->ym['p_mobile'])&&$this->ym['p_mobile']){
							$ym_p_mobile =" checked";
						}else {
							$ym_p_mobile ="";
						} ?>
					<td><input type="checkbox" name="wpshop_payments_ym[p_mobile]"<?php  echo $ym_p_mobile;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Require Payer Name', 'wp-shop'); ?></td>
						<?php if(isset($this->ym['p_name'])&&$this->ym['p_name']){
							$ym_p_name =" checked";
						}else {
							$ym_p_name ="";
						} ?>
					<td><input type="checkbox" name="wpshop_payments_ym[p_name]"<?php  echo $ym_p_name;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Require Payer Email', 'wp-shop'); ?></td>
						<?php if(isset($this->ym['p_email'])&&$this->ym['p_email']){
							$ym_p_email =" checked";
						}else {
							$ym_p_email ="";
						} ?>
					<td><input type="checkbox" name="wpshop_payments_ym[p_email]"<?php  echo $ym_p_email;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Require Payer Cell', 'wp-shop'); ?></td>
						<?php if(isset($this->ym['p_cell'])&&$this->ym['p_cell']){
							$ym_p_cell =" checked";
						}else {
							$ym_p_cell ="";
						} ?>
					<td><input type="checkbox" name="wpshop_payments_ym[p_cell]"<?php  echo $ym_p_cell;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Require Payer Adress', 'wp-shop'); ?></td>
						<?php if(isset($this->ym['p_adr'])&&$this->ym['p_adr']){
							$ym_p_adr =" checked";
						}else {
							$ym_p_adr ="";
						} ?>
					<td><input type="checkbox" name="wpshop_payments_ym[p_adr]"<?php  echo $ym_p_adr;?>/></td>
				</tr>
			</table>
			
			</div>
		</div>
	</div>
	
	<div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('Payment through the merchant', 'wp-shop');/* Оплата через шлюз */?></h3>
			<div class="wpshop_inside_block">
			
			<table cellpadding="2" cellspacing="2" >
				<tr>
					<td><?php  _e('Enable support of Merchant', 'wp-shop'); /*Включить поддержку merchants*/ ?></td>
					<?php if(isset($this->merchant)&&$this->merchant){
						$merchant_activate =" checked";
					  }else {
						$merchant_activate ="";
					} ?>
					<td><input type="checkbox" name="wpshop_merchant"<?php  echo $merchant_activate;?>/></td>
				</tr>
				
				<script>
					jQuery(function(){
						if (jQuery('.merchant_system').val() == 'ek') { 
							jQuery('.robokassa_n').hide();
							jQuery('.yandex_kassa_n').hide();
							jQuery('.ek_n').show();
						}
						
						if (jQuery('.merchant_system').val() == 'robokassa') { 
							jQuery('.ek_n').hide();
							jQuery('.yandex_kassa_n').hide();
							jQuery('.robokassa_n').show();
						}
						
						if (jQuery('.merchant_system').val() == 'yandex_kassa') { 
							jQuery('.ek_n').hide();
							jQuery('.robokassa_n').hide();
							jQuery('.yandex_kassa_n').show();
						}
						
						jQuery('.merchant_system').change(function(){
							if (jQuery(this).val() == 'ek') { 
								jQuery('.robokassa_n').hide();
								jQuery('.yandex_kassa_n').hide();
								jQuery('.ek_n').show();
							}
							
							if (jQuery(this).val() == 'robokassa') { 
								jQuery('.ek_n').hide();
								jQuery('.yandex_kassa_n').hide();
								jQuery('.robokassa_n').show();
							}
							
							if (jQuery(this).val() == 'yandex_kassa') { 
								jQuery('.ek_n').hide();
								jQuery('.robokassa_n').hide();
								jQuery('.yandex_kassa_n').show();
							}
						});
					});
				</script>
				
				<tr>
					<td><?php  _e('Select Merchant System', 'wp-shop'); /*Выбрать merchant system*/ ?></td>
					<td>
						<select name="wpshop_merchant_system" class="merchant_system">
							<option value='ek' <?php  if(isset($this->merchant_system)&&$this->merchant_system == 'ek'){ echo' selected="selected"';}?>><?php  _e('WalletOne', 'wp-shop');/* Единая касса */?></option>
							<option value='robokassa' <?php if(isset($this->merchant_system)&&$this->merchant_system == 'robokassa'){ echo' selected="selected"';}?>><?php  _e('Robokassa', 'wp-shop');/* Робокасса */?></option>
							<option value='yandex_kassa' <?php if(isset($this->merchant_system)&&$this->merchant_system == 'yandex_kassa'){ echo' selected="selected"';}?>><?php  _e('Yandex kassa', 'wp-shop');/* Робокасса */?></option>
						</select>
					</td>
				</tr>
				
				<!-- Настройки robokassa-->
				<table class="robokassa_n" style="width: 50%;float: left;min-width: 650px; display:none">
					<tr>
						<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
						<td>
						<?php 
							$i = 0;
							foreach($this->deliveries as $delivery)
							{
								$checked = "";
								if(isset($this->robokassa['delivery'])&&$this->robokassa['delivery']){
								if (in_array($delivery->ID,$this->robokassa['delivery']))
								{
									$checked = " checked";
								}
								}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.robokassa",array('delivery' => array(2=>'vizit')));}
								echo "<input type='checkbox' name='wpshop_payments_robokassa[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
								if(++$i == 5) break;
							}
						?>
						</td>
					</tr>
					<tr>
						<td><?php  _e('Robokassa Login', 'wp-shop'); ?></td>
						<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_robokassa[login]" value="<?php if(isset($this->robokassa['login'])){echo $this->robokassa['login'];}?>"/></td>
					</tr>
					<tr>
						<td><?php  _e('Robokassa pass 1', 'wp-shop'); /*Robokassa пароль 1*/ ?></td>
						<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_robokassa[pass1]" value="<?php if(isset($this->robokassa['pass1'])){echo $this->robokassa['pass1'];}?>"/></td>
					</tr>
					<tr>
						<td><?php  _e('Robokassa pass 2', 'wp-shop'); /*Robokassa пароль 2*/ ?></td>
						<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_robokassa[pass2]" value="<?php if(isset($this->robokassa['pass2'])){echo $this->robokassa['pass2'];}?>"/></td>
					</tr>
				</table>
				
				<!-- Настройки EK-->
				<table class="ek_n" style="width: 50%;float: left;min-width: 650px; display:none">
					<tr>
						<td>
							<table>
							<tr>
								<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
								<td>
								<?php 
									$i = 0;
									foreach($this->deliveries as $delivery)
									{
										$checked = "";
										if(isset($this->ek['delivery'])&&$this->ek['delivery']){
										if (in_array($delivery->ID,$this->ek['delivery']))
										{
											$checked = " checked";
										}
										}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.ek",array('delivery' => array(2=>'vizit')));}
										echo "<input type='checkbox' name='wpshop_payments_ek[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
										if(++$i == 5) break;
									}

								?>
								</td>
							</tr>
							
							<tr>
								<td><?php  _e('Your WalletOne', 'wp-shop'); /*Ваш WalletOne*/ ?></td>
								<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_ek[wmCheck]" value="<?php if(isset($this->ek['wmCheck'])){ echo $this->ek['wmCheck'];}?>"/></td>
							</tr>
							
							<tr>
								<td><?php  _e('Currency', 'wp-shop'); /*Валюта*/ ?></td>
								<td>
									<?php 
										if(isset($this->ek['currency_ek'])){
										$currency = $this->ek['currency_ek'];
										if ($currency == '643') { $p1 = ' selected="selected"'; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = ''; }
										if ($currency == '710') { $p1 = ''; $p2 = ' selected="selected"'; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = ''; }
										if ($currency == '840') { $p1 = ''; $p2 = ''; $p3 = ' selected="selected"'; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = ''; }
										if ($currency == '978') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ' selected="selected"'; $p5 = ''; $p6 = ''; $p7 = ''; }
										if ($currency == '980') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ' selected="selected"'; $p6 = ''; $p7 = ''; }
										if ($currency == '398') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ' selected="selected"'; $p7 = ''; }
										if ($currency == '974') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = ' selected="selected"'; }
									}
									?>
									<select name="wpshop_payments_ek[currency_ek]">
										<option value='643' <?php echo $p1?>><?php  _e('Russian Ruble', 'wp-shop'); /*Российские рубли*/ ?></option>
										<option value='710' <?php echo $p2?>><?php  _e('South African Rand', 'wp-shop'); /*Южно-Африканские ранды*/ ?></option>
										<option value='840' <?php echo $p3?>><?php  _e('USD', 'wp-shop'); /*Американские доллары*/ ?></option>
										<option value='978' <?php echo $p4?>><?php  _e('euro', 'wp-shop'); /*Евро*/ ?></option>
										<option value='980' <?php echo $p5?>><?php  _e('Ukrainian hryvnia', 'wp-shop'); /*Украинские гривны*/ ?></option>
										<option value='398' <?php echo $p6?>><?php  _e('Kazakhstani tenge', 'wp-shop'); /*Казахстанские тенге*/ ?></option>
										<option value='974' <?php echo $p7?>><?php  _e('Belarusian Ruble', 'wp-shop'); /*Белорусские рубли*/ ?></option>
									</select>
								</td>
							</tr>
              
              <tr>
                <td><?php  _e('Tax system', 'wp-shop'); /*Валюта*/ ?></td>
                <td>
                  <?php 
                    if(isset($this->ek['tax'])){
                    $tax = $this->ek['tax'];
                    if ($tax == 'tax_ru_1') { $tk1 = ' selected="selected"';$tk2 = '';$tk3 = '';$tk4 = '';$tk5 = '';$tk6 = '';}
                    if ($tax== 'tax_ru_2') { $tk1 = '';$tk3 = '';$tk4 = '';$tk5 = '';$tk6 = ''; $tk2 = ' selected="selected"';}
                    if ($tax == 'tax_ru_3') { $tk2 = '';$tk1 = '';$tk4 = '';$tk5 = '';$tk6 = ''; $tk3 = ' selected="selected"';}
                    if ($tax== 'tax_ru_4') { $tk2 = '';$tk3 = '';$tk1 = '';$tk5 = '';$tk6 = ''; $tk4 = ' selected="selected"';}
                    if ($tax== 'tax_ru_5') { $tk2 = '';$tk3 = '';$tk4 = '';$tk1 = '';$tk6 = ''; $tk5 = ' selected="selected"';}
                    if ($tax== 'tax_ru_6') { $tk2 = '';$tk3 = '';$tk4 = '';$tk1 = '';$tk5 = ''; $tk6 = ' selected="selected"';}
                  }
                  ?>
                  <select name="wpshop_payments_ek[tax]">
                    <option value='tax_ru_1' <?php echo $tk1?>><?php  _e('Without VAT', 'wp-shop'); /*без НДС*/ ?></option>
                   <option value='tax_ru_2' <?php echo $tk2?>><?php  _e('VAT at a rate of 0%', 'wp-shop'); /*НДС по ставке 0%*/ ?></option>
                    <option value='tax_ru_3' <?php echo $tk3?>><?php  _e('VAT check at a rate of 10%', 'wp-shop'); /*НДС чека по ставке 10%*/ ?></option>
                   <option value='tax_ru_4' <?php echo $tk4?>><?php  _e('VAT check at a rate of 18%', 'wp-shop'); /*НДС чека по ставке 18%*/ ?></option>
                   <option value='tax_ru_5' <?php echo $tk5?>><?php  _e('VAT check at the estimated rate of 10/110', 'wp-shop'); /*НДС чека по расчетной ставке 10/110*/ ?></option>
                   <option value='tax_ru_6' <?php echo $tk6?>><?php  _e('VAT check at the estimated rate of 18/118', 'wp-shop'); /*НДС чека по расчетной ставке 18/118*/ ?></option>
                  </select>
                </td>
              </tr>
							
							<tr>
								<td><?php  _e('Success URL', 'wp-shop'); ?></td>
								<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_ek[successUrl]" value="<?php if(isset($this->ek['successUrl'])){echo $this->ek['successUrl'];}?>"/></td>
							</tr>
							
							<tr>
								<td><?php  _e('Failed URL', 'wp-shop'); ?></td>
								<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_ek[failedUrl]" value="<?php if(isset($this->ek['failedUrl'])){echo $this->ek['failedUrl'];}?>"/></td>
							</tr>
              
							<tr>
								<td><?php  _e('Result URL', 'wp-shop'); ?></td>
								<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_ek[resultUrl]" value="<?php if(isset($this->ek['resultUrl'])){echo $this->ek['resultUrl'];}?>"  readonly="readonly"/></td>
							</tr>
							<input type="hidden" name="wpshop_payments_ek[passfrase]" value="<?php if(isset($this->ek['passfrase'])){echo $this->ek['passfrase'];}?>"/>
							</table>
						</td>
						<?php add_thickbox(); ?>
						<div id="my-content-id" style="display:none;">
							<img src="<?php echo WPSHOP_URL;?>/images/ek_reg.jpg" width="100%">
						</div>
						<td class="wpshop_information">
							<h3>Внимание, это важно! </h3>
							<p>код подключения к системе <strong>Единая Касса</strong></p>
							<h2>Ra2xrxrxy</h2>
							<p>Для правильной синхронизации данных с системой Единая Касса Вам нужно внести этот код в форму ругистрации аккаунта </p>
							<a href="#TB_inline?width=600&height=550&inlineId=my-content-id" class="thickbox">Подробнее...</a>
						</td>
					</tr>
				</table>
				
				<!-- Настройки yandex_kassa-->
				<table class="yandex_kassa_n" style="width: 50%;float: left;min-width: 650px; display:none">
					
					<tr>
						<td><?php  _e('Test paiments', 'wp-shop'); ?></td>
						<?php if(isset($this->yandex_kassa['test'])&&$this->yandex_kassa['test']){
							$yandex_test =" checked";
						}else {
							$yandex_test ="";
						} ?>						
						<td><input type="checkbox" name="wpshop_payments_yandex_kassa[test]"<?php  echo $yandex_test;?>/></td>
					</tr>
          
					<tr>
						<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
						<td>
						<?php 
							$i = 0;
							foreach($this->deliveries as $delivery)
							{
								$checked = "";
								if(isset($this->yandex_kassa['delivery'])&&$this->yandex_kassa['delivery']){
								if (in_array($delivery->ID,$this->yandex_kassa['delivery']))
								{
									$checked = " checked";
								}
								}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.yandex_kassa",array('delivery' => array(2=>'vizit')));}
								echo "<input type='checkbox' name='wpshop_payments_yandex_kassa[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
								if(++$i == 5) break;
							}

						?>
						</td>
					</tr>

					<tr>
						<td><?php  _e('Your Yandex kassa shop_id', 'wp-shop'); /*Ваш Yandex shop_id*/ ?></td>
						<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_yandex_kassa[shopId]" value="<?php if(isset($this->yandex_kassa['shopId'])){echo $this->yandex_kassa['shopId'];}?>"/></td>
					</tr>
					<tr>
						<td><?php  _e('Your Yandex kassa scid', 'wp-shop'); /*Ваш Yandex scid*/ ?></td>
						<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_yandex_kassa[scid]" value="<?php if(isset($this->yandex_kassa['scid'])){echo $this->yandex_kassa['scid'];}?>"/></td>
					</tr>
					<tr>
						<td><?php  _e('Your Yandex kassa shopPassword', 'wp-shop'); /*Ваш Yandex shopPassword*/ ?></td>
						<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_yandex_kassa[shopPassword]" value="<?php if(isset($this->yandex_kassa['shopPassword'])){echo $this->yandex_kassa['shopPassword'];}?>"/></td>
					</tr>
         <tr>
					<?php 
							if(isset($this->yandex_kassa['tax'])){
							$tax = $this->yandex_kassa['tax'];
							if ($tax == '1') { $m1 = ' selected="selected"'; $m2 = ''; $m3 = ''; $m4 = ''; $m5 = ''; $m6 = '';}
							if ($tax == '2') { $m1 = ''; $m2 = ' selected="selected"'; $m3 = ''; $m4 = ''; $m5 = ''; $m6 = '';}
              if ($tax == '3') { $m1 = ''; $m3 = ' selected="selected"'; $m2 = ''; $m4 = ''; $m5 = ''; $m6 = '';}
              if ($tax == '4') { $m1 = ''; $m4 = ' selected="selected"'; $m3 = ''; $m2 = ''; $m5 = ''; $m6 = '';}
              if ($tax == '5') { $m1 = ''; $m5 = ' selected="selected"'; $m3 = ''; $m4 = ''; $m2 = ''; $m6 = '';}
              if ($tax == '6') { $m1 = ''; $m6 = ' selected="selected"'; $m3 = ''; $m4 = ''; $m5 = ''; $m2 = '';}
						}
						?>
           <td><?php  _e('VAT rate', 'wp-shop'); /*Ставка НДС*/ ?></td> 
           <td>
						<select name="wpshop_payments_yandex_kassa[tax]">
              <option value='1' <?php echo $m1; ?>><?php  _e('Without VAT', 'wp-shop'); /*без НДС*/ ?></option>
							 <option value='2' <?php echo $m2; ?>><?php  _e('VAT at a rate of 0%', 'wp-shop'); /*НДС по ставке 0%*/ ?></option>
              <option value='3' <?php echo $m3; ?>><?php  _e('VAT check at a rate of 10%', 'wp-shop'); /*НДС чека по ставке 10%*/ ?></option>
              <option value='4' <?php echo $m4; ?>><?php  _e('VAT check at a rate of 18%', 'wp-shop'); /*НДС чека по ставке 18%*/ ?></option>
              <option value='5' <?php echo $m5; ?>><?php  _e('VAT check at the estimated rate of 10/110', 'wp-shop'); /*НДС чека по расчетной ставке 10/110*/ ?></option>
              <option value='6' <?php echo $m6; ?>><?php  _e('VAT check at the estimated rate of 18/118', 'wp-shop'); /*НДС чека по расчетной ставке 18/118*/ ?></option>
						</select>
           </td>
         </tr>   
					<tr>
						<td><?php  _e('Success URL', 'wp-shop'); ?></td>
						<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_yandex_kassa[successUrl]" value="<?php if(isset($this->yandex_kassa['successUrl'])){echo $this->yandex_kassa['successUrl'];}?>"/></td>
					</tr>
					<tr>
						<td><?php  _e('Failed URL', 'wp-shop'); ?></td>
						<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_yandex_kassa[failedUrl]" value="<?php if(isset($this->yandex_kassa['failedUrl'])){echo $this->yandex_kassa['failedUrl'];}?>"/></td>
					</tr>
					<tr>
						<td><?php  _e('сheckURL and paymentAvisoURL', 'wp-shop'); ?></td>
						<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_yandex_kassa[resultUrl]" value="<?php if(isset($this->yandex_kassa['resultUrl'])){echo $this->yandex_kassa['resultUrl'];}?>"  readonly="readonly"/></td>
					</tr>
					<tr>
						<td><?php  _e('Enable Sberbank online', 'wp-shop'); ?></td>
						<?php if(isset($this->yandex_kassa['sber'])&&$this->yandex_kassa['sber']){
							$yandex_sber =" checked";
						}else {
							$yandex_sber ="";
						} ?>
						<td><input type="checkbox" name="wpshop_payments_yandex_kassa[sber]"<?php  echo $yandex_sber;?>/></td>
					</tr>
					<tr>
						<td><?php  _e('Enable Webmoney', 'wp-shop'); ?></td>
						<?php if(isset($this->yandex_kassa['webmoney'])&&$this->yandex_kassa['webmoney']){
							$yandex_webmoney =" checked";
						}else {
							$yandex_webmoney ="";
						} ?>
						<td><input type="checkbox" name="wpshop_payments_yandex_kassa[webmoney]"<?php  echo $yandex_webmoney;?>/></td>
					</tr>    
					<tr>
						<td><?php  _e('Enable Qiwi', 'wp-shop'); ?></td>
						<?php if(isset($this->yandex_kassa['qiwi'])&&$this->yandex_kassa['qiwi']){
							$yandex_qiwi =" checked";
						}else {
							$yandex_qiwi ="";
						} ?>
						<td><input type="checkbox" name="wpshop_payments_yandex_kassa[qiwi]"<?php  echo $yandex_qiwi;?>/></td>
					</tr>   
					<tr>
						<td><?php  _e('Enable Promsvyazbank', 'wp-shop'); ?></td>
						<?php if(isset($this->yandex_kassa['prom'])&&$this->yandex_kassa['prom']){
							$yandex_prom =" checked";
						}else {
							$yandex_prom ="";
						} ?>
						<td><input type="checkbox" name="wpshop_payments_yandex_kassa[prom]"<?php  echo $yandex_prom;?>/></td>
					</tr>   
					<tr>
						<td><?php  _e('Enable MasterPass', 'wp-shop'); ?></td>
						<?php if(isset($this->yandex_kassa['master'])&&$this->yandex_kassa['master']){
							$yandex_master =" checked";
						}else {
							$yandex_master ="";
						} ?>
						<td><input type="checkbox" name="wpshop_payments_yandex_kassa[master]"<?php  echo $yandex_master;?>/></td>
					</tr>   
					<tr>
						<td ><?php  _e('Enable Alfa Click', 'wp-shop'); ?></td>
						<?php if(isset($this->yandex_kassa['alfa'])&&$this->yandex_kassa['alfa']){
							$yandex_alfa =" checked";
						}else {
							$yandex_alfa ="";
						} ?>
						<td><input type="checkbox" name="wpshop_payments_yandex_kassa[alfa]"<?php  echo $yandex_alfa;?>/></td>
					</tr>   
					<tr>
						<td ><?php  _e('Enable buy in credit', 'wp-shop'); ?></td>
						<?php if(isset($this->yandex_kassa['credit'])&&$this->yandex_kassa['credit']){
							$yandex_credit =" checked";
						}else {
							$yandex_credit ="";
						} ?>
						<td><input type="checkbox" name="wpshop_payments_yandex_kassa[credit]"<?php  echo $yandex_credit;?>/></td>
					</tr> 
					<input type="hidden" name="wpshop_payments_yandex_kassa[passfrase]" value="<?php  echo $this->yandex_kassa['passfrase'];?>"/>
				</table>
			</table>
			<div style="width: 50%;float: left;text-align: right;min-width: 500px;">
				<ins data-revive-zoneid="13" data-revive-id="03af71d0efe35b0d7d888949e681431d"></ins><script async src="https://wp-shop.ru/adv/www/delivery/asyncjs.php"></script>
			</div>
			</div>
		</div>
	</div>
	
	<div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('Sberbank', 'wp-shop'); /*Сбербанк*/ ?></h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td ><?php  _e('Enable Sberbank', 'wp-shop'); ?></td>
					<?php if(isset($this->sber['activate'])&&$this->sber['activate']){
							$sber_activate =" checked";
						}else {
							$sber_activate ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_sber[activate]"<?php  echo $sber_activate;?>/></td>
				</tr>
				<tr>
					<td ><?php  _e('Test paiments', 'wp-shop'); ?></td>
					<?php if(isset($this->sber['test'])&&$this->sber['test']){
							$sber_test =" checked";
						}else {
							$sber_test ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_sber[test]"<?php  echo $sber_test;?>/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->sber['delivery'])&&$this->sber['delivery']){
							if (in_array($delivery->ID,$this->sber['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.sber",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_sber[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
					
				<tr>
					<td><?php  _e('Login', 'wp-shop'); /*Login продавца*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_sber[login]" value="<?php if(isset($this->sber['login'])){echo $this->sber['login'];}?>"/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Password', 'wp-shop'); /*Пароль продавца*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_sber[pass]" value="<?php if(isset($this->sber['pass'])){echo $this->sber['pass'];}?>"/></td>
				</tr>
				
				<tr>
					<td><?php  _e('One or two stage payments', 'wp-shop'); /*Валюта*/ ?></td>
					<td>
						<?php 
							if(isset($this->sber['stage'])){
							$stage = $this->sber['stage'];
							if ($stage == 'one') { $m1 = ' selected="selected"';}
							if ($stage== 'two') { $m1 = ''; $m2 = ' selected="selected"';}
						}
						?>
						<select name="wpshop_payments_sber[stage]">
							<option value='one' <?php echo $m1?>><?php  _e('One stage', 'wp-shop'); /*В один шаг*/ ?></option>
							<option value='two' <?php echo $m2?>><?php  _e('Two stage', 'wp-shop'); /*В два шага*/ ?></option>
						</select>
					</td>
				</tr>
				
				
				<tr>
					<td><?php  _e('Success URL', 'wp-shop'); ?></td>
					<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_sber[successUrl]" value="<?php if(isset($this->sber['successUrl'])){echo $this->sber['successUrl'];}?>"/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Failed URL', 'wp-shop'); ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_sber[failedUrl]" value="<?php if(isset($this->sber['failedUrl'])){echo $this->sber['failedUrl'];}?>"/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Currency', 'wp-shop'); /*Валюта*/ ?></td>
					<td>
						<?php 
							if(isset($this->sber['currency_sber'])){
							$currency = $this->sber['currency_sber'];
							if ($currency == '840') { $p1 = ' selected="selected"'; $p2 = ''; $p3 = '';$p4 = '';}
							if ($currency == '978') { $p1 = ''; $p2 = ' selected="selected"';$p3 = '';$p4 = '';}
							if ($currency == '643') { $p1 = ''; $p2 = '';$p3 = ' selected="selected"';$p4 = '';}
							if ($currency == '980') { $p1 = ''; $p2 = '';$p3 = ''; $p4 = ' selected="selected"';}
						}
						?>
						<select name="wpshop_payments_sber[currency_sber]">
							<option value='840' <?php echo $p1?>><?php  _e('USD', 'wp-shop'); /*Американские доллары*/ ?></option>
							<option value='978' <?php echo $p2?>><?php  _e('euro', 'wp-shop'); /*Евро*/ ?></option>
							<option value='643' <?php echo $p3?>><?php  _e('Russian Ruble', 'wp-shop'); /*Российские рубли*/ ?></option>
							<option value='980' <?php echo $p4?>><?php  _e('Ukrainian hryvnia', 'wp-shop'); /*Украинские гривны*/ ?></option>
						</select>
					</td>
				</tr>
        
        <tr>
					<td><?php  _e('Tax system', 'wp-shop'); /*Валюта*/ ?></td>
					<td>
						<?php 
							if(isset($this->sber['tax'])){
							$tax = $this->sber['tax'];
							if ($tax == '0') { $t1 = ' selected="selected"';$t2 = '';$t3 = '';$t4 = '';$t5 = '';$t6 = '';}
							if ($tax== '1') { $t1 = '';$t3 = '';$t4 = '';$t5 = '';$t6 = ''; $t2 = ' selected="selected"';}
              if ($tax == '2') { $t2 = '';$t1 = '';$t4 = '';$t5 = '';$t6 = ''; $t3 = ' selected="selected"';}
							if ($tax== '3') { $t2 = '';$t3 = '';$t1 = '';$t5 = '';$t6 = ''; $t4 = ' selected="selected"';}
              if ($tax== '4') { $t2 = '';$t3 = '';$t4 = '';$t1 = '';$t6 = ''; $t5 = ' selected="selected"';}
              if ($tax== '5') { $t2 = '';$t3 = '';$t4 = '';$t1 = '';$t5 = ''; $t6 = ' selected="selected"';}
						}
						?>
						<select name="wpshop_payments_sber[tax]">
							<option value='0' <?php echo $t1?>><?php  _e('common', 'wp-shop'); /*общая*/ ?></option>
             <option value='1' <?php echo $t2?>><?php  _e('Simplified, income', 'wp-shop'); /*упрощённая, доход*/ ?></option>
							<option value='2' <?php echo $t3?>><?php  _e('Simplified, income minus consumption', 'wp-shop'); /*упрощённая, доход минус расход*/ ?></option>
             <option value='3' <?php echo $t4?>><?php  _e('Unified tax on imputed income', 'wp-shop'); /*единый налог на вменённый доход*/ ?></option>
             <option value='4' <?php echo $t5?>><?php  _e('Unified agricultural tax', 'wp-shop'); /*единый сельскохозяйственный налог*/ ?></option>
             <option value='5' <?php echo $t6?>><?php  _e('Patent tax system', 'wp-shop'); /*патентная система налогообложения*/ ?></option>
						</select>
					</td>
         </tr>
         <tr>
            <td><?php  _e('Measurement of the quantity of a commodity item', 'wp-shop');/*Мера измерения количества товарной позиции*/ ?></td>
            <td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_sber[measure]" value="<?php if(isset($this->sber['measure'])){echo $this->sber['measure'];}?>"/></td>
				  </tr>
				</tr>
			</table>
		</div>
	</div>
	</div>
	  <div id="poststuff">
		<div class="postbox">
			<h3>Simplepay</h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td ><?php  _e('Enable Simplepay', 'wp-shop'); ?></td>
					<?php if(isset($this->simplepay['activate'])&&$this->simplepay['activate']){
							$simplepay_activate =" checked";
						}else {
							$simplepay_activate ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_simplepay[activate]"<?php  echo $simplepay_activate;?>/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->simplepay['delivery'])&&$this->simplepay['delivery']){
							if (in_array($delivery->ID,$this->simplepay['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.simplepay",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_simplepay[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
        
        <tr>
					<td><?php  _e('Outlet id', 'wp-shop'); /*Outlet id*/ ?></td>
					<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_simplepay[outlet_id]" value="<?php if(isset($this->simplepay['outlet_id'])){echo $this->simplepay['outlet_id'];}?>"/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Secure key', 'wp-shop'); /*Secure key*/ ?></td>
					<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_simplepay[secure]" value="<?php if(isset($this->simplepay['secure'])){ echo $this->simplepay['secure'];}?>"/></td>
				</tr>
        
        <tr>
					<td><?php  _e('Currency', 'wp-shop'); /*Валюта*/ ?></td>
					<td>
						<?php 
							if(isset($this->simplepay['currency_simplepay'])){
							$currency = $this->simplepay['currency_simplepay'];
							if ($currency == 'USD') { $p1 = ' selected="selected"'; $p2 = ''; $p3 = '';}
							if ($currency == 'EUR') { $p1 = ''; $p2 = ' selected="selected"';$p3 = '';}
							if ($currency == 'RUB') { $p1 = ''; $p2 = '';$p3 = ' selected="selected"';}
						}
						?>
						<select name="wpshop_payments_simplepay[currency_simplepay]">
							<option value='USD' <?php echo $p1?>><?php  _e('USD', 'wp-shop'); /*Американские доллары*/ ?></option>
							<option value='EUR' <?php echo $p2?>><?php  _e('euro', 'wp-shop'); /*Евро*/ ?></option>
							<option value='RUB' <?php echo $p3?>><?php  _e('Russian Ruble', 'wp-shop'); /*Российские рубли*/ ?></option>
						</select>
					</td>
				</tr>
					
			
			</table>
			</div>
		</div>
	</div>
  
  <div id="poststuff">
		<div class="postbox">
			<h3>Chronopay</h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
			
				<script>
					jQuery(function(){
						jQuery('#chronopay').change(function(){
							if(jQuery(this).is(':checked')){
								window.open('http://wp-shop.ru/chronopay/');
							}
						});
							
						
					});
				</script>
				<tr>
					<td ><?php  _e('Enable Chronopay', 'wp-shop'); ?></td>
					<?php if(isset($this->chronopay['activate'])&&$this->chronopay['activate']){
							$chronopay_activate =" checked";
						}else {
							$chronopay_activate ="";
					} ?>
					<td><input type="checkbox" id="chronopay" name="wpshop_payments_chronopay[activate]"<?php  echo $chronopay_activate;?>/></td>
				</tr>
				
				
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->chronopay['delivery'])&&$this->chronopay['delivery']){
							if (in_array($delivery->ID,$this->chronopay['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.chronopay",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_chronopay[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
								
				<tr>
					<td ><p><strong>Важно!</strong> для учета номера заказа необходимо связаться с администрацией Сhronopay для подключения данной услуги. Только после этого активируйте ее в настройках оплаты вашего магазина.</p></td>
				</tr>
						
				<tr>
					<td ><?php  _e('Order_id enable', 'wp-shop');//Учитывать параметр order_id ?></td>
					<?php if(isset($this->chronopay['order'])&&$this->chronopay['order']){
							$chronopay_order =" checked";
						}else {
							$chronopay_order ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_chronopay[order]"<?php  echo $chronopay_order;?>/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Product_id', 'wp-shop'); /*Product_id*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_chronopay[product_id]" value="<?php if(isset($this->chronopay['product_id'])){echo $this->chronopay['product_id'];}?>"/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Success URL', 'wp-shop'); /*Success URL*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_chronopay[success]" value="<?php if(isset($this->chronopay['success'])){echo $this->chronopay['success'];}?>"/></td>
				</tr>
        
				<tr>
					<td><?php  _e('Failed URL', 'wp-shop'); /*Failed URL*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_chronopay[failed]" value="<?php if(isset($this->chronopay['failed'])){ echo $this->chronopay['failed'];}?>"/></td>
				</tr>
        
				<tr>
					<td><?php  _e('Password', 'wp-shop'); /*Пароль*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_chronopay[sharedsec]" value="<?php if(isset($this->chronopay['sharedsec'])){echo $this->chronopay['sharedsec'];}?>"/></td>
				</tr>
        
				</table>
				<div style="width: 50%;float: left;text-align: right;min-width: 500px;">
					<ins data-revive-zoneid="15" data-revive-id="03af71d0efe35b0d7d888949e681431d"></ins><script async src="https://wp-shop.ru/adv/www/delivery/asyncjs.php"></script>
				</div>
			</div>
		</div>
	</div>
	
 </div> 
 <div id="wpshop_tabs_metabox-3">
	<!--Artpay begin-->
	<div id="poststuff">
		<div class="postbox">
			<h3>ArtPay</h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td><?php  _e('Enable support of a payment through ArtPay', 'wp-shop'); ?></td>
					  <?php if(isset($this->ap['activate'])&&$this->ap['activate']){
						$ap_activate =" checked";
					  }else {
						$ap_activate ="";
					  } ?>
					<td><input type="checkbox" name="wpshop_payments_ap[activate]"<?php  echo $ap_activate;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->ap['delivery'])&&$this->ap['delivery']){
								if (in_array($delivery->ID,$this->ap['delivery']))
								{
									$checked = " checked";
								}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.ap",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_ap[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}
					?>
					</td>
				</tr>
				<tr>
					<td><?php  _e('Test paiments', 'wp-shop'); ?></td>
					<?php if(isset($this->ap['test'])&&$this->ap['test']){
						$ap_test =" checked";
					}else {
						$ap_test ="";
					} ?>						
					<td><input type="checkbox" name="wpshop_payments_ap[test]"<?php  echo $ap_test;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Artpay ID', 'wp-shop'); ?></td>
					<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_ap[id]" value="<?php if(isset($this->ap['id'])){echo $this->ap['id'];}?>"/></td>
				</tr>
				<tr>
					<td><?php  _e('Artpay pass 1', 'wp-shop'); /*Artpay пароль 1*/ ?></td>
					<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_ap[pass1]" value="<?php if(isset($this->ap['pass1'])){echo $this->ap['pass1'];}?>"/></td>
				</tr>
				<tr>
					<td><?php  _e('Artpay pass 2', 'wp-shop'); /*Artpay пароль 2*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_ap[pass2]" value="<?php if(isset($this->ap['pass2'])){echo $this->ap['pass2'];}?>"/></td>
				</tr>
			</table>
			</div>
		</div>
	</div>
	<!--Artpay end-->
  
  <div id="poststuff">
		<div class="postbox">
			<h3>Primearea.biz</h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td ><?php  _e('Enable Primearea', 'wp-shop'); ?></td>
					<?php if(isset($this->primearea['activate'])&&$this->primearea['activate']){
							$primearea_activate =" checked";
						}else {
							$primearea_activate ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_primearea[activate]"<?php  echo $primearea_activate;?>/></td>
				</tr>
        
        <tr>
					<td><?php  _e('Test paiments', 'wp-shop'); ?></td>
					<?php if(isset($this->primearea['test'])&&$this->primearea['test']){
						$primearea_test =" checked";
					}else {
						$primearea_test ="";
					} ?>						
					<td><input type="checkbox" name="wpshop_payments_primearea[test]"<?php  echo $primearea_test;?>/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->primearea['delivery'])&&$this->primearea['delivery']){
							if (in_array($delivery->ID,$this->primearea['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.primearea",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_primearea[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}
					?>
					</td>
				</tr>
	
        <tr>
          <td><?php  _e('Secure key', 'wp-shop'); ?></td>
          <td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_primearea[secure]" value="<?php if(isset($this->primearea['secure'])){echo $this->primearea['secure'];}?>"/></td>
        </tr>
      
        <tr>
          <td><?php  _e('Shopid', 'wp-shop'); ?></td>
          <td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_primearea[shopid]" value="<?php if(isset($this->primearea['shopid'])){echo $this->primearea['shopid'];}?>"/></td>
        </tr>			
        
        <tr>
          <td><?php  _e('Success URL', 'wp-shop'); ?></td>
          <td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_primearea[successUrl]" value="<?php if(isset($this->primearea['successUrl'])){echo $this->primearea['successUrl'];}?>"/></td>
        </tr>
        <tr>
          <td><?php  _e('Failed URL', 'wp-shop'); ?></td>
          <td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_primearea[failedUrl]" value="<?php if(isset($this->primearea['failedUrl'])){echo $this->primearea['failedUrl'];}?>"/></td>
        </tr>
			</table>
			</div>
		</div>
	</div>
 
  <div id="poststuff">
		<div class="postbox">
			<h3>SOFORT</h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td ><?php  _e('Enable SOFORT banking', 'wp-shop'); ?></td>
					<?php if(isset($this->sofort['activate'])&&$this->sofort['activate']){
							$sofort_activate =" checked";
						}else {
							$sofort_activate ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_sofort[activate]"<?php  echo $sofort_activate;?>/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->sofort['delivery'])&&$this->sofort['delivery']){
							if (in_array($delivery->ID,$this->sofort['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.sofort",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_sofort[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
					
			<tr>
				<tr>
					<td><?php  _e('Config key', 'wp-shop'); /*Email продавца*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_sofort[conf_key]" value="<?php if(isset($this->sofort['conf_key'])){echo $this->sofort['conf_key'];}?>"/></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><?php  _e('You need to enter your configkey or userid:projektid:apikey', 'wp-shop'); /*Email продавца*/ ?></td>
				</tr>
			</tr>			
			<tr>
				<td><?php  _e('Notification email', 'wp-shop'); ?></td>
				<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_sofort[notifEmail]" value="<?php if(isset($this->sofort['notifEmail'])){echo $this->sofort['notifEmail'];}?>"/></td>
			</tr>			
			<tr>
				<td ><?php  _e('Trust pending payments', 'wp-shop'); ?></td>
				<?php if(isset($this->sofort['trust'])&&$this->sofort['trust']){
						$sofort_trust =" checked";
				}else {
						$sofort_trust ="";
				} ?>
				<td><input type="checkbox" name="wpshop_payments_sofort[trust]"<?php  echo $sofort_trust;?>/></td>
			</tr>
			<tr>
				<td><?php  _e('Success URL', 'wp-shop'); ?></td>
				<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_sofort[successUrl]" value="<?php if(isset($this->sofort['successUrl'])){echo $this->sofort['successUrl'];}?>"/></td>
			</tr>
			<tr>
				<td><?php  _e('Failed URL', 'wp-shop'); ?></td>
				<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_sofort[failedUrl]" value="<?php if(isset($this->sofort['failedUrl'])){echo $this->sofort['failedUrl'];}?>"/></td>
			</tr>
			<tr>
				<td><?php  _e('resultURL', 'wp-shop'); ?></td>
				<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_sofort[resultUrl]" value="<?php if(isset($this->sofort['resultUrl'])){echo $this->sofort['resultUrl'];}?>"  readonly="readonly"/></td>
			</tr>
			<input type="hidden" name="wpshop_payments_sofort[passfrase]" value="<?php echo $this->sofort['passfrase'];?>"/>
			</table>
			</div>
		</div>
	</div>
	
	<div id="poststuff">
		<div class="postbox">
			<h3>PayPal</h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td ><?php  _e('Enable PayPal', 'wp-shop'); ?></td>
					<?php if(isset($this->paypal['activate'])&&$this->paypal['activate']){
							$paypal_activate =" checked";
						}else {
							$paypal_activate ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_paypal[activate]"<?php  echo $paypal_activate;?>/></td>
				</tr>
				
				<tr>
					<td ><?php  _e('Test paiments', 'wp-shop'); ?></td>
					<?php if(isset($this->paypal['test'])&&$this->paypal['test']){
							$paypal_test =" checked";
						}else {
							$paypal_test ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_paypal[test]"<?php  echo $paypal_test;?>/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->paypal['delivery'])&&$this->paypal['delivery']){
							if (in_array($delivery->ID,$this->paypal['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.paypal",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_paypal[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
					
				<tr>
					<td><?php  _e('Saller Email', 'wp-shop'); /*Email продавца*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_paypal[email]" value="<?php if(isset($this->paypal['email'])){echo $this->paypal['email'];}?>"/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Success URL', 'wp-shop'); /*Success URL*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_paypal[success]" value="<?php if(isset($this->paypal['success'])){echo $this->paypal['success'];}?>"/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Currency', 'wp-shop'); /*Валюта*/ ?></td>
					<td>
						<?php 
							if(isset($this->paypal['currency_paypal'])){
							$currency = $this->paypal['currency_paypal'];
							if ($currency == 'USD') { $p1 = ' selected="selected"'; $p2 = ''; $p3 = '';}
							if ($currency == 'EUR') { $p1 = ''; $p2 = ' selected="selected"';$p3 = '';}
							if ($currency == 'RUB') { $p1 = ''; $p2 = '';$p3 = ' selected="selected"';}
						}
						?>
						<select name="wpshop_payments_paypal[currency_paypal]">
							<option value='USD' <?php echo $p1?>><?php  _e('USD', 'wp-shop'); /*Американские доллары*/ ?></option>
							<option value='EUR' <?php echo $p2?>><?php  _e('euro', 'wp-shop'); /*Евро*/ ?></option>
							<option value='RUB' <?php echo $p3?>><?php  _e('Russian Ruble', 'wp-shop'); /*Российские рубли*/ ?></option>
						</select>
					</td>
				</tr>
			</table>
			<div style="width: 50%;float: left;text-align: right;min-width: 500px;">
				<ins data-revive-zoneid="14" data-revive-id="03af71d0efe35b0d7d888949e681431d"></ins><script async src="https://wp-shop.ru/adv/www/delivery/asyncjs.php"></script>
			</div>
			</div>
		</div>
	</div>
	
	<div id="poststuff">
		<div class="postbox">
			<h3>Interkassa</h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td ><?php  _e('Enable Interkassa', 'wp-shop'); ?></td>
					<?php if(isset($this->interkassa['activate'])&&$this->interkassa['activate']){
							$interkassa_activate =" checked";
						}else {
							$interkassa_activate ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_interkassa[activate]"<?php  echo $interkassa_activate;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('shop_id', 'wp-shop'); /*Ваш shop_id*/ ?></td>
					<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_interkassa[shopId]" value="<?php if(isset($this->interkassa['shopId'])){echo $this->interkassa['shopId'];}?>"/></td>
				</tr>
				<tr>
					<td><?php  _e('secret key', 'wp-shop'); ?></td>
					<td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_interkassa[secret]" value="<?php if(isset($this->interkassa['secret'])){echo $this->interkassa['secret'];}?>"/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->interkassa['delivery'])&&$this->interkassa['delivery']){
							if (in_array($delivery->ID,$this->interkassa['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.interkassa",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_interkassa[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
        
			   <tr>
				  <td><?php  _e('Success URL', 'wp-shop'); ?></td>
				  <td style="min-width:300px;"><input type="text" style="width:100%;" name="wpshop_payments_interkassa[successUrl]" readonly="readonly" value="<?php if(isset($this->interkassa['successUrl'])){echo $this->interkassa['successUrl'];}?>"/></td>
			   </tr>
			   <tr>
				  <td><?php  _e('Failed URL', 'wp-shop'); ?></td>
				  <td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_interkassa[failedUrl]" readonly="readonly" value="<?php if(isset($this->interkassa['failedUrl'])){echo $this->interkassa['failedUrl'];}?>"/></td>
			   </tr>
				
				<tr>
					<td><?php  _e('Currency', 'wp-shop'); /*Валюта*/ ?></td>
					<td>
						<?php 
							if(isset($this->interkassa['currency'])){
							$currency = $this->interkassa['currency'];
							if ($currency == 'USD') { $p1 = ' selected="selected"'; $p2 = ''; $p3 = '';$p4 = '';}
							if ($currency == 'EUR') { $p1 = ''; $p2 = ' selected="selected"';$p3 = '';$p4 = '';}
							if ($currency == 'RUB') { $p1 = ''; $p2 = '';$p3 = ' selected="selected"';$p4 = '';}
							if ($currency == 'UAH') { $p1 = ''; $p2 = '';$p3 = '';$p4 = ' selected="selected"';}
						}
						?>
						<select name="wpshop_payments_interkassa[currency]">
							<option value='USD' <?php echo $p1?>><?php  _e('USD', 'wp-shop'); /*Американские доллары*/ ?></option>
							<option value='EUR' <?php echo $p2?>><?php  _e('euro', 'wp-shop'); /*Евро*/ ?></option>
							<option value='RUB' <?php echo $p3?>><?php  _e('Russian Ruble', 'wp-shop'); /*Российские рубли*/ ?></option>
							<option value='UAH' <?php echo $p4?>><?php  _e('Ukrainian hryvnia', 'wp-shop'); /*Украинская гривна*/ ?></option>
						</select>
					</td>
				</tr>
        
			</table>
			
			</div>
		</div>
	</div>
	
	<div id="poststuff">
		<div class="postbox">
			<h3>ICredit</h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td ><?php  _e('Enable ICredit', 'wp-shop'); ?></td>
					<?php if(isset($this->icredit['activate'])&&$this->icredit['activate']){
							$icredit_activate =" checked";
						}else {
							$icredit_activate ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_icredit[activate]"<?php  echo $icredit_activate;?>/></td>
				</tr>
        <tr>
					<td ><?php  _e('Test paiments', 'wp-shop'); ?></td>
					<?php if(isset($this->icredit['test'])&&$this->icredit['test']){
							$icredit_test =" checked";
						}else {
							$icredit_test ="";
					} ?>
					<td><input type="checkbox" name="wpshop_payments_icredit[test]"<?php  echo $icredit_test;?>/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->icredit['delivery'])&&$this->icredit['delivery']){
							if (in_array($delivery->ID,$this->icredit['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.icredit",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_icredit[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
       <tr>
					<td><?php  _e('GroupPrivateToken', 'wp-shop'); /*GroupPrivateToken*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_icredit[token]" value="<?php if(isset($this->icredit['token'])){echo $this->icredit['token'];}?>"/></td>
				</tr>
        <tr>
					<td><?php  _e('Success URL', 'wp-shop'); /*Success URL*/ ?></td>
					<td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_icredit[success]" value="<?php if(isset($this->icredit['success'])){echo $this->icredit['success'];}?>"/></td>
				</tr>
				
				<tr>
					<td><?php  _e('Currency', 'wp-shop'); /*Валюта*/ ?></td>
					<td>
						<?php 
							if(isset($this->icredit['currency'])){
							$currency_icredit = $this->icredit['currency'];
							if ($currency_icredit == '1') { $pr1 = ' selected="selected"'; $pr2 = ''; $pr3 = '';}
							if ($currency_icredit == '2') { $pr1 = ''; $pr2 = ' selected="selected"';$pr3 = '';}
							if ($currency_icredit == '3') { $pr1 = ''; $pr2 = '';$pr3 = ' selected="selected"';}
						}
						?>
						<select name="wpshop_payments_icredit[currency]">
							<option value='1' <?php echo $pr1?>><?php  _e('Shekel', 'wp-shop'); /*Шекель*/ ?></option>
							<option value='2' <?php echo $pr2?>><?php  _e('USD', 'wp-shop'); /*Американские доллары*/ ?></option>
							<option value='3' <?php echo $pr3?>><?php  _e('EUR', 'wp-shop'); /*Евро*/ ?></option>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>
	</div>
</div>
<div id="wpshop_tabs_metabox-4">
	<div id="poststuff">
		<div class="postbox">
			<h3><?php  _e('Cryptonator', 'wp-shop'); ?></h3>
			<div class="wpshop_inside_block">
			<table cellpadding="2" cellspacing="2" style="width: 50%;float: left;min-width: 500px;">
				<tr>
					<td><?php  _e('Enable support for Cripto', 'wp-shop'); /*Включить поддержку наложного платежа*/ ?></td>
						<?php if(isset($this->cripto['activate'])&&$this->cripto['activate']){
						$cripto_activate =" checked";
						}else {
							$cripto_activate ="";
						} ?>
					<td><input type="checkbox" name="wpshop_payments_cripto[activate]"<?php  echo $cripto_activate;?>/></td>
				</tr>
				<tr>
					<td><?php  _e('Delivery', 'wp-shop'); /*Доставка*/ ?></td>
					<td>
					<?php 
						$i = 0;
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if(isset($this->cripto['delivery'])&&$this->cripto['delivery']){
							if (in_array($delivery->ID,$this->cripto['delivery']))
							{
								$checked = " checked";
							}
							}elseif($i==3){ $checked = " checked"; update_option("wpshop.payments.cripto",array('delivery' => array(2=>'vizit')));}
							echo "<input type='checkbox' name='wpshop_payments_cripto[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
							if(++$i == 5) break;
						}

					?>
					</td>
				</tr>
				<tr>
                    <td><?php _e('Merchant_id', 'wp-shop'); ?></td>
                    <td style="min-width:300px;"><input class="cripto_m" style="width:100%;" type="text"  
                        name="wpshop_payments_cripto[merchant_id]"
                        value="<?php if (isset($this->cripto['merchant_id'])) { echo $this->cripto['merchant_id'];} ?>"/>
					</td>
                </tr>
				<tr>
                    <td><?php _e('Secret', 'wp-shop'); ?></td>
                    <td style="min-width:300px;"><input class="cripto_s" style="width:100%;" type="text"  
                        name="wpshop_payments_cripto[secret]"
                        value="<?php if (isset($this->cripto['secret'])) { echo $this->cripto['secret'];} ?>"/>
					</td>
                </tr>
				<tr>
					<td><?php  _e('Currency', 'wp-shop'); /*Валюта*/ ?></td>
					<td>
						<?php 
						if(isset($this->cripto['currency_cripto'])){
							$currency = $this->cripto['currency_cripto'];
							if ($currency == 'rur') { $p1 = ' selected="selected"'; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = ''; $p8 = ''; $p9 = '';$p10 = '';}
							if ($currency == 'usd') { $p1 = ''; $p2 = ' selected="selected"'; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = '';$p8 = ''; $p9 = '';$p10 = ''; }
							if ($currency == 'eur') { $p1 = ''; $p2 = ''; $p3 = ' selected="selected"'; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = '';$p8 = ''; $p9 = '';$p10 = ''; }
							if ($currency == 'blackcoin') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ' selected="selected"'; $p5 = ''; $p6 = ''; $p7 = '';$p8 = ''; $p9 = '';$p10 = ''; }
							if ($currency == 'bitcoin') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ' selected="selected"'; $p6 = ''; $p7 = '';$p8 = ''; $p9 = '';$p10 = ''; }
							if ($currency == 'dash') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ' selected="selected"'; $p7 = '';$p8 = ''; $p9 = '';$p10 = ''; }
							if ($currency == 'dogecoin') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = ' selected="selected"';$p8 = ''; $p9 = '';$p10 = ''; }
							if ($currency == 'emercoin') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = '';$p8 = ' selected="selected"'; $p9 = '';$p10 = ''; }
							if ($currency == 'litecoin') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = ''; $p8 = ''; $p9 = ' selected="selected"';$p10 = '';}
							if ($currency == 'peercoin') { $p1 = ''; $p2 = ''; $p3 = ''; $p4 = ''; $p5 = ''; $p6 = ''; $p7 = ''; $p8 = ''; $p9 = '';$p10 = ' selected="selected"'; }
						}
						?>
						<select name="wpshop_payments_cripto[currency_cripto]">
							<option value='rur' <?php echo $p1?>><?php  _e('Russian Ruble', 'wp-shop'); /*Российские рубли*/ ?></option>
							<option value='usd' <?php echo $p2?>><?php  _e('USD', 'wp-shop'); /*Американские доллары*/ ?></option>
							<option value='eur' <?php echo $p3?>><?php  _e('euro', 'wp-shop'); /*Евро*/ ?></option>
							<option value='blackcoin' <?php echo $p4?>><?php  _e('blackcoin', 'wp-shop'); ?></option>
							<option value='bitcoin' <?php echo $p5?>><?php _e('bitcoin', 'wp-shop'); ?></option>
							<option value='dash' <?php echo $p6?>><?php  _e('dash', 'wp-shop'); ?></option>
							<option value='dogecoin' <?php echo $p7?>><?php  _e('dogecoin', 'wp-shop'); ?></option>
							<option value='emercoin' <?php echo $p8?>><?php  _e('emercoin', 'wp-shop'); ?></option>
							<option value='litecoin' <?php echo $p9?>><?php  _e('litecoin', 'wp-shop'); ?></option>
							<option value='peercoin' <?php echo $p10?>><?php  _e('peercoin', 'wp-shop'); ?></option>
						</select>
					</td>
				</tr>
					<td><?php  _e('Language of payment form', 'wp-shop'); /*Валюта*/ ?></td>
					<td>
						<?php 
						if(isset($this->cripto['lang_cripto'])){
							$currency = $this->cripto['lang_cripto'];
							if ($currency == 'ru') { $pl1 = ' selected="selected"'; $pl2 = ''; $pl3 = ''; $pl4 = ''; $pl5 = ''; $pl6 = ''; }
							if ($currency == 'en') { $pl1 = ''; $pl2 = ' selected="selected"'; $pl3 = ''; $pl4 = ''; $pl5 = ''; $pl6 = ''; }
							if ($currency == 'de') { $pl1 = ''; $pl2 = ''; $pl3 = ' selected="selected"'; $pl4 = ''; $pl5 = ''; $pl6 = ''; }
							if ($currency == 'es') { $pl1 = ''; $pl2 = ''; $pl3 = ''; $pl4 = ' selected="selected"'; $pl5 = ''; $pl6 = ''; }
							if ($currency == 'fr') { $pl1 = ''; $pl2 = ''; $pl3 = ''; $pl4 = ''; $pl5 = ' selected="selected"'; $pl6 = ''; }
							if ($currency == 'cn') { $pl1 = ''; $pl2 = ''; $pl3 = ''; $pl4 = ''; $pl5 = ''; $pl6 = ' selected="selected"'; }
						}
						?>
						<select name="wpshop_payments_cripto[lang_cripto]">
							<option value='ru' <?php echo $pl1?>><?php  _e('Russian', 'wp-shop'); ?></option>
							<option value='en' <?php echo $pl2?>><?php  _e('English', 'wp-shop'); ?></option>
							<option value='de' <?php echo $pl3?>><?php  _e('Deutsch', 'wp-shop'); ?></option>
							<option value='es' <?php echo $pl4?>><?php  _e('Spanish', 'wp-shop'); ?></option>
							<option value='fr' <?php echo $pl5?>><?php _e('French', 'wp-shop'); ?></option>
							<option value='cn' <?php echo $pl6?>><?php  _e('Chinese', 'wp-shop'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
                    <td><?php  _e('Success URL', 'wp-shop'); ?></td>
                    <td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_cripto[successUrl]" value="<?php  if(isset($this->cripto['successUrl'])){echo $this->cripto['successUrl'];}?>"/></td>
                </tr>
                <tr>
                    <td><?php  _e('Failed URL', 'wp-shop'); ?></td>
                    <td style="min-width:300px;"><input style="width:100%;" type="text" name="wpshop_payments_cripto[failedUrl]" value="<?php if(isset($this->cripto['failedUrl'])){echo $this->cripto['failedUrl'];}?>"/></td>
                </tr>
			</table>
			<div style="width: 50%;float: left;text-align: center;min-width: 500px;">
				<p class="cripto_button_text"><?php  echo __('To activate the payment system, click the button below.', 'wp-shop');?></p>
				<a href="https://ru.cryptonator.com/auth/signup/101516800" target="_blank" class="cripto_button"><?php  echo __('Activate account', 'wp-shop');?></a>
			</div>
			</div>
		</div>
	</div>
</div>
</div>
	<input type="submit" value="<?php  _e('Save', 'wp-shop'); /*Сохранить*/ ?>" class="button">

</form>
</div>