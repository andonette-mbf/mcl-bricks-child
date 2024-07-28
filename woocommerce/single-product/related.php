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

global $product;

if ( ! $product ) {
    return;
    }

$current_product_id = $product->get_id ();

// Use wc_get_related_products instead of the deprecated method
$related_products = wc_get_related_products ( $current_product_id, 2 );

$cats_array = array();

// Get categories
$terms = wp_get_post_terms ( $current_product_id, 'product_cat' );

// Select only the category which doesn't have any children
foreach ( $terms as $term ) {
    $children = get_term_children ( $term->term_id, 'product_cat' );
    if ( empty ( $children ) ) {
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

$products = new WP_Query( $args );

if ( $products->have_posts () ) : ?>
    <?php while ( $products->have_posts () ) :
        $products->the_post (); ?>
        <?php
        /**
         * @suppress PHP0417
         */
        $related_product = wc_get_product ( get_the_ID () );
        ?>
        <div class="product-grid">
            <article class="product-post w-100 position-relative" id="product-post-<?php the_ID (); ?>">
                <div class="post-thumbnail-outer w-100 position-relative"
                    style="<?php echo has_post_thumbnail () ? 'background-image: url(' . esc_url ( get_the_post_thumbnail_url ( get_the_ID (), 'full' ) ) . ');' : ''; ?>">
                    <?php if ( ! has_post_thumbnail () ) { ?>
                        <div class="vertical missing-img title">
                            Sorry, we don't currently have an image for this post
                        </div>
                    <?php } ?>
                </div>

                <div class="post-content w-100">
                    <h3 class="position-relative" title="<?php the_title (); ?>"><?php the_title (); ?></h3>
                    <p class="price title"><?php echo $related_product->get_price_html (); ?></p>
                    <a href="<?php the_permalink (); ?>" class="cta-button">Find out more</a>
                </div>
            </article>
        </div>
    <?php endwhile; // end of the loop. ?>
<?php
endif;
wp_reset_postdata ();
?>