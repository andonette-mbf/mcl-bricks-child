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

$related = $product->get_related ( $posts_per_page );

$cats_array = array( 0 );

// get categories
$terms = wp_get_post_terms ( $product->get_id (), 'product_cat' );

// select only the category which doesn't have any children
foreach ( $terms as $term ) {
    $children = get_term_children ( $term->term_id, 'product_cat' );
    if ( ! sizeof ( $children ) ) {
        $cats_array[] = $term->term_id;
        }
    }

$args = apply_filters (
    'woocommerce_related_products_args',
    array(
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
            ),
        ),
    ),
);

$products                      = new WP_Query( $args );
$woocommerce_loop[ 'name' ]    = 'related';
$woocommerce_loop[ 'columns' ] = apply_filters ( 'woocommerce_related_products_columns', $columns );

if ( $products->have_posts () ) : ?>

    <?php while ( $products->have_posts () ) :
        $products->the_post (); ?>

        <style>
            .product-post {

                /* Adjust width and margin as necessary */
                margin-bottom: 20px;
                border: 1px solid #e1e1e1;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                transition: transform 0.3s ease;
                background: #fff;
            }

            .product-post:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
            }

            .post-thumbnail-outer {
                background-size: cover;
                background-position: center;
                width: 100%;
                max-height: 500px;
                height: 500px;
                overflow: hidden;
                border-bottom: 1px solid #e1e1e1;
            }

            .post-thumbnail-outer .vertical.missing-img.title {
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                height: 100%;
                color: #fff;
                background: #000;
            }

            .post-content {
                text-align: center;
                padding: 15px;
            }


            .post-content .price {
                font-size: 1.2em;
                margin: 10px 0;
            }

            .cta-button {
                display: inline-block;
                margin-top: 10px;
                padding: 10px 20px;
                background-color: #0073aa;
                color: #fff;
                text-decoration: none;
                transition: background-color 0.3s ease;
            }

            .cta-button:hover {
                background-color: #005177;
            }
        </style>

        <div class="product-grid">
            <article class="product-post w-100 position-relative" id="product-post-<?php the_ID (); ?>">
                <div class="post-thumbnail-outer w-100 position-relative"
                    style="<?php echo has_post_thumbnail () ? 'background-image: url(' . get_the_post_thumbnail_url ( get_the_ID (), 'full' ) . ');' : ''; ?>">
                    <?php if ( ! has_post_thumbnail ( $post->ID ) ) { ?>
                        <div class="vertical missing-img title">
                            Sorry, we don't currently have an image for this post
                        </div>
                    <?php } ?>
                </div>

                <div class="post-content w-100">
                    <h3 class="position-relative" title="<?php the_title (); ?>"><?php the_title (); ?></h3>
                    <p class="price title"><?php echo $product->get_price_html (); ?></p>
                    <a href="<?php the_permalink (); ?>" class="cta-button">Find out more</a>
                </div>
            </article>
        </div>

    <?php endwhile; // end of the loop. ?>

    <?php
endif;
wp_reset_postdata ();
?>