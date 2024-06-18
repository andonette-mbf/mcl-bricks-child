<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 */

defined ( 'ABSPATH' ) || exit;

global $product;

if ( post_password_required () ) {
  echo get_the_password_form (); // WPCS: XSS ok.
  return;
  }
?>

<main id="brx-content">
  <div id="product-<?php the_ID (); ?>" <?php wc_product_class ( '', $product ); ?>>
    <?php
    if ( has_term ( 'training-courses', 'product_cat' ) ) {
      // Initialize common variables
      $product_group_id = $product->is_type ( 'booking' ) ? parent_grouped_id ( $product->id ) : $product->id;
      $parent_group     = wc_get_product ( $product_group_id );
      $comments         = get_comments ( array( 'post_id' => $product_group_id ) );

      if ( $product->is_type ( 'booking' ) ) {
        $duarionType = get_post_meta ( $product->id, '_wc_booking_duration_unit', true );
        $duarionTime = get_post_meta ( $product->id, '_wc_booking_duration', true );
        $price_2023  = get_field ( '2023_price', $product->id );
        }
      ?>

      <!--Header section partial -->
      <?php get_template_part ( 'woocommerce/template-parts/template', 'header' ); ?>

      <!--Course Selection-->
      <section class="brxe-section brxe-wc-section training-course-product">
        <div class="brxe-container grid--1-3 gap--m">
          <!--sidebar partial -->
          <?php get_template_part ( 'woocommerce/template-parts/template', 'sidebar' ); ?>

          <div class="brxe-block">
            <h3 class="brxe-heading">Confirm Venue</h3>
            <?php if ( $product->is_type ( 'booking' ) ) { ?>
              <?php get_template_part ( 'woocommerce/template-parts/template', 'location' ); ?>

              <?php
              $availabilityInFuture = false;
              $availability         = get_post_meta ( $product->id, '_wc_booking_availability' );
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
              ?>

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
                          href="<?php echo get_permalink ( $location ); ?>?scrollStep=2"><?php echo get_field ( 'select_address', $location ); ?></a>
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
                        <span class="toggle-label">Calendar</span></a>
                    </div>
                  </div>

                  <div class="step-layouts">
                    <div class="layouts" id="layout-list">
                      <div class="calendar-list">
                        <div class="table-section">
                          <?php
                          $select_address       = get_field ( 'location' );
                          $select_address_value = $select_address[ 'value' ];
                          $select_address_label = $select_address[ 'choices' ][ $select_address_value ];
                          if ( ! empty ( $futureAvailabilityDates ) ) : ?>
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
                                <?php
                                $count = 0;
                                foreach ( $futureAvailabilityDates as $futureAvailabilityDate ) :
                                  $count++;
                                  $hiddenClass = $count > 6 ? 'hidden-row' : ''; // Hide rows after the first 6
                                  ?>
                                  <tr class="availability-date <?php echo $hiddenClass; ?>"
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
                                    <td>Bookings Available</td>
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
                <?php get_template_part ( 'woocommerce/template-parts/template', 'persons' ); ?>

                <!--review block partial -->
                <?php get_template_part ( 'woocommerce/template-parts/template', 'review' ); ?>


              </div>
            <?php } ?>
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

    <?php } ?>

    <!--tabs partial -->
    <?php get_template_part ( 'woocommerce/template-parts/template', 'tabs' ); ?>

    <!--related courses partial -->
    <?php get_template_part ( 'woocommerce/template-parts/template', 'related' ); ?>

</main>

<?php do_action ( 'woocommerce_after_single_product' ); ?>