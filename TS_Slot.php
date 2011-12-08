<?php
include_once 'TS_WordpressDatabaseConnector.php';

class TS_Slot
{
	
	// These are implemented
	protected $id;
	protected $start;
	protected $duration;
	
	public function __construct($start, $duration, $id = null)
	{
		$this->id = $id;
		$this->start = $start;
		$this->duration = $duration;
	}
	
	public static function getSlots()
	{
		$cols = array("id", "start", "duration");
		
		$slots = TS_WordpressDatabaseConnector::select("timeslot_slots", $cols);
		
		$return = array();
		
		foreach($slots as $slot)
		{
			$return = array_merge($return, array(new TS_Slot($slot->start, $slot->duration, $slot->id)));
		}
		
		return $return;
	}
	
	public function save()
	{
		//$args = array('user_id' => $this->user, 'resource_id' => $this->resource, 'start' => date("Y-m-d H:i:s", $this->start), 'duration' => $this->duration);
		$args = array('start' => date("Y-m-d H:i:s", strtotime($this->start)), 'duration' => $this->duration);
		
		if(TS_WordpressDatabaseConnector::insert("timeslot_slots", $args))
			return true;
		
		return false;
	}
	
	public static function delete($slot_id)
	{
		if($GLOBALS['wpdb']->query("DELETE FROM timeslot_slots WHERE id = ".$slot_id." LIMIT 1"))
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
}
?>