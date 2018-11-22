<?php

class Wpshop_Button
{
  //keshiruem spiski terms
  private static $cats;
  private static $tags;
  private static $types;
  
  public function __construct()	{
    add_action( 'admin_enqueue_scripts', array( __CLASS__ ,'scripts__styles_for_popup_wpshop'));
    add_action( 'media_buttons', array( __CLASS__ , 'wpshop_generate_shortcode_button' ),1001 );
    
    add_action( "wp_ajax_choose_shortcode", array( __CLASS__ ,"wp_shop_original_shortcode"));
    add_action( "wp_ajax_nopriv_choose_shortcode", array( __CLASS__ ,"wp_shop_original_shortcode"));
    
    add_action( "wp_ajax_generate_shortcode", array( __CLASS__ ,"wp_shop_generate_shortcode"));
    add_action( "wp_ajax_nopriv_generate_shortcode", array( __CLASS__ ,"wp_shop_generate_shortcode"));
    
    add_action( "wp_ajax_shortcode_post_type_change", array( __CLASS__ ,"wp_shop_shortcode_post_type_change"));
    add_action( "wp_ajax_nopriv_shortcode_post_type_change", array( __CLASS__ ,"wp_shop_shortcode_post_type_change"));
    
    add_action( "wp_ajax_shortcode_taxonomy_change", array( __CLASS__ ,"wp_shop_shortcode_taxonomy_change"));
    add_action( "wp_ajax_nopriv_shortcode_taxonomy_change", array( __CLASS__ ,"wp_shop_shortcode_taxonomy_change"));
    
    
    add_filter( 'terms_clauses', array(__CLASS__ ,"wpshop_terms_clauses"), 10, 3 );
    self::$cats = self::wpshop_retrive_all_terms('category',null);
    self::$tags = self::wpshop_retrive_all_terms('post_tag',null);
    self::$types = self::wpshop_retrive_all_cont_types();
  }
  
  public static function wpshop_generate_shortcode_button(){
    //verstka knopki
    echo '<a href="#" id="insert-my-media" data-mfp-src="#wpshop_popup_shoprtcode" class="popup_wpshop_button button">
    <i class="fas fa-shopping-cart" aria-hidden="true"></i> '.__('Add wp-shop shortcode','wp-shop').'</a>';
    
    //podklichaem popup
    add_action( 'wp_footer',    array( __CLASS__ , 'wpshop_generate_popup' ));
		add_action( 'admin_footer', array( __CLASS__ , 'wpshop_generate_popup' ));
  }
  
  public static function wpshop_generate_popup(){
    echo '<div id="wpshop_popup_shoprtcode" class="mfp-hide">
      <div class="wpshop_choose_shortcode">
        <h1>'.__('Choose shortcode','wp-shop').'</h1>
        <span class="wpshop_shortcode" data-type="vitrina"><i class="fas fa-th" aria-hidden="true"></i> '.__('Showcase','wp-shop').'</span>'.
        '<span class="wpshop_shortcode" data-type="price"><i class="fas fa-list" aria-hidden="true"></i> '.__('Pricelist by category','wp-shop').'</span>'.
        '<span class="wpshop_shortcode" data-type="price_tag"><i class="fas fa-list" aria-hidden="true"></i> '.__('Pricelist by tag','wp-shop').'</span>'.
        '<span class="wpshop_shortcode" data-type="vitrina_custom_types"><i class="fas fa-th" aria-hidden="true"></i> '.__('Showcase by post type','wp-shop').' <a class="button button-primary">'.__('Pro','wp-shop').'</a></span>'.
      '</div>'.
      '<div class="wpshop_options_shortcode" style="display:none;"><div class="inner"></div></div>
    </div>';
  }
  
  
  
