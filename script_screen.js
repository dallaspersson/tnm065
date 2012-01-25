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

			// Create array with the data we want to send with POST.
			var postData = {
				action: 'book',
				slot_id: jQuery(obj).attr("id"),
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
			            alert(data.message);
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


