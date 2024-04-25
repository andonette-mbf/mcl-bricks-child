<?php



//Disable Gutenberg by template
function ea_disable_gutenberg( $can_edit, $post_type ) {
	
	$disabled_pages = get_field('disable_block_editor_on_these_pages', 'options');	
	if( is_admin() && in_array($_GET['post'], $disabled_pages))
		$can_edit = false;

	return $can_edit;

}
add_filter( 'gutenberg_can_edit_post_type', 'ea_disable_gutenberg', 10, 2 );
add_filter( 'use_block_editor_for_post_type', 'ea_disable_gutenberg', 10, 2 );

//Block Functions Include
include( get_stylesheet_directory() . '/block-functions.php');


/*////Remove default Posts type since no blog
// Remove side menu
add_action( 'admin_menu', 'remove_default_post_type' );
function remove_default_post_type() {
    remove_menu_page( 'edit.php' );
}
// Remove +New post in top Admin Menu Bar
add_action( 'admin_bar_menu', 'remove_default_post_type_menu_bar', 999 );
function remove_default_post_type_menu_bar( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'new-post' );
}
// Remove Quick Draft Dashboard Widget
add_action( 'wp_dashboard_setup', 'remove_draft_widget', 999 );
function remove_draft_widget(){
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}
////End remove post type*/


//Remove editor box from certain templates
function remove_editor() {
    if (isset($_GET['post'])) {
        $id = $_GET['post'];
        $template = get_post_meta($id, '_wp_page_template', true);

        if($template == 'page-templates/front-page.php'){ 
            remove_post_type_support( 'page', 'editor' );
        }
    }
}
add_action('init', 'remove_editor');

//Include social share function
include('social-share-functions.php');



add_action( 'template_redirect', 'grouped_product_redirect_post' );
function grouped_product_redirect_post() {
    global $post;
    if ( is_product()) {
        $product = get_product( $post->ID );
        if( $product->is_type( 'grouped' ) ){
        
            $locations = $product->get_children();
            //var_dump($locations[0]);
			
			if (strpos(get_permalink($locations[0]), 'nottingham') !== false) {
				wp_redirect( get_permalink($locations[0]), 301);
			} else {
				if (strpos(get_permalink($locations[1]), 'nottingham') !== false) {
					wp_redirect( get_permalink($locations[1]), 301);
				} else {
					wp_redirect( get_permalink($locations[0]), 301);
				}
			}
			
            exit;
        }
    }
}

// ADDING A CUSTOM COLUMN TITLE TO ADMIN PRODUCTS LIST
add_filter( 'manage_edit-product_columns', 'custom_product_column',11);
function custom_product_column($columns)
{

	$new_columns = array();

    foreach ( $columns as $key => $name ) {

        $new_columns[ $key ] = $name;

        // add ship-to after order status column
        if ( 'sku' === $key ) {
            $new_columns['product_location'] = __( 'Product Location', 'textdomain' );
            $new_columns['venue_id'] = __( 'Venue ID', 'textdomain' );
            $new_columns['template_id'] = __( 'Template ID', 'textdomain' );
        }
    }

    unset($new_columns['product_tag']);
    unset($new_columns['featured']);

    return $new_columns;

}

//Control width of columns
add_action( 'admin_head', 'style_custom_columns_identify' );

function style_custom_columns_identify() {
    global $pagenow;
    if ( $pagenow == 'edit.php' ) {
        ?>
        <style type="text/css">
          .manage-column.column-product_location {
               width: 80px;
           }
           .manage-column.column-venue_id, .manage-column.column-template_id {
               width: 60px;
           }
         </style>
        <?php
    }
}

// Add data to columns
add_action( 'manage_product_posts_custom_column' , 'custom_product_list_column_content', 10, 2 );
function custom_product_list_column_content( $column, $product_id )
{
    global $post;

    switch ( $column )
    {
        case 'product_location' :
            echo get_post_meta( $product_id, 'product_location', true ); // display the data
            break;
        case 'venue_id' :
            echo get_post_meta( $product_id, 'venue_id', true ); // display the data
            break;
        case 'template_id' :
            echo get_post_meta( $product_id, 'template_id', true ); // display the data
            break;
    }
}

