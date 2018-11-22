<script type="text/javascript">
jQuery(function()
{
	jQuery(".cform").prepend("<input type='hidden' name='delivery' value=''/>");
	jQuery(".cform").prepend("<input type='hidden' name='custom_delivery_adress' value=''/>");
	jQuery(".cform").prepend("<input type='hidden' name='custom_delivery_cost' value=''/>");
	window.__cart.afterChange();
	window.__cart.afterChange1();
  jQuery(document).ajaxSuccess(function() {
    jQuery('#wpshop_cart tr.rb_item').each(function() {
      jQuery(this).find('.rb_delete a').hide();
    });
    jQuery('#wpshop_cart tfoot tr').each(function() {
      jQuery(this).find('td.rb_delete a').hide();
    });
        
    jQuery('#wpshop_cart tr.rb_item').each(function() {
      jQuery(this).find('.rb_num a').hide();
      jQuery(this).find('input').prop('disabled', true);
    });
  });
});


</script>

<?php 
$user_delivery;
foreach($this->payments as $payment){
	if(in_array('user',$payment->data['delivery'])&& $payment->paymentID == $_GET['payment']){
		echo "<!--user_del-->";
		$user_delivery = 1;
	}
}
?>
<?php do_action('wpshop_custom_delivery_recycle_bin_page_before_select');?>
<?php $yandex_delivery_opts = $this->yandex_delivery;
if (isset($yandex_delivery_opts['cart_code'])&&$user_delivery==1&&isset($yandex_delivery_opts['activate'])&&$yandex_delivery_opts['activate']==='on'&&$yandex_delivery_opts['cart_code']!='') {
	echo $yandex_delivery_opts['cart_code'];
?>
  <!-- Создаем условный объект с данными о содержимом корзины (для примера) -->
  <script type="text/javascript">
  
	jQuery(function()
	{
    window.cart = {};
    var delete_cookie = function(name) {
      document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    };
    delete_cookie('ydOrder');
    jQuery('form.cform .sendbutton').attr('data-ydwidget-createorder','');
    jQuery.ajax({
        type: "POST",
        url: "<?php echo get_bloginfo('wpurl');?>/wp-admin/admin-ajax.php?action=cart_info",
        data: {action:'cart_info'},
        dataType: "json",
        success: function(t){
          
          window.cart = t;
        }
    });
    
    if(jQuery("ul.custom_del").find('li.select').hasClass('ya_dostavka')===true) {
		jQuery("#yandex_dostavka_form_container").show();
		var url_str = jQuery('form.cform').attr('action');
		var new_url_str = url_str.replace("/?", "/?ya_dostavka=1&");
		jQuery('form.cform').attr('action',new_url_str)
    }
		
    jQuery("ul.custom_del li > a.img, ul.custom_del li > a.info").click(function(){
		jQuery(".cform [name='custom_delivery_cost']").val('');
		jQuery(".cform [name='custom_delivery_adress']").val('');
		if(jQuery("ul.custom_del").find('li.select').hasClass('ya_dostavka')===true) {
			jQuery("#yandex_dostavka_form_container").show();
      var url_str = jQuery('form.cform').attr('action');
      var new_url_str = url_str.replace("/?", "/?ya_dostavka=1&");
      jQuery('form.cform').attr('action',new_url_str);
    }else {
			jQuery("#yandex_dostavka_form_container").hide();
      var url_str = jQuery('form.cform').attr('action');
      var new_url_str = url_str.replace("/?ya_dostavka=1&","/?");
      jQuery('form.cform').attr('action',new_url_str);
		}
	});
	});
  </script>
  
  <script type="text/javascript">
  ydwidget.ready(function(){
    ydwidget.initCartWidget({
		
      // Получить указанный пользователем город.
      'getCity': function () {
        var city = yd$('#city').val();
        if (city) {
          return {value: city};
        } else {
           return false;
        }
      },
      // id элемента-контейнера.
      'el': 'ydwidget',
      // Общее количество товаров в корзине.
      'totalItemsQuantity': function () { return cart.quantity },
      // Общий вес товаров в корзине.
      'weight': function () { return cart.weight },
      // Общая стоимость товаров в корзине.
      'cost': function () { return cart.cost },
      // Габариты и количество по каждому товару в корзине.
      'itemsDimensions': function () {return cart.dimentions},
      // Объявленная ценность заказа. В данном случае она равна общей стоимости заказа. Влияет на расчет стоимости в предлагаемых вариантах доставки.
      'assessed_value': function () { return cart.cost },
      //Флаг отправки заказа через единый склад. Влияет на расчет стоимости в предлагаемых вариантах доставки.
      'to_yd_warehouse': 1,
      // Обработка автоматически определенного города.
      'setCity': function (city, region) { yd$('#city').val(city + ', ' + region) },
      // Обработка смены варианта доставки.
      'onDeliveryChange': function (delivery) {
        // Если выбран вариант доставки, вывести его описание и закрыть виджет, иначе произошел 
        //сброс варианта, очищаем описание.
        if (delivery) {
			
          yd$('.custom_del .ya_dostavka a.info').attr('cost',delivery.costWithRules);
		  yd$('.custom_del .ya_dostavka a.img').attr('cost',delivery.costWithRules);
		  window.__cart.afterChange1();
		 
          yd$('#delivery_cost_value').text(delivery.costWithRules);
		  yd$(".cform [name='custom_delivery_cost']").val(delivery.costWithRules);
		 
		  yd$(".cform [name='custom_delivery_adress']").val(yd$('#city').val()+', '+yd$('#street').val()+' '+yd$('#house').val()+', '+yd$('#index').val());
		  
		  yd$('#delivery_description').text(ydwidget.cartWidget.view.helper.getDeliveryDescription(delivery));
          ydwidget.cartWidget.close();
        } else {
          yd$('.custom_del .ya_dostavka a.info').attr('cost','');
		  yd$('.custom_del .ya_dostavka a.img').attr('cost','');
		  yd$('#delivery_description').text('')
        }
      },
      // Завершение загрузки корзинного виджета.
      'onLoad': function () {
        // При клике на переключатель, если это не переключатель «Яндекс.Доставка», сбрасываем 
        //выбранную доставку в виджете.
       /*  yd$(document).on('click', '.custom_del li', function () {
          if (yd$(this).not('.ya_dostavka')) {
            ydwidget.cartWidget.setDeliveryVariant(null);
          }
        }); */
        // Добавляем в форму отсутствующие поля «Улица», «Дом», «Индекс».
        var $streetField = yd$('<div><input type="text" id="street" placeholder="<?php _e('Street'/*Улица*/, 'wp-shop');?>"></div>');
        var $houseField = yd$('<div><input type="text" id="house" placeholder="<?php _e('House'/*Дом*/, 'wp-shop');?>"></div>');
        var $indexField = yd$('<div><input type="text" id="index" placeholder="<?php _e('Index'/*Индекс*/, 'wp-shop');?>"></div>');
        yd$('#city_cont').after($streetField, $houseField, $indexField);
      },
      // Снятие выбора с варианта доставки Яндекс.Доставка (настроенного в CMS).
      //'unSelectYdVariant': function () { yd$('.custom_del li.ya_dostavka').hasClass('select') },
      // Автодополнение.
      'autocomplete': ['city', 'street', 'index'],
      'cityEl': '#city',
      'streetEl': '#street',
      'houseEl': '#house',
      'indexEl': '#index',
      //Создать заказ в cookie для его последующего создания в Яндекс.Доставке только если 
      //выбрана доставка Яндекс.Доставкой.
      //'createOrderFlag': function () { return yd$('.custom_del li.ya_dostavka').hasClass('select'); },
      // Необходимые для создания заказа поля.
      // Можно указывать и другие поля, см. объект Order в Справочнике API корзинного виджета.
      'order': {
      // Имя, фамилия, телефон, улица, дом, индекс.
       /*  'recipient_first_name': function () { return yd$('#name').val() },
        'recipient_last_name': function () { return yd$('#surname').val() },
        'recipient_phone': function () { return yd$('#phone').val() }, */
        'deliverypoint_street': function () { return yd$('#street').val() },
        'deliverypoint_house': function () { return yd$('#house').val() },
        'deliverypoint_index': function () { return yd$('#index').val() }, 
        //объявленная ценность заказа
        'order_assessed_value': function () { return cart.cost },
        //флаг отправки заказа через единый склад.
        'delivery_to_yd_warehouse': 1,
        // Товарные позиции в заказе (возможные).
        // Можно указывать и другие поля, см. объект OrderItem в Справочнике API корзинного 
        //виджета.
        'order_items': function () {
            return cart.goods;
        }
      },
      // id элемента для вывода ошибок валидации. Вместо него можно указать параметр 
      //onValidationEnd, для кастомизации вывода ошибок.
      'errorsEl': 'yd_errors',
      // Запустить сабмит формы, когда валидация успешно прошла и заказ создан в cookie,
      //либо createOrderFlag вернул false.
      'runOrderCreation': function () {yd$('form.cform .sendbutton').submit() }
    })
  })
</script>
<?php } ?>
<br/>
<?php 
echo "<span class='choose_del'>";
echo __('Choose a delivery method:', 'wp-shop'); //Выберите способ доставки:
echo "</span>";

