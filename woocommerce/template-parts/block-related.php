<?php
/**
 * The template for displaying the related products
 *
 */
defined ( 'ABSPATH' ) || exit;
global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';
?>
<section class="brxe-section brxe-wc-section related-products">
  <div class="related-title">
    <span class="title to-animate">Other training you may be interested in</span>
    <a href="<?php get_site_url (); ?>/course/training-courses/" class="float-right title-link">See all training
      courses</a>
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