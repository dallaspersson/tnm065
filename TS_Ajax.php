<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

// Load the database
global $wpdb;
global $current_user;
global $fresh_booking_id;

if(!isset($wpdb))
{
    require_once('../../../wp-config.php');
    require_once('../../../wp-includes/wp-db.php');
}

include_once 'TS_WordpressDatabaseConnector.php';
include_once 'TS_Resource.php';


// Save all resources to an array.
$resources = TS_Resource::getResources();

// get the submitted parameters
$action = $_POST['action'];
$slot = $_POST['slot_id'];
$repetition = $_POST['rep_id'];
$resource = $_POST['resource_id'];
$booking = $_POST['booking_id'];
$user = $current_user->ID;
$fname = $current_user->user_firstname;
$lname = $current_user->user_lastname;


// Check if the POST includes a resource, 
// else get the first resource ID.
if ($resource == null) {
    $resource = $resources[0]->getID();
}

// Check the action variable, what to do?
switch ($action) {
    case "book":
        $success = book($slot, $user, $resource, $repetition);
        if ($success != false) {
            $fresh_booking_id = $success;
            $success = true;
        }
        
        break;
    case "remove":
        $success = unbook($booking);
        break;
}
 
if ($success == true){
 
    // generate the response
    $response = json_encode( array('success'=> true,'message'=>'Hoorray! Amazazing, man!', 'user_fname' => $fname, 'user_lname' => $lname, 'fresh_booking_id' => $fresh_booking_id) );
 
    // response output
    echo $response;
}
else{
    // generate the response
    $response = json_encode( array('success'=> false,'message'=>'This is crap, man!', 'user_fname' => $fname, 'user_lname' => $lname, 'fresh_booking_id' => $fresh_booking_id) );

    // response output
    echo $response;
}

exit;





function book($slot, $user, $res, $rep) {

    $booking = new TS_Booking($slot, $user, $res, null, $rep);
    
    $saving = $booking->save();

    if($saving != false)
    {
        return $fresh_booking_id = $booking->getID();
    }

    return $booking; 
}

function unbook($b) {

    $booking = TS_Booking::delete($b);

    return $booking; 
}
?>