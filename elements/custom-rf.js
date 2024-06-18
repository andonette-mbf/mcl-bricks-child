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

	function updateBookingDetails(personNumber) {
			jQuery('#wc_bookings_field_persons').val(personNumber);
			jQuery('.meta .title.number-of-people').text(personNumber);

			var price = jQuery('#changed-cost-of-course').val();
			var multipliedPrice = price * personNumber;
			var formattedPrice = multipliedPrice.toFixed(2);

			jQuery('.training-course-product').attr('data-people-attending', personNumber);
			jQuery('#multi-cost-of-course').val(multipliedPrice);
			jQuery('#total-cost').text(formattedPrice);

			jQuery('.from-price.price').hide();
			jQuery('.total-price.price').show();
			jQuery('.total-price.price #total-cost').text(formattedPrice);

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

			jQuery('#delegate-details').show();
			jQuery('#delegate-details .outputted-fields').empty();
			jQuery('.single-product form.cart .delegate-name-email-field input[name^="delegate"]').val('');
			jQuery('#delegate-details .outputted-fields').html(jQuery('#delegate-details .step-inputs-split').multiply(personNumber));
	}

	// Persons clickable
	jQuery('.step-field.person-field a').click(function (e) {
			e.preventDefault();
			jQuery('.step-field.person-field a').removeClass('active');
			jQuery(this).addClass('active');
			var personNumber = jQuery(this).attr('data-bookingPerson');
			updateBookingDetails(personNumber);
	});

	// People select
	jQuery('.people-select-menu select').on('change', function (e) {
			var personNumber = this.value;
			updateBookingDetails(personNumber);
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

	function slideDownPeople() {
			jQuery(this).nextAll('.people-select-menu').slideDown();
	}

	function slideUpPeople() {
			// This function is intentionally left empty
	}

	jQuery('.people-select-menu').mouseover(function () {
			jQuery(this).stop(true, true).show();
	});

	jQuery('.step-field.person-dropdown a, .people-select-menu').mouseleave(function () {
			jQuery('.people-select-menu').delay(300).slideUp();
	});

	// Product calendar layout select
	jQuery('.toggle-style-block a.course-style').click(function (e) {
			e.preventDefault();
			jQuery('.toggle-style-block a.course-style span.toggle-label').toggleClass('active');
			jQuery('.toggle-style-block a.course-style span.toggle-identifier').toggleClass('active');
			jQuery(this).toggleClass('active');
			jQuery('.training-course-steps .course-step .step-layouts .layouts').slideToggle();
	});

	function updatePrices(price_cost) {
			var multipliedPrices = [price_cost, price_cost * 2, price_cost * 3, price_cost * 4, price_cost * 5];
			multipliedPrices.forEach((price, index) => {
					jQuery(`.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-${index + 1}`).text(price.toFixed(2));
			});
	}

	// Calendar Click events
	jQuery('#wc-bookings-booking-form > fieldset').on('date-selected', function (event, fdate) {
			var date = new Date(Date.parse(fdate)),
					year = date.getFullYear(),
					month = date.getMonth() + 1,
					day = date.getDate();

			var price_cost = jQuery('input#cost-of-course').val();
			console.log(price_cost);
			jQuery('input#changed-cost-of-course').attr('value', price_cost);

			updatePrices(price_cost);

			month = (month <= 9) ? '0' + month : month;
			day = (day <= 9) ? '0' + day : day;

			jQuery('.training-course-steps .course-step#step-3').slideDown();
			jQuery('.training-sidebar .sidebar-selections .meta#course-date-meta').slideDown();

			jQuery('.meta .title span.start-date .dd').text(day);
			jQuery('.meta .title span.start-date .mm').text(month);
			jQuery('.meta .title span.start-date .yyyy').text(year);

			var end_date = jQuery(`tr[data-start='${day}/${month}/${year}']`).attr('data-end');
			jQuery('.meta .title span.end-date').text(end_date);

			jQuery('.single-product form.cart input.booking_date_month').val(month);
			jQuery('.single-product form.cart input.booking_date_day').val(day);
			jQuery('.single-product form.cart input.booking_date_year').val(year);

			jQuery('html, body').animate({
					scrollTop: jQuery('.training-course-steps .course-step#step-3').offset().top - 160,
			}, 2000);
	});

	// On click of book now table button
	jQuery('.table-section a.book-now-button').click(function (e) {
			e.preventDefault();

			var day = jQuery(this).attr('data-day');
			var month = jQuery(this).attr('data-month');
			var year = jQuery(this).attr('data-year');

			var price_cost = parseFloat(jQuery('input#cost-of-course').val());
			console.log(price_cost);
			jQuery('input#changed-cost-of-course').attr('value', price_cost);

			updatePrices(price_cost);

			month = (month <= 9) ? '0' + month : month;
			day = (day <= 9) ? '0' + day : day;

			jQuery('.training-course-steps .course-step#step-3').slideDown();
			jQuery('.training-sidebar .sidebar-selections .meta#course-date-meta').slideDown();

			const fullDate = `${day}/${month}/${year}`;
			jQuery('#course-date-meta .start-date').text(fullDate);

			var end_date = jQuery(`tr[data-start='${day}/${month}/${year}']`).attr('data-end');
			jQuery('#course-date-meta .end-date').text(end_date);

			jQuery('.single-product form.cart input.booking_date_month').val(month);
			jQuery('.single-product form.cart input.booking_date_day').val(day);
			jQuery('.single-product form.cart input.booking_date_year').val(year);

			jQuery('html, body').animate({
					scrollTop: jQuery('.training-course-steps .course-step#step-3').offset().top - 160,
			}, 2000);
	});

	// Submit Cart Button
	jQuery('a#confirm-boooking').click(function (e) {
			e.preventDefault();

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

	function updateDelegateField(selector, event, fieldType) {
			jQuery('body').on(event, selector, function (e) {
					var fieldNumber = jQuery(this).attr('data-number');
					var fieldValue = jQuery(this).val();
					var inputName = 'delegate[' + fieldNumber + '][' + fieldType + ']';
​⬤