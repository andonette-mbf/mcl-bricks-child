<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 */
defined ( 'ABSPATH' ) || exit;
global $product;


// Debugging: Print the booking data
$bookings_data = get_booking_data ( $product->id );
echo '<pre>';
print_r ( $bookings_data );
echo '</pre>';


// Initialize necessary variables
echo "<div>success</div>";
$product_group_id                 = parent_grouped_id ( $product->id );
$parent_group                     = wc_get_product ( $product_group_id );
$durationType                     = get_post_meta ( $product->id, '_wc_booking_duration_unit', true );
$durationTime                     = get_post_meta ( $product->id, '_wc_booking_duration', true );
$display_title                    = get_field ( 'display_title' );
$course_duration_custom           = get_field ( 'course_duration_custom' );
$certification_meta               = get_field ( 'certification_meta', $product_group_id );
$hero_image                       = get_field ( 'hero_image' );
$location                         = get_field ( 'location' );
$main_telephone_number            = get_field ( 'main_telephone_number', 'options' );
$additional_date_information_json = get_field ( 'additional_date_information' );

// Additional variables for Course Selection
$course_title   = get_the_title ( $product_group_id );
$location       = get_field ( 'location' );
$location_label = '';
if ( is_array ( $location ) ) {
  $location_label = isset ( $location[ 'label' ] ) ? esc_html ( $location[ 'label' ] ) : 'Location data is not properly set';
  } else {
  $location_label = esc_html ( $location );
  }

// Confirm Venue variables
$cost_of_course = $product->get_price ();
$scrollStep     = isset ( $_GET[ 'scrollStep' ] ) ? $_GET[ 'scrollStep' ] : '';

// Step 2 - Dates
$availabilityInFuture = false;
$availability         = get_post_meta ( $product->id, '_wc_booking_availability' );
$availabilityTest     = array_filter ( $availability );


// Store availability dates and remaining spaces
$futureAvailabilityDates = [];
$current_date            = new DateTime();
$date_ranges             = calculate_remaining_spaces ( $product->id );


// Debugging: Print date ranges
echo '<pre>';
print_r ( $date_ranges );
echo '</pre>';

foreach ( $availabilityTest as $availabilityTestRange ) {
  foreach ( $availabilityTestRange as $availabilityTestRangeSingle ) {
    $from_date = $availabilityTestRangeSingle[ "from_date" ] ?? $availabilityTestRangeSingle[ "from" ];
    $to_date   = $availabilityTestRangeSingle[ "to_date" ] ?? $availabilityTestRangeSingle[ "to" ];

    $opening_date = new DateTime( $from_date );
    $closing_date = new DateTime( $to_date );

    if ( $opening_date > $current_date ) {
      $availabilityInFuture = true;

      // Get the remaining spaces for the date range
      $date_range_key   = $opening_date->format ( 'Y-m-d' ) . ' - ' . $closing_date->format ( 'Y-m-d' );
      $remaining_spaces = $date_ranges[ $date_range_key ] ?? 0;

      // Debugging: Print each date range key and remaining spaces
      echo "Template Date Range: $date_range_key, Remaining Spaces: $remaining_spaces\n";

      // Add to array to display in list
      $futureAvailabilityDates[] = [ 
        "from"             => $opening_date,
        "to"               => $closing_date,
        "remaining_spaces" => $remaining_spaces,
      ];
      }
    }
  }