  public static function scripts__styles_for_popup_wpshop(){
    //peredaem vars v script
    $options_array = array(
      'url_site'=> get_bloginfo('wpurl'),
      'wpshop'=> WPSHOP_URL,
      'required'=>__('This field is required.','wp-shop'),
      'remote'=> __('Please fix this field.','wp-shop'),
      'email'=> __('Please enter a valid email address.','wp-shop'),
      'url'=> __('Please enter a valid URL.','wp-shop'),
      'date'=> __('Please enter a valid date.','wp-shop'),
      'dateISO'=> __('Please enter a valid date (ISO).','wp-shop'),
      'number'=> __('Please enter a valid number.','wp-shop'),
      'digits'=> __('Please enter only digits.','wp-shop'),
      'creditcard'=> __('Please enter a valid credit card number.','wp-shop'),
      'equalTo'=> __('Please enter the same value again.','wp-shop'),
      'accept'=> __('Please enter a value with a valid extension.','wp-shop'),
      'maxlength'=> __('Please enter no more than {0} characters.','wp-shop'),
      'minlength'=> __('Please enter at least {0} characters.','wp-shop'),
      'rangelength'=> __('Please enter a value between {0} and {1} characters long.','wp-shop'),
      'range'=> __('Please enter a value between {0} and {1}.','wp-shop'),
      'max'=> __('Please enter a value less than or equal to {0}.','wp-shop'),
      'min'=> __('Please enter a value greater than or equal to {0}.','wp-shop'),
      'pattern'=>__('Incorrect field format','wp-shop')
    );
    
    //register scripts & css
    wp_register_style( 'magnific-popup',WPSHOP_URL.'/css/magnific-popup.css', false, '1.1.0', 'all' );
    
    wp_register_style( 'wpshop_shortcodes_generator',WPSHOP_URL.'/css/generator.css', false, '1', 'all' );
		wp_register_script( 'magnific-popup', WPSHOP_URL.'/js/jquery.magnific-popup.js', array( 'jquery' ), '1.1.0', true );
    wp_register_style( 'font-awesome-web-font', WPSHOP_URL.'/css/fontawesome-all.min.css', false, '5.0.8', 'all' );
    wp_register_script( 'wpshop_shortcodes_serializejson', WPSHOP_URL.'/js/serializejson.min.js', array( 'jquery'), '1', true );
    wp_register_script( 'wpshop_shortcodes_validate_methods', WPSHOP_URL.'/js/additional-methods.min.js', array( 'jquery'), '1', true );
    wp_register_script( 'wpshop_shortcodes_generator', WPSHOP_URL.'/js/generator.js', array( 'jquery','wp-color-picker','magnific-popup',
    'wpshop_shortcodes_serializejson','validation','wpshop_shortcodes_validate_methods'), '1', true );
    
    wp_enqueue_script('magnific-popup');
    wp_enqueue_style( 'wp-color-picker' );
    
    wp_enqueue_script('wpshop_shortcodes_serializejson');
    wp_enqueue_script('wpshop_shortcodes_validate_methods');
  
    
    wp_localize_script('wpshop_shortcodes_generator', 'object_name', $options_array );
    wp_enqueue_script('wpshop_shortcodes_generator');
    
    wp_enqueue_style('magnific-popup');
    wp_enqueue_style('font-awesome-web-font');
    wp_enqueue_style('wpshop_shortcodes_generator');
  }
  
  public static function wpshop_retrive_all_terms($tax,$type){
    $term = array();
    $term_opts = array(
      'fields' => 'all',
      'hide_empty' => true
    );
    $term_obj = get_terms($tax,$term_opts);
    
    if(isset($type)&&$type!=''):
      $term_opts['post_type']=$type;
    endif;
    
    if ($term_obj&&is_array($term_obj)) {
      foreach($term_obj as $key=>$term_item) {
        $term[$term_item->term_id]['name'] = $term_item->name;
        $term[$term_item->term_id]['slug'] = $term_item->slug;
      }
    }
    return $term;
  }
  
  public static function wpshop_retrive_all_cont_types(){
    global $wp_post_types;
    $posttype_info = array();
    $posttypes = array_keys( $wp_post_types );
    // Remove _builtins or others
    $pt_remove = array(
      "attachment",
      "nav_menu_item",
      "customize_changeset",
      "elementor_library",
      "revision",
      "oembed_cache",
      "custom_css",
      "wpshopcarts",
      "wpshop_user_delivery",
      "wpshop_promo",
      "wpshop_client_mail"
    );
    foreach ( $posttypes as $posttype ):
     if ( in_array($posttype, $pt_remove) ) continue;
     $posttype_info[ $posttype ]['name'] = $wp_post_types[$posttype]->labels->name ;
    endforeach;
    return $posttype_info;
  }
  
  public static function wpshop_retrive_all_taxonomies_by_post_types($type){
    $taxes = array();
    if(isset($type)&&$type!=''):
      $taxonomy_objects = get_object_taxonomies($type,'objects');
      if(is_array($taxonomy_objects)&&!empty($taxonomy_objects)):
        foreach ($taxonomy_objects as $name=>$info):
          $taxes[$name]['name'] = $taxonomy_objects[$name]->label;
        endforeach;
      endif;
    endif;
    return $taxes;
  } 
  
  public static function wpshop_terms_clauses( $clauses, $taxonomy, $args ) {
    if ( isset( $args['post_type'] ) && ! empty( $args['post_type'] ) && $args['fields'] !== 'count' ) {
      global $wpdb;

      $post_types = array();

      if ( is_array( $args['post_type'] ) ) {
        foreach ( $args['post_type'] as $cpt ) {
          $post_types[] = "'" . $cpt . "'";
        }
      } else {
        $post_types[] = "'" . $args['post_type'] . "'";
      }

      if ( ! empty( $post_types ) ) {
        $clauses['fields'] = 'DISTINCT ' . str_replace( 'tt.*', 'tt.term_taxonomy_id, tt.taxonomy, tt.description, tt.parent', $clauses['fields'] ) . ', COUNT(p.post_type) AS count';
        $clauses['join'] .= ' LEFT JOIN ' . $wpdb->term_relationships . ' AS r ON r.term_taxonomy_id = tt.term_taxonomy_id LEFT JOIN ' . $wpdb->posts . ' AS p ON p.ID = r.object_id';
        $clauses['where'] .= ' AND (p.post_type IN (' . implode( ',', $post_types ) . ') OR p.post_type IS NULL)';
        $clauses['orderby'] = 'GROUP BY t.term_id ' . $clauses['orderby'];
      }
    }
    return $clauses;
  }
  
