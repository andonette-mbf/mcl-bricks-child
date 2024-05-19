<?php
// All your custom woocommerce theme functionality goes in here

//* Enqueue scripts and styles
add_action ( 'wp_enqueue_scripts', 'disable_woocommerce_loading_css_js' );

function disable_woocommerce_loading_css_js ()
    {

    // Check if WooCommerce plugin is active
    if ( function_exists ( 'is_woocommerce' ) )
        {

        // Check if it's any of WooCommerce page
        if ( ! is_woocommerce () && ! is_cart () && ! is_checkout () && ! is_account_page () )
            {

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
function parent_grouped_id ( $post_id = 0 )
    {
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
    foreach ( $results as $result )
        {
        foreach ( maybe_unserialize ( $result->child_ids ) as $child_id )
            if ( $child_id == $post_id )
                {
                $parent_grouped_id = $result->post_id;
                break;
                }
        if ( $parent_grouped_id != 0 ) break;
        }
    return $parent_grouped_id;
    }



//Redirect training products to cart page on add to cart
function training_custom_add_to_cart_redirect ( $url )
    {

    if ( ! isset ( $_REQUEST[ 'add-to-cart' ] ) || ! is_numeric ( $_REQUEST[ 'add-to-cart' ] ) )
        {
        return $url;
        }

    $product_id = apply_filters ( 'woocommerce_add_to_cart_product_id', absint ( $_REQUEST[ 'add-to-cart' ] ) );

    // Only redirect products that have the 't-shirts' category
    if ( has_term ( 'training-courses', 'product_cat', $product_id ) )
        {
        $url = get_permalink ( 123 );
        }

    return $url;
    }
add_filter ( 'woocommerce_add_to_cart_redirect', 'training_custom_add_to_cart_redirect' );
?>
