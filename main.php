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
include 'TS_Booking.php';
include 'TS_CompositeSchedule.php';

class TimeSlot
{
	public function __construct()
	{
		add_shortcode('timeslot', array($this, 'shortcode'));
	}
	
	public function shortcode()
	{
		// Create an XML document with the Timeslot DTD
		$implementation = new DOMImplementation();
		$dtd = $implementation->createDocumentType('timeslot', '', plugin_dir_path(__FILE__) . 'timeslot.dtd');
		$xml = $implementation->createDocument('','',$dtd);

		$timeslot_element = $xml->createElement("timeslot");
		$xml->appendChild($timeslot_element);

/**
 *	IN PROGRESS STARTED CODE BELOW - Move up when finished
 */
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
		
		$user = new TS_User($GLOBALS['current_user']);
		
		$users_element = $xml->createElement("users");
		$timeslot_element->appendChild($users_element);
		
		$user_element = $xml->createElement("user");
		$users_element->appendChild($user_element);
		
		$user_element->appendChild( $xml->createElement("firstname", $user->getName()) );
		$user_element->appendChild( $xml->createElement("lastname", $user->getName()) );
		$user_element->appendChild( $xml->createElement("e-mail", "* marcus.stenbeck@gmail.com *") );
		$user_element->appendChild( $xml->createElement("id", $user->getID()) );
		$user_element->appendChild( $xml->createElement("role", "* The Bry Man *") );

		/* ---------------------------------------- */
		
		
/**
 *	NOT STARTED CODE BELOW - Move up when in progress
 */
		
		/**
		 *	This should retrieve all slots from the database.
		 *	A slot is a time-range which specifies availability.
		 *
		 *	<!ELEMENT slots (slot*)>
		 *	<!ELEMENT slot (id,time-range)>
		 */
		
		$return.= '<slots>';
		$return.= '<slot>';
		$return.= '<id/>';
		$return.= '<time-range start="" end="" status=""/>';
		$return.= '</slot>';
		$return.= '</slots>';

		/* ---------------------------------------- */
		
		/*
		$return.= '<bookings>';
		$return.= '<booking>';
		$return.= '<booked-slots>';
		$return.= '<slot-id/>';
		$return.= '</booked-slots>';
		$return.= '<resource-id/>';
		$return.= '<user-id/>';
		$return.= '</booking>';
		$return.= '</bookings>';

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
		$xml->save(plugin_dir_path(__FILE__)."timeslot.xml");
		
		// Import XSL document
		$xsl = new DOMDocument();
		$xsl->load(plugin_dir_path(__FILE__)."timeslot-html-screen.xsl");
		
		// Create an XSLT processor and process the XML document
		$parser = new XSLTProcessor();
		$parser->importStyleSheet($xsl);
		$return = $parser->transformToXML($xml);
		
		// Validate XML file against it's DTD
		echo $xml->validate() ? "Validated! Waffle fries… FO' FREE!" : "Not validated. Let sadness commence.";
		
		return $return;
	}
}

// Instantiate a TimeSlot object in order to register shorthand code
$timeslot = new TimeSlot();
?>
