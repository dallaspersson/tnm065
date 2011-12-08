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
	
	public function select($db_table, $cols)
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
		
		if($GLOBALS['wpdb']->insert($db_table, $args))
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
}
?>