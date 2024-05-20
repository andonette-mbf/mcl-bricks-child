<?php
// All your custom woocommerce theme functionality goes in here

//* Enqueue scripts and styles
add_action ( 'wp_enqueue_scripts', 'disable_woocommerce_loading_css_js' );

function disable_woocommerce_loading_css_js () {

    // Check if WooCommerce plugin is active
    if ( function_exists ( 'is_woocommerce' ) ) {

        // Check if it's any of WooCommerce page
        if ( ! is_woocommerce () && ! is_cart () && ! is_checkout () && ! is_account_page () ) {

            ## Dequeue WooCommerce styles
            wp_dequeue_style ( 'woocommerce-layout' );
            wp_dequeue_style ( 'woocommerce-general' );
            wp_dequeue_style ( 'woocommerce-smallscreen' );

            ## Dequeue WooCommerce scripts
            wp_dequeue_script ( 'wc-cart-fragments' );
            wp_dequeue_script ( 'woocommerce' );
            wp_dequeue_script ( 'wc-add-to-cart' );

            wp_deregister_script ( 'js-cookie' );
            wp_dequeue_script ( 'js-cookie' );

            }
        }
    }

//Remove Woocommerce Select2 - Woocommerce 3.2.1+
function woo_dequeue_select2 () {
    if ( class_exists ( 'woocommerce' ) ) {
        wp_dequeue_style ( 'select2' );
        wp_deregister_style ( 'select2' );

        wp_dequeue_script ( 'selectWoo' );
        wp_deregister_script ( 'selectWoo' );
        }
    }
add_action ( 'wp_enqueue_scripts', 'woo_dequeue_select2', 100 );