  public static function wp_shop_shortcode_post_type_change() {
    if ($_POST['post_type']&&$_POST['post_type']!='') {
      $taxes = self::wpshop_retrive_all_taxonomies_by_post_types($_POST['post_type']);
      echo json_encode($taxes);
    }    
    die();
  }
  
  public static function wp_shop_shortcode_taxonomy_change() {
    if ($_POST['post_type']&&$_POST['post_type']!=''&&$_POST['tax']&&$_POST['tax']!='') {
      $terms = self::wpshop_retrive_all_terms($_POST['tax'],$_POST['post_type']);
      echo json_encode($terms);
    }    
    die();
  }
  
  public static function wp_shop_original_shortcode(){
    if ($_POST['short_type']) {
      $options_list = self::get_options_list_by_type($_POST['short_type']);
      echo $options_list;
    }
    die();
  }
  
  public static function get_options_list_by_type($type){
    if ($type=='vitrina') {
      $content = self::vitrina_shortcode($type);
    }
    elseif ($type=='price') {
      $content = self::price_shortcode($type);
    }
    elseif ($type=='price_tag') {
      $content = self::price_tag_shortcode($type);
    }
    elseif ($type=='vitrina_custom_types') {
      $content = self::vitrina_custom_types_shortcode($type);
    }
    return $content;
  }
  
  public static function vitrina_shortcode($type){
    $content = '<h1>'.__('Settings of showcase shortcode','wp-shop').' <a target="_blank" class="shortcode_help" href="https://wp-shop.ru/wpshop-full-settings/id30">'.__('Read instructions','wp-shop').'</a></h1>';
    $content .= '<a href="#" class="return_select_shortcode button button-primary">'.__('Back to the selection of shortcode','wp-shop').'</a>';
    $content .= "<form id='$type'>";
    $content .= self::make_option(__('Choose the display option for the showcase','wp-shop'),'radio','variant',false,false,'short_varinat',false,false,false,false,
    array(array('value'=>'category','label'=>__('By Category','wp-shop'),'checked'=>true),array('value'=>'tag','label'=>__('By Tag','wp-shop'))));
    $content .= self::make_option(__('Select a category','wp-shop'),'select','category[]',false,false,'select_cat',false,
    __('To select multiple categories, use Ctrl','wp-shop'),true,false,self::$cats);
    $content .= self::make_option(__('Select a tag','wp-shop'),'select','tag[]',false,false,'select_tag hidden',false,
    __('To select multiple tags, use Ctrl','wp-shop'),true,false,self::$tags);
    $content .= self::make_option(__('Number of columns','wp-shop'),'input','cols',false,true);
    $content .= self::make_option(__('Number of rows','wp-shop'),'input','rows',false,true);
    $content .= self::make_option(__('Height of showcase item','wp-shop'),'input','height','550',true);
    $content .= self::make_option(__('Length of item description in characters','wp-shop'),'input','text','100');
   
    $content .= '<h1>'.__('The following settings works only in Pro version of the Wpshop plugin','wp-shop').
    '<a class="button button-primary" target="_blank" href="https://wp-shop.ru/wpshop-full-settings/id31">'.__('See more...','wp-shop').'</a></h1>';
    
    $content .= self::make_option(__('Show by custom field','wp-shop'),'input','field',false,false,false,false,__('Please type field name here','wp-shop'));
    $content .= self::make_option(__('Min width of showcase item','wp-shop'),'input','width','250',false);
    $content .= self::make_option(__('Custom CSS class for showcase item','wp-shop'),'input','custom_class',false,false,false,false,__('Enter only the class name without spaces and dots','wp-shop'));
    $content .= self::make_option(__('Shop button text','wp-shop'),'input','shop_text',"<i class='fas fa-shopping-cart'></i> ".__('Buy','wp-shop'),
    false,false,false,__("You can use fontawesome.com icons. Don't use double quotes",'wp-shop'));
    $content .= self::make_option(__('Shop button border radius','wp-shop'),'input','button_rad','5',false);
    $content .= self::make_option(__('Showcase item image height','wp-shop'),'input','img_height','200',false);
    $content .= self::make_option(__('Shop button text color','wp-shop'),'input','text_color','#fff',false,'color-picker');
    $content .= self::make_option(__('Shop button text color on hover','wp-shop'),'input','text_color_h','#fff',false,'color-picker');
    $content .= self::make_option(__('Shop button background color','wp-shop'),'input','bg_color','#000',false,'color-picker');
    $content .= self::make_option(__('Shop button background color on hover','wp-shop'),'input','bg_color_h','#2240b7',false,'color-picker');
    $content .= self::make_option(__('Shop item border color','wp-shop'),'input','border_color','#eee',false,'color-picker');
    $content .= self::make_option(__('Text align','wp-shop'),'radio','align',false,false,'radio',false,false,false,false,
    array(array('value'=>'center','label'=>__('Center','wp-shop'),'checked'=>true),array('value'=>'left','label'=>__('Left','wp-shop')),array('value'=>'right','label'=>__('Right','wp-shop'))));
    
    $content .= self::make_option(__('Hide image?','wp-shop'),'checkbox','hide_img');
    $content .= self::make_option(__('Hide title?','wp-shop'),'checkbox','hide_title');
    $content .= self::make_option(__('Hide item description?','wp-shop'),'checkbox','hide_text');
    $content .= self::make_option(__('Hide shop button?','wp-shop'),'checkbox','hide_shop');
    $content .= self::make_option(__('Hide counter?','wp-shop'),'checkbox','hide_counter',false,false,false,false,false,false,true);
    $content .= self::make_option(__('Hide showcase item with negative field `sklad_1` value?','wp-shop'),'checkbox','hide_sklad',false,false,false,false,false,false,true);
    $content .= self::make_option(__('Hide wp-shop variations?','wp-shop'),'checkbox','hide_vars');
    $content .= self::make_option(__('Show paggination?','wp-shop'),'checkbox','pagg'); 
    $content .= self::make_option(__('Show showcase item border?','wp-shop'),'checkbox','item_border',false,false,false,false,false,false,true); 
    $content .= self::make_option(__('Enable item ratings?','wp-shop'),'checkbox','rating',false,false,'label_info',false,__('Works if only WP-PostRatings plugin is activated','wp-shop'));
    
    $content .= '<div class="button_wrapp"><a href="#" data-type="'.$type.'" class="shortcode_button button button-primary button-hero">'.__('Insert Shortcode','wp-shop').'</a></div>';
    $content .= '</form>';
    return $content;
  }
  
