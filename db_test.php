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

    echo ($visitor->get('user_id'));
    print_r($db->get_room_list($visitor->get('user_id')));
    $user = User::fromDBResult($db->get_user("Kehvarl", "1"));
    echo "load\n";
    print_r($user->display_name);
    $dt->setTimestamp(time());
    $user->login = $dt->format('Y-m-d H:i:s');
    $dt->setTimestamp(0);
    $user->last_post = $dt->format('Y-m-d H:i:s');
    $user->logout = $dt->format('Y-m-d H:i:s');
    echo "edit\n";
    print_r($user);
    $db->update_user($user);
    echo "update\n";
    print_r($user);
    $user = $db->get_user_by_id($user->id);
    echo "load\n";
    print_r($user);

?>
