<?php
abstract class TS_AbstractSlot
{
	protected $id;
	protected $duration;
	protected $start;
	protected $repeat = 0;
	
	public abstract function getAvailability($timestamp);
	public abstract function save();
	public abstract static function delete($id);
	
	public function __construct($start, $duration, $id = null)
	{
		$this->id = $id;
		$this->start = $start;
		$this->duration = $duration;
	}
	
	public function getID()
	{
		// return the duration of the schedule range
		return $this->id;
	}
	
	public function getDuration()
	{
		// return the duration of the schedule range
		return $this->duration;
	}
	
	public function getStartTime()
	{
		return $this->start;
	}
	
	public function getEndTime()
	{
		$endTimestamp = strtotime($this->start) + $this->duration;
		
		$endTime = date("Y-m-d H:i:s", $endTimestamp);
		
		return $endTime;
	}
	
	public function getRepeat()
	{
		// return how many times the range repeats
		return $this->repeat;
	}
}
?>