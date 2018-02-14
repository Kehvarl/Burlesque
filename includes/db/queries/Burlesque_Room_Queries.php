<?php


class Burlesque_Room_Queries
{
    public $prefix;

    public function __construct($prefix = "")
    {
        $this->prefix = $prefix;
    }

    public function add_room()
    {
        $query =  "INSERT IGNORE INTO `".$this->prefix."rooms` ";
        $query .= "(room, color, font, allow_alias, is_public, description) ";
        $query .= "VALUES (:room, :color, :font, :allow_alias, :is_public, :description);";
        return $query;
    }

    public function update_room()
    {
        $query =  "UPDATE `".$this->prefix."rooms` ";
        $query .= "SET room = :room, ";
        $query .= "color = :color, ";
        $query .= "font = :font, ";
        $query .= "allow_alias = :allow_alias, ";
        $query .= "is_public = :is_public, ";
        $query .= "description = :description, ";
        $query .= "WHERE id = :id;";
        return $query;
    }

    public function delete_room()
    {
        $query =  "DELETE FROM  `".$this->prefix."rooms` ";
        $query .= "WHERE id = :id;";
        return $query;
    }

    public function get_all_room_list()
    {
        $query =  "SELECT id, room, color, font, ";
        $query .= "allow_alias, is_public, description ";
        $query .= "FROM `".$this->prefix."rooms`;";
        return $query;
    }

    public function get_room_list()
    {
        $query =  "SELECT DISTINCT r.id, r.room, r.color, r.font, ";
        $query .= "r.allow_alias, r.is_public, r.description ";
        $query .= "FROM `".$this->prefix."rooms` as r ";
        $query .= "LEFT JOIN `".$this->prefix."users` as u ON u.room_id = r.id ";
        $query .= "WHERE r.is_public = 1 OR u.forum_id = :forum_id;";
        return $query;
    }

    public function get_room()
    {
        $query =  "SELECT id, room, color, font, allow_alias, is_public, description";
        $query .= "FROM `".$this->prefix."rooms` WHERE id = :id;";
        return $query;
    }
}


?>