// Remove breadcrumbs from shop & categories
add_filter ( 'woocommerce_before_main_content', 'remove_breadcrumbs' );
function remove_breadcrumbs () {
    remove_action ( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
    }

//Remove Category: From product page
remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


//Remove unused tabs
add_filter ( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
function wcs_woo_remove_reviews_tab ( $tabs ) {
    unset ( $tabs[ 'reviews' ] );
    unset ( $tabs[ 'description' ] );
    unset ( $tabs[ 'additional_information' ] );
    return $tabs;
    }

//Remove product title
remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

//Remove product standard price
remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

//Remove product short desc
remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

//Remove product rating
remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );


// Get Grouped product parent
function parent_grouped_id ( $post_id = 0 ) {
    global $post, $wpdb;

    if ( $post_id == 0 )
        $post_id = $post->ID;

    $parent_grouped_id = 0;

    // The SQL query
    $results = $wpdb->get_results ( "
        SELECT pm.meta_value as child_ids, pm.post_id
        FROM {$wpdb->prefix}postmeta as pm
        INNER JOIN {$wpdb->prefix}posts as p ON pm.post_id = p.ID
        INNER JOIN {$wpdb->prefix}term_relationships as tr ON pm.post_id = tr.object_id
        INNER JOIN {$wpdb->prefix}terms as t ON tr.term_taxonomy_id = t.term_id
        WHERE p.post_type LIKE 'product'
        AND p.post_status LIKE 'publish'
        AND t.slug LIKE 'grouped'
        AND pm.meta_key LIKE '_children'
        ORDER BY p.ID
    " );

    // Retreiving the parent grouped product ID
    foreach ( $results as $result ) {
        foreach ( maybe_unserialize ( $result->child_ids ) as $child_id )
            if ( $child_id == $post_id ) {
                $parent_grouped_id = $result->post_id;
                break;
                }
        if ( $parent_grouped_id != 0 ) break;
        }
    return $parent_grouped_id;
    }

//Redirect training products to cart page on add to cart
function training_custom_add_to_cart_redirect ( $url ) {

    if ( ! isset ( $_REQUEST[ 'add-to-cart' ] ) || ! is_numeric ( $_REQUEST[ 'add-to-cart' ] ) ) {
        return $url;
        }

    $product_id = apply_filters ( 'woocommerce_add_to_cart_product_id', absint ( $_REQUEST[ 'add-to-cart' ] ) );

    // Only redirect products that have the 't-shirts' category
    if ( has_term ( 'training-courses', 'product_cat', $product_id ) ) {
        $url = get_permalink ( 123 );
        }

    return $url;
    }
add_filter ( 'woocommerce_add_to_cart_redirect', 'training_custom_add_to_cart_redirect' );


add_action ( 'template_redirect', 'grouped_product_redirect_post' );
function grouped_product_redirect_post () {
    global $post;
    if ( is_product () ) {
        $product = get_product ( $post->ID );
        if ( $product->is_type ( 'grouped' ) ) {

            $locations = $product->get_children ();
            //var_dump($locations[0]);

            if ( strpos ( get_permalink ( $locations[ 0 ] ), 'nottingham' ) !== false ) {
                wp_redirect ( get_permalink ( $locations[ 0 ] ), 301 );
                } else {
                if ( strpos ( get_permalink ( $locations[ 1 ] ), 'nottingham' ) !== false ) {
                    wp_redirect ( get_permalink ( $locations[ 1 ] ), 301 );
                    } else {
                    wp_redirect ( get_permalink ( $locations[ 0 ] ), 301 );
                    }
                }

            exit;
            }
        }
    }



function delegate_output_fields () {
    global $product;

    if ( $product->is_type ( 'booking' ) ) {
        for ( $i = 1; $i <= 10; $i++ ) {
            ?>
            <div class="delegate-name-email-field hidden">
                <input type="hidden" id="delegate-name-<?php echo $i; ?>" name="delegate[<?php echo $i; ?>][name]">
                <input type="hidden" id="delegate-level-select-<?php echo $i; ?>" name="delegate[<?php echo $i; ?>][level_select]">
                <input type="hidden" id="delegate-number-<?php echo $i; ?>" name="delegate[<?php echo $i; ?>][number]">
                <input type="hidden" id="delegate-dob-<?php echo $i; ?>" name="delegate[<?php echo $i; ?>][dob]">

            </div>
            <?php
            }
        }
    }
add_action ( 'woocommerce_before_add_to_cart_button', 'delegate_output_fields', 10 );


//Update cart meta if it has delegates
function delegate_add_name_email_text_to_cart_item ( $cart_item_data, $product_id, $variation_id ) {
    $delegates = filter_input ( INPUT_POST, 'delegate', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

    if ( empty ( $delegates ) ) {
        return $cart_item_data;
        }

    //set delegates for cart item
    $cart_item_data[ 'delegates' ] = $delegates;

    return $cart_item_data;
    }
add_filter ( 'woocommerce_add_cart_item_data', 'delegate_add_name_email_text_to_cart_item', 10, 3 );

//Display delegates in the cart
function delegate_display_name_email_text_cart ( $item_data, $cart_item ) {

    if ( empty ( $cart_item[ 'delegates' ] ) ) {
        return $item_data;
        }

    //add delegates for array
    $delegates = $cart_item[ 'delegates' ];

    //Split out delegates for display
    $i = 0;
    foreach ( $delegates as $delegate ) {
        $i++;

        if ( ! empty ( $delegate[ 'name' ] ) ) {

            $item_data[] = array(
                'key'     => __ ( 'Delegate ' . $i . ' Name', 'delegates' ),
                'value'   => wc_clean ( $delegate[ 'name' ] ),
                'display' => '',
            );

            $item_data[] = array(
                'key'     => __ ( 'Delegate ' . $i . '  Select', 'delegates' ),
                'value'   => wc_clean ( $delegate[ 'level_select' ] ),
                'display' => '',
            );

            $item_data[] = array(
                'key'     => __ ( 'Delegate ' . $i . ' Number', 'delegates' ),
                'value'   => wc_clean ( $delegate[ 'number' ] ),
                'display' => '',
            );

            $item_data[] = array(
                'key'     => __ ( 'Delegate ' . $i . ' DOB', 'delegates' ),
                'value'   => wc_clean ( $delegate[ 'dob' ] ),
                'display' => '',
            );

            $item_data[] = array(
                'key'     => __ ( 'Delegate ' . $i . ' NI Number', 'delegates' ),
                'value'   => wc_clean ( $delegate[ 'NI' ] ),
                'display' => '',
            );

            }
        }
    return $item_data;
    }

add_filter ( 'woocommerce_get_item_data', 'delegate_display_name_email_text_cart', 10, 2 );

//Add delegates to order
function delegate_add_name_email_text_to_order_items ( $item, $cart_item_key, $values, $order ) {

    if ( empty ( $values[ 'delegates' ] ) ) {
        return;
        }

    //set delegates for variable
    $delegates = $values[ 'delegates' ];
    echo $delegates;

    //add delegates for array
    $item->add_meta_data ( __ ( 'delegates', 'delegates', 'delegates', 'delegates', 'delegates' ), $delegates );

    //Split out delegates for display
    $i = 0;
    foreach ( $delegates as $delegate ) {
        $i++;

        if ( ! empty ( $delegate[ 'name' ] ) ) {
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Name', 'delegates' ), $delegate[ 'name' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Level Select', 'delegates' ), $delegate[ 'level_select' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Number', 'delegates' ), $delegate[ 'number' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' DOB', 'delegates' ), $delegate[ 'dob' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' NI Number', 'delegates' ), $delegate[ 'NI' ] );
            }
        }

    }
add_action ( 'woocommerce_checkout_create_order_line_item', 'delegate_add_name_email_text_to_order_items', 10, 6 );


?>