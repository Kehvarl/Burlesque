<?php

class Burlesque_Setup_Queries
{
    public $prefix;
    
    public function __construct($prefix = "")
    {
        $this->prefix = $prefix;
    }
    
    public function create_rooms()
    {
        $query =  "CREATE TABLE IF NOT EXISTS `".$this->prefix."rooms`(";
        $query .= "`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
        $query .= "`room` varchar(64) NOT NULL UNIQUE,";
        $query .= "`color` varchar(32) NOT NULL,";
        $query .= "`font` varchar(64) NOT NULL,";
        $query .= "`allow_alias` boolean NOT NULL DEFAULT 0,";
        $query .= "`is_public` boolean NOT NULL DEFAULT 1,";
        $query .= "`description` texti,";
	$query .= "`aspects`text";
	$query .= ") COMMENT='Burlesque Chat Rooms';";
        return $query;
    }
    
    public function create_posts()
    {
        $query =  "CREATE TABLE IF NOT EXISTS `".$this->prefix."posts`(";
        $query .= "`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
        $query .= "`room_id` int UNSIGNED NOT NULL,";
        $query .= "`prefix` varchar(16),";
        $query .= "`prefix_color` varchar(32),";
        $query .= "`sender_id` int UNSIGNED NOT NULL,";
        $query .= "`sender_forum_id` int UNSIGNED NOT NULL,";
        $query .= "`sender_forum_name` varchar(255) NOT NULL,";
        $query .= "`sender_name` varchar(255) NOT NULL,";
        $query .= "`target_id` int UNSIGNED NOT NULL,";
        $query .= "`target_name` varchar(255) NOT NULL,";
        $query .= "`color` varchar(32) NOT NULL,";
        $query .= "`font` varchar(64) NOT NULL,";
	$query .= "`message` TEXT,";
	$query .= "`aspects` TEXT";
        $query .= "`raw` TEXT,";
        $query .= "`timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $query .= ") COMMENT='Burlesque Chat Posts';";
        return $query;
    }
    
    public function create_users()
    {
        $query =  "CREATE TABLE IF NOT EXISTS `".$this->prefix."users`(";
        $query .= "`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
        $query .= "`forum_id` int(11) UNSIGNED NOT NULL,";
        $query .= "`forum_name` VARCHAR(255) NOT NULL,";
        $query .= "`room_id` int(11) UNSIGNED NOT NULL,";
	$query .= "`display_name` VARCHAR(255) NOT NULL,";
	$query .= "`aspects` TEXT,";
        $query .= "`login` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
        $query .= "`last_post` TIMESTAMP,";
        $query .= "`logout` TIMESTAMP,";
        $query .= "UNIQUE KEY `user_unique_in_room` (`room_id`, `display_name`))";
        $query .= "COMMENT='Burlesque Chat Users';";
        return $query;
    }
    
    public function create_colors()
    {
        $query =  "CREATE TABLE IF NOT EXISTS `".$this->prefix."colors`( ";
        $query .= "`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $query .= "`name` VARCHAR(32) UNIQUE NOT NULL, ";
        $query .= "`code` VARCHAR(32) NOT NULL, ";
        $query .= "`sort` INT UNSIGNED UNIQUE NOT NULL) ";
        $query .= "COMMENT='Burlesque Chat Colors List'; ";
        return $query;
    }
}

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
        $query .= "(room, color, font, allow_alias, is_public, description, aspects) ";
        $query .= "VALUES (:room, :color, :font, :allow_alias, :is_public, :description, :aspects);";
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
	$query .= "aspects = :aspects ";
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
        $query =  "SELECT id, room, color, font, allow_alias, is_public, description, aspects";
        $query .= "FROM `".$this->prefix."rooms` WHERE id = :id;";
        return $query;
    }
}

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
        $query .="(forum_id, forum_name, room_id, display_name, aspects)";
        $query .="VALUES(:forum_id, :forum_name, :room_id, :display_name, :aspects);";
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
	$query .= "aspects = :aspects ";
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

    public function update_user_aspects()
    {
	$query =  "UPDATE `".$this->prefix."users1` ";
	$query .= "SET aspects = :aspects ";
	$query .= "WHERE is = :id; ";
    }
    
    public function get_user()
    {
        $query = "SELECT id, forum_id, forum_name, room_id, display_name, login, last_post, logout, aspects ";
        $query .= "FROM `".$this->prefix."users` ";
        $query .= "WHERE room_id = :room_id and display_name = :display_name";
        
        return $query;
    }
    
    public function get_user_by_id()
    {
        $query = "SELECT id, forum_id, forum_name, room_id, display_name, login, last_post, logout, aspects ";
        $query .= "FROM `".$this->prefix."users` ";
        $query .= "WHERE id = :user_id;";
        
        return $query;
    }
    
    public function get_user_list()
    {
        $query = "SELECT id, forum_id, forum_name, room_id, display_name, login, last_post, logout, aspects ";
        $query .= "FROM `".$this->prefix."users` ";
        $query .= "WHERE room_id = :room_id";
        
        return $query;
    }
}

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
	$query .= "color, font, aspects, ";
	$query .= "message, raw)";
        $query .= "VALUES(:room_id, :prefix, :prefix_color,";
        $query .= ":sender_id, :sender_name,";
        $query .= ":target_id, :target_name,";
	$query .= ":color, :font, aspects, ";
	$query .= ":message, :raw);";
        return $query;
    }
    
    public function get_posts($placeholders=false)
    {
        $query =  "SELECT id, prefix, prefix_color, sender_id, sender_name,";
        $query .= "target_id, target_name, color, font, aspects, message, timestamp ";
        $query .= "FROM `".$this->prefix."posts` WHERE room_id = :room_id ";
        if($placeholders)
            $query .= "AND sender_id NOT IN ($placeholders) ";
        $query .= "ORDER BY timestamp DESC LIMIT :count";
        return $query;
    }
}

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

