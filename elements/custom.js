jQuery(document).ready(function () {
	//custom scrolling function - use common sense to see how it all fits togehter
	jQuery('.scollingToSection').click(function (e) {
		e.preventDefault();
		var scrollingModule = jQuery(this).attr('data-scrollingModule');
		jQuery('html, body').animate(
			{
				scrollTop: jQuery('#scroll' + scrollingModule).offset().top - 70,
			},
			2000
		);
	});

	//Muliply function
	jQuery.fn.multiply = function (numCopies) {
		var newElements = this.clone().html(this.clone().html().replace(/{X}/g, 1));
		for (var i = 2; i <= numCopies; i++) {
			newElements = newElements.add(
				this.clone()[0].outerHTML.replace(/{X}/g, i)
			);
		}
		return newElements;
	};

	//Persons clickable
	jQuery('.step-field.person-field a').click(function (e) {
		e.preventDefault();
		jQuery('.step-field.person-field a').removeClass('active');
		jQuery(this).addClass('active');
		var personNumber = jQuery(this).attr('data-bookingPerson');
		jQuery(
			'.layouts form.cart .wc-bookings-booking-form .wc_bookings_field_persons input#wc_bookings_field_persons'
		).val(personNumber);
		jQuery('.meta .title.number-of-people').text(personNumber);

		if (jQuery('.training-course-steps .course-step#step-4').is(':hidden')) {
			jQuery('.training-course-steps .course-step#step-4').slideDown();
		}

		var price = jQuery('input#changed-cost-of-course').val();
		var multipliedPrice = price * personNumber;
		var formattedPrice = multipliedPrice.toFixed(2);

		jQuery('.brxe-wc-section.training-course-product').attr(
			'data-people-attending',
			''
		);
		jQuery('.brxe-wc-section.training-course-product').attr(
			'data-people-attending',
			personNumber
		);

		jQuery('input#multi-cost-of-course').val(multipliedPrice);
		jQuery('.row.confirm-row p.total-cost span#total-cost').text(
			formattedPrice
		);

		jQuery('.training-sidebar .sidebar-selections p.from-price.price').hide();
		jQuery('.training-sidebar .sidebar-selections p.total-price.price').show();
		jQuery(
			'.training-sidebar .sidebar-selections p.total-price.price span#total-cost'
		).text(formattedPrice);

		var spaces_remaining = jQuery(
			'.brxe-wc-section.training-course-product'
		).attr('data-spaces-remaining');
		var people_attending = jQuery(
			'.brxe-wc-section.training-course-product'
		).attr('data-people-attending');

		if (parseInt(people_attending) > parseInt(spaces_remaining)) {
			jQuery('#cannotBookCourse')
				.html(
					'We only have ' +
						spaces_remaining +
						' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842. '
				)
				.slideDown();
			jQuery('#confirm-boooking').slideUp();
		} else {
			jQuery('#cannotBookCourse').slideUp();
			jQuery('#confirm-boooking').slideDown();
		}

		jQuery('div#delegate-details').show();
		jQuery('div#delegate-details .outputted-fields').empty();
		jQuery(
			'.single-product form.cart .delegate-name-email-field input[name^="delegate"]'
		).val('');
		jQuery('div#delegate-details .outputted-fields').html(
			jQuery('div#delegate-details .step-inputs-split').multiply(personNumber)
		);
	});

	//People select
	jQuery('.people-select-menu select').on('change', function (e) {
		var personNumber = this.value;
		jQuery(
			'.layouts form.cart .wc-bookings-booking-form .wc_bookings_field_persons input#wc_bookings_field_persons'
		).val(personNumber);
		jQuery('.meta .title.number-of-people').text(personNumber);

		if (jQuery('.training-course-steps .course-step#step-4').is(':hidden')) {
			jQuery('.training-course-steps .course-step#step-4').slideDown();
		}

		var price = jQuery('input#changed-cost-of-course').val();
		var multipliedPrice = price * personNumber;
		var formattedPrice = multipliedPrice.toFixed(2);

		jQuery('.brxe-wc-section.training-course-product').attr(
			'data-people-attending',
			''
		);
		jQuery('.brxe-wc-section.training-course-product').attr(
			'data-people-attending',
			personNumber
		);

		jQuery('input#multi-cost-of-course').val(multipliedPrice);
		jQuery('.row.confirm-row p.total-cost span#total-cost').text(
			formattedPrice
		);

		jQuery('.training-sidebar .sidebar-selections p.from-price.price').hide();
		jQuery('.training-sidebar .sidebar-selections p.total-price.price').show();
		jQuery(
			'.training-sidebar .sidebar-selections p.total-price.price span#total-cost'
		).text(formattedPrice);

		var spaces_remaining = jQuery(
			'.brxe-wc-section.training-course-product'
		).attr('data-spaces-remaining');
		var people_attending = jQuery(
			'.brxe-wc-section.training-course-product'
		).attr('data-people-attending');

		if (parseInt(people_attending) > parseInt(spaces_remaining)) {
			jQuery('#cannotBookCourse')
				.html(
					'We only have ' +
						spaces_remaining +
						' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842. '
				)
				.slideDown();
			jQuery('#confirm-boooking').slideUp();
		} else {
			jQuery('#cannotBookCourse').slideUp();
			jQuery('#confirm-boooking').slideDown();
		}

		jQuery('div#delegate-details').show();
		jQuery('div#delegate-details .outputted-fields').empty();
		jQuery(
			'.single-product form.cart .delegate-name-email-field input[name^="delegate"]'
		).val('');
		jQuery('div#delegate-details .outputted-fields').html(
			jQuery('div#delegate-details .step-inputs-split').multiply(personNumber)
		);
	});

	//product calendar layout select
	jQuery('.toggle-style-block a.course-style').click(function (e) {
		e.preventDefault();
		jQuery('.toggle-style-block a.course-style span.toggle-label').toggleClass(
			'active'
		);
		jQuery(
			'.toggle-style-block a.course-style span.toggle-identifier'
		).toggleClass('active');
		jQuery(this).toggleClass('active');
		jQuery(
			'.training-course-steps .course-step .step-layouts .layouts'
		).slideToggle();
	});

	//Calendar Click events
	jQuery('#wc-bookings-booking-form > fieldset').on(
		'date-selected',
		function (event, fdate) {
			var date = new Date(Date.parse(fdate)), // The selected DATE Object
				year = date.getFullYear(), // Year in numeric value with 4 digits
				month = date.getMonth() + 1, // Month in numeric value from 0 to 11
				day = date.getDate(); // Day in numeric value from 1 to 31

			if (year == 2023) {
				var price_cost = jQuery('input#cost-of-course-2023').val();
				console.log(price_cost);
				jQuery('input#changed-cost-of-course').attr('value', price_cost);

				var multipliedPrice2 = price_cost * 2;
				var multipliedPrice3 = price_cost * 3;
				var multipliedPrice4 = price_cost * 4;
				var multipliedPrice5 = price_cost * 5;
				var formattedPrice2 = multipliedPrice2.toFixed(2);
				var formattedPrice3 = multipliedPrice3.toFixed(2);
				var formattedPrice4 = multipliedPrice4.toFixed(2);
				var formattedPrice5 = multipliedPrice5.toFixed(2);

				jQuery(
					'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-1'
				).text(price_cost);
				jQuery(
					'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-2'
				).text(formattedPrice2);
				jQuery(
					'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-3'
				).text(formattedPrice3);
				jQuery(
					'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-4'
				).text(formattedPrice4);
				jQuery(
					'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-5'
				).text(formattedPrice5);
			} else {
				var price_cost = jQuery('input#cost-of-course').val();
				console.log(price_cost);
				jQuery('input#changed-cost-of-course').attr('value', price_cost);

				var multipliedPrice2 = price_cost * 2;
				var multipliedPrice3 = price_cost * 3;
				var multipliedPrice4 = price_cost * 4;
				var multipliedPrice5 = price_cost * 5;
				var formattedPrice2 = multipliedPrice2.toFixed(2);
				var formattedPrice3 = multipliedPrice3.toFixed(2);
				var formattedPrice4 = multipliedPrice4.toFixed(2);
				var formattedPrice5 = multipliedPrice5.toFixed(2);

				jQuery(
					'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-1'
				).text(price_cost);
				jQuery(
					'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-2'
				).text(formattedPrice2);
				jQuery(
					'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-3'
				).text(formattedPrice3);
				jQuery(
					'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-4'
				).text(formattedPrice4);
				jQuery(
					'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-5'
				).text(formattedPrice5);
			}

			//Add leading 0s
			if (month <= 9) {
				month = '0' + month;
			}

			if (day <= 9) {
				day = '0' + day;
			}

			if (jQuery('.training-course-steps .course-step#step-3').is(':hidden')) {
				jQuery('.training-course-steps .course-step#step-3').slideDown();
			}

			if (
				jQuery(
					'.training-sidebar .sidebar-selections .meta#course-date-meta'
				).is(':hidden')
			) {
				jQuery(
					'.training-sidebar .sidebar-selections .meta#course-date-meta'
				).slideDown();
			}

			jQuery('.meta .title span.start-date .dd').text(day);
			jQuery('.meta .title span.start-date .mm').text(month);
			jQuery('.meta .title span.start-date .yyyy').text(year);

			//Get end date
			var end_date;
			end_date = jQuery(
				"tr[data-start='" + day + '/' + month + '/' + year + "']"
			).attr('data-end');

			jQuery('.meta .title span.end-date').text(end_date);

			jQuery('html, body').animate(
				{
					scrollTop:
						jQuery('.training-course-steps .course-step#step-3').offset().top -
						160,
				},
				2000
			);
		}
	);

	//on click of book now table button
	jQuery('.table-section a.book-now-button').click(function (e) {
		e.preventDefault();

		var day = jQuery(this).attr('data-day');
		var month = jQuery(this).attr('data-month');
		var year = jQuery(this).attr('data-year');

		if (year == 2023) {
			var price_cost = jQuery('input#cost-of-course-2023').val();
			console.log(price_cost);
			jQuery('input#changed-cost-of-course').attr('value', price_cost);

			var multipliedPrice2 = price_cost * 2;
			var multipliedPrice3 = price_cost * 3;
			var multipliedPrice4 = price_cost * 4;
			var multipliedPrice5 = price_cost * 5;
			var formattedPrice2 = multipliedPrice2.toFixed(2);
			var formattedPrice3 = multipliedPrice3.toFixed(2);
			var formattedPrice4 = multipliedPrice4.toFixed(2);
			var formattedPrice5 = multipliedPrice5.toFixed(2);

			jQuery(
				'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-1'
			).text(price_cost);
			jQuery(
				'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-2'
			).text(formattedPrice2);
			jQuery(
				'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-3'
			).text(formattedPrice3);
			jQuery(
				'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-4'
			).text(formattedPrice4);
			jQuery(
				'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-5'
			).text(formattedPrice5);
		} else {
			var price_cost = jQuery('input#cost-of-course').val();
			console.log(price_cost);
			jQuery('input#changed-cost-of-course').attr('value', price_cost);

			var multipliedPrice2 = price_cost * 2;
			var multipliedPrice3 = price_cost * 3;
			var multipliedPrice4 = price_cost * 4;
			var multipliedPrice5 = price_cost * 5;
			var formattedPrice2 = multipliedPrice2.toFixed(2);
			var formattedPrice3 = multipliedPrice3.toFixed(2);
			var formattedPrice4 = multipliedPrice4.toFixed(2);
			var formattedPrice5 = multipliedPrice5.toFixed(2);

			jQuery(
				'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-1'
			).text(price_cost);
			jQuery(
				'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-2'
			).text(formattedPrice2);
			jQuery(
				'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-3'
			).text(formattedPrice3);
			jQuery(
				'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-4'
			).text(formattedPrice4);
			jQuery(
				'.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-5'
			).text(formattedPrice5);
		}

		//Add leading 0s
		if (month <= 9) {
			month = '0' + month;
		}

		if (day <= 9) {
			day = '0' + day;
		}

		if (jQuery('.training-course-steps .course-step#step-3').is(':hidden')) {
			jQuery('.training-course-steps .course-step#step-3').slideDown();
		}

		if (
			jQuery('.training-sidebar .sidebar-selections .meta#course-date-meta').is(
				':hidden'
			)
		) {
			jQuery(
				'.training-sidebar .sidebar-selections .meta#course-date-meta'
			).slideDown();
		}

		jQuery('.meta .title span.start-date .dd').text(day);
		jQuery('.meta .title span.start-date .mm').text(month);
		jQuery('.meta .title span.start-date .yyyy').text(year);

		//Get end date
		var end_date;
		end_date = jQuery(
			"tr[data-start='" + day + '/' + month + '/' + year + "']"
		).attr('data-end');

		jQuery('.meta .title span.end-date').text(end_date);

		jQuery('.single-product form.cart input.booking_date_month').val(month);
		jQuery('.single-product form.cart input.booking_date_day').val(day);
		jQuery('.single-product form.cart input.booking_date_year').val(year);

		jQuery('html, body').animate(
			{
				scrollTop:
					jQuery('.training-course-steps .course-step#step-3').offset().top -
					160,
			},
			2000
		);
	});

	//Submit Cart Button
	jQuery('a#confirm-boooking').click(function (e) {
		e.preventDefault();

		//Check for delegate names and emails
		var missing_delegate_info = jQuery('.outputted-fields input').filter(
			function () {
				return this.value === '';
			}
		);

		if (missing_delegate_info.length > 0) {
			jQuery('.outputted-fields input:empty').addClass('error');
			jQuery('#delegate_info_error').slideDown();
			jQuery('html, body').animate(
				{
					scrollTop: jQuery('#delegate_info_error').offset().top - 250,
				},
				2000
			);
			return false;
		}

		jQuery('div#layout-calendar form.cart').submit();
	});

	//check certification checkboxes
	jQuery('.step-field.qualification-field a').click(function (e) {
		e.preventDefault();
		jQuery(this).toggleClass('active');

		var classNotFound = false;
		jQuery('.step-field.qualification-field a').each(function () {
			if (!jQuery(this).hasClass('active')) {
				classNotFound = true;
			}
		});

		if (classNotFound == false) {
			if (jQuery('.training-course-steps .course-step#step-5').is(':hidden')) {
				jQuery('.training-course-steps .course-step#step-5').slideDown();
			}

			if (
				jQuery(
					'.training-sidebar .sidebar-selections .meta#qualifications-meta'
				).is(':hidden')
			) {
				jQuery(
					'.training-sidebar .sidebar-selections .meta#qualifications-meta'
				).slideDown();
			}

			jQuery('html, body').animate(
				{
					scrollTop:
						jQuery('.training-course-steps .course-step#step-5').offset().top -
						160,
				},
				2000
			);

			var spaces_remaining = jQuery(
				'.brxe-wc-section.training-course-product'
			).attr('data-spaces-remaining');
			var people_attending = jQuery(
				'.brxe-wc-section.training-course-product'
			).attr('data-people-attending');

			if (parseInt(people_attending) > parseInt(spaces_remaining)) {
				jQuery('#cannotBookCourse')
					.html(
						'We only have ' +
							spaces_remaining +
							' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842. '
					)
					.slideDown();
				jQuery('#confirm-boooking').slideUp();
			} else {
				jQuery('#cannotBookCourse').slideUp();
				jQuery('#confirm-boooking').slideDown();
			}
		} else {
			if (jQuery('.training-course-steps .course-step#step-5').not(':hidden')) {
				jQuery('.training-course-steps .course-step#step-5').slideUp();
			}
		}
	});

	///////////////////////////////////
	///// People Dropdown Hover Intent //////
	///////////////////////////////////

	jQuery('.step-field.person-dropdown a').click(function (e) {
		e.preventDefault();
	});

	jQuery('.step-field.person-dropdown a').hoverIntent({
		over: slideDownPeople,
		timeout: 0,
		out: SlideUpPeople,
	});

	function slideDownPeople() {
		jQuery(this)
			.nextAll(
				'.training-course-steps .course-step .step-field .people-select-menu'
			)
			.slideDown();
	}

	function SlideUpPeople() {
		//Don't need anything here but need to define something for jQuery
	}

	//When hovering over mega menu keep it visible
	jQuery(
		'.training-course-steps .course-step .step-field .people-select-menu'
	).mouseover(function () {
		jQuery(this).stop(true, true).show();
	});

	//When leaving the mega menu fade it out
	jQuery(
		'.step-field.person-dropdown a, .training-course-steps .course-step .step-field .people-select-menu'
	).mouseleave(function () {
		jQuery(
			'.training-course-steps .course-step .step-field .people-select-menu'
		)
			.delay(300)
			.slideUp();
	});

	///////////////////////////////////
	///// End People Dropdown Hover Intent //////
	///////////////////////////////////

	//custom scrolling function - use common sense to see how it all fits togehter
	jQuery('.row.title-row.step-title a.previous-step').click(function (e) {
		e.preventDefault();
		var currentStep = jQuery(this).attr('data-step');
		var previousStep = jQuery(this).attr('data-step') - 1;
		jQuery('html, body').animate(
			{
				scrollTop:
					jQuery(
						'.training-course-steps .course-step#step-' + previousStep
					).offset().top - 160,
			},
			2000
		);
		jQuery('.training-course-steps .course-step#step-' + currentStep).slideUp();
	});

	if (jQuery(window).width() < 1200) {
		jQuery(
			'.header .menu-main-menu-container ul li.menu-item-has-children > a'
		).click(function (e) {
			e.preventDefault();
		});
	}

	jQuery('form.slider-form').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();
		//alert("Form Submission prevented / stopped.");
		//var values = jQuery(this).serialize();
		//console.log(values);
		var courseUrl = jQuery(this)
			.find('select[name="course"]')
			.val()
			.replace(/\/$/, '');
		var courseLocation = jQuery(this).find('select[name="location"]').val();
		var newUrl = courseUrl + '-' + courseLocation;
		//console.log(newUrl);
		window.open(newUrl, '_blank');
	});

	//change values of the actual form fields for people
	jQuery('body').on(
		'input',
		'div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_name',
		function (e) {
			var fieldNumber = jQuery(this).attr('data-number');
			var fieldValue = jQuery(this).val();

			jQuery(
				'.single-product form.cart input[name="delegate[' +
					fieldNumber +
					'][name]"]'
			).val();
			jQuery(
				'.single-product form.cart input[name="delegate[' +
					fieldNumber +
					'][name]"]'
			).val(fieldValue);
		}
	);
    // Function to handle changes for delegate level select
    jQuery('body').on(
        'change',
        'div#delegate-details .delegate-fields .outputted-fields .step-inputs-split select.delegate_level_select',
        function(e) {
            var fieldNumber = jQuery(this).attr('data-number');
            var fieldValue = jQuery(this).val();

            // Update the corresponding hidden input field with the selected value
            jQuery('input[name="delegate[' + fieldNumber + '][level_select]"]').val(fieldValue);
        }
    );

	//change values of the actual form fields for people
	jQuery('body').on(
		'input',
		'div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_number',
		function (e) {
			var fieldNumber = jQuery(this).attr('data-number');
			var fieldValue = jQuery(this).val();

			jQuery(
				'.single-product form.cart input[name="delegate[' +
					fieldNumber +
					'][number]"]'
			).val();
			jQuery(
				'.single-product form.cart input[name="delegate[' +
					fieldNumber +
					'][number]"]'
			).val(fieldValue);
		}
	);

	//change values of the actual form fields for people
	jQuery('body').on(
		'input',
		'div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_dob',
		function (e) {
			var fieldNumber = jQuery(this).attr('data-number');
			var fieldValue = jQuery(this).val();

			jQuery(
				'.single-product form.cart input[name="delegate[' +
					fieldNumber +
					'][dob]"]'
			).val();
			jQuery(
				'.single-product form.cart input[name="delegate[' +
					fieldNumber +
					'][dob]"]'
			).val(fieldValue);
		}
	);


});
