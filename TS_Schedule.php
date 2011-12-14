<?php
include_once 'TS_AbstractSlot.php';

class TS_Schedule extends TS_AbstractSlot
{
	private $notes;
	private $schedules;
	
	/*
	public function __construct($schedules)
	{
		$this->schedules = $schedules;
	}
	*/
	
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
	
	public function getSlots()
	{
		$query = 'SELECT timeslot_slots.id, timeslot_slots.start, timeslot_slots.duration
					FROM timeslot_slots, timeslot_schedules_slots
					WHERE timeslot_slots.id = timeslot_schedules_slots.slot_id
					AND timeslot_schedules_slots.schedule_id = ' . $this->id;
		
		$results = TS_WordpressDatabaseConnector::query($query);
		
		$slots = array();
		
		foreach($results as $result)
			$slots[strtotime($result->start)] = new TS_Slot($result->start, $result->duration, $result->id);
		
		return $slots;
	}
	
	public function removeSlot($id)
	{
		$query = 'SELECT timeslot_slots.id, timeslot_slots.start, timeslot_slots.duration
					FROM timeslot_slots, timeslot_schedules_slots
					WHERE timeslot_slots.id = timeslot_schedules_slots.slot_id
					AND timeslot_schedules_slots.slot_id = ' . $id;
		
		$results = TS_WordpressDatabaseConnector::query($query);
		
		echo '<pre>';
		print_r($results);
		echo 'sizeof($results): ' . sizeof($results);
		echo '</pre>';
		
		$schedulesConnectedToSlots = sizeof($results);
		
		if($schedulesConnectedToSlots == 1)
			TS_Slot::delete($id);
		else if($schedulesConnectedToSlots > 1)
		{
			$query = 'DELETE FROM timeslot_schedules_slots
					WHERE slot_id = ' . $id . ' 
					AND schedule_id = ' . $this->id;
			
			$results = TS_WordpressDatabaseConnector::query($query);
		}
	}
	
	public function setNotes($notes)
	{
		$this->notes = $notes;
	}
	
	public static function getSchedule($id)
	{
		$args = array('id', 'start', 'duration');
		$cond = array();
		
		$schedules = TS_WordpressDatabaseConnector::select('timeslot_schedules', $args);
		
		$return = array();
		
		foreach($schedules as $schedule)
		{
			if($schedule->id == $id)
				$return = array_merge($return, array(new TS_Schedule($schedule->start, $schedule->duration, $schedule->id)));
		}
		
		return $return;
	}
	
	public function save()
	{
		$args = array(
						'start' => date("Y-m-d H:i:s", strtotime($this->start)),
						'duration' => $this->duration
					);
		
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