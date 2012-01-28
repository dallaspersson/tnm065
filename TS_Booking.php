<?php
include_once 'TS_WordpressDatabaseConnector.php';

class TS_Booking
{
	
	// These are implemented
	protected $id;
	protected $slots;
	protected $user_id;
	protected $resource_id;
	protected $repetition;
	
	//public function __construct($user, $resource, $start, $duration, $id = null)
	public function __construct($slots, $user_id, $resource_id, $id = null, $repetition = null)
	{
		$this->id = $id;
		$this->slots = $slots;
		$this->user_id = $user_id;
		$this->resource_id = $resource_id;
		$this->repetition = $repetition;
	}
	
	public static function getBookings()
	{
		$cols = array("id", "slot_id", "user_id", "resource_id", "repetition");
		
		$bookings = TS_WordpressDatabaseConnector::select("timeslot_bookings", $cols);
		
		$return = array();
		
		foreach($bookings as $booking)
		{	
			$return = array_merge($return, array(new TS_Booking($booking->slot_id, $booking->user_id, $booking->resource_id, $booking->id, $booking->repetition)));
		}
		
		return $return;
	}
	
	public static function getBooking($id)
	{
		$cols = array("id", "slot_id", "user_id", "resource_id", "repetition");
		
		$cond = array('id' => $id);
		
		$bookings = TS_WordpressDatabaseConnector::select("timeslot_bookings", $cols, $cond);
		
		$return = array();
		
		foreach($bookings as $booking)
		{	
			$return = new TS_Booking($booking->slot_id, $booking->user_id, $booking->resource_id, $booking->id, $booking->repetition);
		}
		
		return $return;
	}
	
	public function save()
	{
		// Build argument array for database connector
		$args = array(
						'slot_id' => $this->slots,
						'user_id' => $this->user_id,
						'resource_id' => $this->resource_id,
						'repetition' => $this->repetition
					);
		
		if($id = TS_WordpressDatabaseConnector::insert("timeslot_bookings", $args))
		{
			$this->id = $id;
			return $id;
		}
			
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
	
	// FUNCTION STUB
	public function getResource()
	{
		return $this->resource_id;
	}
	
	// FUNCTION STUB
	public function getUser()
	{
		return $this->user_id;
	}
	
	public function getSlots()
	{
		return $this->slots;
	}
	
	public function getRepetition()
	{
		return $this->repetition;
	}
}
?>