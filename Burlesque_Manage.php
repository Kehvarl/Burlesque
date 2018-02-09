<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/../config/Burlesque_Config.php');
	require_once('includes/xf_integrations.php');
	require_once('includes/classes/Burlesque_Color.php');
	require_once('includes/classes/Burlesque_Post.php');
	require_once('includes/classes/Burlesque_Room.php');
	require_once('includes/classes/Burlesque_User.php');
	require_once('includes/db/Burlesque_Database_Tools.php');

	$visitor = initialize();

	$session = XenForo_Session::startPublicSession();

	$input_data = json_decode(file_get_contents("php://input"));

	$dt = new DateTime("now", new DateTimeZone($visitor->get('timezone')));

	$timestamp = time();
	$dt->setTimestamp($timestamp);

	$db = new Burlesque_DB_Tools($config['db']['username'],
								 $config['db']['password'],
								 $config['db']['database'],
								 $config['db']['host'],
								 $config['db']['port'],
								 $config['db']['table_prefix']);
	if($db->error)
	{
		error_log($db->error);
	}

    if(!$visitor->hasAdminPermission('node'))
            $input_data->action = "error";

    $return = array();
    switch($input_data->action)
    {
        case 'init':
            $return['rooms'] = getAllRooms($db);
            $return['colors'] = getColors($db);
            break;
        case 'getRooms':
            $return['rooms'] = getAllRooms($db);
            break;
        case 'saveRoom':
            $room = Room::fromRoom($input_data->room);
            if($room->id == -1)
                $room->id = $db->add_room($room);
            else
                $db->update_room($room);
            $return['rooms'] = getAllRooms($db);
            break;
        case 'deleteRoom':
            $db->delete_room($input_data->room->id);
            $return['room'] = Room::fromRoom($input_data->room)->toArray();
            $return['rooms'] = getAllRooms($db);
            break;
        case 'getColors':
            $return['colors'] = getColors($db);
            break;
        case 'addColor':
            $color = Color::fromColor($input_data->color, $db->get_color_next_sort());
            $color->id = $db->add_color($color);
            $return['colors'] = getColors($db);
            break;
        case 'editColor':
            $color = Color::fromColor($input_data->color);
            $color->id = $db->update_color($color);
            $return['colors'] = getColors($db);
            break;
        case 'editMultipleColor':
            return editMultipleColors($db, $input_data->colors);
            break;
        case 'deleteColor':
            $db->delete_color($input_data->color->id);
            $return['colors'] = getColors($db);
            break;
        case 'getUsers':
            $return['users'] = getUsers($db, $input_data->room->id);
            break;
        default:
            $return['errors'] = "Input Validation Error";
    }
    if($db->error)
        $return['error'] = $db->error;

    header('Content-Type','application/json; charset=UTF-8');
    echo json_encode($return);

    function getAllRooms($db)
    {
        $rooms_list = array();
        $rooms = $db->get_all_room_list();
        $rooms_list[] = Room::placeholder("Add New")->toArray();
        foreach($rooms as $_room)
        {
            $rooms_list[] = Room::fromDBResult($_room)->toArray();
        }
        return $rooms_list;
    }

    function getColors($db)
    {
        $color_list = array();
        $colors = $db->get_color_list();
        foreach($colors as $_color)
        {
            $color_list[] = Color::fromDBResult($_color)->toArray();
        }
        return $color_list;
    }

    function getUsers($db, $room_id)
    {
        $users_list = array();
        $users = $db->get_user_list($room_id);
        foreach($users as $_user)
        {
            $users_list[] = User::fromDBResult($_user)->toArray();
        }
        return $users_list;
    }

    function editMultipleColors($db, $colors)
    {
        foreach($colors as $_color)
        {
            $color = Color::fromColor($_color, $_color->sort);
            $db->update_color($color);
        }
        return getColors($db);
    }
?>
