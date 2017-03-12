<?php

class Room
{
	public $id=0;
	public $room = "";
	public $color = "";
	public $font = "";
	public $allow_alias = false;
	public $is_public = true;
	public $description = "";
	
	public static function placeholder($name, $font="Verdana", $color="#c0c0c0")
	{
		$room = new Room();
		$room->id 			= -1;
		$room->room 		= $name;
		$room->font			= $font;
		$room->color		= $color;
		$room->allow_alias 	= 0;
		$room->is_public  	= 1;
		
		return $room;
	}
	
	public static function fromDBResult($_room)
	{
		$room = new Room();
		$room->id =          $_room['id'];
		$room->room =        $_room['room'];
		$room->description = $_room['description'];
		$room->font =        $_room['font'];
		$room->color =       $_room['color'];
		$room->allow_alias = $_room['allow_alias'];
		$room->is_public = 	 $_room['is_public'];
		
		return $room;
	}
	
	public static function fromRoom($_room)
	{
		$room = new Room();
		$room->id =          $_room->id;
		$room->room =        $_room->room;
		$room->description = $_room->description;
		$room->font =        $_room->font;
		$room->color =       $_room->color;
		$room->allow_alias = $_room->allow_alias;
		$room->is_public = 	 $_room->is_public;
		return $room;
	}
	
	public function toArray()
	{
		return array("room"=>array(
			"id"=>$this->id,
			"room"=>$this->room,
			"color"=>$this->color,
			"font"=>$this->font,
			"allow_alias"=>$this->allow_alias,
			"is_public"=>$this->is_public,
			"description"=>$this->description
		));
	}
	
	public function toJson()
	{
		return json_encode($this->toArray);
	}
}

class Post
{
	public $id = 0;
	public $prefix = "";
	public $prefix_color = "";
	public $sender_id = "";
	public $sender_forum_id = "";
	public $sender_forum = "";
	public $sender = "";
	public $target_id = "";
	public $target = "";
	public $color = "#c0c0c0";
	public $font = "Verdana";
	public $message = "test";
	public $raw = "";
	public $timestamp = "";
	
	public static function fromDBResult($_post_data)
	{
		$post = new Post();
		$post->id 				= $_post_data['id'];
		$post->prefix 			= $_post_data['prefix'];
		$post->prefix_color 	= $_post_data['prefix_color'];
		$post->sender_id 		= $_post_data['sender_id'];
		//$post->sender_forum_id 	= $_post_data['sender_forum_id'];
		//$post->sender_forum 	= $_post_data['sender_forum'];
		$post->sender 			= $_post_data['sender_name'];
		$post->target_id 		= $_post_data['target_id'];
		$post->target 			= $_post_data['target_name'];
		$post->color 			= $_post_data['color'];
		$post->font 			= $_post_data['font'];
		$post->message 			= $_post_data['message'];
		//$post->raw 				= $_post_data['raw'];
		$post->timestamp 		= $_post_data['timestamp'];
		return $post;
	}

	public function toArray($include_raw = false)
	{
		$return =  array("post"=>array(
			"id"=>$this->id,
			"prefix"=>$this->prefix,
			"prefix_color"=>$this->prefix_color,
			"sender_id"=>$this->sender_id,
			"sender"=>$this->sender,
			"target_id"=>$this->target_id,
			"target"=>$this->target,
			"color"=>$this->color,
			"font"=>$this->font,
			"message"=>$this->message,
			"timestamp"=>$this->timestamp
		));
		if($include_raw)
		{
			$return['raw'] = $this->raw;
		}
		
		return $return;
	}
	public function toJson()
	{			
		return json_encode($this->toArray());
	}
}

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

class Color
{
	public $id = 0;
	public $name = "";
	public $code = "";
	public $sort = 0;
	
	public function setName($name)
	{
		$this->name = ucwords(strtolower($name));
	}
	
	public function setCode($code)
	{
		$code = ltrim(trim($code),'#');
		if(ctype_xdigit($code) &&
		   (strlen($code) == 6 || strlen($code) == 3))
		{
			$code = "#".strtoupper($code);
		}
		$this->code = $code;
	}
	
	public function toArray()
	{
		return array("color"=>array(
			"id"=>$this->id,
			"name"=>$this->name,
			"code"=>$this->code,
			"sort"=>$this->sort
		));
	}
	
	public static function fromDBResult($_color)
	{
		$color = new Color();
		$color->id   = $_color['id'];
		$color->name = $_color['name'];
		$color->code = $_color['code'];
		$color->sort = $_color['sort'];
		return $color;
	}
	
	public static function fromColor($_color, $sort=1)
	{
		$color = new Color();
		$color->setName($_color->name);
		$color->setCode($_color->code);
		if(isset($_color->sort))
			$color->sort = $_color->sort;
		else
			$color->sort = $sort;
		return $color;
	}
}

?>
