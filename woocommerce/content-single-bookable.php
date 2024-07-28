<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 */
defined ( 'ABSPATH' ) || exit;
global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';

/**
 * @suppress PHP0417
 */
//Get available dates from booking 
$availabilityInFuture = false;
$availability         = get_post_meta ( $product->get_id (), '_wc_booking_availability' );
$availabilityTest     = array_filter ( $availability );
// Store availability dates
$futureAvailabilityDates = [];
$current_date            = new DateTime();

// Loop through and check dates in the future
foreach ( $availabilityTest as $availabilityTestRange ) {
  foreach ( $availabilityTestRange as $availabilityTestRangeSingle ) {
    // Determine the opening and closing dates
    $opening_date = new DateTime( ! empty ( $availabilityTestRangeSingle[ "from_date" ] ) ? $availabilityTestRangeSingle[ "from_date" ] : $availabilityTestRangeSingle[ "from" ] );
    $closing_date = new DateTime( ! empty ( $availabilityTestRangeSingle[ "to_date" ] ) ? $availabilityTestRangeSingle[ "to_date" ] : $availabilityTestRangeSingle[ "to" ] );

    // Check if the opening date is in the future
    if ( $opening_date > $current_date ) {
      $availabilityInFuture = true;

      // Add to array to display in list
      $futureAvailabilityDates[] = [ 
        "from" => $opening_date,
        "to"   => $closing_date,
      ];
      }
    }
  }


//STep 2 for date table 
$select_address       = get_field ( 'location' );
$select_address_value = $select_address[ 'value' ];
//$select_address_label = $select_address[ 'choices' ][ $select_address_value ];

$future_availability_rows = [];
if ( ! empty ( $futureAvailabilityDates ) ) {
  $count = 0;
  foreach ( $futureAvailabilityDates as $futureAvailabilityDate ) {
    $count++;
    $hidden_class = $count > 6 ? 'hidden-row' : '';
    $from_date    = $futureAvailabilityDate[ 'from' ]->format ( 'd/m/Y' );
    $to_date      = $futureAvailabilityDate[ 'to' ]->format ( 'd/m/Y' );
    $data_day     = $futureAvailabilityDate[ 'from' ]->format ( 'j' );
    $data_month   = $futureAvailabilityDate[ 'from' ]->format ( 'n' );
    $data_year    = $futureAvailabilityDate[ 'from' ]->format ( 'Y' );

    $location_name = '';
    switch ( $select_address_value ) {
      case 'grimsby':
        $location_name = 'Humber Training Centre';
        break;
      case 'wandsworth':
        $location_name = 'South London Training Centre';
        break;
      default:
        $location_name = 'East London Training Centre';
        break;
      }

    $future_availability_rows[] = [ 
      'hidden_class'  => $hidden_class,
      'from_date'     => $from_date,
      'to_date'       => $to_date,
      'location_name' => $location_name,
      'data_day'      => $data_day,
      'data_month'    => $data_month,
      'data_year'     => $data_year,
    ];
    }
  }

//step 4 

$additional_dates_information_per_day = [];

if ( $additional_date_information_json = get_field ( 'additional_date_information' ) ) {
  // Decode the JSON data
  $additional_dates_information = json_decode ( $additional_date_information_json, true );

  foreach ( $additional_dates_information as $additional_date_information ) {
    if ( $additional_date_information[ 'from' ] == $additional_date_information[ 'to' ] ) {
      // Single day course
      $date                                                    = new DateTime( $additional_date_information[ 'to' ] );
      $formatted_date                                          = $date->format ( 'Y-m-d' );
      $additional_dates_information_per_day[ $formatted_date ] = [ 
        'spaces_remain' => $additional_date_information[ 'spaces_remain' ],
        'call_to_book'  => $additional_date_information[ 'call_to_book' ],
      ];
      } else {
      // Multi-day course
      $period = new DatePeriod(
        new DateTime( $additional_date_information[ 'from' ] ),
        new DateInterval( 'P1D' ),
        ( new DateTime( $additional_date_information[ 'to' ] ) )->modify ( '+1 day' ) // include end date
      );

      foreach ( $period as $date ) {
        $formatted_date                                          = $date->format ( 'Y-m-d' );
        $additional_dates_information_per_day[ $formatted_date ] = [ 
          'spaces_remain' => $additional_date_information[ 'spaces_remain' ],
          'call_to_book'  => $additional_date_information[ 'call_to_book' ],
        ];
        }
      }
    }
  }
