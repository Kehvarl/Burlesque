<?php

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

 ?>
