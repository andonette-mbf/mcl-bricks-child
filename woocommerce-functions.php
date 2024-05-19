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
    if ( is_product () ) {
        global $post;
        $product = wc_get_product ( $post->ID );

        if ( $product && $product->is_type ( 'grouped' ) ) {
            $children = $product->get_children ();

            // Redirect to the first available child product
            if ( ! empty ( $children ) ) {
                foreach ( $children as $child_id ) {
                    $child_product = wc_get_product ( $child_id );

                    if ( $child_product && $child_product->is_in_stock () ) {
                        wp_redirect ( get_permalink ( $child_id ), 301 );
                        exit;
                        }
                    }
                // If no children are in stock, fallback to the first child
                wp_redirect ( get_permalink ( $children[ 0 ] ), 301 );
                exit;
                }
            }
        }
    }

function get_future_availability_dates ( $product_id ) {
    $availability_in_future = false;
    $availability           = get_post_meta ( $product_id, '_wc_booking_availability' );
    $availability_test      = array_filter ( $availability );

    // Store availability dates
    $future_availability_dates = array();
    $fad                       = 0;

    // Loop through and check date is in the future
    foreach ( $availability_test as $availability_test_range ) {
        foreach ( $availability_test_range as $availability_test_range_single ) {

            // Check if the course has a time and adjust date handling
            if ( isset ( $availability_test_range_single[ "from_date" ] ) ) {
                $opening_date = new DateTime( $availability_test_range_single[ "from_date" ] );
                $closing_date = new DateTime( $availability_test_range_single[ "to_date" ] );
                } else {
                $opening_date = new DateTime( $availability_test_range_single[ "from" ] );
                $closing_date = new DateTime( $availability_test_range_single[ "to" ] );
                }

            $current_date = new DateTime();

            if ( $opening_date > $current_date ) {
                $availability_in_future = true;

                // Add to array to display in list
                $future_availability_dates[ $fad ][ "from" ] = $opening_date;
                $future_availability_dates[ $fad ][ "to" ]   = $closing_date;
                $fad++;
                }
            }
        }

    return array(
        'availability_in_future'    => $availability_in_future,
        'future_availability_dates' => $future_availability_dates,
    );
    }


?>