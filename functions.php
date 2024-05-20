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
