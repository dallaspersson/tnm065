<?php
include_once 'TS_Schedule.php';

class TS_CompositeSchedule extends TS_Schedule
{
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
}
?>