  public static function price_shortcode($type){
    $content = '<h1>'.__('Settings of price shortcode','wp-shop').'</h1>';
    $content .= '<a href="#" class="return_select_shortcode button button-primary">'.__('Back to the selection of shortcode','wp-shop').'</a>';
    $content .= '<form>';
    $content .= self::make_option(__('Select a category','wp-shop'),'select','category[]',false,true,false,false,
    __('To select multiple categories, use Ctrl','wp-shop'),true,false,self::$cats);
    $content .= '<div class="button_wrapp"><a href="#" data-type="'.$type.'" class="shortcode_button button button-primary button-hero">'.__('Insert Shortcode','wp-shop').'</a></div>';
    $content .= '</form>';
    return $content;
  }
  
  public static function price_tag_shortcode($type){
    $content = '<h1>'.__('Settings of price shortcode','wp-shop').'</h1>';
    $content .= '<a href="#" class="return_select_shortcode button button-primary">'.__('Back to the selection of shortcode','wp-shop').'</a>';
    $content .= '<form>';
    $content .= self::make_option(__('Select a tag','wp-shop'),'select','tag[]',false,true,false,false,
    __('To select multiple tags, use Ctrl','wp-shop'),true,false,self::$tags);
    $content .= '<div class="button_wrapp"><a href="#" data-type="'.$type.'" class="shortcode_button button button-primary button-hero">'.__('Insert Shortcode','wp-shop').'</a></div>';
    $content .= '</form>';
    return $content;
  }
  
