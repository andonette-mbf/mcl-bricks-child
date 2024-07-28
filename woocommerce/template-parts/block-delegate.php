<?php
/**
 * The template for displaying delegate details
 */
defined ( 'ABSPATH' ) || exit;
global $product;
include get_stylesheet_directory () . '/woocommerce-variables.php';

$delegate_info_type = '';
if ( has_term ( 'irata-courses', 'product_cat' ) ) {
  $delegate_info_type = 'irata';
  } elseif ( has_term ( 'gwo-courses', 'product_cat' ) ) {
  $delegate_info_type = 'gwo';
  }
?>
<div id="delegate-details">
  <div class="title-row step-title ">
    <span class="title smaller-title to-animate">Enter information for all delegates</span>
  </div>

  <?php if ( $delegate_info_type === 'irata' ) : ?>
    <div class="delegate-fields">
      <div class="step-inputs-split">
        <b>Delegate <span class="number">{X}</span> Information</b>
        <input name="delegate_name[{X}]" data-number="{X}" required class="delegate_name" value=""
          placeholder="Delegate {X} Name">
        <select name="delegate_level_select[{X}]" data-number="{X}" required class="delegate_level_select">
          <option value="" disabled selected>IRATA Course Required</option>
          <option value="1">IRATA Level 1</option>
          <option value="2">IRATA Level 2</option>
          <option value="3">IRATA Level 3</option>
        </select>
        <input name="delegate_number[{X}]" data-number="{X}" class="delegate_number" value=""
          placeholder="Delegate {X} IRATA Number" style="display:none;">
        <label style="color: black; font-weight: bold" for="delegate_dob[{X}]">Delegate Date of
          Birth</label>
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
      <span class="alert alert-danger float-left mt-4" id="delegate_info_error" style="display: none;">Please enter all
        delegate information.</span>
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
      <span class="alert alert-danger float-left mt-4" id="delegate_info_error" style="display: none;">Please enter all
        delegate information.</span>
    </div>
  <?php endif; ?>
</div>