class Burlesque_DB_Tools
{
    private $database;
    private $host;
    private $port;
    private $username;
    private $password;
    private $table_prefix;
    
    private $pdo;
    
    public $error = false;
    
    public function __construct($username, $password, $database="burlesque",
                                $host="localhost", $port = "3306", $table_prefix = "")
    {
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->host = $host;
        $this->port = $port;
        $this->init();
    }
    
    public function init()
    {
        try{
       $this->pdo = new PDO(
                   'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->database,
                   $this->username,
                   $this->password
               );
       $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
       }
       catch(PDOException $e)
       {
           $this->error += $e->getMessage();
           return false;
       }
       return true;
    }
    
    private function execute($query, $label, $bind = false)
    {
        if($bind)
            $result = $query->execute($bind);
        else
            $result = $query->execute();
        if(!$result)
        {
            $this->error += "\n\n Problem with" . $label . " Error contents: ";
            $this->error += print_r($query->getErrorInfo(), true);
        }
    }
    
    public function setup()
    {
        $queries = new Burlesque_Setup_Queries($this->table_prefix);
        $create_rooms  = $this->pdo->prepare($queries->create_rooms());
        $create_posts  = $this->pdo->prepare($queries->create_posts());
        $create_users  = $this->pdo->prepare($queries->create_users());
        $create_colors = $this->pdo->prepare($queries->create_colors());
       
        $this->execute($create_rooms,  "rooms Table");
        $this->execute($create_posts,  "posts Table");
        $this->execute($create_users,  "users Table");
        $this->execute($create_colors, "users Table");
    }
    
    public function add_room($room)
    {
        $queries = new Burlesque_Room_Queries($this->table_prefix);
        $add_room  = $this->pdo->prepare($queries->add_room());
        $add_room->bindParam(":room", $room->room, PDO::PARAM_STR);
        $add_room->bindParam(":color", $room->color, PDO::PARAM_STR);
        $add_room->bindParam(":font", $room->font, PDO::PARAM_STR);
        $add_room->bindParam(":allow_alias", $room->allow_alias, PDO::PARAM_BOOL);
        $add_room->bindParam(":is_public", $room->is_public, PDO::PARAM_BOOL);
        $add_room->bindParam(":description", $room->description, PDO::PARAM_STR);
        
        $this->execute($add_room, "add Room to table");
        
        return $this->pdo->lastInsertId();
    }

