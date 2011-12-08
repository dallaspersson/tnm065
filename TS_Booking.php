<?php
include_once 'TS_WordpressDatabaseConnector.php';

class TS_Booking
{
	// These are not implemented
	
	//protected $resource;
	//protected $user;
	
	// These are implemented
	protected $id;
	protected $start;
	protected $duration;
	
	//public function __construct($user, $resource, $start, $duration, $id = null)
	public function __construct($start, $duration, $id = null)
	{
		$this->id = $id;
		//$this->user = $user;
		//$this->resource = $resource;
		$this->start = $start;
		$this->duration = $duration;
	}
	
	public static function getBookings()
	{
		$cols = array("start", "duration");
		
		$bookings = TS_WordpressDatabaseConnector::select("timeslot_bookings", $cols);
		
		$return = array();
		
		foreach($bookings as $booking)
		{
			$return = array_merge($return, array(new TS_Booking($result->start, $result->duration)));
		}
		
		return $return;
	}
	
	// FUNCTION STUB
	public function getResource()
	{
		$resource = new TS_Resource(-1, "* DUMMY RESOURCE *");
		
		return $resource;
	}
	
	// FUNCTION STUB
	public function getUser()
	{
		$user = "user";
		
		return $user;
	}
	
	public function getSlots()
	{
		$slots = array("slot1", "slot2", "slot3");
		
		return $slots;
	}
	
	public function save()
	{
		//$args = array('user_id' => $this->user, 'resource_id' => $this->resource, 'start' => date("Y-m-d H:i:s", $this->start), 'duration' => $this->duration);
		$args = array('start' => date("Y-m-d H:i:s", strtotime($this->start)), 'duration' => $this->duration);
		
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