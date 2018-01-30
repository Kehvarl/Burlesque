<?php
/**
 * Rooms
 *
 * Add Room
 * Update Room
 * Delete Room
 * Get Rooms For User
 * Get All Rooms
 * Get Room Details
 */
class Burlesque_DB_Rooms
{
    public $prefix;
    
    public function __construct($prefix = "")
    {
        $this->prefix = $prefix;
    }
    
    private function qry_add_room()
    {
        $query =  "INSERT IGNORE INTO `".$this->prefix."rooms` ";
        $query .= "(room, color, font, allow_alias, is_public, description, aspects) ";
        $query .= "VALUES (:room, :color, :font, :allow_alias, :is_public, :description, :aspects);";
        return $query;
    }
    
    private function qry_update_room()
    {
        $query =  "UPDATE `".$this->prefix."rooms` ";
        $query .= "SET room = :room, ";
        $query .= "color = :color, ";
        $query .= "font = :font, ";
        $query .= "allow_alias = :allow_alias, ";
        $query .= "is_public = :is_public, ";
        $query .= "description = :description, ";
        $query .= "aspects = :aspects ";
        $query .= "WHERE id = :id;";
        return $query;
    }
    
    private function qry_delete_room()
    {
        $query =  "DELETE FROM  `".$this->prefix."rooms` ";
        $query .= "WHERE id = :id;";
        return $query;
    }
    
    private function qry_get_room_list()
    {
        $query =  "SELECT DISTINCT r.id, r.room, r.color, r.font, ";
        $query .= "r.allow_alias, r.is_public, r.description ";
        $query .= "FROM `".$this->prefix."rooms` as r ";
        $query .= "LEFT JOIN `".$this->prefix."users` as u ON u.room_id = r.id ";
        $query .= "WHERE r.is_public = 1 OR u.forum_id = :forum_id;";
        return $query;
    }
    
    private function qry_get_all_rooms()
    {
        $query =  "SELECT id, room, color, font, ";
        $query .= "allow_alias, is_public, description ";
        $query .= "FROM `".$this->prefix."rooms`;";
        return $query;
    }
    
    private function qry_get_room()
    {
        $query =  "SELECT id, room, color, font, allow_alias, is_public, description, aspects";
        $query .= "FROM `".$this->prefix."rooms` WHERE id = :id;";
        return $query;
    }
    
    /**
     * Add a Room to the Database
     *
     * @param Burlesque_DB_Connection $connection
     * @param Room $room a Burlesque Room object to insert
     */
    public function add_room($connection, $room)
    {
        $add_room  = $connection->prepare($this->qry_add_room());
        $add_room->bindParam(":room", $room->room, PDO::PARAM_STR);
        $add_room->bindParam(":color", $room->color, PDO::PARAM_STR);
        $add_room->bindParam(":font", $room->font, PDO::PARAM_STR);
        $add_room->bindParam(":allow_alias", $room->allow_alias, PDO::PARAM_BOOL);
        $add_room->bindParam(":is_public", $room->is_public, PDO::PARAM_BOOL);
        $add_room->bindParam(":description", $room->description, PDO::PARAM_STR);
        
        $connection->execute($add_room, "add Room to table");
        
        return $connection->lastInsertId();
    }
    
    /**
     * Change a Room in the Database
     *
     * @param Burlesque_DB_Connection $connection
     * @param Room $room a Burlesque Room object to update
     */
    public function update_room($connection, $room)
    {
        $add_room  = $connection->prepare($this->qry_update_room());
        $add_room->bindParam(":id", $room->id, PDO::PARAM_STR);
        $add_room->bindParam(":room", $room->room, PDO::PARAM_STR);
        $add_room->bindParam(":color", $room->color, PDO::PARAM_STR);
        $add_room->bindParam(":font", $room->font, PDO::PARAM_STR);
        $add_room->bindParam(":allow_alias", $room->allow_alias, PDO::PARAM_BOOL);
        $add_room->bindParam(":is_public", $room->is_public, PDO::PARAM_BOOL);
        $add_room->bindParam(":description", $room->description, PDO::PARAM_STR);
        
        return $connection->execute($add_room, "edit Room in table");
    }
    
    /**
     * Remove a Room from the Database
     *
     * @param Burlesque_DB_Connection $connection
     * @param Room $room a Burlesque Room object to delete
     */
    public function delete_room($connection, $room)
    {
        $delete_room = $connection->prepare($this->qry_delete_room());
        $delete_room->bindParam(":id", $room->id, PDO::PARAM_INT);
        
        return $connection->execute($delete_room, "remove Room from table");
    }
    
    /**
     * Get all Rooms a User can see
     *
     * @param Burlesque_DB_Connection $connection
     * @param User $user a Burlesque USer object to check for room access
     */
    public function get_room_list($connection, $user)
    {
        $get_room_list = $connection->prepare($this->qry_get_room_list());
        $get_room_list->bindParam(":forum_id", $user->forum_id, PDO::PARAM_INT);
        $connection->execute($get_room_list, "Get visible Rooms");
        
        return $get_room_list->fetchAll();
    }
    
    /**
     * Get all Rooms in the Database
     *
     * @param Burlesque_DB_Connection $connection
     */
    public function get_all_rooms($connection)
    {
        $get_all_room_list = $connection->prepare($this->qry_get_all_rooms());
        $connection->execute($get_all_room_list, "Get all Rooms");
        
        return $get_all_room_list->fetchAll();
    }
    
    /**
     * Get Details on a specific Room from the Database
     *
     * @param Burlesque_DB_Connection $connection
     * @param Room $room a Burlesque Room object to look up (must have $room->id set)
     */
    public function get_room($connection, $room_id)
    {
        $get_room = $connection->prepare($this->qry_get_room());
        $get_room->bindParam(":id", $room_id, PDO::PARAM_INT);
        $connection->execute($get_room, "Get specific room $room_id");
        
        return $get_room->fetchAll()[0];
    }
}
?>