<?php
class TS_User
{
	private $name;
	private $id;
	
	public function __construct(WP_User $user)
	{
		$this->name = $user->display_name;
		$this->id = $user->ID;
	}
	
	public function getID()
	{
		return $this->id;
	}
	
	public function getName()
	{
		return $this->name;
	}
}
?>