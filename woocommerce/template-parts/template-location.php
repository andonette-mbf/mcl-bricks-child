<?php
/**
 * Template part for displaying the booking locations
 */
global $product;
?>

<input type="hidden" id="cost-of-course" value="<?php echo $product->get_price (); ?>" />
<input type="hidden" id="multi-cost-of-course" value="" />
<input type="hidden" id="changed-cost-of-course" value="" />

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