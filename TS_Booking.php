<?php
include_once 'TS_WordpressDatabaseConnector.php';

class TS_Booking
{
	
	// These are implemented
	protected $id;
	protected $slots;
	protected $user_id;
	protected $resource_id;
	
	//public function __construct($user, $resource, $start, $duration, $id = null)
	public function __construct($slots, $user_id, $resource_id, $id = null)
	{
		$this->id = $id;
		$this->slots = $slots;
		$this->user_id = $user_id;
		$this->resource_id = $resource_id;
	}
	
	public static function getBookings()
	{
		$cols = array("id", "slot_id", "user_id", "resource_id");
		
		$bookings = TS_WordpressDatabaseConnector::select("timeslot_bookings", $cols);
		
		$return = array();
		
		foreach($bookings as $booking)
		{	
			$return = array_merge($return, array(new TS_Booking($booking->slot_id, $booking->user_id, $booking->resource_id, $booking->id)));
		}
		
		return $return;
	}
	
	// FUNCTION STUB
	public function getResource()
	{
		return $this->resource;
	}
	
	// FUNCTION STUB
	public function getUser()
	{
		return $this->user;
	}
	
	public function getSlots()
	{
		return $this->slots;
	}
	
	public function save()
	{
		// Build argument array for database connector
		$args = array('slot_id' => $this->slots, 'user_id' => $this->user_id, 'resource_id' => $this->resource_id);
		
		if(TS_WordpressDatabaseConnector::insert("timeslot_bookings", $args))
			return true;
			
		return false;
	}
	
	public static function delete($booking_id)
	{
		if($GLOBALS['wpdb']->query("DELETE FROM timeslot_bookings WHERE id = ".$booking_id." LIMIT 1"))
			return true;
		
		return false;
	}
	
	public function isBooked($timestamp)
	{
		$end = $this->start + $this->duration;
		
		if($timestamp >= $this->start && $timestamp < $end)
			return $this->getUserID();

		
		return false;
	}
	
	public function getID()
	{
		return $this->id;
	}
	
	public function getUserID()
	{
		return $this->user;
	}
}
?>