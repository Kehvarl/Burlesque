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

 ?>
