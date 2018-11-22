<?php /*  function my_cforms_filter($POSTdata) {
  if (is_array($POSTdata)){
     if ($POSTdata['wpshop']==1){
      $cform_name = Wpshop_RecycleBin::getCformsName($POSTdata);
	  //echo "<script>console.log( 'Debug Objects: " . json_encode($POSTdata) . "' );</script>";
      if (!empty($cform_name)) {
        Wpshop_Forms::setDataSend();
        return Wpshop_RecycleBin::actionOrder($POSTdata);
      }
       return $POSTdata; 
    }
  }
} */

add_action('cforms2_after_processing_action', function ($validFormData) {
  $POSTdata = $_POST;
  if (is_array($POSTdata)){
     if ($POSTdata['wpshop']==1){
      $cform_name = Wpshop_RecycleBin::getCformsName($POSTdata);
      if (!empty($cform_name)) {
        Wpshop_Forms::setDataSend();
        Wpshop_RecycleBin::actionOrder($POSTdata);
      }
    }
  }
});

class Wpshop_Boot
{
	private $view;
  
  /**
	 * Css style file using administator of site.
	 * @var string
	 */
	private $_css;
	
	public function __construct()
	{
		wpshop_init_lang();
		define( 'CURR',	get_option("wpshop.currency") ); // Валюта
		$this->disableMagicQuotes();
		Wpshop_Forms::getInstance()->checkcforms(Wpshop_Payment::getSingleton()->getPayments());
		$page = new Wpshop_Page();
		$recycleBin = Wpshop_RecycleBin::getInstance();

		add_action('init', array(&$this,'ajaxRequest'));
		add_action('init', array(&$this,'ymlRequest'));
		add_action('init', array(&$this,'miniThumbnail'));
    add_action( 'wp_enqueue_scripts', array(&$this,'enq_js') );

		$post = new Wpshop_Post();

		if (is_admin())
		{
			$admin = new Wpshop_Admin();
		}
		else
		{
			add_filter('widget_text',	array(&$this,'widgetReplace'));
		}
		$user = new Wpshop_User();
		$eximp = new Wpshop_ExImp();
		$GLOBALS['wpshop_obj'] = new WpShop();
		$digital = new Wpshop_Digital();
		

		$profile = new Wpshop_Profile();
		add_action('admin_init',array($profile,'install'));

		function wpb_load_widget() {
			register_widget( 'Wpshop_ProfileWidget' );
		}
		add_action( 'widgets_init', 'wpb_load_widget' );

	}
  
  /**
	 * Специальная обработка cforms
	 * Код ниже отключается магические кавычки.
	 */
	private function disableMagicQuotes()
	{
		if (get_magic_quotes_gpc()) {
			$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
			while (list($key, $val) = each($process)) {
			foreach ($val as $k => $v) {
			    unset($process[$key][$k]);
			    if (is_array($v)) {
				$process[$key][stripslashes($k)] = $v;
				$process[] = &$process[$key][stripslashes($k)];
			    } else {
				$process[$key][stripslashes($k)] = stripslashes($v);
			    }
			}
		    }
		    unset($process);
		}
	}

	public function miniThumbnail(){
		if (isset($_GET['wpshop_thumbnail']))
		{
			global $wpdb;

			
			$resize = new Wpshop_Resize();
			$resize->load("http://www.prodavator.ru/img/1003104205.jpg");
			ob_get_clean();
			ob_start();
			$resize->output();
			$data = ob_get_clean(); 
			header("Content-type: image/jpeg");
			header("Content-length: ".strlen($data)."");			
			echo $data;
			exit;
		}		
	
	}




	public function ymlRequest()
	{
		if (isset($_GET['wpshop_yml']))
		{
			global $wpdb;
			ob_end_clean();
			ob_start();
			include WPSHOP_DIR ."/wpshop-yml.php";
			echo ob_get_clean();
			exit;
		}
	}

	public function ajaxRequest()
	{
		if (isset($_REQUEST['wpshop-ajax']))
		{
			$ajax = new Wpshop_Ajax();
			exit;
		}
	}

	public function widgetReplace($content)
	{
		$minicart = '<div id="'.MINICART_ID.'"><noscript>' . __('You need activate support of JavaScript and Cookies in your browser.') . '</noscript></div>';
		return str_replace(MINICART_TAG, $minicart, $content);
	}
  
  
  
