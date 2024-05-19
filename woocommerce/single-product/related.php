<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined ( 'ABSPATH' ) ) {
    exit;
    }

$current_product_id = get_the_ID ();

global $product, $woocommerce_loop;

if ( empty ( $product ) || ! $product->exists () ) {
    return;
    }

if ( ! $related = $product->get_related ( $posts_per_page ) ) {
    return;
    }

$cats_array = array( 0 );

// get categories
$terms = wp_get_post_terms ( $product->id, 'product_cat' );

// select only the category which doesn't have any children
foreach ( $terms as $term ) {
    $children = get_term_children ( $term->term_id, 'product_cat' );
    if ( ! sizeof ( $children ) )
        $cats_array[] = $term->term_id;
    }

$args = apply_filters ( 'woocommerce_related_products_args', array(
    'post_type'      => 'product',
    'posts_per_page' => 2,
    'post__not_in'   => array( $current_product_id ),
    'tax_query'      => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'id',
            'terms'    => $cats_array,
        ),
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => 'booking',
            'operator' => 'NOT IN',
        ),
    ),
),
);

$products                    = new WP_Query( $args );
$woocommerce_loop[ 'name' ]    = 'related';
$woocommerce_loop[ 'columns' ] = apply_filters ( 'woocommerce_related_products_columns', $columns );

if ( $products->have_posts () ) : ?>


    <?php while ( $products->have_posts () ) :
        $products->the_post (); ?>

        <?php global $product; ?>

        <?php if ( has_term ( 25, 'product_cat', $current_product_id ) ) {
            include ( get_stylesheet_directory () . '/template-parts/post/content-training.php' );
            } else {
            include ( get_stylesheet_directory () . '/template-parts/post/content-product.php' );
            } ?>

    <?php endwhile; // end of the loop. ?>

    <?php
endif;
wp_reset_postdata ();
