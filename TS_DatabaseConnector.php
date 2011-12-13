<?php
interface TS_DatabaseConnector
{
	public function connect();
	public function disconnect();
	public function select($db_table, $cols);
	public static function insert($db_table, $args);
	public function delete();
	public function update();
	public static function query($query);
}
?>