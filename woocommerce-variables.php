<?php
/**
 * @suppress PHP0417
 */
// Use the constants defined in wp-config.php
$consumer_key    = WC_BOOKINGS_CONSUMER_KEY;
$consumer_secret = WC_BOOKINGS_CONSUMER_SECRET;
$api_url         = WC_BOOKINGS_API_URL . 'wp-json/wc-bookings/v1/bookings';
$site_url        = 'https://mcl.local';

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

$durationType = get_post_meta ( $product->get_id (), '_wc_booking_duration_unit', true );
$durationTime = get_post_meta ( $product->get_id (), '_wc_booking_duration', true );