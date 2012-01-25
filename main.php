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
include 'TS_Schedule.php';
include 'TS_Booking.php';

class TimeSlot
{
	// Variables

	private $plugin_dir;
	private $plugin_url;
	
	public function __construct()
	{
		/*error_reporting(E_ALL);
		ini_set('display_errors', '1');*/

		add_shortcode('timeslot', array($this, 'shortcode'));
		$this->plugin_dir = WP_PLUGIN_DIR . '/' . str_replace(basename( __FILE__), "", plugin_basename(__FILE__));
		$this->plugin_url = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "", plugin_basename(__FILE__));
	}

	public function enqueue_my_styles()
	{	
		// Link css
		$tmp_url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		//wp_enqueue_style( 'stylesheet-html-screen', $tmp_url . 'stylesheet-html-screen.css');

		$a = new TimeSlot();

		if ($a->is_mobile()) {
			// Place code you wish to execute if browser is mobile here
			wp_enqueue_style( 'stylesheet-html-screen', $tmp_url . 'stylesheet-html-mobile.css');
		}

		else {
			// Place code you wish to execute if browser is NOT mobile here
			wp_enqueue_style( 'stylesheet-html-screen', $tmp_url . 'stylesheet-html-screen.css');
			//wp_enqueue_style( 'stylesheet-html-screen', $tmp_url . 'stylesheet-html-mobile.css');
		}
	}

	public function enqueue_my_scripts()
	{	
		// Link css
		$tmp_url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		//wp_enqueue_style( 'stylesheet-html-screen', $tmp_url . 'stylesheet-html-screen.css');

		$tmp = new TimeSlot();

		if ($tmp->is_mobile()) {
			// Place code you wish to execute if browser is mobile here
			wp_enqueue_script( 'script_mobile', $tmp_url . 'script_mobile.js', array( 'jquery' ));
		}

		else {
			// Embed the javascript file that makes the AJAX request
			wp_enqueue_script( 'script_screen', $tmp_url . 'script_screen.js', array( 'jquery' ));

			// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
			wp_localize_script( 'script_screen', 'MyAjax', array( 'ajaxurl' => $tmp_url . 'TS_Ajax.php' ) );

			// this hook is fired if the current viewer is not logged in
			do_action( 'wp_ajax_nopriv_' . $_REQUEST['action'] );
			 
			// if logged in:
			do_action( 'wp_ajax_' . $_POST['action'] );

		}
	}

	public function is_mobile() {

		// Get the user agent
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		// Create an array of known mobile user agents
		// This list is from the 21 October 2010 WURFL File.
		// Most mobile devices send a pretty standard string that can be covered by
		// one of these.  I believe I have found all the agents (as of the date above)
		// that do not and have included them below.  If you use this function, you 
		// should periodically check your list against the WURFL file, available at:
		// http://wurfl.sourceforge.net/


		$mobile_agents = Array(


			"240x320",
			"acer",
			"acoon",
			"acs-",
			"abacho",
			"ahong",
			"airness",
			"alcatel",
			"amoi",	
			"android",
			"anywhereyougo.com",
			"applewebkit/525",
			"applewebkit/532",
			"asus",
			"audio",
			"au-mic",
			"avantogo",
			"becker",
			"benq",
			"bilbo",
			"bird",
			"blackberry",
			"blazer",
			"bleu",
			"cdm-",
			"compal",
			"coolpad",
			"danger",
			"dbtel",
			"dopod",
			"elaine",
			"eric",
			"etouch",
			"fly " ,
			"fly_",
			"fly-",
			"go.web",
			"goodaccess",
			"gradiente",
			"grundig",
			"haier",
			"hedy",
			"hitachi",
			"htc",
			"huawei",
			"hutchison",
			"inno",
			"ipad",
			"ipaq",
			"ipod",
			"jbrowser",
			"kddi",
			"kgt",
			"kwc",
			"lenovo",
			"lg ",
			"lg2",
			"lg3",
			"lg4",
			"lg5",
			"lg7",
			"lg8",
			"lg9",
			"lg-",
			"lge-",
			"lge9",
			"longcos",
			"maemo",
			"mercator",
			"meridian",
			"micromax",
			"midp",
			"mini",
			"mitsu",
			"mmm",
			"mmp",
			"mobi",
			"mot-",
			"moto",
			"nec-",
			"netfront",
			"newgen",
			"nexian",
			"nf-browser",
			"nintendo",
			"nitro",
			"nokia",
			"nook",
			"novarra",
			"obigo",
			"palm",
			"panasonic",
			"pantech",
			"philips",
			"phone",
			"pg-",
			"playstation",
			"pocket",
			"pt-",
			"qc-",
			"qtek",
			"rover",
			"sagem",
			"sama",
			"samu",
			"sanyo",
			"samsung",
			"sch-",
			"scooter",
			"sec-",
			"sendo",
			"sgh-",
			"sharp",
			"siemens",
			"sie-",
			"softbank",
			"sony",
			"spice",
			"sprint",
			"spv",
			"symbian",
			"tablet",
			"talkabout",
			"tcl-",
			"teleca",
			"telit",
			"tianyu",
			"tim-",
			"toshiba",
			"tsm",
			"up.browser",
			"utec",
			"utstar",
			"verykool",
			"virgin",
			"vk-",
			"voda",
			"voxtel",
			"vx",
			"wap",
			"wellco",
			"wig browser",
			"wii",
			"windows ce",
			"wireless",
			"xda",
			"xde",
			"zte"
		);

		// Pre-set $is_mobile to false.

		$is_mobile = false;

		// Cycle through the list in $mobile_agents to see if any of them
		// appear in $user_agent.

		foreach ($mobile_agents as $device) {

			// Check each element in $mobile_agents to see if it appears in
			// $user_agent.  If it does, set $is_mobile to true.

			if (stristr($user_agent, $device)) {

				$is_mobile = true;

				// break out of the foreach, we don't need to test
				// any more once we get a true value.

				break;
			}
		}

		return $is_mobile;
	}

	
	public function shortcode()
	{
		add_action('admin_head', 'my_action_javascript');

		if(isset($_GET['resource_id']))
			$resource_id = $_GET['resource_id'];
	
		// Create an XML document with the Timeslot DTD
		$implementation = new DOMImplementation();
		$dtd = $implementation->createDocumentType('timeslot', '', $this->plugin_dir . 'timeslot.dtd');
		$xml = $implementation->createDocument('', '', $dtd);

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
		
		$user_element->appendChild( $xml->createElement("firstname", $user->getFirstName()) );
		$user_element->appendChild( $xml->createElement("lastname", $user->getLastName()) );
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
/*		
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
			$time_range_element->setAttribute("start", date("H:i", strtotime($slot->getStartTime())));
			$time_range_element->setAttribute("end", date("H:i", strtotime($slot->getEndTime())));
			$time_range_element->setAttribute("status", "default");
			
			$slot_element->appendChild($time_range_element);
		}
*/
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
		 *	This should retrieve all slots from the database.
		 *	A slot is a time-range which specifies availability.
		 *
		 *	<!ELEMENT slots (slot*)>
		 *	<!ELEMENT slot (id,time-range)>
		 */
		
		$schedules = TS_Schedule::getSchedule();
		
		$schedules_element = $xml->createElement("schedules");
		$timeslot_element->appendChild($schedules_element);
		
		foreach($schedules as $schedule)
		{
			// Create a "schedule" element to contain all info about a resource
			$schedule_element = $xml->createElement("schedule");
			// Append the "schedule" element to the "schedules" element
			$schedules_element->appendChild($schedule_element);
			
			// Create and append data elements to the "schedule" element
			$schedule_element->appendChild( $xml->createElement("id", $schedule->getID()) );
			
			$time_range_element = $xml->createElement("time-range");
			$time_range_element->setAttribute("start", $schedule->getStartTime());
			$time_range_element->setAttribute("end", $schedule->getEndTime());
			$time_range_element->setAttribute("status", "default");
			
			$schedule_element->appendChild($time_range_element);
			
			$slots_element = $xml->createElement('slots');
			$schedule_element->appendChild($slots_element);
			
			$slots = $schedule->getSlots();
			
			foreach($slots as $slot)
			{
				$slot_element = $xml->createElement('slot');
				$slots_element->appendChild($slot_element);
				
				$slot_element->appendChild( $xml->createElement('id', $slot->getID()) );
				
				$time_range_element = $xml->createElement("time-range");
				$time_range_element->setAttribute("start", date("H:i", strtotime($slot->getStartTime())));
				$time_range_element->setAttribute("end", date("H:i", strtotime($slot->getEndTime())));
				$time_range_element->setAttribute("status", "default");
			
				$slot_element->appendChild($time_range_element);
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

		if ($this->is_mobile()) {
			// Place code you wish to execute if browser is mobile here
			$xsl->load($this->plugin_dir."timeslot-html-mobile.xsl");
		}

		else {
			// Place code you wish to execute if browser is NOT mobile here
			$xsl->load($this->plugin_dir."timeslot-html-screen.xsl");
		}

		// Create an XSLT processor and process the XML document
		$parser = new XSLTProcessor();
		$parser->importStyleSheet($xsl);
		$parser->setParameter('', 'current_resource', $resource_id);
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
				else if(isset($_GET['slot_id']) && isset($_GET['resource_id']))
				{
					$booking = new TS_Booking($_GET['slot_id'], $user->getID(), $_GET['resource_id']);
					
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
					
					
					if($_GET['resource_id'])
					{
						$resource = TS_Resource::getResources($_GET['resource_id']);
						
						$form .= '		<input name="resource_id" type="hidden" value="' . $resource->getID() . '"/>
										<input type="text" disabled="disabled" value="' . $resource->getName() . '"/>';
					}
					else
					{
			 			$resources = TS_Resource::getResources();
			 			
			 			$form .=	'<select id="resources" name="resource_id">';
			 			
			 			foreach($resources as $resource)
			 				$form .= '<option value="' . $resource->getID() . '">' . $resource->getName() . '</option>';
						
						$form .=	'</select>';
					}
					//////
					
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
				if(isset($_POST['resource_name']) && isset($_POST['schedule_id']))
				{
					$resource = new TS_Resource($_POST['resource_name'], $_POST['schedule_id']);
					
					echo '<pre>';
					print_r($resource);
					echo '</pre>';
					
					$resource->save() or die('TS_Resource::save() failed!');
					
					$return = "<h2>Your resource is created!</h2>";
				}
				else
				{
					$form = '<form method="post">
				 					<input type="text" name="resource_name"/>';
				 	
				 	// Create list of schedules
				 	$schedules = TS_Schedule::getSchedule();
				 	$form .= '<select name="schedule_id">';
				 	foreach($schedules as $schedule)
				 		$form .= '<option value="' . $schedule->getID() . '">id: ' . $schedule->getID() . '</option>';
				 	$form .= '</select>';
				 	
				 	
				 	$form .= '<input type="submit" value="Create"/>';
					$form .= '</form>';
					
					$return = $form;
				}
			}
			else if(isset($_GET['remove']))
			{
				if(isset($_GET['id']))
					TS_Resource::delete($_GET['id']);
			}
		}
		else if(isset($_GET['schedule']))
		{
			if(isset($_GET['add']))
			{
				if(isset($_POST['start']) && isset($_POST['end']))
				{
					$duration = strtotime($_POST['end']) - strtotime($_POST['start']);
					$schedule = new TS_Schedule($_POST['start'], $duration);
					
					// Implement saving of notes
					/*
					if(isset($_POST['schedule_notes']))
						$schedule->setNotes($_POST['schedule_notes']);
					*/
					
					$schedule->save() or die('TS_Schedule::save() failed!');
					
					$return = "<h2>Your schedule is created!</h2>";
				}
				else
				{
					$form = '<form method="post">
				 					<label for="start">Starting Date</label>
				 					<input type="date" id="start" name="start"/>
				 					
				 					<label for="end">Ending Date</label>
				 					<input type="date" id="end" name="end" />
				 					
				 					<label for="period_name">Schedule Name</label>
				 					<textarea id="schedule_notes" name="schedule_notes"></textarea>
				 					
				 					<input type="submit" value="Create"/>
								</form>';
					
					$return = $form;
				}
			}
			else if(isset($_GET['edit']))
			{
				$slotsPerDay = 24;
				$slotLength = 86400 / $slotsPerDay;
				
				if(isset($_POST['schedule_id']))
				{
					echo '<pre>';
					print_r($_POST);
					
					// Create an array of slots that were checked in the form
					$formSlots = array();
					while($post = current($_POST))
					{
						$key = key($_POST);
						if(substr($key, 0, 2) == 't_')
							$formSlots[$key] = $_POST[$key];
						
						next($_POST);
					}
					print_r($formSlots);
					
					// Create an array of the slots already in database
					if(isset($_POST['dbSlots']))
						$dbSlots = explode(',', $_POST['dbSlots']);
					print_r($dbSlots);
					
					echo '</pre>';
					
					foreach($formSlots as $formSlot)
					{
						if(isset($dbSlots))
						{
							// If the slot is in both the database and form slots then do nothing
							if(in_array($formSlot, $dbSlots, true))
							{
								// Remove corresponding element from $dbSlots
								unset($dbSlots[array_search($formSlot,$dbSlots, true)]);
							}
							// If a slot exists among the form slots but not the database slots
							// it needs to be added to the database
							else if(!in_array($formSlot, $dbSlots, true))
							{
								// Create a slot object
								$slot = new TS_Slot($formSlot, $slotLength);
								
								// Save the slot object
								$slot->save($_POST['schedule_id']);
							}
						}
						else
						{
							// Create a slot object
							$slot = new TS_Slot($formSlot, $slotLength);
								
							// Save the slot object
							$slot->save($_POST['schedule_id']);
						}
					}
					
					// Get the schedule with given ID
					// NOTE: This could be done in a better way?
					$scheduleID = $_POST['schedule_id'];
					$schedule = TS_Schedule::getSchedule($scheduleID);
					$schedule = $schedule[0];
					$slots = $schedule->getSlots();
					
					// Remove the slots that weren't checked in the form, didn't
					// exist among the form slots, and therefore weren't removed
					// from the $dbSlots array
					if(!empty($slots) && isset($dbSlots))
					{
						foreach($dbSlots as $dbSlot)
						{
							$slot = $slots[$dbSlot];
							$schedule->removeSlot($slot->getID());
						}
					}
				}
				else if(isset($_GET['schedule_id']))
				{
					$schedule_id = $_GET['schedule_id'];
					
					$schedule = TS_Schedule::getSchedule($schedule_id);
					$schedule = $schedule[0];
					
					$slots = $schedule->getSlots();
					
					$form = '<form method="post">';
					$form .= '<input type="hidden" name="schedule_id" value="' . $schedule_id . '" />';
					
					// Add timestamps that existed before edit so we can detect deletions
					if(!empty($slots))
					{
						$form .= '<input type="hidden" name="dbSlots" value="';
						foreach($slots as $timestamp => $slot)
							$form .= $timestamp . ',';
							
						// Remove the trailing slash
						$form = substr($form, 0, -1);
						
						// Close <input> tag
						$form .= '" />';
					}
					
					$form .= '<div style="clear:both;"><input type="submit" value="Create" /></div>';
					
					$startTime = strtotime($schedule->getStartTime());
					$endTime = strtotime($schedule->getEndTime());
					
					for($i = $startTime; $i <= $endTime; $i += 86400)
					{
						$form .= '<div style="float:left;">';
						
						$form .= '<p>' . date('Y-m-d', $i) . '</p>';
						$form .= '<ul style="margin: 0 3em 0 0; list-style:none;">';
						
						for($j = 0; $j < 86400; $j = $j + $slotLength)
						{
							$timestamp = $i + $j;
							$htmlHandle = 't_' . $timestamp;
							$isChecked = isset($slots[$timestamp]) ? 'checked="checked" ' : null;
							$form .= '<li style="position:relative;"><input id="' . $htmlHandle . '" name="' . $htmlHandle . '" value="' . $timestamp . '" type="checkbox" ' . $isChecked . 'style="position:absolute; top:4px; left:-20px;"/><label for="' . $htmlHandle . '">' . date('H:i', $timestamp) . ' - ' . date('H:i', $timestamp + $slotLength) . '</label></li>';
						}
						
						$form .= '</ul>';
						
						$form .= '</div>';
					}
					$form .= '</form>';
					
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




// Ladda jQuery
// Bortkommenterad för att den inte används.
wp_enqueue_script( 'jquery' );


// Ladda css
add_action( 'wp_print_styles', array('TimeSlot','enqueue_my_styles'));

// Ladda javascript
add_action( 'wp_print_scripts', array('TimeSlot','enqueue_my_scripts'));

// Instantiate a TimeSlot object in order to register shorthand code
$timeslot = new TimeSlot();
?>