//Make columns sortable
add_filter( 'manage_edit-product_sortable_columns', 'sortable_custom_product_cols' );
function sortable_custom_product_cols( $columns ) {
    $columns['product_location'] = 'product_location';
    $columns['venue_id'] = 'venue_id';
    $columns['template_id'] = 'template_id';
 
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
 
    return $columns;
}

//Allow meta data to be sortable
add_filter( 'woocommerce_shop_order_search_fields', 'location_searchable_fields', 10, 1 );
function location_searchable_fields( $meta_keys ){
    $meta_keys[] = 'product_location';
    $meta_keys[] = 'venue_id';
    $meta_keys[] = 'template_id';
    return $meta_keys;
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );






//Output delegate name field
function delegate_output_name_field() {
	global $product;
	if($product->is_type('booking')){
		?>

            <div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-1" name="delegate[1][name]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-2" name="delegate[2][name]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-3" name="delegate[3][name]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-4" name="delegate[4][name]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-5" name="delegate[5][name]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-6" name="delegate[6][name]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-7" name="delegate[7][name]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-8" name="delegate[8][name]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-9" name="delegate[9][name]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-10" name="delegate[10][name]">
            </div>

		<?php
	}
}
add_action( 'woocommerce_before_add_to_cart_button', 'delegate_output_name_field', 10 );

//Output delegate dob field
function delegate_output_dob_field() {
	global $product;
	if($product->is_type('booking')){
		?>

            <div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-dob-1" name="delegate[1][dob]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-dob-2" name="delegate[2][dob]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-dob-3" name="delegate[3][dob]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-dob-4" name="delegate[4][dob]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-dob-5" name="delegate[5][dob]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-dob-6" name="delegate[6][dob]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-dob-7" name="delegate[7][dob]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-dob-8" name="delegate[8][dob]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-dob-9" name="delegate[9][dob]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-dob-10" name="delegate[10][dob]">
            </div>

		<?php
	}
}
add_action( 'woocommerce_before_add_to_cart_button', 'delegate_output_dob_field', 10 );

//Output delegate dob field
function delegate_output_NI_field() {
	global $product;
	if($product->is_type('booking')){
		?>

            <div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-NI-1" name="delegate[1][NI]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-NI-2" name="delegate[2][NI]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-NI-3" name="delegate[3][NI]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-NI-4" name="delegate[4][NI]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-NI-5" name="delegate[5][NI]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-NI-6" name="delegate[6][NI]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-NI-7" name="delegate[7][NI]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-NI-8" name="delegate[8][NI]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-NI-9" name="delegate[9][NI]">
            </div>
			<div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-NI-10" name="delegate[10][NI]">
            </div>

		<?php
	}
}
add_action( 'woocommerce_before_add_to_cart_button', 'delegate_output_NI_field', 10 );

//Update cart meta if it has delegates
function delegate_add_name_email_text_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
	$delegates = filter_input(INPUT_POST, 'delegate', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

	if ( empty( $delegates ) ) {
		return $cart_item_data;
	}

	//set delegates for cart item
	$cart_item_data['delegates'] = $delegates;

	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'delegate_add_name_email_text_to_cart_item', 10, 3 );

//Display delegates in the cart
function delegate_display_name_email_text_cart( $item_data, $cart_item ) {

	if ( empty( $cart_item['delegates'] ) ) {
		return $item_data;
	}

	//add delegates for array
	$delegates = $cart_item['delegates'];

	//Split out delegates for display
	$i = 0;
	foreach($delegates as $delegate) {
		$i++;

		if(!empty($delegate['name'])){

			$item_data[] = array(
				'key'     => __( 'Delegate '.$i.' Name', 'delegates' ),
				'value'   => wc_clean($delegate['name']),
				'display' => '',
			);

			$item_data[] = array(
				'key'     => __( 'Delegate '.$i.' DOB', 'delegates' ),
				'value'   => wc_clean($delegate['dob']),
				'display' => '',
			);
			
			$item_data[] = array(
				'key'     => __( 'Delegate '.$i.' NI Number', 'delegates' ),
				'value'   => wc_clean($delegate['NI']),
				'display' => '',
			);

		}

	}

	return $item_data;
}

add_filter( 'woocommerce_get_item_data', 'delegate_display_name_email_text_cart', 10, 2 );

