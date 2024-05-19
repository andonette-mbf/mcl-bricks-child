<?php
/**
 * Register/enqueue custom scripts and styles
 */
add_action ( 'wp_enqueue_scripts', function ()
    {
    // Enqueue your files on the canvas & frontend, not the builder panel. Otherwise custom CSS might affect builder)

    if ( ! bricks_is_builder_main () )
        {
        wp_enqueue_style ( 'bricks-child', get_stylesheet_uri (), [ 'bricks-frontend' ], filemtime ( get_stylesheet_directory () . '/style.css' ) );
        }
    } );

/**
 * Register custom elements
 */
add_action ( 'init', function ()
    {
    $element_files = [ 
        __DIR__ . '/elements/title.php',
    ];

    foreach ( $element_files as $file )
        {
        \Bricks\Elements::register_element ( $file );
        }
    }, 11 );

/**
 * Add text strings to builder
 */
add_filter ( 'bricks/builder/i18n', function ($i18n)
    {
    // For element category 'custom'
    $i18n[ 'custom' ] = esc_html__ ( 'Custom', 'bricks' );

    return $i18n;
    } );

function mytheme_add_woocommerce_support ()
    {
    add_theme_support ( 'woocommerce' );
    }

add_action ( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

//Include woocommerce custom functions comment out if not being used
include ( 'woocommerce-functions.php' );

add_filter ( 'bricks/code/echo_function_names', function ()
    {
    return [ 
        'woocommerce_get_loop_display_mode',
        'get_queried_object_id',
        'date',
    ];
    } );


//Output delegate name field
function delegate_output_name_field ()
    {
    global $product;
    if ( $product->is_type ( 'booking' ) )
        {
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
add_action ( 'woocommerce_before_add_to_cart_button', 'delegate_output_name_field', 10 );

//Output delegate level field
function delegate_output_level_field ()
    {
    global $product;
    if ( $product->is_type ( 'booking' ) )
        {
        ?>

        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-level-1" level="delegate[1][level]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-level-2" level="delegate[2][level]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-level-3" level="delegate[3][level]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-level-4" level="delegate[4][level]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-level-5" level="delegate[5][level]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-level-6" level="delegate[6][level]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-level-7" level="delegate[7][level]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-level-8" level="delegate[8][level]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-level-9" level="delegate[9][level]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-level-10" level="delegate[10][level]">
        </div>

        <?php
        }
    }
add_action ( 'woocommerce_before_add_to_cart_button', 'delegate_output_level_field', 10 );

//Output delegate number field
function delegate_output_number_field ()
    {
    global $product;
    if ( $product->is_type ( 'booking' ) )
        {
        ?>

        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-number-1" number="delegate[1][number]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-number-2" number="delegate[2][number]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-number-3" number="delegate[3][number]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-number-4" number="delegate[4][number]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-number-5" number="delegate[5][number]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-number-6" number="delegate[6][number]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-number-7" number="delegate[7][number]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-number-8" number="delegate[8][number]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-number-9" number="delegate[9][number]">
        </div>
        <div class="delegate-name-email-field hidden">
            <input type="hidden" id="delegate-number-10" number="delegate[10][number]">
        </div>

        <?php
        }
    }
add_action ( 'woocommerce_before_add_to_cart_button', 'delegate_output_number_field', 10 );

//Output delegate dob field
function delegate_output_dob_field ()
    {
    global $product;
    if ( $product->is_type ( 'booking' ) )
        {
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
add_action ( 'woocommerce_before_add_to_cart_button', 'delegate_output_dob_field', 10 );


//Update cart meta if it has delegates
function delegate_add_name_email_text_to_cart_item ( $cart_item_data, $product_id, $variation_id )
    {
    $delegates = filter_input ( INPUT_POST, 'delegate', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

    if ( empty ( $delegates ) )
        {
        return $cart_item_data;
        }

    //set delegates for cart item
    $cart_item_data[ 'delegates' ] = $delegates;

    return $cart_item_data;
    }
add_filter ( 'woocommerce_add_cart_item_data', 'delegate_add_name_email_text_to_cart_item', 10, 3 );

//Display delegates in the cart
function delegate_display_name_email_text_cart ( $item_data, $cart_item )
    {

    if ( empty ( $cart_item[ 'delegates' ] ) )
        {
        return $item_data;
        }

    //add delegates for array
    $delegates = $cart_item[ 'delegates' ];

    //Split out delegates for display
    $i = 0;
    foreach ( $delegates as $delegate )
        {
        $i++;

        if ( ! empty ( $delegate[ 'name' ] ) )
            {

            $item_data[] = array(
                'key'     => __ ( 'Delegate ' . $i . ' Name', 'delegates' ),
                'value'   => wc_clean ( $delegate[ 'name' ] ),
                'display' => '',
            );

            $item_data[] = array(
                'key'     => __ ( 'Delegate ' . $i . ' Level', 'delegates' ),
                'value'   => wc_clean ( $delegate[ 'level' ] ),
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

            }
        }
    return $item_data;
    }

add_filter ( 'woocommerce_get_item_data', 'delegate_display_name_email_text_cart', 10, 2 );

//Add delegates to order
function delegate_add_name_email_text_to_order_items ( $item, $cart_item_key, $values, $order )
    {

    if ( empty ( $values[ 'delegates' ] ) )
        {
        return;
        }

    //set delegates for variable
    $delegates = $values[ 'delegates' ];
    echo $delegates;

    //add delegates for array
    $item->add_meta_data ( __ ( 'delegates', 'delegates', 'delegates', 'delegates', ), $delegates );

    //Split out delegates for display
    $i = 0;
    foreach ( $delegates as $delegate )
        {
        $i++;

        if ( ! empty ( $delegate[ 'name' ] ) )
            {
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Name', 'delegates' ), $delegate[ 'name' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Level', 'delegates' ), $delegate[ 'level' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' Number', 'delegates' ), $delegate[ 'number' ] );
            $item->add_meta_data ( __ ( 'Delegate ' . $i . ' DOB', 'delegates' ), $delegate[ 'dob' ] );
            }
        }

    }
add_action ( 'woocommerce_checkout_create_order_line_item', 'delegate_add_name_email_text_to_order_items', 10, 6 );

add_action ( 'woocommerce_before_add_to_cart_button', 'add_delegate_hidden_fields' );

function add_delegate_hidden_fields ()
    {
    for ( $i = 1; $i <= 10; $i++ )
        { // Assuming a maximum of 10 delegates
        echo '<input type="hidden" name="delegate[' . $i . '][name]" value="" />';
        echo '<input type="hidden" name="delegate[' . $i . '][level]" value="" />';
        echo '<input type="hidden" name="delegate[' . $i . '][number]" value="" />';
        echo '<input type="hidden" name="delegate[' . $i . '][dob]" value="" />';
        echo '<input type="hidden" name="delegate[' . $i . '][ni]" value="" />';
        }
    }

//Add custom field to the checkout page
add_action ( 'woocommerce_after_order_notes', 'custom_po_number_field' );
function custom_po_number_field ( $checkout )
    {
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
function custom_checkout_field_update_order_meta ( $order_id )
    {
    if ( ! empty ( $_POST[ 'po_number' ] ) )
        {
        update_post_meta ( $order_id, 'PO Number', sanitize_text_field ( $_POST[ 'po_number' ] ) );
        }
    }

add_filter ( 'woocommerce_order_actions', 'bbloomer_show_thank_you_page_order_admin_actions', 9999, 2 );

function bbloomer_show_thank_you_page_order_admin_actions ( $actions, $order )
    {
    if ( $order->has_status ( wc_get_is_paid_statuses () ) )
        {
        $actions[ 'view_thankyou' ] = 'Display thank you page';
        }
    return $actions;
    }

add_action ( 'woocommerce_order_action_view_thankyou', 'bbloomer_redirect_thank_you_page_order_admin_actions' );

function bbloomer_redirect_thank_you_page_order_admin_actions ( $order )
    {
    $url = $order->get_checkout_order_received_url ();
    add_filter ( 'redirect_post_location', function () use ($url)
        {
        return $url;
        } );
    }

//Disable KSE Escaping of ACF
add_filter ( 'acf/the_field/allow_unsafe_html', function ($allowed, $selector)
    {
    return true;
    }, 10, 2 );
?>
