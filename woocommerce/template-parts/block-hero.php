<?php
/**
 * The template for displaying the product hero 
 *
 */
defined ( 'ABSPATH' ) || exit;
global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';
/**
 * @suppress PHP0417
 */
//Hero Variables
$hero_image = get_field ( 'hero_image' );
$duration   = get_field ( 'number_of_days' );
?>
<section class="brxe-section brxe-wc-section mcl-hero">
  <div class="brxe-container mcl-flex--col mcl-hero__inner mcl-padding">
    <div class="brxe-div mcl-hero__content mcl-flex--col">
      <h1 class="brxe-heading mcl-hero__title"><?php echo $display_title; ?></h1>

      <h5>
        Duration: <span><?php echo $duration; ?> Days</span>
        <br>
        Availability: See dates below
      </h5>
    </div>
  </div>
  <div class="brxe-container mcl-hero__inner--absolute">
    <div class="brxe-div mcl-hero__overlay mcl-absolute--full"></div>
    <div class="brxe-div mcl-hero__image" style="background-image: url('<?php echo $hero_image; ?>');"></div>
    <div class="brxe-div mcl-hero__tagline-box">
      <h3 class="brxe-heading mcl-hero__tagline-box-heading">We've<br>got<br>you.</h3>
    </div>
  </div>
</section>
<!-- Woocommerce store notices -->
<section class="brxe-section brxe-wc-section">
  <div class="brxe-container">
    <?php wc_print_notices (); // Add this to display WooCommerce notices ?>
  </div>
</section>