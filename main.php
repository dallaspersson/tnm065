<?php
/*
Plugin Name: TimeSlot
Plugin URI: http://marcusstenbeck.com/timeslot
Description: A booking system.
Version: 0.1
Author: Marcus Stenbeck
Author URI: http://marcusstenbeck.com
License: I don't know yet.
.
Why are other booking systems shit or crazy expensive?
.
*/

include 'TS_WordpressDatabaseConnector.php';
include 'TS_Resource.php';
include 'TS_User.php';
include 'TS_Slot.php';
include 'TS_Booking.php';
include 'TS_CompositeSchedule.php';

class TimeSlot
{
	// Variables

	private $plugin_dir;
	private $plugin_url;
	
	public function __construct()
	{
		add_shortcode('timeslot', array($this, 'shortcode'));
		$this->plugin_dir = WP_PLUGIN_DIR .'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		$this->plugin_url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	}

	public function enqueue_my_styles()
	{	
		// Link css
		$tmp_url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		wp_enqueue_style( 'stylesheet-html-screen', $tmp_url . 'stylesheet-html-screen.css');
	}
	
	public function shortcode()
	{
		if(isset($_GET['resource_id']))
			$resource_id = $_GET['resource_id'];
	
		// Create an XML document with the Timeslot DTD
		$implementation = new DOMImplementation();
		$dtd = $implementation->createDocumentType('timeslot', '', $this->plugin_dir . 'timeslot.dtd');
		$xml = $implementation->createDocument('','',$dtd);

		$timeslot_element = $xml->createElement("timeslot");
		$xml->appendChild($timeslot_element);


 		/**
 		 *	Get resources from database and put them in XML document.
 		 */
 		
 		// Retrieve all resources from database
	 	$resources = TS_Resource::getResources();
		
		// Create a "resources" element to contain all resources
		$resources_element = $xml->createElement("resources");
		// Append the "resources" element to document root element
		$timeslot_element->appendChild($resources_element);
		
		
		foreach($resources as $resource)
		{
			// Create a "resource" element to contain all info about a resource
			$resource_element = $xml->createElement("resource");
			// Append the "resource" element to the "resources element
			$resources_element->appendChild($resource_element);
			
			// Create and append data elements to the "resource" element
			$resource_element->appendChild( $xml->createElement("id", $resource->getID()) );
			$resource_element->appendChild( $xml->createElement("resource-type", $resource->getName()) );
			$resource_element->appendChild( $xml->createElement("description", $resource->getName()) );
		}

		/* ---------------------------------------- */
		
		/**
		 *	This should retrieve all users from the database.
		 *
		 *	<!ELEMENT user (firstname, lastname, e-mail, avatar?, id, role, user-allowances?)>
		 */
		
		$user = new TS_User(wp_get_current_user());
		
		$users_element = $xml->createElement("users");
		$timeslot_element->appendChild($users_element);
		
		$user_element = $xml->createElement("user");
		$users_element->appendChild($user_element);
		
		$user_element->appendChild( $xml->createElement("firstname", "Marcus") );
		$user_element->appendChild( $xml->createElement("lastname", "Stenbeck") );
		$user_element->appendChild( $xml->createElement("e-mail", "* marcus.stenbeck@gmail.com *") );
		$user_element->appendChild( $xml->createElement("id", $user->getID()) );
		$user_element->appendChild( $xml->createElement("role", "* The Bry Man *") );

		/* ---------------------------------------- */
		
		/**
		 *	This should retrieve all slots from the database.
		 *	A slot is a time-range which specifies availability.
		 *
		 *	<!ELEMENT slots (slot*)>
		 *	<!ELEMENT slot (id,time-range)>
		 */
		
		$slots = TS_Slot::getSlots();
		
		$slots_element = $xml->createElement("slots");
		$timeslot_element->appendChild($slots_element);
		
		foreach($slots as $slot)
		{
			// Create a "resource" element to contain all info about a resource
			$slot_element = $xml->createElement("slot");
			// Append the "resource" element to the "resources element
			$slots_element->appendChild($slot_element);
			
			// Create and append data elements to the "resource" element
			$slot_element->appendChild( $xml->createElement("id", $slot->getID()) );
			
			$time_range_element = $xml->createElement("time-range");
			$time_range_element->setAttribute("start", $slot->getStartTime());
			$time_range_element->setAttribute("end", $slot->getEndTime());
			$time_range_element->setAttribute("status", "default");
			
			$slot_element->appendChild($time_range_element);
		}


		$return.= '<id/>';
		$return.= '<time-range start="" end="" status=""/>';


		/* ---------------------------------------- */
		
		/**
		 *	This should retrieve all bookings from the database.
		 *
		 *	<!ELEMENT bookings (booking+)>
		 *	<!ELEMENT booking (booked-slots, resource-id, user-id)>
		 *	<!ELEMENT booked-slots (slot-id)>
		 *
		 */
		
		$bookings = TS_Booking::getBookings();
		
		// Create a "bookings" element to contain all resources
		$bookings_element = $xml->createElement("bookings");
		// Append the "bookings" element to document root element
		$timeslot_element->appendChild($bookings_element);
		
		// Choose the first resource ID if a resource_id isn't passed
		if(!isset($resource_id))
			$resource_id = $resources[0]->getID();
		
		foreach($bookings as $booking)
		{
			if($booking->getResource() == $resource_id)
			{
				// Create a "booking" element to contain all info about a resource
				$booking_element = $xml->createElement("booking");
				// Append the "booking" element to the "resources element
				$bookings_element->appendChild($booking_element);
				
				// -- Create and append data elements to the "booking" element --
				$booking_element->appendChild( $xml->createElement("id", $booking->getID()) );
				
				$booked_slots_element = $xml->createElement("booked-slots");
				$booking_element->appendChild($booked_slots_element);	
				$booked_slots_element->appendChild( $xml->createElement("slot-id", $booking->getSlots()) );
				
				$booking_element->appendChild( $xml->createElement("resource-id", $booking->getResource()) );
				$booking_element->appendChild( $xml->createElement("user-id", $booking->getUser()) );
			}
		}
		
		/* ---------------------------------------- */	
/**
 *	NOT STARTED CODE BELOW - Move up when in progress
 */
		
		
		
		/*
		

		$return.= '<allowances>';
		$return.= '<allowance>';
		$return.= '<resource-type/>';
		$return.= '<time-per-period/>';
		$return.= '<max-booking-length/>';
		$return.= '<periods>';
		$return.= '<period id="baize" start="" end=""/>';
		$return.= '</periods>';
		$return.= '</allowance>';
		$return.= '</allowances>';
		*/
		
/**
 *	End of function below
 */
 
		// Save XML to file for review
		$xml->save($this->plugin_dir."timeslot.xml");
		
		// Import XSL document
		$xsl = new DOMDocument();
		$xsl->load($this->plugin_dir."timeslot-html-screen.xsl");
		
		// Create an XSLT processor and process the XML document
		$parser = new XSLTProcessor();
		$parser->importStyleSheet($xsl);
		$standardView = $parser->transformToXML($xml);
		
		// Validate XML file against it's DTD
		echo $xml->validate() ? "Validated! Waffle fries… FO' FREE!" : "Not validated. Let sadness commence.";
		
		
/*
 *	Handle states (… or views, if you wish.)
 */
		if(isset($_GET['booking']))
		{
			/*
			 *	Handle booking states
			 */
			
			// Handle viewing bookings
			if(isset($_GET['add']))
			{
				if(isset($_POST['slot_id']) && isset($_POST['resource_id']) && isset($_POST['user_id']))
				{
					$booking = new TS_Booking($_POST['slot_id'], $_POST['user_id'], $_POST['resource_id']);
					
					$booking->save() or die('save');
					
					$return = '<h2>Your booking is saved</h2>';
				}
				else
				{
					$form = '<form method="post">
					
			 					<select id="slots" name="slot_id">';
					/// slots
			 		$slots = TS_Slot::getSlots();
			 		
			 		foreach($slots as $slot)
			 			$form .= '<option value="' . $slot->getID() . '">' . $slot->getStartTime() . ' - ' . $slot->getEndTime() . '</option>';
						
					$form .=	'</select>';
					
					
					//////
					$form .=	'<select id="resources" name="resource_id">';
	
			 		$resources = TS_Resource::getResources();
			 		
			 		foreach($resources as $resource)
			 			$form .= '<option value="' . $resource->getID() . '">' . $resource->getName() . '</option>';
						
					$form .=	'</select>';
					////////
					
					$form .= '		<input name="user_id" type="hidden" value="' . $user->getID() . '"/>
									<input type="text" disabled="disabled" value="' . ( $user->getName() == '' ? 'unregistered' : $user->getName() ) . '"/>';
					
					
					$form .= '		<input type="submit" value="Book"/>
								</form>';
					
					$return = $form;
				}
			}
			else if(isset($_GET['remove']))
			{
				if(isset($_GET['booking_id']))
					TS_Booking::delete($_GET['booking_id']);
			}
			else if(isset($_GET['edit']))
			{
				echo '<script type="text/javascript">alert("edit")</script>';
			}
			else
			{
				echo '<script type="text/javascript">alert("view (default)")</script>';
			}
		}
		else if(isset($_GET['resource']))
		{
			if(isset($_GET['add']))
			{
				if(isset($_POST['resource_name']))
				{
					$resource = new TS_Resource($_POST['resource_name']);
					
					$resource->save() or die('TS_Resource::save() failed!');
					
					$return = "<h2>Your resource is created!</h2>";
				}
				else
				{
					$form = '<form method="post">
				 					<input type="text" name="resource_name"/>
				 					<input type="submit" value="Create"/>
								</form>';
					
					$return = $form;
				}
			}
			else if(isset($_GET['remove']))
			{
				if(isset($_GET['id']))
					TS_Resource::delete($_GET['id']);
			}
		}
		else
		{
			// Standard state
			$return = $standardView;
		}
		
		return $return;
	}
}


add_action( 'wp_print_styles', array('TimeSlot','enqueue_my_styles'));

// Ladda jQuery
// Bortkommenterad för att den inte används.
//wp_enqueue_script( 'jquery' );


// Instantiate a TimeSlot object in order to register shorthand code
$timeslot = new TimeSlot();
?>
