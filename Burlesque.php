<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/Burlesque_Config.php');
require_once('xf_integrations.php');
require_once('classes.php');	
require_once('database.php');
		
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
                $this->user_color = $this->InputData->post->color;
                $this->user_font = $this->InputData->post->font;
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
        //Apply BBCode to message
        $message = $this->post_bbcode($message);
        
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
        $post->message          = $message;
        
        //Perform slashcommands on post
        $post = $this->post_actions($post);
        
        $post->id = $this->Database->add_post($post, $room->id, 
		                              $this->InputData->post->message);
        
        return array("post"=>$post->toArray(), "user"=>$user->toArray());
    }
    
    function post_bbcode($post_message)
    {
        // Patterns
        $pat = array();
        $pat[] = '/\[url\](.*?)\[\/url\]/isU';         // URL Type 1
        $pat[] = '/\[url=(.*?)\](.*?)\[\/url\]/isU';   // URL Type 2
        $pat[] = '/\[b\](.*?)\[\/b\]/isU';             // bold
        $pat[] = '/\[i\](.*?)\[\/i\]/isU';             // italic
        $pat[] = '/\[u\](.*?)\[\/u\]/isU';             // underline
        $pat[] = '/\[s\](.*?)\[\/s\]/isU';             // striike
        $pat[] = '/\[spoil\](.*?)\[\/spoil\]/isU';     // spoiler
        $pat[] = '/\[color=(.*?)\](.*?)\[\/color\]/isU'; // color
        $pat[] = '/\[font=(.*?)\](.*?)\[\/font\]/isU';   // font
        $pat[] = '/\[rainbow\](.*?)\[\/rainbow\]/isU';    // Rainbow effect
        
        // Replacements
        $rep = array();
        $rep[] = '<a href="$1">$1</a>';             // URL Type 1
        $rep[] = '<a href="$1">$2</a>';             // URL Type 2
        $rep[] = '<b> $1 </b>';                     // Bold
        $rep[] = '<i> $1 </i>';                     // Italic
        $rep[] = '<u> $1 </u>';                     // Underline
        $rep[] = '<span style="text-decoration: line-through;">$1</span>'; // Strike
        $rep[] = '<span class="spoiler">$1</span>'; //Spoler
        $rep[] = '<span style="font-color: $1;">$2</span>';  //Color
        $rep[] = '<span style="font-family: $1, Verdana, sans-serif;">$2</span>';  //Font
        $rep[] = '<span class="rainbow">$1</span>'; //Rainbow
        
        return preg_replace($pat, $rep, $post_message);
    }
    
    function post_actions($post)
    {
        if(!substr($post->message, 0, 1) == "/")
            return $post;
        
        $post_message = $post->message;
        
        $action = strtolower(trim(strtok($post_message, ' '), '/'));
        switch($action)
        {
            case "gm":  // /gm message
                $post->prefix           = "GM";
                $post->prefix_color     = "#0088aa";
                $post->sender           = "";
                $post->message          = strtok("\n");
                break;
            case "nar": // /nar message
                $post->prefix           = "Narrator";
                $post->prefix_color     = "#00aa88";
                $post->sender           = "";
                $post->message          = strtok("\n");
                break;
            case "chat": // /chat message
                $post->prefix           = "Chat";
                $post->prefix_color     = "#FFFFFF";
                $post->sender           = "";
                $post->message          = strtok("\n");
                break;
            case "char": // /char name:message
                $post->prefix           = "Char";
                $post->prefix_color     = "#c0c0c0";
                $post->sender           = strtok(":");
                $post->message          = strtok("\n");
                break;
            case "me":  // /me message
            case "act":  // /me message
            case "do":  // /me message
                $post->prefix           = ucfirst($action);
                $post->prefix_color     = $post->color;
                $post->message          = strtok("\n");
                break;
            case "pref": // /pref prefix:message
                $post->prefix           = ucfirst(strtolower((strtok(":")));
                if(strlen($post->prefix) > 12)
                    $post->prefix = substr($post->prefix, 0, 11);
                $post->prefix_color     = $post->color;
                $post->message          = strtok("\n");
                break;
            case "color":
                $post->prefix           = "Color";
                $post->prefix_color     = "#c0c0c0";
                $post->message          = "has chosen a new color.";
                $this->user_color = strtok("\n");
                break;
            case "font":
                $post->prefix           = "Font";
                $post->prefix_color     = "#c0c0c0";
                $post->message          = "has chosen a new font.";
                $this->user_font = strtok("\n");
                break;
            case "roll":
                //get die-roll arguments: [num]d[sides]{e[+/-each]}{t[+/-tot]}{l[low]][h[high]}
                $filter = '/(?P<number>\d+)d(?P<sides>\d+)(?:e(?P<each>[-+]?\d+))?(?:t(?P<total>[-+]?\d+))?/';
                preg_match($filter, trim(strtok("\n")), $matches);
                
                $number = min(max($matches['number'], 1), 100);               
                $sides  = min(max($matches['sides'], 2), 1000);
                $each = 0
                $total = 0
                    
                if(isset($matches['each']) && is_numeric($matches['each']))
                    $each   = min(max($matches['each'], -100), 100);
                if(isset($matches['total']) && is_numeric($matches['total']))
                    $total  = min(max($matches['total'], -100), 100);
                    
                $message = "has rolled $number ${sides}-sided dice ";
                if(is_int($each) && $each != 0)
                    $message .=",$each to each ";
                if(is_int($total) && $total != 0)
                    $message .=",$total to total ";
                $message .="with results: [";
                
                $roll_min = 1100; //Max is 1000e100 for 1100 per roll
                $roll_max = 0;
                $roll_sum = 0;
                for($d = 0; $d < $number; $d++)
                {
                    $roll = rand(1, $sides) + $each;
                    $message .= "$roll";
                    if($d < $number -1)
                        $message .=", ";
                    $roll_sum += $roll;
                    $roll_min = min($roll_min, $roll);
                    $roll_max = max($roll_max, $roll);
                }
                $roll_avg = round(($roll_sum+$total)/$number);
                if($total != 0)
                     $roll_sum = $roll_sum . $total . "(".$roll_sum+$total.")";
                $message .="] {Total: $roll_sum; Average: $roll_avg; Low: $roll_min; High: $roll_max}";
                
                $post->prefix           = "Roll";
                $post->prefix_color     = "#804000";
                $post->message          = $message;
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
