jQuery( document ).ready(function() {

	//adds custom styling to your select menus
	jQuery('.fields select:not([name="menu-890"]):not([name="menu-889"]):not(#course), .form-row select, .filter-container .sort-container select, .people-select-menu select, .woocommerce #payment .form-row select, .woocommerce-page #payment .form-row select').selectize();
	
  //adds custom styling to your select menus
	jQuery('.fields select[name="menu-889"]').selectize({
		placeholder: 'Select options...',
	});
	
	//adds custom styling to your select menus
	jQuery('.fields select[name="menu-890"]').selectize({
		placeholder: 'Select location...',
	});

	jQuery('.fields select#course').selectize({
		render: {
			option: function (data, escape) {
				return "<div class='option' data-locations='" + escape(data.locations) + "'>" + escape(data.text) + "</div>"
			}
		}
	});

	//Makes sticky elements work on unsupported browsers
	var elements = jQuery('.sticky');
	Stickyfill.add(elements);

	//adds cta class to cf7 to save you the css
	jQuery('.wpcf7-submit, form#commentform input#submit').addClass('cta-button');
	jQuery('form#commentform input#submit').addClass('secondary');

	//Add style to tables by standard if someone misses the styling
	jQuery('.standard-post-content table').addClass('table');

	//Wrap youtube videos in container to style
	jQuery('iframe[src*="youtube"]').wrap("<div class='youtube-responsive-container'></div>");

	// slick for accreditation posts
	jQuery('.accreditation-list .accredications-list-inner').slick({
	  infinite: true,
	  slidesToShow: 5,
	  slidesToScroll: 1,
	  prevArrow : '<button type="button" class="fal previous fa-chevron-left general">',
	  nextArrow : '<button type="button" class="fal next fa-chevron-right general">',
	  dots: false,
	  arrows: false,
	  autoplay: true,
	  autoplaySpeed: 2000,
		  responsive: [
			{
			  breakpoint: 1200,
			  settings: {
				slidesToShow: 5,
				slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 991,
			  settings: {
				slidesToShow: 4,
				slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 767,
			  settings: {
				slidesToShow: 2,
				slidesToScroll: 1
			  }
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		]
	});

	// slick for news posts
	jQuery('.post-list-slider').slick({
	  infinite: true,
	  slidesToShow: 3,
	  slidesToScroll: 1,
	  prevArrow : '<button type="button" class="fal previous fa-chevron-left general">',
	  nextArrow : '<button type="button" class="fal next fa-chevron-right general">',
	  dots: true,
	  arrows: true,
		  responsive: [
			{
			  breakpoint: 1200,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 991,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 767,
			  settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			  }
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		]
	});

	// acc with minmum fuss, please maintain html layout from example
	jQuery(".acc-title").click(function(e) {
		e.preventDefault();
		jQuery(this).parent().toggleClass('active');
		jQuery(this).parent().children('.acc-section').slideToggle(700);
	});

	///////////////////////////////////
	///// Sub Menu Hover Intent //////
	///////////////////////////////////

		jQuery(".header ul.menu > li.menu-item-has-children a").hoverIntent({
			over: slideDownSub,
			timeout: 0,
			out: SlideUpSub,
		});

		function slideDownSub(){
			jQuery(this).nextAll(".header ul.menu > li > ul.sub-menu").slideDown();
		}

		function SlideUpSub(){
			//Don't need anything here but need to define something for jQuery
		}

		//When hovering over mega menu keep it visible
		jQuery(".header ul.menu > li > ul.sub-menu").mouseover(function () {
			jQuery(this).stop(true, true).show();
		});

		//When leaving the mega menu fade it out
		jQuery(".header li.menu-item-has-children a, .header ul.menu > li > ul.sub-menu").mouseleave(function () {
			jQuery(".header ul.menu > li > ul.sub-menu").delay(300).slideUp();
		});

	///////////////////////////////////
	///// End Sub Menu Hover Intent //////
	///////////////////////////////////

	// scrolling function using data classes
	jQuery(window).on("scroll", function () {
		if (jQuery(window).scrollTop() > 50) {
			jQuery(".header").addClass("fixed");
		}
		else {
			jQuery(".header").removeClass("fixed");
		}
	});

	// opens mmobile menu
	jQuery("a#burgerMenu").click(function(e) {
		e.preventDefault();
		jQuery(this).toggleClass('clicked');
		jQuery('.mobile-menu-container').toggleClass('active');
	 });

	/* control burger menu sub options */
	jQuery("ul#menu-mobile-menu li.menu-item-has-children > a").click(function(e) {
		e.preventDefault();
		jQuery(this).toggleClass('active');
		jQuery(this).nextAll("ul.sub-menu").slideToggle();
	});

	//custom scrolling function - use common sense to see how it all fits togehter
	jQuery(".scollingToSection").click(function(e) {
		e.preventDefault();
		var scrollingModule = jQuery(this).attr('data-scrollingModule');
		jQuery('html, body').animate({
			scrollTop: jQuery("#scroll"+scrollingModule).offset().top - 70
		}, 2000);
	});

	/* handles forms + custom loaders */
	/*custom spinner on forms*/
	jQuery( ".wpcf7" ).append( '<div class="form-overlay"><div class="lds-ring vhboth"><div></div><div></div><div></div><div></div></div></div>' );

	// Show new spinner on Send button click
	jQuery('.wpcf7-submit').on('click', function () {
		jQuery(this).parent().parent().parent().find('.form-overlay').css({ visibility: 'visible' });
	});

	// Hide new spinner on result
	jQuery('div.wpcf7').on('wpcf7invalid wpcf7spam wpcf7mailsent wpcf7mailfailed', function () {
		jQuery(this).parent().parent().find('.form-overlay').css({ visibility: 'hidden' });
	});

	// adds lighbox functionality to posts and pages
	jQuery(".standard-post a[href*='.png'],.standard-post a[href*='.jpg'],.standard-post a[href*='.gif']").attr( "data-lightbox", "postImages" );

	// lightbox options (leave if you can)
	lightbox.option({
	  'resizeDuration': 200,
	  'wrapAround': true
	})


	//Call and phone tracking
	 jQuery('a[href^="tel:"]').click(function() {
		ga( 'send', 'event', 'Call', 'Click', jQuery(this).attr("href") );
	});
	jQuery('a[href^="mailto:"]').click(function() {
		ga( 'send', 'event', 'Email', 'Click', jQuery(this).attr("href") );
	});

	//form tracking
	document.addEventListener( 'wpcf7mailsent', function( event ) {
		if ( '352' === event.detail.contactFormId ) {

		} else {
			ga( 'send', 'event', 'Contact Form', 'submit' );
		}
	}, false );



	jQuery(".tabs-section ul.nav li:nth-child(1) a").addClass("active");
	jQuery(".tab-content > .tab-pane:nth-child(1)").addClass("fade active show");


	// slick for accreditation posts
	jQuery('.related-products-standard').slick({
	  infinite: false,
	  slidesToShow: 4,
	  slidesToScroll: 1,
	  prevArrow : '<button type="button" class="fal previous fa-long-arrow-left general">',
	  nextArrow : '<button type="button" class="fal next fa-long-arrow-right general">',
	  dots: false,
	  arrows: true,
		  responsive: [
			{
			  breakpoint: 1200,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 767,
			  settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				adaptiveHeight: true
			  }
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		]
	});

	// slick for accreditation posts
	jQuery('.partners-global-block .partner-quotes-slider').slick({
	  infinite: false,
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  prevArrow : '<button type="button" class="fal previous fa-long-arrow-left general">',
	  nextArrow : '<button type="button" class="fal next fa-long-arrow-right general">',
	  dots: false,
	  arrows: true,
	  adaptiveHeight: true,
		  responsive: [
			{
			  breakpoint: 767,
			  settings: {
				adaptiveHeight: true
			  }
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		]
	});

	//custom partner tabs
	jQuery(".row.logos-top article:nth-child(1) a.partner-logo").addClass('active');

	//custom partner tabs
	jQuery(".row.logos-top a.partner-logo").click(function(e) {
		e.preventDefault();
		jQuery(".row.logos-top a.partner-logo").removeClass('active');
		jQuery(this).addClass('active');
		var slideno = jQuery(this).data('partner');
		jQuery('.partners-global-block .partner-quotes-slider').slick('slickGoTo', slideno - 1);
	});

	//custom partner tabs
	jQuery('.partners-global-block .partner-quotes-slider').on('afterChange', function(event, slick, currentSlide, nextSlide){
		var SlideNo = currentSlide + 1;
		jQuery(".row.logos-top a.partner-logo").removeClass('active');
		jQuery(".row.logos-top a.partner-logo[data-partner="+SlideNo+"]").addClass('active');
	});
	
	//Muliply function
	jQuery.fn.multiply = function(numCopies) {
		var newElements = this.clone().html(this.clone().html().replace(/{X}/g, 1));
		for(var i = 2; i <= numCopies; i++)
		{
			newElements = newElements.add(
				this.clone()[0].outerHTML.replace(/{X}/g, i)
			);
		}
		return newElements;
	};

	//Persons clickable
	jQuery(".step-field.person-field a").click(function(e) {
		e.preventDefault();
		jQuery(".step-field.person-field a").removeClass('active');
		jQuery(this).addClass('active');
		var personNumber = jQuery(this).attr('data-bookingPerson');
		jQuery(".layouts form.cart .wc-bookings-booking-form .wc_bookings_field_persons input#wc_bookings_field_persons").val(personNumber);
		jQuery(".meta .title.number-of-people").text(personNumber);

		if(jQuery(".training-course-steps .course-step#step-4").is(":hidden")){
			jQuery(".training-course-steps .course-step#step-4").slideDown();
		}

		var price = jQuery("input#changed-cost-of-course").val();
		var multipliedPrice = price * personNumber;
		var formattedPrice = multipliedPrice.toFixed(2);
		
		jQuery(".container-fluid.training-course-product").attr('data-people-attending', "");
		jQuery(".container-fluid.training-course-product").attr('data-people-attending', personNumber);
		
		jQuery("input#multi-cost-of-course").val(multipliedPrice);
		jQuery(".row.confirm-row p.total-cost span#total-cost").text(formattedPrice);
		
		jQuery(".training-sidebar .sidebar-selections p.from-price.price").hide();
		jQuery(".training-sidebar .sidebar-selections p.total-price.price").show();
		jQuery(".training-sidebar .sidebar-selections p.total-price.price span#total-cost").text(formattedPrice);
		
		/*jQuery('html, body').animate({
			scrollTop: jQuery(".training-course-steps .course-step#step-4").offset().top - 160
		}, 2000);*/
		
			var spaces_remaining = jQuery(".container-fluid.training-course-product").attr('data-spaces-remaining');
			var people_attending = jQuery(".container-fluid.training-course-product").attr('data-people-attending');
			
			if(parseInt(people_attending) > parseInt(spaces_remaining)) {
				jQuery("#cannotBookCourse").html('We only have '+spaces_remaining+' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842. ').slideDown();
				jQuery("#confirm-boooking").slideUp();
			} else {
				jQuery("#cannotBookCourse").slideUp();
				jQuery("#confirm-boooking").slideDown();
			}
		
		jQuery('div#delegate-details').show();
		jQuery('div#delegate-details .outputted-fields').empty();
		jQuery('.single-product form.cart .delegate-name-email-field input[name^="delegate"]').val("");
		jQuery('div#delegate-details .outputted-fields').html(jQuery('div#delegate-details .step-inputs-split').multiply(personNumber));
	});

	//People select
	jQuery('.people-select-menu select').on('change', function (e) {
		var personNumber = this.value;
		jQuery(".layouts form.cart .wc-bookings-booking-form .wc_bookings_field_persons input#wc_bookings_field_persons").val(personNumber);
		jQuery(".meta .title.number-of-people").text(personNumber);

		if(jQuery(".training-course-steps .course-step#step-4").is(":hidden")){
			jQuery(".training-course-steps .course-step#step-4").slideDown();
		}

		var price = jQuery("input#changed-cost-of-course").val();
		var multipliedPrice = price * personNumber;
		var formattedPrice = multipliedPrice.toFixed(2);
		
		jQuery(".container-fluid.training-course-product").attr('data-people-attending', "");
		jQuery(".container-fluid.training-course-product").attr('data-people-attending', personNumber);
		
		jQuery("input#multi-cost-of-course").val(multipliedPrice);
		jQuery(".row.confirm-row p.total-cost span#total-cost").text(formattedPrice);
		
		jQuery(".training-sidebar .sidebar-selections p.from-price.price").hide();
		jQuery(".training-sidebar .sidebar-selections p.total-price.price").show();
		jQuery(".training-sidebar .sidebar-selections p.total-price.price span#total-cost").text(formattedPrice);
		
		/*jQuery('html, body').animate({
			scrollTop: jQuery(".training-course-steps .course-step#step-4").offset().top - 160
		}, 2000);*/
		
			var spaces_remaining = jQuery(".container-fluid.training-course-product").attr('data-spaces-remaining');
			var people_attending = jQuery(".container-fluid.training-course-product").attr('data-people-attending');
			
			if(parseInt(people_attending) > parseInt(spaces_remaining)) {
				jQuery("#cannotBookCourse").html('We only have '+spaces_remaining+' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842. ').slideDown();
				jQuery("#confirm-boooking").slideUp();
			} else {
				jQuery("#cannotBookCourse").slideUp();
				jQuery("#confirm-boooking").slideDown();
			}
		
		jQuery('div#delegate-details').show();
		jQuery('div#delegate-details .outputted-fields').empty();
		jQuery('.single-product form.cart .delegate-name-email-field input[name^="delegate"]').val("");
		jQuery('div#delegate-details .outputted-fields').html(jQuery('div#delegate-details .step-inputs-split').multiply(personNumber));
	});

	//product calendar layout select
	jQuery(".toggle-style-block a.course-style").click(function(e) {
		e.preventDefault();
		jQuery(".toggle-style-block a.course-style span.toggle-label").toggleClass('active');
		jQuery(".toggle-style-block a.course-style span.toggle-identifier").toggleClass('active');
		jQuery(this).toggleClass('active');
		jQuery(".training-course-steps .course-step .step-layouts .layouts").slideToggle();
	});

	//Calendar Click events
	jQuery("#wc-bookings-booking-form > fieldset").on('date-selected', function( event, fdate ) {

		var date  = new Date(Date.parse(fdate)), // The selected DATE Object
			year  = date.getFullYear(), // Year in numeric value with 4 digits
			month = date.getMonth() + 1, // Month in numeric value from 0 to 11
			day   = date.getDate(); // Day in numeric value from 1 to 31
		
		if(year == 2023){
			var price_cost = jQuery("input#cost-of-course-2023").val();
			console.log(price_cost);
			jQuery("input#changed-cost-of-course").attr('value', price_cost);
			
			var multipliedPrice2 = price_cost * 2;
			var multipliedPrice3 = price_cost * 3;
			var multipliedPrice4 = price_cost * 4;
			var multipliedPrice5 = price_cost * 5;
			var formattedPrice2 = multipliedPrice2.toFixed(2);
			var formattedPrice3 = multipliedPrice3.toFixed(2);
			var formattedPrice4 = multipliedPrice4.toFixed(2);
			var formattedPrice5 = multipliedPrice5.toFixed(2);
			
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-1').text(price_cost);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-2').text(formattedPrice2);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-3').text(formattedPrice3);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-4').text(formattedPrice4);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-5').text(formattedPrice5);
		} else {
			var price_cost = jQuery("input#cost-of-course").val();
			console.log(price_cost);
			jQuery("input#changed-cost-of-course").attr('value', price_cost);
			
			var multipliedPrice2 = price_cost * 2;
			var multipliedPrice3 = price_cost * 3;
			var multipliedPrice4 = price_cost * 4;
			var multipliedPrice5 = price_cost * 5;
			var formattedPrice2 = multipliedPrice2.toFixed(2);
			var formattedPrice3 = multipliedPrice3.toFixed(2);
			var formattedPrice4 = multipliedPrice4.toFixed(2);
			var formattedPrice5 = multipliedPrice5.toFixed(2);
			
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-1').text(price_cost);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-2').text(formattedPrice2);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-3').text(formattedPrice3);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-4').text(formattedPrice4);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-5').text(formattedPrice5);
		}

		//Add leading 0s
		if(month <= 9) {
			month = '0'+month;
		}

		if(day <= 9) {
			day = '0'+day;
		}


		if(jQuery(".training-course-steps .course-step#step-3").is(":hidden")){
			jQuery(".training-course-steps .course-step#step-3").slideDown();
		}

		if(jQuery(".training-sidebar .sidebar-selections .meta#course-date-meta").is(":hidden")){
			jQuery(".training-sidebar .sidebar-selections .meta#course-date-meta").slideDown();
		}
		
		jQuery(".meta .title span.start-date .dd").text(day);
		jQuery(".meta .title span.start-date .mm").text(month);
		jQuery(".meta .title span.start-date .yyyy").text(year);

		//Get end date
		var end_date;
		end_date = jQuery("tr[data-start='"+day+"/"+month+"/"+year+"']").attr("data-end");

		jQuery(".meta .title span.end-date").text(end_date);
		
		jQuery('html, body').animate({
			scrollTop: jQuery(".training-course-steps .course-step#step-3").offset().top - 160
		}, 2000);
	});

	//on click of book now table button
	jQuery('.table-section a.book-now-button').click(function(e) {
		e.preventDefault();

		var day = jQuery(this).attr('data-day');
		var month = jQuery(this).attr('data-month');
		var year = jQuery(this).attr('data-year');
		
		if(year == 2023){
			var price_cost = jQuery("input#cost-of-course-2023").val();
			console.log(price_cost);
			jQuery("input#changed-cost-of-course").attr('value', price_cost);
			
			var multipliedPrice2 = price_cost * 2;
			var multipliedPrice3 = price_cost * 3;
			var multipliedPrice4 = price_cost * 4;
			var multipliedPrice5 = price_cost * 5;
			var formattedPrice2 = multipliedPrice2.toFixed(2);
			var formattedPrice3 = multipliedPrice3.toFixed(2);
			var formattedPrice4 = multipliedPrice4.toFixed(2);
			var formattedPrice5 = multipliedPrice5.toFixed(2);
			
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-1').text(price_cost);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-2').text(formattedPrice2);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-3').text(formattedPrice3);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-4').text(formattedPrice4);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-5').text(formattedPrice5);
		} else {
			var price_cost = jQuery("input#cost-of-course").val();
			console.log(price_cost);
			jQuery("input#changed-cost-of-course").attr('value', price_cost);
			
			var multipliedPrice2 = price_cost * 2;
			var multipliedPrice3 = price_cost * 3;
			var multipliedPrice4 = price_cost * 4;
			var multipliedPrice5 = price_cost * 5;
			var formattedPrice2 = multipliedPrice2.toFixed(2);
			var formattedPrice3 = multipliedPrice3.toFixed(2);
			var formattedPrice4 = multipliedPrice4.toFixed(2);
			var formattedPrice5 = multipliedPrice5.toFixed(2);
			
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-1').text(price_cost);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-2').text(formattedPrice2);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-3').text(formattedPrice3);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-4').text(formattedPrice4);
			jQuery('.training-course-steps .course-step .step-field a[data-bookingperson] .price #price-5').text(formattedPrice5);
		}

		//Add leading 0s
		if(month <= 9) {
			month = '0'+month;
		}

		if(day <= 9) {
			day = '0'+day;
		}

		if(jQuery(".training-course-steps .course-step#step-3").is(":hidden")){
			jQuery(".training-course-steps .course-step#step-3").slideDown();
		}
		
		if(jQuery(".training-sidebar .sidebar-selections .meta#course-date-meta").is(":hidden")){
			jQuery(".training-sidebar .sidebar-selections .meta#course-date-meta").slideDown();
		}

		jQuery(".meta .title span.start-date .dd").text(day);
		jQuery(".meta .title span.start-date .mm").text(month);
		jQuery(".meta .title span.start-date .yyyy").text(year);

		//Get end date
		var end_date;
		end_date = jQuery("tr[data-start='"+day+"/"+month+"/"+year+"']").attr("data-end");

		jQuery(".meta .title span.end-date").text(end_date);

		jQuery(".single-product form.cart input.booking_date_month").val(month);
		jQuery(".single-product form.cart input.booking_date_day").val(day);
		jQuery(".single-product form.cart input.booking_date_year").val(year);
		
		jQuery('html, body').animate({
			scrollTop: jQuery(".training-course-steps .course-step#step-3").offset().top - 160
		}, 2000);
	});

	//Submit Cart Button
	jQuery('a#confirm-boooking').click(function(e) {
		e.preventDefault();
		
		//Check for delegate names and emails
		var missing_delegate_info = jQuery('.outputted-fields input').filter(function () {
			return this.value === '';
		});
		
		if (missing_delegate_info.length > 0) {
			jQuery('.outputted-fields input:empty').addClass("error");
			jQuery('#delegate_info_error').slideDown();
			jQuery('html, body').animate({
				scrollTop: jQuery('#delegate_info_error').offset().top - 250
			}, 2000);
			return false;
		}
		
		jQuery('div#layout-calendar form.cart').submit();
	});
	
	
	//check certification checkboxes
	jQuery('.step-field.qualification-field a').click(function(e) {
		e.preventDefault();
		jQuery(this).toggleClass('active');
		
		var classNotFound = false;
		jQuery('.step-field.qualification-field a').each(function() {
			if (!jQuery(this).hasClass('active')){
				classNotFound = true;
			}
		});
		
		if(classNotFound == false){
			if(jQuery(".training-course-steps .course-step#step-5").is(":hidden")){
				jQuery(".training-course-steps .course-step#step-5").slideDown();
			}
			
			if(jQuery(".training-sidebar .sidebar-selections .meta#qualifications-meta").is(":hidden")){
				jQuery(".training-sidebar .sidebar-selections .meta#qualifications-meta").slideDown();
			}
			
			jQuery('html, body').animate({
				scrollTop: jQuery(".training-course-steps .course-step#step-5").offset().top - 160
			}, 2000);
			
			var spaces_remaining = jQuery(".container-fluid.training-course-product").attr('data-spaces-remaining');
			var people_attending = jQuery(".container-fluid.training-course-product").attr('data-people-attending');
			
			if(parseInt(people_attending) > parseInt(spaces_remaining)) {
				jQuery("#cannotBookCourse").html('We only have '+spaces_remaining+' spaces left on this date. Please call us to book this date on <br /> 0113 257 0842. ').slideDown();
				jQuery("#confirm-boooking").slideUp();
			} else {
				jQuery("#cannotBookCourse").slideUp();
				jQuery("#confirm-boooking").slideDown();
			}
			
		} else {
			if(jQuery(".training-course-steps .course-step#step-5").not(":hidden")){
				jQuery(".training-course-steps .course-step#step-5").slideUp();
			}
		}
	});
	
	
	///////////////////////////////////
	///// People Dropdown Hover Intent //////
	///////////////////////////////////

		jQuery(".step-field.person-dropdown a").click(function(e) {
			e.preventDefault();
		});

		jQuery(".step-field.person-dropdown a").hoverIntent({
			over: slideDownPeople,
			timeout: 0,
			out: SlideUpPeople,
		});

		function slideDownPeople(){
			jQuery(this).nextAll(".training-course-steps .course-step .step-field .people-select-menu").slideDown();
		}

		function SlideUpPeople(){
			//Don't need anything here but need to define something for jQuery
		}

		//When hovering over mega menu keep it visible
		jQuery(".training-course-steps .course-step .step-field .people-select-menu").mouseover(function () {
			jQuery(this).stop(true, true).show();
		});

		//When leaving the mega menu fade it out
		jQuery(".step-field.person-dropdown a, .training-course-steps .course-step .step-field .people-select-menu").mouseleave(function () {
			jQuery(".training-course-steps .course-step .step-field .people-select-menu").delay(300).slideUp();
		});

	///////////////////////////////////
	///// End People Dropdown Hover Intent //////
	///////////////////////////////////


	//Open Reviews Form
	jQuery('.reviews-title-block a.leave-review').click(function(e) {
		e.preventDefault();
		jQuery('.reviews-product-block .woocommerce-tabs.reviews-form div#review_form_wrapper').slideToggle();
		jQuery('.reviews-product-block .woocommerce-tabs.reviews-form div#comments').slideToggle();
	});

	// true squares for height / width
	jQuery(".trueSquare").each(function(){
		var squareResize = jQuery(this).width();
		jQuery(this).height(squareResize);
	});
	jQuery(window).resize(function(){
		jQuery(".trueSquare").each(function(){
			var squareResize = jQuery(this).width();
			jQuery(this).height(squareResize);
		});
	});

	//custom scrolling function - use common sense to see how it all fits togehter
	jQuery(".row.title-row.step-title a.previous-step").click(function(e) {
		e.preventDefault();
		var currentStep = jQuery(this).attr('data-step');
		var previousStep = jQuery(this).attr('data-step') - 1;
		jQuery('html, body').animate({
			scrollTop: jQuery(".training-course-steps .course-step#step-"+previousStep).offset().top - 160
		}, 2000);
		jQuery(".training-course-steps .course-step#step-"+currentStep).slideUp();
	});


	if (jQuery(window).width() < 768) {

		// slick for accreditation posts
		jQuery('.row.offers-row-mobiles').slick({
		  infinite: false,
		  slidesToShow: 1,
		  slidesToScroll: 1,
		  prevArrow : '<button type="button" class="fal previous fa-long-arrow-left general">',
		  nextArrow : '<button type="button" class="fal next fa-long-arrow-right general">',
		  dots: false,
		  arrows: true
		});

	}

	if (jQuery(window).width() < 1200) {
		jQuery(".header .menu-main-menu-container ul li.menu-item-has-children > a").click(function(e) {
			e.preventDefault();
		});
	}
	
	// slick for news posts
