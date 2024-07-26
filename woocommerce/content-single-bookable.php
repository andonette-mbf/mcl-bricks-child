<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 */
defined ( 'ABSPATH' ) || exit;
global $product;
if ( $product ) {
  include get_stylesheet_directory () . '/variables.php';
  }
// Include the WooCommerce API library if it's not already included
if ( ! class_exists ( 'WooCommerce' ) ) {
  require_once ( dirname ( __FILE__ ) . '/woocommerce/woocommerce.php' );
  }
?>
<?php
// Function to get the maximum person count for a specific booking ID
function get_max_person_count ( $bookings, $booking_id ) {
  foreach ( $bookings as $booking ) {
    if ( $booking[ 'id' ] == $booking_id ) {
      if ( isset ( $booking[ 'person_counts' ] ) && is_array ( $booking[ 'person_counts' ] ) ) {
        return max ( $booking[ 'person_counts' ] );
        } else {
        return 'N/A';
        }
      }
    }
  return 'Booking ID not found';
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

// Check for errors
if ( is_wp_error ( $response ) ) {
  echo 'Error: ' . $response->get_error_message ();
  } else {
  // Retrieve and display the response code and body for debugging
  $response_code = wp_remote_retrieve_response_code ( $response );
  $body          = wp_remote_retrieve_body ( $response );

  echo 'Response Code: ' . $response_code . '<br>';

  // Decode the JSON response body
  $bookings = json_decode ( $body, true );

  // Display bookings
  if ( ! empty ( $bookings ) ) {
    echo '<h2>Bookings:</h2>';
    echo '<ul>';
    foreach ( $bookings as $booking ) {
      echo '<li>';
      echo 'ID: ' . $booking[ 'id' ] . '<br>';
      echo 'Cost: ' . $booking[ 'cost' ] . '<br>';
      echo 'Customer ID: ' . $booking[ 'customer_id' ] . '<br>';
      echo 'Status: ' . $booking[ 'status' ] . '<br>';
      echo 'Start Date: ' . date ( 'Y-m-d H:i:s', $booking[ 'start' ] ) . '<br>';
      echo 'End Date: ' . date ( 'Y-m-d H:i:s', $booking[ 'end' ] ) . '<br>';
      echo 'Product ID: ' . $booking[ 'product_id' ] . '<br>';

      // Display person count
      if ( isset ( $booking[ 'person_counts' ] ) && is_array ( $booking[ 'person_counts' ] ) ) {
        echo 'Person Count: ' . array_sum ( $booking[ 'person_counts' ] ) . '<br>';
        echo 'Max Person Count: ' . max ( $booking[ 'person_counts' ] ) . '<br>';
        } else {
        echo 'Person Count: N/A<br>';
        }

      echo '</li>';
      }
    echo '</ul>';

    // Example: Find the max person count for a specific booking ID
    $specific_booking_id = 33991; // Change this to the booking ID you want to check
    $max_person_count    = get_max_person_count ( $bookings, $specific_booking_id );
    echo '<h3>Max Person Count for Booking ID ' . $specific_booking_id . ': ' . $max_person_count . '</h3>';

    } else {
    echo 'No bookings found.';
    }
  }
?>

<main id="brx-content">
  <div id="product-<?php the_ID (); ?>" <?php wc_product_class ( '', $product ); ?>>

    <!--Header Section -->
    <section class="brxe-section brxe-wc-section mcl-hero">
      <div class="brxe-container mcl-flex--col mcl-hero__inner mcl-padding">
        <div class="brxe-div mcl-hero__content mcl-flex--col">
          <h1 class="brxe-heading mcl-hero__title"><?php echo $display_title; ?></h1>

          <h5>
            Duration: <span><?php echo $duration; ?></span>
            <br>
            Certification: <?php echo $certification_meta; ?>
            <br>
            Availability: See dates below
          </h5>
        </div>
      </div>
      <div class="brxe-container mcl-hero__inner--absolute">

        <div class="brxe-div mcl-hero__overlay mcl-absolute--full"></div>
        <div class="brxe-div mcl-hero__image" style="background-image: url('<?php echo $hero_image; ?>');"></div>
        <div class="brxe-div mcl-hero__tagline-box">
          <h3 class="brxe-heading mcl-hero__tagline-box-heading">We've<br>got<br>you.</h3>
        </div>
      </div>
    </section>
    <section class="brxe-section brxe-wc-section">
      <div class="brxe-container">
        <?php wc_print_notices (); // Add this to display WooCommerce notices ?>
      </div>
    </section>

    <!--Course Selection-->
    <section class="brxe-section brxe-wc-section training-course-product">

      <div class="brxe-container grid--1-3 gap--m">
        <!--Sidebar Summary-->
        <div class="training-sidebar">
          <div class="sidebar-inner">
            <div class="sidebar-selections">
              <h3 class="brxe-heading">Your Selection</h3>
              <!-- meta class uses js -->
              <div class="meta">
                <b>Course: </b><br>
                <span><?php echo $product_group_title; ?></span>
              </div>

              <div class="meta">
                <b>Venue</b>
                <span>
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
            <?php
            //Notices
            do_action ( 'woocommerce_before_single_product' );
            ?>

            <div class="course-step" id="step-1">
              <div class="step-title"><span class="title">Step 1 - Choose Your Venue</span></div>
              <div>
                <?php foreach ( $location_data as $location ) : ?>
                  <div class="step-field location">
                    <a class="<?php echo $location[ 'is_active' ] ? 'active' : ''; ?>"
                      href="<?php echo $location[ 'permalink' ]; ?>?scrollStep=2">
                      <?php echo $location[ 'address' ]; ?>
                    </a>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
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
                                  <td><?php echo esc_html ( $available_spaces ); ?></td>
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
                    <?php
                    do_action ( 'woocommerce_single_product_summary' );
                    ?>
                  </div>
                </div>
              </div>

              <div class="brxe-block mcl-dates">
                <div class="brxe-div mcl-flex--col">
                  <h3 class="brxe-heading mcl-dates__heading">Can't see your desired date above? Get in touch with
                    us!&nbsp;</h3>
                </div>
                <div class="brxe-div mcl-flex--col">
                  <a class="brxe-button mcl-btn--white mcl-btn bricks-button" href="#contact">Contact Us</a>
                </div>
              </div>

              <!--Choose number-->

              <div class="course-step" id="step-3">
                <div class="step-title">
                  <span class="title">Step 3 - Select The Number Of People</span>
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

                <div id="delegate-details">
                  <div class="title-row step-title">
                    <span class="title smaller-title to-animate">Please enter delegate information</span>
                  </div>

                  <?php if ( $delegate_info_type === 'irata' ) : ?>
                    <div class="delegate-fields">
                      <div class="step-inputs-split">
                        <b>Delegate <span class="number">{X}</span> Information</b>
                        <input name="delegate_name[{X}]" data-number="{X}" required class="delegate_name" value=""
                          placeholder="Delegate {X} Name">
                        <select name="delegate_level_select[{X}]" data-number="{X}" required class="delegate_level_select">
                          <option value="" disabled selected>IRATA Course Required</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                        </select>
                        <input name="delegate_number[{X}]" data-number="{X}" class="delegate_number" value=""
                          placeholder="Delegate {X} IRATA Number" style="display:none;">
                        <label style="color: black; font-weight: bold" for="delegate_dob[{X}]">Delegate Date of
                          Birth</label>
                        <input type="date" id="delegate_dob{X}" name="delegate_dob[{X}]" data-number="{X}" required
                          class="delegate_dob" value="" placeholder="Delegate {X} DOB">
                        <input type="text" id="delegate_phone{X}" name="delegate_phone[{X}]" data-number="{X}" required
                          class="delegate_phone" value="" placeholder="Delegate {X} Phone Number">
                        <input type="email" id="delegate_email{X}" name="delegate_email[{X}]" data-number="{X}" required
                          class="delegate_email" value="" placeholder="Delegate {X} Email">
                      </div>
                      <div class="outputted-fields"></div>
                      <span class="alert alert-danger float-left mt-4" id="delegate_info_error"
                        style="display: none;">Please enter all delegate information.</span>
                    </div>
                    <script>
                      jQuery('body').on('change', 'select.delegate_level_select', function () {
                        var fieldNumber = jQuery(this).attr('data-number');
                        var selectedValue = jQuery(this).val();
                        var delegateNumberField = jQuery('input[name="delegate_number[' + fieldNumber + ']"]');

                        if (selectedValue === "2" || selectedValue === "3") {
                          delegateNumberField.show();
                          delegateNumberField.attr('required', 'required');
                        } else {
                          delegateNumberField.hide();
                          delegateNumberField.removeAttr('required');
                        }
                      });
                    </script>
                  <?php elseif ( $delegate_info_type === 'gwo' ) : ?>
                    <div class="delegate-fields">
                      <div class="step-inputs-split">
                        <b>Delegate <span class="number">{X}</span> Information</b>
                        <input name="delegate_name[{X}]" data-number="{X}" required class="delegate_name" value=""
                          placeholder="Delegate {X} Name">
                        <input name="delegate_number[{X}]" data-number="{X}" required class="delegate_number" value=""
                          placeholder="Delegate {X} WINDA Number">
                        <label style="color: black; font-weight: bold" for="delegate_dob[{X}]">Delegate Date of
                          Birth</label>
                        <input type="date" name="delegate_dob[{X}]" data-number="{X}" required class="delegate_dob" value=""
                          placeholder="Delegate {X} DOB">
                      </div>
                      <div class="outputted-fields"></div>
                      <span class="alert alert-danger float-left mt-4" id="delegate_info_error"
                        style="display: none;">Please enter all delegate information.</span>
                    </div>
                  <?php else : ?>
                    <div class="delegate-fields">
                      <div class="step-inputs-split">
                        <b>Delegate <span class="number">{X}</span> Information</b>
                        <input name="delegate_name[{X}]" data-number="{X}" required class="delegate_name" value=""
                          placeholder="Delegate {X} Name">
                        <label style="color: black; font-weight: bold" for="delegate_dob[{X}]">Delegate Date of
                          Birth</label>
                        <input type="date" name="delegate_dob[{X}]" data-number="{X}" required class="delegate_dob" value=""
                          placeholder="Delegate {X} DOB">
                      </div>
                      <div class="outputted-fields"></div>
                      <span class="alert alert-danger float-left mt-4" id="delegate_info_error"
                        style="display: none;">Please enter all delegate information.</span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>


              <!--review block partial -->
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
                  <a href="tel:<?php echo $main_telephone_number; ?>" id="cannotBookCourse"
                    class="cta-button float-right w-auto" style="display: none;"></a>
                </div>
              <?php else : ?>
                <div class="brxe-block mcl-dates">
                  <div class="brxe-div mcl-flex--col">
                    <h3 class="brxe-heading mcl-dates__heading">Course fully booked. please try another venue or call us
                      for upcoming dates
                    </h3>
                  </div>
                  <div class="brxe-div mcl-flex--col">
                    <a class="brxe-button mcl-btn--white mcl-btn bricks-button" href="#contact">Contact Us</a>
                  </div>
                </div>
              <?php endif; ?>

            </div>


          </div>

        </div>
      </div>
    </section>

    <!--tabs section -->
    <section class="brxe-section brxe-wc-section">
      <div class="brxe-container">
        <div id="brxe-tabs" data-script-id="tabs" class="brxe-tabs-nested">
          <div id="brxe-pljtos" class="brxe-block tab-menu">
            <div class="brxe-div tab-title brx-open">
              <div class="brxe-text-basic">Course Details</div>
            </div>
            <div class="brxe-div tab-title">
              <div class="brxe-text-basic">Included In Course</div>
            </div>
            <div class="brxe-div tab-title">
              <div class="brxe-text-basic">Pre Training Requirements</div>
            </div>
          </div>
          <div class="brxe-block tab-content">
            <div class="brxe-block tab-pane fade active show brx-open">
              <div class="brxe-text">
                <p><?php echo $course_details; ?></p>
              </div>
            </div>
            <div class="brxe-block tab-pane">
              <div class="brxe-text">
                <p><?php echo $included_in_course; ?></p>
              </div>
            </div>
            <div class="brxe-block tab-pane">
              <div class="brxe-text">
                <p><?php echo $pre_training_requirements; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--related courses partial -->

    <section class="brxe-section brxe-wc-section related-products">
      <div class="related-title">
        <span class="title to-animate">Other <?php echo $cat_name_first; ?> you may be interested in</span>
        <a href="<?php echo $training_courses_link; ?>" class="float-right title-link">See all training courses</a>
      </div>

      <?php
      /**
       * Hook: woocommerce_after_single_product_summary.
       *
       * @hooked woocommerce_output_product_data_tabs - 10
       * @hooked woocommerce_upsell_display - 15
       * @hooked woocommerce_output_related_products - 20
       */
      do_action ( 'woocommerce_after_single_product_summary' );
      ?>
    </section>
  </div>
</main>

<?php do_action ( 'woocommerce_after_single_product' ); ?>