//locations for form - 
$select_address       = get_field ( 'location' );
$select_address_value = $select_address[ 'value' ];
$select_address_label = $select_address[ 'choices' ][ $select_address_value ];
?>
<main id="brx-content">
  <div id="product-<?php the_ID (); ?>" <?php wc_product_class ( '', $product ); ?>>
    <section class="brxe-section brxe-wc-section mcl-hero">
      <div class="brxe-container mcl-flex--col mcl-hero__inner mcl-padding">
        <div class="brxe-div mcl-hero__content mcl-flex--col">
          <h1 class="brxe-heading mcl-hero__title">
            <?php echo $display_title ? $display_title : get_the_title (); ?>
          </h1>

          <h5>
            Duration:
            <?php if ( $course_duration_custom ) { ?>
              <span><?php echo $course_duration_custom; ?></span>
            <?php } else { ?>
              <span class="float-left w-100"><?php echo $durationTime; ?>   <?php echo $durationType; ?></span>
            <?php } ?>
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
    <!--Course Selection-->
    <section class="brxe-section brxe-wc-section training-course-product">
      <div class="brxe-container grid--1-3 gap--m">
        <!--sidebar-->
        <div class="training-sidebar">
          <div class="sidebar-inner">
            <div class="sidebar-selections">
              <h3 class="brxe-heading">Your Selection</h3>

              <div class="meta">
                <b>Course: </b><br>
                <span><?php echo $course_title; ?></span>
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
          <?php if ( $scrollStep ) : ?>
            <script type="text/javascript">
              jQuery(document).ready(function () {
                var scrollStep = "<?php echo $scrollStep; ?>";
                jQuery('html, body').animate({
                  scrollTop: jQuery(".training-course-steps .course-step#step-" + scrollStep).offset().top - 160
                }, 2000);
              });
            </script>
          <?php endif; ?>

          <div class="training-course-steps">
            <?php
            //Notices
            do_action ( 'woocommerce_before_single_product' ); ?>
            <div class="course-step" id="step-1">
              <div class="step-title"><span class="title">Step 1 - Choose Your Venue</span></div>
              <div>
                <?php
                $locations = $parent_group->get_children ();
                foreach ( $locations as $location ) {
                  ?>
                  <div class="step-field location">
                    <a class="<?php if ( $location === $product->id ) { ?>active<?php } ?>"
                      href="<?php echo get_permalink ( $location ); ?>?scrollStep=2">
                      <?php echo get_field ( 'select_address', $location ); ?>
                    </a>
                  </div>
                <?php } ?>
              </div>
            </div>

            <div class="course-step" id="step-2">
              <div class="step-title"><span class="title">Step 2 - Choose The Date</span>
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
                    <div class="table-section">
                      <?php if ( ! empty ( $futureAvailabilityDates ) ) : ?>
                        <table class="table">
                          <thead>
                            <tr>
                              <th>Start Date</th>
                              <th>Location</th>
                              <th>Availability</th>
                              <th class="add-td"></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ( $futureAvailabilityDates as $futureAvailabilityDate ) : ?>
                              <tr class="availability-date"
                                data-start="<?php echo esc_attr ( $futureAvailabilityDate[ 'from' ]->format ( 'd/m/Y' ) ); ?>"
                                data-end="<?php echo esc_attr ( $futureAvailabilityDate[ 'to' ]->format ( 'd/m/Y' ) ); ?>">
                                <td><?php echo esc_html ( $futureAvailabilityDate[ 'from' ]->format ( 'd/m/Y' ) ); ?></td>
                                <td>
                                  <?php
                                  switch ( $select_address_value ) {
                                    case 'grimsby':
                                      echo 'Humber Training Centre';
                                      break;
                                    case 'wandsworth':
                                      echo 'South London Training Centre';
                                      break;
                                    default:
                                      echo 'East London Training Centre';
                                      break;
                                    }
                                  ?>
                                </td>
                                <td>Bookings Available (<?php echo $futureAvailabilityDate[ 'remaining_spaces' ]; ?> spaces
                                  left)</td>
                                <td class="add-td">
                                  <a href="#"
                                    data-day="<?php echo esc_attr ( $futureAvailabilityDate[ 'from' ]->format ( 'j' ) ); ?>"
                                    data-month="<?php echo esc_attr ( $futureAvailabilityDate[ 'from' ]->format ( 'n' ) ); ?>"
                                    data-year="<?php echo esc_attr ( $futureAvailabilityDate[ 'from' ]->format ( 'Y' ) ); ?>"
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

                  <div class="layouts float-left w-100 position-relative" id="layout-calendar">
                    <?php
                    /**
                     * Hook: woocommerce_single_product_summary.
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_rating - 10
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_meta - 40
                     * @hooked woocommerce_template_single_sharing - 50
                     * @hooked WC_Structured_Data::generate_product_data() - 60
                     */
                    do_action ( 'woocommerce_single_product_summary' );
                    ?>
                  </div>
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

            <!--persons partial -->
            <div class="course-step" id="step-3">
              <div class="step-title">
                <span class="title">Step 3 - Select The Number Of People</span>
                <a href="#" data-step="3" class="previous-step">Previous step</a>
              </div>
              <input type="hidden" id="cost-of-course" value="<?php echo $product->get_price (); ?>" />
              <input type="hidden" id="multi-cost-of-course" value="" />
              <input type="hidden" id="changed-cost-of-course" value="" />

              <div class="step-field person-field">
                <a href="#" data-bookingPerson="1">1 Person <p class="price">(£<span
                      id="price-1"><?php echo $product->get_price (); ?> Inc VAT</span>)</p></a>
              </div>

              <div class="step-field person-field">
                <a href="#" data-bookingPerson="2">2 People <p class="price">(£<span
                      id="price-2"><?php echo $product->get_price () * 2; ?> Inc VAT</span>)</p></a>
              </div>

              <div class="step-field person-field">
                <a href="#" data-bookingPerson="3">3 People <p class="price">(£<span
                      id="price-3"><?php echo $product->get_price () * 3; ?> Inc VAT</span>)</p></a>
              </div>

              <div class="step-field person-field">
                <a href="#" data-bookingPerson="4">4 People <p class="price">(£<span
                      id="price-4"><?php echo $product->get_price () * 4; ?> Inc VAT</span>)</p></a>
              </div>

              <div class="step-field person-field">
                <a href="#" data-bookingPerson="5">5 People <p class="price">(£<span
                      id="price-5"><?php echo $product->get_price () * 5; ?> Inc VAT</span>)</p></a>
              </div>

              <div class="step-field person-dropdown">
                <a href="#" class="people-over-5-content">
                  5+ People
                </a>

                <div class="people-select-menu">
                  <select id="groupBookingMenu">
                    <option>Select No. People</option>
                    <option value="6" data-bookingPerson="6">6 People</option>
                    <option value="7" data-bookingPerson="7">7 People</option>
                    <option value="8" data-bookingPerson="8">8 People</option>
                    <option value="9" data-bookingPerson="9">9 People</option>
                    <option value="10" data-bookingPerson="10">10 People</option>
                  </select>
                </div>
              </div>

              <div id="delegate-details">
                <div class="title-row step-title">
                  <span class="title smaller-title to-animate">Please enter delegate information</span>
                </div>
                <?php

                // Check if the current product belongs to the 'irata' category
                if ( has_term ( 'irata-courses', 'product_cat' ) ) {
                  ?>
                  <div class="delegate-fields">
                    <div class="step-inputs-split">
                      <b>Delegate <span class="number">{X}</span> Information</b>
                      <input name="delegate_name[{X}]" data-number="{X}" required class="delegate_name" value=""
                        placeholder="Delegate {X} Name">
                      <select name="delegate_level_select[{X}]" data-number="{X}" required class="delegate_level_select">
                        <option value="" disabled selected>Irata Course Required</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                      </select>
                      <input name="delegate_number[{X}]" data-number="{X}" class="delegate_number" value=""
                        placeholder="Delegate {X} Number" style="display:none;">
                      <label style="color: black; font-weight: bold" for="delegate_dob_{X}">Delegate Date of Birth</label>
                      <input type="date" id="delegate_dob{X}" name="delegate_dob[{X}]" data-number="{X}" required
                        class="delegate_dob" value="" placeholder="Delegate {X} DOB">
                      <input type="text" id="delegate_phone{X}" name="delegate_phone[{X}]" data-number="{X}" required
                        class="delegate_phone" value="" placeholder="Delegate {X} Phone Number">
                      <input type="email" id="delegate_email{X}" name="delegate_email[{X}]" data-number="{X}" required
                        class="delegate_email" value="" placeholder="Delegate {X} Email">
                    </div>
                    <div class="outputted-fields"></div>
                    <span class="alert alert-danger float-left mt-4" id="delegate_info_error"
                      style="display: none;">Please enter all
                      delegate information.</span>
                  </div>
                  <script>
                    // Function to handle changes for delegate level select
                    jQuery('body').on('change', 'select.delegate_level_select', function () {
                      var fieldNumber = jQuery(this).attr('data-number');
                      var selectedValue = jQuery(this).val();

                      if (selectedValue === "2" || selectedValue === "3") {
                        jQuery('input[name="delegate_number[' + fieldNumber + ']"]').show();
                      } else {
                        jQuery('input[name="delegate_number[' + fieldNumber + ']"]').hide();
                      }
                    });

                  </script>
                  <?php
                  } else if ( has_term ( 'gwo-courses', 'product_cat' ) ) {
                  ?>
                    <div class="delegate-fields">
                      <div class="step-inputs-split">
                        <b>Delegate <span class="number">{X}</span> Information</b>
                        <input name="delegate_name[{X}]" data-number="{X}" required class="delegate_name" value=""
                          placeholder="Delegate {X} Name">
                        <input name="delegate_number[{X}]" data-number="{X}" required class="delegate_number" value=""
                          placeholder="Delegate {X} GWO Number">
                        <input type="date" name="delegate_dob[{X}]" data-number="{X}" required class="delegate_dob" value=""
                          placeholder="Delegate {X} DOB">
                      </div>
                      <div class="outputted-fields"></div>
                      <span class="alert alert-danger float-left mt-4" id="delegate_info_error"
                        style="display: none;">Please enter all
                        delegate information.</span>
                    </div>
                  <?php
                  } else {
                  ?>
                    <div class="delegate-fields">
                      <div class="step-inputs-split">
                        <b>Delegate <span class="number">{X}</span> Information</b>
                        <input name="delegate_name[{X}]" data-number="{X}" required class="delegate_name" value=""
                          placeholder="Delegate {X} Name">
                        <input type="date" name="delegate_dob[{X}]" data-number="{X}" required class="delegate_dob" value=""
                          placeholder="Delegate {X} DOB">
                      </div>
                      <div class="outputted-fields"></div>
                      <span class="alert alert-danger float-left mt-4" id="delegate_info_error"
                        style="display: none;">Please enter all
                        delegate information.</span>
                    </div>
                  <?
                  }
                ?>
              </div>
            </div>


            <!--review block partial -->
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
                <div class="meta"><b>Course Venue </b><span
                    class="title"><?php the_field ( 'select_address' ); ?></span>
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


          </div>
        </div>
      </div>
    </section>

    <?php if ( $product->is_type ( 'booking' ) ) : ?>
      <?php
      // Check for additional date information
      if ( $additional_date_information_json = get_field ( 'additional_date_information' ) ) {
        // Decode the JSON data
        $additional_dates_information = json_decode ( $additional_date_information_json, true );

        // Initialize an array for per-day information
        $additional_dates_information_per_day = [];

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
        ?>
        <script>
          jQuery(document).ready(function () {
            var additionalDateInfo = <?php echo json_encode ( $additional_dates_information_per_day ); ?>;

            function updateBookingInfo () {
              var bookingDateCheck = jQuery(".booking_date_year").val() + "-" +
                jQuery(".booking_date_month").val() + "-" +
                jQuery(".booking_date_day").val();
              var bookingPersons = jQuery("#wc_bookings_field_persons").val();
              var dateInfo = additionalDateInfo[ bookingDateCheck ];

              jQuery("#cannotBookCourse").hide();
              jQuery("#confirm-boooking").show();

              if (dateInfo !== undefined) {
                jQuery(".container-fluid.training-course-product").attr("data-spaces-remaining", dateInfo[ 'spaces_remain' ]);

                if (dateInfo[ 'call_to_book' ] === "yes") {
                  jQuery("#cannotBookCourse").html('Please call us to book this date on <br /> 0113 257 0842').slideDown();
                  jQuery("#confirm-boooking").slideUp();
                }

                if (parseInt(bookingPersons) > parseInt(dateInfo[ 'spaces_remain' ])) {
                  jQuery("#cannotBookCourse").html('We only have ' + dateInfo[ 'spaces_remain' ] + ' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842.').slideDown();
                  jQuery("#confirm-boooking").slideUp();
                }
              }
            }

            // Event listeners for booking date and persons change
            jQuery("body").on("click", ".calendar-list .table-section .table tbody td.add-td .book-now-button", updateBookingInfo);
            jQuery("#wc-bookings-booking-form.wc-bookings-booking-form").on("change", ".booking_date_day, #wc_bookings_field_persons", updateBookingInfo);
          });
        </script>
      <?php } ?>
    <?php endif; ?>



    <!--tabs partial -->
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
                <p><?php the_field ( 'course_details' ); ?></p>
              </div>
            </div>
            <div class="brxe-block tab-pane">
              <div class="brxe-text">
                <p><?php the_field ( 'included_in_course' ); ?></p>
              </div>
            </div>
            <div class="brxe-block tab-pane">
              <div class="brxe-text">
                <p><?php the_field ( 'pre_training_requirements' ); ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--related courses partial -->

    <section class="brxe-section brxe-wc-section related-products">
      <div class="related-title">
        <?php
        $training_courses_id = get_term_by ( 'slug', 'training-courses', 'product_cat' );
        $terms               = get_the_terms ( $product->id, 'product_cat' );
        foreach ( $terms as $term ) {
          if ( $term->parent === $training_courses_id->term_id ) {
            $cat_name_first = $term->name;
            break;
            }
          } ?>

        <span class="title to-animate">Other <?php echo $cat_name_first; ?> you may be interested in</span>

        <a href="<?php echo get_term_link ( 'training-courses', 'product_cat' ); ?>" class="float-right title-link">See
          all
          training courses</a>
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
</main>

<?php do_action ( 'woocommerce_after_single_product' ); ?>