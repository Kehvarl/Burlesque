<?php

class Burlesque_User_Queries
{
    public $prefix;

    public function __construct($prefix = "")
    {
        $this->prefix = $prefix;
    }

    public function add_user()
    {
        $query =  "INSERT IGNORE INTO `".$this->prefix."users`";
        $query .="(forum_id, forum_name, room_id, display_name)";
        $query .="VALUES(:forum_id, :forum_name, :room_id, :display_name);";
        return $query;
    }

    public function delete_user()
    {
        $query =  "DELETE FROM `".$this->prefix."users` ";
        $query .= "WHERE id = :id; ";
        return $query;
    }

    public function update_user()
    {
        $query =  "UPDATE `".$this->prefix."users` ";
        $query .= "SET ";
        //$query .= "display_name = :display_name, ";
        $query .= "login = :login, ";
        $query .= "last_post = :last_post, ";
        $query .= "logout = :logout, ";
        $query .= "WHERE id = :id; ";
        return $query;
    }

    public function update_user_display()
    {
        $query =  "UPDATE `".$this->prefix."users` ";
        $query .= "SET ";
        $query .= "display_name = :display_name ";
        $query .= "WHERE id = :id; ";
        return $query;
    }

    public function get_user()
    {
        $query = "SELECT id, forum_id, forum_name, room_id, display_name, login, last_post, logout ";
        $query .= "FROM `".$this->prefix."users` ";
        $query .= "WHERE room_id = :room_id and display_name = :display_name";

        return $query;
    }

    public function get_user_by_id()
    {
        $query = "SELECT id, forum_id, forum_name, room_id, display_name, login, last_post, logout ";
        $query .= "FROM `".$this->prefix."users` ";
        $query .= "WHERE id = :user_id;";

        return $query;
    }

    public function get_user_list()
    {
        $query = "SELECT id, forum_id, forum_name, room_id, display_name, login, last_post, logout ";
        $query .= "FROM `".$this->prefix."users` ";
        $query .= "WHERE room_id = :room_id";

        return $query;
    }
}

?>
