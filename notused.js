// Prevent default action on person dropdown link click
    // @ts-ignore
    jQuery('.step-field.person-dropdown a').click(function (/** @type {{ preventDefault: () => void; }} */ e) {
        // Prevent the default action of the click event (e.g., navigating to a link)
        e.preventDefault();
    });

  // Initialize hoverIntent for person dropdown
  // @ts-ignore
  jQuery('.step-field.person-dropdown a').hoverIntent({
      over: slideDownPeople,
      timeout: 0,
      out: slideUpPeople,
  });

    // Function to handle mouse over event
    function slideDownPeople() {
        // Slide down the next people-select-menu element
        // @ts-ignore
        jQuery(this).nextAll('.people-select-menu').stop(true, true).slideDown();
    }

    // Function to handle mouse out event
    function slideUpPeople() {
        // Intentionally left empty
    }

    // Initialize hoverIntent for person dropdown
    // @ts-ignore
    jQuery('.step-field.person-dropdown a').hoverIntent({
        over: slideDownPeople,
        timeout: 0,
        out: slideUpPeople,
    });

    // Keep the people select menu visible on mouseover
    // @ts-ignore
    jQuery('.people-select-menu').mouseover(function () {
        // @ts-ignore
        jQuery(this).stop(true, true).show();
    });

    // Hide the people select menu on mouseleave
    // @ts-ignore
    jQuery('.step-field.person-dropdown a, .people-select-menu').mouseleave(function () {
        // @ts-ignore
        jQuery('.people-select-menu').delay(300).slideUp();
    });
// @ts-ignore
function jQuery (arg0) {
  throw new Error('Function not implemented.');
}

