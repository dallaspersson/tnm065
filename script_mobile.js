 jQuery(document).ready(function() {

	// Remove the button for the drop down.
	jQuery('#dropdown_button').remove();

	// When the dropdown value changes..
	jQuery('#dropdown').change(function() {
		// Get the value of the selected item in the dropdown.
		link = jQuery("#dropdown").val();

		// Redirect
		window.location = link;
	});

 });


