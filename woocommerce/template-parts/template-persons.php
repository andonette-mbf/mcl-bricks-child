<?php
/**
 * Template part for displaying the header of the shop
 */
global $product;
?>
<div class="course-step" id="step-3">
  <div class="step-title">
    <span class="title">Step 3 - Select The Number Of People</span>
    <a href="#" data-step="3" class="previous-step">Previous step</a>
  </div>

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
          <input type="date" id="delegate_dob{X}" name="delegate_dob[{X}]" data-number="{X}" required class="delegate_dob"
            value="" placeholder="Delegate {X} DOB">
          <input type="text" id="delegate_phone{X}" name="delegate_phone[{X}]" data-number="{X}" required
            class="delegate_phone" value="" placeholder="Delegate {X} Phone Number">
          <input type="email" id="delegate_email{X}" name="delegate_email[{X}]" data-number="{X}" required
            class="delegate_email" value="" placeholder="Delegate {X} Email">
        </div>
        <div class="outputted-fields"></div>
        <span class="alert alert-danger float-left mt-4" id="delegate_info_error" style="display: none;">Please enter all
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
          <span class="alert alert-danger float-left mt-4" id="delegate_info_error" style="display: none;">Please enter all
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
          <span class="alert alert-danger float-left mt-4" id="delegate_info_error" style="display: none;">Please enter all
            delegate information.</span>
        </div>
      <?
      }
    ?>
  </div>
</div>