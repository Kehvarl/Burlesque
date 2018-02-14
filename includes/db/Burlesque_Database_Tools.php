<?php

require_once('queries/Burlesque_Setup_Queries');
require_once('queries/Burlesque_Color_Queries');
require_once('queries/Burlesque_Room_Queries');
require_once('queries/Burlesque_User_Queries');
require_once('queries/Burlesque_Post_Queries');

/**
 * Connect to Burlesque database and perform all needed operations.
 * This class handles all database transactions for Burlesque, including
 * table creation, adds, updates, deletes, and selects.
 */
class Burlesque_DB_Tools
{
    private $table_prefix;
    private $pdo;

    public $error = false;

    /**
     * @param string $username the name to use when connecting to the database
     * @param string $password the password to connect to the chat database
     * @param string $database the name of the database to use (default: burlesque)
     * @param string $table_prefix a prefix to apply to each table's name (default: none)
     * @param string $host the hostname or IP of the database server (default: localhost)
     * @param string $post the port number to use to connect to mysql (default: 3306)
     *
     * @return boolean
     */
    public function __construct($username, $password, $database="burlesque",
                                $table_prefix = "", $host="localhost",
                                $port = "3306")
    {
        try
        {
            $this->pdo = new PDO(
                                 'mysql:host='.$host.';port='.$port.';dbname='.$database,
                                 $username,
                                 $password);
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        }
        catch(PDOException $e)
        {
            $this->error += $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Attempt to exectute a query, capture any errors to an internal log.
     *
     * @param PDOStatement $query the query to execute (must be a PDO prepared statement)
     * @param string $label A label for this query used in any error reporting
     * @param array|boolean $bind A key/value array of additional parameters to bind to the query
     */
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

    /**
     * Create all tables needed by Burlesque.
     */
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
