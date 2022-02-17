<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/database.php';
    include_once '../class/hidroponik.php';

    $database = new Database();
    $db = $database->getConnection();
	
	if(isset($_GET['id'])) {
		$item = new Nodemcu_log($db);
		$item->id = isset($_GET['id']) ? $_GET['id'] : die();
  
		$item->getSingleLogData();

		if($item->created_at != null){
			// create array
			$emp_arr = array(
				"id" =>  $item->id,
				"suhu" => $item->suhu,
				"nutrisi" => $item->nutrisi,
				"air" => $item->air,
				"pH" => $item->pH,
				"created_at" => $item->created_at
			);
		  
			http_response_code(200);
			echo json_encode($emp_arr);
		}
		  
		else{
			http_response_code(404);
			echo json_encode("Data not found.");
		}
		
	} else {
		$items = new Nodemcu_log($db);
		$stmt = $items->getLogData();
		$itemCount = $stmt->rowCount();
		//echo json_encode($itemCount);

		if($itemCount > 0){
			
			$dataArr = array();
			$dataArr["data_log"] = array();
			$dataArr["dataCount"] = $itemCount;

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				//$created = date("G:i ", strtotime($row["created_at"])) . " ";
				$e = array(
					"id" => $id,
					"suhu" => $suhu,
					"nutrisi" => $nutrisi,
					"air" => $air,
					"pH" => $pH,
					"created_at" => $created_at
				);

				array_push($dataArr["data_log"], $e);
			}
			echo json_encode($dataArr);
		}

		else{
			http_response_code(404);
			echo json_encode(
				array("message" => "No record found.")
			);
		}
	}
	
    
?>