//Add delegates to order
function delegate_add_name_email_text_to_order_items( $item, $cart_item_key, $values, $order ) {

	if ( empty( $values['delegates'] ) ) {
		return;
	}

	//set delegates for variable
	$delegates = $values['delegates'];

	//add delegates for array
	$item->add_meta_data( __( 'delegates', 'delegates' ), $delegates );

	//Split out delegates for display
	$i = 0;
	foreach($delegates as $delegate) {
		$i++;

		if(!empty($delegate['name'])){
			$item->add_meta_data( __( 'Delegate '.$i.' Name', 'delegates' ), $delegate['name'] );
			$item->add_meta_data( __( 'Delegate '.$i.' DOB', 'delegates' ), $delegate['dob'] );
			$item->add_meta_data( __( 'Delegate '.$i.' NI Number', 'delegates' ), $delegate['NI'] );
		}
	}

}

add_action( 'woocommerce_checkout_create_order_line_item', 'delegate_add_name_email_text_to_order_items', 10, 4 );

//CITB Number Field
add_action('woocommerce_after_order_notes', 'custom_citb_field');
function custom_citb_field($checkout){
woocommerce_form_field('citb_levy_number', array(
	'type' => 'text',
	'class' => array(
	'my-field-class form-row-wide'
) ,
'label' => __('CITB Levy Number'),
),
$checkout->get_value('citb_levy_number'));
}

//Update the value given in custom field
add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_citb_update_order_meta');
function custom_checkout_citb_update_order_meta($order_id){
	if (!empty($_POST['citb_levy_number'])) {
		update_post_meta($order_id, 'CITB Levy Number',sanitize_text_field($_POST['citb_levy_number']));
	}
}

//Add custom field to the checkout page
add_action('woocommerce_after_order_notes', 'custom_po_number_field');
function custom_po_number_field($checkout){
woocommerce_form_field('po_number', array(
	'type' => 'text',
	'class' => array(
	'my-field-class form-row-wide'
),
'label' => __('PO Number'),
),
$checkout->get_value('po_number'));
}

//Update the value given in custom field
add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');
function custom_checkout_field_update_order_meta($order_id){
	if (!empty($_POST['po_number'])) {
		update_post_meta($order_id, 'PO Number',sanitize_text_field($_POST['po_number']));
	}
}

add_filter( 'woocommerce_order_actions', 'bbloomer_show_thank_you_page_order_admin_actions', 9999, 2 );
 
function bbloomer_show_thank_you_page_order_admin_actions( $actions, $order ) {
   if ( $order->has_status( wc_get_is_paid_statuses() ) ) {
      $actions['view_thankyou'] = 'Display thank you page';
   }
   return $actions;
}
 
add_action( 'woocommerce_order_action_view_thankyou', 'bbloomer_redirect_thank_you_page_order_admin_actions' );
 
function bbloomer_redirect_thank_you_page_order_admin_actions( $order ) {
   $url = $order->get_checkout_order_received_url();
   add_filter( 'redirect_post_location', function() use ( $url ) {
      return $url;
   });
}

add_filter('wpcf7_autop_or_not', '__return_false');



//Show a PPC phone number if the user has come from a paid ad
add_action( 'litespeed_esi_load-phone_no_identify', 'hook_esi' );
function hook_esi( $param ) {
    
    //From original call
    if ($_GET['gclid'] || isset($_COOKIE['google_ppc'])){
        echo get_field('ppc_phone_number', 'options');
    } else {
        echo get_field('main_telephone_number', 'options');
    }

}
function getPhoneNumber() {
    return do_action('litespeed_esi_load-phone_no_identify');
    exit;
}
//Set cookie of Google PPC visitor
add_action( 'init', 'set_local_cookies' );
function set_local_cookies() {

    //Set cookies
    if (strpos($_SERVER['REQUEST_URI'], "gclid")) {
        //setcookie('google_ppc', 'true', time() + (86400 * 28), '/');
		setcookie( 'google_ppc', 'true', time() + 3600, COOKIEPATH, COOKIE_DOMAIN );

    }

}

//Disable KSE Escaping of ACF
add_filter( 'acf/the_field/allow_unsafe_html', function( $allowed, $selector ) {
    return true;
}, 10, 2);
?>