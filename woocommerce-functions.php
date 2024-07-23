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

// // Redirect training products to cart page on add to cart
// add_filter ( 'woocommerce_add_to_cart_redirect', 'training_custom_add_to_cart_redirect' );

// function training_custom_add_to_cart_redirect ( $url ) {
//     if ( ! isset ( $_REQUEST[ 'add-to-cart' ] ) || ! is_numeric ( $_REQUEST[ 'add-to-cart' ] ) ) {
//         return $url;
//         }

//     $product_id = absint ( $_REQUEST[ 'add-to-cart' ] );
//     $product_id = apply_filters ( 'woocommerce_add_to_cart_product_id', $product_id );

//     // Only redirect products that have the 'training-courses' category
//     if ( has_term ( 'training-courses', 'product_cat', $product_id ) ) {
//         return get_permalink ( 123 ); // Replace 123 with the ID of the cart page or desired redirect page
//         }

//     return $url;
//     }


function delegate_output_fields () {
    global $product;

    if ( $product->is_type ( 'booking' ) ) {
        for ( $i = 1; $i <= 6; $i++ ) {
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

function enqueue_woocommerce_scripts () {
    if ( is_product () ) {
        wp_enqueue_script ( 'wc-add-to-cart' );
        wp_enqueue_script ( 'wc-single-product' );
        }
    }
add_action ( 'wp_enqueue_scripts', 'enqueue_woocommerce_scripts' );
