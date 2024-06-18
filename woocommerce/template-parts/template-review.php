<?php
/**
 * Template part for displaying the booking review
 */
global $product;
?>

<div class="course-step" id="step-4">
  <div class="title-row step-title">
    <span class="title">Step 4 - Review Your Booking</span>
    <a href="#" data-step="4" class="previous-step float-right to-animate"></a>
  </div>

  <div class="review-booking-block">
    <div class="meta">
      <b>Course Selection</b>
      <span class="title"><?php echo get_the_title ( $product_group_id ); ?></span>
      <span class="title duration"><i class="far fa-clock"></i> <?php echo $duarionTime[ 0 ]; ?>
        <?php echo $duarionType[ 0 ]; ?> course</span>
    </div>
  </div>

  <div class="review-booking-block">
    <div class="meta"><b>Course Venue </b><span class="title"><?php the_field ( 'select_address' ); ?></span>
    </div>
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
    <?php if ( $product->is_type ( 'booking' ) ) { ?>
      <p class="from-price price"></p>
      <p class="total-price price">
        <span class="price title">Total - £<span id="total-cost"> Inc VAT</span></span>
      </p>
    <?php } ?>

    <a href="#" class="cta-button float-right" id="confirm-boooking">Confirm Your Booking</a>
    <a href="tel:<?php echo get_field ( 'main_telephone_number', 'options' ); ?>" id="cannotBookCourse"
      class="cta-button float-right w-auto" style="display: none;"></a>
  </div>
</div>