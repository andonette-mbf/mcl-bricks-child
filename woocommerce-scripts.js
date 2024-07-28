/// <reference types="jquery" />
jQuery(function () {
// Define a custom jQuery plugin named 'multiply'
jQuery.fn.multiply = function (numCopies) {
    // Clone the original element and replace {X} in the HTML with 1
    var newElements = this.clone().html(this.clone().html().replace(/{X}/g, 1));
    // Loop to create additional clones starting from 2 up to numCopies
    for (var i = 2; i <= numCopies; i++) {
        // Add the cloned element to newElements, replacing {X} with the current value of i
        newElements = newElements.add(
            this.clone()[0].outerHTML.replace(/{X}/g, i)
        );
    }
    // Return the combined set of new elements
    return newElements;
};

  // Persons clickable
  jQuery('.step-field.person-field').on('click', 'a', function (e) {
      e.preventDefault();

      // Toggle active class on person links
      jQuery('.step-field.person-field a').removeClass('active');
      jQuery(this).addClass('active');

      // Get and update the number of persons
      var personNumber = jQuery(this).attr('data-bookingPerson');
      jQuery('#wc_bookings_field_persons').val(personNumber);
      jQuery('.meta .title.number-of-people').text(personNumber);

          // Calculate and update the price
          var price = jQuery('#changed-cost-of-course').val();
          var multipliedPrice = price * personNumber;
          var formattedPrice = multipliedPrice.toFixed(2);

      // Update people attending attribute
      jQuery('.training-course-product').attr('data-people-attending', personNumber);

     // Update price fields
jQuery('#multi-cost-of-course').val(multipliedPrice  + " Inc VAT");
jQuery('#total-cost').text(formattedPrice + " Inc VAT");

      // Show and hide sidebar prices
      jQuery('.from-price.price').hide();
      jQuery('.total-price.price').show();
      jQuery('.total-price.price #total-cost').text(formattedPrice  + " Inc VAT");

      // Check spaces remaining
      var spaces_remaining = jQuery('.training-course-product').attr('data-spaces-remaining');

      if (parseInt(personNumber) > parseInt(spaces_remaining)) {
          jQuery('#cannotBookCourse')
              .html('We only have ' + spaces_remaining + ' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842.')
              .slideDown();
          jQuery('#confirm-boooking').slideUp();
      } else {
          jQuery('#cannotBookCourse').slideUp();
          jQuery('#confirm-boooking').slideDown();
      }

      // Show delegate details and multiply the delegate fields
      jQuery('#delegate-details').show();
      jQuery('#delegate-details .outputted-fields').empty();
      jQuery('.single-product form.cart .delegate-name-email-field input[name^="delegate"]').val('');
      jQuery('#delegate-details .outputted-fields').html(jQuery('#delegate-details .step-inputs-split').multiply(personNumber));
  });

  // People select
  jQuery('.people-select-menu select').on('change', function (e) {
      var personNumber = this.value;
      jQuery('#wc_bookings_field_persons').val(personNumber);
      jQuery('.meta .title.number-of-people').text(personNumber);

      var price = jQuery('#changed-cost-of-course').val();
      var multipliedPrice = price * personNumber;
      var formattedPrice = multipliedPrice.toFixed(2);

      // Update people attending attribute
      jQuery('.training-course-product').attr('data-people-attending', personNumber);

      // Update price fields
      jQuery('#multi-cost-of-course').val(multipliedPrice);
      jQuery('#total-cost').text(formattedPrice);

      // Show and hide sidebar prices
      jQuery('.from-price.price').hide();
      jQuery('.total-price.price').show();
      jQuery('.total-price.price #total-cost').text(formattedPrice);

      // Check spaces remaining
      var spaces_remaining = jQuery('.training-course-product').attr('data-spaces-remaining');

      if (parseInt(personNumber) > parseInt(spaces_remaining)) {
          jQuery('#cannotBookCourse')
              .html('We only have ' + spaces_remaining + ' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842.')
              .slideDown();
          jQuery('#confirm-boooking').slideUp();
      } else {
          jQuery('#cannotBookCourse').slideUp();
          jQuery('#confirm-boooking').slideDown();
      }

      // Show delegate details and multiply the delegate fields
      jQuery('#delegate-details').show();
      jQuery('#delegate-details .outputted-fields').empty();
      jQuery('.single-product form.cart .delegate-name-email-field input[name^="delegate"]').val('');
      jQuery('#delegate-details .outputted-fields').html(jQuery('#delegate-details .step-inputs-split').multiply(personNumber));
  });

  // On click of book now table button
  jQuery('.table-section a.book-now-button').click(function (e) {
      e.preventDefault();

      // Retrieve the date from the clicked button
      var day = jQuery(this).attr('data-day');
      var month = jQuery(this).attr('data-month');
      var year = jQuery(this).attr('data-year');

      // Get the price from the input field and convert it to a number
      var price_cost = parseFloat(jQuery('input#cost-of-course').val());
      console.log(price_cost);
      jQuery('input#changed-cost-of-course').attr('value', price_cost);

      // Calculate and update prices for different numbers of people
      var multipliedPrices = [price_cost, price_cost * 2, price_cost * 3, price_cost * 4, price_cost * 5];
      multipliedPrices.forEach((price, index) => {
          jQuery(`.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-${index + 1}`).text(price.toFixed(2));
      });

      // Add leading zeros to month and day
      month = (month <= 9) ? '0' + month : month;
      day = (day <= 9) ? '0' + day : day;

      // Show step 3 and course date meta if hidden
      jQuery('.training-course-steps .course-step#step-3').slideDown();
      jQuery('.training-sidebar .sidebar-selections .meta#course-date-meta').slideDown();

      // Update date elements in the sidebar (course-date-meta div)
const fullDate = `${day}/${month}/${year}`;
jQuery('#course-date-meta .start-date').text(fullDate);

      // Get end date from the table
      var end_date = jQuery(`tr[data-start='${day}/${month}/${year}']`).attr('data-end');
      jQuery('#course-date-meta .end-date').text(end_date);

      // Update booking date inputs in the form
      jQuery('.single-product form.cart input.booking_date_month').val(month);
      jQuery('.single-product form.cart input.booking_date_day').val(day);
      jQuery('.single-product form.cart input.booking_date_year').val(year);

      // Scroll to step 3
      jQuery('html, body').animate({
          scrollTop: jQuery('.training-course-steps .course-step#step-3').offset().top - 160,
      }, 2000);
  });
    
    

    // Submit Cart Button
    jQuery('a#confirm-boooking').click(function (e) {
        e.preventDefault();
  
        // Check for missing delegate info
        var missing_delegate_info = jQuery('.outputted-fields input').filter(function () {
          return this.value === '' && jQuery(this).is(':visible');
        });
  
        if (missing_delegate_info.length > 0) {
          jQuery('.outputted-fields input:empty').addClass('error');
          jQuery('#delegate_info_error').slideDown();
          jQuery('html, body').animate({
            scrollTop: jQuery('#delegate_info_error').offset().top - 250,
          }, 2000);
          return false;
        }
  
        jQuery('div#layout-calendar form.cart').submit();
      });

    // Function to update delegate fields
    function updateDelegateField(selector, event, fieldType) {
        jQuery('body').on(event, selector, function (e) {
          var fieldNumber = jQuery(this).attr('data-number');
          var fieldValue = jQuery(this).val();
          var inputName = 'delegate[' + fieldNumber + '][' + fieldType + ']';
  
          jQuery('.single-product form.cart input[name="' + inputName + '"]').val(fieldValue);
        });
      }

    // Update delegate fields
    updateDelegateField('div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_name', 'input', 'name');
    updateDelegateField('div#delegate-details .delegate-fields .outputted-fields .step-inputs-split select.delegate_level_select', 'change', 'level_select');
    updateDelegateField('div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_number', 'input', 'number');
    updateDelegateField('div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_dob', 'input', 'dob');
    updateDelegateField('div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_phone', 'input', 'phone');
    updateDelegateField('div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_email', 'input', 'email');
  });
