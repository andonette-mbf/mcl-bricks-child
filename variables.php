<?php
defined ( 'ABSPATH' ) || exit;
global $product;
/**
 * @suppress PHP0415
 */
// Use the constants defined in wp-config.php
$consumer_key    = WC_BOOKINGS_CONSUMER_KEY;
$consumer_secret = WC_BOOKINGS_CONSUMER_SECRET;
$api_url         = WC_BOOKINGS_API_URL . 'wp-json/wc-bookings/v1/bookings';
$site_url        = 'https://mcl.local';

// Initialize common variables
$product_group_id = $product->is_type ( 'booking' ) ? parent_grouped_id ( $product->get_id () ) : $product->get_id ();
$parent_group     = wc_get_product ( $product_group_id );
$durationType     = get_post_meta ( $product->get_id (), '_wc_booking_duration_unit', true );
$duarionTime      = get_post_meta ( $product->get_id (), '_wc_booking_duration', true );
/**
 * @suppress PHP0417
 */
//Hero Variables
$grants_funding         = '';
$product_id             = $product->get_id ();
$display_title          = get_field ( 'display_title' );
$course_duration_custom = get_field ( 'number_of_days' );
$certification_meta     = get_field ( 'certification_meta', $product_group_id );
$hero_image             = get_field ( 'hero_image' );
$duration               = $course_duration_custom ? $course_duration_custom : $duarionTime[ 0 ] . ' ' . $duarionType[ 0 ];
$grants_funding_options = $grants_funding ? 'Options available' : 'Options not available';

//Sidebar Summary Variables 
$product_group_title = get_the_title ( $product_group_id );
$location            = get_field ( 'location' );
$location_label      = is_array ( $location ) && isset ( $location[ 'label' ] ) ? $location[ 'label' ] : ( is_array ( $location ) ? 'Location data is not properly set' : esc_html ( $location ) );

//GET THE DATES
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

//step 1 for locations 
$locations          = $parent_group->get_children ();
$current_product_id = $product->get_id ();
$location_data      = [];

foreach ( $locations as $location ) {
  $location_data[] = [ 
    'id'        => $location,
    'permalink' => get_permalink ( $location ),
    'address'   => get_field ( 'select_address', $location ),
    'is_active' => ( $location === $current_product_id ),
  ];
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
//Step 3 variables
$product_price = $product->get_price ();
$prices        = [ 
  1 => $product_price,
  2 => $product_price * 2,
  3 => $product_price * 3,
  4 => $product_price * 4,
  5 => $product_price * 5,
];

$delegate_info_type = '';
if ( has_term ( 'irata-courses', 'product_cat' ) ) {
  $delegate_info_type = 'irata';
  } elseif ( has_term ( 'gwo-courses', 'product_cat' ) ) {
  $delegate_info_type = 'gwo';
  }
//step 4 
$product_group_title   = get_the_title ( $product_group_id );
$duration_time         = $duarionTime[ 0 ];
$duration_type         = $durationType[ 0 ];
$select_address        = get_field ( 'select_address' );
$main_telephone_number = get_field ( 'main_telephone_number', 'options' );

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
$course_details            = get_field ( 'course_details' );
$included_in_course        = get_field ( 'included_in_course' );
$pre_training_requirements = get_field ( 'pre_training_requirements' );
$training_courses_id       = get_term_by ( 'slug', 'training-courses', 'product_cat' );
$terms                     = get_the_terms ( $product->get_id (), 'product_cat' );
$cat_name_first            = '';

//manual date table ACF fields
$manual_dates = get_field ( 'manual_dates' );

if ( $manual_dates ) {
  $first_row      = $manual_dates[ 0 ];
  $acf_start_date = $first_row[ 'start_date' ];
  $acf_end_date   = $first_row[ 'end_date' ];
  $acf_places     = $first_row[ 'available_spaces' ];
  $acf_full       = $first_row[ 'course_full' ];
  }

if ( $terms ) {
  foreach ( $terms as $term ) {
    if ( $term->parent === $training_courses_id->term_id ) {
      $cat_name_first = $term->name;
      break;
      }
    }
  }

$training_courses_link = get_term_link ( 'training-courses', 'product_cat' );