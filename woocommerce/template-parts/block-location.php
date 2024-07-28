<?php
/**
 * The template for displaying location select
 */
defined ( 'ABSPATH' ) || exit;
global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';
$locations     = $parent_group->get_children ();
$location_data = [];

foreach ( $locations as $location ) {
  $location_data[] = [ 
    'id'        => $location,
    'permalink' => get_permalink ( $location ),
    'address'   => get_field ( 'select_address', $location ),
    'is_active' => ( $location === $product_id ),
  ];
  }
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