  public static function vitrina_custom_types_shortcode($type){
    $types_keys = array_keys(self::$types);
    $init_taxes = self::wpshop_retrive_all_taxonomies_by_post_types($types_keys[0]);
    
    $taxes_keys = array_keys($init_taxes);
    $init_terms = self::wpshop_retrive_all_terms($taxes_keys[0],$types_keys[0]);
    
    $content = '<h1>'.__('Settings of showcase by post type shortcode','wp-shop').' <a class="button button-primary" target="_blank" href="https://wp-shop.ru/wpshop-full-settings/id31">'.__('Buy pro','wp-shop').'</a> <a target="_blank" class="shortcode_help" href="https://wp-shop.ru/wpshop-full-settings/id32">'.__('Read instructions','wp-shop').'</a></h1>';
    $content .= '<a href="#" class="return_select_shortcode button button-primary">'.__('Back to the selection of shortcode','wp-shop').'</a>';
    $content .= "<form id='$type'>";
    $content .= self::make_option(__('Select a post type','wp-shop'),'select','posttype',false,true,'select_post_type',false,false,false,false,self::$types);
    $content .= self::make_option(__('Select a taxonomy','wp-shop'),'select','taxonomy',false,false,'select_taxonomy',false,false,false,false,$init_taxes);
    $content .= self::make_option(__('Select a term','wp-shop'),'select','term[]',false,false,'select_term',false,
    __('To select multiple terms, use Ctrl','wp-shop'),true,false,$init_terms);
    
    $content .= self::make_option(__("Include only posts with ID's",'wp-shop'),'input','include_id',false,false,false,false,__('specify separated by commas','wp-shop'));
    $content .= self::make_option(__("Exclude posts with ID's",'wp-shop'),'input','exclude_id',false,false,false,false,__('specify separated by commas','wp-shop'));
    
    $content .= self::make_option(__('Number of columns','wp-shop'),'input','cols',false,true);
    $content .= self::make_option(__('Number of rows','wp-shop'),'input','rows',false,true);
    $content .= self::make_option(__('Height of showcase item','wp-shop'),'input','height','550',true);
    $content .= self::make_option(__('Length of item description in characters','wp-shop'),'input','text','100');
   
    $content .= self::make_option(__('Show by custom field','wp-shop'),'input','field',false,false,false,false,__('Please type field name here','wp-shop'));
    $content .= self::make_option(__('Min width of showcase item','wp-shop'),'input','width','250',false);
    $content .= self::make_option(__('Custom CSS class for showcase item','wp-shop'),'input','custom_class',false,false,false,false,__('Enter only the class name without spaces and dots','wp-shop'));
    $content .= self::make_option(__('Shop button text','wp-shop'),'input','shop_text',"<i class='fas fa-shopping-cart'></i> ".__('Buy','wp-shop'),
    false,false,false,__("You can use fontawesome.com icons. Don't use double quotes",'wp-shop'));
    $content .= self::make_option(__('Shop button border radius','wp-shop'),'input','button_rad','5',false);
    $content .= self::make_option(__('Showcase item image height','wp-shop'),'input','img_height','200',false);
    $content .= self::make_option(__('Shop button text color','wp-shop'),'input','text_color','#fff',false,'color-picker');
    $content .= self::make_option(__('Shop button text color on hover','wp-shop'),'input','text_color_h','#fff',false,'color-picker');
    $content .= self::make_option(__('Shop button background color','wp-shop'),'input','bg_color','#000',false,'color-picker');
    $content .= self::make_option(__('Shop button background color on hover','wp-shop'),'input','bg_color_h','#2240b7',false,'color-picker');
    $content .= self::make_option(__('Shop item border color','wp-shop'),'input','border_color','#eee',false,'color-picker');
    $content .= self::make_option(__('Text align','wp-shop'),'radio','align',false,false,'radio',false,false,false,false,
    array(array('value'=>'center','label'=>__('Center','wp-shop'),'checked'=>true),array('value'=>'left','label'=>__('Left','wp-shop')),array('value'=>'right','label'=>__('Right','wp-shop'))));
    
    $content .= self::make_option(__('Hide image?','wp-shop'),'checkbox','hide_img');
    $content .= self::make_option(__('Hide title?','wp-shop'),'checkbox','hide_title');
    $content .= self::make_option(__('Hide item description?','wp-shop'),'checkbox','hide_text');
    $content .= self::make_option(__('Hide shop button?','wp-shop'),'checkbox','hide_shop');
    $content .= self::make_option(__('Hide counter?','wp-shop'),'checkbox','hide_counter',false,false,false,false,false,false,true);
    $content .= self::make_option(__('Hide showcase item with negative field `sklad_1` value?','wp-shop'),'checkbox','hide_sklad',false,false,false,false,false,false,true);
    $content .= self::make_option(__('Hide wp-shop variations?','wp-shop'),'checkbox','hide_vars');
    $content .= self::make_option(__('Show paggination?','wp-shop'),'checkbox','pagg'); 
    $content .= self::make_option(__('Show showcase item border?','wp-shop'),'checkbox','item_border',false,false,false,false,false,false,true); 
    $content .= self::make_option(__('Enable item ratings?','wp-shop'),'checkbox','rating',false,false,'label_info',false,__('Works if only WP-PostRatings plugin is activated','wp-shop'));
    $content .= self::make_option(__('Hide showcase filter?','wp-shop'),'checkbox','hide_filter');
    
    $content .= '<div class="button_wrapp"><a href="#" data-type="'.$type.'" class="shortcode_button button button-primary button-hero">'.__('Insert Shortcode','wp-shop').'</a></div>';
    $content .= '</form>';
    return $content;
  }  
  
