<?php
/**
 * The template for displaying location select
 */
defined ( 'ABSPATH' ) || exit;
global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';
/**
 * @suppress PHP0417
 */
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

// Only display the HTML if there is more than one location
if ( count ( $locations ) > 1 ) :
  ?>

  <div class="course-step" id="step-1">
    <div class="step-title"><span class="title">Choose Your Venue</span></div>
    <div>
      <?php foreach ( $location_data as $location ) : ?>
        <div class="step-field location">
          <a class="<?php echo $location[ 'is_active' ] ? 'active' : ''; ?>"
            href="<?php echo $location[ 'permalink' ]; ?>?scrollStep=2">
            <?php echo esc_html ( $location[ 'address' ] ); ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

<?php endif; ?>