  public function enq_js(){
    wp_enqueue_script('jquery');
    wp_register_style( 'font-awesome-web-font', WPSHOP_URL.'/css/fontawesome-all.min.css', false, '5.0.8', 'all' );
    wp_enqueue_script('wp-shop_general.js',WPSHOP_URL . "/js/general.js",array( 'jquery'));
    wp_enqueue_style('wp-shop_style_main',WPSHOP_URL."/css/wp-shop.css");
    $this->_css = get_option('wp-shop_cssfile');
    wp_enqueue_style('wp-shop_style',WPSHOP_URL."/styles/{$this->_css}");
    wp_register_script('wp-shop_wp-shop.js',WPSHOP_URL . "/js/wp-shop.js",array( 'jquery'));
    if ( is_user_logged_in() ) { $login=3; }else{$login=2;}
    $translation_array = array(
        'name' => __('Name'/*Наименование*/, 'wp-shop'),
        'price' => __('Price'/*Цена*/, 'wp-shop'),
        'count' => __('Qty'/*Кол-во*/, 'wp-shop'),
        'sum' => __('Sum'/*Сумма*/, 'wp-shop'),
        'url'=> get_bloginfo('wpurl'),
        'success'=>__('Successfully added to cart!'/*Успешно добавлено в корзину!*/, 'wp-shop'),
        'free'=>__('Free'/*Бесплатная доставка*/, 'wp-shop'),
        'wrong_promocode'=>__('Wrong promocode'/*Промокод не найден!*/, 'wp-shop'),
        'your_promocode'=>__('You use promocode:'/*Вы использовали промокод:*/, 'wp-shop'),
        'show_panel'=> get_option("wpshop.show_panel"),
        'yandex'=> get_option("wpshop.yandex_metrika"),
        'promocode'=> get_option("wp-shop_promo_active"),
        'cartpage'=> get_option("wpshop.cartpage"),
        'order'=> __('To do order'/*Успешно добавлено в корзину!*/, 'wp-shop'),
        'cont'=>__('Continue select'/*Успешно добавлено в корзину!*/, 'wp-shop'),
        'stock'=>__('In stock'/*На складе*/, 'wp-shop'),
        'pcs'=>__('pcs.'/**/, 'wp-shop'),
        'delet'=>__('Delete'/*Удалить*/, 'wp-shop'),
        'total'=>__('TOTAL:'/*ИТОГО:*/, 'wp-shop'),
        'delet_all'=>__('Delete all'/*Удалить все*/, 'wp-shop'),
        'empty'=>__('Empty your shoping cart', 'wp-shop'),
        'discont'=>__('Your discount:'/*Ваша скидка:*/, 'wp-shop'),
        'full_total'=>__('TOTAL with discount'/*ИТОГО со скидкой:*/, 'wp-shop'),
        'price_full'=>__('Price with delivery'/*Стоимость с учетом доставки*/, 'wp-shop'),
        'items'=>__('Items:'/*Позиций:*/, 'wp-shop'),
        'total_sum'=>__('Total:'/*На сумму:*/, 'wp-shop'),
        'user_in'=> $login,
        'submit'=>__('Submit order', 'wp-shop'),
        'return_link'=> get_option('wpshop.cart.shopping_return_link'),
        'cont_shop'=>__('Continue shopping', 'wp-shop'),
        'is_empty'=>__('Your shopping cart is empty!'/*Ваша корзина пуста.*/, 'wp-shop'),
        'stock_error'=>__('Stock error'/*На складе нет необходимого кол-ва товара*/, 'wp-shop'),
		'promoplace'=>__('Promocode'/*Промокод*/, 'wp-shop'),
		'usepromo'=>__('Use Promocode'/*Введите промокод*/, 'wp-shop'),
		'wpshop'=>WPSHOP_URL
      );
      
      wp_localize_script( 'wp-shop_wp-shop.js', 'object_name', $translation_array );
      wp_enqueue_script('wp-shop_wp-shop.js');
      wp_enqueue_style('font-awesome-web-font');
  }

}

class WpShop
{
	/**
	 * @var integer
	 */
	private $_showCost;
  
	
	/**
	 * Position of wpshop block (top or down).
	 * @var string
	 */
	private $_position;

	/**
	 * Default columns will be visible in wpshop block.
	 * @var array
	 */
	private $_defaultWidgetColumns = array('name' => true, 'cost' => true);
	private $view;

	public function __construct()
	{
		$this->view = new Wpshop_View();
		
		$this->_position = get_option('wp-shop_position');
    $this->_showCost = get_option('wp-shop_show-cost');
    $this->_promoActive = get_option('wp-shop_promo_active');

		add_filter('the_content', array(&$this,'goodPostHook'));
		add_filter('the_content', array(&$this,'AutoChanging'),15);
		add_filter('the_content', array(&$this,'propertyHook'));
	}
	
