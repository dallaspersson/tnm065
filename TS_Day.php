<?php
include_once 'TS_Schedule.php';

class TS_Day extends TS_Schedule
{	
	public function __construct($start, $repeat, $id = null)
	{
		$this->duration = 86400;
		$this->start = $start;
		$this->repeat = $repeat;
		$this->id = $id;
	}
	
	public function getAvailability($timestamp)
	{
		// if DateTime is within start to end
		$end = $this->start + $this->duration * ($this->repeat + 1);
		
		if($timestamp >= $this->start && $timestamp < $end)
			return true;

		
		return false;
	}
}
?>