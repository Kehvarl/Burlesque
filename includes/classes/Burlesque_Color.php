<?php

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