	public function GetGoodWidget($post = null, $class = "", array $columns = array('name'=>true,'cost'=>true))
	{
		if ($post == null)
		{
			global $post;
		}
		$cost = false;
		$cost0 = array();
		$name0 = array();
		$sklad0 = array();
		$sklad = array();
		$count0 = array();
		$count = array();
		$meta = get_post_custom($post->ID);
		$sort_type = get_option('wpshop.sort_price');
    $price_trim = get_option('wpshop.price_trim');
		//Properties of good
		//$meta_properties = '';
		if($meta){
		foreach ($meta as $key => $val)
		{
			if ( preg_match('/^cost_(\d+)/i', $key, $m) )
			{
				$cost0[$m[1]] = $val[0];
			}
			if ( preg_match('/^name_(\d+)/i', $key, $m) )
			{
				$name0[$m[1]] = $val[0];
			}
			if ( preg_match('/^sklad_(\d+)/i', $key, $m) )
			{
				$sklad0[$m[1]] = $val[0];
			}
      if ( preg_match('/^count_(\d+)/i', $key, $m) )
			{
				$count0[$m[1]] = $val[0];
			}
		}}
    
		if (count($cost0) > 1){
			$cost = array();
			ksort($cost0);
			foreach ($cost0 as $key => $val1){
        if (isset($name0[$key])){
          $val = $name0[$key];
        }else{
          $val = $key;
        }
        if($price_trim) {
          $cost[$val] = round($cost0[$key],2);
        }else {
          $cost[$val] = $cost0[$key];
        }				

        if (isset($sklad0[$key])){
          $sklad[$val] = $sklad0[$key];
        }
        
        if (isset($count0[$key])){
          $count[$val] = $count0[$key];
        }
      }
      
			if ($sort_type==1){
				asort($cost);
			}
			if ($sort_type==2){
				arsort($cost);
			}
		}else if(count($cost0) > 0) {
      $cost = array();
			ksort($cost0);
			foreach ($cost0 as $key => $val1){
        if (isset($name0[$key])){
          $val = $name0[$key];
        }else{
          $val = '';
        }
        if($price_trim) {
          $cost[$val] = round($cost0[$key],2);
        }else {
          $cost[$val] = $cost0[$key];
        }	
          
        if (isset($sklad0[$key])){
          $sklad[$val] = $sklad0[$key];
        }
        
        if (isset($count0[$key])){
          $count[$val] = $count0[$key];
        }
      }
    }
		
		if ($cost)
		{
			if ($sort_type==1){
				asort($cost);
			}
			if ($sort_type==2){
				arsort($cost);
			}
			ob_start();
			$this->view->class = $class;
			$this->view->cost = $cost;
			$this->view->sklad = $sklad;
      $this->view->count = $count;
			$this->view->columns = $columns;
			$this->view->post = $post;
			$this->view->render("good.widget.inc.php");
			return ob_get_clean();
		}
		return "";
	}

	/**
	 * Function processing content of page. It is checked as hook.
	 * @param string $content
	 * @return string
	*/
	public function goodPostHook($content)
	{
		if ($this->_showCost == 0)
		{
			return $content;
		}
		global $post;
		if ( $this->_position == 'top' )
		{
			return $this->GetGoodWidget($post,'wpshop_post_block').$content;
		}
		else
		{
			return $content.$this->GetGoodWidget($post);
		}
	}
  
  //show props of goods in theme
  public function GetPropertyHook($post){
		$meta = get_post_custom($post->ID);
		$properties_meta = array();
		if($meta){
			foreach ($meta as $key => $val)
			{
				if ( preg_match('/^wpshop_prop_(\d+)/i', $key, $m) )
				{
					$properties_meta[$m[1]] = $val[0];
				}
			}
		}
		$properties_meta_old = get_post_meta($post->ID,'prop',true);
		$return = '';
		$return .= "<div id='wpshop_property_{$post->ID}'>";
		if (is_array($properties_meta)&&count($properties_meta)>0)
		{
			$props = $this->parseProperty($properties_meta);
			$return .= $this->getAdditionProperty($props,$post->ID);
		}elseif($properties_meta_old != ''){
			$props = $this->parseProperty($properties_meta_old,true);
			$return .= $this->getAdditionProperty($props,$post->ID);
		}
		$return .= '</div>';
	
		return $return;
	}

	/**
	* Hook for addition properties of good
	* @param string $content
	* @return string
	*/
	public function propertyHook($content)
	{
		global $post;
		$meta = get_post_custom($post->ID);
		$properties_meta = array();
		if($meta){
			foreach ($meta as $key => $val)
			{
				if ( preg_match('/^wpshop_prop_(\d+)/i', $key, $m) )
				{
					$properties_meta[$m[1]] = $val[0];
				}
			}
		}
		$properties_meta_old = get_post_meta($post->ID,'prop',true);
		$return = '';
		$return .= "<div id='wpshop_property_{$post->ID}'>";
		if (is_array($properties_meta)&&count($properties_meta)>0)
		{
			$props = $this->parseProperty($properties_meta);
			$return .= $this->getAdditionProperty($props,$post->ID);
		}elseif($properties_meta_old != ''){
			$props = $this->parseProperty($properties_meta_old,true);
			$return .= $this->getAdditionProperty($props,$post->ID);
		}
		$return .= '</div>';
		$content = preg_replace("/<!--wp-shop text_fied\[\'(.+)\'\]-->/U","<label class='wpshop-textfield' for='wpshop-wpfield'>$1</label><br /><textarea id='wpshop-wpfield' type='text' name='wpshop-wpfield'></textarea>",$content);
		return str_replace('<!--wpshop_prop-->',$return,$content);
	}

