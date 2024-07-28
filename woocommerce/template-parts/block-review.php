<?php
/**
 * The template for displaying the review summary
 */
defined ( 'ABSPATH' ) || exit;
global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';
/**
 * @suppress PHP0417
 */
$duration_time  = $durationTime[ 0 ];
$duration_type  = $durationType[ 0 ];
$select_address = get_field ( 'select_address' );
?>
<input type="hidden" id="cost-of-course" value="<?php echo $product->get_price (); ?>" />
<input type="hidden" id="multi-cost-of-course" value="" />
<input type="hidden" id="changed-cost-of-course" value="" />
<div class="course-step" id="step-4">
  <div class="title-row step-title">
    <span class="title">Step 4 - Review Your Booking</span>
    <a href="#" data-step="4" class="previous-step float-right to-animate"></a>
  </div>

  <div class="review-booking-block">
    <div class="meta">
      <b>Course Selection</b>
      <span class="title"><?php echo $product_group_title; ?></span>
      <span class="title duration"><i class="far fa-clock"></i> <?php echo $duration_time; ?>
        <?php echo $duration_type; ?> course</span>
    </div>
  </div>

  <div class="review-booking-block">
    <div class="meta"><b>Course Venue </b><span class="title"><?php echo $select_address; ?></span></div>
  </div>

  <div class="meta" id="course-date-meta">
    <b>Course Date</b>
    <span class="start-date">
      <div class="dd"></div>
      <div class="mm"></div>
      <div class="yyyy"></div>
    </span>
  </div>
  <div class="review-booking-block">
    <div class="meta"><b>Number Of People </b><span class="title number-of-people"></span></div>
  </div>
  <div class="row confirm-row">
    <p class="from-price price"></p>
    <p class="total-price price">
      <span class="price title">Total - £<span id="total-cost"> Inc VAT</span></span>
    </p>
    <a href="#" class="cta-button float-right" id="confirm-boooking">Confirm Your Booking</a>
  </div>
</div>