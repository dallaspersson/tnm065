<?php
class TS_User
{
	private $firstName;
	private $lastName;
	private $id;
	
	public function __construct(WP_User $user)
	{
		$this->firstName = $user->first_name;
		$this->lastName = $user->last_name;
		$this->id = $user->ID;
	}
	
	public function getID()
	{
		return $this->id;
	}
	
	public function getName()
	{
		return $this->getFirstName() . " " . $this->getLastName();
	}
	
	public function getFirstName()
	{
		return $this->firstName;
	}
	
	public function getLastName()
	{
		return $this->lastName;
	}
	
	public static function getUsers($userIds)
	{
		$users = array();
		
		foreach($userIds as $userId)
		{
			$users[$userId] = get_userdata($userId);
		}
		
		return $users;
	}
}
?>