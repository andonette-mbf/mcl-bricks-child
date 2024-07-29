<?php
/**
 * The template for displaying dates
 */
defined ( 'ABSPATH' ) || exit;

global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';

// Get available dates from booking
$availability = get_post_meta ( $product->get_id (), '_wc_booking_availability', true );

// Get the max_places ACF field value
$max_places = get_field ( 'max_places', $product->get_id () );

// Initialize the array to store future availability rows
$future_availability_rows = [];
$current_date             = new DateTime();

foreach ( $availability as $availabilityRange ) {
  $from_date = $availabilityRange[ 'from' ];
  $to_date   = $availabilityRange[ 'to' ];
  try {
    $opening_date = new DateTime( $from_date );
    $closing_date = new DateTime( $to_date );

    if ( $opening_date > $current_date ) {
      $future_availability_rows[] = [ 
        'from'             => $opening_date->format ( 'd/m/Y' ),
        'to'               => $closing_date->format ( 'd/m/Y' ),
        'location_name'    => $location_name,
        'data_day'         => $opening_date->format ( 'j' ),
        'data_month'       => $opening_date->format ( 'n' ),
        'data_year'        => $opening_date->format ( 'Y' ),
        'hidden_class'     => '',
        'available_spaces' => $max_places // Use max_places for total available slots
      ];
      }
    } catch ( Exception $e ) {
    error_log ( 'Invalid date format: ' . $e->getMessage () );
    }
  }

// Fetch bookings for the given product
$bookings = new WP_Query(
  array(
    'post_type'      => 'wc_booking',
    'posts_per_page' => -1,
    'post_status'    => array( 'paid', 'confirmed' ),
    'meta_query'     => array(
      array(
        'key'     => '_booking_product_id',
        'value'   => $product->get_id (),
        'compare' => '=',
      ),
    ),
  ),
);

// Initialize an array to store total persons count per start date
$total_persons_by_date = [];

// Loop through bookings and sum the persons count
if ( $bookings->have_posts () ) {
  while ( $bookings->have_posts () ) {
    $bookings->the_post ();

    $booking_id         = get_the_ID ();
    $booking_start      = get_post_meta ( $booking_id, '_booking_start', true );
    $persons_count_meta = get_post_meta ( $booking_id, '_booking_persons', true );

    $booking_start_date = new DateTime( $booking_start );
    $start_date_str     = $booking_start_date->format ( 'Y-m-d' );

    if ( ! isset ( $total_persons_by_date[ $start_date_str ] ) ) {
      $total_persons_by_date[ $start_date_str ] = 0;
      }

    // Sum all persons in this booking
    if ( is_array ( $persons_count_meta ) ) {
      $total_persons = array_sum ( $persons_count_meta );
      } else {
      $total_persons = (int) $persons_count_meta;
      }

    $total_persons_by_date[ $start_date_str ] += $total_persons;
    }
  // Reset post data
  wp_reset_postdata ();
  }

// Update available spaces in future availability rows
foreach ( $future_availability_rows as &$row ) {
  // Convert the 'from' date to 'Y-m-d' format for comparison
  $start_date_str = DateTime::createFromFormat ( 'd/m/Y', $row[ 'from' ] )->format ( 'Y-m-d' );

  // Check if this start date has any bookings and calculate remaining spaces
  if ( isset ( $total_persons_by_date[ $start_date_str ] ) ) {
    $row[ 'available_spaces' ] -= $total_persons_by_date[ $start_date_str ];
    }
  }
?>

<div class="course-step" id="step-2">
  <div class="step-title">
    <span class="title">Step 2 - Choose The Date</span>
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
                  <tr class="availability-date <?php echo esc_attr ( $row[ 'hidden_class' ] ); ?>"
                    data-start="<?php echo esc_attr ( DateTime::createFromFormat ( 'd/m/Y', $row[ 'from' ] )->format ( 'Y-m-d' ) ); ?>"
                    data-end="<?php echo esc_attr ( DateTime::createFromFormat ( 'd/m/Y', $row[ 'to' ] )->format ( 'Y-m-d' ) ); ?>">
                    <td><?php echo esc_html ( $row[ 'from' ] ); ?></td>
                    <td class="mobile-hide"><?php echo esc_html ( $row[ 'location_name' ] ); ?></td>
                    <td><?php echo esc_html ( $row[ 'available_spaces' ] ); ?></td>
                    <td class="add-td">
                      <a href="#" data-day="<?php echo esc_attr ( $row[ 'data_day' ] ); ?>"
                        data-month="<?php echo esc_attr ( $row[ 'data_month' ] ); ?>"
                        data-year="<?php echo esc_attr ( $row[ 'data_year' ] ); ?>"
                        class="cta-button book-now-button float-right">Select Date</a>
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