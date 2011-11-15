<?php
abstract class TS_Schedule
{
	protected $id;
	protected $duration;
	protected $start;
	protected $repeat = 0;
	
	public abstract function getAvailability($timestamp);

	public function getDuration()
	{
		// return the duration of the schedule range
		return $this->duration;
	}
	
	public function getStart()
	{
		// return when the range starts
		return $this->start;
	}
	
	public function getRepeat()
	{
		// return how many times the range repeats
		return $this->repeat;
	}
}
?>