  public static function make_option($title=null,$type,$name,$value=null,$required=null,$class=null,$placeholder=null,$label=null,$multi=null,$check=null,$opts=null){
    $content = '<div class="shortcode_option_wrapp';
    if($class){ $content .=' '.$class; }
    $content .='">';
    
    if ($title)  {
      $content .= '<h5>'.$title;
      if($required){ $content .= ' <span class="red">*</span>';}
      $content .= '</h5>';
    }
    
    if ($type&&$name&&$type=='input') {
      $content .= '<input type="text" name="'.$name.'" ';
      if($placeholder){ $content .= 'placeholder="'.$placeholder.'" ';}
      if($required){ $content .= ' required';}
      if($value){ $content .= ' value="'.$value.'" ';}
      $content .= '/>';
      if ($label) {
        $content .='<label>'.$label.'</label>';
      }
    }
    
    if ($type&&$name&&$type=='select'&&$opts&&is_array($opts)) {
      $content .= '<select name="'.$name.'"';
      if($required){ $content .= ' required';}
      if ($multi) { $content .= ' multiple="multiple"';}
      $content .= '>';
        foreach($opts as $key=>$value) {
          $content .= "<option value='$key'>".$value['name']."</option>";
        }
      $content .= '<select/>';
      if ($label) {
        $content .='<label>'.$label.'</label>';
      }
    }
    
    if ($type&&$name&&$type=='checkbox') {
      if ($opts&&is_array($opts)) {
        foreach($opts as $value) {
          $content .= '<input type="checkbox" name="'.$name.'" id="'.$value['value'].'" value="'.$value['value'].'" ';
          if ($value['checked']){$content .= 'checked="checked"';}
          $content .= '/>';
          if ($value['label']) {
            $content .='<label for="'.$value['value'].'">'.$value['label'].'</label>';
          }
        }
      }else {
        $content .= '<input type="checkbox" name="'.$name.'" ';
        if($check){$content .= 'checked="checked"';}
        $content .= '/>';
        if ($label) {
          $content .='<label>'.$label.'</label>';
        }
      }
    }
    
    if ($type&&$name&&$type=='radio') {
      if ($opts&&is_array($opts)) {
        foreach($opts as $value) {
          $content .= '<input type="radio" name="'.$name.'" id="'.$value['value'].'" value="'.$value['value'].'" ';
          if($value['checked']){$content .= 'checked="checked"';}
          $content .= '/>';
          if ($value['label']) {
            $content .='<label for="'.$value['value'].'">'.$value['label'].'</label>';
          }
        }
      }else {
        $content .= '<input type="radio" name="'.$name.'" ';
        if($check){$content .= 'checked="checked"';}
        $content .= '/>';
        if ($label) {
          $content .='<label>'.$label.'</label>';
        }
      }
    }
    
    if ($type&&$label&&$type=='text') {
      $content .='<p>'.$label.'</p>';
    }
    
    $content .= '</div>';
    return $content;
  }
  
  public static function wp_shop_generate_shortcode(){
    if ($_POST['short_type']&&$_POST['form_data']) {
      $shortcode = self::generate_shotcode_by_type($_POST['short_type'],$_POST['form_data']);
    }
    echo $shortcode;
    die();
  }
  
  public static function generate_shotcode_by_type($type,$data){
    
    if ($type=='vitrina') {
      $shortcode = "[showcase ";
      $shortcode .= self::vitrina_generate($data);
      $shortcode .= "]";
    }
    elseif ($type=='price') {
      $shortcode = "[wpshop pricelist ";
      $shortcode .= self::price_generate($data);
      $shortcode .= "]";
    }
    elseif ($type=='price_tag') {
      $shortcode = "[wpshop price_tag ";
      $shortcode .= self::price_tag_generate($data);
      $shortcode .= "]";
    }
    elseif ($type=='vitrina_custom_types') {
      $shortcode = "[showcase_by_type ";
      $shortcode .= self::vitrina_custom_types_generate($data);
      $shortcode .= "]";
    }

    return $shortcode;
  }
  
