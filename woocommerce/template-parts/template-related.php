<?php
/**
 * Template part for displaying the header of the shop
 */
global $product;
?>
<section class="brxe-section brxe-wc-section related-products">
  <div class="related-title">
    <?php
    $training_courses_id = get_term_by ( 'slug', 'training-courses', 'product_cat' );
    $terms               = get_the_terms ( $product->id, 'product_cat' );
    foreach ( $terms as $term ) {
      if ( $term->parent === $training_courses_id->term_id ) {
        $cat_name_first = $term->name;
        break;
        }
      } ?>

    <span class="title to-animate">Other <?php echo $cat_name_first; ?> you may be interested in</span>

    <a href="<?php echo get_term_link ( 'training-courses', 'product_cat' ); ?>" class="float-right title-link">See all
      training courses</a>
  </div>

  <?php
  /**
   * Hook: woocommerce_after_single_product_summary.
   *
   * @hooked woocommerce_output_product_data_tabs - 10
   * @hooked woocommerce_upsell_display - 15
   * @hooked woocommerce_output_related_products - 20
   */
  do_action ( 'woocommerce_after_single_product_summary' );
  ?>

</section>