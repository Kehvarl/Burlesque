<?php


class Burlesque_Post_Queries
{
    public $prefix;

    public function __construct($prefix = "")
    {
        $this->prefix = $prefix;
    }
    public function add_post()
    {
        $query =  "INSERT INTO `".$this->prefix."posts`";
        $query .= "(room_id, prefix, prefix_color,";
        $query .= "sender_id, sender_name,";
        $query .= "target_id, target_name,";
        $query .= "color, font, ";
        $query .= "message, raw)";
        $query .= "VALUES(:room_id, :prefix, :prefix_color,";
        $query .= ":sender_id, :sender_name,";
        $query .= ":target_id, :target_name,";
        $query .= ":color, :font,  ";
        $query .= ":message, :raw);";
        return $query;
    }

    public function get_posts($placeholders=false)
    {
        $query =  "SELECT id, prefix, prefix_color, sender_id, sender_name,";
        $query .= "target_id, target_name, color, font, message, timestamp ";
        $query .= "FROM `".$this->prefix."posts` WHERE room_id = :room_id ";
        if($placeholders)
            $query .= "AND sender_id NOT IN ($placeholders) ";
        $query .= "ORDER BY timestamp DESC LIMIT :count";
        return $query;
    }
}

?>