  public static function vitrina_generate($data){
    $form_arr = json_decode(stripcslashes($data),true);
     
    if($form_arr['variant']=='category'&&$form_arr['category']!='') {
      $shortcode .= 'input:cat=';
      if(is_array($form_arr['category'])) {
        $shortcode .= implode(",",$form_arr['category']);
      }else {
        $shortcode .= $form_arr['category'];
      }
    } 
    elseif ($form_arr['variant']=='tag'&&$form_arr['tag']!='') {
      $shortcode .= 'input:';
      if(is_array($form_arr['tag'])) {
        $all_tags = self::$tags;
        $slugs = array();
        foreach($form_arr['tag'] as $id) {
          $slugs[] = $all_tags[$id]['slug'];
        }
        $shortcode .= implode(",",$slugs);
        
      } else {
        $all_tags = self::$tags;
        $slug = $all_tags[$form_arr['tag']]['slug'];
        $shortcode .= $slug;
      } 
    }else {
      $shortcode .= 'input:null';
    }
    
    if($form_arr['cols']!='') {
      $shortcode .= ' cols:'.$form_arr['cols'];
    }
    
    if($form_arr['height']!='') {
      $shortcode .= ' height:'.$form_arr['height'];
    }else {
      $shortcode .= ' height:null';
    }
    
    if($form_arr['rows']!='') {
      $shortcode .= ' rows:'.$form_arr['rows'];
    }
    
    if($form_arr['text']!='') {
      $shortcode .= ' text:'.$form_arr['text'];
    }else {
      $shortcode .= ' text:null';
    }
    
    //pro
    
    if($form_arr['field']!='') {
      $shortcode .= ' field:'.$form_arr['field'];
    }else {
      $shortcode .= ' field:null';
    }
    
    if($form_arr['width']!='') {
      $shortcode .= ' width:'.$form_arr['width'];
    }else {
      $shortcode .= ' width:null';
    }
    
    if($form_arr['button_rad']!='') {
      $shortcode .= ' button_rad:'.$form_arr['button_rad'];
    }else {
      $shortcode .= ' button_rad:null';
    }
    
    if($form_arr['custom_class']!='') {
      $shortcode .= ' custom_class:'.$form_arr['custom_class'];
    }else {
      $shortcode .= ' custom_class:null';
    }
    
    if($form_arr['shop_text']!='') {
      $shortcode .= ' shop_text:'.$form_arr['shop_text'];
    }else {
      $shortcode .= ' shop_text:null';
    }
    
    if($form_arr['img_height']!='') {
      $shortcode .= ' img_height:'.$form_arr['img_height'];
    }else {
      $shortcode .= ' img_height:null';
    }
        
    if($form_arr['border_color']!='') {
      $shortcode .= ' border_color:'.$form_arr['border_color'];
    }else {
      $shortcode .= ' border_color:null';
    }
    
    if($form_arr['text_color']!='') {
      $shortcode .= ' text_color:'.$form_arr['text_color'];
    }else {
      $shortcode .= ' text_color:null';
    }
    
    if($form_arr['text_color_h']!='') {
      $shortcode .= ' text_color_h:'.$form_arr['text_color_h'];
    }else {
      $shortcode .= ' text_color_h:null';
    }
    
    if($form_arr['bg_color']!='') {
      $shortcode .= ' bg_color:'.$form_arr['bg_color'];
    }else {
      $shortcode .= ' bg_color:null';
    }
    
    if($form_arr['bg_color_h']!='') {
      $shortcode .= ' bg_color_h:'.$form_arr['bg_color_h'];
    }else {
      $shortcode .= ' bg_color_h:null';
    }
    
    if($form_arr['align']!='') {
      $shortcode .= ' align:'.$form_arr['align'];
    }else {
      $shortcode .= ' align:null';
    }
    
    if($form_arr['hide_img']=='on') {
      $shortcode .= ' hide_img:1';
    }else {
      $shortcode .= ' hide_img:null';
    }
    
    if($form_arr['hide_sklad']=='on') {
      $shortcode .= ' hide_sklad:1';
    }else {
      $shortcode .= ' hide_sklad:null';
    }
    
    if($form_arr['item_border']=='on') {
      $shortcode .= ' item_border:1';
    }else {
      $shortcode .= ' item_border:null';
    }
    
    if($form_arr['hide_title']=='on') {
      $shortcode .= ' hide_title:1';
    }else {
      $shortcode .= ' hide_title:null';
    }
    
    if($form_arr['hide_text']=='on') {
      $shortcode .= ' hide_text:1';
    }else {
      $shortcode .= ' hide_text:null';
    }
    
    if($form_arr['hide_shop']=='on') {
      $shortcode .= ' hide_shop:1';
    }else {
      $shortcode .= ' hide_shop:null';
    }
    
    if($form_arr['pagg']=='on') {
      $shortcode .= ' pagg:1';
    }else {
      $shortcode .= ' pagg:null';
    }  
    
    if($form_arr['hide_counter']=='on') {
      $shortcode .= ' hide_counter:1';
    }else {
      $shortcode .= ' hide_counter:null';
    }  
    
    if($form_arr['hide_vars']=='on') {
      $shortcode .= ' hide_vars:1';
    }else {
      $shortcode .= ' hide_vars:null';
    }  
   
    if($form_arr['rating']=='on') {
      $shortcode .= ' rating:1';
    }else {
      $shortcode .= ' rating:null';
    }
    
    return $shortcode;
  }
  
  public static function price_generate($data){
    $form_arr = json_decode(stripcslashes($data),true);
     
    if($form_arr['category']!='') {
      if(is_array($form_arr['category'])) {
        $shortcode .= implode(",",$form_arr['category']);
      }else {
        $shortcode .= $form_arr['category'];
      }
    }    
    
    return $shortcode;
  }
  
  public static function price_tag_generate($data){
    $form_arr = json_decode(stripcslashes($data),true);
    if ($form_arr['tag']!='') { 
      if(is_array($form_arr['tag'])) {
        $all_tags = self::$tags;
        $slugs = array();
        foreach($form_arr['tag'] as $id) {
          $slugs[] = $all_tags[$id]['slug'];
        }
        $shortcode .= implode(",",$slugs);
        
      } else {
        $all_tags = self::$tags;
        $slug = $all_tags[$form_arr['tag']]['slug'];
        $shortcode .= $slug;
      } 
    }
    return $shortcode;
  }
  