if ($user_delivery == 1){
	 wp_reset_query();
	$the_query_1 = new WP_Query(
	array(
		'post_type' => 'wpshop_user_delivery',
		'tax_query' => array(
			array(
				'taxonomy' => 'payment_del',
				'field' => 'slug',
				'terms' => $_GET['payment']
			)
		),
		'caller_get_posts'=> 1,
		'post_status' => 'publish',
		'posts_per_page' => -1 
		) 
	);?>
	<?php  $firstPayment = null;
	$dost_content = '';?>
	<ul class="custom_del">
	<?php  while ( $the_query_1->have_posts() ) :  $the_query_1->the_post();
	$del_id = $the_query_1->post->ID;
	$cost_del = get_post_meta($del_id, 'cost_del', true);
	$free_shiping = get_post_meta($del_id, 'free_shiping', true);
  $formula_shiping = get_post_meta($del_id, 'free_formula', true);
	$cost_link = get_permalink($del_id);
  $yandex_dost = get_post_meta($del_id, 'wpshop_yandex_del', true);
 	$thumbnail = wp_get_attachment_image_src ( get_post_thumbnail_id ($del_id),full);
	$del_name = get_the_title();
		
	if (isset($yandex_dost)&&$yandex_dost!=''&&isset($yandex_delivery_opts['activate'])&&$yandex_delivery_opts['activate']==='on') {
		$dost_class = 'ya_dostavka';
		$dost_class_full = 'class="ya_dostavka"';
		$content_post_dost = get_post(get_the_ID());
		$dost_content = $content_post_dost->post_content;
	}else {
		$dost_class = '';
		$dost_class_full = '';
	}
	$custom_del_plugin = '';
	$custom_del_plugin = apply_filters('wpshop_custom_delivery_dost_class',$custom_del_plugin,$del_id);
	if (is_array($custom_del_plugin)&&isset($custom_del_plugin['class'])&&$custom_del_plugin['class']!='') {
		$dost_class = $custom_del_plugin['class'];
		$dost_class_full = $custom_del_plugin['class_full'];;
	}
  ?>
	<?php  if ($firstPayment == null){
			$firstPayment = $del_name;
			echo "<li class='select $dost_class'>";
			if( !empty ($thumbnail)){
				echo "<a cost='".$cost_del."' free_delivery='{$free_shiping}' formula_shiping='{$formula_shiping}' class='img'><img src='".$thumbnail[0]."' /></a><br>";
			}
			echo "<a cost='".$cost_del."' free_delivery='{$free_shiping}' formula_shiping='{$formula_shiping}' link='".$cost_link."' class='info'>".$del_name."</a>";
			echo "<br>";
			echo "<a href='".$cost_link."' class='delivery_link_more'>";
			_e('more...'/*Подробнее о доставке*/, 'wp-shop');
			echo "</a>";
			echo "</li>";
		}else{
			echo "<li $dost_class_full>";
			if( !empty ($thumbnail)){
			echo "<a cost='".$cost_del."' free_delivery='{$free_shiping}' formula_shiping='{$formula_shiping}' class='img'><img src='".$thumbnail[0]."' /></a><br>";
		}
      
		echo "<a cost='".$cost_del."' free_delivery='{$free_shiping}' formula_shiping='{$formula_shiping}' link='".$cost_link."' class='info'>".$del_name."</a>";
		echo "<br>";
			echo "<a href='".$cost_link."' class='delivery_link_more'>";
			_e('more...'/*Подробнее о доставке*/, 'wp-shop');
			echo "</a>";
			echo "</li>";
		}
	endwhile;?>
	</ul>
	<div class="clear"></div>
	
  <?php do_action('wpshop_custom_delivery_recycle_bin_page_after_select');?>
	
  <?php if (isset($yandex_delivery_opts['activate'])&&$yandex_delivery_opts['activate']==='on') {?>
	<div id="yandex_dostavka_form_container" style="display:none;">
		<!-- элемент для отображения ошибок валидации -->
		<?php if (isset($dost_content)&&$dost_content!=''){?>
			<div class="delivery_desc"><?php echo $dost_content;?></div>
		<?php } ?>
		<div id="yd_errors"></div>

		<form>
			<div id="city_cont"><input type="text" name="city" placeholder="<?php _e('City'/*Город*/, 'wp-shop');?>" id="city"></div>
			
			<div class="clear"></div>
			<div id="delivery_description"></div>
			<div class="clear"></div>
			<input type="button" class="wpshop-button" data-ydwidget-open value="<?php _e('calculate shipping costs'/*расчитать стоимость доставки*/, 'wp-shop');?>" >
		</form>

		<!-- Элемент-контейнер виджета. Класс yd-widget-modal обеспечивает отображение виджета в модальном окне -->
		<div id="ydwidget" class="yd-widget-modal"></div>
		<div class="clear"></div>
	</div>
	<?php }  wp_reset_postdata();
}else{
?>	

<select name="select_delivery" class="select_delivery" >
	<?php 
	$firstPayment = null;
	foreach($this->delivery as $delivery)
	{
		foreach($this->payments as $payment)
		{
			if (in_array($delivery->ID,$payment->data['delivery']) && $payment->paymentID == $_GET['payment'])
			{
				if ($firstPayment == null)
				{
					$firstPayment = $delivery->ID;
					$selected = ' selected';
				}
				else
				{
					$selected = '';
				}
				echo "<option value='{$delivery->ID}' free_delivery='{$delivery->free_delivery}' cost='{$delivery->cost}'{$selected}>{$delivery->name}</option>";
			}
		}
	}
	?>
</select>
<?php }?>
&nbsp;&nbsp;
<?php
$del_cond_link = get_option('wpshop.cart.deliveyrCondition');
if(isset($del_cond_link)&&$del_cond_link!=''){?>
<a class="del_cond" href="<?php  echo $del_cond_link; ?>">
	<?php  echo __('Delivery details', 'wp-shop'); ?>
</a>
<?php } ?>