<?php declare(strict_types=1);

	require_once dirname(__DIR__) . '/../vendor/autoload.php';

	try {
		$FamilyCalculator = new FamilyCalculator();
		$AllowedActions = $FamilyCalculator->AllowedActions;
		$reqData = file_get_contents('php://input');
		$jsonData= null;

		//extract here json data for calculation
		if(isset($reqData) && is_string($reqData) && is_array(json_decode($reqData, true)) && (json_last_error() == JSON_ERROR_NONE)){
			$jsonData = json_decode($reqData);
			$jsonData = $jsonData->data;
		}

		if	(	//do we have a proper request at all
				(isset($_GET['action'])  && array_search($_GET['action'], array_column($AllowedActions, 'action')) !== false)
				//check if it is a proper json 
				&& (isset($jsonData) && is_string($jsonData) && is_array(json_decode($jsonData, true)) && (json_last_error() == JSON_ERROR_NONE)) 
		){
			$res = $FamilyCalculator->addFamilyMember($_GET['action'], $jsonData);
			$cost = $FamilyCalculator->SumUpCosts($res);
			$totalMembers = $FamilyCalculator->SumUpTotalMembers($res);
			
			$res = ["data" => [...$res], "numbers" => ["cost" => $cost, "totalMembers" => $totalMembers]];

			//success response
			header('Content-Type: application/json');
			echo json_encode(["message" => "Bravo!", "result" => $res]);
			exit;
		}else{
			header('Content-Type: application/json');
			http_response_code(400);
			echo json_encode(["message" => "Sorry, bad request"]);
			exit;
		}

	} catch (Exception $e) {
		http_response_code(406);
		echo json_encode(["message" => $e->getMessage()]);
		exit;
	}
?>