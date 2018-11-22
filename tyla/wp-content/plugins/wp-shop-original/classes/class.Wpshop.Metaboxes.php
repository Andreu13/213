<?php 
/**
 * 
 * @author WP Shop Team
 */
class Wpshop_Metaboxes {
	public function __construct(){
		global $post;
		add_action('add_meta_boxes',  array(&$this,'wpshop_add_custom_box'));
		add_action( 'save_post', array(&$this,'save_wpshop_customboxes'));
	}

  public function wpshop_add_custom_box( $post ) {
    add_meta_box(
      'Wpshop Meta Box', // ID, should be a string.
      __('Product Options','wp-shop'), // Meta Box Title.
      array(&$this,'wpshop_meta_box_callback'), // Your call back function, this is where your form field will go.
      'post', // The post type you want this to show up on, can be post, page, or custom post type.
      'normal', // The placement of your meta box, can be normal or side.
      'core' // The priority in which this will be displayed.
    );
  }

public function wpshop_meta_box_callback($post) {
    wp_nonce_field( 'wpshop_nonce_metadata', 'wpshop_nonce' );    
    $checkboxMeta = get_post_meta( $post->ID );
    
    $view = new Wpshop_View();
    $view->post = $checkboxMeta;
	$view->post_id = $post->ID;
	$view->message = __('Please specify the cost of product!','wp-shop');
    $view->render('wpshop_meta_widget.php');
}


public function save_wpshop_customboxes( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;
        if ( ( isset ( $_POST['wpshop_nonce_metadata'] ) ) && ( ! wp_verify_nonce( $_POST['wpshop_nonce_metadata'], plugin_basename( __FILE__ ) ) ) )
            return;
        if ( ( isset ( $_POST['post_type'] ) ) && ( 'page' == $_POST['post_type'] )  ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }    
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
		//type_of_goods
		if( isset( $_POST['type_of_goods'] ) ) {
            update_post_meta( $post_id, 'type_of_goods', $_POST['type_of_goods'] );
        } 
		
		if( isset( $_POST['type_of_goods'] )&&$_POST['type_of_goods']!='1') {
           
			#1
			//cost_1
			if( isset( $_POST['wpshop_meta_cost_1'])&&$_POST['wpshop_meta_cost_1']!='') {
				update_post_meta( $post_id, 'cost_1', $_POST['wpshop_meta_cost_1'] );
			}else {
				delete_post_meta($post_id, 'cost_1');
			}
			//old_price
			if( isset( $_POST['wpshop_meta_old_price'])&&$_POST['wpshop_meta_old_price']!='') {
				update_post_meta( $post_id, 'old_price', $_POST['wpshop_meta_old_price'] );
			}else {
				delete_post_meta($post_id, 'old_price');
			}
			//sklad_1
			if( isset( $_POST['wpshop_meta_sklad_1'])&&$_POST['wpshop_meta_sklad_1']!='') {
				update_post_meta( $post_id, 'sklad_1', $_POST['wpshop_meta_sklad_1'] );
			}else {
				delete_post_meta($post_id, 'sklad_1');
			}
			//count_1
			if( isset( $_POST['wpshop_meta_count_1'])&&$_POST['wpshop_meta_count_1']!='') {
				update_post_meta( $post_id, 'count_1', $_POST['wpshop_meta_count_1'] );
			}else {
				delete_post_meta($post_id, 'count_1');
			}
			//similar_products
			if( isset( $_POST['wpshop_meta_similar_products'])&&$_POST['wpshop_meta_similar_products']=='on') {
				update_post_meta( $post_id, 'similar_products',1);
			}else {
				delete_post_meta($post_id, 'similar_products');
			}
			//new
			if( isset( $_POST['wpshop_meta_new'])&&$_POST['wpshop_meta_new']=='on') {
				update_post_meta( $post_id, 'new',1);
			}else {
				delete_post_meta($post_id, 'new');
			}
			//part_url
			if( isset( $_POST['wpshop_meta_part_url'])&&$_POST['wpshop_meta_part_url']!='') {
				update_post_meta( $post_id, 'part_url_plugin', $_POST['wpshop_meta_part_url'] );
			}else {
				delete_post_meta($post_id, 'part_url_plugin');
			}
			
			//Thumbnail
			if( isset( $_POST['wpshop_meta_thumbnail'])&&$_POST['wpshop_meta_thumbnail']!='') {
				update_post_meta( $post_id, 'Thumbnail', $_POST['wpshop_meta_thumbnail'] );
			}else {
				delete_post_meta($post_id, 'Thumbnail');
			}
			
			#2
			//wpshop_prod_weight
			if( isset( $_POST['wpshop_meta_wpshop_prod_weight'])&&$_POST['wpshop_meta_wpshop_prod_weight']!='') {
				update_post_meta( $post_id, 'wpshop_prod_weight', $_POST['wpshop_meta_wpshop_prod_weight'] );
			}else {
				delete_post_meta($post_id, 'wpshop_prod_weight');
			}
			
			//wpshop_prod_x
			if( isset( $_POST['wpshop_meta_wpshop_prod_x'])&&$_POST['wpshop_meta_wpshop_prod_x']!='') {
				update_post_meta( $post_id, 'wpshop_prod_x', $_POST['wpshop_meta_wpshop_prod_x'] );
			}else {
				delete_post_meta($post_id, 'wpshop_prod_x');
			}
			
			//wpshop_prod_y
			if( isset( $_POST['wpshop_meta_wpshop_prod_y'])&&$_POST['wpshop_meta_wpshop_prod_y']!='') {
				update_post_meta( $post_id, 'wpshop_prod_y', $_POST['wpshop_meta_wpshop_prod_y'] );
			}else {
				delete_post_meta($post_id, 'wpshop_prod_y');
			}
			
			//wpshop_prod_z
			if( isset( $_POST['wpshop_meta_wpshop_prod_z'])&&$_POST['wpshop_meta_wpshop_prod_z']!='') {
				update_post_meta( $post_id, 'wpshop_prod_z', $_POST['wpshop_meta_wpshop_prod_z'] );
			}else {
				delete_post_meta($post_id, 'wpshop_prod_z');
			}
			
			#3
			//Thumbnail1
			if( isset( $_POST['wpshop_meta_thumbnail1'])&&$_POST['wpshop_meta_thumbnail1']!='') {
				update_post_meta( $post_id, 'Thumbnail1', $_POST['wpshop_meta_thumbnail1'] );
			}else {
				delete_post_meta($post_id, 'Thumbnail1');
			}
			
			//Thumbnail2
			if( isset( $_POST['wpshop_meta_thumbnail2'])&&$_POST['wpshop_meta_thumbnail2']!='') {
				update_post_meta( $post_id, 'Thumbnail2', $_POST['wpshop_meta_thumbnail2'] );
			}else {
				delete_post_meta($post_id, 'Thumbnail2');
			}
			
			//Thumbnail3
			if( isset( $_POST['wpshop_meta_thumbnail3'])&&$_POST['wpshop_meta_thumbnail3']!='') {
				update_post_meta( $post_id, 'Thumbnail3', $_POST['wpshop_meta_thumbnail3'] );
			}else {
				delete_post_meta($post_id, 'Thumbnail3');
			}
			
			//Thumbnail4
			if( isset( $_POST['wpshop_meta_thumbnail4'])&&$_POST['wpshop_meta_thumbnail4']!='') {
				update_post_meta( $post_id, 'Thumbnail4', $_POST['wpshop_meta_thumbnail4'] );
			}else {
				delete_post_meta($post_id, 'Thumbnail4');
			}
			
			//Thumbnail5
			if( isset( $_POST['wpshop_meta_thumbnail5'])&&$_POST['wpshop_meta_thumbnail5']!='') {
				update_post_meta( $post_id, 'Thumbnail5', $_POST['wpshop_meta_thumbnail5'] );
			}else {
				delete_post_meta($post_id, 'Thumbnail5');
			}
			
			//Thumbnail6
			if( isset( $_POST['wpshop_meta_thumbnail6'])&&$_POST['wpshop_meta_thumbnail6']!='') {
				update_post_meta( $post_id, 'Thumbnail6', $_POST['wpshop_meta_thumbnail6'] );
			}else {
				delete_post_meta($post_id, 'Thumbnail6');
			}
			
			//Thumbnail7
			if( isset( $_POST['wpshop_meta_thumbnail7'])&&$_POST['wpshop_meta_thumbnail7']!='') {
				update_post_meta( $post_id, 'Thumbnail7', $_POST['wpshop_meta_thumbnail7'] );
			}else {
				delete_post_meta($post_id, 'Thumbnail7');
			}
			
			//pic
			if( isset( $_POST['wpshop_meta_pic'])&&$_POST['wpshop_meta_pic']!='') {
				$htmlent_editor_val=$_POST['wpshop_meta_pic'];
				update_post_meta($post_id, 'pic', $htmlent_editor_val );
			}else {
				delete_post_meta($post_id, 'pic');
			}
			
			#4
			//yml_pic
			if( isset( $_POST['wpshop_meta_yml_pic'])&&$_POST['wpshop_meta_yml_pic']!='') {
				update_post_meta( $post_id, 'yml_pic', $_POST['wpshop_meta_yml_pic'] );
			}else {
				delete_post_meta($post_id, 'yml_pic');
			}
			
			//noyaml
			if( isset( $_POST['wpshop_meta_noyaml'])&&$_POST['wpshop_meta_noyaml']=='on') {
				update_post_meta( $post_id, 'noyaml',1);
			}else {
				delete_post_meta($post_id, 'noyaml');
			}
			
			//short_text
			if( isset( $_POST['wpshop_meta_short_text'])&&$_POST['wpshop_meta_short_text']!='') {
				update_post_meta( $post_id, 'short_text', $_POST['wpshop_meta_short_text'] );
			}else {
				delete_post_meta($post_id, 'short_text');
			}
			
			#5
			//name_1
			if( isset( $_POST['wpshop_meta_name_1'])&&$_POST['wpshop_meta_name_1']!='') {
				update_post_meta( $post_id, 'name_1', $_POST['wpshop_meta_name_1'] );
			}else {
				delete_post_meta($post_id, 'name_1');
			}
			//name_2
			if( isset( $_POST['wpshop_meta_name_2'])&&$_POST['wpshop_meta_name_2']!='') {
				update_post_meta( $post_id, 'name_2', $_POST['wpshop_meta_name_2'] );
			}else {
				delete_post_meta($post_id, 'name_2');
			}
			//cost_2
			if( isset( $_POST['wpshop_meta_cost_2'])&&$_POST['wpshop_meta_cost_2']!='') {
				update_post_meta( $post_id, 'cost_2', $_POST['wpshop_meta_cost_2'] );
			}else {
				delete_post_meta($post_id, 'cost_2');
			}
			//sklad_2
			if( isset( $_POST['wpshop_meta_sklad_2'])&&$_POST['wpshop_meta_sklad_2']!='') {
				update_post_meta( $post_id, 'sklad_2', $_POST['wpshop_meta_sklad_2'] );
			}else {
				delete_post_meta($post_id, 'sklad_2');
			}
			//count_2
			if( isset( $_POST['wpshop_meta_count_2'])&&$_POST['wpshop_meta_count_2']!='') {
				update_post_meta( $post_id, 'count_2', $_POST['wpshop_meta_count_2'] );
			}else {
				delete_post_meta($post_id, 'count_2');
			}
			//name_3
			if( isset( $_POST['wpshop_meta_name_3'])&&$_POST['wpshop_meta_name_3']!='') {
				update_post_meta( $post_id, 'name_3', $_POST['wpshop_meta_name_3'] );
			}else {
				delete_post_meta($post_id, 'name_3');
			}
			//cost_3
			if( isset( $_POST['wpshop_meta_cost_3'])&&$_POST['wpshop_meta_cost_3']!='') {
				update_post_meta( $post_id, 'cost_3', $_POST['wpshop_meta_cost_3'] );
			}else {
				delete_post_meta($post_id, 'cost_3');
			}
			//sklad_3
			if( isset( $_POST['wpshop_meta_sklad_3'])&&$_POST['wpshop_meta_sklad_3']!='') {
				update_post_meta( $post_id, 'sklad_3', $_POST['wpshop_meta_sklad_3'] );
			}else {
				delete_post_meta($post_id, 'sklad_3');
			}
			//count_3
			if( isset( $_POST['wpshop_meta_count_3'])&&$_POST['wpshop_meta_count_3']!='') {
				update_post_meta( $post_id, 'count_3', $_POST['wpshop_meta_count_3'] );
			}else {
				delete_post_meta($post_id, 'count_3');
			}
			//name_4
			if( isset( $_POST['wpshop_meta_name_4'])&&$_POST['wpshop_meta_name_4']!='') {
				update_post_meta( $post_id, 'name_4', $_POST['wpshop_meta_name_4'] );
			}else {
				delete_post_meta($post_id, 'name_4');
			}
			//cost_4
			if( isset( $_POST['wpshop_meta_cost_4'])&&$_POST['wpshop_meta_cost_4']!='') {
				update_post_meta( $post_id, 'cost_4', $_POST['wpshop_meta_cost_4'] );
			}else {
				delete_post_meta($post_id, 'cost_4');
			}
			//sklad_4
			if( isset( $_POST['wpshop_meta_sklad_4'])&&$_POST['wpshop_meta_sklad_4']!='') {
				update_post_meta( $post_id, 'sklad_4', $_POST['wpshop_meta_sklad_4'] );
			}else {
				delete_post_meta($post_id, 'sklad_4');
			}
			//count_4
			if( isset( $_POST['wpshop_meta_count_4'])&&$_POST['wpshop_meta_count_4']!='') {
				update_post_meta( $post_id, 'count_4', $_POST['wpshop_meta_count_4'] );
			}else {
				delete_post_meta($post_id, 'count_4');
			}
			
			#6
			//wpshop_prop_1
			if( isset( $_POST['wpshop_meta_wpshop_prop_1'])&&$_POST['wpshop_meta_wpshop_prop_1']!='') {
				update_post_meta( $post_id, 'wpshop_prop_1', $_POST['wpshop_meta_wpshop_prop_1'] );
			}else {
				delete_post_meta($post_id, 'wpshop_prop_1');
			}
			//wpshop_prop_2
			if( isset( $_POST['wpshop_meta_wpshop_prop_2'])&&$_POST['wpshop_meta_wpshop_prop_2']!='') {
				update_post_meta( $post_id, 'wpshop_prop_2', $_POST['wpshop_meta_wpshop_prop_2'] );
			}else {
				delete_post_meta($post_id, 'wpshop_prop_2');
			}
			//wpshop_prop_3
			if( isset( $_POST['wpshop_meta_wpshop_prop_3'])&&$_POST['wpshop_meta_wpshop_prop_3']!='') {
				update_post_meta( $post_id, 'wpshop_prop_3', $_POST['wpshop_meta_wpshop_prop_3'] );
			}else {
				delete_post_meta($post_id, 'wpshop_prop_3');
			}
			
			#7
			//digital_link
			if( isset( $_POST['wpshop_meta_digital_link'])&&$_POST['wpshop_meta_digital_link']!='') {
				update_post_meta( $post_id, 'digital_link', $_POST['wpshop_meta_digital_link'] );
			}else {
				delete_post_meta($post_id, 'digital_link');
			}
			//digital_count
			if( isset( $_POST['wpshop_meta_digital_count'])&&$_POST['wpshop_meta_digital_count']!='') {
				update_post_meta( $post_id, 'digital_count', $_POST['wpshop_meta_digital_count'] );
			}else {
				delete_post_meta($post_id, 'digital_count');
			}
			//digital_live
			if( isset( $_POST['wpshop_meta_digital_live'])&&$_POST['wpshop_meta_digital_live']!='') {
				update_post_meta( $post_id, 'digital_live', $_POST['wpshop_meta_digital_live'] );
			}else {
				delete_post_meta($post_id, 'digital_live');
			}
      //external_digital
      if( isset( $_POST['wpshop_meta_external_digital'])&&$_POST['wpshop_meta_external_digital']=='on') {
				update_post_meta( $post_id, 'external_digital', 1 );
			}else {
				delete_post_meta($post_id, 'external_digital');
			}
		
		}
	}
}