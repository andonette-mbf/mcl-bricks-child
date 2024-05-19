<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined ( 'ABSPATH' ) || exit;

global $product;

if ( post_password_required () )
  {
  echo get_the_password_form (); // WPCS: XSS ok.
  return;
  }
?>

<main id="brx-content">

  <div id="product-<?php the_ID (); ?>" <?php wc_product_class ( '', $product ); ?>>

    <?php if ( has_term ( 'training-courses', 'product_cat' ) )
    {
    if ( $product->is_type ( 'booking' ) )
      {
      $product_group_id = parent_grouped_id ( $product->id );
      $parent_group     = wc_get_product ( $product_group_id );
      $comments         = get_comments ( array( 'post_id' => $product_group_id ) );

      $duarionType = get_post_meta ( $product->id, '_wc_booking_duration_unit' );
      $duarionTime = get_post_meta ( $product->id, '_wc_booking_duration' );

      $price_2023 = get_field ( '2023_price', $product->id );
      }
    else
      {
      $product_group_id = $product->id;
      $parent_group     = $product;
      $comments         = get_comments ( array( 'post_id' => $product_group_id ) );
      }
    ?>

      <!--Header section -->
      <section class="brxe-section brxe-wc-section mcl-hero">
        <div class="brxe-container mcl-flex--col mcl-hero__inner mcl-padding">
          <div class="brxe-div mcl-hero__content mcl-flex--col">
            <h1 class="brxe-heading mcl-hero__title"><?php if ( get_field ( 'display_title' ) )
            { ?>
                <?php the_field ( 'display_title' ); ?>
              </h1>
            <?php }
          else
            { ?>
              <h1 class="brxe-heading mcl-hero__title"><?php the_title (); ?></h1>
            <?php } ?>

            <h5>
              Duration: <?php if ( get_field ( 'course_duration_custom' ) )
              { ?>
                <span><?php the_field ( 'course_duration_custom' ); ?></span>
              <?php }
            else
              { ?>
                <span class="float-left w-100"><?php echo $duarionTime[ 0 ]; ?>     <?php echo $duarionType[ 0 ]; ?></span>
              <?php } ?>
              <br>
              Certification:
              <?php echo get_field ( 'certification_meta', $product_group_id ); ?>
              <br>
              Grants & Funding: <?php if ( get_field ( 'grants_funding', $product_group_id ) )
              { ?>
                <span>Options available</span>
              <?php }
            else
              { ?>
                <span>Options not available</span>
              <?php } ?>
              <br>
              Availability: See dates below
            </h5>
          </div>
        </div>

        <div class="brxe-container mcl-hero__inner--absolute">
          <div class="brxe-div mcl-hero__overlay mcl-absolute--full"></div>
          <div class="brxe-div mcl-hero__image" style="background-image: url('<?php the_field ( 'hero_image' ); ?>');"></div>
          <div class="brxe-div mcl-hero__tagline-box">
            <h3 class="brxe-heading mcl-hero__tagline-box-heading">We've<br>got<br>you.</h3>
          </div>
        </div>
      </section>

      <!--Course Selection-->
      <section class="brxe-section brxe-wc-section training-course-product">
        <div class="brxe-container grid--1-3 gap--m">

          <div class="training-sidebar">
            <div class="sidebar-inner">
              <div class="sidebar-selections">
                <h3 class="brxe-heading">Your Selection</h3>
                <?php if ( $product->is_type ( 'booking' ) )
                { ?>
                  <!-- meta class uses js --->
              <div class="meta">
                <b>Course: </b><br>
                <span>
                  <?php echo get_the_title ( $product_group_id ); ?>
                </span>
              </div>

              <div class="meta">
                <b>Venue</b>
                <span>
                  <?php the_field ( 'location' ); ?>
                </span>
              </div>

              <div class="meta" id="course-date-meta">
                <b>Course Date</b><br>
                <span class="title">
                  <span class="start-date">
                    <div class="dd"></div>/<div class="mm"></div>/<div class="yyyy"></div>
                  </span>
                </span>
              </div>
              <?php } ?>

              <?php if ( $product->is_type ( 'booking' ) )
              { ?>
              <p class="from-price price"></p>
              <p class="total-price price">
                <span class="price title">Total - £<span id="total-cost"></span></span </p>
                <?php } ?>
            </div>
          </div>
        </div>

        <div class="brxe-block">
          <h3 class="brxe-heading">Confirm Venue</h3>
          <?php if ( $product->is_type ( 'booking' ) )
          { ?>
          <input type="hidden" id="cost-of-course" value="<?php echo $product->get_price (); ?>" />

          <input type="hidden" id="multi-cost-of-course" value="" />

          <input type="hidden" id="changed-cost-of-course" value="" />

          <?php if ( isset ( $_GET[ 'scrollStep' ] ) )
          {
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

          <?php
$product_id = $product->get_id(); // Assuming $product is the current product object
$availability_data = get_future_availability_dates($product_id);

if ($availability_data['availability_in_future']) {
    echo '<ul>';
    foreach ($availability_data['future_availability_dates'] as $date_range) {
        echo '<li>Available from ' . $date_range['from']->format('Y-m-d') . ' to ' . $date_range['to']->format('Y-m-d') . '</li>';
    }
    echo '</ul>';
} else {
    echo 'No future availability dates.';
}
?>

          <div class="training-course-steps">
            <?php
            //Notices
            do_action ( 'woocommerce_before_single_product' ); ?>

            <div class="course-step" id="step-1">
              <div class="step-title">
                <span class="title">Step 1 - Select Your Venue</span>
              </div>
              <div>
                <?php
                $locations = $parent_group->get_children ();
                foreach ( $locations as $location )
                  {
                  ?>
                <!-- Need to get the right info coming in -->
                      <div class="step-field location">
                        <a class="<?php if ( $location === $product->id )
                        { ?>active<?php } ?>"
                          href="<?php echo get_permalink ( $location ); ?>?scrollStep=2"><?php echo get_field ( 'select_address', $location ); ?></a>
                      </div>
                    <?php } ?>
                  </div>
                </div>

                <div class="course-step" id="step-2">
                  <div class="step-title">
                    <span class="title">Step 2 - Choose The Date</span>
                    <a href="#" data-step="2" class="previous-step">Previous step</a>
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
                          $select_address       = get_field_object ( 'select_address' );
                          $select_address_value = $select_address[ 'value' ];

                          //Get values from label
                          $select_address_field = get_field_object ( 'select_address' );
                          $select_address_value = get_field ( 'select_address' );
                          $select_address_label = $field[ 'choices' ][ $value ];

                          if ( ! empty ( $futureAvailabilityDates ) )
                            { ?>

                            <table class="table">
                              <thead>
                                <th>Start Date</th>
                                <th>Location</th>
                                <th>Availability</th>
                                <th class="add-td"></th>
                              </thead>
                              <tbody>
                                <?php
                                $dateCounter = 0;
                                foreach ( $futureAvailabilityDates as $futureAvailabilityDate )
                                  {
                                  $dateCounter++;

                                  echo '<tr data-start="' . $futureAvailabilityDate[ 'from' ]->format ( 'd/m/Y' ) . '" data-end="' . $futureAvailabilityDate[ 'to' ]->format ( 'd/m/Y' ) . '">';
                                  echo '<td>' . $futureAvailabilityDate[ 'from' ]->format ( 'd/m/Y' ) . '</td>';

                                  if ( $select_address_value == 'Redwood Park Estate, Stallingborough NE, Lincolnshire, DN41 8TH' )
                                    {
                                    echo '<td>Humber Training Centre</td>';
                                    }
                                  else if ( $select_address_value == '11 Thornsett Works, Thornsett Road, Wandsworth, London, SW18 4EW' )
                                    {
                                    echo '<td>South London Training Centre</td>';
                                    }
                                  else
                                    {
                                    echo '<td>East London Training Centre</td>';
                                    }

                                  echo '<td>Bookings Available</td>';
                                  echo '<td class="add-td"><a href="#" data-day="' . $futureAvailabilityDate[ 'from' ]->format ( 'j' ) . '" data-month="' . $futureAvailabilityDate[ 'from' ]->format ( 'n' ) . '" data-year="' . $futureAvailabilityDate[ 'from' ]->format ( 'Y' ) . '" class="cta-button book-now-button float-right">Select Date</a></td>';
                                  echo '</tr>';
                                  }
                                ?>
                              </tbody>
                            </table>
                          <?php } ?>
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

                <div class="course-step" id="step-3">
                  <div class="step-title">
                    <span class="title">Step 3 - Select The Number Of People</span>
                    <a href="#" data-step="3" class="previous-step">Previous step</a>
                  </div>

                  <div class="step-field person-field">
                    <a href="#" data-bookingPerson="1">1 Person <p class="price">(£<span
                          id="price-1"><?php echo $product->get_price (); ?></span>)</p></a>
                  </div>

                  <div class="step-field person-field">
                    <a href="#" data-bookingPerson="2">2 People <p class="price">(£<span
                          id="price-2"><?php echo $product->get_price () * 2; ?></span>)</p></a>
                  </div>

                  <div class="step-field person-field">
                    <a href="#" data-bookingPerson="3">3 People <p class="price">(£<span
                          id="price-3"><?php echo $product->get_price () * 3; ?></span>)</p></a>
                  </div>

                  <div class="step-field person-field">
                    <a href="#" data-bookingPerson="4">4 People <p class="price">(£<span
                          id="price-4"><?php echo $product->get_price () * 4; ?></span>)</p></a>
                  </div>

                  <div class="step-field person-field">
                    <a href="#" data-bookingPerson="5">5 People <p class="price">(£<span
                          id="price-5"><?php echo $product->get_price () * 5; ?></span>)</p></a>
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

                    <div class="delegate-fields">
                      <div class="step-inputs-split">
                        <b>Delegate <span class="number">{X}</span> Information</b>
                        <input name="delegate_name[{X}]" data-number="{X}" required class="delegate_name" value=""
                          placeholder="Delegate {X} Name">
                        <select name="delegate_level[{X}]" data-number="{X}" required class="delegate_level" placeholder="Delegate {X} Level">
                          <option value="" disabled selected>Select Level</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                  
                        <input name="delegate_number[{X}]" data-number="{X}" required class="delegate_number" value=""
                          placeholder="Delegate {X} Number">
                        <input name="delegate_dob[{X}]" data-number="{X}" required class="delegate_dob" value=""
                          placeholder="Delegate {X} DOB">
                      </div>
                      <div class="outputted-fields"></div>

                      <span class="alert alert-danger float-left mt-4" id="delegate_info_error"
                        style="display: none;">Please enter all delegate information.</span>
                    </div>
                  </div>
                </div>

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

      <?php if ( $product->is_type ( 'booking' ) )
      { ?>
        <script>
          <?php
          //Do we have any call to book dates?
          if ( get_field ( 'additional_date_information' ) )
            {
            //Get the call to book dates
            $additional_dates_information = json_decode ( get_field ( 'additional_date_information' ), true );

            //Create an array of every day
            $additional_dates_information_per_day = [];

            $x = 0;
            foreach ( $additional_dates_information as $additional_date_information )
              {

              if ( $additional_date_information[ 'from' ] == $additional_date_information[ 'to' ] )
                {

                //1 day course
                $period                                                                          = new DateTime( $additional_date_information[ 'to' ] );
                $additional_dates_information_per_day[ $period->format ( 'Y-m-d' ) ][ 'spaces_remain' ] = $additional_date_information[ 'spaces_remain' ];
                $additional_dates_information_per_day[ $period->format ( 'Y-m-d' ) ][ 'call_to_book' ]  = $additional_date_information[ 'call_to_book' ];
                }
              else
                {

                //Add each day in the period
                $period = new DatePeriod(
                  new DateTime( $additional_date_information[ 'from' ] ),
                  new DateInterval( 'P1D' ),
                  new DateTime( $additional_date_information[ 'to' ] ),
                );

                foreach ( $period as $key => $value )
                  {
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
    
    <!--tabbed data -->
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
<section class="brxe-section brxe-wc-section related-products">
  <div class="related-title">
  <?php
  $training_courses_id = get_term_by ( 'slug', 'training-courses', 'product_cat' );
  $terms               = get_the_terms ( $product->id, 'product_cat' );
  foreach ( $terms as $term )
    {
    if ( $term->parent === $training_courses_id->term_id )
      {
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
