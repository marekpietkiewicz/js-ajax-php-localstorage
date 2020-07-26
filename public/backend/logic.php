<?php declare(strict_types=1);

	require_once dirname(__DIR__) . '/../vendor/autoload.php';
	use App\FamilyMembers\FamilyMembers as FamilyMembers;

	try {
		$FamilyMembers = new FamilyMembers($_GET, (($_POST)? $_POST : json_decode(file_get_contents('php://input'), true)) );
		$res = $FamilyMembers->getMembersList();

		header('Content-Type: application/json');
		echo json_encode(["message" => "Bravo!", "result" => $res]);
		exit;

	} catch (Exception $e) {
		http_response_code($e->getCode());
		echo json_encode(["message" => $e->getMessage()]);
		exit;
	}
?>