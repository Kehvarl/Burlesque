<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/../config/Burlesque_Config.php');
	require_once('xf_integrations.php');
	require_once('classes.php');	
	require_once('database.php');
		
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
        echo($db->error);
	}
		
	//Create Tables
	$db->setup();
	
	$room = new Room();
	$room->room = "Main";
	$room->color = "#008000";
	$room->font = "Times New Roman";
	$room->allow_alias = false;
	$room->is_public = true;
	$room->description = 'The Main Room for all chatting';
	$room->id = $db->add_room($room);
	/*
	$room = new Room();
	$room->room = "RP Tower";
	$room->color = "#800080";
	$room->font = "Papyrus";
	$room->allow_alias = true;
	$room->is_public = true;
	$room->description = 'The place to be someone else!';
	$room->id = $db->add_room($room);
	
	$room = new Room();
	$room->room = "Secret Cave";
	$room->color = "#008080";
	$room->font = "Lucida Console";
	$room->allow_alias = true;
	$room->is_public = false;
	$room->description = 'The secret place to be someone else!';
	$room->id = $db->add_room($room);
	
	$room = new Room();
	$room->room = "Admin Team";
	$room->color = "#808080";
	$room->font = "Arial";
	$room->allow_alias = false;
	$room->is_public = false;
	$room->description = 'CONSPIRACY!';
	$room->id = $db->add_room($room);
	
	$user = new User();
	$user->forum_id = $visitor->get('user_id');
	$user->forum_name = $visitor->get('username');
	$user->display_name = $visitor->get('username');
	$user->room_id = 1;
	$db->add_user($user, 1);
	$user->room_id = 2;
	$db->add_user($user, 2);
	$user->room_id = 3;
	$db->add_user($user, 3);
	//$user->room_id = 4;
	//$db->add_user($user, 4);
	*/
	
	$color_file = file($_SERVER['DOCUMENT_ROOT'].'/../config//colorlist.dat');
	$color_sort = 1;
	foreach($color_file as $color_line)
	{
		list($name,$code) = preg_split("/,/", $color_line);
		$color = new Color();
		$color->setName($name);
		$color->setCode($code);
		$color->sort = $color_sort;
		$color_sort += 1;
		$db->add_color($color);
	}
    
	if($db->error)
	{
		error_log($db->error);
        echo($db->error);
	}
    
    echo("Install Completed\n");
    
	
	//header('Content-Type','application/json; charset=UTF-8');
	//echo json_encode($ret);
?>