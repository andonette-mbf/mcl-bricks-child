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

add_filter ( 'bricks/code/echo_function_names', function () {
    return [ 
        'date',
        'get_queried_object_id',
        'woocommerce_get_loop_display_mode',
    ];
    } );

//Functions Added By Andonette 
//woocommerce theme support
function mytheme_add_woocommerce_support () {
    add_theme_support ( 'woocommerce' );
    }

add_action ( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

//Include woocommerce custom functions comment out if not being used
include ( 'woocommerce-functions.php' );

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