//tabs


$training_courses_id = get_term_by ( 'slug', 'training-courses', 'product_cat' );
$product_cat         = get_the_terms ( $product_id, 'product_cat' );
$cat_name_first      = '';

//manual date table ACF fields
$manual_dates = get_field ( 'manual_dates' );

if ( $manual_dates ) {
  $first_row      = $manual_dates[ 0 ];
  $acf_start_date = $first_row[ 'start_date' ];
  $acf_end_date   = $first_row[ 'end_date' ];
  $acf_places     = $first_row[ 'available_spaces' ];
  $acf_full       = $first_row[ 'course_full' ];
  }


// Set up the authentication header
$headers = array(
  'Authorization' => 'Basic ' . base64_encode ( $consumer_key . ':' . $consumer_secret ),
);

// Define the API endpoint
$api_endpoint = $site_url . '/wp-json/wc-bookings/v1/bookings';

// Make the request
$response = wp_remote_get (
  $api_endpoint,
  array(
    'headers' => $headers,
  ),
);

// Decode the JSON response body
$json_bookings = json_decode ( wp_remote_retrieve_body ( $response ), true );

// Store person counts in a variable
$person_count = 0;

foreach ( $json_bookings as $booking ) {
  if ( isset ( $booking[ 'person_counts' ] ) && is_array ( $booking[ 'person_counts' ] ) ) {
    $person_count = array_sum ( $booking[ 'person_counts' ] );
    // Only need the person count once, so break after first
    }
  }


// Perform the subtraction and print the result
$result_count = 8 - $person_count;
echo $result_count;


?>
<?php

// Check if bookings are not empty
if ( empty ( $json_bookings ) ) {
  echo 'No bookings found.';
  } else {
  // Loop through each availability row
  foreach ( $future_availability_rows as $row ) {
    // Initialize person count for each date range
    $person_count = 0;

    // Convert availability dates to timestamps
    $from_date = strtotime ( $row[ 'from_date' ] );
    $to_date   = strtotime ( $row[ 'to_date' ] );

    foreach ( $json_bookings as $booking ) {
      // Convert booking dates to timestamps
      $booking_start = isset ( $booking[ 'start' ] ) ? strtotime ( $booking[ 'start' ] ) : null;
      $booking_end   = isset ( $booking[ 'end' ] ) ? strtotime ( $booking[ 'end' ] ) : null;

      // Check if booking is within the date range
      if ( $booking_start && $booking_end && $booking_start >= $from_date && $booking_end <= $to_date ) {

        }
      }

    // Perform the subtraction and print the result for each date range

    echo "From: " . esc_attr ( $row[ 'from_date' ] ) . " To: " . esc_attr ( $row[ 'to_date' ] ) . "<br>";
    }
  }
?>
<?php
function get_bookings_by_product_id ( $product_id ) {
  $args = array(
    'post_type'      => 'wc_booking',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'meta_query'     => array(
      array(
        'key'     => '_booking_product_id',
        'value'   => $product_id,
        'compare' => '=',
        'type'    => 'NUMERIC',
      ),
    ),
  );

  $course_bookings = new WP_Query( $args );
  return $course_bookings->posts;
  }

$bookings = get_bookings_by_product_id ( $product_id );
// Assuming $bookings is already declared and contains the necessary data
if ( ! empty ( $bookings ) ) {
  echo '<ul>';
  foreach ( $bookings as $booking ) {
    $booking_id         = $booking->ID;
    $booking_start      = get_post_meta ( $booking_id, '_booking_start', true );
    $booking_end        = get_post_meta ( $booking_id, '_booking_end', true );
    $customer_id        = get_post_meta ( $booking_id, '_booking_customer_id', true );
    $customer           = get_userdata ( $customer_id );
    $persons_count_meta = get_post_meta ( $booking_id, '_booking_persons', true );

    // Calculate the total persons count
    $persons_count = 0;
    if ( is_array ( $persons_count_meta ) ) {
      $persons_count = array_sum ( $persons_count_meta );
      }

    echo '<li>';
    echo 'Booking ID: ' . $booking_id . '<br>';
    echo 'Start: ' . date ( 'Y-m-d H:i:s', strtotime ( $booking_start ) ) . '<br>';
    echo 'End: ' . date ( 'Y-m-d H:i:s', strtotime ( $booking_end ) ) . '<br>';
    //secho 'Customer: ' . ( $customer ? $customer->display_name : 'N/A' ) . '<br>';
    echo 'Persons Count: ' . ( $persons_count > 0 ? $persons_count : 'N/A' );
    echo '</li>';
    }
  echo '</ul>';
  } else {
  echo 'No bookings found for this product.';
  }