//	jQuery('.featured-cats-listing .featured-courses').slick({
//	  infinite: false,
//	  slidesToShow: 2,
//	  slidesToScroll: 1,
//	  prevArrow : '<button type="button" class="fal previous fa-chevron-left general">',
//	  nextArrow : '<button type="button" class="fal next fa-chevron-right general">',
//	  dots: true,
//	  arrows: false,
//		  responsive: [
//			{
//			  breakpoint: 1200,
//			  settings: {
//				slidesToShow: 2,
//				slidesToScroll: 1
//			  }
//			},
//			{
//			  breakpoint: 991,
//			  settings: {
//				slidesToShow: 2,
//				slidesToScroll: 1
//			  }
//			},
//			{
//			  breakpoint: 769,
//			  settings: {
//				slidesToShow: 1,
//				slidesToScroll: 1
//			  }
//			}
//			// You can unslick at a given breakpoint now by adding:
//			// settings: "unslick"
//			// instead of a settings object
//		]
//	});
	
	// slick for news posts
	jQuery('.centre-gallery-images').slick({
	  infinite: false,
	  slidesToShow: 2,
	  slidesToScroll: 2,
	  prevArrow : '<button type="button" class="fal previous fa-chevron-left general">',
	  nextArrow : '<button type="button" class="fal next fa-chevron-right general">',
	  dots: true,
	  arrows: false
	});
	
	
	
	//custom modal-open-link function
	jQuery(".open-modal").click(function(e) {
		e.preventDefault();
		var modalToOpen = jQuery(this).attr('data-form');
		jQuery(".modal-container-block#"+modalToOpen).fadeIn();
		jQuery('body').addClass('modal-open');
	});
	jQuery(".header .menu-main-menu-container > ul > li#menu-item-1484 > a").click(function(e) {
		e.preventDefault();
		jQuery(".modal-container-block#enquiry").fadeIn();
		jQuery('body').addClass('modal-open');
	});
	
	
	//custom modal-open-link function
	jQuery(".modal-container-block .close-modal").click(function(e) {
		e.preventDefault();
		jQuery(".modal-container-block").fadeOut();
		jQuery('body').removeClass('modal-open');
	});
	
	
	
	//map location modals
	jQuery(".map-main-block a.circle-marker").click(function(e) {
		e.preventDefault();
		jQuery(".map-main-block a.circle-marker").removeClass('active');
		jQuery(this).addClass('active');
		var mapId = jQuery(this).attr('data-map-location');
		jQuery(".map-main-block .map-inner-modal").fadeOut();
		jQuery(".map-main-block .map-inner-modal#map-modal-"+mapId).fadeIn();
	});
	
	//map location modals close
	jQuery(".map-inner-modal .close-map-modal").click(function(e) {
		e.preventDefault();
		jQuery(".map-main-block .map-inner-modal").fadeOut();
		jQuery(".map-main-block a.circle-marker").removeClass('active');
	});
	
	
	
	
	
	
	/*//People select
	jQuery('.slider-main .fields.dropdown-selects select#course').on('change', function (e) {
		var groupId = this.value;
		
		console.log(groupId);
	});*/
	
	
	jQuery('form.slider-form').on('submit', function(event){
		event.preventDefault();
		event.stopPropagation();
		//alert("Form Submission prevented / stopped.");
		//var values = jQuery(this).serialize();
		//console.log(values);
		var courseUrl = jQuery(this).find('select[name="course"]').val().replace(/\/$/,'');;
		var courseLocation = jQuery(this).find('select[name="location"]').val();
		var newUrl = courseUrl+"-"+courseLocation;
		//console.log(newUrl);
		window.open(newUrl, '_blank');
	});
	
	//change values of the actual form fields for people
	jQuery('body').on('input', 'div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_name', function(e) {
		var fieldNumber = jQuery(this).attr('data-number');
		var fieldValue = jQuery(this).val();

		jQuery('.single-product form.cart input[name="delegate['+fieldNumber+'][name]"]').val();
		jQuery('.single-product form.cart input[name="delegate['+fieldNumber+'][name]"]').val(fieldValue);
	});
	
	//change values of the actual form fields for people
	jQuery('body').on('input', 'div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_dob', function(e) {
		var fieldNumber = jQuery(this).attr('data-number');
		var fieldValue = jQuery(this).val();

		jQuery('.single-product form.cart input[name="delegate['+fieldNumber+'][dob]"]').val();
		jQuery('.single-product form.cart input[name="delegate['+fieldNumber+'][dob]"]').val(fieldValue);
	});
	
	//change values of the actual form fields for people
	jQuery('body').on('input', 'div#delegate-details .delegate-fields .outputted-fields .step-inputs-split input.delegate_NI', function(e) {
		var fieldNumber = jQuery(this).attr('data-number');
		var fieldValue = jQuery(this).val();

		jQuery('.single-product form.cart input[name="delegate['+fieldNumber+'][NI]"]').val();
		jQuery('.single-product form.cart input[name="delegate['+fieldNumber+'][NI]"]').val(fieldValue);
	});
	
	

});




