<?php


class Burlesque_Color_Queries
{
    public $prefix;

    public function __construct($prefix = "")
    {
        $this->prefix = $prefix;
    }

    public function add_color()
    {
        $query =  "INSERT IGNORE INTO `".$this->prefix."colors`";
        $query .="(name, code, sort)";
        $query .="VALUES(:name, :code, :sort);";
        return $query;
    }

    public function update_color()
    {
        $query =  "UPDATE `".$this->prefix."colors`";
        $query .= "SET name = :name";
        $query .= "code = :code,";
        $query .= "sort = :sort";
        $query .= "WHERE id = :id";
        return $query;
    }

    public function delete_color()
    {
        $query =  "DELETE FROM `".$this->prefix."colors`";
        $query .= "WHERE id = :id";
        return $query;
    }

    public function get_color_list()
    {
        $query =  "SELECT id, name, code, sort ";
        $query .= "FROM `".$this->prefix."colors`";
        $query .= "ORDER BY sort;";
        return $query;
    }

    public function get_next_sort()
    {
        $query =  "SELECT max(sort)+1 ";
        $query .= "FROM `".$this->prefix."colors`";
    }
}


 ?>
