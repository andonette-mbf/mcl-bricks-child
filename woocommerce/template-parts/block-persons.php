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
//Step 3 variables
$product_price = $product->get_price ();
$prices        = [ 
  1 => $product_price,
  2 => $product_price * 2,
  3 => $product_price * 3,
  4 => $product_price * 4,
  5 => $product_price * 5,
];
?>
<div class="course-step" id="step-3">
  <div class="step-title">
    <span class="title">Select The Number Of People</span>
    <a href="#" data-step="3" class="previous-step">Previous step</a>
  </div>

  <?php foreach ( $prices as $people => $price ) : ?>
    <div class="step-field person-field">
      <a href="#" data-bookingPerson="<?php echo $people; ?>">
        <?php echo $people; ?> Person<?php echo $people > 1 ? 's' : ''; ?>
        <p class="price">(£<span id="price-<?php echo $people; ?>"><?php echo $price; ?> Inc VAT</span>)</p>
      </a>
    </div>
  <?php endforeach; ?>

  <!-- <div class="step-field person-dropdown">
                <a href="#" class="people-over-5-content">5+ People</a>
                <div class="people-select-menu">
                  <select id="groupBookingMenu">
                    <option>Select No. People</option>
                    <?php //for ( $i = 6; $i <= 10; $i++ ) : ?>
                      <option value="<?php //echo $i; ?>" data-bookingPerson="<?php //echo $i; ?>"><?php //echo $i; ?> People
                      </option>
                    <?php //endfor; ?>
                  </select>
                </div>
              </div> -->
  <?php get_template_part ( 'woocommerce/template-parts/block', 'delegate' ); ?>
</div>