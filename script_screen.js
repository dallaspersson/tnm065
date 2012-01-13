 jQuery(document).ready(function() {

	// Remove links in bookings, 
	// since we will use ajax if it is allowed.
	jQuery('.book_btn').each(function(i, obj) {
	    jQuery(obj).click(function(e) {
	    	e.preventDefault();
	    	//do other stuff when a click happens
		});
	});

	jQuery('.remove_btn').each(function(i, obj) {
	    jQuery(obj).click(function(e) {
	    	e.preventDefault();
	    	//do other stuff when a click happens
		});
	});



 });


