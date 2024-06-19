<?php
// All your custom woocommerce theme functionality goes in here

//* Enqueue scripts and styles
add_action ( 'wp_enqueue_scripts', 'disable_woocommerce_loading_css_js' );

function disable_woocommerce_loading_css_js () {
    // Check if WooCommerce plugin is active and if it's not a WooCommerce-related page
    if ( function_exists ( 'is_woocommerce' ) && ! is_woocommerce () && ! is_cart () && ! is_checkout () && ! is_account_page () ) {
        // Dequeue WooCommerce styles
        wp_dequeue_style ( 'woocommerce-layout' );
        wp_dequeue_style ( 'woocommerce-general' );
        wp_dequeue_style ( 'woocommerce-smallscreen' );

        // Dequeue WooCommerce scripts
        wp_dequeue_script ( 'wc-cart-fragments' );
        wp_dequeue_script ( 'woocommerce' );
        wp_dequeue_script ( 'wc-add-to-cart' );
        wp_deregister_script ( 'js-cookie' );
        wp_dequeue_script ( 'js-cookie' );
        }
    }
// Remove WooCommerce Select2 - WooCommerce 3.2.1+
add_action ( 'wp_enqueue_scripts', 'woo_dequeue_select2', 100 );

function woo_dequeue_select2 () {
    if ( class_exists ( 'woocommerce' ) ) {
        wp_dequeue_style ( 'select2' );
        wp_deregister_style ( 'select2' );
        wp_dequeue_script ( 'selectWoo' );
        wp_deregister_script ( 'selectWoo' );
        }
    }

// Combine remove_action calls into one function
add_action ( 'init', 'customize_woocommerce_hooks' );

