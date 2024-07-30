<?php
/**
 * @suppress PHP0417
 */
// Use the constants defined in wp-config.php
$consumer_key    = WC_BOOKINGS_CONSUMER_KEY;
$consumer_secret = WC_BOOKINGS_CONSUMER_SECRET;
$api_url         = WC_BOOKINGS_API_URL . 'wp-json/wc-bookings/v1/bookings';
$site_url        = home_url ();

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

$product_id          = $product->get_id ();
$product_group_id    = $product->is_type ( 'booking' ) ? parent_grouped_id ( $product->get_id () ) : $product->get_id ();
$product_group_title = get_the_title ( $product_group_id );
$product_cat         = get_the_terms ( $product_id, 'product_cat' );
$parent_group        = wc_get_product ( $product_group_id );

$display_title = get_field ( 'display_title' );
$location      = get_field ( 'location' );

//availability determines what blocks show in the template
// Store availability dates
$future_availability_rows = [];
$current_date             = new DateTime();
$select_address           = get_field ( 'location' );
$select_address_value     = $select_address[ 'value' ];

// Determine location name based on the selected address value
$location_name = '';
switch ( $select_address_value ) {
  case 'grimsby':
    $location_name = 'Humber Training Centre';
    $location_venue = 'Humber Training Centre';
    break;
  case 'wandsworth':
    $location_name = 'South London Training Centre';
    $location_venue = 'South London Training Centre';
    break;
  default:
    $location_name = 'East London Training Centre';
    $location_venue = 'East London Training Centre';
    break;
  }
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

