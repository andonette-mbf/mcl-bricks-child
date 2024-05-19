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

//Remove Woocommerce Select2 - Woocommerce 3.2.1+
function woo_dequeue_select2 ()
    {
    if ( class_exists ( 'woocommerce' ) )
        {
        wp_dequeue_style ( 'select2' );
        wp_deregister_style ( 'select2' );

        wp_dequeue_script ( 'selectWoo' );
        wp_deregister_script ( 'selectWoo' );
        }
    }
add_action ( 'wp_enqueue_scripts', 'woo_dequeue_select2', 100 );


// Remove breadcrumbs from shop & categories
add_filter ( 'woocommerce_before_main_content', 'remove_breadcrumbs' );
function remove_breadcrumbs ()
    {
    remove_action ( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
    }

//Remove Category: From product page
remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


//Change in and out of stock message
add_filter ( 'woocommerce_get_stock_html', 'filter_get_stock_html', 10, 2 );
function filter_get_stock_html ( $html, $product )
    {
    // Low stock quantity amount
    $low_stock_qty = 3;

    $availability = $product->get_availability ();

    if ( ! empty ( $availability[ 'availability' ] ) )
        {
        $class      = esc_attr ( $availability[ 'class' ] );
        $avail_text = wp_kses_post ( $availability[ 'availability' ] );
        $stock_qty  = $product->get_stock_quantity ();

        if ( $stock_qty <= $low_stock_qty )
            {
            $class .= ' few-in-stock';
            }
        ob_start ();

        // Make your changes below
        ?>
        <p class="stock <?php echo $class; ?>"><?php echo $avail_text; ?></p>
        <?php

        $html = ob_get_clean ();
        }
    return $html;
    }

//Remove unused tabs
add_filter ( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
function wcs_woo_remove_reviews_tab ( $tabs )
    {
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


//Dont show booking products on front end
add_action ( 'pre_get_posts', 'dont_show_booking' );
function dont_show_booking ( $query )
    {
    if ( ! is_admin () && is_tax () && $query->is_main_query () )
        {

        $taxquery = array(
            array(
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => 'booking',
                'operator' => 'NOT IN',
            ),
        );

        $query->set ( 'tax_query', $taxquery );
        }
    }


// Change Woocommerce css breaktpoint from max width: 768px to 767px  
add_filter ( 'woocommerce_style_smallscreen_breakpoint', 'woo_custom_breakpoint' );

function woo_custom_breakpoint ( $px )
    {
    $px = '767px';
    return $px;
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