?>
<main id="brx-content">
  <div id="product-<?php the_ID (); ?>" <?php
     /**
      * @suppress PHP0417
      */
     wc_product_class ( '', $product ); ?>>

    <!--Header Section -->
    <?php get_template_part ( 'woocommerce/template-parts/block', 'hero' ); ?>
    <section class="brxe-section brxe-wc-section">
      <div class="brxe-container">
        <?php wc_print_notices (); // Add this to display WooCommerce notices ?>
      </div>
    </section>

    <!--Course Selection-->
    <section class="brxe-section brxe-wc-section training-course-product">

      <div class="brxe-container grid--1-3 gap--m">
        <!--Sidebar Summary-->
        <?php get_template_part ( 'woocommerce/template-parts/block', 'sidebar' ); ?>
        <div class="brxe-block">
          <h3 class="brxe-heading">Confirm Venue</h3>
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

            <?php if ( ! empty ( $future_availability_rows ) ) : ?>
              <div class="course-step" id="step-2">
                <div class="step-title">
                  <span class="title">Step 2 - Choose The Date</span>
                  <a href="#" data-step="2" class="previous-step"></a>
                </div>

                <div class="course-layout-select">
                  <div class="toggle-style-block">
                    <a href="#" class="title course-style">
                      <span class="toggle-label active">List</span>
                      <span class="toggle-identifier"></span>
                      <span class="toggle-label">Calendar</span>
                    </a>
                  </div>
                </div>

                <div class="step-layouts">
                  <div class="layouts" id="layout-list">
                    <div class="calendar-list">

                      <?php
                      // Assuming $future_availability_rows is fetched and contains data
// Fetch the manual_dates repeater field data for the current product
                      $manual_dates          = get_field ( 'manual_dates' );
                      $available_spaces_list = [];

                      // Create a list of available spaces in order and check the dates
                      $current_date = date ( 'Ymd' ); // Get the current date in 'YYYYMMDD' format
                    
                      if ( $manual_dates ) {
                        foreach ( $manual_dates as $row ) {
                          $start_date = $row[ 'start_date' ]; // Assuming 'start_date' is in 'YYYYMMDD' format
                          if ( $start_date >= $current_date ) { // Check if the start date is today or in the future
                            $available_spaces_list[] = $row[ 'available_spaces' ];
                            }
                          }
                        }
                      ?>

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
                              <?php
                              $i = 0; // Initialize a counter to access available spaces
                              foreach ( $future_availability_rows as $row ) :
                                $available_spaces = isset ( $available_spaces_list[ $i ] ) ? $available_spaces_list[ $i ] : 'N/A';
                                $i++; // Increment the counter
                                ?>
                                <tr class="availability-date <?php echo esc_attr ( $row[ 'hidden_class' ] ); ?>"
                                  data-start="<?php echo esc_attr ( $row[ 'from_date' ] ); ?>"
                                  data-end="<?php echo esc_attr ( $row[ 'to_date' ] ); ?>">
                                  <td><?php echo esc_html ( $row[ 'from_date' ] ); ?></td>
                                  <td class="mobile-hide"><?php echo esc_html ( $row[ 'location_name' ] ); ?></td>
                                  <td><?php echo esc_html ( $available_spaces ); ?><?php echo esc_html ( $result_count ); ?>
                                  </td>
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
              <!--contact partial -->
              <?php get_template_part ( 'woocommerce/template-parts/block', 'contact' ); ?>
              <!--People select partial-->
              <?php get_template_part ( 'woocommerce/template-parts/block', 'persons' ); ?>
              <!--review block partial -->
              <?php get_template_part ( 'woocommerce/template-parts/block', 'review' ); ?>
            <?php else : ?>
              <!--course full partial -->
              <?php get_template_part ( 'woocommerce/template-parts/block', 'full' ); ?>
            <?php endif; ?>
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