    public function add_post($post, $room_id=1, $raw="")
    {
        $queries = new Burlesque_Post_Queries($this->table_prefix);
        $add_post  = $this->pdo->prepare($queries->add_post());
        $add_post->bindParam(":room_id", $room_id, PDO::PARAM_STR);
        $add_post->bindParam(":prefix", $post->prefix, PDO::PARAM_STR);
        $add_post->bindParam(":prefix_color", $post->prefix_color, PDO::PARAM_STR);
        $add_post->bindParam(":sender_id", $post->sender_id, PDO::PARAM_INT);
        $add_post->bindParam(":sender_name", $post->sender, PDO::PARAM_STR);
        $add_post->bindParam(":target_id", $post->target_id, PDO::PARAM_INT);
        $add_post->bindParam(":target_name", $post->target, PDO::PARAM_STR);
        $add_post->bindParam(":color", $post->color, PDO::PARAM_STR);
        $add_post->bindParam(":font", $post->font, PDO::PARAM_STR);
        $add_post->bindParam(":message", $post->message, PDO::PARAM_STR);
        $add_post->bindParam(":raw", $raw, PDO::PARAM_STR);
        
        $this->execute($add_post, "add Post to table");
        
        return $this->pdo->lastInsertId();
    }
       
    public function add_user($user, $room)
    {
        $queries = new Burlesque_User_Queries($this->table_prefix);
        $add_user = $this->pdo->prepare($queries->add_user());
        $add_user->bindParam(":room_id", $room, PDO::PARAM_INT);
        $add_user->bindParam(":forum_id", $user->forum_id, PDO::PARAM_INT);
        $add_user->bindParam(":forum_name", $user->forum_name, PDO::PARAM_INT);
        $add_user->bindParam(":display_name", $user->display_name, PDO::PARAM_STR);
        
        $this->execute($add_user, "add User to table (login!)");
        
        return $this->pdo->lastInsertId();
    }
    
    public function add_color($color)
    {
        $queries = new Burlesque_Color_Queries($this->table_prefix);
        $add_color = $this->pdo->prepare($queries->add_color());
        $add_color->bindParam(":name", $color->name, PDO::PARAM_STR);
        $add_color->bindParam(":code", $color->code, PDO::PARAM_STR);
        $add_color->bindParam(":sort", $color->sort, PDO::PARAM_INT);
        
        $this->execute($add_color, "add Color to table");
        
        return $this->pdo->lastInsertId();
    }
    
    public function update_room($room)
    {
        $queries = new Burlesque_Room_Queries($this->table_prefix);
        $add_room  = $this->pdo->prepare($queries->update_room());
        $add_room->bindParam(":id", $room->id, PDO::PARAM_STR);
        $add_room->bindParam(":room", $room->room, PDO::PARAM_STR);
        $add_room->bindParam(":color", $room->color, PDO::PARAM_STR);
        $add_room->bindParam(":font", $room->font, PDO::PARAM_STR);
        $add_room->bindParam(":allow_alias", $room->allow_alias, PDO::PARAM_BOOL);
        $add_room->bindParam(":is_public", $room->is_public, PDO::PARAM_BOOL);
        $add_room->bindParam(":description", $room->description, PDO::PARAM_STR);
        
        return $this->execute($add_room, "edit Room in table");
    }
    
    public function update_user($user)
    {
        $queries = new Burlesque_User_Queries($this->table_prefix);
        $update_user = $this->pdo->prepare($queries->update_user());
        //$update_user->bindParam(":display_name", $user->display_name, PDO::PARAM_STR);
        $update_user->bindParam(":login", $user->login, PDO::PARAM_STR);
        $update_user->bindParam(":last_post", $user->last_post, PDO::PARAM_STR);
        $update_user->bindParam(":logout", $user->logout, PDO::PARAM_STR);
        $update_user->bindParam(":id", $user->id, PDO::PARAM_INT);
        
        return $this->execute($update_user, "update User in table (new login, post, logout)");
    }
    
    public function update_color($color)
    {
        $queries = new Burlesque_Color_Queries($this->table_prefix);
        $add_color = $this->pdo->prepare($queries->update_color());
        $add_color->bindParam(":name", $color->name, PDO::PARAM_STR);
        $add_color->bindParam(":code", $color->code, PDO::PARAM_STR);
        $add_color->bindParam(":sort", $color->sort, PDO::PARAM_INT);
        $add_color->bindParam(":id", $color->id, PDO::PARAM_INT);
        
        return $this->execute($add_color, "update Color in table");
    }
    
