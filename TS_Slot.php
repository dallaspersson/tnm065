<?php
include_once 'TS_AbstractSlot.php';
include_once 'TS_WordpressDatabaseConnector.php';

class TS_Slot extends TS_AbstractSlot
{	
	public static function getSlots($cond = null)
	{
		$cols = array("id", "start", "duration");
		
		$slots = TS_WordpressDatabaseConnector::select("timeslot_slots", $cols, $cond);
		
		$return = array();
		
		foreach($slots as $slot)
		{
			$return = array_merge($return, array(new TS_Slot($slot->start, $slot->duration, $slot->id)));
		}
		
		return $return;
	}
	
	// Checks if a specific timestamp is availible 
	// and returns true or false.
	public function getAvailability($timestamp)
	{
		// if DateTime is within start to end
		$end = $this->start + $this->duration * ($this->repeat + 1);
		
		if($timestamp >= $this->start && $timestamp < $end)
			return true;
		
		return false;
	}
	
	public function save($schedule_id = null)
	{
		if($schedule_id)
		{
			$args = array('start' => date("Y-m-d H:i:s", $this->start), 'duration' => $this->duration);
			
			// Try to add slot to database and connect it to a schedule.
			// Delete the slot if not able to connect to schedule.
			if($this->id = TS_WordpressDatabaseConnector::insert("timeslot_slots", $args))
			{
				if($result = TS_WordpressDatabaseConnector::insert("timeslot_schedules_slots", array('schedule_id' => $schedule_id, 'slot_id' => $this->id)))
					return $this->id;
				else
				{
					$this->delete($this->id);
				}
			}
			else
			{
				// Create an array with conditions that will look for the unique pair
				// that start and duration make up
				$cond = array(
								'start' => "'" . date('Y-m-d H:i:s', $this->start) . "'",
								'duration' => $this->duration
							);
				
				// Try to find the slot in the database
				$slot = TS_Slot::getSlots($cond);
				
				// If the slot was found then create a relation between
				// the slot and the schedule
				if(sizeof($slot) == 1)
					TS_WordpressDatabaseConnector::insert("timeslot_schedules_slots", array('schedule_id' => $schedule_id, 'slot_id' => $slot[0]->id));
			}
		}
		else
		{
			// Create an argument array with data to store into database
			$args = array(
							'start' => date("Y-m-d H:i:s", $this->start),
							'duration' => $this->duration
						);
			
			// Try to add slot to database
			if($this->id = TS_WordpressDatabaseConnector::insert("timeslot_slots", $args))
				return $this->id;
		}
		
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