<?php
include_once 'TS_Schedule.php';

class TS_CompositeSchedule extends TS_Schedule
{
	private $schedules;
	
	public function __construct($schedules)
	{
		$this->schedules = $schedules;
	}
	
	public function getAvailability($timestamp)
	{
		$available = false;
		
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