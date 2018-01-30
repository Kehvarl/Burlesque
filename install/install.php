<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/../config/Burlesque_Config.php');
	require_once('../includes/xf_integrations.php');
	require_once('../includes/classes.php');
  require_once('../includes/Burlesque_Database.php');
  require_once('../includes/db/Burlesque_Database_Colors.php');
	require_once('Burlesque_Database_Install.php');

	$visitor = initialize();

	$session = XenForo_Session::startPublicSession();

	//$input_data = json_decode(file_get_contents("php://input"));

	//$dt = new DateTime("now", new DateTimeZone($visitor->get('timezone')));

	//$timestamp = time();
	//$dt->setTimestamp($timestamp);

	$db = new Burlesque_DB_Connection($config['db']['username'],
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

    $dbCreator = new Burlesque_DB_Setup($db->table_prefix);
    $dbColor = new Burlesque_DB_Colors($db->table_prefix);

    $dbCreator->setup($db); //Create Tables

    //Add Main Room
	$room = new Room();
	$room->room = "Main";
	$room->color = "#008000";
	$room->font = "Times New Roman";
	$room->allow_alias = false;
	$room->is_public = true;
	$room->description = 'The Main Room for all chatting';
	$room->id = $db->add_room($room);

	//Load coloslist and populate Colors table
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
        $dbColor->add_color($db, $color);
	}

	if($db->error)
	{
		error_log($db->error);
        echo($db->error);
	}

    echo("Install Completed\n");
?>
