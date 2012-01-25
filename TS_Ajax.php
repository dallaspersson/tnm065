<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

// Load the database
global $wpdb;
global $current_user;


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
$resource = $_POST['resource_id'];

// Check if the POST includes a resource, 
// else get the first resource ID.
if ($resource == null) {
    $resource = $resources[0]->getID();
}

// Check the action variable, what to do?
switch ($action) {
    case "book":
        $success = book($slot, $resource);
        break;
    case "remove":
        // Stuff
        break;
}
 
if ($success == true){
 
    // generate the response
    $response = json_encode( array('success'=> true,'message'=>'Success message: hooray!') );
 
    // response output
    echo $response;
}
else{
    // generate the response
    $response = json_encode( array('success'=> false,'message'=>'This is crap, man!') );

    // response output
    echo $response;
}

exit;





function book($s, $r) {
    $booking = new TS_Booking($s, $current_user->ID, $r);
    return true; 
    $booking->save() or die('save');

    
}
?>