function customize_woocommerce_hooks () {
    // Remove breadcrumbs from shop & categories
    remove_action ( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

    // Remove Category: From product page
    remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

    // Remove product title
    remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

    // Remove product standard price
    remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

    // Remove product short desc
    remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

    // Remove product rating
    remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
    }

// Remove unused tabs
add_filter ( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );

function wcs_woo_remove_reviews_tab ( $tabs ) {
    unset ( $tabs[ 'reviews' ], $tabs[ 'description' ], $tabs[ 'additional_information' ] );
    return $tabs;
    }

// Get Grouped product parent
function parent_grouped_id ( $post_id = 0 ) {
    global $post, $wpdb;

    $post_id = $post_id ?: $post->ID;
    $results = $wpdb->get_results ( "
        SELECT pm.meta_value as child_ids, pm.post_id
        FROM {$wpdb->prefix}postmeta as pm
        INNER JOIN {$wpdb->prefix}posts as p ON pm.post_id = p.ID
        INNER JOIN {$wpdb->prefix}term_relationships as tr ON pm.post_id = tr.object_id
        INNER JOIN {$wpdb->prefix}terms as t ON tr.term_taxonomy_id = t.term_id
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        AND t.slug = 'grouped'
        AND pm.meta_key = '_children'
        ORDER BY p.ID
    " );

    foreach ( $results as $result ) {
        foreach ( maybe_unserialize ( $result->child_ids ) as $child_id ) {
            if ( $child_id == $post_id ) {
                return $result->post_id;
                }
            }
        }
    return 0;
    }

// Redirect training products to cart page on add to cart
add_filter ( 'woocommerce_add_to_cart_redirect', 'training_custom_add_to_cart_redirect' );

function training_custom_add_to_cart_redirect ( $url ) {
    if ( ! isset ( $_REQUEST[ 'add-to-cart' ] ) || ! is_numeric ( $_REQUEST[ 'add-to-cart' ] ) ) {
        return $url;
        }

    $product_id = absint ( $_REQUEST[ 'add-to-cart' ] );
    $product_id = apply_filters ( 'woocommerce_add_to_cart_product_id', $product_id );

    // Only redirect products that have the 'training-courses' category
    if ( has_term ( 'training-courses', 'product_cat', $product_id ) ) {
        return get_permalink ( 123 ); // Replace 123 with the ID of the cart page or desired redirect page
        }

    return $url;
    }


function delegate_output_fields () {
    global $product;

    if ( $product->is_type ( 'booking' ) ) {
        for ( $i = 1; $i <= 10; $i++ ) {
            echo '<div class="delegate-name-email-field hidden">';
            echo '<input type="hidden" id="delegate-name-' . $i . '" name="delegate[' . $i . '][name]">';
            echo '<input type="hidden" id="delegate-level-select-' . $i . '" name="delegate[' . $i . '][level_select]">';
            echo '<input type="hidden" id="delegate-number-' . $i . '" name="delegate[' . $i . '][number]">';
            echo '<input type="hidden" id="delegate-dob-' . $i . '" name="delegate[' . $i . '][dob]">';
            echo '<input type="hidden" id="delegate-phone-' . $i . '" name="delegate[' . $i . '][phone]">';
            echo '<input type="hidden" id="delegate-email-' . $i . '" name="delegate[' . $i . '][email]">';
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
            $i++;
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
    $i = 0;
    foreach ( $delegates as $delegate ) {
        $i++;

        if ( ! empty ( $delegate[ 'name' ] ) ) {
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Name', 'delegates' ), $delegate[ 'name' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Level Select', 'delegates' ), $delegate[ 'level_select' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Number', 'delegates' ), $delegate[ 'number' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' DOB', 'delegates' ), $delegate[ 'dob' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Phone', 'delegates' ), $delegate[ 'phone' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Email', 'delegates' ), $delegate[ 'email' ] );
            }
        }

    }
add_action ( 'woocommerce_checkout_create_order_line_item', 'delegate_add_name_email_text_to_order_items', 10, 6 );

// Function to get booking data
function get_booking_data ( $product_id ) {
    $consumer_key    = WC_BOOKINGS_CONSUMER_KEY;
    $consumer_secret = WC_BOOKINGS_CONSUMER_SECRET;
    $base_url        = get_site_url (); // Dynamic root URL

    $request_url = $base_url . '/wp-json/wc-bookings/v1/bookings';
    $params      = [ 
        'product_id' => $product_id,
        'status'     => 'confirmed',
    ];

    // Setup the request
    $url      = add_query_arg ( $params, $request_url );
    $response = wp_remote_get ( $url, [ 
        'headers' => [ 
            'Authorization' => 'Basic ' . base64_encode ( $consumer_key . ':' . $consumer_secret )
        ],
    ] );

    if ( is_wp_error ( $response ) ) {
        return [];
        }

    $body = wp_remote_retrieve_body ( $response );
    return json_decode ( $body, true );
    }

// Function to calculate remaining spaces
function calculate_remaining_spaces ( $product_id ) {
    $bookings     = get_booking_data ( $product_id );
    $total_spaces = intval ( get_post_meta ( $product_id, '_wc_booking_max_persons_group', true ) ); // Ensure total spaces is an integer

    $date_ranges = []; // To store date ranges and remaining spaces

    foreach ( $bookings as $booking ) {
        // Filter out cancelled bookings only
        if ( $booking[ 'status' ] === 'cancelled' ) {
            continue;
            }

        // Convert Unix timestamps to date strings
        $start_date     = ( new DateTime() )->setTimestamp ( $booking[ 'start' ] )->format ( 'Y-m-d' );
        $end_date       = ( new DateTime() )->setTimestamp ( $booking[ 'end' ] )->format ( 'Y-m-d' );
        $persons_booked = array_sum ( array_map ( 'intval', $booking[ 'person_counts' ] ) ); // Sum the persons booked and ensure they are integers

        $date_range_key = $start_date . ' - ' . $end_date;

        if ( ! isset ( $date_ranges[ $date_range_key ] ) ) {
            $date_ranges[ $date_range_key ] = $total_spaces;
            }

        $date_ranges[ $date_range_key ] -= $persons_booked;

        // Ensure remaining spaces do not go below zero
        if ( $date_ranges[ $date_range_key ] < 0 ) {
            $date_ranges[ $date_range_key ] = 0;
            }

        // Debugging: Print each date range key and remaining spaces
        echo "Date Range Key: $date_range_key, Remaining Spaces: {$date_ranges[ $date_range_key ]}\n";
        }

    return $date_ranges;
    }
// Function to display remaining spaces on the product page
function display_remaining_spaces ( $product_id ) {
    $remaining_spaces = calculate_remaining_spaces ( $product_id );

    if ( ! empty ( $remaining_spaces ) ) {
        echo '<ul class="remaining-spaces">';
        foreach ( $remaining_spaces as $date_range => $spaces ) {
            echo '<li>' . esc_html ( $date_range ) . ': ' . intval ( $spaces ) . ' spaces left</li>';
            }
        echo '</ul>';
        }
    }

// Hook to display remaining spaces before the product content
add_action ( 'woocommerce_before_single_product', function () {
    global $product;
    if ( $product->get_type () === 'booking' ) {
        display_remaining_spaces ( $product->get_id () );
        }
    }, 10 );

// Add to the single product page (adjust the hook as needed)
add_action ( 'woocommerce_single_product_summary', function () {
    global $product;
    if ( $product->get_type () === 'booking' ) {
        display_remaining_spaces ( $product->get_id () );
        }
    }, 25 );