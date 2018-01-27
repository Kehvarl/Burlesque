<?php
/**
 * Colors
 *
  * Get Next Color-sort Value
 * Add Color
 * Update Color
 * Delete Color
 * Get All Colors
 */
class Burlesque_DB_Colors
{
    public $prefix;
    
    public function __construct($prefix = "")
    {
        $this->prefix = $prefix;
    }
    
    private function qry_get_next_sort_index()
    {
        $query =  "SELECT max(sort)+1 ";
        $query .= "FROM `".$this->prefix."colors`";
        return $query;
    }
    
    private function qry_add_color()
    {
        $query =  "INSERT IGNORE INTO `".$this->prefix."colors`";
        $query .="(name, code, sort)";
        $query .="VALUES(:name, :code, :sort);";
        return $query;
    }
    
    private function qry_update_color()
    {
        $query =  "UPDATE `".$this->prefix."colors`";
        $query .= "SET name = :name";
        $query .= "code = :code,";
        $query .= "sort = :sort";
        $query .= "WHERE id = :id";
        return $query;
    }
    
    private function qry_delete_color()
    {
        $query =  "DELETE FROM `".$this->prefix."colors`";
        $query .= "WHERE id = :id";
        return $query;
    }
    
    private function qry_get_color_list()
    {
        $query =  "SELECT id, name, code, sort ";
        $query .= "FROM `".$this->prefix."colors`";
        $query .= "ORDER BY sort;";
        return $query;
    }
    
    /**
     * Get Next Color-Sort Index.
     * Returns the next highest value for sorting colors
     *
     * @param Burlesque_DB_Connection $connection
     *
     * @return integer
     */
    public function get_next_sort_index($connection)
    {
        $get_next = $connection->prepare($this->qry_get_next_sort_index());
        $connection->execute($get_next, "get next available color index");
        return $get_color_list->fetchAll()[0];
    }
    
    /**
     * Add a Color to the Database
     *
     * @param Burlesque_DB_Connection $connection
     * @param Color $color a Burlesque Color object to insert
     */
    public function add_color($connection, $color)
    {
        $add_color = $connection->prepare($this->qry_add_color());
        $add_color->bindParam(":name", $color->name, PDO::PARAM_STR);
        $add_color->bindParam(":code", $color->code, PDO::PARAM_STR);
        $add_color->bindParam(":sort", $color->sort, PDO::PARAM_INT);
        
        $connection->execute($add_color, "add Color to table");
        
        return $connection->last_insert_id();
    }
    
    /**
     * Edit a Color in the Database
     *
     * @param Burlesque_DB_Connection $connection
     * @param Color $color a Burlesque Color object representing the item to be changed
     */
    public function update_color($connection, $color)
    {
        $update_color = $connection->prepare($this->qry_update_color());
        $update_color->bindParam(":name", $color->name, PDO::PARAM_STR);
        $update_color->bindParam(":code", $color->code, PDO::PARAM_STR);
        $update_color->bindParam(":sort", $color->sort, PDO::PARAM_INT);
        $update_color->bindParam(":id", $color->id, PDO::PARAM_INT);
        
        $this->execute($update_color, "update Color in table");
    }
    
    /**
     * Delete Color from Database
     *
     * @param Burlesque_DB_Connection $connection
     * @param Color $color a Burlesque Color object representing the item to be removed
     */
    public function delete_color($connection, $color)
    {
        $delete_color = $connection->prepare($this->qry_delete_color());
        $delete_color->bindParam(":id", $color->id, PDO::PARAM_INT);
        
        $this->execute($delete_color, "remove Color from table");
    }
    
    /**
     * Get all Colors from the database
     *
     * @param Burlesque_DB_Connection $connection
     */
    public function get_color_list($connection)
    {
        $get_color_list = $connection->prepare($this->qry_get_color_list());
        $this->execute($get_color_list, "Get all Colors");
        
        return $get_color_list->fetchAll();    
    }
}
?>