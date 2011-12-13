<?php
include_once 'TS_DatabaseConnector.php';

class TS_WordpressDatabaseConnector implements TS_DatabaseConnector
{
	public function __construct()
	{
		print 'TS_WordpressDatabaseConnector::__construct()';
	}
	
	public function connect()
	{
		print 'TS_WordpressDatabaseConnector::connect()';
	}
	
	public function disconnect()
	{
		print 'TS_WordpressDatabaseConnector::disconnect()';
	}
	
	public function select($db_table, $cols, $conditions = null)
	{
		
		// Make sure the database table name is set
		if(empty($db_table))
			return false;
		
		// Make sure that there are arguments
		if(empty($cols))
			return false;
		
		// Build query string 	
		$query = "SELECT id";
		foreach($cols as $col)
			$query .= ", " . $col;
		$query .= " FROM " . $db_table;
		
		if($conditions)
		{
			$query .= ' WHERE';
			foreach($conditions as $condition => $value)
				$query .= ' ' . $condition . ' = ' . $value . ' AND';
				
			// Remove trailing " AND"
			$query = substr($query, 0, -4);
		}
		
		
		
		// Fetch data
		$results = $GLOBALS['wpdb']->get_results($query);
		
		return $results;
	}
	
	public static function insert($db_table, $args)
	{
		
		// Make sure the database table name is set
		if(empty($db_table))
			return false;
		
		// Make sure that there are arguments
		if(empty($args))
			return false;
		
		$GLOBALS['wpdb']->show_errors();
		
		// Try to insert data
		// If successful then:
		// 1. return insert_id if it is not zero
		// 2. return true if insert_id is zero
		if($GLOBALS['wpdb']->insert($db_table, $args))
			if($insert_id = $GLOBALS['wpdb']->insert_id)
				return $insert_id;
			else
				return true;
				
		
		return false;
	}
	
	public function delete()
	{
		print 'TS_WordpressDatabaseConnector::delete()';
	}	
	
	public function update()
	{
		print 'TS_WordpressDatabaseConnector::update()';
	}
	
	public static function query($query)
	{
		return $GLOBALS['wpdb']->get_results($query);
	}
}
?>