<?php
/**
 * Burlesque Database Setup.
 * Creates all needed tables for Burlesque
 */
class Burlesque_DB_Setup
{
    public $prefix;
    
    public function __construct($prefix = "")
    {
        $this->prefix = $prefix;
    }
    
    private function create_rooms()
    {
        $query =  "CREATE TABLE IF NOT EXISTS `".$this->prefix."rooms`(";
        $query .= " `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
        $query .= " `room` varchar(64) NOT NULL UNIQUE,";
        $query .= " `color` varchar(32) NOT NULL,";
        $query .= " `font` varchar(64) NOT NULL,";
        $query .= " `allow_alias` boolean NOT NULL DEFAULT 0,";
        $query .= " `is_public` boolean NOT NULL DEFAULT 1,";
        $query .= " `description` texti,";
        $query .= " `aspects` text";
        $query .= ") COMMENT='Burlesque Chat Rooms';";
        return $query;
    }
    
    private function create_posts()
    {
        $query =  "CREATE TABLE IF NOT EXISTS `".$this->prefix."posts`(";
        $query .= " `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
        $query .= " `room_id` int UNSIGNED NOT NULL,";
        $query .= " `prefix` varchar(16),";
        $query .= " `prefix_color` varchar(32),";
        $query .= " `sender_id` int UNSIGNED NOT NULL,";
        $query .= " `sender_forum_id` int UNSIGNED NOT NULL,";
        $query .= " `sender_forum_name` varchar(255) NOT NULL,";
        $query .= " `sender_name` varchar(255) NOT NULL,";
        $query .= " `target_id` int UNSIGNED NOT NULL,";
        $query .= " `target_name` varchar(255) NOT NULL,";
        $query .= " `color` varchar(32) NOT NULL,";
        $query .= " `font` varchar(64) NOT NULL,";
        $query .= " `message` TEXT,";
        $query .= " `aspects` TEXT";
        $query .= " `raw` TEXT,";
        $query .= " `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $query .= ") COMMENT='Burlesque Chat Posts';";
        return $query;
    }
    
    private function create_users()
    {
        $query =  "CREATE TABLE IF NOT EXISTS `".$this->prefix."users`(";
        $query .= " `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
        $query .= " `forum_id` int(11) UNSIGNED NOT NULL,";
        $query .= " `forum_name` VARCHAR(255) NOT NULL,";
        $query .= " `room_id` int(11) UNSIGNED NOT NULL,";
        $query .= " `display_name` VARCHAR(255) NOT NULL,";
        $query .= " `aspects` TEXT,";
        $query .= " `login` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
        $query .= " `last_post` TIMESTAMP,";
        $query .= " `logout` TIMESTAMP,";
        $query .= " UNIQUE KEY `user_unique_in_room` (`room_id`, `display_name`))";
        $query .= "COMMENT='Burlesque Chat Users';";
        return $query;
    }
    
    private function create_colors()
    {
        $query =  "CREATE TABLE IF NOT EXISTS `".$this->prefix."colors`( ";
        $query .= " `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $query .= " `name` VARCHAR(32) UNIQUE NOT NULL, ";
        $query .= " `code` VARCHAR(32) NOT NULL, ";
        $query .= " `sort` INT UNSIGNED UNIQUE NOT NULL) ";
        $query .= "COMMENT='Burlesque Chat Colors List'; ";
        return $query;
    }
    
    /**
     * Create all tables needed by Burlesque.
     *
     * @param Burlesque_DB_Connection $connection
     */
    public function setup($connection)
    {
        $create_rooms  = $connection->prepare($this->create_rooms());
        $create_posts  = $connection->prepare($this->create_posts());
        $create_users  = $connection->prepare($this->create_users());
        $create_colors = $connection->prepare($this->create_colors());
       
        $connection->execute($create_rooms,  "rooms Table");
        $connection->execute($create_posts,  "posts Table");
        $connection->execute($create_users,  "users Table");
        $connection->execute($create_colors, "users Table");
    }
}
?>