<?php

class User
{
	public $id = "";
	public $forum_id = "";
	public $forum_name = "";
	public $room_id = "";
	public $display_name = "";
	public $login = "";
	public $last_post = "";
	public $logout = "";

	public static function fromDBResult($_user)
	{
		$user = new User();
		$user->id = 			$_user['id'];
		$user->forum_id =		$_user['forum_id'];
		$user->forum_name = 	$_user['forum_name'];
		$user->display_name =   $_user['display_name'];
		$user->room_id =       	$_user['room_id'];
		$user->login = 			$_user['login'];
		$user->last_post = 	 	$_user['last_post'];
		$user->logout = 	 	$_user['logout'];

		return $user;
	}

	public function toArray()
	{
		return array("user"=>array(
			"id"=>$this->id,
			"forum_id"=>$this->forum_id,
			"forum_name"=>$this->forum_name,
			"display_name"=>$this->display_name,
			"room_id"=>$this->room_id,
			"login"=>$this->login,
			"last_post"=>$this->last_post,
			"logout"=>$this->logout
		));
	}
}

?>
