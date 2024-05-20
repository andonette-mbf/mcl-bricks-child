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
  if (has_term('training-courses', 'product_cat')) {
  // Initialize common variables
  $product_group_id = $product->is_type('booking') ? parent_grouped_id($product->id) : $product->id;
  $parent_group = wc_get_product($product_group_id);
  $comments = get_comments(array('post_id' => $product_group_id));
  
  if ($product->is_type('booking')) {
      $duarionType = get_post_meta($product->id, '_wc_booking_duration_unit', true);
      $duarionTime = get_post_meta($product->id, '_wc_booking_duration', true);
      $price_2023 = get_field('2023_price', $product->id);
  }
  ?>

  <!--Header section partial -->
  <?php get_template_part('woocommerce/template-parts/template', 'header'); ?>

  <!--Course Selection-->
  <section class="brxe-section brxe-wc-section training-course-product">
    <div class="brxe-container grid--1-3 gap--m">
    <!--sidebar partial -->
    <?php get_template_part('woocommerce/template-parts/template', 'sidebar'); ?>

    <div class="brxe-block">
      <h3 class="brxe-heading">Confirm Venue</h3>
      <?php if ( $product->is_type ( 'booking' ) ) { ?>
        <?php get_template_part('woocommerce/template-parts/template', 'location'); ?>
        
        <?php
        $availabilityInFuture = false;
        $availability = get_post_meta($product->id, '_wc_booking_availability');
        $availabilityTest = array_filter($availability);
        
        // Store availability dates
        $futureAvailabilityDates = [];
        $current_date = new DateTime();
        
        // Loop through and check dates in the future
        foreach ($availabilityTest as $availabilityTestRange) {
          foreach ($availabilityTestRange as $availabilityTestRangeSingle) {
              // Determine the opening and closing dates
              $opening_date = new DateTime(!empty($availabilityTestRangeSingle["from_date"]) ? $availabilityTestRangeSingle["from_date"] : $availabilityTestRangeSingle["from"]);
              $closing_date = new DateTime(!empty($availabilityTestRangeSingle["to_date"]) ? $availabilityTestRangeSingle["to_date"] : $availabilityTestRangeSingle["to"]);

              // Check if the opening date is in the future
              if ($opening_date > $current_date) {
                  $availabilityInFuture = true;

                  // Add to array to display in list
                  $futureAvailabilityDates[] = [
                      "from" => $opening_date,
                      "to" => $closing_date
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
          <div class="step-title"><span class="title">Step 1 - Check Your Venue</span></div>
          <div>
            <?php
            $locations = $parent_group->get_children ();
            foreach ( $locations as $location ) {
              ?>
              <div class="step-field location">
                <a class="<?php if ( $location === $product->id ) { ?>active<?php } ?>" href="<?php echo get_permalink ( $location ); ?>?scrollStep=2"><?php echo get_field ( 'select_address', $location ); ?></a>
              </div>
            <?php } ?>
          </div>
        </div>
        
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
                <span class="toggle-label">Calendar</span></a>
            </div>
          </div>

              <div class="step-layouts">
                <div class="layouts" id="layout-list">
                  <div class="calendar-list">
                    <div class="table-section">

                    <?php
                    $select_address = get_field('location');
                    $select_address_value = $select_address['value'];
                    $select_address_label = $select_address['choices'][$select_address_value];

                    if (!empty($futureAvailabilityDates)) : ?>
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
                        <?php foreach ($futureAvailabilityDates as $futureAvailabilityDate) : ?>
                          <tr data-start="<?php echo esc_attr($futureAvailabilityDate['from']->format('d/m/Y')); ?>" data-end="<?php echo esc_attr($futureAvailabilityDate['to']->format('d/m/Y')); ?>">
                            <td><?php echo esc_html($futureAvailabilityDate['from']->format('d/m/Y')); ?></td>
                            <td>
                              <?php
                              switch ($select_address_value) {
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
                                <a href="#" data-day="<?php echo esc_attr($futureAvailabilityDate['from']->format('j')); ?>" data-month="<?php echo esc_attr($futureAvailabilityDate['from']->format('n')); ?>" data-year="<?php echo esc_attr($futureAvailabilityDate['from']->format('Y')); ?>" class="cta-button book-now-button float-right">Select Date</a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
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
          
          <!--persons partial -->
          <?php get_template_part('woocommerce/template-parts/template', 'persons'); ?>

          <div class="course-step" id="step-4">
            <div class="title-row step-title">
              <span class="title">Step 4 - <?php echo get_field ( 'step_1_title', $product_group_id ); ?></span>
              <a href="#" data-step="4" class="previous-stept">Previous step</a>

              <div class="content">
                <?php echo get_field ( 'step_1_content', $product_group_id ); ?>
              </div>
            </div>

            <div>
              <?php
              // loop through the rows of data
              while ( have_rows ( 'step_1_questions', $product_group_id ) ) :
                the_row (); ?>
                <div class="step-field qualification-field">
                  <span class="qual-label"><?php the_sub_field ( 'label' ); ?></span>
                  <a href="#">Yes</a>

                  <div class="qual-listing">
                    <div class="content">
                      <ul>
                        <?php
                        // loop through the rows of data
                        while ( have_rows ( 'details' ) ) :
                          the_row (); ?>
                          <li><?php the_sub_field ( 'options' ); ?></li>
                        <?php endwhile; ?>
                      </ul>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          </div>

          <div class="course-step" id="step-5">
            <div class="title-row step-title">
              <span class="title">Step 5 - Review Your Booking</span>
              <a href="#" data-step="5" class="previous-step float-right to-animate">Previous step</a>
            </div>

            <div>
              <div class="review-booking-block">
                <div class="meta">
                  <b>Course Selection</b>
                  <span class="title"><?php echo get_the_title ( $product_group_id ); ?></span>
                  <span class="title duration"><i class="far fa-clock"></i> <?php echo $duarionTime[ 0 ]; ?>
                    <?php echo $duarionType[ 0 ]; ?> course</span>
                </div>
              </div>
            </div>

            <div>
              <div class="review-booking-block">
                <div class="meta">
                  <b>Course Venue</b>
                  <span class="title"><?php the_field ( 'select_address' ); ?></span>
                </div>
              </div>
            </div>
            <div>
              <div class="review-booking-block">
                <div class="meta">
                  <b>Course Date</b>
                  <span class="title">Start -
                    <span class="start-date">
                      <div class="dd"></div>/<div class="mm"></div>/<div class="yyyy"></div>
                    </span>
                  </span>
                  <span class="title">End -
                    <span class="end-date"></span>
                  </span>
                </div>
              </div>
            </div>
            <div>
              <div class="review-booking-block">
                <div class="meta">
                  <b>Number Of People</b>
                  <span class="title number-of-people"></span>
                </div>
              </div>
            </div>

            <div class="row confirm-row">
              <p class="total-cost title float-left">Total Cost - <span class="price title">£<span
                    id="total-cost"><?php echo $product->get_price (); ?></span></span></p>
              <a href="#" class="cta-button float-right" id="confirm-boooking">Confirm Your Booking</a>
              <a href="tel:<?php echo get_field ( 'main_telephone_number', 'options' ); ?>" id="cannotBookCourse"
                class="cta-button float-right w-auto" style="display: none;"></a>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>

<?php if ( $product->is_type ( 'booking' ) ) { ?>
  <script>
    <?php
    //Do we have any call to book dates?
    if ( get_field ( 'additional_date_information' ) ) {
      //Get the call to book dates
      $additional_dates_information = json_decode ( get_field ( 'additional_date_information' ), true );

      //Create an array of every day
      $additional_dates_information_per_day = [];

      $x = 0;
      foreach ( $additional_dates_information as $additional_date_information ) {

        if ( $additional_date_information[ 'from' ] == $additional_date_information[ 'to' ] ) {

          //1 day course
          $period                                                                          = new DateTime( $additional_date_information[ 'to' ] );
          $additional_dates_information_per_day[ $period->format ( 'Y-m-d' ) ][ 'spaces_remain' ] = $additional_date_information[ 'spaces_remain' ];
          $additional_dates_information_per_day[ $period->format ( 'Y-m-d' ) ][ 'call_to_book' ]  = $additional_date_information[ 'call_to_book' ];
          } else {

          //Add each day in the period
          $period = new DatePeriod(
            new DateTime( $additional_date_information[ 'from' ] ),
            new DateInterval( 'P1D' ),
            new DateTime( $additional_date_information[ 'to' ] ),
          );

          foreach ( $period as $key => $value ) {
            $additional_dates_information_per_day[ $value->format ( 'Y-m-d' ) ][ 'spaces_remain' ] = $additional_date_information[ 'spaces_remain' ];
            $additional_dates_information_per_day[ $value->format ( 'Y-m-d' ) ][ 'call_to_book' ]  = $additional_date_information[ 'call_to_book' ];
            }
          }
        $x++;
        }
      ?>

      jQuery(document).ready(function(){

        //On selection of a booking date, check whether this is call to book
        jQuery("body").on("click", ".calendar-list .table-section .table tbody td.add-td .book-now-button", function(){
            console.log("change");

          //Reset
          jQuery("#cannotBookCourse").hide();
          jQuery("#confirm-boooking").show();

          //Get all additional information in a format we can check
          var additional_date_information = <?php echo json_encode ( $additional_dates_information_per_day ); ?>;

          //Construct the full booking date
          var booking_date_check = jQuery(".booking_date_year").val() + "-" + jQuery(".booking_date_month").val() + "-" + jQuery(".booking_date_day").val();

          //Do we have a number of persons set?
          var booking_persons = jQuery("#wc_bookings_field_persons").val();

          jQuery(".container-fluid.training-course-product").attr("data-spaces-remaining", "");
          jQuery(".container-fluid.training-course-product").attr("data-spaces-remaining", additional_date_information[ booking_date_check ][ 'spaces_remain' ]);

          //Check against array of call to book dates
          if (additional_date_information[ booking_date_check ] !== undefined) {

                if (additional_date_information[booking_date_check]['call_to_book'] == "yes") {
              jQuery("#cannotBookCourse").html('Please call us to book this date on <br /> 0113 257 0842').slideDown();
              jQuery("#confirm-boooking").slideUp();
        }

                  if(parseInt(booking_persons) > parseInt(additional _ date_information[booking_dat e_check]['spaces_r em ain'])) {
                  jQuery("#cannotBookCourse").html('We only have '+additional_date_information[booking_date_check]["spaces_remain"]+' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842. ').slideDown();
              jQuery("#confirm-boooking").slideUp();
            }
          }
        });
        //On selection of a booking date, check whether this is call to book
        jQuery("#wc-bookings-booking-form.wc-bookings-booking-form").on("change", ".booking_date_day, #wc_bookings_field_persons", function(){
      console.log("change");

          //Reset
      jQuery("#cannotBookCourse").hide();
          jQuery("#confirm-boooking").show();

          //Get all additional information in a format we can check
            var additional_date_information = <?php echo json_encode ( $additional_dates_information_per_day ); ?>;

              //Construct the full booking date
              var booking_date_check = jQuery(".booking_date_year").val()+"-"+jQuery(".booking_date_month").val()+"-"+jQuery(".booking_date_day").val();
  
              //Do we have a number of persons set?
              var booking_persons = jQuery("#wc_bookings_field_persons").val();
  
              jQuery(".container-fluid.training-course-product").attr("data-spaces-remaining", "");
              jQuery(".container-fluid.training-course-product").attr("data-spaces-remaining", additional_date_information[booking_date_check]['spaces_remain']);
  
            //Check against array of call to book dates
            if(additional_date_information[booking_date_check] !== undefined) {
                if (additional_date_information[booking_date_check]['call_to_book'] == "yes") {
                  jQuery("#cannotBookCourse").html('Please call us to book this date on <br /> 0113 257 0842').slideDown();
                    jQuery("#confirm-boooking").slideUp();
                }
  
                if(parseInt(booking_persons) > parseInt(additional_date_information[booking_date_check]['spaces_remain'])) {
                  jQuery("#cannotBookCourse").html('We only have '+additional_date_information[booking_date_check]["spaces_remain"]+' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842. ').slideDown();
                    jQuery("#confirm-boooking").slideUp();
                }
              }
            });
          });
          <?php } ?>
      </script>
  
  <?php } ?>
<?php } ?>

<!--tabs partial -->
<?php get_template_part('woocommerce/template-parts/template', 'tabs'); ?>
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

<a href="<?php echo get_term_link ( 'training-courses', 'product_cat' ); ?>" class="float-right title-link">See all training courses</a>
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