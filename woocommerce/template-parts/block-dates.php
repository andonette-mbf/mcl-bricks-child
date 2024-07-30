<?php
/**
 * The template for displaying dates
 */
defined ( 'ABSPATH' ) || exit;

global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';
?>

<div class="course-step" id="step-2">
  <div class="step-title">
    <span class="title">Choose The Date</span>
    <a href="#" data-step="2" class="previous-step"></a>
  </div>

  <div class="step-layouts">
    <div class="layouts" id="layout-list">
      <div class="calendar-list">
        <div class="table-section">
          <?php if ( ! empty ( $future_availability_rows ) ) : ?>
            <table class="table">
              <style>
                /* CSS to hide columns on mobile devices */
                @media only screen and (max-width: 767px) {
                  .mobile-hide {
                    display: none;
                  }
                }
              </style>
              <thead>
                <tr>
                  <th>Start Date</th>
                  <th class="mobile-hide">Location</th>
                  <th>Availability</th>
                  <th class="add-td"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ( $future_availability_rows as $row ) : ?>
                  <?php $is_fully_booked = ( $row[ 'available_spaces' ] == 0 ); ?>
                  <tr class="availability-date <?php echo esc_attr ( $row[ 'hidden_class' ] ); ?>"
                    data-start="<?php echo esc_attr ( DateTime::createFromFormat ( 'd/m/Y', $row[ 'from' ] )->format ( 'Y-m-d' ) ); ?>"
                    data-end="<?php echo esc_attr ( DateTime::createFromFormat ( 'd/m/Y', $row[ 'to' ] )->format ( 'Y-m-d' ) ); ?>">
                    <td><?php echo esc_html ( $row[ 'from' ] ); ?></td>
                    <td class="mobile-hide"><?php echo esc_html ( $row[ 'location_name' ] ); ?></td>
                    <td>
                      <?php if ( $is_fully_booked ) : ?>
                        <b><?php echo esc_html ( 'Course Full' ); ?></b>
                      <?php else : ?>
                        <?php echo esc_html ( $row[ 'available_spaces' ] ); ?>
                      <?php endif ?>
                    </td>
                    <td class="add-td">
                      <?php if ( $is_fully_booked ) : ?>
                        <a href="#contact" class="cta-button cta-button--alt float-right">Contact Us</a>
                      <?php else : ?>
                        <a href="#" data-day="<?php echo esc_attr ( $row[ 'data_day' ] ); ?>"
                          data-month="<?php echo esc_attr ( $row[ 'data_month' ] ); ?>"
                          data-year="<?php echo esc_attr ( $row[ 'data_year' ] ); ?>"
                          class="cta-button book-now-button float-right">Select Date</a>
                      <?php endif ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <button id="show-more-dates" class="cta-button" style="display: none;">More</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="layouts float-left w-100 position-relative" id="layout-calendar">
      <?php do_action ( 'woocommerce_single_product_summary' ); ?>
    </div>
  </div>
</div>