<?php 
class Wpshop_Shortcodes
{
	
	private $view;
  private $shop;

	public function __construct()
	{
		$this->view = new Wpshop_View();
    $this->shop = $GLOBALS['wpshop_obj'];
		
    add_filter('the_content', array(&$this,'PriceList'));
		add_filter('the_content', array(&$this,'PriceInfo'));
		add_filter('the_content', array(&$this,'Vitrina'));
    add_filter('the_content', array(&$this,'Showcase'));
    add_filter('the_content', array(&$this,'Showcase_by_type'));
	}	
		
  public function Showcase($content)
	{
		return preg_replace_callback('/(<!--|\[)showcase input:(\S+)\s*cols:(\d+)\s*height:(\d+)\s*rows:(\d*)\s*text:(\d*|null)\s*field:(\w+|null)\s*width:(\d*|null)\s*button_rad:(\d*|null)\s*custom_class:(\w+|null)\s*shop_text:([a-zа-яё \-\<\>\'\"\=\/]+)\s*img_height:(\d*|null)\s*border_color:(\#[A-Fa-f0-9]{6}|\#[A-Fa-f0-9]{3}|null)\s*text_color:(\#[A-Fa-f0-9]{6}|\#[A-Fa-f0-9]{3}|null)\s*text_color_h:(\#[A-Fa-f0-9]{6}|\#[A-Fa-f0-9]{3}|null)\s*bg_color:(\#[A-Fa-f0-9]{6}|\#[A-Fa-f0-9]{3}|null)\s*bg_color_h:(\#[A-Fa-f0-9]{6}|\#[A-Fa-f0-9]{3}|null)\s*align:(\w+|null)\s*hide_img:(1|null)\s*hide_sklad:(1|null)\s*item_border:(1|null)\s*hide_title:(1|null)\s*hide_text:(1|null)\s*hide_shop:(1|null)\s*pagg:(1|null)\s*hide_counter:(1|null)\s*hide_vars:(1|null)\s*rating:(1|null)(\]|-->)/ui',array(&$this,'ShowcaseCallBack'),$content);
	}
  
  public function Showcase_by_type($content)
	{
		return preg_replace_callback('/(<!--|\[)showcase_by_type posttype:(\w+)\s*taxonomy:(\w+)\s*term:([\d,]+|null)\s*include_id:([\d,]+|null)\s*exclude_id:([\d,]+|null)\s*cols:(\d+)\s*height:(\d+)\s*rows:(\d*)\s*text:(\d*|null)\s*field:(\w+|null)\s*width:(\d*|null)\s*button_rad:(\d*|null)\s*custom_class:(\w+|null)\s*shop_text:([a-zа-яё \-\<\>\'\"\=\/]+)\s*img_height:(\d*|null)\s*border_color:(\#[A-Fa-f0-9]{6}|\#[A-Fa-f0-9]{3}|null)\s*text_color:(\#[A-Fa-f0-9]{6}|\#[A-Fa-f0-9]{3}|null)\s*text_color_h:(\#[A-Fa-f0-9]{6}|\#[A-Fa-f0-9]{3}|null)\s*bg_color:(\#[A-Fa-f0-9]{6}|\#[A-Fa-f0-9]{3}|null)\s*bg_color_h:(\#[A-Fa-f0-9]{6}|\#[A-Fa-f0-9]{3}|null)\s*align:(\w+|null)\s*hide_img:(1|null)\s*hide_sklad:(1|null)\s*item_border:(1|null)\s*hide_title:(1|null)\s*hide_text:(1|null)\s*hide_shop:(1|null)\s*pagg:(1|null)\s*hide_counter:(1|null)\s*hide_vars:(1|null)\s*rating:(1|null)\s*hide_filter:(1|null)(\]|-->)/ui',array(&$this,'ShowcaseByTypeCallBack'),$content);
	}

	public function PriceList($content)
	{
		return preg_replace_callback('/(<!--|\[)wpshop pricelist\s*([\d,]*)(\]|-->)/', array(&$this,'PriceListCallback'),$content);
	}
  
  public function PriceInfo($content)
	{
		return preg_replace_callback('/(<!--|\[)wpshop price_tag\s*([\S,]*)(\]|-->)/', array(&$this,'PriceInfoCallback'),$content);
	}
  
  public function Vitrina($content)
	{
		return preg_replace_callback('/(<!--|\[)vitrina (\S+)\s*(\d+)\s*(\d+)\s*(\d*)\s*(\d*)(\]|-->)/',array(&$this,'VitrinaCallBack'),$content);
	}
  
