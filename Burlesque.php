<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/Burlesque_Config.php');
require_once('includes/xf_integrations.php');
require_once('includes/classes/Burlesque_Color.php');
require_once('includes/classes/Burlesque_Post.php');
require_once('includes/classes/Burlesque_Room.php');
require_once('includes/classes/Burlesque_User.php');
require_once('includes/db/Burlesque_Database_Tools.php');
require_once('includes/Burlesque_BBCode.php');
require_once('includes/Burlesque_Commands.php');

class Burlesque
{
    private $InputData;

    private $Database;

    private $user_color;
    private $user_font;

    public $Output;

    function __construct($config, $input_data)
    {
        //Connect to Xenforo
				$this->XF = new Burlesque_Xenforo_Integration($_SERVER['DOCUMENT_ROOT']);

        //Setup Datetime module
        $this->DT = new DateTime("now",
            new DateTimeZone($this->XF->getVisitorTimezone());
        $timestamp = time();
        $this->DT->setTimestamp($timestamp);

        //Connect to Database
        $this->Database = new Burlesque_DB_Tools(
                                     $config['db']['username'],
                                     $config['db']['password'],
                                     $config['db']['database'],
                                     $config['db']['host'],
                                     $config['db']['port'],
                                     $config['db']['table_prefix']);
        if($this->Database->error)
        {
            error_log($this->Database->error);
        }

        //Get information from user
        $this->InputData = $input_data;
    }

    function process()
    {
        $this->Output = array();

        switch($this->InputData->action)
        {
            case 'init':
                $this->Output['colors'] = $this->getColors();
                $this->Output['rooms']  = $this->getRooms();
                break;
            case 'login':
                $this->Output['colors'] = $this->getColors();
                $this->Output['login'] = $this->doLogin();
                $this->Output['login']['settings'] = array(
                                    'color'=>$this->InputData->data->color);
                $this->Output['posts'] = $this->getPosts();
                break;
            case 'logout':
                break;
            case 'post':
                $this->Output = $this->doPost();
                $this->user_color = $this->InputData->data->color;
                $this->user_font = $this->InputData->data->font;
                $this->Output['posts'] = $this->getPosts();
                $this->Output['user']['settings'] = array(
                                    'color'=>$this->user_color,
                                    'font'=>$this->user_font);
                break;
            case 'load':
                $this->Output['posts'] = $this->getPosts();
                break;
            default:
                $this->Output['error'] = "Input Validation Error";
        }
    }

    function getRooms()
    {
        $rooms_list = array();
        $rooms = $this->Database->get_room_list(
                                    $this->XF->getUserId());
        foreach($rooms as $_room)
        {
            $rooms_list[] = Room::fromDBResult($_room)->toArray();
        }
        return $rooms_list;
    }

    function getColors()
    {
        $color_list = array();
        $colors = $this->Database->get_color_list();
        foreach($colors as $_color)
        {
            $color_list[] = Color::fromDBResult($_color)->toArray();
        }
        return $color_list;
    }

    function getRoom($room_id)
    {
        return Room::fromDBResult($this->Database->get_room($room_id));
    }

    function doLogin()
    {
        //Get user login details (room, desired name, forum info, etc)
        $room = $this->getRoom($this->InputData->data->room_id);
        $display_name   = $this->InputData->data->display_name;
        $forum_name     = $this->getUserName();
        if(!$room->allow_alias)
        {
            $display_name = $forum_name;
        }
        //Try to load user details from database
        $user = User::fromDBResult(
                    $this->Database->get_user(  $display_name,
                                                $room->id));
        if(!$user->id)
        {
            //User not in DB.  Create new for current room
            $user = new User();
            $user->display_name     = $display_name;
            $user->forum_id         = $this->XF->getUserId();
            $user->forum_name       = $forum_name;
            $user->room_id          = $room->id;
            $user->id               = $this->Database->add_user($user,
                                                                $room->id);
        }
        else
        {
            //User in DB.  Update post and logout times.
            $this->DT->setTimestamp(time());
            $user->login = $this->DT->format('Y-m-d H:i:s');
            $user->last_post = $this->DT->format('Y-m-d H:i:s');
            $this->DT->setTimestamp(0);
            $user->logout = $this->DT->format('Y-m-d H:i:s');
            $this->Database->update_user($user);
            $user = User::fromDBResult(
                                $this->Database->get_user_by_id($user->id));
        }

        //Store user details in session
        $this->XF->Session->set('room'.$room->id.'user'.$user->id, array(
                'color'=> $this->InputData->data->color,
                'font' => $this->InputData->data->font,
                'name' => $user->display_name,
                'id'   => $user->id
        ));
        $this->XF->Session->save();

        //Login Post
        $this->doPost("Login", "#33xx33", false);

        //Return User data to send to client
        return $user->toArray();
    }

    function doPost($prefix = "", $prefix_color = "", $allow_commands = true)
    {
        $message = $this->InputData->data->message;

        //Verify message has no illegal characters
        $message = htmlentities($message, ENT_QUOTES | ENT_IGNORE, "UTF-8");

        $room = $this->getRoom($this->InputData->data->room_id);
        $display_name = $this->InputData->data->display_name;
        $user = User::fromDBResult($this->Database->get_user(   $display_name,
                                                                $room->id));
        $post = new Post();
        $post->prefix           = $prefix;
        $post->prefix_color     = $prefix_color;
        $post->sender_id        = $user->id;
        $post->sender_forum_id  = $user->forum_id;
        $post->sender_forum     = $user->forum_name;
        $post->sender           = $user->display_name;
        $post->target_id        = 0;
        $post->target           = "";
        $post->color            = $this->InputData->data->color;
        $post->font             = $this->InputData->data->room_id;
        $post->message          = $message;

        if($allow_commands)
        {
            //Perform slashcommands on post
            $post = Burlesque_Commands($post);
        }

        //Apply BBCode to message
        $message = Burlesque_BBCode($message);

        $post->id = $this->Database->add_post($post, $room->id,
		                              $this->InputData->data->message);

        return array("post"=>$post->toArray(), "user"=>$user->toArray());
    }

    function getPosts()
    {
        $post_array = array();
        $posts = $this->Database->get_posts($this->InputData->load);
        foreach($posts as $_post_data)
        {
            $post_array[] = Post::fromDBResult($_post_data)->toArray();
        }

        return $post_array;
    }
}

$input_data = json_decode(file_get_contents("php://input"));

$Burlesque = new Burlesque($config, $input_data);
$Burlesque->process();

header('Content-Type','application/json; charset=UTF-8');
echo json_encode($Burlesque->Output);
?>
