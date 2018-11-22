<?php 
/**
 * 
 * @author WP Shop Team
 */
class Wpshop_Requests {

    protected static $instance = NULL;
    public function __construct() {}

    public static function get_instance() {
        NULL === self::$instance and self::$instance = new self;
        return self::$instance;
    }    

    public function setup() {
      add_action('init', array($this, 'rewrite_rules'));
      add_filter('query_vars', array($this, 'query_vars'), 10, 1);
      add_action('parse_request', array($this, 'parse_request'), 10, 1);
      add_action('template_redirect', array($this,'wpshop_ext_redirect'));
    }

    public function rewrite_rules(){
        $ek_result = get_option("wpshop.payments.ek");
        $ya_result = get_option("wpshop.payments.yandex_kassa");
        $sofort_result = get_option("wpshop.payments.sofort");
        add_rewrite_rule('wpshop/wallet_'.$ek_result["passfrase"].'/?$', 'index.php?wpshop_wallet_'.$ek_result["passfrase"].'=true', 'top');
        add_rewrite_rule('wpshop/yandex_'.$ya_result["passfrase"].'/?$', 'index.php?wpshop_kassa_'.$ya_result["passfrase"].'=true', 'top');
        add_rewrite_rule('wpshop/sofort_'.$sofort_result["passfrase"].'/?$', 'index.php?wpshop_sofort_'.$sofort_result["passfrase"].'=true', 'top');
        add_rewrite_rule('wpshop/interkassa_result/?$', 'index.php?wpshop_interkassa=true', 'top');
        add_rewrite_rule('wpshop/tinkoff_xMcxNv/?$', 'index.php?wpshop_tinkoff=true', 'top');
        add_rewrite_rule('wpshop/primearea_result/?$', 'index.php?wpshop_primearea=true', 'top');
    }

    public function flush_rules(){
        $this->rewrite_rules();
        flush_rewrite_rules();
    }

    public function query_vars($vars){
        $ek_result = get_option("wpshop.payments.ek");
        $ya_result = get_option("wpshop.payments.yandex_kassa");
        $sofort_result = get_option("wpshop.payments.sofort");
        $vars[] = 'wpshop_wallet_'.$ek_result["passfrase"];
        $vars[] = 'wpshop_kassa_'.$ya_result["passfrase"];
        $vars[] = 'wpshop_sofort_'.$sofort_result["passfrase"];
        $vars[] = 'wpshop_interkassa';
        $vars[] = 'wpshop_tinkoff';
        $vars[] = 'wpshop_primearea';
        return $vars;
    }

    public function parse_request($wp){
      $ek_result = get_option("wpshop.payments.ek");
      $ya_result = get_option("wpshop.payments.yandex_kassa");
      $sofort_result = get_option("wpshop.payments.sofort");
      if ( array_key_exists( 'wpshop_wallet_'.$ek_result["passfrase"], $wp->query_vars ) ){
        include WPSHOP_DIR . '/views/wallet_result.php';
        exit();
      }
      if ( array_key_exists( 'wpshop_kassa_'.$ya_result["passfrase"], $wp->query_vars ) ){
        include WPSHOP_DIR . '/views/yandex_result.php';
        exit();
      }
      if ( array_key_exists( 'wpshop_sofort_'.$sofort_result["passfrase"], $wp->query_vars ) ){
        include WPSHOP_DIR . '/views/sofort_result.php';
        exit();
      }
      if ( array_key_exists( 'wpshop_interkassa', $wp->query_vars ) ){
        include WPSHOP_DIR . '/views/interkassa_result.php';
        exit();
      }
      if ( array_key_exists( 'wpshop_tinkoff', $wp->query_vars ) ){
        require_once WPSHOP_DIR . '/views/tinkoff_result.php';
        exit();
      }
      if ( array_key_exists( 'wpshop_primearea', $wp->query_vars ) ){
        require_once WPSHOP_DIR . '/views/primearea_result.php';
        exit();
      }
  }
  
  public function wpshop_ext_redirect() {
    $url=$_SERVER['REQUEST_URI'];
    if(($url[0]!='/') and ($url[strlen($url)]!='/'))return;
    $uarr=explode("/",$url);
    
    if(get_option("wp-shop_relink") === false||get_option("wp-shop_relink") ==''){ 
      update_option("wp-shop_relink",dechex(rand(0x1000,0xFFFFFF)));
    }
    
    $relink = get_option("wp-shop_relink");
    
    if($uarr[1]==$relink){
      $link = str_replace("/".$uarr[1]."/",'',$url);
      $link = str_replace("==/",'==',$link);
      wp_redirect(html_entity_decode(base64_decode($link)), 302);
      exit;
    }
  }
}