  public function ShowcaseCallBack($params)
	{
		ob_start();
		$this->view->shop = $this->shop;
		$this->view->colCount = $params[3];
		$this->view->height = $params[4];
    $this->view->rowCount = $params[5];
    $this->view->countSimbols = $params[6]=='null' ? 150 : $params[6];
    $this->view->field = $params[7];
    $this->view->width = $params[8];
    $this->view->button_rad = $params[9];
    $this->view->custom_class = $params[10];
    
    $shop_text = html_entity_decode($params[11]);
    $shop_text = str_replace("\"","'",$shop_text); 
    $this->view->shop_text = $shop_text;   
    
    $this->view->img_height = $params[12];
    $this->view->border_color = $params[13];
    $this->view->text_color = $params[14];
    $this->view->text_color_h = $params[15];
    $this->view->bg_color = $params[16];
    $this->view->bg_color_h = $params[17];
    $this->view->align = $params[18];
    $this->view->hide_img = $params[19];
    $this->view->hide_sklad = $params[20];
    $this->view->item_border = $params[21];
    
    $this->view->hide_title = $params[22];
    $this->view->hide_text = $params[23];
    $this->view->hide_shop = $params[24];
    $this->view->pagg = $params[25];
    $this->view->hide_counter = $params[26];
    $this->view->hide_vars = $params[27];
    $this->view->rating = $params[28];
    
		$this->view->page = isset($_GET['vpage']) ? $_GET['vpage'] : 1;

    if(isset($_GET['order_query'])&&$_GET['order_query']!='') {
      $this->view->order_query =  $_GET['order_query'];
    }
    if(isset($_GET['select'])&&$_GET['select']!='') {
      $this->view->select =  $_GET['select'];
    }   
    
		// Проверяем, возможно ли это витрина по категориям
		$category = "";
    if($params[2]!='null'&&$params[2]!=''){
      if (preg_match("/cat=(\S+)/",$params[2],$category))
      {
        $this->view->category = $category[1];
      }
      else
      {
        $this->view->tag = $params[2];
      }
    }
		$this->view->params = $params;
    
    //locale
    $this->view->by_title = __('by title','wp-shop');
    $this->view->by_date = __('by date','wp-shop');
    $this->view->by_price = __('by price','wp-shop');
    $this->view->randomly = __('randomly','wp-shop');
    
    $this->view->decrease = __('decrease','wp-shop');
    $this->view->increase = __('increase','wp-shop');
    $this->view->Buy = __(' Buy', 'wp-shop');
    $this->view->Sort_by = __('Sort by', 'wp-shop');
    
		$this->view->render("vitrina.php");
		return ob_get_clean();
	}
  
  public function ShowcaseByTypeCallBack($params)
	{
		ob_start();
		$this->view->shop = $this->shop;
    $this->view->post_type = $params[2];
    $this->view->tax = $params[3];
    $this->view->term = $params[4];
    $this->view->include_id = $params[5];
    $this->view->exclude_id = $params[6];
		$this->view->colCount = $params[7];
		$this->view->height = $params[8];
    $this->view->rowCount = $params[9];
    $this->view->countSimbols = $params[10]=='null' ? 150 : $params[10];
    $this->view->field = $params[11];
    $this->view->width = $params[12];
    $this->view->button_rad = $params[13];
    $this->view->custom_class = $params[14];
    
    $shop_text = html_entity_decode($params[15]);
    $shop_text = str_replace("\"","'",$shop_text); 
    $this->view->shop_text = $shop_text;   
    
    $this->view->img_height = $params[16];
    $this->view->border_color = $params[17];
    $this->view->text_color = $params[18];
    $this->view->text_color_h = $params[19];
    $this->view->bg_color = $params[20];
    $this->view->bg_color_h = $params[21];
    $this->view->align = $params[22];
    $this->view->hide_img = $params[23];
    $this->view->hide_sklad = $params[24];
    $this->view->item_border = $params[25];
    
    $this->view->hide_title = $params[26];
    $this->view->hide_text = $params[27];
    $this->view->hide_shop = $params[28];
    $this->view->pagg = $params[29];
    $this->view->hide_counter = $params[30];
    $this->view->hide_vars = $params[31];
    $this->view->rating = $params[32];
    $this->view->filter = $params[33];
    
		$this->view->page = isset($_GET['vpage']) ? $_GET['vpage'] : 1;

    if(isset($_GET['order_query'])&&$_GET['order_query']!='') {
      $this->view->order_query =  $_GET['order_query'];
    }
    if(isset($_GET['select'])&&$_GET['select']!='') {
      $this->view->select =  $_GET['select'];
    }   
    
		$this->view->params = $params;
    
    //locale
    $this->view->by_title = __('by title','wp-shop');
    $this->view->by_date = __('by date','wp-shop');
    $this->view->by_price = __('by price','wp-shop');
    $this->view->randomly = __('randomly','wp-shop');
    
    $this->view->decrease = __('decrease','wp-shop');
    $this->view->increase = __('increase','wp-shop');
    $this->view->Buy = __(' Buy', 'wp-shop');
    $this->view->Sort_by = __('Sort by', 'wp-shop');
    
		$this->view->render("showcase_by_type.php");
		return ob_get_clean();
	}
  
