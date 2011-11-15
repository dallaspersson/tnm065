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
	
	public function insert()
	{
		print 'TS_WordpressDatabaseConnector::insert()';
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