 jQuery(document).ready(function() {

	// Remove links in bookings, 
	// since we will use ajax if it is allowed.
	// BOOK BUTTON
	jQuery('.book_btn').each(function(i, obj) {
	    jQuery(obj).click(function(e) {

	    	// Remove previous link
	    	e.preventDefault();
	
		});

		// Add click event
		jQuery(obj).bind('click', { btn: jQuery(obj) }, bookIt);

	});

	// Remove links in bookings, 
	// since we will use ajax if it is allowed.
	// REMOVE BOOKING BUTTON
	jQuery('.remove_btn').each(function(i, obj) {
	    jQuery(obj).click(function(e) {

	    	e.preventDefault();

		});

		// Add click event
		jQuery(obj).bind('click', { btn: jQuery(obj) }, removeIt);

	});



 });

 function removeIt(o) {
 	// Get URL and retrive the resource id if there is one in ther url.
	    	// If not, then it gives an empty string ("").
	    	var urlQuery = location.search;
			urlQuery = urlQuery.replace('?', '');
			var split = urlQuery.split('=');

			// Break out the slot ID and repetition ID
			// from the id attribute. (slotID:repetitionID)
			var ids = o.data.btn.attr("id");
			var split2 = ids.split(':');
			var current_slot = split2[0];
			var current_rep = split2[1];
			var current_booking = split2[2];


			// Create array with the data we want to send with POST.
			var postData2 = {
				action: 'remove',
				slot_id: current_slot,
				rep_id: current_rep,
				resource_id: split[1],
				booking_id: current_booking
			};

			// Call the ajax.
	    	jQuery.ajax({
			    type: "POST",
			    dataType: "json",
			    data: postData2,
			    beforeSend: function(x) {
			        if(x && x.overrideMimeType) {
			            x.overrideMimeType("application/json;charset=UTF-8");
			        }
			    },
			    url: MyAjax.ajaxurl,
			    success: function(data) {
			        // 'data' is a JSON object which we can access directly.
			        // Evaluate the data.success member and do something appropriate...
			        if (data.success == true){
			            //alert(data.message);
			            
			            // Change elements class to remove_btn
			            o.data.btn.removeClass("remove_btn").addClass("book_btn");

			            // Change button text
			            o.data.btn.fadeTo('slow', 0.0, function() {
							o.data.btn.text("Book");
					    });
					    o.data.btn.fadeTo('slow', 1.0);
			            
			            // Change information about who owns the booking 
			            o.data.btn.parent().prev().find("em").fadeTo('slow', 0.0, function() {
							o.data.btn.parent().prev().find("em").text("Not booked");
					    });
					    o.data.btn.parent().prev().find("em").fadeTo('slow', 1.0);

					    o.data.btn.unbind("click", removeIt);
			    		o.data.btn.bind('click', { btn: o.data.btn }, bookIt);
			        }
			        else{
			         	alert(data.message);  
			        }
			    }
			});
 }

 function bookIt(o) {
	//alert("hej" + o.data.btn.text());

	// Get URL and retrive the resource id if there is one in ther url.
	// If not, then it gives an empty string ("").
	var urlQuery = location.search;
	urlQuery = urlQuery.replace('?', '');
	var split = urlQuery.split('=');

	// Break out the slot ID and repetition ID
	// from the id attribute. (slotID:repetitionID)
	var ids = o.data.btn.attr("id");
	var split2 = ids.split(':');
	var current_slot = split2[0];
	var current_rep = split2[1];


	// Create array with the data we want to send with POST.
	var postData = {
		action: 'book',
		slot_id: current_slot,
		rep_id: current_rep,
		resource_id: split[1],
		booking_id: null
	};

	// Call the ajax.
	jQuery.ajax({
	    type: "POST",
	    dataType: "json",
	    data: postData,
	    beforeSend: function(x) {
	        if(x && x.overrideMimeType) {
	            x.overrideMimeType("application/json;charset=UTF-8");
	        }
	    },
	    url: MyAjax.ajaxurl,
	    success: function(data) {
	        // 'data' is a JSON object which we can access directly.
	        // Evaluate the data.success member and do something appropriate...
	        if (data.success == true){
	            //alert(data.message);

	            // Change elements class to remove_btn
	            o.data.btn.removeClass("book_btn").addClass("remove_btn");

	            // Change button text
	            o.data.btn.fadeTo('slow', 0.0, function() {
					o.data.btn.text("Remove");
			    });
			    o.data.btn.fadeTo('slow', 1.0);
	            
	            // Change information about who owns the booking 
	            var user_name = data.user_fname + " " + data.user_lname;

	            o.data.btn.parent().prev().find("em").fadeTo('slow', 0.0, function() {
					o.data.btn.parent().prev().find("em").text(user_name);
			    });
			    o.data.btn.parent().prev().find("em").fadeTo('slow', 1.0);

			    o.data.btn.unbind("click", bookIt);
			    o.data.btn.bind('click', { btn: o.data.btn }, removeIt);
	        }
	        else{
	         	alert(data.message);  
	        }
	    }
	}); 	
 }


