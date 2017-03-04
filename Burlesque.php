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
    
    public $Output;
    
    function __construct($config, $input_data)
    {
        $this->Visitor = initialize($_SERVER['DOCUMENT_ROOT']);
    
        $this->Session = XenForo_Session::startPublicSession();
        
        $this->InputData = $input_data;
        
	$this->DT = new DateTime("now", 
		new DateTimeZone($this->Visitor->get('timezone'))); 
            
        $timestamp = time();
        $this->DT->setTimestamp($timestamp);
        
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
	$user = User::fromDBResult($this->Database->get_user($display_name, 
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
	$user = User::fromDBResult($this->Database->get_user($display_name, 
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
        
	$post->id = $this->Database->add_post($post, $room->id, 
		                              $this->InputData->post->message);
        
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