  public static function vitrina_custom_types_generate($data){
    $form_arr = json_decode(stripcslashes($data),true);
    
    if($form_arr['posttype']!='') {
      $shortcode .= 'posttype:'.$form_arr['posttype'];
    }
    
    if($form_arr['taxonomy']!='') {
      $shortcode .= ' taxonomy:'.$form_arr['taxonomy'];
    }else {
      $shortcode .= ' taxonomy:null';
    }
    
    if($form_arr['term']!='') {
      $shortcode .= ' term:';
      if (is_array($form_arr['term'])){
        $shortcode .= implode(",",$form_arr['term']);
      }else {
        $shortcode .= $form_arr['term'];
      }
    }else {
      $shortcode .= ' term:null';
    }
    
    if($form_arr['include_id']!='') {
      $shortcode .= ' include_id:'.$form_arr['include_id'];
    }else {
      $shortcode .= ' include_id:null';
    }
    
    if($form_arr['exclude_id']!='') {
      $shortcode .= ' exclude_id:'.$form_arr['exclude_id'];
    }else {
      $shortcode .= ' exclude_id:null';
    }     
      
    if($form_arr['cols']!='') {
      $shortcode .= ' cols:'.$form_arr['cols'];
    }
    
    if($form_arr['height']!='') {
      $shortcode .= ' height:'.$form_arr['height'];
    }else {
      $shortcode .= ' height:null';
    }
    
    if($form_arr['rows']!='') {
      $shortcode .= ' rows:'.$form_arr['rows'];
    }
    
    if($form_arr['text']!='') {
      $shortcode .= ' text:'.$form_arr['text'];
    }else {
      $shortcode .= ' text:null';
    }

    if($form_arr['field']!='') {
      $shortcode .= ' field:'.$form_arr['field'];
    }else {
      $shortcode .= ' field:null';
    }
    
    if($form_arr['width']!='') {
      $shortcode .= ' width:'.$form_arr['width'];
    }else {
      $shortcode .= ' width:null';
    }
    
    if($form_arr['button_rad']!='') {
      $shortcode .= ' button_rad:'.$form_arr['button_rad'];
    }else {
      $shortcode .= ' button_rad:null';
    }
    
    if($form_arr['custom_class']!='') {
      $shortcode .= ' custom_class:'.$form_arr['custom_class'];
    }else {
      $shortcode .= ' custom_class:null';
    }
    
    if($form_arr['shop_text']!='') {
      $shortcode .= ' shop_text:'.$form_arr['shop_text'];
    }else {
      $shortcode .= ' shop_text:null';
    }
    
    if($form_arr['img_height']!='') {
      $shortcode .= ' img_height:'.$form_arr['img_height'];
    }else {
      $shortcode .= ' img_height:null';
    }
        
    if($form_arr['border_color']!='') {
      $shortcode .= ' border_color:'.$form_arr['border_color'];
    }else {
      $shortcode .= ' border_color:null';
    }
    
    if($form_arr['text_color']!='') {
      $shortcode .= ' text_color:'.$form_arr['text_color'];
    }else {
      $shortcode .= ' text_color:null';
    }
    
    if($form_arr['text_color_h']!='') {
      $shortcode .= ' text_color_h:'.$form_arr['text_color_h'];
    }else {
      $shortcode .= ' text_color_h:null';
    }
    
    if($form_arr['bg_color']!='') {
      $shortcode .= ' bg_color:'.$form_arr['bg_color'];
    }else {
      $shortcode .= ' bg_color:null';
    }
    
    if($form_arr['bg_color_h']!='') {
      $shortcode .= ' bg_color_h:'.$form_arr['bg_color_h'];
    }else {
      $shortcode .= ' bg_color_h:null';
    }
    
    if($form_arr['align']!='') {
      $shortcode .= ' align:'.$form_arr['align'];
    }else {
      $shortcode .= ' align:null';
    }
    
    if($form_arr['hide_img']=='on') {
      $shortcode .= ' hide_img:1';
    }else {
      $shortcode .= ' hide_img:null';
    }
    
    if($form_arr['hide_sklad']=='on') {
      $shortcode .= ' hide_sklad:1';
    }else {
      $shortcode .= ' hide_sklad:null';
    }
    
    if($form_arr['item_border']=='on') {
      $shortcode .= ' item_border:1';
    }else {
      $shortcode .= ' item_border:null';
    }
    
    if($form_arr['hide_title']=='on') {
      $shortcode .= ' hide_title:1';
    }else {
      $shortcode .= ' hide_title:null';
    }
    
    if($form_arr['hide_text']=='on') {
      $shortcode .= ' hide_text:1';
    }else {
      $shortcode .= ' hide_text:null';
    }
    
    if($form_arr['hide_shop']=='on') {
      $shortcode .= ' hide_shop:1';
    }else {
      $shortcode .= ' hide_shop:null';
    }
    
    if($form_arr['pagg']=='on') {
      $shortcode .= ' pagg:1';
    }else {
      $shortcode .= ' pagg:null';
    }  
    
    if($form_arr['hide_counter']=='on') {
      $shortcode .= ' hide_counter:1';
    }else {
      $shortcode .= ' hide_counter:null';
    }  
    
    if($form_arr['hide_vars']=='on') {
      $shortcode .= ' hide_vars:1';
    }else {
      $shortcode .= ' hide_vars:null';
    }  
   
    if($form_arr['rating']=='on') {
      $shortcode .= ' rating:1';
    }else {
      $shortcode .= ' rating:null';
    }
    
    if($form_arr['hide_filter']=='on') {
      $shortcode .= ' hide_filter:1';
    }else {
      $shortcode .= ' hide_filter:null';
    }   
    
    return $shortcode;
  }
}