<?php
include_once 'TS_AbstractSlot.php';

class TS_Schedule extends TS_AbstractSlot
{
	private $notes;
	private $schedules;
	
	public function __construct($schedules)
	{
		$this->schedules = $schedules;
	}
	
	// Checks if a specific timestamp is availible 
	// in the composite schedual, and returns true or false.
	public function getAvailability($timestamp)
	{
		$available = false;
		
		// Loop through all of the objects scheduals
		foreach($this->schedules as $schedule)
			if($schedule->getAvailability($timestamp))
			{
				$available = true;
				break;
			}
			
		return $available;
	}
	
	public function setNotes($notes)
	{
		$this->notes = $notes;
	}
	
	public function save()
	{
		$args = array('start' => date("Y-m-d H:i:s", strtotime($this->start)), 'duration' => $this->duration, 'notes' => $this->notes);
		
		if(TS_WordpressDatabaseConnector::insert("timeslot_schedules", $args))
			return true;
		
		return false;
	}
	
	public static function delete($id)
	{
		if($GLOBALS['wpdb']->query("DELETE FROM timeslot_schedules WHERE id = ".$id." LIMIT 1"))
			return true;
		
		return false;
	}
}
?>