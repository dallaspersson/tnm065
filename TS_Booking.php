<?php
class TS_Booking
{
	protected $id;
	protected $resource;
	protected $user;
	protected $start;
	protected $duration;
	
	public function __construct($user, $resource, $start, $duration, $id = null)
	{
		$this->user = $user;
		$this->resource = $resource;
		$this->start = $start;
		$this->duration = $duration;
		$this->id = $id;
	}
	
	public function save()
	{
		if($GLOBALS['wpdb']->insert('timeslot_bookings', array('user_id' => $this->user, 'resource_id' => $this->resource, 'start' => date("Y-m-d H:i:s", $this->start), 'duration' => $this->duration)))
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