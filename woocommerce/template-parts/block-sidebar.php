<?php
/**
 * The template for displaying the product sidebar
 */
defined ( 'ABSPATH' ) || exit;
global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';

$location_label = esc_html ( $location[ 'label' ] );
?>

<div class="training-sidebar">
  <div class="sidebar-inner">
    <div class="sidebar-selections">
      <h3 class="brxe-heading">Your Selection</h3>
      <!-- meta class uses js -->
      <div class="meta">
        <b>Course: </b><br><span><?php echo $display_title; ?></span>
      </div>

      <div class="meta">
        <b>Venue</b><span>
          <p>Location: <?php echo $location_label; ?></p>
        </span>
      </div>

      <div class="meta" id="course-date-meta">
        <b>Course Date</b><br>
        <span class="title">
          <span class="start-date">
            <div class="dd"></div>
            <div class="mm"></div>
            <div class="yyyy"></div>
          </span>
        </span>
      </div>
      <p class="from-price price"></p>
      <p class="total-price price">
        <span class="price title">Total: <span id="total-cost"></span></span>
      </p>
    </div>
  </div>
</div>