/* this handles lazy loading images */
jQuery( window ).load(function() {

	jQuery.fn.ImagesView = function(){
		//Window Object
		var win = jQuery(window);
		//Object to Check
		var obj = jQuery(this);
		//the top Scroll Position in the page
		var scrollPosition = win.scrollTop();
		//the end of the visible area in the page, starting from the scroll position
		var visibleArea = win.scrollTop() + win.height();
		//the end of the object to check
		var objEndPos = (obj.offset().top -0);
		return(visibleArea >= objEndPos && scrollPosition <= objEndPos ? true : false);
	};

	var addLazyLoad = function(){
		jQuery(".lazyLoadImage").each(function(){
		if(jQuery(this).ImagesView()){
			var lazyLoadUrl = jQuery(this).attr('data-imageLoad');
				jQuery(this).css('background-image', 'url(' + lazyLoadUrl + ')');
				jQuery(this).addClass('active');
			}
		});
	};

	setTimeout(addLazyLoad(), 500);

	jQuery(window).scroll(function(){
		addLazyLoad();
	});

	jQuery('table.variations td select').selectize();
	
	jQuery('.gliph-col-container').delay(300).queue('fx', function() { jQuery(this).addClass('active'); });
	
/*jQuery('select#course').change(function(){
	  if(jQuery(this).val() == 'no-option'){
		  jQuery('.location-select').addClass('disabled');
	  }else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-plettac-metrix-training/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
	  }else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/sa-scaffold-subcontract-review-managing-the-risk/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
	 }else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-system-scaffolding-product-training/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
	 }else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-scaffolding-appreciation/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
	}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-supervisor-refresher/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
	}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-supervisor/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
	}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-asi-advanced-scaffold-inspection/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-2-day-skills-test/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-advanced-scaffolder/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-1-day-skills-test/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-scaffolding-course-part-2-tube-and-fitting/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/sa-gin-wheel-rope-use/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/sa-haki-stair-tower/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/sa-scaffolding-appreciation/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/sa-scaffolding-estimator/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/sa-sg4-practical/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/sa-tg20-sg4-theory/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/sa-basic-scaffolding-inspection/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/pasma-mobile-towers-course/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/site-supervision-safety-training-scheme-sssts/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/site-management-safety-training-scheme-smsts/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
		}else if(jQuery(this).val() == 'https://safetyaccess.co.uk/product/cisrs-scaffolding-course-part-1-tube-and-fitting/'){
		jQuery('select#location').addClass('hide-london');
		jQuery('.location-select').removeClass('disabled');
	  } else {
		 jQuery('select#location').removeClass('hide-london');
		 jQuery('.location-select').removeClass('disabled');
	  }
	});*/
	
	jQuery('form.slider-form:not(.new-slider-style) .fields select#course').on('change', function(event) {
		jQuery('form.slider-form:not(.new-slider-style) .fields select#location').removeClass('show-humber');
		jQuery('form.slider-form:not(.new-slider-style) .fields select#location').removeClass('show-london');
		jQuery('form.slider-form:not(.new-slider-style) .fields select#location').removeClass('show-nottingham');
		jQuery('.location-select').addClass('disabled');
		var selectedValue = jQuery(this).val();
		var $selectedOption = jQuery(this)[0].selectize.getOption(selectedValue);
		if ($selectedOption.data('locations').indexOf("lincolnshire") >= 0){
			jQuery('.location-select').removeClass('disabled');
			jQuery('form.slider-form:not(.new-slider-style) .fields select#location').addClass('show-humber');
		}
		if ($selectedOption.data('locations').indexOf("london") >= 0){
			jQuery('.location-select').removeClass('disabled');
			jQuery('form.slider-form:not(.new-slider-style) .fields select#location').addClass('show-london');
		}
		if ($selectedOption.data('locations').indexOf("nottingham") >= 0){
			jQuery('.location-select').removeClass('disabled');
			jQuery('form.slider-form:not(.new-slider-style) .fields select#location').addClass('show-nottingham');
		}
	});

});
