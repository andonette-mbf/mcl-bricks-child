<?php
/**
 * The template for displaying the product sidebar
 */
defined ( 'ABSPATH' ) || exit;
global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';
/**
 * @suppress PHP0417
 */
$course_details            = get_field ( 'course_details' );
$included_in_course        = get_field ( 'included_in_course' );
$pre_training_requirements = get_field ( 'pre_training_requirements' );
?>

<section class="brxe-section brxe-wc-section">
  <div class="brxe-container">
    <div id="brxe-tabs" data-script-id="tabs" class="brxe-tabs-nested">
      <div id="brxe-pljtos" class="brxe-block tab-menu">
        <div class="brxe-div tab-title brx-open">
          <div class="brxe-text-basic">Course Information</div>
        </div>
        <div class="brxe-div tab-title brx">
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
            <?php echo $product->get_description (); ?>
          </div>
        </div>
        <div class="brxe-block tab-pane fade active show brx">
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