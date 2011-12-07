<?php
interface TS_DatabaseConnector
{
	public function connect();
	public function disconnect();
	public function select();
	public static function insert($db_table, $args);
	public function delete();
	public function update();
}
?>