<?php 
/*
 Plugin Name: WP-Shop
 Plugin URI: http://www.wp-shop.ru
 Description: Интернет-магазин для WordPress.
 Author: www.wp-shop.ru
 Version: 3.9.2
 Author URI: http://www.wp-shop.ru
 */


//error_reporting(E_ALL);
//ini_set("display_errors", 1);


if (!session_id()) { session_start(); }

define( 'WPSHOP_DIR', dirname(realpath(__FILE__)));
define( 'WPSHOP_URL', plugins_url("",__FILE__) );
define( 'WPSHOP_CLASSES_DIR' , WPSHOP_DIR ."/classes");
define( 'WPSHOP_VIEWS_DIR' , WPSHOP_DIR ."/views");
define( 'WPSHOP_DATA_DIR', WPSHOP_DIR ."/data");
define( 'WPSHOP_THEME_TEMPLATE_DIR', get_template_directory() . "/wpshop" );

define( 'CURR_BEFORE',	'&nbsp;' );
define( 'CART_ID',		'wpshop_cart' );
define( 'MINICART_ID',	'wpshop_minicart' );
define( 'CART_TAG',		'[cart]' );
define( 'MINICART_TAG',	'[minicart]' );
define( 'SPL', '}{' );
require_once(dirname(__FILE__) . '/ajax.php');

function wpshopAutoload($ClassName){	
	$class = array();
	preg_match("/Wpshop_(\S+)/",$ClassName,$class);
	if($class){
		$file = WPSHOP_CLASSES_DIR."/class.Wpshop.{$class[1]}.php";
		if (file_exists($file))
		{
			require_once($file);
		}
	}
}

spl_autoload_register('wpshopAutoload');

$WpShopBoot = new Wpshop_Boot();
new Wpshop_Shortcodes();

function wpshop_plugin_activate() {
	$installer = new Wpshop_Installer();
	$requests = new Wpshop_Requests();
	$requests->flush_rules();
}
register_activation_hook( __FILE__, 'wpshop_plugin_activate' );
function wpshop_init_lang(){
	load_plugin_textdomain('wp-shop', false, dirname(plugin_basename(__FILE__)).'/languages');
}

function wpshop_init_signon(){
	if (isset($_POST['wpshop_auth_usr_btn'])){
		$wpshop_user_name = htmlspecialchars(stripslashes($_POST['wpshop_user_name']));
		$wpshop_user_password = htmlspecialchars(stripslashes($_POST['wpshop_user_password']));
		$creds = array();
		$creds['user_login'] = $wpshop_user_name;
		$creds['user_password'] = $wpshop_user_password;
		$creds['remember'] = false;
		$secure_cookie = 0;
		//$user = wp_signon($creds, true);
		$user = wp_authenticate($wpshop_user_name, $wpshop_user_password);
		if ( is_wp_error($user) ){
			//echo $user->get_error_message();
		}elseif($_GET['page_id']){
			wp_set_auth_cookie($user->ID, $creds['remember'], $secure_cookie);
			do_action('wp_login', $user->user_login, $user);
			$full_path=get_option("wpshop.cartpage",'{sitename}/cart');
			header("Location: ".$full_path."&step=3");
			exit;
		}else{
			wp_set_auth_cookie($user->ID, $creds['remember'], $secure_cookie);
			do_action('wp_login', $user->user_login, $user);
			header("Location: ?step=3");
			exit;
		}
	}
	
if ( is_user_logged_in() ) {
	global $current_user;
	
	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	
	if ( $user_role =='Customer'|| current_user_can( 'manage_options' ) || $user_role =='Merchant') {
	if ( in_array('Customer', $current_user->roles)) {
		function remove_profile_submenu() {
			remove_menu_page('index.php');
		}
		add_action('admin_head', 'remove_profile_submenu');
		
		function profile_redirect() {
			$result = stripos($_SERVER['REQUEST_URI'], 'index.php');
			if ($result!==false) {
				wp_redirect(get_option('siteurl') . '/wp-admin/profile.php');
			}
		}
	 	add_action('admin_menu', 'profile_redirect');
	}
	} else { 
		function remove_menus_shop(){
			remove_menu_page('wpshop_main');      
		}
		add_action( 'admin_menu', 'remove_menus_shop' );
	}
}

function ipstenu_admin_bar_add() {
	global $wp_admin_bar;
	global $current_user;
	
	if (  in_array('Customer', $current_user->roles )) {
		if( !is_admin()){
			$wp_admin_bar->add_menu(array('parent'=>false,'id'=>'site-name', 'href'=>get_home_url().'/wp-admin/profile.php'));
			$wp_admin_bar->remove_menu('dashboard');
		}
	}
}

add_action( 'wp_before_admin_bar_render', 'ipstenu_admin_bar_add' );
	
}

add_action( 'init', 'wpshop_init_signon', 7);

add_action(
    'plugins_loaded', 
    array(Wpshop_Requests::get_instance(), 'setup')
);
