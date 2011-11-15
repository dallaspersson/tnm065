<?php
interface TS_DatabaseConnector
{
	public function connect();
	public function disconnect();
	public function select();
	public function insert();
	public function delete();
	public function update();
}
?>