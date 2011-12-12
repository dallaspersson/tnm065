<?php

class TS_Resource
{
	private $id;
	private $name;
	
	public function __construct($name, $id = null)
	{
		$this->id = $id;
		$this->name = $name;
	}
	
	public static function getResources($id = null)
	{
		if($id)
		{
			$results = $GLOBALS['wpdb']->get_results("SELECT id, name FROM timeslot_resources WHERE id = " . $id);

			$resources = new TS_Resource($results[0]->name, $results[0]->id);
		}
		else
		{
			$results = $GLOBALS['wpdb']->get_results("SELECT id, name FROM timeslot_resources");
		
			$resources = array();
			
			foreach($results as $result)
				$resources = array_merge($resources, array(new TS_Resource($result->name, $result->id)));
		}
		
		return $resources;
	}
	
	public function getSchedule()
	{
		$results = $GLOBALS['wpdb']->get_results("SELECT `id`, `start`, `repeat` FROM timeslot_schedule INNER JOIN timeslot_resources_schedule ON timeslot_schedule.id = timeslot_resources_schedule.schedule_id WHERE timeslot_resources_schedule.resource_id = " . $this->getID());
		
		$schedules = array();
		
		foreach($results as $result)
		{
			$schedules = array_merge($schedules, array(new TS_Day(strtotime($result->start), $result->repeat, $result->id)));
		}
		
		return new TS_CompositeSchedule($schedules);
	}
	
	public function getBookings()
	{
		$results = $GLOBALS['wpdb']->get_results("SELECT `id`, `user_id`, `resource_id`, `start`, `duration` FROM timeslot_bookings WHERE `resource_id` = " . $this->getID());
		
		$bookings = array();
		
		foreach($results as $result)
		{
			$bookings = array_merge($bookings, array(new TS_Booking($result->user_id, $result->resource_id, strtotime($result->start), $result->duration, $result->id)));
		}
		
		return $bookings;
	}
	
	public function save()
	{
		// Build argument array for database connector
		$args = array('name' => $this->name);

		if(TS_WordpressDatabaseConnector::insert("timeslot_resources", $args))
			return true;
			
		return false;
	}
	
	public static function delete($resource_id)
	{
		if($GLOBALS['wpdb']->query("DELETE FROM timeslot_resources WHERE id = ".$resource_id." LIMIT 1"))
			return true;
		
		return false;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getID()
	{
		return $this->id;
	}
}
?>