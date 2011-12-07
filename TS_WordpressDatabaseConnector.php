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
	
	public function select()
	{
		print 'TS_WordpressDatabaseConnector::select()';
	}
	
	public static function insert($db_table, $args)
	{
		// Make sure the database table name is set
		if(empty($db_table))
			return false;
		
		// Make sure that there are arguments
		if(empty($args))
			return false;
		
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