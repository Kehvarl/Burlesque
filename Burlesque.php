<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/Burlesque_Config.php');
require_once('xf_integrations.php');
require_once('classes.php');	
require_once('database.php');
require_once("jbbcode-1.2.0/Parser.php");
		
class Burlesque
{
    private $Visitor;
    private $Session;
    private $InputData;

    private $Database;
    
    private $user_color;
    private $user_font;
    
    public $Output;
    
    function __construct($config, $input_data)
    {
        //Connect to Xenforo
        $this->Visitor = initialize($_SERVER['DOCUMENT_ROOT']);
        $this->Session = XenForo_Session::startPublicSession();
        
        //Setup Datetime module
        $this->DT = new DateTime("now", 
            new DateTimeZone($this->Visitor->get('timezone'))); 
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
        
        //Get input from user
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
                                    'color'=>$this->InputData->login->color);
                $this->Output['posts'] = $this->getPosts();
                break;
            case 'logout':
                break;
            case 'post':
                $this->Output = $this->doPost();
                $this->Output['user']['settings'] = array(
                                    'color'=>$this->InputData->post->color);
                $this->Output['posts'] = $this->getPosts();
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
                                    $this->Visitor->get('user_id'));
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
        $room = $this->getRoom($this->InputData->login->room);
        $display_name   = $this->InputData->login->display_name;
        $forum_name     = $this->Visitor->get('username');
        if(!$room->allow_alias)
        {
            $display_name = $forum_name;
        }
        $user = User::fromDBResult(
                    $this->Database->get_user(  $display_name, 
                                                $room->id));
        if(!$user->id)
        {
            $user = new User();
            $user->display_name     = $display_name;
            $user->forum_id         = $this->Visitor->get('user_id');
            $user->forum_name       = $forum_name;
            $user->room_id          = $room->id;
            $user->id               = $this->Database->add_user($user, 
                                                                $room->id);
        }
        else
        {
            $this->DT->setTimestamp(time());
            $user->login = $this->DT->format('Y-m-d H:i:s');
            $user->last_post = $this->DT->format('Y-m-d H:i:s');
            $this->DT->setTimestamp(0);
            $user->logout = $this->DT->format('Y-m-d H:i:s');
            $this->Database->update_user($user);
            $user = User::fromDBResult(
                                $this->Database->get_user_by_id($user->id));
        }
        
        $this->Session->set('room'.$room->id.'user'.$user->id, array(
                'color'=> $this->InputData->login->color,
                'font' => $this->InputData->login->font,
                'name' => $user->display_name,
                'id'   => $user->id
        ));
        $this->Session->save();
        
        $post = new Post();
        $post->prefix           = "Login";
        $post->prefix_color     = "#33cc33";
        $post->sender_id        = $user->id;
        $post->sender_forum_id  = $user->forum_id;
        $post->sender_forum     = $user->forum_name;
        $post->sender           = $user->display_name;
        $post->target_id        = 0;
        $post->target           = "";
        $post->color            = $this->InputData->login->color;
        $post->font             = $this->InputData->login->font;
        $post->message          = $this->InputData->login->message;
	
        $this->Database->add_post($post, $room->id, 
                            $this->InputData->login->message);
        
        return $user->toArray();
    }
    
    function doPost()
    {
        $message = $this->InputData->post->message;
        
        //Verify message has no illegal characters
        $message = htmlentities($message, ENT_QUOTES | ENT_IGNORE, "UTF-8");
        
        //Process any bbcode, custom tags, or URLs
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\BasicCodeDefinitionSet());
        $parser->parse($message);
        
        $room = $this->getRoom($this->InputData->post->room_id);
        $display_name = $this->InputData->post->display_name;
        $user = User::fromDBResult($this->Database->get_user(   $display_name, 
                                                                $room->id));
        $post = new Post();
        $post->prefix           = "";
        $post->prefix_color     = "";
        $post->sender_id        = $user->id;
        $post->sender_forum_id  = $user->forum_id;
        $post->sender_forum     = $user->forum_name;
        $post->sender           = $user->display_name;
        $post->target_id        = 0;
        $post->target           = "";
        $post->color            = $this->InputData->post->color;
        $post->font             = $this->InputData->post->room_id;
        $post->message          = $parser->getAsHtml();
        
        $post = $this->post_actions($post);
        
        $post->id = $this->Database->add_post($post, $room->id, 
		                              $this->InputData->post->message);
        
        return array("post"=>$post->toArray(), "user"=>$user->toArray());
    }
    
    function post_actions($post)
    {
        if(!substr($post->message, 0, 1) == "/")
            return $post;
        
        $post_message = $post->message;
        
        $action = trim(strtok($post_message, ' '), '/');
        switch(strtolower($action))
        {
            case "gm":  // /gm message
                $post->prefix           = "GM";
                $post->prefix_color     = "#0088aa";
                $post->sender           = "";
                $post->message          = strtok("\n");
                break;
            case "nar": // /nar message
                $post->prefix           = "NARRATOR";
                $post->prefix_color     = "#00aa88";
                $post->sender           = "";
                $post->message          = strtok("\n");
                break;
            case "chat": // /chat message
                $post->prefix           = "CHAT";
                $post->prefix_color     = "#FFFFFF";
                $post->sender           = "";
                $post->message          = strtok("\n");
                break;
            case "char": // /char name:message
                $post->prefix           = "CHAR";
                $post->prefix_color     = "#c0c0c0";
                $post->sender           = strtok(":");
                $post->message          = strtok("\n");
                break;
            case "me":  // /me message
                $post->prefix           = "ME";
                $post->prefix_color     = $post->color;
                $post->message          = strtok("\n");
                break;
            case "pref": // /pref prefix:message
                $post->prefix           = strtoupper(strtok(":"));
                if(strlen($post->prefix) > 12)
                    $post->prefix = substr($post->prefix, 0, 11);
                $post->prefix_color     = $post->color;
                $post->message          = strtok("\n");
                break;
            case "color":
                $post->prefix           = "COLOR";
                $post->prefix_color     = "#c0c0c0";
                $post->message          = "has chosen a new color.";
                $this->user_color = strtok("\n");
                break;
            case "font":
                $post->prefix           = "FONT";
                $post->prefix_color     = "#c0c0c0";
                $post->message          = "has chosen a new font.";
                $this->user_font = strtok("\n");
                break;
        }
        return $post;
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
