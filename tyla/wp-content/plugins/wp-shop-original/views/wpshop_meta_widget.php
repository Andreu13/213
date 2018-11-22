<script>
	jQuery( document ).ready(function( $ ) {
	
		var type_of_prod = $("#wpshop_type_of_goods").val();
		if (type_of_prod!='1'){
			$("#wpshop_metabox_tabs_container").show();
			if (type_of_prod=='2'){
				$('.wpshop_tab-1').show();
				$('.wpshop_tab-2').show();
				$('.wpshop_tab-3').show();
				$('.wpshop_tab-4').show();
				$('.wpshop_tab-5').hide();
				$('.wpshop_tab-6').hide();
				$('.wpshop_tab-7').hide();
			}
			if (type_of_prod=='3'){
				$('.wpshop_tab-1').show();
				$('.wpshop_tab-2').show();
				$('.wpshop_tab-3').show();
				$('.wpshop_tab-4').show();
				$('.wpshop_tab-5').show();
				$('.wpshop_tab-6').show();
				$('.wpshop_tab-7').hide();
			}
			if (type_of_prod=='4'){
				$('.wpshop_tab-1').show();
				$('.wpshop_tab-2').show();
				$('.wpshop_tab-3').show();
				$('.wpshop_tab-4').show();
				$('.wpshop_tab-5').hide();
				$('.wpshop_tab-6').show();
				$('.wpshop_tab-7').hide();
			}
			if (type_of_prod=='5'){
				$('.wpshop_tab-1').show();
				$('.wpshop_tab-2').show();
				$('.wpshop_tab-3').show();
				$('.wpshop_tab-4').show();
				$('.wpshop_tab-5').hide();
				$('.wpshop_tab-6').hide();
				$('.wpshop_tab-7').show();
			}
			
			jQuery("#post").validate({
				rules: {
				 wpshop_meta_cost_1: {required: true}
				},
				messages: {
					wpshop_meta_cost_1: "<?php echo $this->message; ?>"
				}
			});
		
		}else {
			$("#wpshop_metabox_tabs_container").hide();
		}
      
		var tabs = $( "#wpshop_tabs_metabox" ).tabs();
				
		$("#wpshop_type_of_goods").change(function() {
			var val = $(this).val();
			if (val!='1'){
				$("#wpshop_metabox_tabs_container").show();
				if (val=='2'){
					$('.wpshop_tab-1').show();
					$('.wpshop_tab-2').show();
					$('.wpshop_tab-3').show();
					$('.wpshop_tab-4').show();
					$('.wpshop_tab-5').hide();
					$('.wpshop_tab-6').hide();
					$('.wpshop_tab-7').hide();
				}
				if (val=='3'){
					$('.wpshop_tab-1').show();
					$('.wpshop_tab-2').show();
					$('.wpshop_tab-3').show();
					$('.wpshop_tab-4').show();
					$('.wpshop_tab-5').show();
					$('.wpshop_tab-6').show();
					$('.wpshop_tab-7').hide();
				}
				if (val=='4'){
					$('.wpshop_tab-1').show();
					$('.wpshop_tab-2').show();
					$('.wpshop_tab-3').show();
					$('.wpshop_tab-4').show();
					$('.wpshop_tab-5').hide();
					$('.wpshop_tab-6').show();
					$('.wpshop_tab-7').hide();
				}
				if (val=='5'){
					$('.wpshop_tab-1').show();
					$('.wpshop_tab-2').show();
					$('.wpshop_tab-3').show();
					$('.wpshop_tab-4').show();
					$('.wpshop_tab-5').hide();
					$('.wpshop_tab-6').hide();
					$('.wpshop_tab-7').show();
				}
				
				jQuery("#post").validate({
					rules: {
					 wpshop_meta_cost_1: {required: true}
					},
					messages: {
						wpshop_meta_cost_1: "<?php echo $this->message; ?>"
					}
				});
			}else {
				$("#wpshop_metabox_tabs_container").hide();
				jQuery("#post").validate({
					rules: {
					 wpshop_meta_cost_1: {required: false}
					}
				});
			}
		});
			
		$('#wpshop_metabox_tabs_container').on('click','.wpshop_upload_image_button',function(){
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = $(this);
			wp.media.editor.send.attachment = function(props, attachment) {
				$(button).parent().prev().attr('src', attachment.url);
				$(button).prev().val(attachment.url);
				wp.media.editor.send.attachment = send_attachment_bkp;
			}
			wp.media.editor.open(button);
			return false;    
		});
		
		/*
		 * удаляем значение произвольного поля
		 * если быть точным, то мы просто удаляем value у input type="hidden"
		 */
		$('.wpshop_remove_image_button').click(function(){
			var r = confirm("Are you sure?");
			if (r == true) {
				var src = $(this).parent().prev().attr('data-src');
				$(this).parent().prev().attr('src', src);
				$(this).prev().prev().val('');
			}
			return false;
		});	
		
		

		$('#add_new_var').click(function(){
			var num_val = parseInt($(this).data('num'));
			if (num_val>0&&num_val<=4){
				$(this).before("<div class=\"meta_item_wpshop\"><label style=\"font-weight: bold;\" for=\"wpshop_meta_name_"+num_val+"\"><?php  echo __('Name of', 'wp-shop')?> "+num_val+" <?php  echo __('product variant', 'wp-shop');?>"+"</label>"+
				"<p style=\"margin: 4px 0px;\"><em><?php  echo __('Specify the name of the','wp-shop');?> "+num_val+" <?php echo __('product variant (for example \"Size XXL\" or \"blue\") or SKU', 'wp-shop')?></em></p>"+
				"<input type=\"text\" style=\"width:100%;\" name=\"wpshop_meta_name_"+num_val+"\" value=\"\" />"+
				"</div>"+
				"<div class=\"meta_item_wpshop\"><label style=\"font-weight: bold;\" for=\"wpshop_meta_cost_"+num_val+"\"><?php  echo __('Cost of', 'wp-shop')?> "+num_val+" <?php  echo __('product variant', 'wp-shop');?>"+"</label>"+
				"<p style=\"margin: 4px 0px;\"><em><?php  echo __('Specify the cost of the product variant #','wp-shop');?>"+num_val+"</em></p>"+
				"<input type=\"text\" style=\"width:100%;\" name=\"wpshop_meta_cost_"+num_val+"\" value=\"\" />"+
				"</div>"+
				"<div class=\"meta_item_wpshop\"><label style=\"font-weight: bold;\" for=\"wpshop_meta_sklad_"+num_val+"\"><?php  echo __('Balance of #', 'wp-shop')?>"+num_val+" <?php  echo __('product variant', 'wp-shop');?>"+"</label>"+
				"<p style=\"margin: 4px 0px;\"><em><?php  echo __('If the product in unlimited quantities - leave the field blank. If the product is no longer in stock - insert 0 (zero), this product will not be available for order','wp-shop');?></em></p>"+
				"<input type=\"text\" style=\"width:100%;\" name=\"wpshop_meta_sklad_"+num_val+"\" value=\"\" />"+
				"</div>"+
				"<div class=\"meta_item_wpshop\"><label style=\"font-weight: bold;\" for=\"wpshop_meta_count_"+num_val+"\"><?php  echo __('Number of units', 'wp-shop')?> "+num_val+" <?php  echo __('product variant', 'wp-shop');?>"+"</label>"+
				"<p style=\"margin: 4px 0px;\"><em><?php  echo __('The preset number of product that the customer can put in the cart. Can be used when the goods are measured by the large number of units','wp-shop');?></em></p>"+
				"<input type=\"text\" style=\"width:100%;\" name=\"wpshop_meta_count_"+num_val+"\" value=\"\" />"+
				"</div>");
				$(this).data('num',++num_val);
				if (num_val>4) {
					$(this).hide();
				}
			}else {
				$(this).hide();
			}
			return false;
		});
	});
