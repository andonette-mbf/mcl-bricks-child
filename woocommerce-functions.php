<?php

//Functions Added By Andonette 
//woocommerce theme support
function mytheme_add_woocommerce_support () {
    add_theme_support ( 'woocommerce' );
    }

add_action ( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

//Include JavaScript 
function enqueue_custom_scripts () {
    // Enqueue the custom JS file
    wp_enqueue_script (
        'custom-js', // Handle for the script
        get_stylesheet_directory_uri () . '/woocommerce-scripts.js', // Path to the JS file
        array( 'jquery' ), // Dependencies (if any)
        '1.0.0', // Version number
        true // Load in the footer
    );
    }
add_action ( 'wp_enqueue_scripts', 'enqueue_custom_scripts' );
// Include the Composer autoload file
require_once get_stylesheet_directory () . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

function initialize_woocommerce_client () {
    $woocommerce = new Client(
        site_url (), // Your store URL
        WC_BOOKINGS_CONSUMER_KEY,
        WC_BOOKINGS_CONSUMER_SECRET,
        [ 
            'wp_api'  => true, // Enable the WP REST API integration
            'version' => 'wc/v3', // WooCommerce WP REST API version
        ],
    );

    return $woocommerce;
    }
function add_woocommerce_capabilities () {
    $role = get_role ( 'shop_manager' ); // Change to 'administrator' if needed
    if ( $role ) {
        $role->add_cap ( 'manage_woocommerce' );
        $role->add_cap ( 'view_woocommerce_reports' );
        }
    }
add_action ( 'admin_init', 'add_woocommerce_capabilities' );
// Custom Woocommerce Functions
// Remove unused tabs
add_filter ( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
function wcs_woo_remove_reviews_tab ( $tabs ) {
    unset ( $tabs[ 'description' ] );
    return $tabs;
    }

// Get Grouped product parent using WooCommerce functions and redirect
function parent_grouped_id ( $post_id = 0 ) {
    if ( ! $post_id ) {
        global $post;
        $post_id = $post->get_id ();
        }

    $grouped_parents = wc_get_products ( [ 
        'type'   => 'grouped',
        'status' => 'publish',
        'limit'  => -1
    ] );

    foreach ( $grouped_parents as $parent ) {
        $children = $parent->get_children ();
        if ( in_array ( $post_id, $children ) ) {
            return $parent->get_id ();
            }
        }

    return 0;
    }

// Redirect to the first bookable child if on a grouped product page
function redirect_grouped_to_first_bookable_child () {
    if ( is_product () ) {
        global $post;

        $product = wc_get_product ( $post->ID );

        if ( $product && $product->is_type ( 'grouped' ) ) {
            $children = $product->get_children ();

            foreach ( $children as $child_id ) {
                $child_product = wc_get_product ( $child_id );

                if ( $child_product && $child_product->is_type ( 'booking' ) ) {
                    wp_safe_redirect ( get_permalink ( $child_id ) );
                    exit;
                    }
                }
            }
        }
    }
add_action ( 'template_redirect', 'redirect_grouped_to_first_bookable_child' );

function delegate_output_fields () {
    global $product;
    if ( $product->is_type ( 'booking' ) ) {
        for ( $i = 1; $i <= 6; $i++ ) {
            echo '<div class="delegate-name-email-field hidden">';
            $fields = [ 'name', 'level_select', 'number', 'dob', 'phone', 'email' ];
            foreach ( $fields as $field ) {
                echo '<input type="hidden" id="delegate-' . $field . '-' . $i . '" name="delegate[' . $i . '][' . $field . ']">';
                }
            echo '</div>';
            }
        }
    }
add_action ( 'woocommerce_before_add_to_cart_button', 'delegate_output_fields', 10 );


// Update cart meta if it has delegates
add_filter ( 'woocommerce_add_cart_item_data', 'delegate_add_name_email_text_to_cart_item', 10, 3 );

function delegate_add_name_email_text_to_cart_item ( $cart_item_data, $product_id, $variation_id ) {
    $delegates = filter_input ( INPUT_POST, 'delegate', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

    if ( ! empty ( $delegates ) ) {
        $cart_item_data[ 'delegates' ] = $delegates;
        }

    return $cart_item_data;
    }

// Display delegates in the cart
add_filter ( 'woocommerce_get_item_data', 'delegate_display_name_email_text_cart', 10, 2 );

function delegate_display_name_email_text_cart ( $item_data, $cart_item ) {
    if ( ! empty ( $cart_item[ 'delegates' ] ) ) {
        $delegates = $cart_item[ 'delegates' ];
        foreach ( $delegates as $i => $delegate ) {
            $i = 1;
            if ( ! empty ( $delegate[ 'name' ] ) ) {
                $item_data[] = array(
                    'key'     => __ ( 'Delegate ' . $i . ' Name', 'delegates' ),
                    'value'   => wc_clean ( $delegate[ 'name' ] ),
                    'display' => '',
                );
                $item_data[] = array(
                    'key'     => __ ( 'Delegate ' . $i . ' Select', 'delegates' ),
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
                    'key'     => __ ( 'Delegate ' . $i . ' Phone', 'delegates' ),
                    'value'   => wc_clean ( $delegate[ 'phone' ] ),
                    'display' => '',
                );
                $item_data[] = array(
                    'key'     => __ ( 'Delegate ' . $i . ' Email', 'delegates' ),
                    'value'   => wc_clean ( $delegate[ 'email' ] ),
                    'display' => '',
                );
                }
            $i++;
            }
        }
    return $item_data;
    }

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
    $i = 1;
    foreach ( $delegates as $delegate ) {
        if ( ! empty ( $delegate[ 'name' ] ) ) {
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Name', 'delegates' ), $delegate[ 'name' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Level Select', 'delegates' ), $delegate[ 'level_select' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Number', 'delegates' ), $delegate[ 'number' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' DOB', 'delegates' ), $delegate[ 'dob' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Phone', 'delegates' ), $delegate[ 'phone' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Email', 'delegates' ), $delegate[ 'email' ] );
            }
        $i++;
        }

    }
add_action ( 'woocommerce_checkout_create_order_line_item', 'delegate_add_name_email_text_to_order_items', 10, 6 );



function enqueue_woocommerce_scripts () {
    if ( is_product () ) {
        wp_enqueue_script ( 'wc-add-to-cart' );
        wp_enqueue_script ( 'wc-single-product' );
        }
    }
add_action ( 'wp_enqueue_scripts', 'enqueue_woocommerce_scripts' );
