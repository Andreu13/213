<?php 

class Wpshop_Profile
{
	public $view;
	public function __construct() {
		$this->view = new Wpshop_View();
		add_action( 'show_user_profile', array(&$this,'wpshop_show_profile_fields'));
		add_action( 'edit_user_profile', array(&$this,'wpshop_show_profile_fields'));
		add_action( 'personal_options_update', array(&$this,'wpshop_save_profile_fields'));
		add_action( 'edit_user_profile_update', array(&$this,'wpshop_save_profile_fields'));
	}

	public function install() {
		$this->createRoles();		
	}

	public function manageCustomerPage() {

	}

	public function createRoles() {
		add_role('Customer', 'Customer');
		add_role('Merchant', 'Merchant');
		
		$roleEditor = get_role('editor');
		$administrator = get_role('administrator');

		$role = get_role('Customer');
		// Add custome roles
		$role->add_cap( 'read' ); 
		$role = get_role( 'Merchant' );	
		
		foreach($roleEditor->capabilities as $cap=>$value) {
			$role->add_cap( $cap );	
		}
		$role->add_cap( 'Customer' );		
		$user = wp_get_current_user();
		$role = array_shift($user->roles);
		
		if ($role == "Customer"||$role =="administrator"||$role =="Merchant") {
			add_filter('user_contactmethods',array(&$this,'customer_contactmethod'));
			add_action('personal_options', array(&$this,'customerProfilePage'));
			add_action('wp_dashboard_setup',array(&$this,'customerDashboard'));
		}

	}
	
	public static function isCurrentUserCustomer() {
		global $current_user;
		return array_key_exists("Customer",$current_user->caps);
	}

	public function customerDashboard() {
		global $wp_meta_boxes;
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);			
	}

	public function customer_contactmethod( $contactmethods ) {
				
		$contactmethods['phone'] = __('Phone','wp-shop');
		$contactmethods['address'] = __('Address','wp-shop');
		$contactmethods['company_name'] = __('Company name','wp-shop');
		$contactmethods['description'] = __('Description','wp-shop');
		
		unset($contactmethods['jabber']);
		unset($contactmethods['yim']);
		unset($contactmethods['aim']);
		return $contactmethods;
	}

	public function customerProfilePage($user) {?>
		<script type="text/javascript">
			jQuery(function($) {
				$('table').has('textarea[name="description"]').find('tr:first').remove();
				var value = $('[name="description"]').val();
				$('[name="description"]').parent().html("<textarea name='description'>"+value+"</textarea>");
			});
		</script>
	<?php }
	
	public function wpshop_show_profile_fields( $user ) {
		$cr_user = wp_get_current_user();
		$cr_role_ar = $cr_user->caps;
		$role_name = '';
		if (is_array($cr_role_ar)){
			foreach ($cr_role_ar as $key => $val) break;
			$role_name = $key;
		}
		
		$disc_value = get_user_meta($user->ID,'wpshop_user_discount_value',true);
		if ($role_name =="administrator"||$role_name =="Merchant") {?>
		<h3><?php _e('Additional Information:', 'wp-shop');?></h3>
		
		<table class="form-table">
			<tr><th><label for="wpshop_user_discount"><?php _e('Base discount in percents:', 'wp-shop');?></label></th>
			<td><input type="text" name="wpshop_user_discount" id="wpshop_user_discount" value="<?php echo esc_attr($disc_value);?>" class="regular-text" /><br /></td></tr>
		</table>
		<?php }else {
			if(isset($disc_value)&&$disc_value!='') {?>
				<h3><?php _e('Additional Information:', 'wp-shop');?></h3>
				<table class="form-table">
					<tr>
						<th><label for="wpshop_discount"><?php _e('Your discount in percents is:', 'wp-shop');?></label></th>
						<td><?php echo esc_attr($disc_value);?></td>
						<input type="hidden" name="wpshop_user_discount" value="<?php echo esc_attr($disc_value);?>" class="regular-text" />
					</tr>
				</table>
			<?php } 
		}
	} 
	
	public function wpshop_save_profile_fields($user_id) {
		$cr_user = wp_get_current_user();
		$cr_role_ar = $cr_user->caps;
		$role_name = '';
		if (is_array($cr_role_ar)){
			foreach ($cr_role_ar as $key => $val) break;
			$role_name = $key;
		}
		if ($role_name =="administrator"||$role_name =="Merchant") {
			$disc_value = $_POST['wpshop_user_discount'];
			if ($disc_value!='') {
				$disc_value = intval($disc_value);
				if ($disc_value>100) {
					$disc_value = 0;
				}
			}
			update_user_meta( $user_id, 'wpshop_user_discount_value', $disc_value);
		}
	}

}