</script>

<style>
.meta_item_wpshop {
	padding: 10px 0px 15px;
	border-top: #e8e8e8 solid 1px;
}

.meta_item_wpshop a {
	color: blue;
}

.meta_item_wpshop.first {
	border-top: none;
}

#wpshop_type_of_goods {
	margin-bottom: 15px;
}
</style>
<?php 
	function wpshop_true_image_uploader_field( $name, $value = '', $num = 1, $w = 150, $h = 150) {
		$default = WPSHOP_URL . '/images/no_foto.png';
		if( $value ) {
			$src = $value;
		} else {
			$src = $default;
		}
		if ($num == 1) {
			echo '<div class="meta_item_wpshop first">';
			echo '<p style="font-weight: bold;margin: 4px 0px;">'.__('Main image ', 'wp-shop').'</p>';
			echo '<p style="margin: 4px 0px 10px;"><em>'.__('Optimal parameters of image: width up to 500px, file size not more than 50 KB. Show full-length pictures inside post-content by link or button', 'wp-shop').'</em></p>';
		}else {
			echo '<div class="meta_item_wpshop">';
			echo '<p style="font-weight: bold;margin: 4px 0px;">'.__('Image #', 'wp-shop').$num.'</p>';
			echo '<p style="margin: 4px 0px 10px;"><em>'.__('Works only in <a href="http://demo.wp-shop.ru" target="_blank">paid WP Shop themes</a>', 'wp-shop').'</em></p>';
		}		
		echo '<img data-src="' . $default . '" src="' . $src . '" width="' . $w . 'px" height="' . $h . 'px" />
			<div>
				<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '"  />
				<button type="submit" class="wpshop_upload_image_button button">'.__('Download ', 'wp-shop').'</button>
				<button type="submit" class="wpshop_remove_image_button button">&times;</button>
			</div>
		</div>';
	}
	
	function wpshop_variant_fields($num,$name,$cost,$sklad,$count) {
		echo '<div class="meta_item_wpshop">';
		echo '<label style="font-weight: bold;" for="wpshop_meta_name_'.$num.'">'.__('Name of', 'wp-shop').' '.$num.' '.__('product variant', 'wp-shop').'</label>';
		echo '<p style="margin: 4px 0px;"><em>'.__('Specify the name of the','wp-shop').' '.$num.' '.__('product variant (for example \"Size XXL\" or \"blue\") or SKU', 'wp-shop').'</em></p>';
		echo '<input type="text" style="width:100%;" name="wpshop_meta_name_'.$num.'" value="'.$name.'" />';
		echo '</div>';
		echo '<div class="meta_item_wpshop">';
		echo '<label style="font-weight: bold;" for="wpshop_meta_cost_'.$num.'">'.__('Cost of', 'wp-shop').' '.$num.' '.__('product variant', 'wp-shop').'</label>';
		echo '<p style="margin: 4px 0px;"><em>'.__('Specify the cost of the product variant #','wp-shop').$num.'</em></p>';
		echo '<input type="text" style="width:100%;" name="wpshop_meta_cost_'.$num.'" value="'.$cost.'" />';
		echo '</div>';
		echo '<div class="meta_item_wpshop">';
		echo '<label style="font-weight: bold;" for="wpshop_meta_sklad_'.$num.'">'.__('Balance of #', 'wp-shop').$num.' '.__('product in stock', 'wp-shop').'</label>';
		echo '<p style="margin: 4px 0px;"><em>'.__('If the product in unlimited quantities - leave the field blank. If the product is no longer in stock - insert 0 (zero), this product will not be available for order','wp-shop').'</em></p>';
		echo '<input type="text" style="width:100%;" name="wpshop_meta_sklad_'.$num.'" value="'.$sklad.'" />';
		echo '</div>';
		echo '<div class="meta_item_wpshop">';
		echo '<label style="font-weight: bold;" for="wpshop_meta_count_'.$num.'">'.__('Number of units', 'wp-shop').' '.$num.' '.__('product variant', 'wp-shop').'</label>';
		echo '<p style="margin: 4px 0px;"><em>'.__('The preset number of product that the customer can put in the cart. Can be used when the goods are measured by the large number of units','wp-shop').'</em></p>';
		echo '<input type="text" style="width:100%;" name="wpshop_meta_count_'.$num.'" value="'.$count.'" />';
		echo '</div>';
	}
	
	$type_of_goods = $this->post['type_of_goods'][0];
	if(isset($type_of_goods)){
		if($type_of_goods=='1') {$s1=' selected'; $s2 = '';$s3 = '';$s4 = '';$s5 = '';}
		if($type_of_goods=='2') {$s1=''; $s2 = ' selected';$s3 = '';$s4 = '';$s5 = '';}
		if($type_of_goods=='3') {$s1=''; $s2 = '';$s3 = ' selected';$s4 = '';$s5 = '';}
		if($type_of_goods=='4') {$s1=''; $s2 = '';$s3 = '';$s4 = ' selected';$s5 = '';}
		if($type_of_goods=='5') {$s1=''; $s2 = '';$s3 = '';$s4 = '';$s5 = ' selected';}
	}
	?>
	<p style="font-weight: bold;margin: 4px 0px;"><?php echo __('Product type', 'wp-shop');?></p>
	<p style="margin: 4px 0px 10px;"><em><?php echo __('Specify the type of this product: simple product; variant product; product with options; digital (virtual) product', 'wp-shop');?></em></p>
	<select id="wpshop_type_of_goods" name="type_of_goods">
		<option value='1' <?php echo $s1?>><?php echo __('This entry is not a product', 'wp-shop'); ?></option>
		<option value='2' <?php echo $s2?>><?php echo __('simple product', 'wp-shop'); ?></option>
		<option value='3' <?php echo $s3?>><?php echo __('variant product (product with several different variants with different prices)', 'wp-shop'); ?></option>
		<option value='4' <?php echo $s4?>><?php echo __('product with options (product have some options with mark-up to main cost value or without it)', 'wp-shop'); ?></option>
		<option value='5' <?php echo $s5?>><?php echo __('digital (virtual) goods - if the product is a file', 'wp-shop'); ?></option>
	</select>


	<div id="wpshop_metabox_tabs_container" style="display: none;" >
		<div id="wpshop_tabs_metabox">
      <ul>
        <li class="wpshop_tab-1"><a href="#wpshop_tabs_metabox-1"><?php echo __('Price, quantity', 'wp-shop'); ?></a></li>
        <li class="wpshop_tab-2"><a href="#wpshop_tabs_metabox-2"><?php echo __('Size, weight', 'wp-shop'); ?></a></li>
        <li class="wpshop_tab-3"><a href="#wpshop_tabs_metabox-3"><?php echo __('Product images', 'wp-shop'); ?></a></li>
        <li class="wpshop_tab-4"><a href="#wpshop_tabs_metabox-4"><?php echo __('Other parameters', 'wp-shop'); ?></a></li>
        <li class="wpshop_tab-5"><a href="#wpshop_tabs_metabox-5"><?php echo __('Product variations', 'wp-shop'); ?></a></li>
        <li class="wpshop_tab-6"><a href="#wpshop_tabs_metabox-6"><?php echo __('Product options', 'wp-shop'); ?></a></li>
        <li class="wpshop_tab-7"><a href="#wpshop_tabs_metabox-7"><?php echo __('Options of digital (virtual) goods', 'wp-shop'); ?></a></li>
      </ul>
      <div id="wpshop_tabs_metabox-1">
		<!--cost_1-->
		<div class="meta_item_wpshop first">
			<label style="font-weight: bold;" for="wpshop_meta_cost_1"><?php  echo __('The base price of the product ', 'wp-shop'); ?><span style="color:red;">*</span></label>
			<p style="margin: 4px 0px;"><em><?php echo __('Specify the price of product', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_cost_1" value="<?php echo $this->post['cost_1'][0]; ?>" />
		</div>
		<!--old_price-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_old_price"><?php  echo __('Old price ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __(' <strong>Warning! </strong> This option works only in <a href = "http://demo.wp-shop.ru" target = "_blank" > paid  WP Shop themes </a>! Set the "old price" of the product to show that the product have discount', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_old_price" value="<?php echo $this->post['old_price'][0]; ?>" />
		</div>
		<!--sklad_1-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_sklad_1"><?php  echo __('Balance of product in stock ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('If the product in unlimited quantities - leave the field blank. If the product is no longer in stock - insert 0 (zero), this product will not be available for order', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_sklad_1" value="<?php echo $this->post['sklad_1'][0]; ?>" />
		</div>
		<!--count_1-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_count_1"><?php  echo __('Number of units ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('The preset number of units of the item that the customer can put in the basket. Can be used when the goods are measured by the large number of units', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_count_1" value="<?php echo $this->post['count_1'][0]; ?>" />
		</div>
		<!--similar_products-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_similar_products"><?php  echo __('Show related products ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('<strong>Warning!</strong> This option works only in <a href = "http://demo.wp-shop.ru" target = "_blank" > paid WP Shop themes</a>! Enables rotation of goods in product page. The principle of output similar products: by the same tag as the root product (allows arbitrary to associate products with each other tags) If the tag is not defined - dispalays from the same category as the root product. Sort by novelty.', 'wp-shop') ?></em></p>
			<?php if(isset($this->post['similar_products'][0])&&$this->post['similar_products'][0]==1){$s1=" checked";}else {$s1="";}?>
			<input type="checkbox" name="wpshop_meta_similar_products" <?php echo $s1;?>/>
		</div>
		<!--new-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_new"><?php  echo __('New product ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('<strong>Warning!</strong> This option works only in <a href = "http://demo.wp-shop.ru" target = "_blank"> paid WP Shop themes</a>! Enable option to indicate the product as new.', 'wp-shop') ?></em></p>
			<?php if(isset($this->post['new'][0])&&$this->post['new'][0]==1){$s2=" checked";}else {$s2="";}?>
			<input type="checkbox" name="wpshop_meta_new" <?php echo $s2;?>/>
		</div>
		<!--part_url-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_part_url"><?php  echo __('Affiliate product ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('<strong>Warning!</strong> This option works only in <a href = "http://demo.wp-shop.ru" target = "_blank"> paid WP Shop themes</a>! Set the address of the product page on the store-provider site. Sets as a URL-address. Causes that by clicking on the button "buy" products placed in the shop cart, and the visitor is redirected to the specified URL. The option made for the sale of products on affiliate programs other stores', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_part_url" value="<?php echo $this->post['part_url_plugin'][0]; ?>" />
		</div>
      </div>
      <div id="wpshop_tabs_metabox-2">
		<!--wpshop_prod_weight-->
		<div class="meta_item_wpshop first">
			<label style="font-weight: bold;" for="wpshop_meta_wpshop_prod_weight"><?php  echo __('Weight, kg ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('Enter the weight in kg, e.g. 0.25', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_wpshop_prod_weight" value="<?php echo $this->post['wpshop_prod_weight'][0]; ?>" />
		</div>
		<!--wpshop_prod_x-->
		<div class="meta_item_wpshop ">
			<label style="font-weight: bold;" for="wpshop_meta_wpshop_prod_x"><?php  echo __('Length in package, cm ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('specify the length of the product in package, in cm: for example 25', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_wpshop_prod_x" value="<?php echo $this->post['wpshop_prod_x'][0]; ?>" />
		</div>
		<!--wpshop_prod_y-->
		<div class="meta_item_wpshop ">
			<label style="font-weight: bold;" for="wpshop_meta_wpshop_prod_y"><?php  echo __('Width in package, cm ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('specify the width of the goods in the package in cm, for example: 25', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_wpshop_prod_y" value="<?php echo $this->post['wpshop_prod_y'][0]; ?>" />
		</div>
		<!--wpshop_prod_z-->
		<div class="meta_item_wpshop ">
			<label style="font-weight: bold;" for="wpshop_meta_wpshop_prod_z"><?php  echo __('Height in package, cm ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('specify the height of product in package, in cm: for example 25', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_wpshop_prod_z" value="<?php echo $this->post['wpshop_prod_z'][0]; ?>" />
		</div>
	  </div>
      <div id="wpshop_tabs_metabox-3">
		<?php
			wpshop_true_image_uploader_field( 'wpshop_meta_thumbnail', get_post_meta($this->post_id, 'Thumbnail',true) );
		?>	
			<div class="meta_item_wpshop">
			<p style="font-weight: bold;margin: 4px 0px;"><?php echo __('The image for "showcases"', 'wp-shop');?></p>
			<p style="margin: 4px 0px 10px;"><em><?php echo __('HTML-code of the product pictures, which are displayed in a showcase, the output output by shortcode. It is NOT specified AS a PATH (for example: http://site.ru/files/image.jpg), as well as HTML pictures. This field can be any HTML code, as well as shortcodes of other plugins. <strong> important! </strong> Does not apply to paid WpShop themes, has its own processing of images', 'wp-shop');?></em></p>
		<?php	
			$editor_val=  get_post_meta($this->post_id, 'pic' , true ) ;
			wp_editor( htmlspecialchars_decode($editor_val), 'wpshop_metabox_editor', $settings = array('textarea_name'=>'wpshop_meta_pic','textarea_rows' => 4));
		?>
			</div>
		<?php	
			wpshop_true_image_uploader_field( 'wpshop_meta_thumbnail1', get_post_meta($this->post_id, 'Thumbnail1',true),2);
			wpshop_true_image_uploader_field( 'wpshop_meta_thumbnail2', get_post_meta($this->post_id, 'Thumbnail2',true),3);
			wpshop_true_image_uploader_field( 'wpshop_meta_thumbnail3', get_post_meta($this->post_id, 'Thumbnail3',true),4);
			wpshop_true_image_uploader_field( 'wpshop_meta_thumbnail4', get_post_meta($this->post_id, 'Thumbnail4',true),5);
			wpshop_true_image_uploader_field( 'wpshop_meta_thumbnail5', get_post_meta($this->post_id, 'Thumbnail5',true),6);
			wpshop_true_image_uploader_field( 'wpshop_meta_thumbnail6', get_post_meta($this->post_id, 'Thumbnail6',true),7);
			wpshop_true_image_uploader_field( 'wpshop_meta_thumbnail7', get_post_meta($this->post_id, 'Thumbnail7',true),8);
		
		?>
      </div>
      <div id="wpshop_tabs_metabox-4">
        <!--noyaml-->
		<div class="meta_item_wpshop first">
			<label style="font-weight: bold;" for="wpshop_meta_noyaml"><?php  echo __('Exclude from the YML feed ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('enable this option if you want to delete this product from the YML-catalog (for Yandex Market)', 'wp-shop') ?></em></p>
			<?php if(isset($this->post['noyaml'][0])&&$this->post['noyaml'][0]==1){$s3=" checked";}else {$s3="";}?>
			<input type="checkbox" name="wpshop_meta_noyaml" <?php echo $s3;?>/>
		</div>
		<!--yml_pic-->
		<div class="meta_item_wpshop">
			<?php $default = WPSHOP_URL . '/images/no_foto.png';
			$yml_pic = get_post_meta($this->post_id, 'yml_pic',true);
			if( $yml_pic ) {
				$src = $yml_pic;
			} else {
				$src = $default;
				$yml_pic = '';
			}
			?>
			<p style="font-weight: bold;margin: 4px 0px;"><?php echo __('Picture for YML ', 'wp-shop');?></p>
			<p style="margin: 4px 0px 10px;"><em><?php echo __('Specify the image to display in the YML catalogue', 'wp-shop');?></em></p>
			<img data-src="<?php echo $default;?>" src="<?php echo $src;?>" width="150px" height="150px" />
			<div>
				<input type="hidden" name="wpshop_meta_yml_pic" id="wpshop_meta_yml_pic" value="<?php echo $yml_pic;?>"  />
				<button type="submit" class="wpshop_upload_image_button button"><?php echo __('Download ', 'wp-shop');?></button>
				<button type="submit" class="wpshop_remove_image_button button">&times;</button>
			</div>
		</div>
		<!--short_text-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_short_text"><?php  echo __('Description for YML catalogue ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('Specify a description for the product that you want to display in the YML', 'wp-shop') ?></em></p>
			<textarea style="width:100%;" name="wpshop_meta_short_text" rows="4"><?php echo $this->post['short_text'][0]; ?></textarea>
		</div>
      </div>
      <div id="wpshop_tabs_metabox-5">
        <!--name_1-->
		<div class="meta_item_wpshop first">
			<label style="font-weight: bold;" for="wpshop_meta_name_1"><?php  echo __('The name of the first product variant ', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('Specify the name of the first product variant (for example "XL-size" or "color red") or SKU. The price of first product variant is taken from the base price', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_name_1" value="<?php echo $this->post['name_1'][0]; ?>" />
		</div>
		
		<?php
		$next = 2;
		if(isset($this->post['cost_2'][0])&&$this->post['cost_2'][0]!=''){
			wpshop_variant_fields(2,$this->post['name_2'][0],$this->post['cost_2'][0],$this->post['sklad_2'][0],$this->post['count_2'][0]);
			$next = 3;
		}?>
		<?php if(isset($this->post['cost_3'][0])&&$this->post['cost_3'][0]!=''){
			wpshop_variant_fields(3,$this->post['name_3'][0],$this->post['cost_3'][0],$this->post['sklad_3'][0],$this->post['count_3'][0]);
			$next = 4;
		}?>
		<?php if(isset($this->post['cost_4'][0])&&$this->post['cost_4'][0]!=''){
			wpshop_variant_fields(4,$this->post['name_4'][0],$this->post['cost_4'][0],$this->post['sklad_4'][0],$this->post['count_4'][0]);
			$next = 0;
		}?>
		<?php if ($next>0&&$next<5) {?>
			<a href="#" data-num="<?php echo $next; ?>" id="add_new_var" class="button"><?php  echo __('Add a product variation', 'wp-shop') ?></a>
		<?php }?>
		
      </div>
      <div id="wpshop_tabs_metabox-6">
        <!--wpshop_prop_1-->
		<div class="meta_item_wpshop first">
			<label style="font-weight: bold;" for="wpshop_meta_wpshop_prop_1"><?php  echo __('Option #', 'wp-shop') ?>1</label>
			<p style="margin: 4px 0px;"><em><?php echo __('Specify the name of the option #1 through the colon and vertical splitter, indicating the value of the option (for example "size: S = 0 | M = 100 | L = 150 | XL = 250 | XXL = 250 "). The price of the selected option will be summarized with the base cost and the price of other selected options', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_wpshop_prop_1" value="<?php echo $this->post['wpshop_prop_1'][0]; ?>" />
		</div>
		<!--wpshop_prop_2-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_wpshop_prop_2"><?php  echo __('Option #', 'wp-shop') ?>2</label>
			<p style="margin: 4px 0px;"><em><?php echo __('Specify the name of the option #2 followed by a colon and a vertical splitter, indicating a value options (such as "color: Blue = 0 | red = 0 | Green = 0 | 0 gray = | yellow = 100"). The price of the selected option will be summarized with the base cost and the price of other selected options', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_wpshop_prop_2" value="<?php echo $this->post['wpshop_prop_2'][0]; ?>" />
		</div>
		<!--wpshop_prop_3-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_wpshop_prop_3"><?php  echo __('Option #', 'wp-shop') ?>3</label>
			<p style="margin: 4px 0px;"><em><?php echo __('specify the name of the option #3, separated by a colon and a vertical splitter, indicating the value of the option (for example "packaging: standard = 0 | Premium = 100 | gift = 200 | VIP = 500 "). The price of the selected option will be summarized with the base cost and the price of other selected options', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_wpshop_prop_3" value="<?php echo $this->post['wpshop_prop_3'][0]; ?>" />
		</div>
      </div>
      <div id="wpshop_tabs_metabox-7">
        <!--digital_link-->
		<div class="meta_item_wpshop first">
			<label style="font-weight: bold;" for="wpshop_meta_digital_link"><?php  echo __('Selling file', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('In http://site.com/folder/file.zip format (for example), can be both internal (relative to the site store) and external', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_digital_link" value="<?php echo $this->post['digital_link'][0]; ?>" />
		</div>
		<!--digital_count-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_digital_count"><?php  echo __('The number of allowed downloads', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('Is specified as a number. Each customer downloading decreases by 1, then off for download', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_digital_count" value="<?php echo $this->post['digital_count'][0]; ?>" />
		</div>
		<!--digital_live-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_digital_live"><?php  echo __('Lifetime download links', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('Is specified as the number of hours. Upon the expiration of the specified time to live (TTL) is a file off for download', 'wp-shop') ?></em></p>
			<input type="text" style="width:100%;" name="wpshop_meta_digital_live" value="<?php echo $this->post['digital_live'][0]; ?>" />
		</div>
    <!--external_digital-->
		<div class="meta_item_wpshop">
			<label style="font-weight: bold;" for="wpshop_meta_external_digital"><?php  echo __('External url?', 'wp-shop') ?></label>
			<p style="margin: 4px 0px;"><em><?php echo __('select if this is a link to an external page', 'wp-shop') ?></em></p>
			<?php if(isset($this->post['external_digital'][0])&&$this->post['external_digital'][0]==1){$s1=" checked";}else {$s1="";}?>
			<input type="checkbox" name="wpshop_meta_external_digital" <?php echo $s1;?>/>
		</div>
      </div>
    </div>
    
	</div>
  