    public function delete_room($room_id)
    {
        $queries = new Burlesque_Room_Queries($this->table_prefix);
        $delete_room = $this->pdo->prepare($queries->delete_room());
        $delete_room->bindParam(":id", $room_id, PDO::PARAM_INT);
        
        return $this->execute($delete_room, "remove Room from table");
    }
    
    public function delete_color($color_id)
    {
        $queries = new Burlesque_Color_Queries($this->table_prefix);
        $delete_color = $this->pdo->prepare($queries->delete_color());
        $delete_color->bindParam(":id", $color_id, PDO::PARAM_INT);
        
        return $this->execute($delete_color, "remove Color from table");
    }
    
    public function get_user_by_id($user_id)
    {
        $queries = new Burlesque_User_Queries($this->table_prefix);
        $get_user = $this->pdo->prepare($queries->get_user_by_id());
        $get_user->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        
        $this->execute($get_user, "get User form table...");
        
        return $get_user->fetchAll()[0];
    }
    
    public function get_user($user_name, $room_id)
    {
        $queries = new Burlesque_User_Queries($this->table_prefix);
        $get_user = $this->pdo->prepare($queries->get_user());
        $get_user->bindParam(":room_id", $room_id, PDO::PARAM_INT);
        $get_user->bindParam(":display_name", $user_name, PDO::PARAM_STR);
        
        $this->execute($get_user, "get User form table...");
        
        return $get_user->fetchAll()[0];
    }
    
    public function get_user_list($room_id)
    {
        $queries = new Burlesque_User_Queries($this->table_prefix);
        $get_user = $this->pdo->prepare($queries->get_user_list());
        $get_user->bindParam(":room_id", $room_id, PDO::PARAM_INT);
        
        $this->execute($get_user, "get User form table...");
        
        return $get_user->fetchAll();
    }
    
    public function get_posts($room_id, $count = 30, $ignore = false)
    {
        //$params = array(':room'=>$room, ':count'=>$count);
        $params = array();
        $ignore_slots = false;
        if($ignore)
        {
            foreach($ignore as $k=>$v)
            {
                $ignore_slots[] = ':ignore_'.$k;
                $params[':ignore_'.$k] = $v;
            }
            $ignore_slots = join(',', $ignore_slots);
        }
        
        $queries = new Burlesque_Post_Queries($this->table_prefix);
        $get_posts = $this->pdo->prepare($queries->get_posts($ignore_slots));
        $get_posts->bindParam(":room_id", $room_id, PDO::PARAM_STR);
        $get_posts->bindParam(":count", $count, PDO::PARAM_INT);
        $this->execute($get_posts, "Get posts from Table", $params);
        
        return $get_posts->fetchAll();
    }
    
    public function get_room_list($forum_id = 0)
    {
        $queries = new Burlesque_Room_Queries($this->table_prefix);
        $get_room_list = $this->pdo->prepare($queries->get_room_list());
        $get_room_list->bindParam(":forum_id", $forum_id, PDO::PARAM_INT);
        $this->execute($get_room_list, "Get visible Rooms");
        
        return $get_room_list->fetchAll();
    }
    
    public function get_all_room_list()
    {
        $queries = new Burlesque_Room_Queries($this->table_prefix);
        $get_all_room_list = $this->pdo->prepare($queries->get_all_room_list());
        $this->execute($get_all_room_list, "Get all Rooms");
        
        return $get_all_room_list->fetchAll();
    }
    
    public function get_room($room_id)
    {
        $queries = new Burlesque_Room_Queries($this->table_prefix);
        $get_room = $this->pdo->prepare($queries->get_room());
        $get_room->bindParam(":id", $room_id, PDO::PARAM_INT);
        $this->execute($get_room, "Get specific room $room_id");
        
        return $get_room->fetchAll()[0];
    }
    
    public function get_color_list()
    {
        $queries = new Burlesque_Color_Queries($this->table_prefix);
        $get_color_list = $this->pdo->prepare($queries->get_color_list());
        $this->execute($get_color_list, "Get all Colors");
        
        return $get_color_list->fetchAll();        
    }
    
    public function get_color_next_sort()
    {
        $queries = new Burlesque_Color_Queries($this->table_prefix);
        $get_color_next_sort = $this->pdo->prepare($queries->get_next_sort());
        $this->execute($get_color_next_sort, "Get next Color sort");
        
        return $get_color_list->fetchAll()[0];
    }
}
?>
