<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 */
defined ( 'ABSPATH' ) || exit;

global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';

?>
<main id="brx-content">
  <div id="product-<?php the_ID (); ?>" class="product-<?php the_ID (); ?>">
    <!--Header Section -->
    <?php get_template_part ( 'woocommerce/template-parts/block', 'hero' ); ?>
    <!--Course Selection-->
    <section class="brxe-section brxe-wc-section training-course-product">
      <div class="brxe-container grid--1-3 gap--m">
        <!--Sidebar Summary-->
        <?php get_template_part ( 'woocommerce/template-parts/block', 'sidebar' ); ?>
        <div class="brxe-block">
          <?php if ( isset ( $_GET[ 'scrollStep' ] ) ) {
            $scrollStep = $_GET[ 'scrollStep' ]; ?>
            <script type="text/javascript">
              jQuery(document).ready(function () {
                var scrollStep = "<?php echo $scrollStep; ?>";
                //custom scrolling function - use common sense to see how it all fits together
                jQuery('html, body').animate({
                  scrollTop: jQuery(".training-course-steps .course-step#step-" + scrollStep).offset().top - 160
                }, 2000);
              });
            </script>
          <?php } ?>
          <div class="training-course-steps">
            <?php do_action ( 'woocommerce_before_single_product' ); ?>
            <?php get_template_part ( 'woocommerce/template-parts/block', 'location' ); ?>
            <!-- Only show the date table & selection options if there is availability -->
            <? //php if ( ! empty ( $future_availability_rows ) ) : ?>
            <!--dates partial -->
            <?php get_template_part ( 'woocommerce/template-parts/block', 'dates' ); ?>
            <!--contact partial -->
            <?php get_template_part ( 'woocommerce/template-parts/block', 'contact' ); ?>
            <!--People select partial-->
            <?php get_template_part ( 'woocommerce/template-parts/block', 'persons' ); ?>
            <!--review block partial -->
            <?php get_template_part ( 'woocommerce/template-parts/block', 'review' ); ?>
            <?php //else : ?>
            <!--course full partial -->
            <?php get_template_part ( 'woocommerce/template-parts/block', 'full' ); ?>
            <?php //endif; ?>
          </div>
        </div>
      </div>
  </div>
  </section>
  <!--tabs section -->
  <?php get_template_part ( 'woocommerce/template-parts/block', 'tabs' ); ?>
  <!--related courses partial -->
  <?php get_template_part ( 'woocommerce/template-parts/block', 'related' ); ?>
  </div>
</main>
<?php do_action ( 'woocommerce_after_single_product' ); ?>