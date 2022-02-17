<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/hidroponik.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Nodemcu_log($db);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// The request is using the POST method
		$data = json_decode(file_get_contents("php://input"));
		$item->suhu = $data->suhu;
		$item->nutrisi = $data->nutrisi;
		$item->air = $data->air;
		$item->pH = $data->pH;
	} 
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET'){
		// The request is using the GET method
		$item->suhu = isset($_GET['suhu']) ? $_GET['suhu'] : die('wrong structure!');
		$item->nutrisi = isset($_GET['nutrisi']) ? $_GET['nutrisi'] : die('wrong structure!');
		$item->air = isset($_GET['air']) ? $_GET['air'] : die('wrong structure!');
		$item->pH = isset($_GET['pH']) ? $_GET['pH'] : die('wrong structure!');
	}else {
		die('wrong request method');
	}
	
    if($item->createLogData()){
        echo 'Data created successfully.';
    } else{
        echo 'Data could not be created.';
    }
?>