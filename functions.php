<?php
/**
 * Register/enqueue custom scripts and styles
 */
add_action ( 'wp_enqueue_scripts', function () {
    // Enqueue your files on the canvas & frontend, not the builder panel. Otherwise custom CSS might affect builder)
    if ( ! bricks_is_builder_main () ) {
        wp_enqueue_style ( 'bricks-child', get_stylesheet_uri (), [ 'bricks-frontend' ], filemtime ( get_stylesheet_directory () . '/style.css' ) );
        }
    } );

/**
 * Register custom elements
 */
add_action ( 'init', function () {
    $element_files = [ 
        __DIR__ . '/elements/title.php',
    ];

    foreach ( $element_files as $file ) {
        \Bricks\Elements::register_element ( $file );
        }
    }, 11 );

/**
 * Add text strings to builder
 */
add_filter ( 'bricks/builder/i18n', function ($i18n) {
    // For element category 'custom'
    $i18n[ 'custom' ] = esc_html__ ( 'Custom', 'bricks' );

    return $i18n;
    } );

function mytheme_add_woocommerce_support () {
    add_theme_support ( 'woocommerce' );
    }

add_action ( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

//Include woocommerce custom functions comment out if not being used
include ( 'woocommerce-functions.php' );

add_filter ( 'bricks/code/echo_function_names', function () {
    return [ 
        'woocommerce_get_loop_display_mode',
        'get_queried_object_id',
        'date',
    ];
    } );


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

// ADDING A CUSTOM COLUMN TITLE TO ADMIN PRODUCTS LIST
add_filter ( 'manage_edit-product_columns', 'custom_product_column', 11 );
function custom_product_column ( $columns ) {

    $new_columns = array();

    foreach ( $columns as $key => $name ) {

        $new_columns[ $key ] = $name;

        // add ship-to after order status column
        if ( 'sku' === $key ) {
            $new_columns[ 'product_location' ] = __ ( 'Product Location', 'textdomain' );
            $new_columns[ 'venue_id' ]         = __ ( 'Venue ID', 'textdomain' );
            $new_columns[ 'template_id' ]      = __ ( 'Template ID', 'textdomain' );
            }
        }

    unset ( $new_columns[ 'product_tag' ] );
    unset ( $new_columns[ 'featured' ] );

    return $new_columns;

    }

//Control width of columns
add_action ( 'admin_head', 'style_custom_columns_identify' );

function style_custom_columns_identify () {
    global $pagenow;
    if ( $pagenow == 'edit.php' ) {
        ?>
        <style type="text/css">
            .manage-column.column-product_location {
                width: 80px;
            }

            .manage-column.column-venue_id,
            .manage-column.column-template_id {
                width: 60px;
            }
        </style>
        <?php
        }
    }

// Add data to columns
add_action ( 'manage_product_posts_custom_column', 'custom_product_list_column_content', 10, 2 );
function custom_product_list_column_content ( $column, $product_id ) {
    global $post;

    switch ( $column ) {
        case 'product_location':
            echo get_post_meta ( $product_id, 'product_location', true ); // display the data
            break;
        case 'venue_id':
            echo get_post_meta ( $product_id, 'venue_id', true ); // display the data
            break;
        case 'template_id':
            echo get_post_meta ( $product_id, 'template_id', true ); // display the data
            break;
        }
    }

//Make columns sortable
add_filter ( 'manage_edit-product_sortable_columns', 'sortable_custom_product_cols' );
function sortable_custom_product_cols ( $columns ) {
    $columns[ 'product_location' ] = 'product_location';
    $columns[ 'venue_id' ]         = 'venue_id';
    $columns[ 'template_id' ]      = 'template_id';

    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);

    return $columns;
    }

//Allow meta data to be sortable
add_filter ( 'woocommerce_shop_order_search_fields', 'location_searchable_fields', 10, 1 );
function location_searchable_fields ( $meta_keys ) {
    $meta_keys[] = 'product_location';
    $meta_keys[] = 'venue_id';
    $meta_keys[] = 'template_id';
    return $meta_keys;
    }

remove_action ( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );


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
                <input type="hidden" id="delegate-NI-<?php echo $i; ?>" name="delegate[<?php echo $i; ?>][NI]">
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

//CITB Number Field
add_action ( 'woocommerce_after_order_notes', 'custom_citb_field' );
function custom_citb_field ( $checkout ) {
    woocommerce_form_field (
        'citb_levy_number',
        array(
            'type'  => 'text',
            'class' => array(
                'my-field-class form-row-wide',
            ),
            'label' => __ ( 'CITB Levy Number' ),
        ),
        $checkout->get_value ( 'citb_levy_number' ),
    );
    }

//Update the value given in custom field
add_action ( 'woocommerce_checkout_update_order_meta', 'custom_checkout_citb_update_order_meta' );
function custom_checkout_citb_update_order_meta ( $order_id ) {
    if ( ! empty ( $_POST[ 'citb_levy_number' ] ) ) {
        update_post_meta ( $order_id, 'CITB Levy Number', sanitize_text_field ( $_POST[ 'citb_levy_number' ] ) );
        }
    }

//Add custom field to the checkout page
add_action ( 'woocommerce_after_order_notes', 'custom_po_number_field' );
function custom_po_number_field ( $checkout ) {
    woocommerce_form_field (
        'po_number',
        array(
            'type'  => 'text',
            'class' => array(
                'my-field-class form-row-wide',
            ),
            'label' => __ ( 'PO Number' ),
        ),
        $checkout->get_value ( 'po_number' ),
    );
    }

//Update the value given in custom field
add_action ( 'woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta' );
function custom_checkout_field_update_order_meta ( $order_id ) {
    if ( ! empty ( $_POST[ 'po_number' ] ) ) {
        update_post_meta ( $order_id, 'PO Number', sanitize_text_field ( $_POST[ 'po_number' ] ) );
        }
    }

add_filter ( 'woocommerce_order_actions', 'bbloomer_show_thank_you_page_order_admin_actions', 9999, 2 );

function bbloomer_show_thank_you_page_order_admin_actions ( $actions, $order ) {
    if ( $order->has_status ( wc_get_is_paid_statuses () ) ) {
        $actions[ 'view_thankyou' ] = 'Display thank you page';
        }
    return $actions;
    }

add_action ( 'woocommerce_order_action_view_thankyou', 'bbloomer_redirect_thank_you_page_order_admin_actions' );

function bbloomer_redirect_thank_you_page_order_admin_actions ( $order ) {
    $url = $order->get_checkout_order_received_url ();
    add_filter ( 'redirect_post_location', function () use ($url) {
        return $url;
        } );
    }

add_filter ( 'wpcf7_autop_or_not', '__return_false' );


//Disable KSE Escaping of ACF
add_filter ( 'acf/the_field/allow_unsafe_html', function ($allowed, $selector) {
    return true;
    }, 10, 2 );
?>