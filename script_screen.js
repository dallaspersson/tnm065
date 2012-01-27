 jQuery(document).ready(function() {

	// Remove links in bookings, 
	// since we will use ajax if it is allowed.
	// BOOK BUTTON
	jQuery('.book_btn').each(function(i, obj) {
	    jQuery(obj).click(function(e) {

	    	// Remove previous link
	    	e.preventDefault();

	    	//do other stuff when a click happens

	    	// Get URL and retrive the resource id if there is one in ther url.
	    	// If not, then it gives an empty string ("").
	    	var urlQuery = location.search;
			urlQuery = urlQuery.replace('?', '');
			var split = urlQuery.split('=');

			// Break out the slot ID and repetition ID
			// from the id attribute. (slotID:repetitionID)
			var ids = jQuery(obj).attr("id");
			var split2 = ids.split(':');
			var current_slot = split2[0];
			var current_rep = split2[1];


			// Create array with the data we want to send with POST.
			var postData = {
				action: 'book',
				slot_id: current_slot,
				rep_id: current_rep,
				resource_id: split[1]
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
			            jQuery(obj).removeClass("book_btn").addClass("remove_btn");

			            // Change button text
			            jQuery(obj).fadeTo('slow', 0.0, function() {
							jQuery(obj).text("Remove");
					    });
					    jQuery(obj).fadeTo('slow', 1.0);
			            
			            // Change information about who owns the booking 
			            var user_name = data.user_fname + " " + data.user_lname;

			            jQuery(obj).parent().prev().find("em").fadeTo('slow', 0.0, function() {
							jQuery(obj).parent().prev().find("em").text(user_name);
					    });
					    jQuery(obj).parent().prev().find("em").fadeTo('slow', 1.0);
			        }
			        else{
			         	alert(data.message);  
			        }
			    }
			});
			/*
			jQuery.post(
			    // see tip #1 for how we declare global javascript variables
			    MyAjax.ajaxurl,
			    {
			        // here we declare the parameters to send along with the request
			        // this means the following action hooks will be fired:
			        // wp_ajax_nopriv_myajax-submit and wp_ajax_myajax-submit
			        action : 'myajax-submit',
			 
			        // other parameters can be added along with "action"
			        postID : MyAjax.postID
			    },
			    function( response ) {
			        alert( response );
			    }
			);*/


		});
	});

	// Remove links in bookings, 
	// since we will use ajax if it is allowed.
	// REMOVE BOOKING BUTTON
	jQuery('.remove_btn').each(function(i, obj) {
	    jQuery(obj).click(function(e) {
	    	e.preventDefault();

	    	//do other stuff when a click happens
	    	alert("You can't remove right now..");

		});
	});



 });


