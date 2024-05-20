jQuery(document).ready(function () {
	// Animate scrolling to sections
	jQuery('.scollingToSection').click(function (e) {
			e.preventDefault();
			var scrollingModule = jQuery(this).attr('data-scrollingModule');
			var targetElement = jQuery('#scroll' + scrollingModule);

			if (targetElement.length) {
					jQuery('html, body').animate({
							scrollTop: targetElement.offset().top - 70,
					}, 500);
			}
	});

	// Multiply function. This is responsible for cloning delegates on select
	jQuery.fn.multiply = function (numCopies) {
			var newElements = this.clone().html(this.clone().html().replace(/{X}/g, 1));
			for (var i = 2; i <= numCopies; i++) {
					newElements = newElements.add(
							this.clone()[0].outerHTML.replace(/{X}/g, i)
					);
			}
			return newElements;
	};

	// Persons clickable
	jQuery('.step-field.person-field a').click(function (e) {
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

	// Prevent default action on person dropdown link click
	jQuery('.step-field.person-dropdown a').click(function (e) {
			e.preventDefault();
	});

	// Initialize hoverIntent for person dropdown
	jQuery('.step-field.person-dropdown a').hoverIntent({
			over: slideDownPeople,
			timeout: 0,
			out: slideUpPeople,
	});

	// Function to slide down the people select menu
	function slideDownPeople() {
			jQuery(this).nextAll('.people-select-menu').slideDown();
	}

	// Function to slide up the people select menu
	function slideUpPeople() {
			// This function is intentionally left empty
	}

	// Keep the people select menu visible on mouseover
	jQuery('.people-select-menu').mouseover(function () {
			jQuery(this).stop(true, true).show();
	});

	// Hide the people select menu on mouseleave
	jQuery('.step-field.person-dropdown a, .people-select-menu').mouseleave(function () {
			jQuery('.people-select-menu').delay(300).slideUp();
	});

	// Product calendar layout select
	jQuery('.toggle-style-block a.course-style').click(function (e) {
			e.preventDefault();

			// Toggle active class on the labels and identifiers
			jQuery('.toggle-style-block a.course-style span.toggle-label').toggleClass('active');
			jQuery('.toggle-style-block a.course-style span.toggle-identifier').toggleClass('active');

			// Toggle active class on the clicked link
			jQuery(this).toggleClass('active');

			// Slide toggle the layouts
			jQuery('.training-course-steps .course-step .step-layouts .layouts').slideToggle();
	});

	// Calendar Click events
	jQuery('#wc-bookings-booking-form > fieldset').on('date-selected', function (event, fdate) {
			var date = new Date(Date.parse(fdate)),
					year = date.getFullYear(),
					month = date.getMonth() + 1,
					day = date.getDate();

			// Get the price from the input field
			var price_cost = jQuery('input#cost-of-course').val();
			console.log(price_cost);
			jQuery('input#changed-cost-of-course').attr('value', price_cost);

			// Calculate and update prices
			var multipliedPrices = [price_cost, price_cost * 2, price_cost * 3, price_cost * 4, price_cost * 5];
			multipliedPrices.forEach((price, index) => {
					jQuery(`.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-${index + 1}`).text(price.toFixed(2));
			});

			// Add leading zeros
			month = (month <= 9) ? '0' + month : month;
			day = (day <= 9) ? '0' + day : day;

			// Show step 3 and course date meta if hidden
			jQuery('.training-course-steps .course-step#step-3').slideDown();
			jQuery('.training-sidebar .sidebar-selections .meta#course-date-meta').slideDown();

			// Update date elements
			jQuery('.meta .title span.start-date .dd').text(day);
			jQuery('.meta .title span.start-date .mm').text(month);
			jQuery('.meta .title span.start-date .yyyy').text(year);

			// Get end date
			var end_date = jQuery(`tr[data-start='${day}/${month}/${year}']`).attr('data-end');
			jQuery('.meta .title span.end-date').text(end_date);

			// Update booking date inputs
			jQuery('.single-product form.cart input.booking_date_month').val(month);
			jQuery('.single-product form.cart input.booking_date_day').val(day);
			jQuery('.single-product form.cart input.booking_date_year').val(year);

			// Scroll to step 3
			jQuery('html, body').animate({
					scrollTop: jQuery('.training-course-steps .course-step#step-3').offset().top - 160,
			}, 2000);
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
			jQuery('#course-date-meta .start-date .dd').text(day);
			jQuery('#course-date-meta .start-date .mm').text(month);
			jQuery('#course-date-meta .start-date .yyyy').text(year);

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

			// Check for delegate names and emails
			var missing_delegate_info = jQuery('.outputted-fields input').filter(function () {
					return this.value === '';
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

	// Update delegate name field
	updateDelegateField('div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_name', 'input', 'name');

	// Update delegate level select field
	updateDelegateField('div#delegate-details .delegate-fields .outputted-fields .step-inputs-split select.delegate_level_select', 'change', 'level_select');

	// Update delegate number field
	updateDelegateField('div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_number', 'input', 'number');

	// Update delegate DOB field
	updateDelegateField('div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_dob', 'input', 'dob');
});