	/**
	 * Method parsing meta_value "prop"
	 * @param string $property_meta that saving meta field "prop"
	 * @return array width datas
	 */
	private function parseProperty($property_meta,$string=false)
	{
		$return = array();
		if($string===true) {
			$properties_str = array();
			$properties_str = explode("|",$property_meta);
			$prop_count = count($properties_str);
			for ($i = 0; $i < $prop_count; ++$i){
				$z = &$return[];
				$parse_property = explode(':',$properties_str[$i]);
				$z['name'] = $parse_property[0];
				$z['values'] = explode(',',$parse_property[1]);
			}
		}else{
			foreach ($property_meta as $prop){
				$z = &$return[];
				$parse_property = explode(':',$prop);
				$z['name'] = $parse_property[0];
				$z['values'] = explode('|',$parse_property[1]);
			}
		}
		
		return $return;
	}

	/**
	 * Method returning addition list boxes saving addition properties of goods
	 * @param array $properties that saving array with properties
	 * @return string html for page
	 */
	private function getAdditionProperty($properties,$id)
	{
		$return .= "<div class='wpshop_properties'><dl>";
		$prop_count = count($properties);
		for ($i = 0; $i < $prop_count; ++$i)
		{
			$values_count = count($properties[$i]['values']);
			if (!empty($properties[$i]['name']) && $values_count > 0)
			{
				$currency = get_option("wpshop.currency");
				$return .= "<script>
				jQuery( document ).ready(function( $ ) {
					var block = jQuery(\"[name='wpshop-good-title-".$id."']\").parent('.wpshop_bag');
					var m = 0;
					var old_price_arr = new Array();
					block.find('.wpshop_buy tr').each(function(){
						var old_price = jQuery(this).find('> td.wpshop_price');
						
						var old_price_str = old_price.text();
						var old_price_num = old_price_str.replace('{$currency}','')*1;
						old_price_arr[m] = old_price_num;
						m++;
					});
					
					jQuery(\"#wpshop_property_$id .wpshop_properties\").on( \"change\",\"[name='".$properties[$i]['name']."']\",function() {
						var formula_value = 0;
						jQuery(\"#wpshop_property_$id .wpshop_properties dt\").each(function(){
							var get_formula = jQuery(this).find('option:selected').attr('formula')*1;
							if (get_formula){
								formula_value = formula_value+get_formula;
							}
						});
						var i = 0;
						block.find('.wpshop_buy tr').each(function(){
							var button_block = jQuery(this).find('> td.wpshop_button > a');
							var caption_block = jQuery(this).find('> td.wpshop_caption > a');
							var button_old_val = button_block.attr('onclick');
							var button_to_array = button_old_val.split(',');
							var old_price_num = old_price_arr[i];
							if (formula_value) {
								var new_price = old_price_num+formula_value;
								if (new_price <= 0) {
									new_price = old_price_num;
								}
								
							}else{
								var new_price = old_price_num;
							}
							button_to_array[3] = new_price;
							button_block.attr('onclick',button_to_array.toString());
							caption_block.attr('onclick',button_to_array.toString());
							jQuery(this).find('> td.wpshop_price').text(new_price+' {$currency}');
							i++;
						});
					});
				});</script>";
				$return .= "<dt>".$properties[$i]['name']." ";
				$return .= "<select name='".$properties[$i]['name']."'>";
				$return .= "<option value='". __( '-', 'wp-shop' )."'>".__( 'select', 'wp-shop' )."</option>";
				for ($j = 0; $j < $values_count; $j++)
				{
					$value=$properties[$i]['values'][$j];
					$formula = explode('=',$value);
					if ($formula[1]!=''){
						$return .= "<option formula='".$formula[1]." 'value='".$formula[0]."'>".$formula[0]."</option>";
					}else {
						$return .= "<option value='".$formula[0]."'>".$formula[0]."</option>";
					}
				}
				$return .= "</select></dt>";
			}
		}
		$return .= '</dl></div>';
		return $return;
	}

	public function AutoChangingCallback($m)
	{
		return $this->GetGoodWidget(get_post($m[1]),'wpshop_buy_new',array('cost'=>true));
	}

	public function AutoChanging($content)
	{
		return preg_replace_callback("/<\!--wpshop id_(\d+)-->/",array(&$this,'AutoChangingCallback'),$content);
	}

}