  public function VitrinaCallBack($params)
	{
		ob_start();
		$this->view->shop = $this->shop;
		$this->view->colCount = $params[3];
		$this->view->rowCount = $params[5];
		$this->view->height = $params[4];
		$this->view->countSimbols = empty($params[6]) ? 150 : $params[6];
		$this->view->page = isset($_GET['vpage']) ? $_GET['vpage'] : 1;
		// Проверяем, возможно ли это витрина по категориям
		$category = "";
		if (preg_match("/cat=(\S+)/",$params[2],$category))
		{
			$this->view->category = $category[1];
		}
		else
		{
			$this->view->tag = $params[2];
		}
		$this->view->params = $params;
		$this->view->render("vitrina.php");
		return ob_get_clean();
	}

	public function PriceListCallback($matches)
	{
		global $post;
		$categories = explode(",",$matches[2]);
		$result = "";
		$result .= "<ul class=\"price_categories\">";
		$cats = array();
		foreach ($categories as $cat_ID)
		{
			$cat = get_category($cat_ID);
			$result .= "<li><a href=\"#{$cat->slug}\">{$cat->name}</a></li>";
			$cats[] = $cat;
		}
		$result .="</ul>";

		$meta_under_title = get_option('wpshop_price_under_title');

		foreach($cats as $cat)
		{
			$my_query = new WP_Query("cat={$cat->term_id}&orderby=date&order=desc&posts_per_page=-1&page_id != {$post->ID}");
			if (!$my_query->have_posts()) continue;

			$result .= "<table class=\"price_table\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">";
			$result .= "<tr class=\"h\"><th colspan=\"3\"><h3><a name=\"{$cat->slug}\">{$cat->name}</a></h3></th></tr>";
			preg_match_all("/(.+)(\r\n|<br \/>)*/",get_post_meta($post->ID,"thead",true),$r,PREG_PATTERN_ORDER);

			$result .= "<tr class=\"_h\">";
			foreach($r[0] as $key=>$temp)
			{
				$result .="<th>{$temp}</th>";
			}
			$result .="</tr>";

			while ($my_query->have_posts())
			{
				$my_query->the_post();
				$p = $my_query->post;

				if (!get_post_meta($p->ID,"cost_1",true))continue;

				#Пропускаем запись, если все склады равны нулю.
				$all_sklad = 0;
				$post_custom = get_post_custom($p->ID);
				$is_sklad == false;
				foreach($post_custom as $key => $value)
				{
					if (strpos($key,"sklad_") !== false)
					{
						$all_sklad += current($value);
						$is_sklad = true;
					}
				}

				if ($all_sklad == 0 && $is_sklad) continue;
				if ($i++ % 2) $class="odd"; else $class="even";
				$result .= "<tr class=\"{$class}\" valign=\"top\">";
				if (!empty($meta_under_title))
				{
					$under_title = get_post_meta($p->ID,$meta_under_title,true);
				}
				else
				{
					$under_title = '';
				}
				$result .= "<td class=\"title\"><a href=\"".get_permalink($p->ID)."\">{$p->post_title}</a><div>{$under_title}</div></td>";
				$result .= "<td class='wpshop_table_td'>".$this->shop->GetGoodWidget($p,null,array('name'=>true,'cost'=>true))."</td>";
				$result .= "</tr>";
			}
			wp_reset_query();
			$result .="</table>";
		}
		return $result;
	}

	public function PriceInfoCallback($matches)
	{
		global $post;
		$meta_under_title = get_option('wpshop_price_under_title');
		$my_query = new WP_Query(array("tag"=>$matches[2],"posts_per_page"=>"-1"));
		$result .= "<table class=\"price_table\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">";
		$pos = $my_query->get_posts();
		wp_reset_query();

		preg_match_all("/(.+)(\r\n|<br \/>)*/",get_post_meta($post->ID,"thead",true),$r,PREG_PATTERN_ORDER);
		$result .= "<tr class=\"_h\">";
		foreach($r[0] as $key=>$temp)
		{
			$result .="<th>{$temp}</th>";
		}
		$result .="</tr>";

		foreach($pos as $p)
		{
			if (!get_post_meta($p->ID,"cost_1",true))continue;
			$all_sklad = 0;
			$is_sklad = false;
			$post_custom = get_post_custom($p->ID);
			foreach($post_custom as $key => $value)
			{
				if (strpos($key,"sklad_") !== false)
				{
					$all_sklad += current($value);
					$is_sklad = true;
				}
			}
			if ($all_sklad == 0 && $is_sklad) continue;

			if ($i++ % 2) $class = "odd"; else $class = "even";

			if (!empty($meta_under_title))
			{
				$under_title = get_post_meta($p->ID,$meta_under_title,true);
			}
			else
			{
				$under_title = '';
			}

			$result .= "<tr class=\"{$class}\" valign=\"top\">";
			$result .= "<td width=50% class=\"title\"><a href=\"".get_permalink($p->ID)."\">{$p->post_title}</a><div>{$under_title}</div></td>";
			$result .= "<td class='wpshop_table_td'>".$this->shop->GetGoodWidget($p,null,array('name'=>true,'cost'=>true))."</td>";
			$result .= "</tr>";
		}
		$result .= "</table>";
		return $result;
	}
}